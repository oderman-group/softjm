<?php
session_start();

const RUTA = "C:/xampp/htdocs/softjm";

if( $_SESSION["id"]=="" || !is_numeric($_SESSION["id"]) ){
	header("Location:../salir.php");
	exit();
}
	
$tiempo_inicial = microtime(true);
	
require_once(RUTA."/conexion.php");
require_once(RUTA."/usuarios/config/config.php");
require_once(RUTA."/usuarios/includes/funciones-para-el-sistema.php");
require_once(RUTA."/usuarios/includes/sesion-usuario-actual.php");