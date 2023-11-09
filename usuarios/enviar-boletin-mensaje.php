
<?php
 include("sesion.php");?>
<?php
$idPagina = 318;
?>
<?php include("includes/verificar-paginas.php");

$tituloMsj = $_POST["asunto"];
$bgTitulo = "#4086f4";
$contenidoMsj = '
		<p>
			Hola!<br>
			<b>' . strtoupper($cumple["uss_nombre"]) . '</b>, Este mensaje es para ti.
		</p>
		
		<p>' . $_POST["mensaje"] . '</p>
	';

$fin =  '<html><body style="background-color:#FFF;">';
$fin .= '
				<center>
					<div style="width:600px; text-align:justify; padding:15px;">
						<img src="http://plataformasintia.com/images/logo.png" width="40">
					</div>

					<div style="font-family:arial; background:' . $bgTitulo . '; width:600px; color:#FFF; text-align:center; padding:15px;">
						<h3>' . $tituloMsj . '</h3>
					</div>

					<div style="font-family:arial; background:#FAFAFA; width:600px; color:#000; text-align:justify; padding:15px;">
						' . $contenidoMsj . '
					</div>

					<div align="center" style="width:600px; color:#000; text-align:center; padding:15px;">
							<img src="http://plataformasintia.com/images/logo.png" width="30"><br>
							¡Que tengas un excelente d&iacute;a!<br>
							<a href="https://plataformasintia.com/">www.plataformasintia.com</a>
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
	$mail->Password   = $configuracion['conf_clave_correo'];                          // SMTP password
	$mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
	$mail->Port       = 465;                                   // TCP port to connect to

	//Recipients
	$mail->setFrom($configuracion['conf_email'], '');
	//$mail->addAddress('noreply@softjm.com');   // Add a recipient

	$numero = (count($_POST["zonas"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {
			$clientes = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes
				INNER JOIN localidad_ciudades ON ciu_departamento='" . $_POST["zonas"][$contador] . "' AND ciu_id=cli_ciudad");
			
			while ($ctes = mysqli_fetch_array($clientes)) {
				if ($ctes['cli_email'] != "" and !is_null($ctes['cli_email']))
					$mail->addBCC($ctes['cli_email'], $ctes['cli_nombre']);
			}
			$contador++;
		}
	}

	$numero = (count($_POST["tipos"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {
			$clientes = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_categoria='" . $_POST["tipos"][$contador] . "'");
			
			while ($ctes = mysqli_fetch_array($clientes)) {
				if ($ctes['cli_email'] != "" and !is_null($ctes['cli_email']))
					$mail->addBCC($ctes['cli_email'], $ctes['cli_nombre']);
			}
			$contador++;
		}
	}

	$numero = (count($_POST["grupos"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {
			$clientes = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes
				INNER JOIN clientes_categorias ON cpcat_categoria='" . $_POST["grupos"][$contador] . "' AND cpcat_cliente=cli_id
				GROUP BY cli_id");
			
			while ($ctes = mysqli_fetch_array($clientes)) {
				if ($ctes['cli_email'] != "" and !is_null($ctes['cli_email']))
					$mail->addBCC($ctes['cli_email'], $ctes['cli_nombre']);
			}
			$contador++;
		}
	}

	$numero = (count($_POST["clientes"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {
			$clientes = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes
				WHERE cli_id='" . $_POST["clientes"][$contador] . "'");
			
			while ($ctes = mysqli_fetch_array($clientes)) {
				if ($ctes['cli_email'] != "" and !is_null($ctes['cli_email']))
					$mail->addBCC($ctes['cli_email'], $ctes['cli_nombre']);
			}
			$contador++;
		}
	}

	if ($_FILES['boletin']['name'] != "") {
		$archivo = $_FILES['boletin']['name'];
		$destino = "files/adjuntos";
		move_uploaded_file($_FILES['boletin']['tmp_name'], $destino . "/" . $archivo);

		// Attachments
		$mail->addAttachment('files/adjuntos/' . $archivo);    // Optional name
	}



	// Content
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->Subject = $_POST["asunto"];
	$mail->Body = $fin;
	$mail->CharSet = 'UTF-8';

	$mail->send();
	echo 'Enviado mensaje masivo.';
} catch (Exception $e) {
	echo "Error: {$mail->ErrorInfo}";
	exit();
}

echo '<script type="text/javascript">window.location.href="enviar-mensaje.php?envd=1";</script>';
exit();
