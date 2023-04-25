<?php
include("../sesion.php");
include("../../conexion.php");

$consultaConfig=mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1");
$configuracion = mysqli_fetch_array($consultaConfig, MYSQLI_BOTH);

if($_POST["em"]==5){
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_terminado=1, cli_terminado_por='".$_SESSION["id"]."', cli_terminado_fecha=now() WHERE cli_id='".$_POST["cliente"]."'");
}

if($_POST["em"]!=6){
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_papelera=0 WHERE cli_id='".$_POST["cliente"]."'");
}

mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_estado_mercadeo='".$_POST["em"]."', cli_estado_mercadeo_fecha=now(), cli_estado_mercadeo_usuario='".$_SESSION["id"]."' WHERE cli_id='".$_POST["cliente"]."'");

echo "Acción realizada";
?>