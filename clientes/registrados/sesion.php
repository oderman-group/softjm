<?php
session_start();
if($_SESSION["id_cliente"]=="" || !is_numeric($_SESSION["id_cliente"]) ){
	header("Location:../salir.php");
	exit();
}

$tiempo_inicial = microtime(true);

include("../../conexion.php");
//USUARIO ACTUAL
$consultaUsuarioActual = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_SESSION["id_cliente"]."'");
$numUsuarioActual = mysqli_num_rows($consultaUsuarioActual);
$datosUsuarioActual = mysqli_fetch_array($consultaUsuarioActual, MYSQLI_BOTH);

$consultaConfiguracion=mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id_empresa='".$_SESSION["id_empresa"]."'");
$configu = mysqli_fetch_array($consultaConfiguracion, MYSQLI_BOTH);
