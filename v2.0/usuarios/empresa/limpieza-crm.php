<?php
include("../../modelo/conexion.php");
$clientes = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes");

$cont = 0;
while($cliente = mysqli_fetch_array($clientes, MYSQLI_BOTH)){
	$tickets = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_tikets WHERE tik_cliente='".$cliente['cli_id']."'"));
	
	$seguimientos = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM cliente_seguimiento WHERE cseg_cliente='".$cliente['cli_id']."'"));
	
	$facturas = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM facturacion WHERE fact_cliente='".$cliente['cli_id']."'"));
	
	$remisiones = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones WHERE rem_cliente='".$cliente['cli_id']."'"));
	
	if($tickets==0 and $seguimientos==0 and $facturas==0 and $remisiones==0){
		mysqli_query($conexionBdPrincipal,"DELETE FROM clientes WHERE cli_id='".$cliente['cli_id']."'");
		$cont++;
	}
}
echo "Se borraron en total ".$cont." clientes.";	  
?>
