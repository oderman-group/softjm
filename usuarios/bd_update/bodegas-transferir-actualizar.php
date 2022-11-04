<?php
    require_once("../sesion.php");

    $idPagina = 220;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$conexionBdPrincipal->query("UPDATE productos_bodegas SET prodb_bodega='" . $_POST["hasta"] . "' WHERE prodb_bodega='" . $_POST["desde"] . "'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../bodegas.php?msg=2";</script>';
	exit();