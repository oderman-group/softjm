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
//Constantes
$meses = array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
$opcionesSINO = array("NO","SI");
$estadosCertificados = array("","Vigente","Vencido","Provisional");
$em = array("","NC","NE","EP","IN","AC","PR");
$emColor = array("white","goldenrod","gold","green","limegreen","aqua","tomato");	
$estadosRemision = array("","Entrada","Salida");
$mesesAbre = array("", "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
$opcionesEtapa = array("N/A","En progreso","En espera","Propuesta/Cotización","Negociación/Revisión","Cerrado y ganado","Cerrado y perdido");
$opcionesTipoNegocio = array("N/A","Venta","Servicio","Servicio Post venta");
$opcionesOrigenNegocio = array("N/A","LLamada mercadeo","Email Marketing","Sitio Web","Publicidad","Cliente existente","Recomendación","Exhibición","Otro");