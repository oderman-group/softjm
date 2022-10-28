<?php   
require_once("../sesion.php");

$idPagina = 80;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
$conexionBdPrincipal->query("DELETE FROM cotizacion WHERE cotiz_id='" . $_GET["id"] . "'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
exit();