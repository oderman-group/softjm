<?php   
require_once("../sesion.php");
$idPagina = 374;

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
WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_PED."' AND czpp_producto!=''");


while ($prod = mysqli_fetch_array($productos)) {

    if ($prod['czpp_orden'] == "") $prod['czpp_orden'] = 1;
    if ($prod['czpp_cantidad'] == "") $prod['czpp_cantidad'] = 1;

    mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega, czpp_descuento)VALUES('" . $idInsert . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', '" . $prod['czpp_orden'] . "', '".$prod['czpp_cantidad']."', '" . $prod['czpp_impuesto'] . "', ".CZPP_TIPO_REM.", 1, '" . $prod['czpp_descuento'] . "')");
    
    $contador++;
}


//Consultamos los combos de este pedido para extraer sus productos de manera individual
$productosCombos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_PED."' AND czpp_combo!=''");

while ($combo = mysqli_fetch_array($productosCombos)) {
    if ($combo['czpp_orden'] == "") $combo['czpp_orden'] = 1;
    if ($combo['czpp_cantidad'] == "") $combo['czpp_cantidad'] = 1;

    $combos = mysqli_query($conexionBdPrincipal,"SELECT * FROM combos_productos 
    INNER JOIN productos ON prod_id=copp_producto
    INNER JOIN combos ON combo_id=copp_combo
    WHERE copp_combo='" . $combo['czpp_combo'] . "'");

    //Ingresar todos los productos de forma individual cuando son combos (SE ELIMINA EL CONCEPTO DE COMBO. TOD PASA A SER PRODUCTOS)
    while($comProd = mysqli_fetch_array($combos)){

        $porcentajeDcto = ($comProd['combo_descuento'] / 100);
        $precioConDctoCombo = $comProd['prod_precio'] - ($comProd['prod_precio'] * $porcentajeDcto);

        mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega, czpp_descuento)VALUES('" . $idInsert . "','" . $comProd['prod_id'] . "', '" . $precioConDctoCombo. "', '" . $combo['czpp_orden'] . "', '".$comProd['copp_cantidad']."', '" . $combo['czpp_impuesto'] . "', ".CZPP_TIPO_REM.", 1, '".$combo['czpp_descuento']."')");
    }

}

echo '<script type="text/javascript">window.location.href="../remisionbdg.php?busqueda=' . $idInsert . '";</script>';
exit();