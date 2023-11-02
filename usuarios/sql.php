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

//ELIMINAR AUDITORES (no se encuentra)
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

//ELIMINAR MATERIALES DE PRODUCTOS

//ENVIAR ENCUESTA AL CORREO

//REPLICAR FACTURA

//CAMBIAR DE ESTADO LAS NOTIFICACIONES

//ELIMINAR ORDENES DE SERVICIO // no se encuentra 
if ($_GET["get"] == 21) {
	$idPagina = 76;
	include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"DELETE FROM ordenes_servicio WHERE ord_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
//ELIMINAR SUCURSALES (facturacion-abonos no funciona)

//AGREGAR ABONO AUTOMÁTICO POR EL VALOR PENDIENTE // verificar en tikets-asuntos.php

if ($_GET["get"] == 30) { //(no existe interfaz grafica)
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
if ($_GET["get"] == 31) { //no apunta a ninguna parte
	//$idPagina = 100; include("includes/verificar-paginas.php");
	mysqli_query($conexionBdPrincipal,"UPDATE productos SET prod_visible='" . $_GET["estado"] . "' WHERE prod_id='" . $_GET["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}





//ENVIAR COTIZACIÓN AL CORREO

//Eliminar marcas

//ELIMINAR SERVICIOS

//ELIMINAR PEDIDO

//ANULAR PEDIDO

//GENERAR REMISIÓN A PARTIR DE PEDIDOS

//ELIMINAR COMBO


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

//GENERAR FACTURA DE VENTA A PARTIR DE REMISIÓN

//COLOCAR REDIMIDA UNA FACTURA, PUNTOS DEL CLIENTE

//SALDAR COMISIÓN A VENDEDORES

//ELIMINAR SUCURSALES

//AGREGAR PRODUCTOS

//APROBAR DESCUENTOS ESPECIALES EN COTIZACIONES


?>