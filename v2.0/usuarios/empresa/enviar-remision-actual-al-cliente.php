<?php
include("sesion.php");
include("../compartido/head.php");
$idPagina = 340;
$tituloPagina = "Enviar remision actual al cliente";
include("verificar-paginas.php");

		$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_GET["cte"]."'"));
		$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='".$_GET["contacto"]."'"));
		$remision = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones WHERE rem_id='".$_GET["id"]."'"));
		
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">
							'.date("d-m-Y").'<br>
							Señores:<br>
							'.strtoupper($cliente['cli_nombre']).'.<br>
							'.strtoupper($contacto['cont_nombre']).'.<br>
							Cordial saludo,<br>
							Adjuntamos link de descarga de la remisión actual de su equipo con los siguientes datos:<br>
							<b>Fecha de entrada:</b> '.$remision["rem_fecha"].'<br>
							<b>Equipo:</b> '.$remision["rem_equipo"].'<br>
							<b>Referencia:</b> '.$remision["rem_referencia"].'<br>
							<b>Serial:</b> '.$remision['rem_serial'].'<br>
							<b>LINK DE DESCARGA:</b><br> https://softjm.com/v2.0/usuarios/empresa/lab-remisiones-imprimir.php?id='.$_GET["id"].'&estado='.$remision["rem_estado"].'<br>
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
		$sdestinatario=$contacto['cont_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject="Remisión de su equipo - JMEQUIPOS"; //ASUNTO DEL MENSAJE 				
		$shtml=$fin; //MENSAJE EN SI			
		$sheader="From:".$sfrom."\nReply-To:".$sfrom."\n"; 			
		$sheader=$sheader."X-Mailer:PHP/".phpversion()."\n"; 			
		$sheader=$sheader."Mime-Version: 1.0\n"; 		
		$sheader=$sheader."Content-Type: text/html; charset=UTF-8\r\n"; 			
		@mail($sdestinatario,$ssubject,$shtml,$sheader);
	/*
	echo $fin."<br>";
	echo $contacto['cont_email'];
	exit();
	*/
	echo '<script type="text/javascript">window.location.href="lab-remisiones.php?msg=1";</script>';
	exit();
