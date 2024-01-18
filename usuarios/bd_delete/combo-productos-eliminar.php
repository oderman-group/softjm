<?php
    require_once("../sesion.php");

	$idPagina = 305;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    
	mysqli_query($conexionBdPrincipal,"DELETE FROM combos_productos WHERE copp_id='" . $_GET["idItem"] . "'");
	
	
	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();	