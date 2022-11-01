<?php
    require_once("../sesion.php");

    $idPagina = 62;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$conexionBdPrincipal->query("DELETE FROM productos WHERE prod_categoria='" . $_GET["id"] . "'");
	$conexionBdPrincipal->query("DELETE FROM productos_categorias WHERE catp_id='" . $_GET["id"] . "'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();