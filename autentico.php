<?php
session_start();
$_SESSION["bd"] = $_POST["bd"];
include("conexion.php");

$urlRed = REDIRECT_ROUTE;


$rst_usrE = $conexionBdPrincipal->query("SELECT usr_login, usr_id, usr_intentos_fallidos FROM usuarios 
WHERE usr_login='".trim(mysqli_real_escape_string($conexionBdPrincipal, $_POST["Usuario"]))."' AND TRIM(usr_login)!='' AND usr_login IS NOT NULL");

$numE = $rst_usrE->num_rows;
if($numE ==0 ){
	header("Location:".$urlRed."/index.php?error=1&bd=".$_SESSION["bd"]."&u=".$_POST["Usuario"]."&bd2=".$_POST["bd"]);
	exit();
}
$usrE = mysqli_fetch_array($rst_usrE, MYSQLI_BOTH);

if($usrE['usr_intentos_fallidos']>=3 and md5($_POST["suma"])<>$_POST["sumaReal"]){
	header("Location:".$urlRed."/index.php?error=3");
	exit();
}

$rst_usr = $conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_login='".trim($_POST["Usuario"])."' AND usr_clave=SHA1('".$_POST["Clave"]."')");
$num = $rst_usr->num_rows;
$fila = mysqli_fetch_array($rst_usr, MYSQLI_BOTH);
if($num>0)
{
	//VERIFICAR SI EL USUARIO ESTÁ BLOQUEADO
	if($fila[6]==1){header("Location:".$urlRed."/index.php?error=4");exit();}
	//INICIO SESION
	//session_start();
	$_SESSION["id"] = $fila[0];
	//$_SESSION["idUsuario"] = $fila[0];
	if(!isset($_POST["idseg"]) or !is_numeric($_POST["idseg"])){$url = 'usuarios/';}
	else{$url = 'usuarios/clientes-seguimiento-editar.php?id='.$_POST["idseg"];}
	
	$conexionBdPrincipal->query("UPDATE usuarios SET usr_sesion=1, usr_ultimo_ingreso=now(), usr_intentos_fallidos=0 WHERE usr_id='".$fila[0]."'");
	//if(mysql_errno()!=0){echo mysql_error();exit();}
	
	header("Location:".$url);	
	exit();
}else{
	$conexionBdPrincipal->query("UPDATE usuarios SET usr_intentos_fallidos=usr_intentos_fallidos+1 WHERE usr_id='".$usrE['usr_id']."'",$conexion);
	//if(mysql_errno()!=0){echo mysql_error();exit();}

	header("Location:".$urlRed."/index.php?error=2&idseg=".$_POST["idseg"]);
	exit();
}
?>