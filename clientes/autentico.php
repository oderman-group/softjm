<?php
session_start();
$_SESSION["bd"] = $_POST["bd"];
include("../conexion.php");
$rst_usr = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_usuario_acceso='".trim($_POST["Usuario"])."' AND cli_clave='".$_POST["Clave"]."' AND TRIM(cli_usuario_acceso)!='' AND cli_categoria!='".CLI_CATEGORIA_PROSPECTO."'");
$num = mysqli_num_rows($rst_usr);
$fila = mysqli_fetch_array($rst_usr, MYSQLI_BOTH);
if($num>0)
{
	//INICIO SESION
	$_SESSION["id_cliente"] = $fila['cli_id'];
	$_SESSION["id_empresa"] = $fila['cli_id_empresa'];
	
	switch($_POST["refe"]){
		case 1:	$url = 'registrados/'; break;
		case 2: $url = 'https://jmequipos.com/clientes-certificados.php?cte='.$fila[0]; break;
		
			
		default: $url = 'registrados/'; break;	
	}
	
	if(!isset($_POST["idseg"]) or !is_numeric($_POST["idseg"])){$url = 'registrados/';}
	else{$url = 'registrados/notificaciones-lista.php?idSeg='.$_POST["idseg"];}
	
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_sesion=1, cli_ultimo_ingreso=now() WHERE cli_id='".$fila[0]."'");
	
	header("Location:".$url);	
	exit();
}else{
	header("Location:http://jmequipos.com/clientes.php?6=current-menu-item&error=3&msg=esProspecto");
	exit();
}
?>