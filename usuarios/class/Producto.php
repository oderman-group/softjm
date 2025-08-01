<?php

class Producto {

    public const PROD_UTILIDAD = 'prod_utilidad';
    public const PROD_COSTO    = 'prod_costo';

    public static function CalcularPrecioLista(string $costo, string $utilidadSobreCien) {
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
     * @return array Un array asociativo con 'status' (success/error), 'message' y 'remaining_stock_global' (si aplica).
     */
    public static function sacarExistenciasProductoMultiBodega(
        int $idProducto, 
        int $cantidadASacar, 
        $conexionBdPrincipal
    ): array {
        // 1. Validar entradas básicas
        if ($cantidadASacar <= 0) {
            return ['status' => 'error', 'message' => 'La cantidad a sacar debe ser un número positivo.'];
        }

        // Usar un ID de usuario predeterminado si no se proporciona
        $idUsuarioActualizacion = $_SESSION['id']; // Ajusta el ID por defecto si es necesario

        // Iniciar una transacción para asegurar la atomicidad de la operación
        $conexionBdPrincipal->begin_transaction();

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
                $conexionBdPrincipal->rollback(); // Revertir la transacción
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
                $conexionBdPrincipal->commit();
                $remainingGlobal = $totalExistenciasGlobal - $cantidadASacar;
                return ['status' => 'success', 'message' => 'Existencias descontadas exitosamente.', 'remaining_stock_global' => $remainingGlobal];
            } else {
                // Esto no debería suceder si totalExistenciasGlobal >= cantidadASacar,
                // pero es una salvaguarda.
                $conexionBdPrincipal->rollback();
                throw new Exception("No se pudo descontar la cantidad completa de las bodegas disponibles.");
            }

        } catch (Exception $e) {
            // Si algo sale mal, revertir la transacción
            $conexionBdPrincipal->rollback();
            self::logError("Excepción en sacarExistenciasProductoMultiBodega: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Fallo al procesar la operación: ' . $e->getMessage()];
        }
    }

}