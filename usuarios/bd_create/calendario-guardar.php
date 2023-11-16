<?php
require_once("../sesion.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require RUTA_PROYECTO.'/librerias/phpmailer/Exception.php';
require RUTA_PROYECTO.'/librerias/phpmailer/PHPMailer.php';
require RUTA_PROYECTO.'/librerias/phpmailer/SMTP.php';

$idPagina = 288;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"INSERT INTO agenda(age_evento, age_fecha, age_usuario, age_inicio, age_fin, age_lugar, age_notas, age_cliente, age_id_empresa)VALUES('" . $_POST["evento"] . "','" . $_POST["fecha"] . "','" . $_SESSION["id"] . "','" . $_POST["inicio"] . "','" . $_POST["fin"] . "','" . $_POST["lugar"] . "','" . $_POST["notas"] . "','" . $_POST["cliente"] . "', '".$_SESSION['dataAdicional']['id_empresa']."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);


	if ($_POST["cliente"] != '0' && $_POST["enviarCorreo"] == 1 ) {


		$hora = 1;
		$minuto = 0;
		$i = 0;
		while ($hora < 24) {
			if ($i % 2 == 0) {
				$minuto = 0;
				if ($i > 0) {
					$hora++;
				}
			} else {
				$minuto = 30;
			}
			if ($i == $_POST["inicio"]) {
				$inicioHora = $hora . ":" . $minuto;
			}


			$i++;
		}


		$hora = 1;
		$minuto = 0;
		$i = 0;
		while ($hora < 24) {
			if ($i % 2 == 0) {
				$minuto = 0;
				if ($i > 0) {
					$hora++;
				}
			} else {
				$minuto = 30;
			}
			if ($i == $_POST["fin"]) {
				$finHora = $hora . ":" . $minuto;
			}


			$i++;
		}


		$resultado = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='" . $_POST["cliente"] . "'"));

		$fin =  '<html><body style="background-color:' . $configuracion["conf_fondo_boletin"] . ';">';
		$fin .= '
				<center>
					<p align="center"><img src="' . $configuracion["conf_url_encuestas"] . '/usuarios/files/' . $configuracion["conf_logo"] . '" width="350"></p>
					<div style="font-family:arial; background:' . $configuracion["conf_fondo_mensaje"] . '; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
						
						<p style="color:' . $configuracion["conf_color_letra"] . ';">
						Hola <b>' . $resultado['cli_nombre'] . '</b>.<br>
						Te notificamos que <b>' . $datosUsuarioActual['usr_nombre'] . '</b> te está haciendo una invitación para un evento. A continuación los detalles:<br><br>
						<b>Evento:</b> ' . $_POST["evento"] . '<br>
						<b>Fecha:</b> ' . $_POST["fecha"] . '<br>
						<b>Hora inicio:</b> ' . $inicioHora . '<br>
						<b>Hora fin:</b> ' . $finHora . '<br>
						<b>Nota:</b> ' . $_POST["notas"] . '<br>
						</p>
						
						<p align="center"><a href="' . $configuracion["conf_url_encuestas"] . '/usuarios/reportes/formato-cotizacion-1.php?cte=1&id=' . base64_encode($idInsertU) . '" target="_blank" 
						
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
			$mail->SMTPDebug = 2;                                       // Enable verbose debug output
			$mail->isSMTP();                                            // Set mailer to use SMTP
			$mail->Host       = 'mail.orioncrm.com.co';  // Specify main and backup SMTP servers
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = $configuracion['conf_email'];                     // SMTP username
			$mail->Password   = $configuracion['conf_clave_correo'];                              // SMTP password
			$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
			$mail->Port       = 465;                                    // TCP port to connect to

			//Recipients
			$mail->setFrom($configuracion['conf_email'], '');

			$mail->addAddress($resultado['cli_email'], $resultado['cli_nombre']);     // Add a recipient


			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = "Te invitaron a un evento - " . $_POST["evento"];
			$mail->Body = $fin;
			$mail->CharSet = 'UTF-8';

			$mail->send();
			echo 'Correo Enviado';
		} catch (Exception $e) {
			echo "Error: {$mail->ErrorInfo}";
		}
	}

	echo '<script type="text/javascript">window.location.href="../calendario.php?id=' . $_SESSION["id"] . '";</script>';
	exit();
