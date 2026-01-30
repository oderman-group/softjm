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

$contador = 0;
$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos
INNER JOIN productos ON prod_id=czpp_producto
WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_PED."' AND czpp_producto!=''");


while ($prod = mysqli_fetch_array($productos)) {

    if ($prod['czpp_orden'] == "") $prod['czpp_orden'] = 1;
    if ($prod['czpp_cantidad'] == "") $prod['czpp_cantidad'] = 1;

    mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega, czpp_descuento, czpp_productos_existencias, czpp_nombre_original)VALUES('" . $idInsert . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', '" . $prod['czpp_orden'] . "', '".$prod['czpp_cantidad']."', '" . $prod['czpp_impuesto'] . "', ".CZPP_TIPO_REM.", 1, '" . $prod['czpp_descuento'] . "', '" . $prod['prod_existencias'] . "', '" . mysqli_real_escape_string($conexionBdPrincipal, $prod['czpp_nombre_original']) . "')");

    $resultado = Producto::sacarExistenciasProductoMultiBodega($prod['czpp_producto'], $prod['czpp_cantidad'], $conexionBdPrincipal, true);

    if ($resultado['status'] === 'success') {
        Producto::sincronizarExistenciasConBodegas($prod['czpp_producto'], $conexionBdPrincipal);
    } else {
        $conexionBdPrincipal->rollback();
        echo '<p style="font-family:arial;color:red;">No se pudo generar la remisión. Error al descontar existencias del producto (ID: ' . (int)$prod['czpp_producto'] . '). ' . ($resultado['message'] ?? 'Sin stock suficiente.') . '</p><p><a href="' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../pedidos.php') . '">Volver</a></p>';
        exit();
    }
    $contador++;
}


// Consultamos los combos de este pedido para extraer sus productos de manera individual
$productosCombos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_PED."' AND czpp_combo!=''");

while ($combo = mysqli_fetch_array($productosCombos, MYSQLI_ASSOC)) {
    $ordenCombo = !empty($combo['czpp_orden']) ? (int)$combo['czpp_orden'] : 1;
    $cantidadCombosPedidos = !empty($combo['czpp_cantidad']) ? (int)$combo['czpp_cantidad'] : 1;
    $idImpuestoCombo = !empty($combo['czpp_impuesto']) ? $combo['czpp_impuesto'] : 0;
    $descuentoCombo = !empty($combo['czpp_descuento']) ? $combo['czpp_descuento'] : 0;
    $comboInsertadoComoProductos = false;

    if (!empty($combo['czpp_productos_en_combo_generar_pedido'])) {
        $productosDelComboJSON = json_decode($combo['czpp_productos_en_combo_generar_pedido'], true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($productosDelComboJSON)) {
            foreach ($productosDelComboJSON as $comProd) {
                $cantidadTotalProductoRemision = (int)($comProd['cantidad_en_combo'] ?? 0) * $cantidadCombosPedidos;
                if ($cantidadTotalProductoRemision <= 0) {
                    continue;
                }

                $resultado = Producto::sacarExistenciasProductoMultiBodega($comProd['id_producto'], $cantidadTotalProductoRemision, $conexionBdPrincipal, true);
                if ($resultado['status'] !== 'success') {
                    $conexionBdPrincipal->rollback();
                    $idProdCombo = (int)($comProd['id_producto'] ?? 0);
                    $nombreProdCombo = '';
                    if ($idProdCombo) {
                        $rNombre = mysqli_fetch_assoc($conexionBdPrincipal->query("SELECT prod_nombre FROM productos WHERE prod_id=" . $idProdCombo . " AND prod_id_empresa='".$idEmpresa."' LIMIT 1"));
                        $nombreProdCombo = $rNombre ? (' "' . htmlspecialchars($rNombre['prod_nombre']) . '"') : '';
                    }
                    echo '<p style="font-family:arial;color:red;">No se pudo generar la remisión. No hay unidades disponibles para uno de los productos del combo: producto' . $nombreProdCombo . ' (ID: ' . $idProdCombo . '). ' . ($resultado['message'] ?? 'Sin stock suficiente.') . '</p><p><a href="' . (isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : '../pedidos.php') . '">Volver</a></p>';
                    exit();
                }
                Producto::sincronizarExistenciasConBodegas($comProd['id_producto'], $conexionBdPrincipal);

                $predicado = [
                    'prod_id'         => $comProd['id_producto'],
                    'prod_id_empresa' => $idEmpresa
                ];
                $resProd = Producto::Select($predicado, 'prod_existencias');
                $datosDelProductoActual = $resProd ? mysqli_fetch_assoc($resProd) : ['prod_existencias' => 0];

                $descuentoDelCombo = !empty($comProd['descuento_del_combo']) ? ((float)$comProd['descuento_del_combo'] / 100) : 0;
                $precioUnitarioCotizado = (float)($comProd['precio_unitario_cotizado'] ?? 0);
                $precioUnitarioFinalProducto = $precioUnitarioCotizado - ($precioUnitarioCotizado * $descuentoDelCombo);
                $valorComboEnPedido = ($combo['czpp_valor'] * $combo['czpp_cantidad']);

                $stmt = $conexionBdPrincipal->prepare("
                    INSERT INTO cotizacion_productos(
                        czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden,
                        czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega, czpp_descuento, czpp_productos_existencias, czpp_combo, czpp_precio_original
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                if ($stmt === false) {
                    $conexionBdPrincipal->rollback();
                    echo '<p style="font-family:arial;color:red;">No se pudo generar la remisión. Error al preparar la inserción.</p><p><a href="' . (isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : '../pedidos.php') . '">Volver</a></p>';
                    exit();
                }
                $bodegaDefault = 1;
                $existencias = (int)($datosDelProductoActual['prod_existencias'] ?? 0);
                $tipoRemision = CZPP_TIPO_REM;
                $vIdInsert = $idInsert;
                $vIdProducto = (int)$comProd['id_producto'];
                $vPrecio = $precioUnitarioFinalProducto;
                $vOrden = $ordenCombo;
                $vCantidad = $cantidadTotalProductoRemision;
                $vImpuesto = $idImpuestoCombo;
                $vBodega = $bodegaDefault;
                $vDescuento = $descuentoCombo;
                $vComboId = (int)$combo['czpp_combo'];
                $vPrecioOriginal = $valorComboEnPedido;
                $stmt->bind_param(
                    "iidiiiiidiid",
                    $vIdInsert,
                    $vIdProducto,
                    $vPrecio,
                    $vOrden,
                    $vCantidad,
                    $vImpuesto,
                    $tipoRemision,
                    $vBodega,
                    $vDescuento,
                    $existencias,
                    $vComboId,
                    $vPrecioOriginal
                );
                if (!$stmt->execute()) {
                    $stmt->close();
                    $conexionBdPrincipal->rollback();
                    echo '<p style="font-family:arial;color:red;">No se pudo generar la remisión. Error al guardar el detalle.</p><p><a href="' . (isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : '../pedidos.php') . '">Volver</a></p>';
                    exit();
                }
                $stmt->close();
                $comboInsertadoComoProductos = true;
            }
        } else {
            $conexionBdPrincipal->rollback();
            error_log("Error decodificando JSON para combo en pedido ID: " . $_GET["id"] . " - Error: " . json_last_error_msg());
            echo '<p style="font-family:arial;color:red;">No se pudo generar la remisión. Error en los datos del combo (JSON inválido).</p><p><a href="' . (isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : '../pedidos.php') . '">Volver</a></p>';
            exit();
        }
    }

    if (!$comboInsertadoComoProductos) {
        $czppNombreOriginal = isset($combo['czpp_nombre_original']) ? mysqli_real_escape_string($conexionBdPrincipal, $combo['czpp_nombre_original']) : '';
        mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_combo, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega, czpp_descuento, czpp_productos_en_combo, czpp_nombre_original) VALUES('" . $idInsert . "','" . $combo['czpp_combo'] . "', '" . $combo['czpp_valor'] . "', '" . $ordenCombo . "', '" . $combo['czpp_cantidad'] . "', '" . $combo['czpp_impuesto'] . "', " . CZPP_TIPO_REM . ", 1, '" . $descuentoCombo . "', '" . mysqli_real_escape_string($conexionBdPrincipal, $combo['czpp_productos_en_combo'] ?? '') . "', '" . $czppNombreOriginal . "')");
    }
}

$conexionBdPrincipal->commit();

echo '<script type="text/javascript">window.location.href="../remisionbdg.php?busqueda=' . (int)$idInsert . '";</script>';
exit();