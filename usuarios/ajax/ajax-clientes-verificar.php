<?php
include("sesion.php");
$configuracion = mysql_fetch_array(mysql_query("SELECT * FROM configuracion WHERE conf_id=1",$conexion));

if($_POST["opcion"]==1){
	$clienteV = mysql_fetch_array(mysql_query("SELECT * FROM clientes WHERE cli_usuario='".trim($_POST["usuario"])."'",$conexion));
	if($clienteV[0]!=""){
		echo "<span style='font-family:arial; text-align:center; color:red;'>Ya existe un cliente con este n&uacute;mero de NIT: <b><a href='clientes-editar.php?id=".$clienteV['cli_id']."'>".$clienteV['cli_nombre']."</a></b></div>";
		exit();
	}else{
		echo "<span style='font-family:arial; text-align:center; color:blue;'>Este NIT disponible, puedes continuar.</div>";
		exit();
	}
}

if($_POST["opcion"]==2){
	$clienteV = mysql_fetch_array(mysql_query("SELECT * FROM proveedores WHERE prov_documento='".trim($_POST["usuario"])."'",$conexion));
	if($clienteV[0]!=""){
		echo "<span style='font-family:arial; text-align:center; color:red;'>Ya existe un proveedores con este DNI: <b><a href='proveedores-editar.php?id=".$clienteV['prov_id']."'>".$clienteV['prov_nombre']."</a></b></div>";
		exit();
	}else{
		echo "<span style='font-family:arial; text-align:center; color:blue;'>DNI disponible, puedes continuar.</div>";
		exit();
	}
}

if($_POST["opcion"]==3){
	$datos = mysql_fetch_array(mysql_query("SELECT * FROM productos WHERE prod_referencia='".trim($_POST["idUnico"])."'",$conexion));
	if($datos[0]!=""){
		echo "<span style='font-family:arial; text-align:center; color:red;'>Ya existe un registro con esta Referencia: <b><a href='productos-editar.php?id=".$datos[0]."'>".$datos['prod_nombre']."</a></b></div>";
		exit();
	}else{
		echo "<span style='font-family:arial; text-align:center; color:blue;'>REFERENCIA disponible, puedes continuar.</div>";
		exit();
	}
}
?>