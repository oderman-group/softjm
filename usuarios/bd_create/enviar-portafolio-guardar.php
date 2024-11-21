<?php
require_once("../sesion.php");

include_once(RUTA_PROYECTO."/usuarios/class/Api/JmEquipos.php");

require '../../librerias/phpmailer/Exception.php';
require '../../librerias/phpmailer/PHPMailer.php';
require '../../librerias/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$idPagina = 365;
$asesor = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='" . $_SESSION["id"] . "'"));

$numero = (count($_POST["portafolios"]));

if ($numero > 0) {
	$contador = 0;
	while ($contador < $numero) {
		$portafolios .= '<a href="' . Api_JmEquipos::JM_URL_ARCHIVOS_PORTAFOLIOS . $_POST["portafolios"][$contador] . '">' . $_POST["portafolios"][$contador] . '</a><br>';
		$contador++;
	}
}

$numC = strlen($portafolios) - 1;
$portafolios = substr($portafolios, 0, $numC);


$numero = (count($_POST["clientes"]));
if ($numero > 0) {
	$contador = 0;
	while ($contador < $numero) {
		
		$clientes = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='" . $_POST["clientes"][$contador] . "'");
		
		while ($ctes = mysqli_fetch_array($clientes)) {
			
			if ($ctes['cli_email'] != "" and !is_null($ctes['cli_email'])) {
				$fin =  '<html><body style="background-color:#FFF;">';
				$fin .=  '<div style="width: 100%; display: grid; place-content: center;">';
				$fin .=  '
				<div style="font-family:arial; background:#0033a0; width:600px; color:#FFF; text-align:center; padding:15px;">
					<h3>PORTAFOLIOS</h3>
				</div>
				';
				$fin .= '
							<div style="font-family:arial; background:#FAFAFA; width:600px; color:#000; text-align:justify; padding:15px;">

									<p style="color:' . $configuracion["conf_color_letra"] . ';">
									Cordial saludo estimado cliente.
									</p>

									<p>' . $configuracion["conf_emsj_portafolios"] . '</p>

									<p>' . $portafolios . '</p>

									<p>
									Cualquier duda o inquietud no dude en contactarnos.<br>
									Recuerde que el asesor que lo atendió en esta ocasión fue:<br>
									' . strtoupper($asesor['usr_nombre']) . '<br>
									' . strtolower($asesor['usr_email']) . '<br>
									' . $asesor['usr_telefono'] . '
									</p>

									<p align="center" style="background:#0033a0; color:#FFF;">
										' . $configuracion["conf_mensaje_pie"] . '<br>
										<a href="' . $configuracion["conf_web"] . '" style="color:#FFF;">' . $configuracion["conf_web"] . '</a>
									</p>

								</div>
								</div>
							</center>
							</div>
							</center>
							<p>&nbsp;</p>
						';
				$fin .= '</div>';
				$fin .=  '</body></html>';

				// Instantiation and passing `true` enables exceptions
				$mail = new PHPMailer(true);
				echo '<div style="display:block;">';
				try {
					//Server settings
					$mail->SMTPDebug = 0;                                       // Enable verbose debug output
					$mail->isSMTP();                                            // Set mailer to use SMTP
					$mail->Host       = 'mail.orioncrm.com.co';  // Specify main and backup SMTP servers
					$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
					$mail->Username   = $configuracion['conf_email'];                     // SMTP username
					$mail->Password   = $configuracion['conf_clave_correo'];                              // SMTP password
					$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
					$mail->Port       = 465;                                    // TCP port to connect to

					//Recipients
					$mail->setFrom($configuracion['conf_email'], '');

					$mail->addAddress($ctes['cli_email'], $ctes['cli_nombre']);     // Add a recipient
					$mail->addAddress($asesor['usr_email'], $asesor['usr_nombre']);     // Add a recipient

					// Content
					$mail->isHTML(true);                                  // Set email format to HTML
					$mail->Subject = "PORTAFOLIO ".$_SESSION["dataAdicional"]['nombre_empresa'];
					$mail->Body = $fin;
					$mail->CharSet = 'UTF-8';

					$mail->send();

					mysqli_query($conexionBdPrincipal,"INSERT INTO buzon_salida(buz_remite, buz_destino, buz_tipo, buz_estado, buz_observacion, buz_referencia, buz_cliente, buz_usuario)VALUES('" . $asesor['usr_email'] . "', '" . $ctes['cli_email'] . "', 1, 1, 'Enviados correctamente.<br> Portafolios:<br> " . $portafolios . "', '" . $ctes['cli_id'] . "', '" . $ctes['cli_id'] . "', '" . $_SESSION["id"] . "')");
					
				} catch (Exception $e) {
					echo "Error: {$mail->ErrorInfo}";

					mysqli_query($conexionBdPrincipal,"INSERT INTO buzon_salida(buz_remite, buz_destino, buz_tipo, buz_estado, buz_observacion, buz_referencia, buz_cliente, buz_usuario)VALUES('" . $asesor['usr_email'] . "', '" . $ctes['cli_email'] . "', 1, 2, 'Error al enviar.<br> Portafolios:<br> " . $portafolios . ".<br> Error: " . $mail->ErrorInfo . "', '" . $ctes['cli_id'] . "', '" . $ctes['cli_id'] . "', '" . $_SESSION["id"] . "')");
					
				}
				echo '</div>';
			}
		}
		$contador++;
	}
}

echo '<script type="text/javascript">window.location.href="../enviar-portafolios.php?envd=1&cte='.$_POST['cte'].'";</script>';
exit();