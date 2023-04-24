<?php
require_once("sesion.php");

$configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1"), MYSQLI_BOTH);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../librerias/phpmailer/Exception.php';
require '../librerias/phpmailer/PHPMailer.php';
require '../librerias/phpmailer/SMTP.php';
//AGREGAR USUARIOS

//EDITAR USUARIOS

//AGREGAR ROLES

//EDITAR ROLES

//AGREGAR CLIENTES


//AGREGAR SEGUIMIENTO CLIENTES

//EDITAR SEGUIMIENTO CLIENTES

//AGREGAR MARCAS

//EDITAR MARCAS

//AGREGAR FACTURAS

//EDITAR FACTURAS

//AGREGAR DOCUMENTOS
if ($_POST["idSql"] == 13) {
	if ($_FILES['archivo']['name'] != "") {
		$archivo = $_FILES['archivo']['name'];
		$destino = "files/documentos";
		move_uploaded_file($_FILES['archivo']['tmp_name'], $destino . "/" . $archivo);
	}
	mysqli_query($conexionBdPrincipal,"INSERT INTO documentos(doc_nombre, doc_documento, doc_cliente)VALUES('" . $_POST["nombre"] . "','" . $archivo . "','" . $_POST["cliente"] . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="documentos-editar.php?id=' . $idInsertU . '&msg=1&cte=' . $_POST["cte"] . '";</script>';
	exit();
}
//EDITAR DOCUMENTOS
if ($_POST["idSql"] == 14) {
	if ($_FILES['archivo']['name'] != "") {
		$archivo = $_FILES['archivo']['name'];
		$destino = "files/documentos";
		move_uploaded_file($_FILES['archivo']['tmp_name'], $destino . "/" . $archivo);
		mysqli_query($conexionBdPrincipal,"UPDATE documentos SET doc_documento='" . $archivo . "' WHERE doc_id='" . $_POST["id"] . "'");
	}
	mysqli_query($conexionBdPrincipal,"UPDATE documentos SET doc_nombre='" . $_POST["nombre"] . "', doc_cliente='" . $_POST["cliente"] . "' WHERE doc_id='" . $_POST["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="documentos-editar.php?id=' . $_POST["id"] . '&msg=2&cte=' . $_POST["cte"] . '";</script>';
	exit();
}
//AGREGAR MOMENTOS
if ($_POST["idSql"] == 15) {
	mysqli_query($conexionBdPrincipal,"INSERT INTO momentos(mom_cliente, mom_nombre, mom_fecha_creacion)VALUES('" . $_POST["cte"] . "','" . $_POST["nombre"] . "',now())");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="clientes-momentos-editar.php?id=' . $idInsertU . '&msg=1&cte=' . $_POST["cte"] . '";</script>';
	exit();
}
//EDITAR MOMENTOS
if ($_POST["idSql"] == 16) {
	mysqli_query($conexionBdPrincipal,"UPDATE momentos SET mom_nombre='" . $_POST["nombre"] . "' WHERE mom_id='" . $_POST["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="clientes-momentos-editar.php?id=' . $_POST["id"] . '&msg=2&cte=' . $_POST["cte"] . '";</script>';
	exit();
}
//AGREGAR DEALER/GRUPOS
if ($_POST["idSql"] == 17) {
	mysqli_query($conexionBdPrincipal,"INSERT INTO dealer(deal_nombre)VALUES('" . $_POST["nombre"] . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	$numero = (count($_POST["clientes"]));
	$contador = 0;
	mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_categorias WHERE cpcat_categoria='" . $idInsertU . "'");
	
	while ($contador < $numero) {
		mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_categorias(cpcat_cliente, cpcat_categoria)VALUES(" . $_POST["clientes"][$contador] . ",'" . $idInsertU . "')");
		
		$contador++;
	}
	echo '<script type="text/javascript">window.location.href="dealer-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();
}
//EDITAR DEALER/GRUPOS
if ($_POST["idSql"] == 18) {
	mysqli_query($conexionBdPrincipal,"UPDATE dealer SET deal_nombre='" . $_POST["nombre"] . "' WHERE deal_id='" . $_POST["id"] . "'");
	
	$numero = (count($_POST["clientes"]));
	$contador = 0;
	mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_categorias WHERE cpcat_categoria='" . $_POST["id"] . "'");
	
	while ($contador < $numero) {
		mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_categorias(cpcat_cliente, cpcat_categoria)VALUES(" . $_POST["clientes"][$contador] . ",'" . $_POST["id"] . "')");
		
		$contador++;
	}
	echo '<script type="text/javascript">window.location.href="dealer-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();
}
//AGREGAR PRODUCTOS

//EDITAR PRODUCTOS

//AGREGAR CATEGORIA PRODUCTOS

//EDITAR CATEGORIA PRODUCTOS

//aqui estaba configuración

//AGREGAR CONTACTOS

//EDITAR CONTACTOS

//AGREGAR ZONAS
if ($_POST["idSql"] == 26) {
	mysqli_query($conexionBdPrincipal,"INSERT INTO zonas(zon_nombre, zon_observaciones)VALUES('" . $_POST["nombre"] . "','" . $_POST["observaciones"] . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="zonas-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();
}
//EDITAR ZONAS
if ($_POST["idSql"] == 27) {
	mysqli_query($conexionBdPrincipal,"UPDATE zonas SET zon_nombre='" . $_POST["nombre"] . "', zon_observaciones='" . $_POST["observaciones"] . "' WHERE zon_id='" . $_POST["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="zonas-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();
}
//AGREGAR ENCUESTAS
if ($_POST["idSql"] == 28) {
	mysqli_query($conexionBdPrincipal,"INSERT INTO encuesta_satisfaccion(encs_fecha, encs_cliente, encs_atendido, encs_producto, encs_contacto)VALUES(now(),'" . $_POST["cliente"] . "','" . $_POST["usuario"] . "','" . $_POST["producto"] . "','" . $_POST["contacto"] . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);

	$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='" . $_POST["contacto"] . "'"));
	$fin =  '<html><body style="background-color:' . $configuracion["conf_fondo_boletin"] . ';">';
	$fin .= '
				<center>
					<p align="center"><img src="' . $configuracion["conf_url_encuestas"] . '/usuarios/files/' . $configuracion["conf_logo"] . '" width="350"></p>
					<div style="font-family:arial; background:' . $configuracion["conf_fondo_mensaje"] . '; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
						
						<p style="color:' . $configuracion["conf_color_letra"] . ';">' . strtoupper($contacto['cont_nombre']) . ',<br>
						Agradecemos se tome 3 minutos para responder una escuesta sobre la atención brindada por nuestra empresa.<br>
						Haga click en el siguiente enlace para responder la encuesta.</p>
						
						<p align="center"><a href="' . $configuracion["conf_url_encuestas"] . '/formato-encuesta.php?id=' . $idInsertU . '" target="_blank" style="color:' . $configuracion["conf_color_link"] . ';">RESPONDER ENCUESTA</a></p>
						
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
	$sdestinatario = $contacto['cont_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
	$ssubject = "Encuesta de satisfaccion"; //ASUNTO DEL MENSAJE 				
	$shtml = $fin; //MENSAJE EN SI			
	$sheader = "From:" . $sfrom . "\nReply-To:" . $sfrom . "\n";
	$sheader = $sheader . "X-Mailer:PHP/" . phpversion() . "\n";
	$sheader = $sheader . "Mime-Version: 1.0\n";
	$sheader = $sheader . "Content-Type: text/html; charset=UTF-8\r\n";
	@mail($sdestinatario, $ssubject, $shtml, $sheader);
	echo '<script type="text/javascript">window.open("../formato-encuesta.php?id=' . $idInsertU . '");</script>';
	echo '<script type="text/javascript">window.location.href="encuesta-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();
}
//EDITAR ENCUESTAS
if ($_POST["idSql"] == 29) {
	mysqli_query($conexionBdPrincipal,"UPDATE encuesta_satisfaccion SET encs_cliente='" . $_POST["cliente"] . "', encs_atendido='" . $_POST["usuario"] . "', encs_producto='" . $_POST["producto"] . "' WHERE encs_id='" . $_POST["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="encuesta-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();
}
//AGREGAR MATERIALES A PRODUCTOS
if ($_POST["idSql"] == 30) {
	$numero = (count($_FILES['documento']['name']));
	if ($numero > 0 and $_FILES['documento']['name'][0] != "") {
		$contador = 0;
		while ($contador < $numero) {
			$extension = end(explode(".", $_FILES['documento']['name'][$contador]));
			$archivo = uniqid('file_') . "." . $extension;

			//$archivo = $_FILES['documento']['name'][$contador];
			$destino = "files/materiales";
			move_uploaded_file($_FILES['documento']['tmp_name'][$contador], $destino . "/" . $archivo);
			$material = $archivo;
			mysqli_query($conexionBdPrincipal,"INSERT INTO productos_materiales(ppmt_material, ppmt_tipo, ppmt_activo, ppmt_producto, ppmt_nombre)VALUES('" . $material . "','" . $_POST["tipo"] . "','" . $_POST["activo"] . "','" . $_POST["pdto"] . "','" . $_POST["nombre"] . "')");
			
			$contador++;
		}
	} else {
		$material = $_POST["video"];
		mysqli_query($conexionBdPrincipal,"INSERT INTO productos_materiales(ppmt_material, ppmt_tipo, ppmt_activo, ppmt_producto, ppmt_nombre)VALUES('" . $material . "','" . $_POST["tipo"] . "','" . $_POST["activo"] . "','" . $_POST["pdto"] . "','" . $_POST["nombre"] . "')");
		
		$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	}
	echo '<script type="text/javascript">window.location.href="productos-materiales.php?msg=1&pdto=' . $_POST["pdto"] . '";</script>';
	exit();
}
//EDITAR MATERIALES A PRODUCTOS
if ($_POST["idSql"] == 31) {

	if ($_FILES['documento']['name'] != "") {

		$extension = end(explode(".", $_FILES['documento']['name']));
		$archivo = uniqid('file_') . "." . $extension;

		//$archivo = $_FILES['documento']['name'][$contador];
		$destino = "files/materiales";
		move_uploaded_file($_FILES['documento']['tmp_name'], $destino . "/" . $archivo);
		$material = $archivo;
		mysqli_query($conexionBdPrincipal,"UPDATE productos_materiales SET ppmt_material='" . $material . "' WHERE ppmt_id='" . $_POST["id"] . "'");
		
	} else {
		$material = $_POST["video"];
		if ($_POST["tipo"] == 2) {
			mysqli_query($conexionBdPrincipal,"UPDATE productos_materiales SET ppmt_material='" . $material . "' WHERE ppmt_id='" . $_POST["id"] . "'");
		}
	}
	mysqli_query($conexionBdPrincipal,"UPDATE productos_materiales SET ppmt_tipo='" . $_POST["tipo"] . "', ppmt_activo='" . $_POST["activo"] . "', ppmt_nombre='" . $_POST["nombre"] . "' WHERE ppmt_id='" . $_POST["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="productos-materiales.php?msg=2&pdto=' . $_POST["pdto"] . '";</script>';
	exit();
}
//ENVIAR BOLETIN  DE MENSAJES
if ($_POST["idSql"] == 32) {

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
}
//AGREGAR ORDENES DE SERVICIO
if ($_POST["idSql"] == 33) {
	if ($_POST["fechaFin"] == "") $_POST["fechaFin"] = '0000-00-00';
	if ($_POST["ord_fecha_entrega"] == "") $_POST["ord_fecha_entrega"] = '0000-00-00';
	mysqli_query($conexionBdPrincipal,"INSERT INTO ordenes_servicio(ord_fecha_registro, ord_fecha_solicitud, ord_fecha_fin, ord_contacto_cliente, ord_descripcion, ord_canal, ord_estado, ord_observaciones, ord_prioridad, ord_fecha_entrega)VALUES(now(),'" . $_POST["fechaSolicitud"] . "','" . $_POST["fechaFin"] . "','" . $_POST["contacto"] . "','" . $_POST["descripcion"] . "','" . $_POST["canal"] . "','" . $_POST["estado"] . "','" . $_POST["observaciones"] . "','" . $_POST["prioridad"] . "','" . $_POST["fechaIdeal"] . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="ordenes-servicio-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();
}
//EDITAR ORDENES DE SERVICIO
if ($_POST["idSql"] == 34) {
	if ($_POST["fechaFin"] == "") $_POST["fechaFin"] = '0000-00-00';
	if ($_POST["ord_fecha_entrega"] == "") $_POST["ord_fecha_entrega"] = '0000-00-00';
	mysqli_query($conexionBdPrincipal,"UPDATE ordenes_servicio SET ord_fecha_solicitud='" . $_POST["fechaSolicitud"] . "', ord_fecha_fin='" . $_POST["fechaFin"] . "', ord_contacto_cliente='" . $_POST["contacto"] . "', ord_descripcion='" . $_POST["descripcion"] . "', ord_canal='" . $_POST["canal"] . "', ord_estado='" . $_POST["estado"] . "', ord_observaciones='" . $_POST["observaciones"] . "', ord_prioridad='" . $_POST["prioridad"] . "', ord_fecha_entrega='" . $_POST["fechaIdeal"] . "' WHERE ord_id='" . $_POST["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="ordenes-servicio-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();
}

/* 
* Se cambió el 35 y 36 para paginas a parte. Guardar y actualizar cotizaciones.
*/




//AGREGAR SUCURSALES

//EDITAR SUCURSALES

//AGREGAR TIKCETS CLIENTES

//EDITAR TIKETS CLIENTES

//AGREGAR ABONO A FACTURAS
if ($_POST["idSql"] == 41) {
	if ($_FILES['archivo']['name'] != "") {
		$archivo = $_FILES['archivo']['name'];
		$destino = "files/comprobantes";
		move_uploaded_file($_FILES['archivo']['tmp_name'], $destino . "/" . $archivo);
	}
	mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion_abonos(fpab_factura, fpab_fecha_abono, fpab_valor, fpab_fecha_registro, fpab_observaciones, fpab_medio_pago, fpab_responsable_registro, fpab_comprobante)VALUES('" . $_POST["fact"] . "','" . $_POST["fecha"] . "','" . $_POST["valor"] . "',now(),'" . $_POST["observaciones"] . "','" . $_POST["medio"] . "','" . $_SESSION["id"] . "','" . $archivo . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	//Calculamos el saldo
	$abonos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT sum(fpab_valor), fact_valor, fact_id FROM facturacion_abonos, facturacion
	WHERE fpab_factura='" . $_POST["fact"] . "' AND fact_id='" . $_POST["fact"] . "'"));
	$saldoFinal = $abonos[1] - $abonos[0];
	if ($saldoFinal <= 0) {
		mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=1 WHERE fact_id='" . $_POST["fact"] . "' AND fact_estado!=3");
		
	} else {
		mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=2 WHERE fact_id='" . $_POST["fact"] . "' AND fact_estado!=3");
		
	}

	echo '<script type="text/javascript">window.location.href="facturacion-abonos-editar.php?id=' . $idInsertU . '&msg=1&fact=' . $_POST["fact"] . '";</script>';
	exit();
}
//EDITAR ABONO A FACTURAS
if ($_POST["idSql"] == 42) {
	if ($_FILES['archivo']['name'] != "") {
		$archivo = $_FILES['archivo']['name'];
		$destino = "files/comprobantes";
		move_uploaded_file($_FILES['archivo']['tmp_name'], $destino . "/" . $archivo);
		mysqli_query($conexionBdPrincipal,"UPDATE facturacion_abonos SET fpab_comprobante='" . $archivo . "' WHERE fpab_id='" . $_POST["id"] . "'");
	}
	mysqli_query($conexionBdPrincipal,"UPDATE facturacion_abonos SET fpab_fecha_abono='" . $_POST["fecha"] . "', fpab_valor='" . $_POST["valor"] . "', fpab_observaciones='" . $_POST["observaciones"] . "', fpab_medio_pago='" . $_POST["medio"] . "', fpab_responsable_modificacion='" . $_SESSION["id"] . "', fpab_fecha_ultima_modificacion=now() WHERE fpab_id='" . $_POST["id"] . "'");
	
	//Calculamos el saldo
	$abonos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT sum(fpab_valor), fact_valor, fact_id FROM facturacion_abonos, facturacion
	WHERE fpab_factura='" . $_POST["fact"] . "' AND fact_id='" . $_POST["fact"] . "'"));
	$saldoFinal = $abonos[1] - $abonos[0];
	if ($saldoFinal <= 0) {
		mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=1 WHERE fact_id='" . $_POST["fact"] . "' AND fact_estado!=3");
		
	} else {
		mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=2 WHERE fact_id='" . $_POST["fact"] . "' AND fact_estado!=3");
		
	}

	echo '<script type="text/javascript">window.location.href="facturacion-abonos-editar.php?id=' . $_POST["id"] . '&msg=2&fact=' . $_POST["fact"] . '";</script>';
	exit();
}
//AGREGAR SOPORTE PRODUCTOS
if ($_POST["idSql"] == 43) {
	if ($_FILES['imagen']['name'] != "") {
		$imagen = $_FILES['imagen']['name'];
		$destino = "files/soporte";
		move_uploaded_file($_FILES['imagen']['tmp_name'], $destino . "/" . $imagen);
	}
	mysqli_query($conexionBdPrincipal,"INSERT INTO soporte_productos(sop_nombre, sop_descripcion, sop_imagen, sop_video, sop_nivel, sop_padre)VALUES('" . $_POST["nombre"] . "','" . $_POST["descripcion"] . "','" . $imagen . "','" . $_POST["video"] . "','" . $_POST["nivel"] . "','" . $_POST["padre"] . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="soporte-productos-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();
}
//EDITAR SOPORTE PRODUCTOS
if ($_POST["idSql"] == 44) {
	if ($_FILES['imagen']['name'] != "") {
		$imagen = $_FILES['imagen']['name'];
		$destino = "files/soporte";
		move_uploaded_file($_FILES['imagen']['tmp_name'], $destino . "/" . $imagen);
		mysqli_query($conexionBdPrincipal,"UPDATE soporte_productos SET sop_imagen='" . $imagen . "' WHERE sop_id='" . $_POST["id"] . "'");
	}
	mysqli_query($conexionBdPrincipal,"UPDATE soporte_productos SET sop_nombre='" . $_POST["nombre"] . "', sop_descripcion='" . $_POST["descripcion"] . "', sop_nivel='" . $_POST["nivel"] . "', sop_video='" . $_POST["video"] . "', sop_padre='" . $_POST["padre"] . "' WHERE sop_id='" . $_POST["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="soporte-productos-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();
}
//AGREGAR ASUNTOS DE TIKETS
if ($_POST["idSql"] == 45) {
	mysqli_query($conexionBdPrincipal,"INSERT INTO tikets_asuntos(tkpas_nombre)VALUES('" . $_POST["nombre"] . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="tikets-asuntos-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();
}
//EDITAR ASUNTOS DE TIKETS
if ($_POST["idSql"] == 46) {
	mysqli_query($conexionBdPrincipal,"UPDATE tikets_asuntos SET tkpas_nombre='" . $_POST["nombre"] . "' WHERE tkpas_id='" . $_POST["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="tikets-asuntos-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();
}
//ENVIAR PORTAFOLIOS

//AGREGAR PROYECTOS

//EDITAR PROYECTOS

//AGREGAR TAREAS-PROYECTOS

//EDITAR TAREAS-PROYECTOS

//AGREGAR EVENTOS AL CALENDARIO
if ($_POST["idSql"] == 52) {
	mysqli_query($conexionBdPrincipal,"INSERT INTO agenda(age_evento, age_fecha, age_usuario, age_inicio, age_fin, age_lugar, age_notas, age_cliente)VALUES('" . $_POST["evento"] . "','" . $_POST["fecha"] . "','" . $_SESSION["id"] . "','" . $_POST["inicio"] . "','" . $_POST["fin"] . "','" . $_POST["lugar"] . "','" . $_POST["notas"] . "','" . $_POST["cliente"] . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);

	if ($_POST["cliente"] != '0') {


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
						
						<p align="center"><a href="' . $configuracion["conf_url_encuestas"] . '/usuarios/reportes/formato-cotizacion-1.php?cte=1&id=' . base64_encode($_POST["id"]) . '" target="_blank" 
						
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
			echo 'Enviada cotización al cliente.';
		} catch (Exception $e) {
			echo "Error: {$mail->ErrorInfo}";
		}
	}

	echo '<script type="text/javascript">window.location.href="calendario.php?id=' . $_SESSION["id"] . '";</script>';
	exit();
}
//EDITAR EVENTOS AL CALENDARIO
if ($_POST["idSql"] == 53) {
	mysqli_query($conexionBdPrincipal,"UPDATE agenda SET age_evento='" . $_POST["evento"] . "', age_fecha='" . $_POST["fecha"] . "', age_lugar='" . $_POST["lugar"] . "', age_notas='" . $_POST["notas"] . "', age_inicio='" . $_POST["inicio"] . "', age_fin='" . $_POST["fin"] . "' WHERE age_id='" . $_POST["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="calendario.php?id=' . $_SESSION["id"] . '";</script>';
	exit();
}
//EDITAR PUBLICIDAD DE CONFIGURACIÓN
if ($_POST["idSql"] == 54) {

	if ($_FILES['bTop']['name'] != "") {
		$archivo = $_FILES['bTop']['name'];
		$destino = "files/publicidad";
		move_uploaded_file($_FILES['bTop']['tmp_name'], $destino . "/" . $archivo);
		mysqli_query($conexionBdPrincipal,"UPDATE configuracion SET conf_banner_top='" . $archivo . "' WHERE conf_id=1");
		
	}

	if ($_FILES['bLat']['name'] != "") {
		$archivo = $_FILES['bLat']['name'];
		$destino = "files/publicidad";
		move_uploaded_file($_FILES['bLat']['tmp_name'], $destino . "/" . $archivo);
		mysqli_query($conexionBdPrincipal,"UPDATE configuracion SET conf_banner_lateral='" . $archivo . "' WHERE conf_id=1");
		
	}

	mysqli_query($conexionBdPrincipal,"UPDATE configuracion SET conf_url_top='" . $_POST["urlTop"] . "', conf_url_lateral='" . $_POST["urlLat"] . "' WHERE conf_id=1");
	

	echo '<script type="text/javascript">window.location.href="publicidad.php?msg=2";</script>';
	exit();
}
//AGREGAR CUPONES
if ($_POST["idSql"] == 55) {
	mysqli_query($conexionBdPrincipal,"INSERT INTO cupones(cupo_codigo, cupo_descuento, cupo_activo, cupo_redimido, cupo_compra_minima, cupo_creacion, cupo_vencimiento, cupo_cliente)VALUES('" . $_POST["codigo"] . "','" . $_POST["descuento"] . "','" . $_POST["estado"] . "',0,'" . $_POST["compraMinima"] . "',now(),'" . $_POST["fechaVencimiento"] . "','" . $_POST["cliente"] . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);

	echo '<script type="text/javascript">window.location.href="cupones.php";</script>';
	exit();
}
//CONDICIONAR PRODUCTOS

//ENVIAR COTIZACIÓN AL CORREO ELABORADO

//AGREGAR SERVICIOS

//EDITAR SERVICIOS

//AGREGAR COMBOS

//EDITAR COMBOS

//GESTIONAR PRODUCTOS WEB - STORE JM

//SUBIR FOTOS A LOS PRODUCTOS

//AGREGAR AREAS

//EDITAR AREAS

//ESTABA AQUI EDITAR PERFIL

//GESTIONAR PRODUCTOS CON PRECIO PREDETERMINADO

//REGISTRAR GASTOS
if ($_POST["idSql"] == 69) {
	mysqli_query($conexionBdPrincipal,"INSERT INTO gastos(gasv_fecha, gasv_registro, gasv_concepto, gasv_valor, gasv_responsable)
	VALUES('" . $_POST["fecha"] . "', now(), '" . $_POST["concepto"] . "', '" . $_POST["valor"] . "', '" . $_SESSION["id"] . "')");
	
	$idRegistro = mysqli_insert_id($conexionBdPrincipal);

	echo '<script type="text/javascript">window.location.href="viaticos/index.php?msg=1";</script>';
	exit();
}
//AGREGAR PROVEEDORES
if ($_POST["idSql"] == 70) {
	$clave = round($_POST["pais"]);
	mysqli_query($conexionBdPrincipal,"INSERT INTO proveedores(prov_documento, prov_clave, prov_nombre, prov_email, prov_telefono, prov_ciudad, prov_fecha_registro, prov_responsable, prov_eliminado, prov_tipo_regimen, prov_direccion, prov_pais, prov_otra_ciudad)VALUES('" . $_POST["dni"] . "', '" . $clave . "', '" . $_POST["nombre"] . "', '" . $_POST["email"] . "', '" . $_POST["telefono"] . "', '" . $_POST["ciudad"] . "', now(), '" . $_SESSION["id"] . "', 0, '" . $_POST["regimen"] . "', '" . $_POST["direccion"] . "', '" . $_POST["pais"] . "', '" . $_POST["otraCiudad"] . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);

	echo '<script type="text/javascript">window.location.href="proveedores-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();
}
//EDITAR PROVEEDORES
if ($_POST["idSql"] == 71) {

	if ($_FILES['logo']['name'] != "") {
		$extension = end(explode(".", $_FILES['logo']['name']));
		$logo = uniqid('prov_') . "." . $extension;
		$destino = "files/proveedores";
		move_uploaded_file($_FILES['logo']['tmp_name'], $destino . "/" . $logo);

		mysqli_query($conexionBdPrincipal,"UPDATE proveedores SET prov_logo='" . $logo . "' WHERE prov_id='" . $_POST["id"] . "'");
		
	}

	mysqli_query($conexionBdPrincipal,"UPDATE proveedores SET prov_documento='" . $_POST["dni"] . "', prov_nombre='" . $_POST["nombre"] . "', prov_email='" . $_POST["email"] . "', prov_telefono='" . $_POST["telefono"] . "', prov_ciudad='" . $_POST["ciudad"] . "', prov_tipo_regimen='" . $_POST["regimen"] . "', prov_direccion='" . $_POST["direccion"] . "', prov_pais='" . $_POST["pais"] . "', prov_otra_ciudad='" . $_POST["otraCiudad"] . "' WHERE prov_id='" . $_POST["id"] . "'");
	

	echo '<script type="text/javascript">window.location.href="proveedores-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();
}
//EDITAR PEDIDOS
if ($_POST["idSql"] == 72) {
	mysqli_query($conexionBdPrincipal,"UPDATE pedidos SET pedid_fecha_propuesta='" . $_POST["fecha"] . "', pedid_estado='" . $_POST["estado"] . "', pedid_empresa_envio='" . $_POST["empresaEnvio"] . "', pedid_codigo_seguimiento='" . $_POST["codigoSeguimiento"] . "' WHERE pedid_id='" . $_POST["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="pedidos-timeline.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();
}
//AGREGAR NOVEDADES PEDIDO
if ($_POST["idSql"] == 73) {

	mysqli_query($conexionBdPrincipal,"INSERT INTO pedidos_novedades(pednov_dia, pednov_mes, pednov_estado, pednov_novedad, pednov_pedido, pednov_usuario)VALUES('" . $_POST["dia"] . "', '" . $_POST["mes"] . "', '" . $_POST["estado"] . "', '" . $_POST["novedad"] . "', '" . $_POST["id"] . "', '" . $_SESSION["id"] . "')");
	

	echo '<script type="text/javascript">window.location.href="pedidos-timeline.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();
}

//aqui estaba la actualización de estructura de mensajes.

//EDITAR CLAVE DE USUARIOS

//EDITAR CONTRASEÑA
/*
if ($_POST["idSql"] == 76) {
	$rst_usr = mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='" . $_SESSION["id"] . "' AND usr_clave=SHA1('" . $_POST["claveActual"] . "')");
	$fila = mysqli_fetch_array($rst_usr);
	$num = mysql_num_rows($rst_usr);
	if ($num > 0) {
		mysqli_query($conexionBdPrincipal,"UPDATE usuarios SET  usr_clave=SHA1('" . $_POST["clave"] . "') WHERE usr_id='" . $_SESSION["id"] . "'");
		

		echo '<script type="text/javascript">window.location.href="perfil-editar.php?msg=2";</script>';
		exit();
	} else {
		echo '<script type="text/javascript">window.location.href="perfil-editar.php?error=1";</script>';
		exit();
	}
}*/
//AGREGAR IMPORTACIONES
if ($_POST["idSql"] == 77) {

	mysqli_query($conexionBdPrincipal,"INSERT INTO importaciones(imp_fecha, imp_proveedor, imp_concepto, imp_liquidada, imp_responsable)VALUES('" . $_POST["fecha"] . "','" . $_POST["proveedor"] . "', '" . $_POST["concepto"] . "', 0, '" . $_SESSION["id"] . "')");
	
	$idInsert = mysqli_insert_id($conexionBdPrincipal);

	echo '<script type="text/javascript">window.location.href="importacion-editar.php?id=' . $idInsert . '&msg=1";</script>';
	exit();
}
//AGREGAR BODEGAS

//EDITAR BODEGAS

//AGREGAR O ACTUALIZAR PRODUCTOS EN BODEGAS

//AGREGAR REMISIONES
if ($_POST["idSql"] == 81) {

	//En proceso pendiente...

	mysqli_query($conexionBdPrincipal,"INSERT INTO remisionbdg(cotiz_fecha_propuesta, cotiz_cliente, cotiz_fecha_vencimiento, cotiz_vendedor, cotiz_creador, cotiz_sucursal, cotiz_contacto, cotiz_forma_pago, cotiz_fecha_creacion, cotiz_moneda, cotiz_observaciones, cotiz_envio, cotiz_proveedor)VALUES('" . $_POST["fechaPropuesta"] . "','" . $_POST["cliente"] . "','" . $_POST["fechaVencimiento"] . "','" . $_POST["influyente"] . "','" . $_SESSION["id"] . "','" . $_POST["sucursal"] . "','" . $_POST["contacto"] . "','" . $_POST["formaPago"] . "',now(),'" . $_POST["moneda"] . "','" . $_POST["notas"] . "','" . $_POST["envio"] . "','" . $_POST["proveedor"] . "')");
	
	$idInsert = mysqli_insert_id($conexionBdPrincipal);

	//Productos
	$numero = (count($_POST["producto"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {
			$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos WHERE prod_id='" . $_POST["producto"][$contador] . "'"));
			

			$valorProducto = $productoDatos['prod_precio'];
			if ($_POST["moneda"] == 2) {
				$valorProducto = round(($productoDatos['prod_precio'] / $configuracion['conf_trm_compra']), 0);
			}

			mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo)VALUES('" . $idInsert . "','" . $_POST["producto"][$contador] . "', '" . $valorProducto . "', '" . $contador . "', 1, 19, 1)");
			
			$contador++;
		}
	}

	//COMBOS
	$numero = (count($_POST["combo"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {

			$datosCombos = mysqli_query($conexionBdPrincipal,"SELECT ROUND((SUM(copp_cantidad)*prod_precio),0), combo_descuento FROM combos
			INNER JOIN combos_productos ON copp_combo=combo_id
			INNER JOIN productos ON prod_id=copp_producto
			WHERE combo_id='" . $_POST["combo"][$contador] . "'
			GROUP BY copp_producto
			");
			$precioCombo = 0;
			$dctoCombo = 0;
			while ($dCombos = mysqli_fetch_array($datosCombos)) {
				$precioCombo += $dCombos[0];
				$dctoCombo = $dCombos[1];
			}
			if ($dctoCombo > 0) {
				$precioCombo = round($precioCombo - ($precioCombo * ($dctoCombo / 100)), 0);
			}


			$productoNum = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_combo='" . $_POST["combo"][$contador] . "'"));
			


			if ($productoNum['czpp_id'] == '') {
				$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM combos WHERE combo_id='" . $_POST["combo"][$contador] . "'"));
				

				$valorProducto = $precioCombo;
				if ($_POST["moneda"] == 2) {
					$valorProducto = round(($precioCombo / $configuracion['conf_trm_compra']), 0);
				}

				mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_combo, czpp_cantidad, czpp_impuesto, czpp_descuento, czpp_valor, czpp_orden, czpp_tipo)VALUES('" . $idInsert . "','" . $_POST["combo"][$contador] . "', 1, 19, 0, '" . $valorProducto . "', '" . $numero . "', 1)");
				
			}

			$contador++;
		}
	}

	//Servicios
	$numero = (count($_POST["servicio"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {
			$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM servicios WHERE serv_id='" . $_POST["servicio"][$contador] . "'"));
			

			$valorProducto = $productoDatos['serv_precio'];
			if ($_POST["moneda"] == 2) {
				$valorProducto = round(($productoDatos['serv_precio'] / $configuracion['conf_trm_compra']), 0);
			}

			mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_servicio, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo)VALUES('" . $idInsert . "','" . $_POST["servicio"][$contador] . "', '" . $valorProducto . "', '" . $contador . "', 1, 19, 1)");
			
			$contador++;
		}
	}

	echo '<script type="text/javascript">window.location.href="cotizaciones-editar.php?id=' . $idInsert . '&msg=1";</script>';
	exit();
}
//TRASNFERIR PRODUCTOS ENTRE BODEGAS

//AGREGAR FACTURAS DE VENTA
if ($_POST["idSql"] == 83) {

	mysqli_query($conexionBdPrincipal,"INSERT INTO facturas(factura_fecha_propuesta, factura_cliente, factura_fecha_vencimiento, factura_vendedor, factura_creador, factura_sucursal, factura_contacto, factura_forma_pago, factura_fecha_creacion, factura_moneda, factura_estado, factura_tipo)VALUES('" . $_POST["fechaPropuesta"] . "','" . $_POST["cliente"] . "','" . $_POST["fechaVencimiento"] . "','" . $_POST["influyente"] . "','" . $_SESSION["id"] . "','" . $_POST["sucursal"] . "','" . $_POST["contacto"] . "','" . $_POST["formaPago"] . "',now(),'" . $_POST["moneda"] . "', 1, 1)");
	
	$idInsert = mysqli_insert_id($conexionBdPrincipal);

	//Productos
	$numero = (count($_POST["producto"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {
			$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos WHERE prod_id='" . $_POST["producto"][$contador] . "'"));
			

			$valorProducto = $productoDatos['prod_precio'];
			if ($_POST["moneda"] == 2) {
				$valorProducto = round(($productoDatos['prod_precio'] / $configuracion['conf_trm_compra']), 0);
			}

			mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo)VALUES('" . $idInsert . "','" . $_POST["producto"][$contador] . "', '" . $valorProducto . "', '" . $contador . "', 1, 19, 4)");
			
			$contador++;
		}
	}

	//COMBOS
	$numero = (count($_POST["combo"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {

			$datosCombos = mysqli_query($conexionBdPrincipal,"SELECT ROUND((SUM(copp_cantidad)*prod_precio),0), combo_descuento FROM combos
			INNER JOIN combos_productos ON copp_combo=combo_id
			INNER JOIN productos ON prod_id=copp_producto
			WHERE combo_id='" . $_POST["combo"][$contador] . "'
			GROUP BY copp_producto
			");
			$precioCombo = 0;
			$dctoCombo = 0;
			while ($dCombos = mysqli_fetch_array($datosCombos)) {
				$precioCombo += $dCombos[0];
				$dctoCombo = $dCombos[1];
			}
			if ($dctoCombo > 0) {
				$precioCombo = round($precioCombo - ($precioCombo * ($dctoCombo / 100)), 0);
			}


			$productoNum = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_combo='" . $_POST["combo"][$contador] . "'"));
			


			if ($productoNum['czpp_id'] == '') {
				$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM combos WHERE combo_id='" . $_POST["combo"][$contador] . "'"));
				

				$valorProducto = $precioCombo;
				if ($_POST["moneda"] == 2) {
					$valorProducto = round(($precioCombo / $configuracion['conf_trm_compra']), 0);
				}

				mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_combo, czpp_cantidad, czpp_impuesto, czpp_descuento, czpp_valor, czpp_orden, czpp_tipo)VALUES('" . $idInsert . "','" . $_POST["combo"][$contador] . "', 1, 19, 0, '" . $valorProducto . "', '" . $numero . "', 4)");
				
			}

			$contador++;
		}
	}

	//Servicios
	$numero = (count($_POST["servicio"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {
			$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM servicios WHERE serv_id='" . $_POST["servicio"][$contador] . "'"));
			

			$valorProducto = $productoDatos['serv_precio'];
			if ($_POST["moneda"] == 2) {
				$valorProducto = round(($productoDatos['serv_precio'] / $configuracion['conf_trm_compra']), 0);
			}

			mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_servicio, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo)VALUES('" . $idInsert . "','" . $_POST["servicio"][$contador] . "', '" . $valorProducto . "', '" . $contador . "', 1, 19, 4)");
			
			$contador++;
		}
	}

	echo '<script type="text/javascript">window.location.href="facturas.php?id=' . $idInsert . '&msg=1";</script>';
	exit();
}

//AGREGAR FACTURAS DE COMPRA
if ($_POST["idSql"] == 84) {

	mysqli_query($conexionBdPrincipal,"INSERT INTO facturas(factura_fecha_propuesta, factura_proveedor, factura_fecha_vencimiento, factura_creador, factura_forma_pago, factura_fecha_creacion, factura_moneda, factura_estado, factura_tipo, factura_concepto, factura_extranjera, factura_preferencia)VALUES('" . $_POST["fechaPropuesta"] . "','" . $_POST["proveedor"] . "','" . $_POST["fechaVencimiento"] . "','" . $_SESSION["id"] . "','" . $_POST["formaPago"] . "',now(),'" . $_POST["moneda"] . "', 1, 2, '" . $_POST["concepto"] . "', '" . $_POST["fce"] . "', '" . $_POST["preferencia"] . "')");
	
	$idInsert = mysqli_insert_id($conexionBdPrincipal);

	//Productos
	$numero = (count($_POST["producto"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {
			$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos WHERE prod_id='" . $_POST["producto"][$contador] . "'"));
			

			$valorProducto = $productoDatos['prod_precio'];
			if ($_POST["moneda"] == 2) {
				$valorProducto = round(($productoDatos['prod_precio'] / $configuracion['conf_trm_compra']), 0);
			}

			mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega)VALUES('" . $idInsert . "','" . $_POST["producto"][$contador] . "', '" . $valorProducto . "', '" . $contador . "', 1, 19, 4, 1)");
			
			$contador++;
		}
	}

	if ($_POST["fce"] == 1) {

		echo '<script type="text/javascript">window.location.href="fce-editar.php?id=' . $idInsert . '&msg=1";</script>';
	} else {
		echo '<script type="text/javascript">window.location.href="facturas-compra-editar.php?id=' . $idInsert . '&msg=1";</script>';
	}


	exit();
}
//EDITAR FACTURAS
if ($_POST["idSql"] == 85) {

	if ($_POST["fce"] == 1) {

		mysqli_query($conexionBdPrincipal,"UPDATE facturas SET factura_fecha_propuesta='" . $_POST["fechaPropuesta"] . "', factura_proveedor='" . $_POST["proveedor"] . "', factura_fecha_vencimiento='" . $_POST["fechaVencimiento"] . "', factura_forma_pago='" . $_POST["formaPago"] . "', factura_moneda='" . $_POST["moneda"] . "', factura_ultima_modificacion=now(), factura_usuario_modificacion='" . $_SESSION["id"] . "', factura_observaciones='" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["notas"]) . "', factura_concepto='" . $_POST["concepto"] . "', factura_trm_usd='" . $_POST["trmUsd"] . "', factura_trm_euro='" . $_POST["trmEuro"] . "', factura_trm_usd_flete='" . $_POST["trmUsdFlete"] . "', factura_trm_euro_flete='" . $_POST["trmEuroFlete"] . "', factura_preferencia='" . $_POST["preferencia"] . "' WHERE factura_id='" . $_POST["id"] . "'");
		

		//PRODUCTOS
		$numero = (count($_POST["producto"]));
		if ($numero > 0) {
			$contador = 0;
			while ($contador < $numero) {

				$productoNum = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
				WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_producto='" . $_POST["producto"][$contador] . "' AND czpp_tipo=4"));
				


				if ($productoNum['czpp_id'] == '') {
					$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos WHERE prod_id='" . $_POST["producto"][$contador] . "'"));
					

					

					mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_cantidad, czpp_impuesto, czpp_descuento, czpp_orden, czpp_tipo, czpp_bodega)VALUES('" . $_POST["id"] . "','" . $_POST["producto"][$contador] . "', 1, 19, 0, '" . $numero . "', 4, 1)");
					
				} 

				$contador++;
			}


			//ELIMINAR LOS QUE YA NO ESTÁN EN LA FACTURACIÓN.
			$productosWeb = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_tipo=4");
			while ($pWeb = mysqli_fetch_array($productosWeb)) {

				$encontrado = 0;
				$contador = 0;
				while ($contador < $numero) {

					if ($pWeb['czpp_producto'] == $_POST["producto"][$contador]) {
						$encontrado = 1;
						break;
					}

					$contador++;
				}

				if ($encontrado == 0) {
					mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion_productos WHERE czpp_producto='" . $pWeb['czpp_producto'] . "' AND czpp_cotizacion='" . $_POST["id"] . "' AND czpp_tipo=4");
					
				}
			}
		} else {
			mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_servicio IS NULL AND czpp_combo IS NULL AND czpp_tipo=4");
			
		}



		echo '<script type="text/javascript">window.location.href="fce-editar.php?id=' . $_POST["id"] . '&msg=1";</script>';
	} else {

		mysqli_query($conexionBdPrincipal,"UPDATE facturas SET factura_fecha_propuesta='" . $_POST["fechaPropuesta"] . "', factura_proveedor='" . $_POST["proveedor"] . "', factura_fecha_vencimiento='" . $_POST["fechaVencimiento"] . "', factura_forma_pago='" . $_POST["formaPago"] . "', factura_moneda='" . $_POST["moneda"] . "', factura_ultima_modificacion=now(), factura_usuario_modificacion='" . $_SESSION["id"] . "', factura_observaciones='" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["notas"]) . "', factura_concepto='" . $_POST["concepto"] . "', factura_valor='" . $_POST["valor"] . "', factura_preferencia='" . $_POST["preferencia"] . "' WHERE factura_id='" . $_POST["id"] . "'");
		

		//PRODUCTOS
		$numero = (count($_POST["producto"]));
		if ($numero > 0) {
			$contador = 0;
			while ($contador < $numero) {

				$productoNum = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
				WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_producto='" . $_POST["producto"][$contador] . "' AND czpp_tipo=4"));
				


				if ($productoNum['czpp_id'] == '') {
					$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos WHERE prod_id='" . $_POST["producto"][$contador] . "'"));
					

					$valorProducto = $productoDatos['prod_precio'];
					if ($_POST["moneda"] == 2) {
						$valorProducto = round(($productoDatos['prod_precio'] / $configuracion['conf_trm_compra']), 0);
					}

					mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_cantidad, czpp_impuesto, czpp_descuento, czpp_valor, czpp_orden, czpp_tipo, czpp_bodega)VALUES('" . $_POST["id"] . "','" . $_POST["producto"][$contador] . "', 1, 19, 0, '" . $valorProducto . "', '" . $numero . "', 4, 1)");
					
				} else {
					if ($_POST["monedaActual"] != $_POST["moneda"]) {
						//Si cambió a pesos colombianos
						if ($_POST["moneda"] == 1) {
							$valorProducto = round(($productoNum['czpp_valor'] * $configuracion['conf_trm_venta']), 0);
						}
						//Si cambió a Dolares
						else {
							$valorProducto = round(($productoNum['czpp_valor'] / $configuracion['conf_trm_compra']), 0);
						}

						mysqli_query($conexionBdPrincipal,"UPDATE cotizacion_productos SET czpp_valor='" . $valorProducto . "' WHERE czpp_id='" . $productoNum['czpp_id'] . "'");
						
					}
				}

				$contador++;
			}


			//ELIMINAR LOS QUE YA NO ESTÁN EN LA FACTURACIÓN.
			$productosWeb = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_tipo=4");
			while ($pWeb = mysqli_fetch_array($productosWeb)) {

				$encontrado = 0;
				$contador = 0;
				while ($contador < $numero) {

					if ($pWeb['czpp_producto'] == $_POST["producto"][$contador]) {
						$encontrado = 1;
						break;
					}

					$contador++;
				}

				if ($encontrado == 0) {
					mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion_productos WHERE czpp_producto='" . $pWeb['czpp_producto'] . "' AND czpp_cotizacion='" . $_POST["id"] . "' AND czpp_tipo=4");
					
				}
			}
		} else {
			mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_servicio IS NULL AND czpp_combo IS NULL AND czpp_tipo=4");
			
		}



		echo '<script type="text/javascript">window.location.href="facturas-compra-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	}
	exit();
}
//EDITAR IMPORTACIÓN
if ($_POST["idSql"] == 86) {

	mysqli_query($conexionBdPrincipal,"UPDATE importaciones SET imp_fecha='" . $_POST["fecha"] . "', imp_proveedor='" . $_POST["proveedor"] . "', imp_concepto='" . $_POST["concepto"] . "', imp_responsable='" . $_SESSION["id"] . "', imp_liquidada='" . $_POST["liquidada"] . "', imp_fce='" . $_POST["fce"] . "', imp_valor_nacionalizacion='" . $_POST["nacionalizacion"] . "', imp_otros_gastos='" . $_POST["otrosCostos"] . "' WHERE imp_id='" . $_POST["id"] . "'");
	


	//Facturas asociadas
	$numero = (count($_POST["facturas"]));
	if ($numero > 0) {
		mysqli_query($conexionBdPrincipal,"DELETE FROM importaciones_facturas
		WHERE impf_importacion='" . $_POST["id"] . "' AND impf_preferencia='0'");
		
		$contador = 0;
		while ($contador < $numero) {

			mysqli_query($conexionBdPrincipal,"INSERT INTO importaciones_facturas(impf_fecha, impf_importacion, impf_factura, impf_responsable)VALUES(now(), '" . $_POST["id"] . "', '" . $_POST["facturas"][$contador] . "', '" . $_SESSION["id"] . "')");
			
			$contador++;
		}
	}

	//Facturas costos nacionalización
	$numero = (count($_POST["facturasNac"]));
	if ($numero > 0) {
		mysqli_query($conexionBdPrincipal,"DELETE FROM importaciones_facturas
		WHERE impf_importacion='" . $_POST["id"] . "' AND impf_preferencia=1");
		
		$contador = 0;
		while ($contador < $numero) {

			mysqli_query($conexionBdPrincipal,"INSERT INTO importaciones_facturas(impf_fecha, impf_importacion, impf_factura, impf_responsable, impf_preferencia)VALUES(now(), '" . $_POST["id"] . "', '" . $_POST["facturasNac"][$contador] . "', '" . $_SESSION["id"] . "', 1)");
			
			$contador++;
		}
	}

	//Cuando se liquida una factura
	if ($_POST["liquidada"]) {
		//Facturas asociadas
		$facturasValor =  mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT SUM(factura_valor) FROM importaciones_facturas
		INNER JOIN facturas ON factura_id=impf_factura
		WHERE impf_importacion='" . $_POST["id"] . "'
		"));
		


		//Cantidad de productos asociados
		$productoNum = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT SUM(czpp_cantidad) FROM cotizacion_productos
		WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_tipo=5"));
		

		$valorRepartido = ($facturasValor[0] / $productoNum[0]);

		//Consulto productos
		$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos
		WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_tipo=5");
		

		//Actualizo el costo y las cantidades en la bodega general
		while ($prod = mysqli_fetch_array($productos)) {

			$costoProducto = ($prod['czpp_valor'] + $valorRepartido);

			mysqli_query($conexionBdPrincipal,"UPDATE productos SET prod_costo='" . $costoProducto . "' 
			WHERE prod_id='" . $prod['czpp_producto'] . "'");
			

			//En Bodega...
			$bpp = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_bodegas WHERE prodb_producto='" . $prod['czpp_producto'] . "' AND prodb_bodega=1"));
			

			if ($bpp[0] == "") {
				mysqli_query($conexionBdPrincipal,"INSERT INTO productos_bodegas(prodb_producto, prodb_bodega, prodb_existencias, prodb_fecha_actualizacion, prodb_usuario_actualizacion)VALUES('" . $prod['czpp_producto'] . "', 1, '" . $prod['czpp_cantidad'] . "', now(), '" . $_SESSION["id"] . "')");
				
				$idInsertU = mysqli_insert_id($conexionBdPrincipal);
			} else {
				$nuevaCantidad = $bpp['prodb_existencias'] + $prod['czpp_cantidad'];

				mysqli_query($conexionBdPrincipal,"UPDATE productos_bodegas SET prodb_existencias= '" . $nuevaCantidad . "', prodb_fecha_actualizacion=now(), prodb_usuario_actualizacion='" . $_SESSION["id"] . "' WHERE prodb_producto='" . $prod['czpp_producto'] . "' AND prodb_bodega=1");
				
			}
		}
	}



	echo '<script type="text/javascript">window.location.href="importacion-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();
}

//aquí iba la parte de actualizar las métricas.

//AGREGAR SUCURSALES

//EDITAR SUCURSALES

//EDITAR CLIENTES ORION

//AGREGAR CLIENTES ORION

//EDITAR ZONAS USUARIOS







//GET GET GET GET GET GETGET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET
//ELIMINAR USUARIO

//ELIMINAR ROLES

//ELIMINAR SEGUIMIENTO CLIENTES

//ELIMINAR AUDITORES
if ($_GET["get"] == 5) {
	$idPagina = 57;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM auditores WHERE aud_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="auditores.php?msg=3";</script>';
	exit();
}
//ELIMINAR FACTURAS

//ELIMINAR DOCUMENTOS
if ($_GET["get"] == 7) {
	$idPagina = 59;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM documentos WHERE doc_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="documentos.php?msg=3";</script>';
	exit();
}
//OCULTAR CLIENTES
if ($_GET["get"] == 8) {
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_ocultar=1 WHERE cli_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="clientes.php?msg=4";</script>';
	exit();
}
//MOSTRAR TODOS CLIENTES
if ($_GET["get"] == 9) {
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_ocultar=0");
	
	echo '<script type="text/javascript">window.location.href="clientes.php?msg=5";</script>';
	exit();
}
//OCULTAR CLIENTES
if ($_GET["get"] == 10) {
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_ocultar=0 WHERE cli_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="co.php?msg=4";</script>';
	exit();
}
//ELIMINAR DEALER
if ($_GET["get"] == 11) {
	$idPagina = 60;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM dealer WHERE deal_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR PRODUCTOS

//ELIMINAR CATEGORÍA DE PRODUCTOS

//ELIMINAR ZONAS
if ($_GET["get"] == 14) {
	$idPagina = 63;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM zonas WHERE zon_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR ENCUESTAS
if ($_GET["get"] == 15) {
	$idPagina = 64;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM encuesta_satisfaccion WHERE encs_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR NOTIFICACIONES
if ($_GET["get"] == 16) {
	//$idPagina = 65; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM notificaciones WHERE not_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR MATERIALES DE PRODUCTOS
if ($_GET["get"] == 17) {
	$idPagina = 71;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM productos_materiales WHERE ppmt_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ENVIAR ENCUESTA AL CORREO
if ($_GET["get"] == 18) {
	$contacto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='" . $_GET["cont"] . "'"));
	$fin =  '<html><body style="background-color:' . $configuracion["conf_fondo_boletin"] . ';">';
	$fin .= '
				<center>
					<p align="center"><img src="' . $configuracion["conf_url_encuestas"] . '/usuarios/files/' . $configuracion["conf_logo"] . '" width="350"></p>
					<div style="font-family:arial; background:' . $configuracion["conf_fondo_mensaje"] . '; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;">
						
						<p style="color:' . $configuracion["conf_color_letra"] . ';">' . strtoupper($contacto['cont_nombre']) . ',<br>
						Agradecemos se tome 3 minutos para responder una escuesta sobre la atención brindada por nuestra empresa.<br>
						Haga click en el siguiente enlace para responder la encuesta.</p>
						
						<p align="center"><a href="' . $configuracion["conf_url_encuestas"] . '/formato-encuesta.php?id=' . $_GET["id"] . '" target="_blank" style="color:' . $configuracion["conf_color_link"] . ';">RESPONDER ENCUESTA</a></p>
						
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
	$sdestinatario = $contacto['cont_email']; //CUENTA DEL QUE RECIBE EL MENSAJE			
	$ssubject = "Encuesta de satisfaccion"; //ASUNTO DEL MENSAJE 				
	$shtml = $fin; //MENSAJE EN SI			
	$sheader = "From:" . $sfrom . "\nReply-To:" . $sfrom . "\n";
	$sheader = $sheader . "X-Mailer:PHP/" . phpversion() . "\n";
	$sheader = $sheader . "Mime-Version: 1.0\n";
	$sheader = $sheader . "Content-Type: text/html; charset=UTF-8\r\n";
	@mail($sdestinatario, $ssubject, $shtml, $sheader);
	
	echo '<script type="text/javascript">window.location.href="encuesta.php?msg=4";</script>';
	exit();
}
//REPLICAR FACTURA
if ($_GET["get"] == 19) {
	$idPagina = 72;
	include("includes/verificar-paginas.php");
	//$factura = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM facturacion",$conexion));
	mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion (fact_cliente, fact_fecha, fact_valor, fact_estado, fact_usuario_responsable, fact_descripcion, fact_observacion, fact_descuento, fact_producto, fact_numero_fisica, fact_usuario_influyente, fact_fecha_real, fact_fecha_vencimiento) SELECT fact_cliente, now(), fact_valor, fact_estado, fact_usuario_responsable, fact_descripcion, fact_observacion, fact_descuento, fact_producto, fact_numero_fisica, fact_usuario_influyente, now(), fact_fecha_vencimiento FROM facturacion WHERE fact_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//CAMBIAR DE ESTADO LAS NOTIFICACIONES
if ($_GET["get"] == 20) {
	$not = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM notificaciones WHERE not_id='" . $_GET["id"] . "'"));
	if ($not[5] == 1) $estadoN = 2;
	else $estadoN = 1;
	mysqli_query($conexionBdPrincipal,"UPDATE notificaciones SET not_estado='" . $estadoN . "' WHERE not_id='" . $_GET["id"] . "'");
	
	mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_realizado='" . $estadoN . "' WHERE cseg_id='" . $_GET["seg"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR ORDENES DE SERVICIO
if ($_GET["get"] == 21) {
	$idPagina = 76;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM ordenes_servicio WHERE ord_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR SUCURSALES

if ($_GET["get"] == 25) {
	$idPagina = 95;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM facturacion_abonos WHERE fpab_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//AGREGAR ABONO AUTOMÁTICO POR EL VALOR PENDIENTE
if ($_GET["get"] == 26) {
	$nmsg = 8;
	$abonos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT sum(fpab_valor), fact_valor, fact_id, fact_fecha_real, fact_impuestos, fact_retencion, fact_descuento FROM facturacion_abonos, facturacion
	WHERE fpab_factura='" . $_GET["id"] . "' AND fact_id='" . $_GET["id"] . "'"));
	$impuestos = $abonos['fact_valor'] * $abonos['fact_impuestos'] / 100;
	$retencion = $abonos['fact_valor'] * $abonos['fact_retencion'] / 100;
	$descuento = $res['fact_valor'] * $abonos['fact_descuento'] / 100;
	$valorReal = ($abonos['fact_valor'] + $impuestos) - ($retencion + $descuento);
	$saldoFinal = $valorReal - $abonos[0];
	if ($saldoFinal > 0) {
		mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion_abonos(fpab_factura, fpab_fecha_abono, fpab_valor, fpab_fecha_registro, fpab_observaciones, fpab_medio_pago, fpab_responsable_registro)VALUES('" . $_GET["id"] . "','" . $abonos[3] . "','" . $saldoFinal . "',now(),'Abono automático por el saldo pendiente',8,'" . $_SESSION["id"] . "')");
		
		$idInsertU = mysqli_insert_id($conexionBdPrincipal);

		mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=1, fact_observacion=CONCAT(fact_observacion, ' <br>-- ', now(), ' Abono automático por el saldo pendiente y cambió a estado pagada') WHERE fact_id='" . $_GET["id"] . "' AND fact_estado!=3");
		
		$nmsg = 7;
	}

	echo '<script type="text/javascript">window.location.href="facturacion.php?msg=' . $nmsg . '";</script>';
	exit();
}
if ($_GET["get"] == 27) {
	$idPagina = 100;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM soporte_productos WHERE sop_padre='" . $_GET["id"] . "'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM soporte_productos WHERE sop_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 28) {
	//$idPagina = 100; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_realizado=1 WHERE cseg_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 29) {
	//$idPagina = 100; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE clientes_tikets SET tik_estado=2 WHERE tik_id='" . $_GET["id"] . "'");
	mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_realizado=1 WHERE cseg_tiket='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 30) {
	//$idPagina = 100; include("includes/verificar-paginas.php");
	$producto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos WHERE prod_id='" . $_GET["id"] . "'"));
	

	mysqli_query($conexionBdPrincipal,"INSERT INTO productos(prod_nombre, prod_categoria)VALUES('" . $producto['prod_nombre'] . "(COPIA)', '" . $producto['prod_categoria'] . "')");
	
	$idpn = mysqli_insert_id($conexionBdPrincipal);

	$materialesP = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_materiales WHERE ppmt_producto='" . $_GET["id"] . "'");
	
	while ($mp = mysqli_fetch_array($materialesP)) {
		mysqli_query($conexionBdPrincipal,"INSERT INTO productos_materiales(ppmt_material, ppmt_tipo, ppmt_activo, ppmt_producto, ppmt_nombre)
		VALUES('" . $mp['ppmt_material'] . "', '" . $mp['ppmt_tipo'] . "', '" . $mp['ppmt_activo'] . "', '" . $idpn . "', '" . $mp['ppmt_nombre'] . "')");
	}

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//Cambiar de estado a los productos
if ($_GET["get"] == 31) {
	//$idPagina = 100; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE productos SET prod_visible='" . $_GET["estado"] . "' WHERE prod_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 32) {
	//$idPagina = 100; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_archivo='' WHERE cseg_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 33) {
	//$idPagina = 100; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_papelera=1, cli_papelera_por='" . $_SESSION["id"] . "', cli_papelera_fecha=now() WHERE cli_id='" . $_GET["idR"] . "'");
	

	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_estado_mercadeo='" . $_GET["em"] . "', cli_estado_mercadeo_fecha=now(), cli_estado_mercadeo_usuario='" . $_SESSION["id"] . "' WHERE cli_id='" . $_GET["idR"] . "'");
	

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 34) {
	//$idPagina = 100; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_terminado=1, cli_terminado_por='" . $_SESSION["id"] . "', cli_terminado_fecha=now() WHERE cli_id='" . $_GET["idR"] . "'");
	

	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_estado_mercadeo='" . $_GET["em"] . "', cli_estado_mercadeo_fecha=now(), cli_estado_mercadeo_usuario='" . $_SESSION["id"] . "' WHERE cli_id='" . $_GET["idR"] . "'");
	

	if ($_GET["em"] != 6) {
		mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_papelera=0 WHERE cli_id='" . $_GET["idR"] . "'");
		
	}

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 35) {
	$idPagina = 114;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM proyectos_tareas WHERE ptar_id_proyecto='" . $_GET["id"] . "'");
	mysqli_query($conexionBdPrincipal,"DELETE FROM proyectos WHERE proy_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 36) {
	$idPagina = 115;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM proyectos_tareas WHERE ptar_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 37) {
	$idPagina = 118;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM agenda WHERE age_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="calendario.php?id=' . $_SESSION["id"] . '";</script>';
	exit();
}
if ($_GET["get"] == 38) {
	//$idPagina = 100; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE configuracion SET conf_banner_top='' WHERE conf_id=1");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 39) {
	//$idPagina = 100; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE configuracion SET conf_banner_lateral='' WHERE conf_id=1");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 40) {
	//$idPagina = 118; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM cupones WHERE cupo_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 41) {
	//$idPagina = 100; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE clientes_tikets SET tik_etapa='" . $_GET["etapa"] . "' WHERE tik_id='" . $_GET["idtk"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 42) {
	//$idPagina = 100; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_estado_mercadeo='" . $_GET["em"] . "', cli_estado_mercadeo_fecha=now(), cli_estado_mercadeo_usuario='" . $_SESSION["id"] . "' WHERE cli_id='" . $_GET["idR"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
if ($_GET["get"] == 43) {
	//$idPagina = 118; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion_productos WHERE czpp_id='" . $_GET["idItem"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ENVIAR COTIZACIÓN AL CORREO
if ($_GET["get"] == 44) {

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
}
//Eliminar marcas

//ELIMINAR SERVICIOS

//ELIMINAR PEDIDO
if ($_GET["get"] == 50) {
	//$idPagina = 118; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM pedidos WHERE pedid_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ANULAR PEDIDO
if ($_GET["get"] == 51) {
	//$idPagina = 118; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE pedidos SET pedid_estado=2 WHERE pedid_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//GENERAR REMISIÓN A PARTIR DE PEDIDOS
if ($_GET["get"] == 52) {
	//$idPagina = 72; include("includes/verificar-paginas.php");

	$generoRemision = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisionbdg WHERE remi_pedido='" . $_GET["id"] . "'"));
	if($generoRemision[0]!=""){
		echo "<span style='font-family:arial; text-align:center; color:red;'>Este Pedido ya generó la remision con ID: ".$generoRemision[0].". En la fecha: ".$generoRemision['remi_fecha_creacion']."</div>";
		exit();
	}

	mysqli_query($conexionBdPrincipal,"INSERT INTO remisionbdg(remi_fecha_propuesta, remi_observaciones, remi_cliente, remi_fecha_vencimiento, remi_vendedor, remi_creador, remi_sucursal, remi_contacto, remi_forma_pago, remi_fecha_creacion, remi_moneda, remi_pedido, remi_estado) SELECT now(), pedid_observaciones, pedid_cliente, pedid_fecha_vencimiento, pedid_vendedor, '" . $_SESSION["id"] . "', pedid_sucursal, pedid_contacto, pedid_forma_pago, pedid_fecha_creacion, pedid_moneda, pedid_id, 1 FROM pedidos WHERE pedid_id='" . $_GET["id"] . "'");
	
	$idInsert = mysqli_insert_id($conexionBdPrincipal);


	$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo=2 AND czpp_producto!=''");
	

	while ($prod = mysqli_fetch_array($productos)) {
		if ($prod['czpp_orden'] == "") $prod['czpp_orden'] = 1;
		if ($prod['czpp_cantidad'] == "") $prod['czpp_cantidad'] = 1;

		mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega, czpp_descuento)VALUES('" . $idInsert . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', '" . $prod['czpp_orden'] . "', '".$prod['czpp_cantidad']."', '" . $prod['czpp_impuesto'] . "', 3, 1, '" . $prod['czpp_descuento'] . "')");
		
		$contador++;
	}


	//Ingresar todos los productos cuando son combos
	$productosCombos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo=2 AND czpp_combo!=''");
	

	while ($combo = mysqli_fetch_array($productosCombos)) {
		if ($combo['czpp_orden'] == "") $combo['czpp_orden'] = 1;
		if ($combo['czpp_cantidad'] == "") $combo['czpp_cantidad'] = 1;

		$combos = mysqli_query($conexionBdPrincipal,"SELECT * FROM combos_productos 
		INNER JOIN productos ON prod_id=copp_producto
		INNER JOIN combos ON combo_id=copp_combo
		WHERE copp_combo='" . $combo['czpp_combo'] . "'");
		
		
		while($comProd = mysqli_fetch_array($combos)){

			$porcentajeDcto = ($comProd['combo_descuento'] / 100);
			$precioConDctoCombo = $comProd['prod_precio'] - ($comProd['prod_precio'] * $porcentajeDcto);

			mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega, czpp_descuento)VALUES('" . $idInsert . "','" . $comProd['prod_id'] . "', '" . $precioConDctoCombo. "', '" . $combo['czpp_orden'] . "', '".$comProd['copp_cantidad']."', '" . $combo['czpp_impuesto'] . "', 3, 1, '".$combo['czpp_descuento']."')");
			

		}

	}

	echo '<script type="text/javascript">window.location.href="remisionbdg.php?q=' . $idInsert . '";</script>';
	exit();
}
//ELIMINAR COMBO

if ($_GET["get"] == 55) {
	//$idPagina = 118; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM combos_productos WHERE copp_id='" . $_GET["idItem"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//GENERAR FACTURA A PARTIR DE COTIZACIÓN (VIEJA)
if ($_GET["get"] == 56) {
	//$idPagina = 72; include("includes/verificar-paginas.php");
	$generoFactura = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM facturas WHERE factura_remision='" . $_GET["id"] . "'"));
	if($generoFactura[0]!=""){
		echo "<span style='font-family:arial; text-align:center; color:red;'>Esta Remisión ya generó la factura con ID: ".$generoFactura[0].". En la fecha: ".$generoFactura['factura_fecha_creacion']."</div>";
		exit();
	}

	$valorProductos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT sum(czpp_valor) FROM cotizacion_productos WHERE czpp_cotizacion='" . $_GET["id"] . "'"));

	mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion(fact_cliente, fact_fecha, fact_estado, fact_usuario_responsable, fact_descripcion, fact_observacion, fact_usuario_influyente, fact_fecha_real, fact_fecha_vencimiento, fact_tipo, fact_sucursal, fact_contacto, fact_forma_pago, fact_moneda, fact_cotizacion, fact_valor)
	SELECT cotiz_cliente, now(), 2, '" . $_SESSION["id"] . "', 'Traída desde Cotización " . $_GET["id"] . "', cotiz_observaciones, cotiz_vendedor, now(), cotiz_fecha_vencimiento, 1, cotiz_sucursal, cotiz_contacto, cotiz_forma_pago, cotiz_moneda, '" . $_GET["id"] . "', '" . $valorProductos[0] . "' FROM cotizacion WHERE cotiz_id='" . $_GET["id"] . "'");
	
	$idInsert = mysqli_insert_id($conexionBdPrincipal);


	$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_GET["id"] . "'");
	

	while ($prod = mysqli_fetch_array($productos)) {
		mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_descuento, czpp_observacion, czpp_servicio, czpp_combo)VALUES('" . $idInsert . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', '" . $prod['czpp_orden'] . "', '" . $prod['czpp_cantidad'] . "', '" . $prod['czpp_impuesto'] . "', 4, '" . $prod['czpp_descuento'] . "', '" . $prod['czpp_observacion'] . "', '" . $prod['czpp_servicio'] . "', '" . $prod['czpp_combo'] . "')");
		

		mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion_productos(fpp_factura, fpp_producto)VALUES('" . $idInsert . "','" . $prod['czpp_producto'] . "')");
		

		$contador++;
	}

	echo '<script type="text/javascript">window.location.href="facturacion-editar.php?id=' . $idInsert . '";</script>';
	exit();
}


//COTIZACIÓN VENDIDA
if ($_GET["get"] == 59) {
	mysqli_query($conexionBdPrincipal,"UPDATE cotizacion SET cotiz_vendida=1, cotiz_fecha_vendida=now() WHERE cotiz_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR GASTOS
if ($_GET["get"] == 60) {
	mysqli_query($conexionBdPrincipal,"DELETE FROM gastos WHERE gasv_id='" . $_GET["id"] . "'");
	

	echo '<script type="text/javascript">window.location.href="viaticos/index.php?msg=1";</script>';
	exit();
}
//ELIMINAR PROVEEDORES
if ($_GET["get"] == 61) {
	mysqli_query($conexionBdPrincipal,"UPDATE proveedores SET prov_eliminado=1, prov_fecha_eliminado=now(), prov_responsable_elimacion='" . $_SESSION["id"] . "' WHERE prov_id='" . $_GET["id"] . "'");
	

	echo '<script type="text/javascript">window.location.href="proveedores.php?msg=1";</script>';
	exit();
}
//ELIMINAR BODEGAS

//ELIMINAR BODEGAS POR PRODUCTOS

//OTRO
if ($_GET["get"] == 64) {
	$prod = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
	WHERE czpp_id='" . $_GET["idItem"] . "'"));
	


	mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega)VALUES('" . $prod['czpp_cotizacion'] . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', 1, 0, '" . $prod['czpp_impuesto'] . "', 3, 1)");
	

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//GENERAR FACTURA DE VENTA A PARTIR DE REMISIÓN
if ($_GET["get"] == 65) {
	//$idPagina = 72; include("includes/verificar-paginas.php");

	$generoFactura = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM facturas WHERE factura_remision='" . $_GET["id"] . "'"));
	if($generoFactura[0]!=""){
		echo "<span style='font-family:arial; text-align:center; color:red;'>Esta Remisión ya generó la factura con ID: ".$generoFactura[0].". En la fecha: ".$generoFactura['factura_fecha_creacion']."</div>";
		exit();
	}

	$valorProductos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT sum(czpp_valor*czpp_cantidad) + sum(czpp_valor*czpp_cantidad)*(czpp_impuesto/100) FROM cotizacion_productos 
	WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo=3"));

	mysqli_query($conexionBdPrincipal,"INSERT INTO facturas(factura_fecha_propuesta, factura_observaciones, factura_cliente, factura_fecha_vencimiento, factura_vendedor, factura_creador, factura_sucursal, factura_contacto, factura_forma_pago, factura_fecha_creacion, factura_moneda, factura_estado, factura_tipo, factura_concepto, factura_extranjera, factura_remision)
	SELECT now(), remi_observaciones, remi_cliente, remi_fecha_vencimiento, remi_vendedor, '" . $_SESSION["id"] . "', remi_sucursal, remi_contacto, remi_forma_pago, now(), remi_moneda, 1, 1, 'Traída de remisión', 0, remi_id FROM remisionbdg WHERE remi_id='" . $_GET["id"] . "'");
	
	$idInsert = mysqli_insert_id($conexionBdPrincipal);


	$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo=3");
	

	while ($prod = mysqli_fetch_array($productos)) {
		if ($prod['czpp_orden'] == "") $prod['czpp_orden'] = 1;
		if ($prod['czpp_cantidad'] == "") $prod['czpp_cantidad'] = 1;

		mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_descuento, czpp_observacion, czpp_servicio, czpp_combo, czpp_bodega)VALUES('" . $idInsert . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', '" . $prod['czpp_orden'] . "', '" . $prod['czpp_cantidad'] . "', '" . $prod['czpp_impuesto'] . "', 4, '" . $prod['czpp_descuento'] . "', '" . $prod['czpp_observacion'] . "', '" . $prod['czpp_servicio'] . "', '" . $prod['czpp_combo'] . "', '" . $prod['czpp_bodega'] . "')");
		

		$contador++;
	}

	echo '<script type="text/javascript">window.location.href="facturas.php?q=' . $idInsert . '";</script>';
	exit();
}

//COLOCAR REDIMIDA UNA FACTURA, PUNTOS DEL CLIENTE
if ($_GET["get"] == 66) {
	mysqli_query($conexionBdPrincipal,"UPDATE facturas SET factura_redimido_cliente=1 WHERE factura_id='" . $_GET["id"] . "'");
	

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//SALDAR COMISIÓN A VENDEDORES
if ($_GET["get"] == 67) {
	mysqli_query($conexionBdPrincipal,"UPDATE facturas SET factura_redimido_vendedor=1 WHERE factura_id='" . $_GET["id"] . "'");
	

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR SUCURSALES
if ($_GET["get"] == 68) {
	mysqli_query($conexionBdPrincipal,"DELETE FROM sucursales_propias WHERE sucp_id='" . $_GET["id"] . "'");
	

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}

//AGREGAR PRODUCTOS

//APROBAR DESCUENTOS ESPECIALES EN COTIZACIONES
if ($_GET["get"] == 70) {

	mysqli_query($conexionBdPrincipal,"UPDATE cotizacion_productos SET czpp_descuento=czpp_descuento_especial, czpp_aprobado_usuario='".$_SESSION['id']."', czpp_aprobado_fecha=now() WHERE czpp_id='" . $_GET["idItem"] . "'");

	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
}

?>