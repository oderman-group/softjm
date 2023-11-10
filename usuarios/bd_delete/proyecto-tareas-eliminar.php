<?php
require_once("../sesion.php");
require_once("../class/BaseDatos.php");

$idPagina = 114;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM proyectos_tareas WHERE ptar_id_proyecto='" . $_GET["id"] . "'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM proyectos WHERE proy_id='" . $_GET["id"] . "'");
	
	

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();