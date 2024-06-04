<?php
require_once("../sesion.php");

require '../../librerias/phpmailer/Exception.php';
require '../../librerias/phpmailer/PHPMailer.php';
require '../../librerias/phpmailer/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

	/*if($_POST["idTK"]==""){
		mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_tikets(tik_asunto_principal, tik_tipo_tiket, tik_fecha_creacion, tik_usuario_responsable, tik_estado, tik_cliente, tik_prioridad, tik_observaciones, tik_canal)VALUES('TIKET AUTOMÁTICO','".$_POST["tipoS"]."','".$_POST["fechaContacto"]."','".$_SESSION["id"]."',2,'".$_POST["cliente"]."',1,'".$_POST["observaciones"]."','".$_POST["canal"]."')",$conexion);
		if(mysql_errno()!=0){echo informarErrorAlUsuario(__LINE__, mysql_error()); exit();}
		$tiketID = mysqli_insert_id($conexionBdPrincipal);
	}else{
		$tiketID = $_POST["idTK"];
	}*/
	if (empty($_POST["idTK"]) && empty($_POST["tiketCreado"])) {
		mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_tikets(tik_asunto_principal, tik_tipo_tiket, tik_fecha_creacion, tik_usuario_responsable, tik_estado, tik_cliente, tik_prioridad, tik_observaciones, tik_canal)
		VALUES('TIKCET AUTOMÁTICO',1,'" . $_POST["fechaContacto"] . "','" . $_SESSION["id"] . "',2,'" . $_POST["cliente"] . "',1,'" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["observaciones"]) . "','" . $_POST["canal"] . "')");
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
	if ($_POST["cotizo"] == 1) {
		$cotizo = 1;
	}

	$vendio = 0;
	if ($_POST["vendio"] == 1) {
		$vendio = 1;
	}

	if (!empty($_POST["encargado"])) {
		$numero = (count($_POST["encargado"]));

		if ($numero == 1) {
			mysqli_query($conexionBdPrincipal,"INSERT INTO cliente_seguimiento(cseg_cliente, cseg_fecha_reporte, cseg_observacion, cseg_usuario_responsable, cseg_fecha_proximo_contacto, cseg_asunto, cseg_usuario_encargado, cseg_cotizacion, cseg_fecha_contacto, cseg_tipo, cseg_contacto, cseg_tiket, cseg_canal, cseg_canal_proximo_contacto, cseg_archivo, cseg_cotizo, cseg_vendio, cseg_consiguio_datos, cseg_forma_contacto)VALUES('" . $_POST["cliente"] . "',now(),'" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["observaciones"]) . "','" . $_SESSION["id"] . "','" . $_POST["fechaPC"] . "','" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["asunto"]) . "','" . $_POST["encargado"][0] . "','" . $_POST["cotizacion"] . "','" . $_POST["fechaContacto"] . "','" . $_POST["tipoS"] . "','" . $_POST["contacto"] . "','" . $tiketID . "','" . $_POST["canal"] . "','" . $_POST["canalPC"] . "','" . $archivo . "','" . $cotizo . "','" . $vendio . "','" . $datos . "','" . $_POST["formaContacto"] . "')");
			$idInsertU = mysqli_insert_id($conexionBdPrincipal);
		} elseif ($numero > 1) {
			mysqli_query($conexionBdPrincipal,"INSERT INTO cliente_seguimiento(cseg_cliente, cseg_fecha_reporte, cseg_observacion, cseg_usuario_responsable, cseg_fecha_proximo_contacto, cseg_asunto, cseg_cotizacion, cseg_fecha_contacto, cseg_tipo, cseg_contacto, cseg_tiket, cseg_canal, cseg_canal_proximo_contacto, cseg_varios, cseg_archivo, cseg_forma_contacto)VALUES('" . $_POST["cliente"] . "',now(),'" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["observaciones"]) . "','" . $_SESSION["id"] . "','" . $_POST["fechaPC"] . "','" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["asunto"]) . "','" . $_POST["cotizacion"] . "','" . $_POST["fechaContacto"] . "','" . $_POST["tipoS"] . "','" . $_POST["contacto"] . "','" . $tiketID . "','" . $_POST["canal"] . "','" . $_POST["canalPC"] . "','" . $numero . "','" . $archivo . "','" . $_POST["formaContacto"] . "')");
			$idInsertU = mysqli_insert_id($conexionBdPrincipal);
		}
	} else {
		mysqli_query($conexionBdPrincipal,"INSERT INTO cliente_seguimiento(cseg_cliente, cseg_fecha_reporte, cseg_observacion, cseg_usuario_responsable, cseg_cotizacion, cseg_fecha_contacto, cseg_tipo, cseg_contacto, cseg_tiket, cseg_canal, cseg_varios, cseg_archivo, cseg_forma_contacto)VALUES('" . $_POST["cliente"] . "',now(),'" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["observaciones"]) . "','" . $_SESSION["id"] . "','" . $_POST["cotizacion"] . "','" . $_POST["fechaContacto"] . "','" . $_POST["tipoS"] . "','" . $_POST["contacto"] . "','" . $tiketID . "','" . $_POST["canal"] . "','" . $numero . "','" . $archivo . "','" . $_POST["formaContacto"] . "')");
		$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	}


	if ($_POST["cerrarTK"] == 1) {
		mysqli_query($conexionBdPrincipal,"UPDATE clientes_tikets SET tik_estado='".TIK_ESTADO_CERRADO."' WHERE tik_id='" . $tiketID . "'");

		mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_realizado=1 WHERE cseg_id='" . $idInsertU . "'");
	}

	$portafNombres = array("", "Topografía", "Construcción y Arquitectura", "Accesorios", "Agricultura", "Cartografía", "Completo Exacta Ing.", "Brochure Laboratorio", "Portafolio Drones", "Portafolio Estaciones totales");

    if(!empty($_POST["portafolios"])){
        $numero = (count($_POST["portafolios"]));
        if ($numero > 0) {
            $contador = 0;
            while ($contador < $numero) {
                $portafolios .= '<a href="'.REDIRECT_ROUTE.'/usuarios/files/portafolios/' . $_POST["portafolios"][$contador] . '.pdf">' . $portafNombres[$_POST["portafolios"][$contador]] . '</a><br>';
                $contador++;
            }
        }
        $numC = strlen($portafolios) - 1;
        $portafolios = substr($portafolios, 0, $numC);



        if ($numero > 0) {
            $contactoCLiente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='" . $_POST["contacto"] . "'"));

            $asesor = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='" . $_SESSION["id"] . "'"));

            $fin =  '<html><body style="background-color:' . $configuracion["conf_fondo_boletin"] . ';">';
            $fin .= '
                        <center>
                            <div style="font-family:arial; background:' . $configuracion["conf_fondo_mensaje"] . '; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">

                                <p style="color:' . $configuracion["conf_color_letra"] . ';">
                                Cordial saludo estimado ' . strtoupper($contactoCLiente['cont_nombre']) . ',<br>
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

                                <p align="center" style="color:' . $configuracion["conf_color_letra"] . ';">
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
            echo '<div style="display:none;">';
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

                $mail->addAddress($contactoCLiente['cont_email'], $contactoCLiente['cont_nombre']);     // Add a recipient
                $mail->addAddress($asesor['usr_email'], $asesor['usr_nombre']);     // Add a recipient



                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = "PORTAFOLIO ".$_SESSION["dataAdicional"]['nombre_empresa'];
                $mail->Body = $fin;
                $mail->CharSet = 'UTF-8';

                $mail->send();
                echo 'Enviado portafolio al cliente.';

                mysqli_query($conexionBdPrincipal,"INSERT INTO buzon_salida(buz_remite, buz_destino, buz_tipo, buz_estado, buz_observacion, buz_referencia, buz_cliente, buz_usuario, buz_contacto)VALUES('" . $asesor['usr_email'] . "', '" . $contactoCLiente['cont_email'] . "', 1, 1, 'Enviados correctamente.<br> Portafolios:<br> " . $portafolios . "', '" . $idInsertU . "', '" . $contactoCLiente['cont_cliente_principal'] . "', '" . $_SESSION["id"] . "', '" . $contactoCLiente['cont_id'] . "')");
            } catch (Exception $e) {
                echo "Error: {$mail->ErrorInfo}";

                mysqli_query($conexionBdPrincipal,"INSERT INTO buzon_salida(buz_remite, buz_destino, buz_tipo, buz_estado, buz_observacion, buz_referencia, buz_cliente, buz_usuario, buz_contacto)VALUES('" . $asesor['usr_email'] . "', '" . $contactoCLiente['cont_email'] . "', 1, 2, 'Error al enviar desde seguimiento.<br> Portafolios:<br> " . $portafolios . "<br>" . $mail->ErrorInfo . "', '" . $idInsertU . "', '" . $contactoCLiente['cont_cliente_principal'] . "', '" . $_SESSION["id"] . "', '" . $contactoCLiente['cont_id'] . "')");
            }
            echo '</div>';
        }
    }


	/*
	if ($_POST["notf"] == 1) {

		$contador = 0;
		while ($contador < $numero) {
			mysqli_query($conexionBdPrincipal,"INSERT INTO notificaciones(not_asunto, not_cliente, not_usuario, not_visto, not_estado, not_seguimiento, not_fecha)VALUES('" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["asunto"]) . "', '" . $_POST["cliente"] . "', '" . $_POST["encargado"][$contador] . "', 0, 1, '" . $idInsertU . "', now())");
			
		}

		$cliente = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='" . $_POST["cliente"] . "'"));

		$fin =  '<html><body style="background-color:' . $configuracion["conf_fondo_boletin"] . ';">';
		$fin .= '
					<center>
						<p align="center"><img src="' . $configuracion["conf_url_encuestas"] . '/usuarios/files/' . $configuracion["conf_logo"] . '" width="350"></p>
						<div style="font-family:arial; background:' . $configuracion["conf_fondo_mensaje"] . '; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
							
							<p style="color:' . $configuracion["conf_color_letra"] . ';">' . strtoupper($contacto['usr_nombre']) . ',<br>
							Te han encargado un nuevo seguimiento para uno de los clientes.<br>
							<b>ALGUNOS DETALLES</b><br>
							Asunto: ' . $_POST["asunto"] . '<br>
							Cliente: ' . $cliente['cli_nombre'] . '<br>
							Para revisar este pendiente ingresa al CRM con tus datos de acceso, mediante el siguiente link.</p>
							
							<p align="center"><a href="http://softjm.com/index.php?idseg=' . $idInsertU . '" target="_blank" style="color:' . $configuracion["conf_color_link"] . ';">IR AL SEGUIMIENTO</a></p>
							
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
		$sfrom = $configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario = $contacto['usr_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject = "ORIÓN - Seguimiento a clientes"; //ASUNTO DEL MENSAJE 				
		$shtml = $fin; //MENSAJE EN SI			
		$sheader = "From:" . $sfrom . "\nReply-To:" . $sfrom . "\n";
		$sheader = $sheader . "X-Mailer:PHP/" . phpversion() . "\n";
		$sheader = $sheader . "Mime-Version: 1.0\n";
		$sheader = $sheader . "Content-Type: text/html; charset=UTF-8\r\n";
		@mail($sdestinatario, $ssubject, $shtml, $sheader);
	}*/

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
		$fin .= '';
		$fin .=  '<html><body>';
		$sfrom = $configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario = $cliente['cli_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject = "CRM - Seguimiento a clientes"; //ASUNTO DEL MENSAJE 				
		$shtml = $fin; //MENSAJE EN SI			
		$sheader = "From:" . $sfrom . "\nReply-To:" . $sfrom . "\n";
		$sheader = $sheader . "X-Mailer:PHP/" . phpversion() . "\n";
		$sheader = $sheader . "Mime-Version: 1.0\n";
		$sheader = $sheader . "Content-Type: text/html; charset=UTF-8\r\n";
		@mail($sdestinatario, $ssubject, $shtml, $sheader);
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
		$fin .= '';
		$fin .=  '<html><body>';
		$sfrom = $configuracion['conf_email']; //LA CUETA DEL QUE ENVIA EL MENSAJE			
		$sdestinatario = $cliente['cli_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
		$ssubject = "Visita programada - JMEQUIPOS"; //ASUNTO DEL MENSAJE 				
		$shtml = $fin; //MENSAJE EN SI			
		$sheader = "From:" . $sfrom . "\nReply-To:" . $sfrom . "\n";
		$sheader = $sheader . "X-Mailer:PHP/" . phpversion() . "\n";
		$sheader = $sheader . "Mime-Version: 1.0\n";
		$sheader = $sheader . "Content-Type: text/html; charset=UTF-8\r\n";
		@mail($sdestinatario, $ssubject, $shtml, $sheader);
	}

	echo '<script type="text/javascript">window.location.href="../clientes-seguimiento-editar.php?id=' . $idInsertU . '&msg=1&idTK=' . $tiketID . '&cte=' . $_POST["cliente"] . '";</script>';
	exit();