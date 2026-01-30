<?php
session_start();

date_default_timezone_set('America/Bogota');

require_once($_SERVER['DOCUMENT_ROOT']."/softjm/constantes.php");

if( $_SESSION["id"]=="" || !is_numeric($_SESSION["id"]) ){
	// Conservar la URL que el usuario intentaba ver para redirigirlo tras el login
	$urlDestino = REDIRECT_ROUTE.'/usuarios/'.basename($_SERVER['SCRIPT_NAME']).(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '' ? '?'.$_SERVER['QUERY_STRING'] : '');
	header("Location:".REDIRECT_ROUTE."/index.php?s=11&redirect_to=".urlencode($urlDestino));
	exit();
}
	
$tiempo_inicial = microtime(true);
	
require_once(RUTA_PROYECTO."/conexion.php");
require_once(RUTA_PROYECTO."/usuarios/config/config.php");
require_once(RUTA_PROYECTO."/usuarios/includes/funciones-para-el-sistema.php");
require_once(RUTA_PROYECTO."/usuarios/includes/sesion-usuario-actual.php");