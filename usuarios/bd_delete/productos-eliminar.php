<?php
    require_once("../sesion.php");

	$idPagina = 61;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$conexionBdPrincipal->query("DELETE FROM productos_materiales WHERE ppmt_producto='" . $_GET["id"] . "'");
	$conexionBdPrincipal->query("DELETE FROM productos WHERE prod_id='" . $_GET["id"] . "'");

	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();