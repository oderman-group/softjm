<?php   
require_once("../sesion.php");
$idPagina = 374;

require_once RUTA_PROYECTO.'/usuarios/class/Producto.php';

$conexionBdPrincipal->begin_transaction();

//Verificamos que el pedido actual no haya generado ya otra remisión
$generoRemision = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisionbdg WHERE remi_pedido='" . $_GET["id"] . "' AND remi_id_empresa='".$idEmpresa."'"));
if($generoRemision[0]!=""){
    echo "<span style='font-family:arial; text-align:center; color:red;'>Este Pedido ya generó la remision con ID: ".$generoRemision[0].". En la fecha: ".$generoRemision['remi_fecha_creacion']."</div>";
    exit();
}

mysqli_query($conexionBdPrincipal,"INSERT INTO remisionbdg(remi_fecha_propuesta, remi_observaciones, remi_cliente, remi_fecha_vencimiento, remi_vendedor, remi_creador, remi_sucursal, remi_contacto, remi_forma_pago, remi_fecha_creacion, remi_moneda, remi_pedido, remi_estado,remi_id_empresa) SELECT now(), pedid_observaciones, pedid_cliente, pedid_fecha_vencimiento, pedid_vendedor, '" . $_SESSION["id"] . "', pedid_sucursal, pedid_contacto, pedid_forma_pago, pedid_fecha_creacion, pedid_moneda, pedid_id, 1, pedid_id_empresa 
FROM pedidos 
WHERE pedid_id='" . $_GET["id"] . "'");

$idInsert = mysqli_insert_id($conexionBdPrincipal);

$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos
INNER JOIN productos ON prod_id=czpp_producto
WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_PED."' AND czpp_producto!=''");


while ($prod = mysqli_fetch_array($productos)) {

    if ($prod['czpp_orden'] == "") $prod['czpp_orden'] = 1;
    if ($prod['czpp_cantidad'] == "") $prod['czpp_cantidad'] = 1;

    mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega, czpp_descuento, czpp_productos_existencias)VALUES('" . $idInsert . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', '" . $prod['czpp_orden'] . "', '".$prod['czpp_cantidad']."', '" . $prod['czpp_impuesto'] . "', ".CZPP_TIPO_REM.", 1, '" . $prod['czpp_descuento'] . "', '" . $prod['prod_existencias'] . "')");

    $resultado = Producto::sacarExistenciasProductoMultiBodega($prod['czpp_producto'], $prod['czpp_cantidad'], $conexionBdPrincipal);

    if ($resultado['status'] === 'success') {
        Producto::sincronizarExistenciasConBodegas($prod['czpp_producto'], $conexionBdPrincipal);
    } else {
        $conexionBdPrincipal->rollback();
    }
    
    $contador++;
}


//Consultamos los combos de este pedido para extraer sus productos de manera individual
$productosCombos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_PED."' AND czpp_combo!=''");

while ($combo = mysqli_fetch_array($productosCombos, MYSQLI_ASSOC)) { // Usar MYSQLI_ASSOC para mejor acceso por nombre
    // Establecer valores predeterminados si están vacíos
    $ordenCombo = !empty($combo['czpp_orden']) ? $combo['czpp_orden'] : 1;
    $cantidadCombosPedidos = !empty($combo['czpp_cantidad']) ? $combo['czpp_cantidad'] : 1;
    $idImpuestoCombo = !empty($combo['czpp_impuesto']) ? $combo['czpp_impuesto'] : 0; // Asumo un valor por defecto si no hay impuesto
    $descuentoCombo = !empty($combo['czpp_descuento']) ? $combo['czpp_descuento'] : 0; // Asumo un valor por defecto si no hay descuento

    // === INICIO DEL CAMBIO CLAVE ===
    // Verificar si esta línea de pedido es un combo y tiene el JSON de detalles
    if (!empty($combo['czpp_productos_en_combo_generar_pedido'])) {
        $productosDelComboJSON = json_decode($combo['czpp_productos_en_combo_generar_pedido'], true);

        // Verificar si el JSON se decodificó correctamente y es un array
        if (json_last_error() === JSON_ERROR_NONE && is_array($productosDelComboJSON)) {
            // Iterar sobre cada producto dentro del JSON del combo
            foreach ($productosDelComboJSON as $comProd) {

                $resultado = Producto::sacarExistenciasProductoMultiBodega($comProd['id_producto'], $comProd['cantidad_en_combo'], $conexionBdPrincipal);

                if ($resultado['status'] === 'success') {
                    Producto::sincronizarExistenciasConBodegas($comProd['id_producto'], $conexionBdPrincipal);
                } else {
                    $conexionBdPrincipal->rollback();
                }

                $datosDelProductoActual = mysqli_fetch_assoc(
                    mysqli_query($conexionBdPrincipal,"SELECT prod_existencias 
                    FROM productos 
                    WHERE prod_id='".$comProd['id_producto']."'")
                );

                // Cantidad total del producto en la remisión: (cantidad del producto en 1 combo) * (cantidad de combos pedidos)
                $cantidadTotalProductoRemision = $comProd['cantidad_en_combo'] * $cantidadCombosPedidos;
                $descuentoDelCombo = !empty($comProd['descuento_del_combo']) ? ($comProd['descuento_del_combo']/100) : 0;

                // El precio ya viene "cotizado" y ajustado en el JSON
                // Si el precio unitario ya incluye descuentos o ajustes, lo tomamos directamente.
                // Si necesitas aplicar un descuento adicional del combo a nivel de remisión,
                // ese cálculo debería basarse en el precio_unitario_cotizado que ya está en el JSON.
                // Aquí usamos directamente el precio_unitario_cotizado del JSON.
                $precioUnitarioFinalProducto = $comProd['precio_unitario_cotizado'] - ($comProd['precio_unitario_cotizado'] * $descuentoDelCombo);
                //$descuento


                // Preparar y ejecutar la inserción para cada producto desglosado en la remisión
                $stmt = $conexionBdPrincipal->prepare("
                    INSERT INTO cotizacion_productos(
                        czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, 
                        czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega, czpp_descuento, czpp_productos_existencias, czpp_combo, czpp_precio_original
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                if ($stmt === false) {
                    // Manejar error de preparación de la consulta
                    error_log("Error al preparar INSERT para remisión (combo): " . $conexionBdPrincipal->error);
                    continue; // Saltar a la siguiente iteración de combo
                }

                // czpp_tipo = CZPP_TIPO_REM (asumo que es una constante definida en tu código)
                $tipoRemision = CZPP_TIPO_REM;
                $bodegaDefault = 1; // Asumo 1 como valor por defecto

                $valorComboEnPedido      = ($combo['czpp_valor'] * $combo['czpp_cantidad']);
                // $descuentoComboEnPedido  = !empty($combo['czpp_descuento']) ? $valorComboEnPedido * ($combo['czpp_descuento'] / 100) : 0;
                // $valorFinalComboEnPedido = $valorComboEnPedido - $descuentoComboEnPedido;

                // 'isdiiisid' - s:string (czpp_cotizacion), i:int (czpp_producto), d:decimal/float (czpp_valor), etc.
                // Ajusta los tipos según tus columnas reales y el orden
                $stmt->bind_param(
                    "iidiiisidiii", // Ejemplo de tipos, revisa los tuyos. 'i' para INT, 'd' para DECIMAL/FLOAT, 's' para STRING
                    $idInsert, // ID de la remisión
                    $comProd['id_producto'], // ID del producto individual
                    $precioUnitarioFinalProducto, // Precio ya calculado
                    $ordenCombo, // Orden de la línea del combo en la remisión
                    $cantidadTotalProductoRemision, // Cantidad total calculada
                    $idImpuestoCombo, // Impuesto del combo
                    $tipoRemision, // Tipo (remisión)
                    $bodegaDefault, // Bodega
                    $descuentoCombo, // Descuento del combo en la cotización
                    $datosDelProductoActual['prod_existencias'], // Existencias actuales
                    $combo['czpp_combo'], // ID del combo para saber la procedencia de los productos
                    $valorComboEnPedido // Precio del combo cuando se generó el pedido para replicarlo en la remisión
                );

                if (!$stmt->execute()) {
                    error_log("Error al ejecutar INSERT para remisión (producto de combo): " . $stmt->error);
                }

                $conexionBdPrincipal->commit();

                $stmt->close();
            }
        } else {
            $conexionBdPrincipal->rollback();
            error_log("Error decodificando JSON para combo en pedido ID: " . $_GET["id"] . " - Error: " . json_last_error_msg());
            // Manejar caso donde el JSON es inválido o no es un array
            // Podrías registrar un error y tal vez insertar el combo como una línea genérica si no puedes desglosarlo.
        }
    }
}

echo '<script type="text/javascript">window.location.href="../remisionbdg.php?busqueda=' . $idInsert . '";</script>';
exit();