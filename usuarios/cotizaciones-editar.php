<?php
include("sesion.php");

$idPagina = 79;

include("includes/verificar-paginas.php");
include("includes/head.php");

// ========================================
// CONSULTA PRINCIPAL OPTIMIZADA
// ========================================
// Solo traer campos necesarios en lugar de SELECT *
$consultaCliente = $conexionBdPrincipal->query("
	SELECT 
		c.cotiz_id, c.cotiz_cliente, c.cotiz_contacto, c.cotiz_vendedor, c.cotiz_proveedor,
		c.cotiz_sucursal, c.cotiz_fecha_propuesta, c.cotiz_fecha_vencimiento, c.cotiz_forma_pago,
		c.cotiz_moneda, c.cotiz_observaciones, c.cotiz_envio, c.cotiz_ticket, c.cotiz_vendida,
		c.cotiz_fecha_vendida, c.cotiz_ocultar_descuento_combo, c.cotiz_descuentos_especiales,
		c.cotiz_es_precotizacion, c.cotiz_version, c.cotiz_ultima_modificacion, c.cotiz_creador,
		cli.cli_id, cli.cli_nombre, cli.cli_categoria, cli.cli_ciudad, cli.cli_direccion,
		cli.cli_telefono, cli.cli_celular, cli.cli_credito,
		cont.cont_id, cont.cont_nombre, cont.cont_email
	FROM cotizacion c
	INNER JOIN clientes cli ON cli.cli_id = c.cotiz_cliente
	INNER JOIN contactos cont ON cont.cont_id = c.cotiz_contacto
	WHERE c.cotiz_id = '".$_GET["id"]."' AND c.cotiz_id_empresa = '".$idEmpresa."'
	LIMIT 1
");
$resultadoD = mysqli_fetch_array($consultaCliente, MYSQLI_BOTH);

if (!$resultadoD) {
	echo '<script>alert("Cotización no encontrada"); window.location.href="cotizaciones.php";</script>';
	exit;
}

if(isset($_GET["cte"]) && is_numeric($_GET["cte"])){
	$cliente = $_GET["cte"]; 
}else{
	$cliente = $resultadoD['cotiz_cliente'];
}

// Guardar info del cliente para uso posterior
$clienteInfo = [
	'cli_credito' => $resultadoD['cli_credito'],
	'cli_ciudad' => $resultadoD['cli_ciudad'],
	'cli_direccion' => $resultadoD['cli_direccion'],
	'cli_telefono' => $resultadoD['cli_telefono'],
	'cli_celular' => $resultadoD['cli_celular'],
	'cli_nombre' => $resultadoD['cli_nombre'],
	'cli_categoria' => $resultadoD['cli_categoria']
];

require_once RUTA_PROYECTO.'/usuarios/class/Cotizacion.php';
require_once RUTA_PROYECTO.'/usuarios/class/Pedido.php';
require_once RUTA_PROYECTO.'/usuarios/class/Remision.php';
require_once RUTA_PROYECTO.'/usuarios/class/Factura.php';
require_once RUTA_PROYECTO.'/usuarios/class/Combo.php';
require_once RUTA_PROYECTO.'/usuarios/class/Tickets.php';

$ticketAsociado = Ticket::obtenerDatosTikcetPorIdCotizacion($resultadoD['cotiz_id'], $conexionBdPrincipal);

// ========================================
// CACHE DE CONSULTAS COMUNES
// ========================================
// Usuarios activos (usado 2 veces en la página)
$usuariosActivos = [];
$consultaUsuarios = $conexionBdPrincipal->query("
	SELECT usr_id, usr_nombre, usr_email 
	FROM usuarios 
	WHERE usr_bloqueado != 1 AND usr_id_empresa = '".$idEmpresa."' 
	ORDER BY usr_nombre
");
while($usr = mysqli_fetch_array($consultaUsuarios, MYSQLI_BOTH)){
	$usuariosActivos[] = $usr;
}

// Sucursales del cliente
$sucursales = [];
$consultaSucursales = $conexionBdPrincipal->query("
	SELECT sucu_id, sucu_nombre 
	FROM sucursales 
	WHERE sucu_cliente_principal = '".$cliente."'
	ORDER BY sucu_nombre
");
while($sucu = mysqli_fetch_array($consultaSucursales, MYSQLI_BOTH)){
	$sucursales[] = $sucu;
}

// Si no hay sucursales, crear automáticamente
if(count($sucursales) == 0){
	$conexionBdPrincipal->query("
		INSERT INTO sucursales(sucu_cliente_principal, sucu_ciudad, sucu_direccion, sucu_telefono, sucu_celular, sucu_nombre)
		VALUES('".$cliente."', '".$clienteInfo['cli_ciudad']."', '".$clienteInfo['cli_direccion']."', '".$clienteInfo['cli_telefono']."', '".$clienteInfo['cli_celular']."','Sede principal (Automática)')
	");
	// Recargar sucursales
	$consultaSucursales = $conexionBdPrincipal->query("
		SELECT sucu_id, sucu_nombre 
		FROM sucursales 
		WHERE sucu_cliente_principal = '".$cliente."'
	");
	while($sucu = mysqli_fetch_array($consultaSucursales, MYSQLI_BOTH)){
		$sucursales[] = $sucu;
	}
}

// Contactos del cliente
$contactos = [];
$consultaContactos = $conexionBdPrincipal->query("
	SELECT cont_id, cont_nombre, cont_email 
	FROM contactos 
	WHERE cont_cliente_principal = '".$cliente."'
	ORDER BY cont_nombre
");
while($cont = mysqli_fetch_array($consultaContactos, MYSQLI_BOTH)){
	$contactos[] = $cont;
}

// Si no hay contactos, crear automáticamente
if(count($contactos) == 0){
	$conexionBdPrincipal->query("
		INSERT INTO contactos(cont_nombre, cont_cliente_principal)
		VALUES('Contacto principal (Automático)', '".$cliente."')
	");
	// Recargar contactos
	$consultaContactos = $conexionBdPrincipal->query("
		SELECT cont_id, cont_nombre, cont_email 
		FROM contactos 
		WHERE cont_cliente_principal = '".$cliente."'
	");
	while($cont = mysqli_fetch_array($consultaContactos, MYSQLI_BOTH)){
		$contactos[] = $cont;
	}
}
?>

<link href="css/chosen.css" rel="stylesheet">
<link href="../assets-login/plugins/select2/css/select2.css" rel="stylesheet" />
<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-fileupload.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.tagsinput.js"></script>
<script src="js/chosen.jquery.js"></script>
<script src="js/bootstrap-colorpicker.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script src="js/date.js"></script>
<script src="js/daterangepicker.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script src="../assets-login/plugins/select2/js/select2.js"></script>
<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>
<?php include("includes/texto-editor.php");?>


<?php if($resultadoD['cotiz_vendida']!=1){?>

	<script type="text/javascript">
		function productos(enviada){
			var tipoCliente   = enviada.alt;
			var campo         = enviada.title;
			var producto      = enviada.name;
			var proceso       = 2;
			var valor         = enviada.value;
			var valorAnterior = enviada.getAttribute('data-valor-actual');
			
			// Mostrar overlay con mensaje específico
			showAjaxOverlay('Actualizando producto...');
			
			$('#resp').empty().hide().html("Esperando...").show(1);
			datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo)+"&tipoCliente="+(tipoCliente);
			$.ajax({
				type: "POST",
				url: "ajax/ajax-productos.php",
				data: datos,
				success: function(data) {
					var response = JSON.parse(data);
					if(response.success) {
						$('#resp').empty().hide().html(response.message).show(1);
						// Ocultar overlay con éxito
						hideAjaxOverlay();
					} else {
						$('#resp').empty().hide().html('').show(1);
						// Ocultar overlay y mostrar error
						hideAjaxOverlay();
						alert(response.message);
						enviada.value = valorAnterior;
					}
				},
				error: function() {
					hideAjaxOverlay();
					alert('Error al actualizar el producto');
					enviada.value = valorAnterior;
				}
			});
		}


		function combos(enviada){
			var campo = enviada.title;
			var producto = enviada.name;
			var proceso = 11;
			var valor = enviada.value;
			
			// Mostrar overlay
			showAjaxOverlay('Actualizando combo...');
			
			$('#resp').empty().hide().html("Esperando...").show(1);
				datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo);
					$.ajax({
						type: "POST",
						url: "ajax/ajax-productos.php",
						data: datos,
						success: function(data){
						$('#resp').empty().hide().html(data).show(1);
						hideAjaxOverlay();
						},
						error: function() {
							hideAjaxOverlay();
							alert('Error al actualizar el combo');
						}
					});
		}	

		function servicios(enviada){
			var campo = enviada.title;
			var producto = enviada.name;
			var proceso = 12;
			var valor = enviada.value;
			
			// Mostrar overlay
			showAjaxOverlay('Actualizando servicio...');
			
			$('#resp').empty().hide().html("Esperando...").show(1);
				datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo);
					$.ajax({
						type: "POST",
						url: "ajax/ajax-productos.php",
						data: datos,
						success: function(data){
						$('#resp').empty().hide().html(data).show(1);
						hideAjaxOverlay();
						},
						error: function() {
							hideAjaxOverlay();
							alert('Error al actualizar el servicio');
						}
					});
		}
	</script>
<?php }?>
<?php
		require '../usuarios/class/CotizacionesEditar.php';

		if (!empty($_POST['action']) && $_POST['action'] === 'generarTablaProductos') {
			$htmlTablaProductos = CotizacionesEditar::generarTablaProductos($conexionBdPrincipal, $resultadoD,$simbolosMonedas, $idEmpresa);
			echo $htmlTablaProductos;
			exit; 
		}

		if (!empty($_POST['action']) && $_POST['action'] === 'generarTablacombos') {
			$htmlTablaCombos = CotizacionesEditar::generarTablacombos($conexionBdPrincipal, $resultadoD,$simbolosMonedas, $idEmpresa);
			echo $htmlTablaCombos;
			exit; 
		}

		if (!empty($_POST['action']) && $_POST['action'] === 'generarTablaServicios') {
			$htmlTablaServicios = CotizacionesEditar::generarTablaServicios($conexionBdPrincipal, $resultadoD,$simbolosMonedas, $idEmpresa);
			echo $htmlTablaServicios;
			exit; 
		}
?>
</head>
<body>

<!-- Overlay de carga inicial -->
<div id="loading-overlay" class="loading-overlay-modern">
	<div class="loading-content-modern">
		<div class="spinner-modern"></div>
		<h3 class="loading-text-modern">Cargando contenido...</h3>
		<p class="loading-subtext-modern">Por favor espere un momento</p>
	</div>
</div>

<!-- Overlay de operaciones AJAX -->
<div id="ajax-overlay" class="ajax-overlay-modern" style="display: none;">
	<div class="ajax-content-modern">
		<div class="ajax-spinner-modern"></div>
		<h4 class="ajax-text-modern" id="ajax-message">Procesando...</h4>
		<p class="ajax-subtext-modern">Por favor no cierre esta ventana</p>
	</div>
</div>

<style>
/* Overlay de carga moderno */
.loading-overlay-modern {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
	display: flex;
	justify-content: center;
	align-items: center;
	z-index: 9999;
	transition: opacity 0.5s ease, visibility 0.5s ease;
}

.loading-overlay-modern.hidden {
	opacity: 0;
	visibility: hidden;
}

.loading-content-modern {
	text-align: center;
	background: white;
	padding: 40px 60px;
	border-radius: 20px;
	box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
	animation: slideInUp 0.5s ease;
}

@keyframes slideInUp {
	from {
		transform: translateY(30px);
		opacity: 0;
	}
	to {
		transform: translateY(0);
		opacity: 1;
	}
}

/* Spinner moderno */
.spinner-modern {
	width: 60px;
	height: 60px;
	margin: 0 auto 20px;
	border: 4px solid #f3f3f3;
	border-top: 4px solid #667eea;
	border-right: 4px solid #764ba2;
	border-radius: 50%;
	animation: spin 1s linear infinite;
}

@keyframes spin {
	0% { transform: rotate(0deg); }
	100% { transform: rotate(360deg); }
}

.loading-text-modern {
	color: #333;
	font-size: 24px;
	font-weight: 600;
	margin: 0 0 10px 0;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
	background-clip: text;
}

.loading-subtext-modern {
	color: #666;
	font-size: 14px;
	margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
	.loading-content-modern {
		padding: 30px 40px;
		margin: 0 20px;
	}
	
	.spinner-modern {
		width: 50px;
		height: 50px;
	}
	
	.loading-text-modern {
		font-size: 20px;
	}
	
	.loading-subtext-modern {
		font-size: 13px;
	}
}

/* Estilos para carga lazy de tabs */
.lazy-loading-tab {
	display: flex;
	justify-content: center;
	align-items: center;
	min-height: 300px;
	padding: 40px 20px;
}

.lazy-loading-spinner {
	text-align: center;
}

.spinner-border {
	width: 50px;
	height: 50px;
	margin: 0 auto 20px;
	border: 4px solid #f3f3f3;
	border-top: 4px solid #667eea;
	border-radius: 50%;
	animation: spin 1s linear infinite;
}

.lazy-loading-spinner p {
	color: #666;
	font-size: 16px;
	margin: 0;
	font-weight: 500;
}

.tab-loaded {
	animation: fadeInTab 0.5s ease;
}

@keyframes fadeInTab {
	from {
		opacity: 0;
		transform: translateY(10px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

/* Error state */
.lazy-loading-error {
	text-align: center;
	padding: 40px 20px;
}

.lazy-loading-error i {
	font-size: 48px;
	color: #dc3545;
	margin-bottom: 15px;
}

.lazy-loading-error p {
	color: #666;
	font-size: 16px;
	margin: 10px 0;
}

.lazy-loading-error .btn {
	margin-top: 15px;
}

/* ========================================
   OVERLAY AJAX PARA OPERACIONES
   ======================================== */
.ajax-overlay-modern {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: rgba(0, 0, 0, 0.7);
	display: flex;
	justify-content: center;
	align-items: center;
	z-index: 10000; /* Mayor que el overlay de carga inicial */
	transition: opacity 0.3s ease;
}

.ajax-overlay-modern.show {
	display: flex;
	animation: fadeIn 0.3s ease;
}

.ajax-overlay-modern.hide {
	animation: fadeOut 0.3s ease;
}

@keyframes fadeIn {
	from { opacity: 0; }
	to { opacity: 1; }
}

@keyframes fadeOut {
	from { opacity: 1; }
	to { opacity: 0; }
}

.ajax-content-modern {
	text-align: center;
	background: white;
	padding: 35px 50px;
	border-radius: 15px;
	box-shadow: 0 15px 50px rgba(0, 0, 0, 0.4);
	animation: slideInScale 0.4s ease;
	min-width: 300px;
}

@keyframes slideInScale {
	from {
		transform: scale(0.8) translateY(20px);
		opacity: 0;
	}
	to {
		transform: scale(1) translateY(0);
		opacity: 1;
	}
}

/* Spinner para AJAX */
.ajax-spinner-modern {
	width: 50px;
	height: 50px;
	margin: 0 auto 20px;
	border: 4px solid #f3f3f3;
	border-top: 4px solid #667eea;
	border-right: 4px solid #764ba2;
	border-radius: 50%;
	animation: spin 0.8s linear infinite;
}

.ajax-text-modern {
	color: #333;
	font-size: 20px;
	font-weight: 600;
	margin: 0 0 8px 0;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
	background-clip: text;
}

.ajax-subtext-modern {
	color: #666;
	font-size: 13px;
	margin: 0;
}

/* Variantes de mensajes */
.ajax-content-modern.success {
	border-top: 4px solid #28a745;
}

.ajax-content-modern.error {
	border-top: 4px solid #dc3545;
}

.ajax-content-modern.warning {
	border-top: 4px solid #ffc107;
}

/* Responsive */
@media (max-width: 768px) {
	.ajax-content-modern {
		padding: 25px 35px;
		margin: 0 20px;
		min-width: 250px;
	}
	
	.ajax-spinner-modern {
		width: 40px;
		height: 40px;
	}
	
	.ajax-text-modern {
		font-size: 18px;
	}
	
	.ajax-subtext-modern {
		font-size: 12px;
	}
}
</style>

<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>                        
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="cotizaciones.php">Cotizaciones</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            
            <?php include("includes/notificaciones.php");?>

			<p>
				<?php if (Modulos::validarRol([78], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
				<a href="cotizaciones-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
				<?php } ?>
				<?php if (Modulos::validarRol([50], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
				<div class="btn-group">
					<a href="reportes/formato-cotizacion-1_pdf.php?id=<?=$_GET["id"];?>" class="btn btn-success" target="_blank"><i class="icon-print"></i> Imprimir (Formato 1)</a>
					<a href="reportes/formato-cotizacion-3_pdf.php?id=<?=$_GET["id"];?>" class="btn btn-warning" target="_blank"><i class="icon-print"></i> Imprimir (Formato 2)</a>
				</div>
				<?php } ?>
				
				<?php
				if(
					$resultadoD['cotiz_vendida'] != 1 && 
					Modulos::validarRol([263], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) &&
					!empty($ticketAsociado) && 
					$resultadoD['cotiz_es_precotizacion'] != 1
				) {
				?>
					<a href="bd_create/cotizaciones-generar-pedido.php?id=<?= $resultadoD['cotiz_id']; ?>" class="btn btn-info" onClick="if(!confirm('Desea generar pedido de esta cotización?')){return false;}"><i class="icon-money"></i> Generar pedido</a>
				<?php
				} else if ($resultadoD['cotiz_vendida'] != 1 && empty($ticketAsociado) && $resultadoD['cotiz_es_precotizacion'] != 1) {
				?>
					<div class="alert alert-warning">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<i class="icon-warning-sign"></i><strong>Ticket pendiente!</strong> No es posible generar pedido en una cotización sin ticket comercial asociado.
					</div>
				<?php } else if ($resultadoD['cotiz_es_precotizacion'] == 1) {?>
					<div class="alert alert-warning">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<i class="icon-warning-sign"></i><strong>Precotización!</strong> No es posible generar pedido con una PRE-cotización.
					</div>
				<?php }?>
			</p>
			
			<?php
			$camposCotizacionDisabled = '';

			if ($resultadoD['cotiz_vendida'] == Cotizacion::COTIZACION_VENDIDA) {
				$camposCotizacionDisabled = 'disabled';

				//Pedido
				$predicado = [
					'pedid_cotizacion' => $resultadoD['cotiz_id'],
					'pedid_id_empresa' => $idEmpresa
				];

				$pedidoAsociado = Pedido::Select($predicado);
				$pedidoAsociadoDatos = mysqli_fetch_array($pedidoAsociado, MYSQLI_BOTH);

				//Remisión
				$predicado = [
					'remi_pedido' => $pedidoAsociadoDatos[Pedido::$primaryKey],
					'remi_id_empresa' => $idEmpresa
				];

				$remisionAsociada = Remision::Select($predicado);
				$remisionAsociadaDatos = mysqli_fetch_array($remisionAsociada, MYSQLI_BOTH);

				$linkRemision = '#';
				$breadCrumbRemision = 'Remisión Pendiente';

				if (!empty($remisionAsociadaDatos[Remision::$primaryKey])) {
					$linkRemision = 'remisionbdg.php?busqueda='.$remisionAsociadaDatos[Remision::$primaryKey];
					$breadCrumbRemision = 'Remisión Nro. '.$remisionAsociadaDatos[Remision::$primaryKey];
				}

				//Factura
				$predicado = [
					'factura_remision' => $remisionAsociadaDatos[Remision::$primaryKey],
					'factura_id_empresa' => $idEmpresa
				];

				$facturaAsociada = Factura::Select($predicado);
				$facturaAsociadaDatos = mysqli_fetch_array($facturaAsociada, MYSQLI_BOTH);

				$linkFactura = '#';
				$breadCrumbFactura = 'Factura Pendiente';

				if (!empty($facturaAsociadaDatos[Factura::$primaryKey])) {
					$linkFactura = 'remisionbdg.php?busqueda='.$facturaAsociadaDatos[Factura::$primaryKey];
					$breadCrumbFactura = 'Factura Nro. '.$facturaAsociadaDatos[Factura::$primaryKey];
				}
			?>
				<p style="color: black; background-color: aquamarine; padding: 10px; font-weight: bold;">
					Esta cotización ya generó pedido en la siguiente fecha: <?=$resultadoD['cotiz_fecha_vendida'];?>. 
				</p>

				<ul class="breadcrumb" style="background: antiquewhite;">
					<?php if (!empty($ticketAsociado)) {?>
						<li><a href="clientes-tikets-editar.php?id=<?=$ticketAsociado['tik_id'];?>">Ticket Nro. <?=$ticketAsociado['tik_id'];?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<?php }?>
					<li><a href="#">Cotización Nro. <?=$resultadoD['cotiz_id'];?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<li><a href="pedidos.php?busqueda=<?=$pedidoAsociadoDatos[Pedido::$primaryKey];?>" target="_blank">Pedido Nro.<?=$pedidoAsociadoDatos[Pedido::$primaryKey];?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<li><a href="<?=$linkRemision;?>" target="_blank"><?=$breadCrumbRemision;?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<li><a href="<?=$linkFactura;?>" target="_blank"><?=$breadCrumbFactura;?></a><span class="divider"></li>
				</ul>

				<p style="color: black; background-color: gold; padding: 10px; font-weight: bold;"> No es posible hacer más cambios en esta cotización.</p>
			<?php
			} else if(!empty($ticketAsociado)) {
			?>
				<ul class="breadcrumb" style="background: antiquewhite;">
					<li><a href="clientes-tikets-editar.php?id=<?=$ticketAsociado['tik_id'];?>">Ticket Nro. <?=$ticketAsociado['tik_id'];?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<li><a href="#">Cotización Nro. <?=$resultadoD['cotiz_id'];?></a><span class="divider"></span></li>
				</ul>
			<?php
			}

			$versionActualCotizacion = Cotizacion::obtenerVersionCotizacion($resultadoD['cotiz_version']);
			?>

		<ul class="nav nav-tabs" id="myTab1">
			<li class="active"><a href="#cotizacion" data-toggle="tab"><i class="icon-file-alt"></i> Información de la cotización</a></li>
			<li><a href="#itemsCotizados" data-toggle="tab"><i class="icon-list"></i> Items cotizados</a></li>
			<li><a href="#enviarCotizacion" data-toggle="tab"><i class="icon-envelope"></i> Enviar cotización por correo</a></li>
			<li><a href="#cotizacionesAsociadas" data-toggle="tab" data-lazy-load="asociadas"><i class="icon-retweet"></i> Cotizaciones asociadas</a></li>
			<li><a href="#seguimientos" data-toggle="tab" data-lazy-load="seguimientos"><i class="icon-list-ol"></i> Seguimientos</a></li>
		</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="cotizacion">
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> <?=$paginaActual['pag_nombre'];?> #<?=$resultadoD['cotiz_id'];?> <?=$versionActualCotizacion;?> <?php if (!empty($resultadoD['cotiz_ultima_modificacion'])) echo " - Ultima modificación: " . $resultadoD['cotiz_ultima_modificacion'];?></h3>
								</div>
							<div class="widget-container">
								<!-- Mensaje de respuesta para guardado asíncrono -->
								<div id="mensaje-guardado" style="display: none; margin-bottom: 15px;"></div>
								
								<form class="form-horizontal" method="post" action="bd_update/cotizaciones-actualizar.php" id="form-cotizacion-info">
								<input type="hidden" name="id" id="id" value="<?=$_GET["id"];?>">
								<input type="hidden" name="monedaActual" value="<?=$resultadoD['cotiz_moneda'];?>">
									
								<script type="application/javascript">
										function clientes(datos){
											id = datos.value;
											idCotizacion = <?=$_GET["id"];?>;
											datos = "idCotizacion="+(idCotizacion)+"&idCliente="+(id);

											$.ajax({
												type: "POST",
												url: "ajax/ajax-cotizaciones-actualizar.php",
												data: datos,
												success: function(data) {
													var response = JSON.parse(data);
													if(response.success) {
														location.href = "cotizaciones-editar.php?id="+idCotizacion+"&cte="+id+"#productos";
													} else {
														alert(response.message);
													}

												}
											});
											
										}
									</script>
									
									<div class="form-actions">
										<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
										<?php
										if($resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA){
										?>
										<button type="button" id="btn-guardar-cambios" class="btn btn-info">
											<i class="icon-save"></i> <span id="btn-text">Guardar cambios</span>
										</button>
										<?php }?>
									</div>
										

										<?php if($configuracion['conf_proveedor_cotizacion'] == 1){?>
										
										<div class="control-group">
											<label class="control-label">Escoja un proveedor</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="proveedor" onChange="provee(this)" required>
													<option value=""></option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT prov_id, prov_nombre FROM proveedores WHERE prov_id_empresa='".$idEmpresa."'");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>" <?php if($resultadoD['cotiz_proveedor']==$resOp[0]) echo "selected";?>><?=$resOp['prov_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
											
											
													<?php if (Modulos::validarRol([125], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
													<a href="proveedores-editar.php?id=<?=$resultadoD['cotiz_proveedor'];?>" class="btn btn-info" target="_blank">Editar proveedor</a>
													<?php } ?>
											
											
										</div>
		
										<?php }?>

										
									<fieldset class="default">
										<legend>Datos del cliente</legend>	
										
									<div class="control-group">
											<label class="control-label">Cliente</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente" required onChange="clientes(this)" <?=$camposCotizacionDisabled;?>>
													<option value=""></option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT cli_id, cli_nombre, cli_categoria, 
														CASE 
															WHEN cli_categoria = '".CLI_CATEGORIA_DEALER."' THEN '(DEALER)'
															ELSE ''
														END AS 'categoria'	
														FROM clientes 
														WHERE cli_id_empresa='".$idEmpresa."'
														ORDER BY cli_categoria, cli_nombre
														");

														$categoriaActual = 1;
														$nombreCategoria = ['','Prospectos', 'Clientes', 'Dealer'];
														echo '<optgroup label="'.$nombreCategoria[1].'">';

													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){

														if ($categoriaActual != $resOp['cli_categoria']) {
															echo '</optgroup>';
															echo '<optgroup label="'.$nombreCategoria[$resOp['cli_categoria']].'">';
															$categoriaActual = $resOp['cli_categoria'];
														}

														$disabled = '';
														
														if(!Modulos::validarRol([393], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) and $resOp['cli_categoria']== CLI_CATEGORIA_DEALER) {
															$disabled = 'disabled';
														}	
														

													?>
														<option value="<?=$resOp['cli_id'];?>" <?php if($cliente==$resOp['cli_id']){echo "selected";}  echo $disabled; ?>><?=$resOp['cli_nombre']." ".$resOp['categoria'];?></option>
													<?php
													}
													?>
												</select>
											</div>
											<?php if (Modulos::validarRol([11], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && $resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA) {?>
													<a href="clientes-editar.php?id=<?=$cliente;?>" class="btn btn-info" target="_blank">Editar cliente</a>
											<?php } ?>
									</div>
									
									<div class="control-group">
										<label class="control-label">Sucursal</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="sucursal" required <?=$camposCotizacionDisabled;?>>
												<option value=""></option>
												<?php
												// Usar sucursales del cache
												foreach($sucursales as $sucursal){
												?>
													<option value="<?=$sucursal['sucu_id'];?>" <?php if($resultadoD['cotiz_sucursal']==$sucursal['sucu_id'] || count($sucursales) == 1){echo "selected";} ?>><?=$sucursal['sucu_nombre'];?></option>
												<?php 
												}
												?>
											</select>
										</div>
										<?php if (Modulos::validarRol([83], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="clientes-sucursales.php?cte=<?=$cliente;?>" class="btn btn-info" target="_blank">Ver sucursales</a>
										<?php } ?>
								</div>
										
									<div class="control-group">
										<label class="control-label">Contacto</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="contacto" required <?=$camposCotizacionDisabled;?>>
												<option value=""></option>
												<?php
												// Usar contactos del cache
												foreach($contactos as $contacto){
												?>
													<option value="<?=$contacto['cont_id'];?>" <?php if($resultadoD['cotiz_contacto']==$contacto['cont_id'] || count($contactos) == 1){echo "selected";}?>><?=strtoupper($contacto['cont_nombre'])." (".$contacto['cont_email'].")";?></option>
												<?php
												}
												?>
											</select>
										</div>
										<?php if (Modulos::validarRol([44], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
										<a href="clientes-contactos.php?cte=<?=$cliente;?>" class="btn btn-info" target="_blank">Ver contactos</a>
										<?php } ?>
								</div>
									
									</fieldset>
										
									<div class="control-group">
										<label class="control-label">Usuario Influyente</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="influyente" <?=$camposCotizacionDisabled;?>>
												<option value=""></option>
												<?php
												// Usar usuarios del cache
												foreach($usuariosActivos as $usuario){
												?>
													<option value="<?=$usuario['usr_id'];?>" <?php if($resultadoD['cotiz_vendedor']==$usuario['usr_id']){echo "selected";}?>><?=strtoupper($usuario['usr_nombre'])." (".$usuario['usr_email'].")";?></option>
												<?php
												}
												?>
											</select>
										</div>
								</div>
									
									<div class="control-group">
											<label class="control-label">Fecha de la propuesta</label>
											<div class="controls">
												<input type="date" class="span4" name="fechaPropuesta" required value="<?=$resultadoD['cotiz_fecha_propuesta'];?>" <?=$camposCotizacionDisabled;?>>
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label">Fecha de vencimiento</label>
											<div class="controls">
												<input type="date" class="span4" name="fechaVencimiento" required value="<?=$resultadoD['cotiz_fecha_vencimiento'];?>" <?=$camposCotizacionDisabled;?>>
											</div>
										</div>
										
										<?php
										if (!Cotizacion::esCotizacionVendida($resultadoD['cotiz_id'], $idEmpresa)) {
											if(isset($clienteInfo['cli_credito'])){
												if($clienteInfo['cli_credito']==1){
													$msjCredito = "Este cliente tiene crédito con la compañía.";
													$colorCredito = 'aquamarine';
												}
											}else{
												$msjCredito = "Este cliente aún NO tiene crédito con la compañía.";
												$colorCredito = 'gold';
											}
											?>
											<p style="color: black; background-color: <?=$colorCredito;?>; padding: 10px; font-weight: bold;"><?=$msjCredito;?></p>
										<?php }?>
										
										<div class="control-group">
											<label class="control-label">Forma de pago</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="formaPago" <?=$camposCotizacionDisabled;?>>
													<option value=""></option>
													<option value="1" <?php if($resultadoD['cotiz_forma_pago']==1)echo "selected";?>>Contado</option>
													<option value="2" <?php if($resultadoD['cotiz_forma_pago']==2)echo "selected";?>>Crédito</option>
												</select>
											</div>
									</div>

									<script type="text/javascript">
										function getPay(pay){
											let msg;
											
											if(pay.value == 1){
												msg = 'La cotización cambiará a pesos colombianos. Es decir que se tomará el valor en dolares y se multiplicará por el TRM de venta actual.';
											}else if(pay.value == 2){
												msg = 'La cotización cambiará a dólares americanos. Es decir que se tomará el valor en pesos y se dividirá entre el TRM de compra actual.';
											}

											alert(msg);
										}
									</script>
										
										<div class="control-group">
											<label class="control-label">Moneda</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="moneda" onChange="getPay(this)" <?=$camposCotizacionDisabled;?>>

													<option value=""></option>
													<option value="1" <?php if($resultadoD['cotiz_moneda']==1)echo "selected";?>>COP</option>
													<option value="2" <?php if($resultadoD['cotiz_moneda']==2)echo "selected";?>>USD</option>
												</select>
											</div>
									</div>	
										
									<div class="control-group">
											<label class="control-label">Combos</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="span10" tabindex="2" name="combo[]" multiple id="combos-select" <?=$camposCotizacionDisabled;?>>
													<option value=""></option>
													<?php
													// Consulta optimizada: solo campos necesarios, índice en czpp_cotizacion y czpp_tipo
													$conOp = $conexionBdPrincipal->query("
														SELECT cb.combo_id, cb.combo_nombre 
														FROM cotizacion_productos cp
														INNER JOIN combos cb ON cb.combo_id = cp.czpp_combo AND cb.combo_id_empresa = '".$idEmpresa."'
														WHERE cp.czpp_cotizacion = '".$resultadoD['cotiz_id']."' AND cp.czpp_tipo = '".CZPP_TIPO_COTZ."'
														ORDER BY cb.combo_nombre
													");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option selected value="<?=$resOp['combo_id'];?>"><?=$resOp['combo_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
									</div>
										
									<div class="control-group">
											<label class="control-label">Productos</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="span10" tabindex="2" name="producto[]" multiple id="product-select" <?=$camposCotizacionDisabled;?>>
												<?php
													// Consulta optimizada: solo campos necesarios para el select
													$consultaProductos = $conexionBdPrincipal->query("
														SELECT p.prod_id, p.prod_nombre, cp.czpp_cantidad
														FROM cotizacion_productos cp
														INNER JOIN productos p ON p.prod_id = cp.czpp_producto AND p.prod_id_empresa = '".$idEmpresa."'
														WHERE cp.czpp_cotizacion = '".$_GET["id"]."' AND cp.czpp_tipo = ".CZPP_TIPO_COTZ."
														ORDER BY p.prod_nombre
													");

													while ($resProducto = mysqli_fetch_array($consultaProductos, MYSQLI_BOTH)) {
													?>
														<option selected value="<?= $resProducto['prod_id']; ?>"><?= $resProducto['prod_id'] . ". " . strtoupper($resProducto['prod_nombre']) . " - [HAY " . $resProducto['czpp_cantidad'] . "]"; ?></option>
													<?php
														}
													?>
												</select>
											</div>
										</div>


										<div class="control-group">
											<label class="control-label">Ocultar descuento de combos</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span2" tabindex="2" name="dctoCombos" <?=$camposCotizacionDisabled;?>>
													<option value=""></option>
													<option value="1" <?php if($resultadoD['cotiz_ocultar_descuento_combo']==1)echo "selected";?>>SI</option>
													<option value="0" <?php if($resultadoD['cotiz_ocultar_descuento_combo']=='0')echo "selected";?>>NO</option>
												</select>
											</div>
									</div>

									<?php if (!Cotizacion::esCotizacionVendida($resultadoD['cotiz_id'], $idEmpresa)) {?>
										<p style="color: black; background-color: mediumaquamarine; padding: 10px; font-weight: bold;">Escoja SÍ, si desea solicitar a la Administración, que a esta cotización se le hagan algunos descuentos especiales en los items cotizados.</p>
									<?php }?>

									<div class="control-group">
											<label class="control-label">Requiere un descuento especial?</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span2" tabindex="2" name="dctoEspecial" <?=$camposCotizacionDisabled;?>>
													<option value=""></option>
													<option value="1" <?php if($resultadoD['cotiz_descuentos_especiales']==1)echo "selected";?>>SI</option>
													<option value="0" <?php if($resultadoD['cotiz_descuentos_especiales']=='0')echo "selected";?>>NO</option>
												</select>
											</div>
									</div>
										
										
										
											<div class="control-group">
												<label class="control-label">Observaciones</label>
												<div class="controls">
													<textarea rows="5" cols="80" style="width: 80%" class="tinymce-simple" name="notas" <?=$camposCotizacionDisabled;?>><?=$resultadoD['cotiz_observaciones'];?></textarea>
												</div>
											</div>
										
										<div class="control-group">
											<label class="control-label">Costo Envío</label>
											<div class="controls">
												<input type="text" class="span4" name="envio" value="<?=$resultadoD['cotiz_envio'];?>" <?=$camposCotizacionDisabled;?>>
											</div>
										</div>

									<?php
									$envio = $resultadoD['cotiz_envio'];

									if (empty($resultadoD['cotiz_ticket'])) {
										// Consulta optimizada: solo campos necesarios para mostrar
										$consultaTickets = $conexionBdPrincipal->query("
											SELECT tik_id, tik_asunto, tik_fecha_creacion
											FROM clientes_tikets 
											WHERE tik_cliente = '".$resultadoD['cotiz_cliente']."'
											AND tik_id_cotizacion IS NULL
											AND tik_tipo_tiket = 1
											AND tik_estado = 1
											AND tik_tipo_negocio = 1
											ORDER BY tik_id DESC
										");
										$numTickets = $consultaTickets->num_rows;
									?>

											<div class="control-group">
												<label class="control-label">Asociar a un ticket</label>
												<div class="controls">
													<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="ticket" <?=$camposCotizacionDisabled;?>>
														<option value="TICKET_AUTO">Deseo que el ticket se cree automáticamente</option>
														<option value="NO_TICKET" selected>NO deseo asociar ningun ticket a esta cotización por el momento</option>
													<?php
													if ($numTickets > 0) {
														echo '<optgroup label="Tickets disponibles">';
														while ($resOp = mysqli_fetch_array($consultaTickets, MYSQLI_BOTH)) {
													?>
															<option value="<?=$resOp['tik_id'];?>"><?="Ticket # ".$resOp['tik_id']." - ".strtoupper($resOp['tik_asunto'])." (".$resOp['tik_fecha_creacion'].")";?></option>
													<?php
													}
														echo '</optgroup>';
														}
													?>
													</select>
												</div>
											</div>
										<?php } else {?>
											<input type="hidden" name="ticket" value="<?=$resultadoD['cotiz_ticket'];?>">
										<?php }?>
										
								<div class="form-actions">
										<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
										<?php
										if($resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA){
										?>
										<button type="button" id="btn-guardar-cambios-2" class="btn btn-info">
											<i class="icon-save"></i> <span id="btn-text-2">Guardar cambios</span>
										</button>
										<?php }?>
									</div>
									</form>
									
							</div>
						</div>
					</div>
				</div>
			</div>

		<!-- LISTADO DE LO QUE SE ESTÁ COTIZANDO -->	
		<div class="tab-pane" id="itemsCotizados">
			<div class="row-fluid">
				<div class="span12">
					
					<span id="resp"></span>
					
					<div class="content-widgets light-gray" id="productos">
						<div class="widget-head green">
							<h3>PRODUCTOS</h3>
						</div>
						<div class="widget-container">
							<p></p>
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
								<th>Orden</th>
								<th>Producto/Servicio</th>
								<th>Cant.</th> 
								<th>Valor Base</th>
								<th>IVA</th>
								<th>Dcto.</th>
								<?php 
								$colspan = 7;
								if($resultadoD['cotiz_descuentos_especiales'] == 1){
									$colspan = 8;
								?>
								<th>Dcto. Especial</th>
								<?php }?>

								<th>SUBTOTAL</th>
							</tr>
							</thead>
							<tbody id="tableBody"></tbody>
							<tfoot>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="<?=$colspan;?>">SUBTOTAL</td>
									<td id="subtotal">
									<span class="moneda-simbolo">
										<?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
										<span class="valor-numerico"><?=!empty($subtotal) ? number_format($subtotal,0,",",".") : 0;?>
										</span>
									</td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="<?=$colspan;?>">DESCUENTO</td>
									<td id="totalDiscount"><span class="moneda-simbolo">
										<?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
										<span class="valor-numerico"><?=!empty($totalDescuento) ? number_format($envio,0,",",".") : 0;?>
										</span></td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="<?=$colspan;?>">IVA</td>
									<td id="totalIva"><span class="moneda-simbolo">
										<?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
										<span class="valor-numerico"><?=!empty($totalIva) ? number_format($totalIva,0,",",".") : 0;?>
										</span></td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="<?=$colspan;?>">ENVÍO</td>
									<td><?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?><?php if(!empty($envio)) echo number_format($envio,0,",","."); else echo 0;?>
										</td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="<?=$colspan;?>">TOTAL NETO</td>
									<td id="total"><span class="moneda-simbolo">
										<?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
										<span class="valor-numerico"><?=!empty($subtotal) ? number_format($subtotal,0,",",".") : 0;?>
										</span></td>
								</tr>
							</tfoot>	
								
							</table>

							<?php
							if(Modulos::validarRol([394], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){?>

								<p style="color: black; background-color: #d8ff0038; padding: 15px; font-weight: bold; font-size: 16px;">Esta cotización deja una utilidad aproximada de $<span id="utilidadTotal">0</span>
							<?php }?>
							
							
								<div class="form-actions">
									
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<?php
									if($resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA){
									?>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<?php }?>
									
										
									<?php if (Modulos::validarRol([50], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<div class="btn-group">
										<a href="reportes/formato-cotizacion-1_pdf.php?id=<?=$_GET["id"];?>" class="btn btn-success" target="_blank"><i class="icon-print"></i> Imprimir (Formato 1)</a>
										<a href="reportes/formato-cotizacion-3_pdf.php?id=<?=$_GET["id"];?>" class="btn btn-warning" target="_blank"><i class="icon-print"></i> Imprimir (Formato 2)</a>
									</div>
									<?php } ?>
								</div>
							</form>
							
						</div>
					</div>
				</div>
			</div>
		</div>

				<div class="tab-pane" id="enviarCotizacion">
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head orange">
									<h3> Enviar cotización por correo</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="post" action="enviar_correos/cotizaciones-enviar-correo.php">
									<input type="hidden" name="id" value="<?=$_GET["id"];?>">
										<div class="control-group">
											<label class="control-label">Nombre del contacto</label>
											<div class="controls">
												<input type="text" class="span8" name="destinoNombre" readonly value="<?=$resultadoD['cont_nombre'];?>">
											</div>
										</div>	

										<div class="control-group">
											<label class="control-label">Email</label>
											<div class="controls">
												<input type="text" class="span8" name="destinoEmail" readonly value="<?=$resultadoD['cont_email'];?>">
											</div>
										</div>	

										<div class="control-group">
											<label class="control-label">Asunto</label>
											<div class="controls">
												<input type="text" class="span8" name="asunto" required value="COTIZACIÓN #<?=$resultadoD['cotiz_id'];?>">
											</div>
										</div>	

										<div class="control-group">
											<label class="control-label">Mensaje</label>
											<div class="controls">
												<textarea rows="7" cols="80" style="width: 80%" class="tinymce-simple" name="mensaje"><?=strtoupper($resultadoD['cli_nombre']);?><br>
													<?=strtoupper($resultadoD['cont_nombre']);?><br><br>
													<br>
													<?=$configuracion['conf_emsj_cotizacion'];?>
												</textarea>
											</div>
										</div>

										<?php if (!empty($resultadoD['cont_email']) && filter_var($resultadoD['cont_email'], FILTER_VALIDATE_EMAIL)) { ?>
											<div class="form-actions">
												<button type="submit" class="btn btn-info"><i class="icon-envelope"></i> Enviar cotización</button>
											</div>
										<?php } else {?>
											<div class="alert alert-warning">
												<button type="button" class="close" data-dismiss="alert">&times;</button>
												<i class="icon-warning-sign"></i><strong>Email inválido!</strong> Este contacto no tiene email registrado o el que tiene está incorrecto.
											</div>
										<?php }?>
									</form>	
								</div>
							</div>
						</div>
					</div>
				</div>

			<div class="tab-pane" id="cotizacionesAsociadas">
				<div class="lazy-loading-tab">
					<div class="lazy-loading-spinner">
						<div class="spinner-border"></div>
						<p>Cargando cotizaciones asociadas...</p>
					</div>
				</div>
			</div>

			<div class="tab-pane" id="seguimientos">
				<div class="lazy-loading-tab">
					<div class="lazy-loading-spinner">
						<div class="spinner-border"></div>
						<p>Cargando seguimientos...</p>
					</div>
				</div>
			</div>

			</div>


		</div>
	</div>
	<?php include("includes/pie.php");?>
	<script src="js/Cotizaciones.js"></script>
</div>

<script>
// Ocultar overlay cuando la página esté completamente cargada
window.addEventListener('load', function() {
	// Esperar un pequeño delay para que se vea el overlay
	setTimeout(function() {
		const overlay = document.getElementById('loading-overlay');
		if (overlay) {
			overlay.classList.add('hidden');
			
			// Remover el overlay del DOM después de la transición
			setTimeout(function() {
				overlay.style.display = 'none';
			}, 500);
		}
	}, 300); // 300ms de delay para que el usuario vea el overlay brevemente
});

// También ocultar si jQuery está listo (para páginas con muchos AJAX)
$(document).ready(function() {
	// Este es un respaldo adicional
	setTimeout(function() {
		const overlay = document.getElementById('loading-overlay');
		if (overlay && !overlay.classList.contains('hidden')) {
			overlay.classList.add('hidden');
			setTimeout(function() {
				overlay.style.display = 'none';
			}, 500);
		}
	}, 2000); // Timeout máximo de 2 segundos
	
	// ========================================
	// SISTEMA DE CARGA LAZY PARA TABS
	// ========================================
	
	// Objeto para controlar qué tabs ya se han cargado
	const loadedTabs = {
		asociadas: false,
		seguimientos: false
	};
	
	// ID de la cotización actual
	const cotizacionId = <?=$_GET["id"];?>;
	
	/**
	 * Función para cargar el contenido de un tab via AJAX
	 */
	function loadTabContent(tabType, tabId) {
		// Si ya está cargado, no hacer nada
		if (loadedTabs[tabType]) {
			return;
		}
		
		// Mapeo de tipos a URLs
		const urls = {
			'asociadas': 'ajax/cotizaciones-editar-asociadas.php',
			'seguimientos': 'ajax/cotizaciones-editar-seguimientos.php'
		};
		
		const url = urls[tabType];
		
		if (!url) {
			console.error('Tipo de tab no reconocido:', tabType);
			return;
		}
		
		// Obtener el contenedor del tab
		const $tabPane = $('#' + tabId);
		
		// Realizar la petición AJAX
		$.ajax({
			url: url,
			type: 'GET',
			data: { id: cotizacionId },
			dataType: 'json',
			beforeSend: function() {
				console.log('Cargando contenido de tab:', tabType);
			},
			success: function(response) {
				if (response.success) {
					// Reemplazar el contenido del tab con el HTML recibido
					$tabPane.html(response.html);
					
					// Agregar clase de animación
					$tabPane.addClass('tab-loaded');
					
					// Marcar como cargado
					loadedTabs[tabType] = true;
					
					console.log('Tab cargado exitosamente:', tabType);
				} else {
					// Mostrar error
					showTabError($tabPane, response.message || 'Error al cargar el contenido', tabType, tabId);
				}
			},
			error: function(xhr, status, error) {
				console.error('Error al cargar tab:', tabType, error);
				showTabError($tabPane, 'Error al cargar el contenido. Por favor, intente nuevamente.', tabType, tabId);
			}
		});
	}
	
	/**
	 * Función para mostrar un error en el tab
	 */
	function showTabError($tabPane, message, tabType, tabId) {
		const errorHtml = `
			<div class="lazy-loading-error">
				<i class="icon-exclamation-sign"></i>
				<p>${message}</p>
				<button class="btn btn-primary" onclick="retryLoadTab('${tabType}', '${tabId}')">
					<i class="icon-refresh"></i> Reintentar
				</button>
			</div>
		`;
		$tabPane.html(errorHtml);
	}
	
	/**
	 * Función global para reintentar la carga (llamada desde el HTML)
	 */
	window.retryLoadTab = function(tabType, tabId) {
		// Resetear el estado de carga
		loadedTabs[tabType] = false;
		
		// Mostrar el spinner nuevamente
		const $tabPane = $('#' + tabId);
		$tabPane.html(`
			<div class="lazy-loading-tab">
				<div class="lazy-loading-spinner">
					<div class="spinner-border"></div>
					<p>Cargando contenido...</p>
				</div>
			</div>
		`);
		
		// Intentar cargar de nuevo
		loadTabContent(tabType, tabId);
	};
	
	/**
	 * Evento cuando se cambia de tab
	 */
	$('a[data-toggle="tab"]').on('shown', function(e) {
		const $target = $(e.target);
		const lazyLoad = $target.data('lazy-load');
		const tabId = $target.attr('href').substring(1); // Remover el #
		
		// Si tiene atributo data-lazy-load, cargar el contenido
		if (lazyLoad && !loadedTabs[lazyLoad]) {
			loadTabContent(lazyLoad, tabId);
		}
	});
	
	// Detectar si hay un hash en la URL para cargar ese tab directamente
	const hash = window.location.hash;
	if (hash) {
		const tabId = hash.substring(1);
		const $tabLink = $('a[href="' + hash + '"]');
		
		if ($tabLink.length > 0) {
			const lazyLoad = $tabLink.data('lazy-load');
			
			// Activar el tab
			$tabLink.tab('show');
			
			// Si necesita carga lazy, cargar el contenido
			if (lazyLoad && !loadedTabs[lazyLoad]) {
				setTimeout(function() {
					loadTabContent(lazyLoad, tabId);
				}, 100);
			}
		}
	}
	
	console.log('Sistema de carga lazy para tabs inicializado');
	
	// ========================================
	// GUARDADO ASÍNCRONO DEL FORMULARIO
	// ========================================
	
	/**
	 * Función para mostrar mensajes de respuesta
	 */
	function mostrarMensaje(tipo, mensaje) {
		const $mensajeDiv = $('#mensaje-guardado');
		let icono = '';
		let alertClass = '';
		
		switch(tipo) {
			case 'success':
				alertClass = 'alert-success';
				icono = '<i class="icon-ok-sign"></i>';
				break;
			case 'error':
				alertClass = 'alert-danger';
				icono = '<i class="icon-exclamation-sign"></i>';
				break;
			case 'warning':
				alertClass = 'alert-warning';
				icono = '<i class="icon-warning-sign"></i>';
				break;
			case 'info':
				alertClass = 'alert-info';
				icono = '<i class="icon-info-sign"></i>';
				break;
		}
		
		$mensajeDiv.html(`
			<div class="alert ${alertClass}">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				${icono} <strong>${mensaje}</strong>
			</div>
		`).fadeIn();
		
		// Scroll suave hacia el mensaje
		$('html, body').animate({
			scrollTop: $mensajeDiv.offset().top - 100
		}, 500);
		
		// Auto-ocultar después de 5 segundos
		setTimeout(function() {
			$mensajeDiv.fadeOut();
		}, 5000);
	}
	
	/**
	 * Función para deshabilitar/habilitar botones
	 */
	function toggleBotones(disabled) {
		$('#btn-guardar-cambios, #btn-guardar-cambios-2').prop('disabled', disabled);
		
		if(disabled) {
			$('#btn-text, #btn-text-2').html('Guardando... <i class="icon-spinner icon-spin"></i>');
		} else {
			$('#btn-text, #btn-text-2').html('Guardar cambios');
		}
	}
	
	/**
	 * Función para actualizar la fecha de última modificación en el header
	 */
	function actualizarFechaModificacion(fecha) {
		const $header = $('.widget-head.bondi-blue h3');
		const textoActual = $header.text();
		
		// Buscar si ya existe "Ultima modificación"
		if(textoActual.indexOf('Ultima modificación') !== -1) {
			// Reemplazar la fecha existente
			const partes = textoActual.split(' - Ultima modificación:');
			$header.text(partes[0] + ' - Ultima modificación: ' + fecha);
		} else {
			// Agregar la fecha por primera vez
			$header.text(textoActual + ' - Ultima modificación: ' + fecha);
		}
		
		// Efecto visual para indicar actualización
		$header.fadeOut(200).fadeIn(200);
	}
	
	/**
	 * Manejador del click en los botones de guardar
	 */
	$('#btn-guardar-cambios, #btn-guardar-cambios-2').on('click', function(e) {
		e.preventDefault();
		
		console.log('Iniciando guardado asíncrono...');
		
		// Obtener todos los datos del formulario
		const formData = $('#form-cotizacion-info').serialize();
		
		// Deshabilitar botones durante el guardado
		toggleBotones(true);
		
		// Ocultar mensaje anterior si existe
		$('#mensaje-guardado').fadeOut();
		
		// Realizar petición AJAX
		$.ajax({
			url: 'ajax/cotizaciones-guardar-asincrono.php',
			type: 'POST',
			data: formData,
			dataType: 'json',
			success: function(response) {
				console.log('Respuesta del servidor:', response);
				
				if(response.success) {
					// Mostrar mensaje de éxito
					mostrarMensaje('success', response.message);
					
					// Actualizar fecha de última modificación si viene en la respuesta
					if(response.ultima_modificacion) {
						actualizarFechaModificacion(response.ultima_modificacion);
					}
					
					// Si es el cambio de cliente, recargar página (como antes)
					if($('#form-cotizacion-info').data('cliente-cambiado')) {
						setTimeout(function() {
							location.reload();
						}, 1500);
					}
				} else {
					// Mostrar mensaje de error
					mostrarMensaje('error', response.message || 'Error al guardar los cambios');
				}
			},
			error: function(xhr, status, error) {
				console.error('Error AJAX:', {xhr, status, error});
				
				let mensaje = 'Error al guardar los cambios';
				
				// Intentar obtener mensaje del servidor
				try {
					const response = JSON.parse(xhr.responseText);
					if(response.message) {
						mensaje = response.message;
					}
				} catch(e) {
					console.error('Error parseando respuesta:', e);
				}
				
				mostrarMensaje('error', mensaje);
			},
			complete: function() {
				// Rehabilitar botones
				toggleBotones(false);
				console.log('Guardado completado');
			}
		});
	});
	
	// Prevenir submit tradicional del formulario
	$('#form-cotizacion-info').on('submit', function(e) {
		e.preventDefault();
		// Simular click en el botón para usar la misma lógica
		$('#btn-guardar-cambios').trigger('click');
		return false;
	});
	
	console.log('Sistema de guardado asíncrono inicializado');
});

// ========================================
// FUNCIONES GLOBALES PARA OVERLAY AJAX
// ========================================

/**
 * Muestra el overlay de operaciones AJAX
 * @param {string} mensaje - Mensaje a mostrar (opcional)
 */
function showAjaxOverlay(mensaje) {
	const $overlay = $('#ajax-overlay');
	const $messageElement = $('#ajax-message');
	
	// Establecer mensaje personalizado si se proporciona
	if (mensaje) {
		$messageElement.text(mensaje);
	} else {
		$messageElement.text('Procesando...');
	}
	
	// Mostrar overlay con animación
	$overlay.css('display', 'flex').addClass('show');
	
	// Prevenir scroll del body
	$('body').css('overflow', 'hidden');
	
	console.log('Overlay AJAX mostrado:', mensaje);
}

/**
 * Oculta el overlay de operaciones AJAX
 * @param {number} delay - Delay en ms antes de ocultar (opcional)
 */
function hideAjaxOverlay(delay) {
	const $overlay = $('#ajax-overlay');
	
	if (typeof delay === 'number' && delay > 0) {
		setTimeout(function() {
			ocultarOverlay();
		}, delay);
	} else {
		ocultarOverlay();
	}
	
	function ocultarOverlay() {
		$overlay.removeClass('show').addClass('hide');
		
		// Después de la animación, ocultar completamente
		setTimeout(function() {
			$overlay.css('display', 'none').removeClass('hide');
			// Restaurar scroll del body
			$('body').css('overflow', '');
		}, 300);
		
		console.log('Overlay AJAX ocultado');
	}
}

/**
 * Muestra el overlay con un mensaje de éxito y lo oculta automáticamente
 * @param {string} mensaje - Mensaje de éxito
 * @param {number} duration - Duración en ms (default: 1500)
 */
function showAjaxSuccess(mensaje, duration) {
	const $overlay = $('#ajax-overlay');
	const $content = $overlay.find('.ajax-content-modern');
	const $messageElement = $('#ajax-message');
	const $spinner = $overlay.find('.ajax-spinner-modern');
	
	// Cambiar a modo éxito
	$content.addClass('success');
	$spinner.hide();
	$messageElement.html('<i class="icon-ok-sign"></i> ' + (mensaje || 'Operación exitosa'));
	
	// Mostrar overlay
	$overlay.css('display', 'flex').addClass('show');
	$('body').css('overflow', 'hidden');
	
	// Ocultar después del tiempo especificado
	setTimeout(function() {
		hideAjaxOverlay();
		// Restaurar estado original
		setTimeout(function() {
			$content.removeClass('success');
			$spinner.show();
			$messageElement.text('Procesando...');
		}, 400);
	}, duration || 1500);
}

/**
 * Muestra el overlay con un mensaje de error
 * @param {string} mensaje - Mensaje de error
 * @param {number} duration - Duración en ms (default: 2000)
 */
function showAjaxError(mensaje, duration) {
	const $overlay = $('#ajax-overlay');
	const $content = $overlay.find('.ajax-content-modern');
	const $messageElement = $('#ajax-message');
	const $spinner = $overlay.find('.ajax-spinner-modern');
	
	// Cambiar a modo error
	$content.addClass('error');
	$spinner.hide();
	$messageElement.html('<i class="icon-exclamation-sign"></i> ' + (mensaje || 'Error en la operación'));
	
	// Mostrar overlay
	$overlay.css('display', 'flex').addClass('show');
	$('body').css('overflow', 'hidden');
	
	// Ocultar después del tiempo especificado
	setTimeout(function() {
		hideAjaxOverlay();
		// Restaurar estado original
		setTimeout(function() {
			$content.removeClass('error');
			$spinner.show();
			$messageElement.text('Procesando...');
		}, 400);
	}, duration || 2000);
}

/**
 * Interceptor global para todas las peticiones AJAX de jQuery
 * (Opcional: comentar si causa conflictos)
 */
/*
$(document).ajaxStart(function() {
	// Solo mostrar si no hay overlay ya visible
	if (!$('#ajax-overlay').is(':visible')) {
		showAjaxOverlay('Procesando petición...');
	}
}).ajaxStop(function() {
	hideAjaxOverlay(200);
});
*/

console.log('Funciones de overlay AJAX inicializadas');
</script>

<!-- Estilos adicionales para el spinner -->
<style>
.icon-spin {
	animation: icon-spin 1s infinite linear;
}

@keyframes icon-spin {
	0% { transform: rotate(0deg); }
	100% { transform: rotate(360deg); }
}

#btn-guardar-cambios:disabled,
#btn-guardar-cambios-2:disabled {
	opacity: 0.6;
	cursor: not-allowed;
}

.alert {
	position: relative;
	padding: 15px;
	margin-bottom: 20px;
	border: 1px solid transparent;
	border-radius: 4px;
}

.alert-success {
	color: #3c763d;
	background-color: #dff0d8;
	border-color: #d6e9c6;
}

.alert-danger {
	color: #a94442;
	background-color: #f2dede;
	border-color: #ebccd1;
}

.alert-warning {
	color: #8a6d3b;
	background-color: #fcf8e3;
	border-color: #faebcc;
}

.alert-info {
	color: #31708f;
	background-color: #d9edf7;
	border-color: #bce8f1;
}

.alert .close {
	position: absolute;
	top: 10px;
	right: 10px;
	padding: 0;
	cursor: pointer;
	background: transparent;
	border: 0;
	font-size: 21px;
	font-weight: bold;
	line-height: 1;
	color: #000;
	opacity: 0.2;
}

.alert .close:hover {
	opacity: 0.5;
}
</style>

</body>
</html>
