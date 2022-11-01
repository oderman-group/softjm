<?php
require_once("../sesion.php");

$idPagina = 63;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");


	$conexionBdPrincipal->query("DELETE FROM zonas WHERE zon_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="../zonas.php?msg=3";</script>';
	exit();