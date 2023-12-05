<?php
require_once("../sesion.php");
$idPagina = 377;
$generoFactura = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM facturas WHERE factura_remision='" . $_GET["id"] . "'"));
if($generoFactura[0]!=""){
    echo "<span style='font-family:arial; text-align:center; color:red;'>Esta Remisión ya generó la factura con ID: ".$generoFactura[0].". En la fecha: ".$generoFactura['factura_fecha_creacion']."</div>";
    exit();
}

$valorProductos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT sum(czpp_valor*czpp_cantidad) + sum(czpp_valor*czpp_cantidad)*(czpp_impuesto/100) FROM cotizacion_productos 
WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo=3"));

mysqli_query($conexionBdPrincipal,"INSERT INTO facturas(factura_fecha_propuesta, factura_observaciones, factura_cliente, factura_fecha_vencimiento, factura_vendedor, factura_creador, factura_sucursal, factura_contacto, factura_forma_pago, factura_fecha_creacion, factura_moneda, factura_estado, factura_tipo, factura_concepto, factura_extranjera, factura_remision)
SELECT now(), remi_observaciones, remi_cliente, remi_fecha_vencimiento, remi_vendedor, '" . $_SESSION["id"] . "', remi_sucursal, remi_contacto, remi_forma_pago, now(), remi_moneda, 1, 1, 'Traída de remisión', 0, remi_id FROM remisionbdg WHERE remi_id='" . $_GET["id"] . "'");

$idInsert = mysqli_insert_id($conexionBdPrincipal);


$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo=3");


while ($prod = mysqli_fetch_array($productos)) {
    if ($prod['czpp_orden'] == "") $prod['czpp_orden'] = 1;
    if ($prod['czpp_cantidad'] == "") $prod['czpp_cantidad'] = 1;

    mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_descuento, czpp_observacion, czpp_servicio, czpp_combo, czpp_bodega)VALUES('" . $idInsert . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', '" . $prod['czpp_orden'] . "', '" . $prod['czpp_cantidad'] . "', '" . $prod['czpp_impuesto'] . "', 4, '" . $prod['czpp_descuento'] . "', '" . $prod['czpp_observacion'] . "', '" . $prod['czpp_servicio'] . "', '" . $prod['czpp_combo'] . "', '" . $prod['czpp_bodega'] . "')");
    

    $contador++;
}

echo '<script type="text/javascript">window.location.href="../facturas.php?q=' . $idInsert . '";</script>';
exit();