<?php
    require_once("../sesion.php");

	$idPagina = 310;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    
	mysqli_query($conexionBdPrincipal,"DELETE FROM notificaciones WHERE not_id='" . $_GET["id"] . "'");
	
	
	
	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();