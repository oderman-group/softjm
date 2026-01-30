<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Producto extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'productos';
    public static $primaryKey = 'prod_id';
    public static $tableAs    = 'prod';

    public const PROD_UTILIDAD = 'prod_utilidad';
    public const PROD_COSTO    = 'prod_costo';

    public static function CalcularPrecioLista(string $costo, string $utilidadSobreCien) {
        if (empty($costo) || empty($utilidadSobreCien)) {
            return 0;
        }

        return $costo / (1 - $utilidadSobreCien);
    }

    /**
     * Devuelve el número de procesos comerciales (Documentos) en las cuales está incluido el producto
     */
    public static function numeroProcesosComerciales(int $id, $conexionBdPrincipal) {

        $consultaCotizProducto = $conexionBdPrincipal->query("SELECT * FROM cotizacion_productos WHERE czpp_producto='".$id."'");
        return $consultaCotizProducto->num_rows;

    }

    /**
     * Devuelve el número de combos en las cuales está incluido el producto
     */
    public static function numeroCombos(int $id, $conexionBdPrincipal) {

        $consultaCotizProducto = $conexionBdPrincipal->query("SELECT * FROM combos_productos WHERE copp_producto='".$id."'");
        return $consultaCotizProducto->num_rows;

    }

    /**
     * 
     */
    public static function sincronizarExistenciasConBodegas($idProducto, $conexionBdPrincipal) {
        $consultaExistencia = $conexionBdPrincipal->query("SELECT SUM(prodb_existencias) as totalExistenciasProducto 
        FROM productos_bodegas 
        WHERE prodb_producto='".$idProducto."'");
        $exis = mysqli_fetch_array($consultaExistencia, MYSQLI_BOTH);

        $conexionBdPrincipal->query("UPDATE productos SET prod_existencias='".$exis['totalExistenciasProducto']."', prod_ultima_actualizacion=now() 
        WHERE prod_id='".$idProducto."'");
    }

    // Función auxiliar para registrar errores (ajusta la ruta de tu log)
    public static function logError($message) {
        error_log(date('[Y-m-d H:i:s]') . ' ' . $message . PHP_EOL, 3, '/error.log');
    }

    /**
     * Descuenta una cantidad de existencias de un producto de múltiples bodegas.
     * Las existencias se descuentan de las bodegas disponibles, priorizando por 'prodb_id' ascendente.
     *
     * @param int $idProducto El ID del producto.
     * @param int $cantidadASacar La cantidad de existencias a descontar.
     * @param mysqli $conexionBdPrincipal La conexión a la base de datos MySQLi.
     * @param bool $transaccionExterna Si true, el llamador ya tiene una transacción abierta: no se llama begin/commit/rollback aquí.
     * @return array Un array asociativo con 'status' (success/error), 'message' y 'remaining_stock_global' (si aplica).
     */
    public static function sacarExistenciasProductoMultiBodega(
        int $idProducto,
        int $cantidadASacar,
        $conexionBdPrincipal,
        bool $transaccionExterna = false
    ): array {
        // 1. Validar entradas básicas
        if ($cantidadASacar <= 0) {
            return ['status' => 'error', 'message' => 'La cantidad a sacar debe ser un número positivo.'];
        }

        // Usar un ID de usuario predeterminado si no se proporciona
        $idUsuarioActualizacion = $_SESSION['id']; // Ajusta el ID por defecto si es necesario

        if (!$transaccionExterna) {
            $conexionBdPrincipal->begin_transaction();
        }

        try {
            // 2. Sumar las existencias totales de todas las bodegas para el producto
            $sqlTotalStock = "SELECT SUM(prodb_existencias) AS total_existencias
                            FROM productos_bodegas
                            WHERE prodb_producto = ?";
            $stmtTotalStock = $conexionBdPrincipal->prepare($sqlTotalStock);

            if ($stmtTotalStock === false) {
                self::logError("Error al preparar la consulta de total de existencias: " . $conexionBdPrincipal->error);
                throw new Exception("Error interno al preparar la consulta de stock total.");
            }

            $stmtTotalStock->bind_param("i", $idProducto);
            $stmtTotalStock->execute();
            $resultTotalStock = $stmtTotalStock->get_result();
            $rowTotalStock = $resultTotalStock->fetch_assoc();
            $totalExistenciasGlobal = (int)$rowTotalStock['total_existencias']; // Ya sabemos que es entero

            $stmtTotalStock->close();

            // 3. Verificar si hay existencias suficientes globalmente
            if ($totalExistenciasGlobal < $cantidadASacar) {
                if (!$transaccionExterna) {
                    $conexionBdPrincipal->rollback();
                }
                return ['status' => 'error', 'message' => 'No hay existencias suficientes. Disponibles en total: ' . $totalExistenciasGlobal];
            }

            // 4. Obtener las existencias de cada bodega para el producto, ordenadas para el descuento
            // Se puede ordenar por prodb_id o por prodb_bodega, o por alguna prioridad si la tienes.
            // Aquí se ordena por prodb_id ascendente (generalmente el más antiguo o de menor ID)
            $sqlGetBodegaStock = "SELECT prodb_id, prodb_existencias, prodb_bodega
                                FROM productos_bodegas
                                WHERE prodb_producto = ? AND prodb_existencias > 0
                                ORDER BY prodb_id ASC"; // O ORDER BY prodb_bodega_prioridad ASC, prodb_existencias DESC

            $stmtGetBodega = $conexionBdPrincipal->prepare($sqlGetBodegaStock);

            if ($stmtGetBodega === false) {
                self::logError("Error al preparar la consulta de stock por bodega: " . $conexionBdPrincipal->error);
                throw new Exception("Error interno al preparar la consulta de stock por bodega.");
            }

            $stmtGetBodega->bind_param("i", $idProducto);
            $stmtGetBodega->execute();
            $resultBodegaStock = $stmtGetBodega->get_result();
            
            $cantidadPendienteADescontar = $cantidadASacar;
            $actualizacionesExitosas = 0; // Para contar si se logró descontar algo

            // Iterar sobre las bodegas y descontar
            while ($bodega = $resultBodegaStock->fetch_assoc()) {

                if ($cantidadPendienteADescontar <= 0) { // <-- Condición aquí
                    break; // Si ya descontamos todo lo necesario, salimos del bucle
                }

                $prodbId = (int)$bodega['prodb_id'];
                $existenciasEnBodega = (int)$bodega['prodb_existencias'];

                if ($existenciasEnBodega === 0) {
                    continue; // Saltar si la bodega no tiene stock
                }

                // Cantidad a descontar de esta bodega específica
                $cantidadADescontarDeEstaBodega = min($cantidadPendienteADescontar, $existenciasEnBodega);
                $nuevasExistenciasEnBodega = $existenciasEnBodega - $cantidadADescontarDeEstaBodega;

                // 5. Actualizar existencias en la bodega actual
                $sqlUpdateBodega = "UPDATE productos_bodegas 
                                    SET prodb_existencias = ?, 
                                        prodb_fecha_actualizacion = NOW(), 
                                        prodb_usuario_actualizacion = ? 
                                    WHERE prodb_id = ?";
                $stmtUpdateBodega = $conexionBdPrincipal->prepare($sqlUpdateBodega);

                if ($stmtUpdateBodega === false) {
                    self::logError("Error al preparar la actualización de bodega ID " . $prodbId . ": " . $conexionBdPrincipal->error);
                    throw new Exception("Error interno al preparar la actualización de bodega.");
                }

                $stmtUpdateBodega->bind_param("iii", $nuevasExistenciasEnBodega, $idUsuarioActualizacion, $prodbId);

                if (!$stmtUpdateBodega->execute()) {
                    self::logError("Error al ejecutar la actualización para prodb_id " . $prodbId . ": " . $stmtUpdateBodega->error);
                    throw new Exception("Fallo al descontar existencias de la bodega ID " . $prodbId);
                }

                $stmtUpdateBodega->close(); // Cierra el statement después de cada uso
                
                $cantidadPendienteADescontar -= $cantidadADescontarDeEstaBodega;
                $actualizacionesExitosas++;
            }

            $stmtGetBodega->close(); // Cierra el statement principal de obtención de bodegas

            // 6. Confirmar la transacción si todo el descuento se realizó
            if ($cantidadPendienteADescontar === 0) {
                if (!$transaccionExterna) {
                    $conexionBdPrincipal->commit();
                }
                $remainingGlobal = $totalExistenciasGlobal - $cantidadASacar;
                return ['status' => 'success', 'message' => 'Existencias descontadas exitosamente.', 'remaining_stock_global' => $remainingGlobal];
            } else {
                if (!$transaccionExterna) {
                    $conexionBdPrincipal->rollback();
                }
                throw new Exception("No se pudo descontar la cantidad completa de las bodegas disponibles.");
            }

        } catch (Exception $e) {
            if (!$transaccionExterna) {
                $conexionBdPrincipal->rollback();
            }
            self::logError("Excepción en sacarExistenciasProductoMultiBodega: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Fallo al procesar la operación: ' . $e->getMessage()];
        }
    }

    /**
     * 
     */
    public static function procesarProductosEnCombos(
        array  $itemActual,
        string &$nombreProducto,
        bool   &$esValorOk,
        array  &$combosAsociados
    ) {
        if (!empty($itemActual['czpp_combo'])) {
            $esValorOk = false;
            $nombreProducto = !empty($itemActual['prod_nombre']) ? 
                                $itemActual['prod_nombre'] ." <br><b>(En combo #".$itemActual['czpp_combo']." con valor de $".number_format($itemActual['czpp_precio_original'],0,",",".").")</b>" :
                                "";

            if (!isset($combosAsociados[$itemActual['czpp_combo']])) {
                $valorTotalDelCombo       = !empty($itemActual['czpp_precio_original']) ? (float)$itemActual['czpp_precio_original'] : 0;
                $valorDescuentoDelCombo   = $valorTotalDelCombo * ($itemActual['czpp_descuento'] / 100);
                $valorFinalDelCombo       = $valorTotalDelCombo - $valorDescuentoDelCombo;
                $valorIvaDelCombo         = $valorFinalDelCombo * ($itemActual['czpp_impuesto'] / 100);
                $valorFinalDelComboConIva = $valorFinalDelCombo + $valorIvaDelCombo;

                $combosAsociados[$itemActual['czpp_combo']] = [
                    'valor_combo_total'         => $valorTotalDelCombo,
                    'descuento_combo'           => $itemActual['czpp_descuento'],
                    'valor_descuento_combo'     => $valorDescuentoDelCombo,
                    'valor_final_combo'         => $valorFinalDelCombo,
                    'iva_porcentaje_combo'      => $itemActual['czpp_impuesto'],
                    'valor_iva_combo'           => $valorIvaDelCombo,
                    'valor_final_combo_con_iva' => $valorFinalDelComboConIva
                ];
            }
        }
    }

    /**
     * 
     */
    public static function productoMasVendido($conexionBdPrincipal) {
        $sql = "SELECT 
            czpp_producto as id_producto, 
            prod_nombre as nombre_producto, 
            SUM(czpp_cantidad) AS total_unidades_vendidas, 
            COUNT(*) AS total_documentos_diferentes 
        FROM 
            cotizacion_productos
        INNER JOIN 
            productos 
            ON prod_id = czpp_producto
        INNER JOIN 
            facturas 
            ON factura_id = czpp_cotizacion 
            AND factura_tipo = ".FACT_TIPO_VENTA."
            AND YEAR(factura_fecha_creacion)=".date("Y")."
        WHERE 
            czpp_tipo = ".CZPP_TIPO_FACT."
        GROUP BY 
            czpp_producto, prod_nombre
        ORDER BY 
            total_unidades_vendidas desc
        LIMIT 1
        ";

        return mysqli_fetch_assoc(mysqli_query($conexionBdPrincipal, $sql));
    }

    /**
     * Sincroniza un producto con Ofima
     * Se llama automáticamente cuando se crea o actualiza un producto
     * 
     * @param int $productoId ID del producto a sincronizar
     * @param mysqli $conexionBdPrincipal Conexión a la base de datos
     * @param int $idEmpresa ID de la empresa
     * @param string $tipoOperacion 'CREATE' o 'UPDATE'
     * @return array Resultado de la sincronización
     */
    public static function sincronizarConOfima($productoId, $conexionBdPrincipal, $idEmpresa, $tipoOperacion = 'UPDATE') {
        // Verificar si la sincronización está activa
        $query = "SELECT apic_activo FROM api_configuracion 
                  WHERE apic_modulo = 'productos' 
                  AND apic_direccion = 'orion_ofima' 
                  AND apic_id_empresa = ? 
                  LIMIT 1";
        
        $stmt = $conexionBdPrincipal->prepare($query);
        $stmt->bind_param("i", $idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0 || $result->fetch_assoc()['apic_activo'] != 1) {
            // Sincronización desactivada, no hacer nada
            return [
                'success' => false,
                'message' => 'Sincronización desactivada'
            ];
        }
        
        // Obtener datos completos del producto
        $query = "SELECT * FROM productos WHERE prod_id = ? AND prod_id_empresa = ?";
        $stmt = $conexionBdPrincipal->prepare($query);
        $stmt->bind_param("ii", $productoId, $idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return [
                'success' => false,
                'error' => 'Producto no encontrado'
            ];
        }
        
        $producto = $result->fetch_assoc();
        
        // Incluir relaciones si existen
        if (!empty($producto['prod_categoria'])) {
            $queryCat = "SELECT catp_nombre FROM productos_categorias WHERE catp_id = ?";
            $stmtCat = $conexionBdPrincipal->prepare($queryCat);
            $stmtCat->bind_param("i", $producto['prod_categoria']);
            $stmtCat->execute();
            $catResult = $stmtCat->get_result();
            if ($catResult->num_rows > 0) {
                $producto['categoria_nombre'] = $catResult->fetch_assoc()['catp_nombre'];
            }
        }
        
        // Sincronizar con Ofima
        require_once RUTA_PROYECTO.'/usuarios/class/ApiOfimaClient.php';
        $apiClient = new ApiOfimaClient($conexionBdPrincipal, $idEmpresa);
        
        return $apiClient->sincronizarProducto($producto, $tipoOperacion);
    }

}