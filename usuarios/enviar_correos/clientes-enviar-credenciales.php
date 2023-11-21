<?php   
require_once("../sesion.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require RUTA_PROYECTO.'/librerias/phpmailer/Exception.php';
require RUTA_PROYECTO.'/librerias/phpmailer/PHPMailer.php';
require RUTA_PROYECTO.'/librerias/phpmailer/SMTP.php';

$consulta = $conexionBdPrincipal->query("SELECT * FROM clientes WHERE cli_id='" . $_GET["id"] . "'");
$resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);


	$fin =  '<html><body style="background-color:' . $configuracion["conf_fondo_boletin"] . ';">';
	$fin .= '
				<center>
					<p align="center"><img src="' . $configuracion["conf_url_encuestas"] . '/usuarios/files/' . $configuracion["conf_logo"] . '" width="350"></p>
					<div style="font-family:arial; background:' . $configuracion["conf_fondo_mensaje"] . '; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
						
						<p style="color:' . $configuracion["conf_color_letra"] . ';">' . strtoupper($resultado['cli_nombre']) . ',<br>
						Estamos enviando sus credenciales de acceso al sistema de clientes de '.$_SESSION["dataAdicional"]['nombre_empresa'].'. Sus credenciales son:<br>
						<b>URL de acceso:</b> https://jmequipos.com/clientes.php<br>
						<b>Usuario:</b> ' . $resultado['cli_usuario_acceso'] . '<br>
						<b>Contraseña:</b> ' . $resultado['cli_clave'] . '
						</p>
						
						<p align="center" style="color:' . $configuracion["conf_color_letra"] . ';">
							<img src="' . $configuracion["conf_url_encuestas"] . '/usuarios/files/' . $configuracion["conf_logo"] . '" width="80"><br>
							' . $configuracion["conf_mensaje_pie"] . '<br>
							<a href="' . $configuracion["conf_web"] . '" style="color:' . $configuracion["conf_color_link"] . ';">' . $configuracion["conf_web"] . '</a>
						</p>
						
					</div>
				</center>
				<p>&nbsp;</p>
			';
	$fin .= '';
	$fin .=  '<html><body>';


	// Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);

	try {
		//Server settings
		$mail->SMTPDebug = 0;                                       // Enable verbose debug output
		$mail->isSMTP();                                            // Set mailer to use SMTP
		$mail->Host       = 'mail.orioncrm.com.co';  // Specify main and backup SMTP servers
		$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		$mail->Username   = $configuracion['conf_email'];                     // SMTP username
		$mail->Password   = $configuracion['conf_clave_correo'];                               // SMTP password
		$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
		$mail->Port       = 465;                                    // TCP port to connect to

		//Recipients
		$mail->setFrom($configuracion['conf_email'], 'INFORMACIÓN '.$_SESSION["dataAdicional"]['nombre_empresa']);

		$mail->addAddress($resultado['cli_email'], $resultado['cli_nombre']);     // Add a recipient


		// Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = 'Credenciales de acceso';
		$mail->Body = $fin;
		$mail->CharSet = 'UTF-8';

		$mail->send();
		echo 'Enviada credenciales al cliente.';
	} catch (Exception $e) {
		echo "Error: {$mail->ErrorInfo}";
	}

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");	

echo '<script type="text/javascript">window.location.href="../clientes-editar.php?id=' . $_GET["id"] . '&msg=13";</script>';
exit();
