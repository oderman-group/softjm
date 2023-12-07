<?php include("sesion.php");
$idPagina = 332;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../librerias/phpmailer/Exception.php';
require '../../librerias/phpmailer/PHPMailer.php';
require '../../librerias/phpmailer/SMTP.php';

$remision = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT * FROM remisiones 
    INNER JOIN clientes ON cli_id=rem_cliente
    WHERE rem_id='" . $_POST["idRem"] . "'"), MYSQLI_BOTH);

// Check if email is set before using trim
$email = isset($_POST["email"]) ? trim($_POST["email"]) : '';

$contactoV = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT * FROM contactos 
    WHERE cont_email='" . $email . "' AND cont_cliente_principal='" . $_SESSION["id_cliente"] . "'", MYSQLI_USE_RESULT));

// Check if the query result is not empty before accessing $contactoV[0]
if (empty($contactoV)) {
    mysqli_query($conexionBdPrincipal, "INSERT INTO contactos(cont_nombre, cont_email, cont_cliente_principal) 
        VALUES('" . $_POST["nombre"] . "','" . $_POST["email"] . "','" . $_SESSION["id_cliente"] . "')");
    $idContacto = mysqli_insert_id($conexionBdPrincipal);
    $contacto = $idContacto;
} else {
    $contacto = $contactoV[0];
}




	
	mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_tikets(tik_asunto_principal, tik_tipo_tiket, tik_fecha_creacion, tik_usuario_responsable, tik_estado, tik_cliente, tik_prioridad, tik_canal)
	VALUES('SOLICITUD COPIA CERTIFICADO',2,now(),27,1,'".$_SESSION["id_cliente"]."',1,7)");
	
	$tiketID = mysqli_insert_id($conexionBdPrincipal);
	
	mysqli_query($conexionBdPrincipal,"INSERT INTO cliente_seguimiento(cseg_cliente, cseg_fecha_reporte, cseg_observacion, cseg_usuario_responsable, cseg_fecha_proximo_contacto, cseg_asunto, cseg_usuario_encargado, cseg_fecha_contacto, cseg_tipo, cseg_contacto, cseg_tiket, cseg_canal, cseg_canal_proximo_contacto)VALUES('".$_SESSION["id_cliente"]."',now(),'Solicitud de copia a certificado C".$_POST["idRem"]."',27,now(),'Respuesta a la solicitud de  la copia',21,now(),2,'".$contacto."','".$tiketID."',7,8)");
	
	$idSeguimiento = mysqli_insert_id($conexionBdPrincipal);
	
	mysqli_query($conexionBdPrincipal,"INSERT INTO notificaciones(not_asunto, not_cliente, not_usuario, not_visto, not_estado, not_fecha, not_seguimiento)VALUES('COPIA DE CERTIFICADO C".$_POST["idRem"]."', '".$_SESSION["id_cliente"]."', 21, 0, 1, now(), '".$idSeguimiento."')");
	
		
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
    $mail->addAddress(EMAIL_RECIPIENT, "Auxiliar Laboratorio");   
	$mail->addAddress($_POST["email"], $_POST["nombre"]);   // Add a recipient


    // Content
    $mail->isHTML(true);                                                            // Set email format to HTML
    $mail->Subject = "Solicitud copia de certificado - ".$remision['cli_nombre'];
    $mail->Body = $fin;
    $mail->CharSet = 'UTF-8';

    $mail->send();
  } catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
  }
  
	echo '<script type="text/javascript">window.location.href="documentos.php?id='.$_POST["id"].'&msg=1";</script>';
	exit();
?>