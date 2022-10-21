<?php   
require_once("../sesion.php");

$idPagina = 18;
include(RUTA_PROYECTO."/usuarios/verificar-paginas.php");
$consulta= $conexionBdPrincipal->query("INSERT INTO cotizacion (cotiz_fecha_propuesta, cotiz_descripcion, cotiz_valor, cotiz_observaciones, cotiz_cliente, cotiz_fecha_vencimiento, cotiz_vendedor, cotiz_creador, cotiz_sucursal, cotiz_contacto, cotiz_forma_pago, cotiz_fecha_creacion, cotiz_moneda) SELECT cotiz_fecha_propuesta, cotiz_descripcion, cotiz_valor, cotiz_observaciones, cotiz_cliente, cotiz_fecha_vencimiento, cotiz_vendedor, '" . $_SESSION["id"] . "', cotiz_sucursal, cotiz_contacto, cotiz_forma_pago, now(), cotiz_moneda FROM cotizacion WHERE cotiz_id='" . $_GET["id"] . "'");
$idInsert = mysqli_insert_id($conexionBdPrincipal);


$productos = $conexionBdPrincipal->query("SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_GET["id"] . "'");

while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
    $conexionBdPrincipal->query("INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_observacion, czpp_servicio, czpp_combo)VALUES('" . $idInsert . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', '" . $prod['czpp_orden'] . "', '" . $prod['czpp_cantidad'] . "', '" . $prod['czpp_impuesto'] . "', '" . $prod['czpp_tipo'] . "', '" . $prod['czpp_observacion'] . "', '" . $prod['czpp_servicio'] . "', '" . $prod['czpp_combo'] . "')");
    $contador++;
}

include(RUTA_PROYECTO."/usuarios/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../cotizaciones-editar.php?id=' . $idInsert . '";</script>';
exit();