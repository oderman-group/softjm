<?php 
include("sesion.php");

//EDITAR USUARIOS
if($_POST["idSql"]==1){
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_usuario='".$_POST["usuario"]."', cli_clave='".$_POST["clave"]."', cli_nombre='".$_POST["nombre"]."', cli_email='".$_POST["email"]."' WHERE cli_id='".$_SESSION["id_cliente"]."'");
	echo '<script type="text/javascript">window.location.href="perfil-editar.php?msg=2";</script>';
	exit();
}
//RECORDAR CLAVE DE ACCESO A DOCUMENTOS
if($_POST["idSql"]==2){
	$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_SESSION["id_cliente"]."'"), MYSQLI_BOTH);
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($cliente['cli_nombre']).',<br>
							Le informamos que su clave de acceso a documentos es <b>'.$cliente['cli_clave_documentos'].'</b>.
							</p>
							
							
							<p align="center" style="color:'.$configuracion["conf_color_letra"].';">
								<img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="80"><br>
								'.$configuracion["conf_mensaje_pie"].'<br>
								<a href="'.$configuracion["conf_web"].'" style="color:'.$configuracion["conf_color_link"].';">'.$configuracion["conf_web"].'</a>
							</p>
							
						</div>
					</center>
					<p>&nbsp;</p>
				';	
		$fin .='';						
		$fin .=  '<html><body>';							
		$sfrom=$configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario=$cliente['cli_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject="Clave de acceso a documentos"; //ASUNTO DEL MENSAJE 				
		$shtml=$fin; //MENSAJE EN SI			
		$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
		$sheader=$sheader."Mime-Version: 1.0\n"; 		
		$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
		@mail($sdestinatario,$ssubject,$shtml,$sheader);
	echo '<script type="text/javascript">window.location.href="clave-documentos.php?msg=2";</script>';
	exit();
}
//RENOVAR CERTIFICADOS
if($_POST["idSql"]==3){
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
<?php
}

//SOLICITAR COPIA DE CERTIFICADO
if($_POST["idSql"]==4){
	$remision = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones 
	INNER JOIN clientes ON cli_id=rem_cliente
	WHERE rem_id='".$_POST["idRem"]."'"), MYSQLI_BOTH);
	
	$contactoV = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos 
	WHERE cont_email='".trim($_POST["email"])."' AND cont_cliente_principal='".$_SESSION["id_cliente"]."'", MYSQLI_BOTH));
	if($contactoV[0]==""){
		mysqli_query($conexionBdPrincipal,"INSERT INTO contactos(cont_nombre, cont_email, cont_cliente_principal)VALUES('".$_POST["nombre"]."','".$_POST["email"]."','".$_SESSION["id_cliente"]."')");
		$idContacto = mysqli_insert_id($conexionBdPrincipal);
		$contacto = $idContacto;
	}else{
		$contacto = $contactoV[0];	
	}
	
	
	mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_tikets(tik_asunto_principal, tik_tipo_tiket, tik_fecha_creacion, tik_usuario_responsable, tik_estado, tik_cliente, tik_prioridad, tik_canal)
	VALUES('SOLICITUD COPIA CERTIFICADO',2,now(),27,1,'".$_SESSION["id_cliente"]."',1,7)");
	
	$tiketID = mysqli_insert_id($conexionBdPrincipal);
	
	mysqli_query($conexionBdPrincipal,"INSERT INTO cliente_seguimiento(cseg_cliente, cseg_fecha_reporte, cseg_observacion, cseg_usuario_responsable, cseg_fecha_proximo_contacto, cseg_asunto, cseg_usuario_encargado, cseg_fecha_contacto, cseg_tipo, cseg_contacto, cseg_tiket, cseg_canal, cseg_canal_proximo_contacto)VALUES('".$_SESSION["id_cliente"]."',now(),'Solicitud de copia a certificado C".$_POST["idRem"]."',27,now(),'Respuesta a la solicitud de  la copia',21,now(),2,'".$contacto."','".$tiketID."',7,8)");
	
	$idSeguimiento = mysqli_insert_id($conexionBdPrincipal);
	
	mysqli_query($conexionBdPrincipal,"INSERT INTO notificaciones(not_asunto, not_cliente, not_usuario, not_visto, not_estado, not_fecha, not_seguimiento)VALUES('COPIA DE CERTIFICADO C".$_POST["idRem"]."', '".$_SESSION["id_cliente"]."', 21, 0, 1, now(), '".$idSeguimiento."')");
	
		
		$meses = array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$fechaHoy = date("d")." de ".$meses[date("m")]." del ".date("Y");
			
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">
							'.$fechaHoy.'<br><br>
							Señores de Soporte técnico:<br>
							Cordial saludo,<br><br>
							Solicito una copia del certificado <b>#C'.$_POST["idRem"].'</b><br>
							Por favor enviarla al correo <b>'.$_POST["email"].'</b> a nombre de <b>'.$_POST["nombre"].'</b>
							</p>
							
							<p align="center" style="color:'.$configuracion["conf_color_letra"].';">
								<img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="80"><br>
								'.$configuracion["conf_mensaje_pie"].'<br>
								<a href="'.$configuracion["conf_web"].'" style="color:'.$configuracion["conf_color_link"].';">'.$configuracion["conf_web"].'</a>
							</p>
							
						</div>
					</center>
					<p>&nbsp;</p>
				';	
		$fin .='';						
		$fin .=  '<html><body>';							
		$sfrom="auxlaboratorio@jmequipos.com"; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario="auxlaboratorio@jmequipos.com"; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject="Solicitud copia de certificado - ".$remision['cli_nombre']; //ASUNTO DEL MENSAJE 				
		$shtml=$fin; //MENSAJE EN SI			
		$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
		$sheader=$sheader."Mime-Version: 1.0\n"; 		
		$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
		@mail($sdestinatario,$ssubject,$shtml,$sheader);
	echo '<script type="text/javascript">window.location.href="documentos.php?id='.$_POST["id"].'&msg=1";</script>';
	exit();
}
//AGREGAR CONTACTOS
if($_POST["idSql"]==5){
	mysqli_query($conexionBdPrincipal,"INSERT INTO contactos(cont_nombre, cont_email, cont_cliente_principal, cont_celular, cont_sucursal, cont_rol)VALUES('".$_POST["nombre"]."','".$_POST["email"]."','".$_SESSION["id_cliente"]."','".$_POST["celular"]."','".$_POST["sucursal"]."','".$_POST["rol"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="contactos-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();
}
//EDITAR CONTACTOS
if($_POST["idSql"]==6){
	mysqli_query($conexionBdPrincipal,"UPDATE contactos SET cont_nombre='".$_POST["nombre"]."', cont_email='".$_POST["email"]."', cont_rol='".$_POST["rol"]."', cont_celular='".$_POST["celular"]."', cont_sucursal='".$_POST["sucursal"]."' WHERE cont_id='".$_POST["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="contactos-editar.php?id='.$_POST["id"].'&msg=2";</script>';
	exit();
}

//----------------GET GET GET---------------
//SALIR DE DOCUMENTOS
if($_GET["get"]==1){
	$_SESSION["idDoc"] = "";
	echo '<script type="text/javascript">window.location.href="documentos.php";</script>';
	exit();
}
?>