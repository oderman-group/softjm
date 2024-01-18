<?php
include("sesion.php");
include("../compartido/head.php");
$idPagina = 340;
$tituloPagina = "Enviar remision actual al cliente";
include("verificar-paginas.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require RUTA_PROYECTO.'/librerias/phpmailer/Exception.php';
require RUTA_PROYECTO.'/librerias/phpmailer/PHPMailer.php';
require RUTA_PROYECTO.'/librerias/phpmailer/SMTP.php';

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
							<b>LINK DE DESCARGA:</b><br> '.REDIRECT_ROUTE.'/v2.0/usuarios/empresa/lab-remisiones-imprimir.php?id='.$_GET["id"].'&estado='.$remision["rem_estado"].'<br>
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
		$mail = new PHPMailer(true);
  try {
    $mail->SMTPDebug = 0;                                                           // Enable verbose debug output
    $mail->isSMTP();                                                                // Set mailer to use SMTP
    $mail->Host       = EMAIL_SERVER;                                               // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                                       // Enable SMTP authentication
    $mail->Username   = EMAIL_USER;                                                 // SMTP username
    $mail->Password   = EMAIL_PASSWORD;                                             // SMTP password
    $mail->SMTPSecure = 'ssl';                                                      // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 465;                                                        // TCP port to connect to

    //Recipients
    $mail->setFrom(EMAIL_SENDER, NAME_SENDER);
		$mail->addAddress($contacto['cont_email'], $contacto['cont_nombre']);   // Add a recipient


    // Content
    $mail->isHTML(true);                                                            // Set email format to HTML
    $mail->Subject = "Remisión de su equipo";
    $mail->Body = $fin;
    $mail->CharSet = 'UTF-8';

    $mail->send();
  } catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
  }
	/*
	echo $fin."<br>";
	echo $contacto['cont_email'];
	exit();
	*/
	echo '<script type="text/javascript">window.location.href="lab-remisiones.php?msg=1";</script>';
	exit();
