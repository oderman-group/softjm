<?php
require_once("../sesion.php");

include_once(RUTA_PROYECTO."/usuarios/class/Api/JmEquipos.php");
require_once RUTA_PROYECTO.'/usuarios/class/MailerService.php';

if (empty($_POST["encargado"])) {
	$_POST["encargado"] = $_SESSION["id"];
}


if (empty($_POST["idTK"]) && empty($_POST["tiketCreado"])) {
	mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_tikets(tik_asunto_principal, tik_tipo_tiket, tik_fecha_creacion, tik_usuario_responsable, tik_estado, tik_cliente, tik_prioridad, tik_observaciones, tik_canal, tik_etapa, tik_tipo_negocio)
	VALUES('TIKCET AUTOMÁTICO',1,'" . $_POST["fechaContacto"] . "','" . $_SESSION["id"] . "',2,'" . $_POST["cliente"] . "',1,'" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["observaciones"]) . "','" . $_POST["canal"] . "', 3, 1)");
	$tiketID = mysqli_insert_id($conexionBdPrincipal);
} else {
	if (!empty($_POST["idTK"])) {
		$tiketID = $_POST["idTK"];
	} elseif (!empty($_POST["tiketCreado"])) {
		$tiketID = $_POST["tiketCreado"];
	}
}
if (empty($_POST["fechaPC"])) $_POST["fechaPC"] = '0000-00-00';
if (empty($_POST["encargado"])) $_POST["encargado"] = 0;

if (!empty($_FILES['archivo']['name'])) {
	$archivo = $_FILES['archivo']['name'];
	$destino = "files/adjuntos";
	move_uploaded_file($_FILES['archivo']['tmp_name'], $destino . "/" . $archivo);
}

$datos = 0;
if ($_POST["datos"] == 1) {
	$datos = 1;
}

$cotizo = 0;
if (!empty($_POST["cotizacion"])) {
	$cotizo = 1;
}

$vendio = 0;

$demostracion = 0;
if ($_POST["demostracion"] == 1) {
	$demostracion = 1;
}

$visita = 0;
if ($_POST["visita"] == 1) {
	$visita = 1;
}

if (!empty($_POST["encargado"])) {
	$numero = (count($_POST["encargado"]));

	if ($numero == 1) {
		mysqli_query($conexionBdPrincipal,"INSERT INTO cliente_seguimiento(cseg_cliente, cseg_fecha_reporte, cseg_observacion, cseg_usuario_responsable, cseg_fecha_proximo_contacto, cseg_asunto, cseg_usuario_encargado, cseg_cotizacion, cseg_fecha_contacto, cseg_tipo, cseg_contacto, cseg_tiket, cseg_canal, cseg_canal_proximo_contacto, cseg_archivo, cseg_cotizo, cseg_consiguio_datos, cseg_forma_contacto, cseg_demostracion,cseg_visita, cseg_hora_proximo_contacto, cseg_minutos_recordar_anticipadamente)VALUES('" . $_POST["cliente"] . "',now(),'" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["observaciones"]) . "','" . $_SESSION["id"] . "','" . $_POST["fechaPC"] . "','" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["asunto"]) . "','" . $_POST["encargado"][0] . "','" . $_POST["cotizacion"] . "','" . $_POST["fechaContacto"] . "','" . $_POST["tipoS"] . "','" . $_POST["contacto"] . "','" . $tiketID . "','" . $_POST["canal"] . "','" . $_POST["canalPC"] . "','" . $archivo . "','" . $cotizo . "','" . $datos . "','" . $_POST["formaContacto"] . "','" . $demostracion . "','" . $visita . "','" . $_POST["horaPC"] . "','" . $_POST["minutosRecordarAntes"] . "')");
		$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	} elseif ($numero > 1) {
		mysqli_query($conexionBdPrincipal,"INSERT INTO cliente_seguimiento(cseg_cliente, cseg_fecha_reporte, cseg_observacion, cseg_usuario_responsable, cseg_fecha_proximo_contacto, cseg_asunto, cseg_cotizacion, cseg_fecha_contacto, cseg_tipo, cseg_contacto, cseg_tiket, cseg_canal, cseg_canal_proximo_contacto, cseg_varios, cseg_archivo, cseg_forma_contacto, cseg_demostracion,cseg_visita, cseg_hora_proximo_contacto, cseg_minutos_recordar_anticipadamente)VALUES('" . $_POST["cliente"] . "',now(),'" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["observaciones"]) . "','" . $_SESSION["id"] . "','" . $_POST["fechaPC"] . "','" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["asunto"]) . "','" . $_POST["cotizacion"] . "','" . $_POST["fechaContacto"] . "','" . $_POST["tipoS"] . "','" . $_POST["contacto"] . "','" . $tiketID . "','" . $_POST["canal"] . "','" . $_POST["canalPC"] . "','" . $numero . "','" . $archivo . "','" . $_POST["formaContacto"] . "','" . $demostracion . "','" . $visita . "','" . $_POST["horaPC"] . "','" . $_POST["minutosRecordarAntes"] . "')");
		$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	}
} else {
	mysqli_query($conexionBdPrincipal,"INSERT INTO cliente_seguimiento(cseg_cliente, cseg_fecha_reporte, cseg_observacion, cseg_usuario_responsable, cseg_cotizacion, cseg_fecha_contacto, cseg_tipo, cseg_contacto, cseg_tiket, cseg_canal, cseg_varios, cseg_archivo, cseg_forma_contacto)VALUES('" . $_POST["cliente"] . "',now(),'" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["observaciones"]) . "','" . $_SESSION["id"] . "','" . $_POST["cotizacion"] . "','" . $_POST["fechaContacto"] . "','" . $_POST["tipoS"] . "','" . $_POST["contacto"] . "','" . $tiketID . "','" . $_POST["canal"] . "','" . $numero . "','" . $archivo . "','" . $_POST["formaContacto"] . "')");
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
}

if (!empty($_POST["cotizacion"])) {
	mysqli_query($conexionBdPrincipal,"UPDATE clientes_tikets SET 
	tik_id_cotizacion=".$_POST["cotizacion"].",
	tik_etapa = 3
	WHERE tik_id='" . $tiketID . "'");

	mysqli_query($conexionBdPrincipal,"UPDATE cotizacion SET cotiz_ticket=".$tiketID." WHERE cotiz_id='" . $_POST["cotizacion"] . "'");
}


if ($_POST["cerrarTK"] == 1) {
	mysqli_query($conexionBdPrincipal,"UPDATE clientes_tikets SET 
	tik_estado='".TIK_ESTADO_CERRADO."', 
	tik_fecha_cierre=NOW(), 
	tik_etapa=6
	WHERE tik_id='" . $tiketID . "'");

	mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_realizado=1 WHERE cseg_id='" . $idInsertU . "'");
}

$contactoCLiente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos 
INNER JOIN clientes ON cli_id=cont_cliente_principal
WHERE cont_id='" . $_POST["contacto"] . "'
"));
$asesor = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='" . $_SESSION["id"] . "'"));

if(!empty($_POST["portafolios"])){
	$numeroPortafolios = (count($_POST["portafolios"]));
	if ($numeroPortafolios > 0) {
		$contador = 0;
		while ($contador < $numeroPortafolios) {
			$portafolios .= '<a href="' . Api_JmEquipos::JM_URL_ARCHIVOS_PORTAFOLIOS . $_POST["portafolios"][$contador] . '">' . $_POST["portafolios"][$contador] . '</a><br>';
			$contador++;
		}
	}
	$numC = strlen($portafolios) - 1;
	$portafolios = substr($portafolios, 0, $numC);

	if ($numeroPortafolios > 0) {

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
		// $mail = new PHPMailer(true);
		echo '<div style="display:none;">';
		try {
			// //Server settings
			// $mail->SMTPDebug = 2;                                       // Enable verbose debug output
			// $mail->isSMTP();                                            // Set mailer to use SMTP
			// $mail->Host       = 'mail.orioncrm.com.co';  // Specify main and backup SMTP servers
			// $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			// $mail->Username   = $configuracion['conf_email'];                     // SMTP username
			// $mail->Password   = $configuracion['conf_clave_correo'];                              // SMTP password
			// $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
			// $mail->Port       = 465;                                    // TCP port to connect to

			// //Recipients
			// $mail->setFrom($configuracion['conf_email'], '');

			// $mail->addAddress($contactoCLiente['cont_email'], $contactoCLiente['cont_nombre']);     // Add a recipient
			// $mail->addAddress($asesor['usr_email'], $asesor['usr_nombre']);     // Add a recipient


			// // Content
			// $mail->isHTML(true);                                  // Set email format to HTML
			// $mail->Subject = "PORTAFOLIO ".$_SESSION["dataAdicional"]['nombre_empresa'];
			// $mail->Body = $fin;
			// $mail->CharSet = 'UTF-8';

			// $mail->send();
			// echo 'Enviado portafolio al cliente.';

			mysqli_query($conexionBdPrincipal,"INSERT INTO buzon_salida(buz_remite, buz_destino, buz_tipo, buz_estado, buz_observacion, buz_referencia, buz_cliente, buz_usuario, buz_contacto)VALUES('" . $asesor['usr_email'] . "', '" . $contactoCLiente['cont_email'] . "', 1, 1, 'Enviados correctamente.<br> Portafolios:<br> " . $portafolios . "', '" . $idInsertU . "', '" . $contactoCLiente['cont_cliente_principal'] . "', '" . $_SESSION["id"] . "', '" . $contactoCLiente['cont_id'] . "')");
		} catch (Exception $e) {
			echo "Error: {$mail->ErrorInfo}";

			mysqli_query($conexionBdPrincipal,"INSERT INTO buzon_salida(buz_remite, buz_destino, buz_tipo, buz_estado, buz_observacion, buz_referencia, buz_cliente, buz_usuario, buz_contacto)VALUES('" . $asesor['usr_email'] . "', '" . $contactoCLiente['cont_email'] . "', 1, 2, 'Error al enviar desde seguimiento.<br> Portafolios:<br> " . $portafolios . "<br>" . $mail->ErrorInfo . "', '" . $idInsertU . "', '" . $contactoCLiente['cont_cliente_principal'] . "', '" . $_SESSION["id"] . "', '" . $contactoCLiente['cont_id'] . "')");
		}
		echo '</div>';
	}
}


	
if ($_POST["notf"] == 1) {

	$contador = 0;
	while ($contador < $numero) {
		mysqli_query($conexionBdPrincipal,"INSERT INTO notificaciones(not_asunto, not_cliente, not_usuario, not_visto, not_estado, not_seguimiento, not_fecha)VALUES('" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["asunto"]) . "', '" . $_POST["cliente"] . "', '" . $_POST["encargado"][$contador] . "', 0, 1, '" . $idInsertU . "', now())");

		// Obtener datos del encargado (destinatario del correo) por su ID
		$encargadoRow = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT usr_id, usr_nombre, usr_email FROM usuarios WHERE usr_id='" . (int)$_POST["encargado"][$contador] . "'"));
		$contactoCliente = [
			'cont_email' => (!empty($encargadoRow['usr_email']) ? $encargadoRow['usr_email'] : ''),
			'cont_nombre' => (!empty($encargadoRow['usr_nombre']) ? $encargadoRow['usr_nombre'] : 'Encargado')
		];

		// Solo enviar correo si el encargado tiene email
		if (empty($contactoCliente['cont_email'])) {
			$contador++;
			continue;
		}

		$asesorDatos = [
			'usr_email' => $asesor['usr_email'],
			'usr_nombre' => $asesor['usr_nombre']
		];

		$_SESSION["dataAdicional"]["nombre_empresa"] = isset($_SESSION["dataAdicional"]["nombre_empresa"]) ? $_SESSION["dataAdicional"]["nombre_empresa"] : "JM EQUIPOS S.A.S.";

		// URL directa al seguimiento; si el usuario no está logueado, el sistema lo llevará al login y luego aquí
		$urlVerSeguimiento = REDIRECT_ROUTE.'/usuarios/clientes-seguimiento-editar.php?id='.$idInsertU.'&idTK='.$tiketID.'&cte='.$_POST["cliente"];

		$nombreCliente = isset($contactoCLiente['cli_nombre']) ? $contactoCLiente['cli_nombre'] : 'Cliente';
		$fechaProx = !empty($_POST["fechaPC"]) && $_POST["fechaPC"] != '0000-00-00' ? $_POST["fechaPC"] : 'No definida';
		$fin = "
		<p>
		Hola <b>".htmlspecialchars($contactoCliente['cont_nombre'])."</b>,
		</p>
		<p>
		Te informamos que <b>".htmlspecialchars($datosUsuarioActual['usr_nombre'])."</b> te ha asignado un nuevo seguimiento.
		</p>
		<p><strong>Detalles:</strong></p>
		<ul>
			<li><strong>Ticket N.º:</strong> ".$tiketID."</li>
			<li><strong>Seguimiento N.º:</strong> ".$idInsertU."</li>
			<li><strong>Cliente:</strong> ".htmlspecialchars($nombreCliente)."</li>
			<li><strong>Asunto:</strong> ".htmlspecialchars($_POST["asunto"])."</li>
			<li><strong>Fecha próximo contacto:</strong> ".$fechaProx."</li>
		</ul>
		<p><strong>Observaciones:</strong><br><i>".nl2br(htmlspecialchars($_POST["observaciones"]))."</i></p>
		<p>Utiliza el botón inferior para ir directamente al seguimiento. Si no has iniciado sesión, se te pedirá ingresar y luego se te llevará a este seguimiento.</p>
		";

		// 1. Instanciar el servicio
		$mailer = new MailerService();

		// 2. Preparar el contenido del correo usando la plantilla
		$subject = "Nuevo seguimiento asignado - Ticket #".$tiketID." - Seguimiento #".$idInsertU." - ".$nombreCliente;

		$emailData = [
			'subject'       => $subject,
			'app_name'      => $_SESSION["dataAdicional"]["nombre_empresa"],
			'content'       => $fin,
			'button_link'   => $urlVerSeguimiento,
			'button_text'   => 'Ver el seguimiento',
			'support_email' => 'soporte@jmequipos.com',
		];

		$htmlBody = renderEmailTemplate(RUTA_PROYECTO.'/usuarios/plantillas_email/notificaciones.html', $emailData);
		$textBody = strip_tags($htmlBody); // Siempre genera una versión de texto plano

		// 3. Definir destinatarios adicionales (asesor en CC)
		$ccRecipients = [];
		if (!empty($asesorDatos['usr_email'])) {
			$ccRecipients[] = $asesorDatos['usr_email'];
		}

		// 4. Enviar el correo usando el método sendEmail
		if ($mailer->sendEmail(
			$contactoCliente['cont_email'],
			$contactoCliente['cont_nombre'],
			$subject,
			$htmlBody,
			$textBody,
			[], // No hay adjuntos en este ejemplo
			$ccRecipients // El asesor en CC
		)) {
			echo "Correo de seguimiento enviado con éxito.<br>";
		} else {
			echo "Fallo el envío del correo de seguimiento.<br>";
		}

		$contador++;
	}


}

if ($_POST["notfCliente"] == 1 and $_POST["canalPC"] != 4) {
	$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='" . $_POST["cliente"] . "'"));
	$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='" . $_POST["encargado"] . "'"));
	$fin =  '<html><body style="background-color:' . $configuracion["conf_fondo_boletin"] . ';">';
	$fin .= '
				<center>
					<p align="center"><img src="' . $configuracion["conf_url_encuestas"] . '/usuarios/files/' . $configuracion["conf_logo"] . '" width="350"></p>
					<div style="font-family:arial; background:' . $configuracion["conf_fondo_mensaje"] . '; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
						
						<p style="color:' . $configuracion["conf_color_letra"] . ';">' . strtoupper($cliente['cli_nombre']) . ',<br>
						Le informamos que se está haciendo un seguimiento.<br>
						<b>ALGUNOS DETALLES</b><br>
						Asunto: ' . $_POST["asunto"] . '<br>
						Fecha próximo contacto: ' . $_POST["fechaPC"] . '<br>
						Encargado próximo contacto: ' . $contacto['usr_nombre'] . '<br>
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
	// $fin .= '';
	// $fin .=  '<html><body>';
	// $sfrom = $configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
	// $sdestinatario = $cliente['cli_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
	// $ssubject = "CRM - Seguimiento a clientes"; //ASUNTO DEL MENSAJE 				
	// $shtml = $fin; //MENSAJE EN SI			
	// $sheader = "From:" . $sfrom . "\nReply-To:" . $sfrom . "\n";
	// $sheader = $sheader . "X-Mailer:PHP/" . phpversion() . "\n";
	// $sheader = $sheader . "Mime-Version: 1.0\n";
	// $sheader = $sheader . "Content-Type: text/html; charset=UTF-8\r\n";
	// @mail($sdestinatario, $ssubject, $shtml, $sheader);
}

if ($_POST["canalPC"] == 4) {
	$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='" . $_POST["cliente"] . "'"));
	$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='" . $_POST["encargado"] . "'"));
	$fin =  '<html><body style="background-color:' . $configuracion["conf_fondo_boletin"] . ';">';
	$fin .= '
				<center>
					<p align="center"><img src="' . $configuracion["conf_url_encuestas"] . '/usuarios/files/' . $configuracion["conf_logo"] . '" width="350"></p>
					<div style="font-family:arial; background:' . $configuracion["conf_fondo_mensaje"] . '; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
						
						<p style="color:' . $configuracion["conf_color_letra"] . ';">' . strtoupper($cliente['cli_nombre']) . ',<br>
						Le informamos que se ha programado una visita para la fecha <b>' . $_POST["fechaPC"] . '</b>, con el asesor <b>' . $contacto['usr_nombre'] . '</b>.<br>
						<b>ALGUNOS DETALLES</b><br>
						Asunto: ' . $_POST["asunto"] . '<br>
						Fecha próximo contacto: ' . $_POST["fechaPC"] . '<br>
						Encargado próximo contacto: ' . $contacto['usr_nombre'] . '<br>
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
	// $fin .= '';
	// $fin .=  '<html><body>';
	// $sfrom = $configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
	// $sdestinatario = $cliente['cli_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
	// $ssubject = "Visita programada - JMEQUIPOS"; //ASUNTO DEL MENSAJE 				
	// $shtml = $fin; //MENSAJE EN SI			
	// $sheader = "From:" . $sfrom . "\nReply-To:" . $sfrom . "\n";
	// $sheader = $sheader . "X-Mailer:PHP/" . phpversion() . "\n";
	// $sheader = $sheader . "Mime-Version: 1.0\n";
	// $sheader = $sheader . "Content-Type: text/html; charset=UTF-8\r\n";
	// @mail($sdestinatario, $ssubject, $shtml, $sheader);
}

echo '<script type="text/javascript">window.location.href="../clientes-seguimiento-editar.php?id=' . $idInsertU . '&msg=1&idTK=' . $tiketID . '&cte=' . $_POST["cliente"] . '";</script>';
exit();