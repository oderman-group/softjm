<?php
$conexion=mysqli_connect("localhost","odermancom_jm_crm",")S{q9V7hBJv;");
mysqli_select_db($conexion, "odermancom_jm_crm");

$configuracion = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM configuracion WHERE conf_id=1"));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'librerias/phpmailer/Exception.php';
require 'librerias/phpmailer/PHPMailer.php';
require 'librerias/phpmailer/SMTP.php';
//RECORDAR RENOVAR CERTIFICADOS
$consultaC = mysqli_query($conexion, "SELECT * FROM remisiones
INNER JOIN clientes ON cli_id=rem_cliente
");
$no = 1;
while($resC = mysqli_fetch_array($consultaC)){	
	$vencimiento = mysqli_fetch_array(mysqli_query($conexion, "SELECT DATEDIFF(DATE_ADD(rem_fecha, INTERVAL 6 MONTH), now()) 
	FROM remisiones 
	WHERE rem_id='".$resC['rem_id']."'"));
	
	//Actualizar estado del certificado a vencido con 1 o más días
	if($vencimiento[0]<0){
		mysqli_query($conexion, "UPDATE remisiones SET rem_estado_certificado='".REM_ESTADO_CERTIFICADO_VENCIDO."' WHERE rem_id='".$resC['rem_id']."'");
	}
	
	//Enviar un correo de que vence ese día el certificado
	if($vencimiento[0]=='0'){
		$fin =  '<html><body style="background-color:'.$configuracion["conf_fondo_boletin"].';">';
		$fin .= '
					<center>
						<p align="center"><img src="'.$configuracion["conf_url_encuestas"].'/usuarios/files/'.$configuracion["conf_logo"].'" width="350"></p>
						<div style="font-family:arial; background:'.$configuracion["conf_fondo_mensaje"].'; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:'.$configuracion["conf_color_letra"].';">'.strtoupper($resC['cli_nombre']).',<br>
							Le informamos que su certificado <b>C'.$resC['rem_id'].'</b> vence HOY.<br>
							Le sugerimos ponerse en contacto con nuestra área de servicio técnico al <strong>PBX: (4) 322 0619 EXT. 102, WhatsApp: 310 798 3526</strong>  o al correo <strong>laboratorio@jmequipos.com</strong> para la renovación de su certificado.<br><br>
							<b>DETALLES</b><br>
							<b>Equipo:</b> '.$resC['rem_equipo'].'<br>
							<b>Referencia:</b> '.$resC['rem_referencia'].'<br>
							<b>Marca:</b> '.$resC['rem_marca'].'<br>
							<b>Serial:</b> '.$resC['rem_serial'].'<br>
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

		// Instantiation and passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->SMTPDebug = 2;                                       // Enable verbose debug output
			$mail->isSMTP();                                            // Set mailer to use SMTP
			$mail->Host       = 'mail.orioncrm.com.co';  // Specify main and backup SMTP servers
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = $configuracion['conf_email'];                     // SMTP username
			$mail->Password   = $configuracion['conf_clave_correo'];                              // SMTP password
			$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
			$mail->Port       = 465;                                    // TCP port to connect to

			//Recipients
			$mail->setFrom($configuracion['conf_email'], 'JMEQUIPOS');
			$mail->addAddress($resC['cli_email'], $resC['cli_nombre']);     // Add a recipient
			$mail->addAddress('auxlaboratorio@jmequipos.com', 'Auxiliar laboratorio');     // Add a recipient
			$mail->addAddress('laboratorio@jmequipos.com', 'Laboratorio');     // Add a recipient
			$mail->addAddress('recepcion@jmequipos.com', 'Laboratorio');     // Add a recipient
			//$mail->addAddress('jhonoderman@gmail.com', 'Jhon Mejia');     // Add a recipient
			
			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = 'Recordamos que hoy vence el certificado ('.$resC['rem_id'].') - JMEQUIPOS';
			$mail->Body = $fin;
			$mail->CharSet = 'UTF-8';

			$mail->send();
			echo 'Enviada notificación de vencimiento HOY';
		} catch (Exception $e) {echo "Error: {$mail->ErrorInfo}";}
	}
}