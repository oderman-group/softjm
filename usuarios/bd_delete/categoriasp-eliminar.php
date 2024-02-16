<?php
    require_once("../sesion.php");

    $idPagina = 62;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$conexionBdPrincipal->query("DELETE FROM productos WHERE prod_categoria='" . $_GET["id"] . "' AND  prod_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");
    $conexionBdPrincipal->query("DELETE FROM productos_categorias WHERE catp_id='" . $_GET["id"] . "' AND  catp_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();