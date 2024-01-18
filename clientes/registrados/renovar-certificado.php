<?php 
include("sesion.php");

$idPagina = 330;
$tituloPagina = "Mis Contactos";

include("head.php");

$servicio = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM servicios WHERE serv_id='".$_POST["servicio"]."'"), MYSQLI_BOTH);
	
	mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion(fact_cliente, fact_fecha, fact_valor, fact_estado, fact_descripcion, fact_observacion, fact_fecha_real, fact_fecha_vencimiento, fact_tipo, fact_impuestos, fact_producto, fact_id_empresa)VALUES('".$_SESSION["id_cliente"]."',now(),'".$servicio['serv_precio']."',2,'Renovar certificado ".$_POST["certificado"]."','Factura automática',now(),now(),1,'19','".$_POST["idCertificado"]."', '".$_SESSION["id_empresa"]."')");
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	
	$totalaPagar = ($servicio['serv_precio'] * 1.19);
	$ahora = getdate();
	
	$certificado = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones WHERE rem_id='".$_POST["idCertificado"]."'"), MYSQLI_BOTH);
	$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes 
	INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
	WHERE cli_id='".$_SESSION["id_cliente"]."'"), MYSQLI_BOTH);
	
	$firmaDatos = 'C4budLS1xJFM8LwZQNQt218wHx~764579~'.$idInsertU.'~'.$totalaPagar.'~COP';
	$firma = md5($firmaDatos);
?>	
	<form method="post" name="frm_botonPayU" action="https://checkout.payulatam.com/ppp-web-gateway-payu"> 
		<input name="merchantId"    type="hidden"  value="764579">
		<input name="accountId"     type="hidden"  value="771173">
		<input name="description"   type="hidden"  value="Renovar certificado <?=$_POST["certificado"];?>">
		<input name="referenceCode" type="hidden"  value="<?=$idInsertU;?>" >
		<input name="amount"        type="hidden"  value="<?=$totalaPagar;?>">
		<input name="tax"           type="hidden"  value="0">
		<input name="taxReturnBase" type="hidden"  value="0">
		<input name="currency"      type="hidden"  value="COP">
		<input name="signature"     type="hidden"  value="<?=$firma;?>">
		<input name="test"          type="hidden"  value="0">
		<input name="buyerFullName" type="hidden"  value="<?=$cliente["cli_nombre"];?>">	
		<input name="buyerEmail"    type="hidden"  value="<?=$cliente["cli_email"];?>">
		<input name="shippingAddress" type="hidden"  value="<?=$cliente["cli_direccion"];?>">
		<input name="shippingCity"  type="hidden"  value="<?=$cliente["ciu_nombre"];?>">
		<input name="shippingCountry" type="hidden"  value="CO">
		<input name="telephone"     type="hidden"  value="<?=$cliente["cli_telefono"];?>">	
		<input name="responseUrl"    type="hidden"  value="https://jmequipos.com/respuesta.php">
		<input name="confirmationUrl"    type="hidden"  value="https://jmequipos.com/respuesta.php">
		<input name="extra1"    type="hidden"  value="<?=$cliente["cli_nombre"];?>">
	</form>

		<script type="text/javascript">
			document.frm_botonPayU.submit();
		</script>
	<?php include("pie.php");?>
</div>
</body>
</html>