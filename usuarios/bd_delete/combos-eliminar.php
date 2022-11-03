<?php
    require_once("../sesion.php");

	$idPagina = 218;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$conexionBdPrincipal->query("DELETE FROM combos WHERE combo_id='" . $_GET["id"] . "'");
    $conexionBdPrincipal->query("DELETE FROM combos_productos WHERE copp_combo='" . $_GET["id"] . "'");

	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();