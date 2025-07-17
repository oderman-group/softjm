<?php
require_once("../sesion.php");
$idPagina = 371;

require_once RUTA_PROYECTO.'/usuarios/class/ItemAsociado.php';
require_once RUTA_PROYECTO.'/usuarios/class/Cotizacion.php';

$predicado = [
    'czpp_id' => $_GET["idItem"],
];

$join = "INNER JOIN cotizacion ON cotiz_id=czpp_cotizacion AND cotiz_vendida !=".Cotizacion::COTIZACION_VENDIDA;

$consulta = ItemAsociado::Select($predicado, join:$join);
$datos = mysqli_fetch_array($pedidoAsociado, MYSQLI_BOTH);

mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion_productos 
WHERE 
    czpp_id='" . $_GET["idItem"] . "'
");

echo '<script type="text/javascript">window.location.href="../cotizaciones-editar.php?id=' . $_GET["id"] . '&msg=2";</script>';
exit();