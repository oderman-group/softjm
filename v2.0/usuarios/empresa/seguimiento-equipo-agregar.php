<?php
include("sesion.php");
include("../compartido/head.php");
$idPagina = 341;
$tituloPagina = "Seguuimiento equipo agregar";
include("verificar-paginas.php");

if($_POST["notfCliente"]==""){$_POST["notfCliente"]=0;}
	
if($_FILES['archivo']['name']!=""){
	$archivo = $_FILES['archivo']['name'];
	$destino = "../../../usuarios/files/adjuntos";
	move_uploaded_file($_FILES['archivo']['tmp_name'], $destino ."/".$archivo);
}

mysqli_query($conexionBdPrincipal,"INSERT INTO remisiones_seguimiento(remseg_id_remisiones, remseg_fecha, remseg_usuario, remseg_comentario, remseg_notificar_cliente, remseg_archivo)VALUES('".$_POST["id"]."',now(),'".$_SESSION["id"]."','".$_POST["obsLista"]."<br> ".$_POST["observaciones"]."','".$_POST["notfCliente"]."','".$archivo."')");

$idInsertU = mysqli_insert_id($conexionBdPrincipal);

if($_POST["notfCliente"]==1){
	$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_POST["cliente"]."'"));
	$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='".$_POST["contacto"]."'"));
	$remision = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones WHERE rem_id='".$_POST["id"]."'"));
	
	$meses = array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
	$fechaHoy = date("d")." de ".$meses[date("m")]." del ".date("Y");
		
	$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
	$fin .= '
				<center>
					<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
					<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
						
						<h3 align="center" style="background:darkblue; color:white;">Notificación servicio técnico - JMEQUIPOS</h3>
						
						<p style="color:'.$configuracion["conf_color_letra"].';">
						'.$fechaHoy.'<br><br>
						Señores:<br>
						'.strtoupper($cliente['cli_nombre']).'.<br>
						'.strtoupper($contacto['cont_nombre']).'.<br><br>
						Cordial saludo,<br><br>
						El departamento técnico de JMEQUIPOS SAS le informa que su equipo se encuentra en el siguiente estado:<br>
						<b>Fecha de entrada:</b> '.$remision["rem_fecha"].'<br>
						<b>Equipo:</b> '.$remision["rem_equipo"].'<br>
						<b>Referencia:</b> '.$remision["rem_referencia"].'<br>
						<b>Serial:</b> '.$remision['rem_serial'].'<br>
						<b>NOTA:</b><br>
						Para revisar este pendiente ingresa a nuestro sistema ORIÓN con tus datos de acceso, mediante el siguiente link.</p>
						
						<p align="center"><a href="https://softjm.com/clientes/index.php?idseg='.$idInsertU.'" target="_blank" style="color:'.$configuracion["conf_color_link"].';">VER EL SEGUIMIENTO</a></p>
						
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
	$sdestinatario=$contacto['cont_email'].", ".$cliente['cli_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
	$ssubject="Notificación servicio técnico - JMEQUIPOS"; //ASUNTO DEL MENSAJE 				
	$shtml=$fin; //MENSAJE EN SI			
	$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
	$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
	$sheader=$sheader."Mime-Version: 1.0\n"; 		
	$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
	@mail($sdestinatario,$ssubject,$shtml,$sheader);
}
/*
echo $fin."<br>";
echo $contacto['cont_email'];
exit();
*/
echo '<script type="text/javascript">window.location.href="lab-remisiones-seguimiento.php?id='.$_POST["id"].'";</script>';
exit();