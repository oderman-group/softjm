<?php
include("sesion.php");

require '../librerias/phpmailer/Exception.php';
require '../librerias/phpmailer/PHPMailer.php';
require '../librerias/phpmailer/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$idPagina = 317;

include("includes/verificar-paginas.php");
$resultado = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion
	INNER JOIN clientes ON cli_id=cotiz_cliente
	INNER JOIN sucursales ON sucu_id=cotiz_sucursal
	INNER JOIN contactos ON cont_id=cotiz_contacto
	INNER JOIN usuarios ON usr_id=cotiz_vendedor
	WHERE cotiz_id='" . $_GET["id"] . "'"));

	$fin =  '<html><body style="background-color:' . $configuracion["conf_fondo_boletin"] . ';">';
	$fin .= '
				<center>
					<p align="center"><img src="' . $configuracion["conf_url_encuestas"] . '/usuarios/files/' . $configuracion["conf_logo"] . '" width="350"></p>
					<div style="font-family:arial; background:' . $configuracion["conf_fondo_mensaje"] . '; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
						
						<p style="color:' . $configuracion["conf_color_letra"] . ';">' . strtoupper($resultado['cont_nombre']) . ',<br>
						Estamos enviando la cotización por este medio para que la revise y la pueda imprimir según su necesidad.<br>
						Haga click en el siguiente enlace para revisar la cotización.</p>
						
						<p align="center"><a href="' . $configuracion["conf_url_encuestas"] . '/usuarios/reportes/formato-cotizacion-1.php?cte=1&id=' . base64_encode($_GET["id"]) . '" target="_blank" style="color:' . $configuracion["conf_color_link"] . ';">REVISAR COTIZACIÓN</a></p>
						
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
		$mail->addAddress($resultado['cont_email'], $contacto['cont_nombre']);     // Add a recipient
		$mail->addAddress($resultado['cli_email'], $contacto['cli_nombre']);     // Add a recipient
		$mail->addAddress($resultado['usr_email'], $contacto['usr_nombre']);     // Add a recipient


		// Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = 'Cotización #' . $_GET["id"];
		$mail->Body = $fin;
		$mail->CharSet = 'UTF-8';

		$mail->send();
		echo 'Enviada cotización al cliente.';
	} catch (Exception $e) {
		echo "Error: {$mail->ErrorInfo}";
	}


	echo '<script type="text/javascript">window.location.href="cotizaciones.php?msg=6";</script>';
	exit();
