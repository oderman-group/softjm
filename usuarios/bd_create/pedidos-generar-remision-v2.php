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

    mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega, czpp_descuento, czpp_productos_existencias, czpp_nombre_original)VALUES('" . $idInsert . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', '" . $prod['czpp_orden'] . "', '".$prod['czpp_cantidad']."', '" . $prod['czpp_impuesto'] . "', ".CZPP_TIPO_REM.", 1, '" . $prod['czpp_descuento'] . "', '" . $prod['prod_existencias'] . "', '" . $prod['czpp_nombre_original'] . "')");

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

                $predicado = [
                    'prod_id'         => $comProd['id_producto'],
                    'prod_id_empresa' => $idEmpresa
                ];

                $datosDelProductoActual = mysqli_fetch_assoc(Producto::Select($predicado, 'prod_existencias'));

            }
        } else {
            $conexionBdPrincipal->rollback();
            error_log("Error decodificando JSON para combo en pedido ID: " . $_GET["id"] . " - Error: " . json_last_error_msg());
            // Manejar caso donde el JSON es inválido o no es un array
            // Podrías registrar un error y tal vez insertar el combo como una línea genérica si no puedes desglosarlo.
        }
    }

    mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_combo, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega, czpp_descuento, czpp_productos_en_combo, czpp_nombre_original)VALUES('" . $idInsert . "','" . $combo['czpp_combo'] . "', '" . $combo['czpp_valor'] . "', '" . $combo['czpp_orden'] . "', '".$combo['czpp_cantidad']."', '" . $combo['czpp_impuesto'] . "', ".CZPP_TIPO_REM.", 1, '" . $combo['czpp_descuento'] . "', '" . $combo['czpp_productos_en_combo'] . "', '" . $prod['czpp_nombre_original'] . "')");
}

echo '<script type="text/javascript">window.location.href="../remisionbdg.php?busqueda=' . $idInsert . '";</script>';
exit();