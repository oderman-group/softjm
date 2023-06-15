<?php
session_start();
include("../../conexion.php");
$rst_usr = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_SESSION["id"]."' AND cli_clave_documentos='".$_POST["claveDoc"]."'");
$num = mysqli_num_rows($rst_usr);
$fila = mysqli_fetch_array($rst_usr, MYSQLI_BOTH);
if($num>0)
{
	//INICIO SESION
	$_SESSION["idDoc"] = $fila[0];
	header("Location:documentos.php");	
	exit();
}else{
	header("Location:clave-documentos.php?error=1");
	exit();
}
?>