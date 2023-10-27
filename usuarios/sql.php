<?php
require_once("sesion.php");

require '../librerias/phpmailer/Exception.php';
require '../librerias/phpmailer/PHPMailer.php';
require '../librerias/phpmailer/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1"), MYSQLI_BOTH);
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

//EDITAR DOCUMENTOS No tiene interfaz grafica
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

//EDITAR DEALER/GRUPOS

//AGREGAR PRODUCTOS

//EDITAR PRODUCTOS

//AGREGAR CATEGORIA PRODUCTOS

//EDITAR CATEGORIA PRODUCTOS

//aqui estaba configuración

//AGREGAR CONTACTOS

//EDITAR CONTACTOS

//AGREGAR ZONAS

//EDITAR ZONAS

//AGREGAR ENCUESTAS

//EDITAR ENCUESTAS

//AGREGAR MATERIALES A PRODUCTOS

//EDITAR MATERIALES A PRODUCTOS

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

//EDITAR ABONO A FACTURAS

//AGREGAR SOPORTE PRODUCTOS  (ARCHIVO CREADO, EN PAGINA NO FUNCIONA LA OPCION AGREGAR--VERIFICAR)

//EDITAR SOPORTE PRODUCTOS (ARCHIVO CREADO, EN PAGINA NO FUNCIONA LA OPCION AGREGAR--VERIFICAR)

//AGREGAR ASUNTOS DE TIKETS (ARCHIVO CREADO, EN PAGINA No se encuentra la opcion a probar)

//EDITAR ASUNTOS DE TIKETS (ARCHIVO CREADO, EN PAGINA No se encuentra la opcion a probar)

//ENVIAR PORTAFOLIOS

//AGREGAR PROYECTOS

//EDITAR PROYECTOS

//AGREGAR TAREAS-PROYECTOS

//EDITAR TAREAS-PROYECTOS

//AGREGAR EVENTOS AL CALENDARIO

//EDITAR EVENTOS AL CALENDARIO

//EDITAR PUBLICIDAD DE CONFIGURACIÓN

//AGREGAR CUPONES

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

//REGISTRAR GASTOS (no se encuentra el archivo de registrar gastos)
if ($_POST["idSql"] == 69) {
	mysqli_query($conexionBdPrincipal,"INSERT INTO gastos(gasv_fecha, gasv_registro, gasv_concepto, gasv_valor, gasv_responsable)
	VALUES('" . $_POST["fecha"] . "', now(), '" . $_POST["concepto"] . "', '" . $_POST["valor"] . "', '" . $_SESSION["id"] . "')");
	
	$idRegistro = mysqli_insert_id($conexionBdPrincipal);

	echo '<script type="text/javascript">window.location.href="viaticos/index.php?msg=1";</script>';
	exit();
}
//AGREGAR PROVEEDORES

//EDITAR PROVEEDORES

//EDITAR PEDIDOS

//AGREGAR NOVEDADES PEDIDO

//aqui estaba la actualización de estructura de mensajes.

//EDITAR CLAVE DE USUARIOS

//EDITAR CONTRASEÑA

//AGREGAR IMPORTACIONES

//AGREGAR BODEGAS

//EDITAR BODEGAS

//AGREGAR O ACTUALIZAR PRODUCTOS EN BODEGAS

//AGREGAR REMISIONES

//TRASNFERIR PRODUCTOS ENTRE BODEGAS

//AGREGAR FACTURAS DE VENTA


//AGREGAR FACTURAS DE COMPRA

//EDITAR FACTURAS

//EDITAR IMPORTACIÓN

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

//ELIMINAR DOCUMENTOS (buscar para probar)

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

//ELIMINAR PRODUCTOS

//ELIMINAR CATEGORÍA DE PRODUCTOS

//ELIMINAR ZONAS

//ELIMINAR ENCUESTAS

//ELIMINAR NOTIFICACIONES
if ($_GET["get"] == 16) {
	//$idPagina = 65; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM notificaciones WHERE not_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR MATERIALES DE PRODUCTOS

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

if ($_GET["get"] == 40) {
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

//ANULAR PEDIDO

//GENERAR REMISIÓN A PARTIR DE PEDIDOS

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