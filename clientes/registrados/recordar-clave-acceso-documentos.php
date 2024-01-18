<?php 
include("sesion.php");

$idPagina = 331;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../librerias/phpmailer/Exception.php';
require '../../librerias/phpmailer/PHPMailer.php';
require '../../librerias/phpmailer/SMTP.php';

$tituloPagina = "Acceso a documentos";

include("verificar-paginas.php");
include("head.php");
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
		  $mail->addAddress($cliente['cli_email'], $cliente['cli_nombre']);     // Add a recipient
	  
	  
		  // Content
		  $mail->isHTML(true);                                                            // Set email format to HTML
		  $mail->Subject = "Clave de acceso a documentos";
		  $mail->Body = $fin;
		  $mail->CharSet = 'UTF-8';
	  
		  $mail->send();
		} catch (Exception $e) {
		  echo "Error: {$mail->ErrorInfo}";
		}
	echo '<script type="text/javascript">window.location.href="clave-documentos.php?msg=2";</script>';
    
    include("pie.php");
	exit();
	