<?php 
include("../modelo/conexion.php");
$rst_usr = mysql_query("SELECT * FROM usuarios WHERE usr_login='".trim($_POST["Usuario"])."' AND usr_clave='".$_POST["Clave"]."'",$conexion);
$num = mysql_num_rows($rst_usr);
$fila = mysql_fetch_array($rst_usr);
if($num>0)
{
	//VERIFICAR SI EL USUARIO ESTÁ BLOQUEADO
	if($fila[6]==1){header("Location:../index.php?error=4");exit();}
	
	//INICIO SESION
	session_start();
	$_SESSION["id"] = $fila[0];
	if(!isset($_POST["idseg"]) or !is_numeric($_POST["idseg"])){$url = '../usuarios/empresa/';}
	else{$url = '../usuarios/empresa/clientes-seguimiento-editar.php?id='.$_POST["idseg"];}
	header("Location:".$url);	
	exit();
}else{
	header("Location:../index.php?error=2&idseg=".$_POST["idseg"]);
	exit();
}
?>