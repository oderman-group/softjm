<?php
    require_once("../sesion.php");

	$idPagina = 211;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    $conexionBdPrincipal->query("DELETE FROM productos_galeria WHERE pgal_id='" . $_GET["idItem"] . "'");
        
	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
    exit();