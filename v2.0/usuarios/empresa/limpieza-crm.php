<?php
include("../../modelo/conexion.php");
$clientes = mysql_query("SELECT * FROM clientes",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}

$cont = 0;
while($cliente = mysql_fetch_array($clientes)){
	$tickets = mysql_num_rows(mysql_query("SELECT * FROM clientes_tikets WHERE tik_cliente='".$cliente['cli_id']."'",$conexion));
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	
	$seguimientos = mysql_num_rows(mysql_query("SELECT * FROM cliente_seguimiento WHERE cseg_cliente='".$cliente['cli_id']."'",$conexion));
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	
	$facturas = mysql_num_rows(mysql_query("SELECT * FROM facturacion WHERE fact_cliente='".$cliente['cli_id']."'",$conexion));
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	
	$remisiones = mysql_num_rows(mysql_query("SELECT * FROM remisiones WHERE rem_cliente='".$cliente['cli_id']."'",$conexion));
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	
	if($tickets==0 and $seguimientos==0 and $facturas==0 and $remisiones==0){
		mysql_query("DELETE FROM clientes WHERE cli_id='".$cliente['cli_id']."'",$conexion);
		if(mysql_errno()!=0){echo mysql_error(); exit();}
		$cont++;
	}
}
echo "Se borraron en total ".$cont." clientes.";	  
?>
