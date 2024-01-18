<?php
$idPagina = 405;
session_start();
include("conexion.php");
if(isset($_SESSION["id"])){
	$query = $conexionBdPrincipal->query("UPDATE usuarios SET usr_sesion=0, usr_ultima_salida=now() WHERE usr_id='".$_SESSION["id"]."'");
}

session_destroy();
header("Location:".REDIRECT_ROUTE."/index.php?s=11");
?>