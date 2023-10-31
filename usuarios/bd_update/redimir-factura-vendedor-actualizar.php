<?php
require_once("../sesion.php");

$idPagina = 308;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"UPDATE facturas SET factura_redimido_vendedor=1 WHERE factura_id='" . $_GET["id"] . "'");
	
include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();