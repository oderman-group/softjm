<?php
    require_once("../sesion.php");

	$idPagina = 194;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo "Por seguridad NO es posible eliminar estos registros.";
	exit();

	$conexionBdPrincipal->query("DELETE FROM marcas WHERE mar_id='" . $_GET["id"] . "' AND mar_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();