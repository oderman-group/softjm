<?php
session_start();
$_SESSION["bd"] = $_POST["bd"];
include("conexion.php");

switch($_POST["bd"]){
	case 'odermancom_jm_crm'; $urlRed = 'https://softjm.com'; break;

	case 'odermancom_orioncrm_exacta'; $urlRed = 'https://orioncrm.com.co/exactaingenieria'; break;
		
	case 'odermancom_orioncrm_asalliancesas'; $urlRed = 'https://orioncrm.com.co/asalliancesas'; break;

	case 'orioncrmcom_oscar'; $urlRed = 'https://orioncrm.com.co/oscar'; break;	

	default: $urlRed = 'https://softjm.com'; break;
}

$rst_usrE = mysql_query("SELECT usr_login, usr_id, usr_intentos_fallidos FROM usuarios 
WHERE usr_login='".trim(mysql_real_escape_string($_POST["Usuario"]))."' AND TRIM(usr_login)!='' AND usr_login IS NOT NULL",$conexion);
$numE = mysql_num_rows($rst_usrE);
if($numE==0){
	header("Location:".$urlRed."/index.php?error=1&bd=".$_SESSION["bd"]."&u=".$_POST["Usuario"]."&bd2=".$_POST["bd"]);
	exit();
}
$usrE = mysql_fetch_array($rst_usrE);

if($usrE['usr_intentos_fallidos']>=3 and md5($_POST["suma"])<>$_POST["sumaReal"]){
	header("Location:".$urlRed."/index.php?error=3");
	exit();
}

$rst_usr = mysql_query("SELECT * FROM usuarios WHERE usr_login='".trim($_POST["Usuario"])."' AND usr_clave=SHA1('".$_POST["Clave"]."')",$conexion);
$num = mysql_num_rows($rst_usr);
$fila = mysql_fetch_array($rst_usr);
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
	
	mysql_query("UPDATE usuarios SET usr_sesion=1, usr_ultimo_ingreso=now(), usr_intentos_fallidos=0 WHERE usr_id='".$fila[0]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error();exit();}
	
	header("Location:".$url);	
	exit();
}else{
	mysql_query("UPDATE usuarios SET usr_intentos_fallidos=usr_intentos_fallidos+1 WHERE usr_id='".$usrE['usr_id']."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error();exit();}

	header("Location:".$urlRed."/index.php?error=2&idseg=".$_POST["idseg"]);
	exit();
}
?>