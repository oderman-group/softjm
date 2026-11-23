<?php
include("sesion.php");

$idPagina = 79;

include("includes/verificar-paginas.php");
require_once RUTA_PROYECTO.'/usuarios/includes/api-ofima-conexion.php';
$ofimaPedidosBloqueados = ofimaPedidosBloqueados($conexionBdPrincipal, (int) $idEmpresa);

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
	LEFT JOIN contactos cont ON cont.cont_id = c.cotiz_contacto
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

require_once RUTA_PROYECTO.'/usuarios/class/Cliente.php';
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

<?php include("includes/head.php"); ?>

<link href="css/chosen.css" rel="stylesheet">
<link href="css/clientes-listado.css" rel="stylesheet">
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
<script>
	$(function () {
		if (!$.fn.select2) {
			return;
		}
		function filtrarOpcion(params, data) {
			var termino = $.trim(params.term || '').toUpperCase();
			if (data.children) {
				var hijos = [];
				for (var i = 0; i < data.children.length; i++) {
					var hijo = filtrarOpcion(params, data.children[i]);
					if (hijo) {
						hijos.push(hijo);
					}
				}
				if (!hijos.length) {
					return null;
				}
				var grupo = $.extend(true, {}, data);
				grupo.children = hijos;
				return grupo;
			}
			if (!data.id) {
				return null;
			}
			if (termino === '' || (data.text || '').toUpperCase().indexOf(termino) > -1) {
				return data;
			}
			return null;
		}
		[
			{ nombre: 'cliente', placeholder: 'Escoja un cliente', buscar: 'Buscar cliente' },
			{ nombre: 'sucursal', placeholder: 'Escoja una sucursal', buscar: 'Buscar sucursal' },
			{ nombre: 'contacto', placeholder: 'Escoja un contacto', buscar: 'Buscar contacto' }
		].forEach(function (campo) {
			var select = $('select.cotiz-select[name="' + campo.nombre + '"]');
			if (!select.length || select.data('select2')) {
				return;
			}
			select.select2({
				width: '100%',
				placeholder: campo.placeholder,
				minimumResultsForSearch: 0,
				dropdownParent: $(document.body),
				matcher: filtrarOpcion,
				language: { noResults: function () { return 'Sin resultados'; } }
			});
			select.on('select2:open', function () {
				var buscador = document.querySelector('.select2-container--open .select2-search__field');
				if (buscador) {
					buscador.setAttribute('placeholder', campo.buscar);
					buscador.focus();
				}
			});
		});
	});
</script>
<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>
<?php include("includes/texto-editor.php");?>


<?php if($resultadoD['cotiz_vendida']!=1){?>

	<script type="text/javascript">
		function notificarCotizacion(mensaje, tipo) {
			tipo = tipo || 'info';
			var colores = { success: '#047857', error: '#b91c1c', warning: '#b45309', info: '#334155' };
			var color = colores[tipo] || colores.info;
			var duracion = tipo === 'success' ? 7000 : (tipo === 'error' ? 5500 : 4200);
			var nav = document.querySelector('.navbar .navbar-inner') || document.querySelector('.navbar-inner') || document.querySelector('.navbar');
			var offsetY = 96;
			if (nav) offsetY = Math.max(88, Math.round(nav.getBoundingClientRect().bottom + 12));
			var caja = document.getElementById('resp');
			if (caja) {
				caja.innerHTML = '';
				caja.style.display = 'none';
			}
			if (typeof Toastify === 'function') {
				Toastify({
					text: String(mensaje || ''),
					duration: duracion,
					gravity: 'top',
					position: 'right',
					close: true,
					stopOnFocus: true,
					backgroundColor: color,
					offset: { x: 16, y: offsetY },
					style: {
						background: color,
						color: '#fff',
						borderRadius: '10px',
						boxShadow: '0 12px 30px rgba(15, 23, 42, 0.25)',
						fontFamily: "'Plus Jakarta Sans', -apple-system, sans-serif",
						fontSize: '0.875rem',
						fontWeight: '600',
						maxWidth: '420px',
						padding: '12px 16px',
						zIndex: 10060
					}
				}).showToast();
			}
		}
		function numeroCampoCotiz(el) {
			if (!el) return 0;
			var n = parseFloat(String(el.value).trim().replace(/\s/g, '').replace(',', '.'));
			return isNaN(n) ? 0 : n;
		}
		function porcentajeDesdeMonto(monto, base) {
			var centavos = Math.round(monto * 100);
			var bruto = (monto / base) * 100;
			var decimales;
			var factor;
			var candidato;
			for (decimales = 2; decimales <= 8; decimales++) {
				factor = Math.pow(10, decimales);
				candidato = Math.round(bruto * factor) / factor;
				if (Math.round(base * candidato) === centavos) {
					return candidato.toFixed(decimales);
				}
			}
			return bruto.toFixed(8);
		}
		function mostrarMontoDescuento(origen) {
			var fila = origen && origen.closest ? origen.closest('tr') : null;
			if (!fila) return;
			var pct = fila.querySelector('input[title="czpp_descuento"]');
			var monto = fila.querySelector('input.dcto-valor');
			if (!pct || !monto) return;
			if (monto.getAttribute('data-valor-fijo') === '1') return;
			var base = numeroCampoCotiz(fila.querySelector('input[title="czpp_cantidad"]')) * numeroCampoCotiz(fila.querySelector('input[title="czpp_valor"]'));
			var valor = base * (numeroCampoCotiz(pct) / 100);
			monto.value = (isFinite(valor) ? valor : 0).toFixed(2);
		}
		function descuentoPorcentaje(origen) {
			var fila = origen && origen.closest ? origen.closest('tr') : null;
			var monto = fila ? fila.querySelector('input.dcto-valor') : null;
			if (monto) monto.removeAttribute('data-valor-fijo');
			mostrarMontoDescuento(origen);
		}
		function restaurarMontoDescuento(enviada) {
			var fila = enviada && enviada.closest ? enviada.closest('tr') : null;
			var monto = fila ? fila.querySelector('input.dcto-valor') : null;
			if (monto) monto.removeAttribute('data-valor-fijo');
			mostrarMontoDescuento(enviada);
			if (typeof recalculate === 'function') recalculate();
		}
		function descuentoValor(input) {
			var fila = input.closest('tr');
			var pctInput = fila ? fila.querySelector('input[title="czpp_descuento"]') : null;
			if (!pctInput) return;
			var base = numeroCampoCotiz(fila.querySelector('input[title="czpp_cantidad"]')) * numeroCampoCotiz(fila.querySelector('input[title="czpp_valor"]'));
			var monto = numeroCampoCotiz(input);
			if (base <= 0) {
				notificarCotizacion('Indique cantidad y precio antes de escribir el valor del descuento.', 'error');
				input.value = '0.00';
				return;
			}
			if (monto < 0) monto = 0;
			if (monto > base) monto = base;
			monto = Math.round(monto * 100) / 100;
			input.value = monto.toFixed(2);
			input.setAttribute('data-valor-fijo', '1');
			pctInput.setAttribute('data-valor-actual', pctInput.value);
			pctInput.value = porcentajeDesdeMonto(monto, base);
			if (fila.classList.contains('combo')) combos(pctInput);
			else productos(pctInput);
		}
		function productos(enviada){
			var tipoCliente   = enviada.alt;
			var campo         = enviada.getAttribute('data-campo') || enviada.title;
			var producto      = enviada.name;
			var proceso       = 2;
			var valor         = enviada.value;
			var valorAnterior = enviada.getAttribute('data-valor-actual');
			mostrarMontoDescuento(enviada);
			
			// Mostrar overlay con mensaje específico
			showAjaxOverlay('Actualizando producto...');
			
			$('#resp').empty().hide().html("Esperando...").show(1);
			var filaProd = enviada.closest ? enviada.closest('tr') : null;
			var montoProd = filaProd ? filaProd.querySelector('input.dcto-valor') : null;
			var monto = montoProd ? montoProd.value : '';
			if (montoProd && montoProd.getAttribute('data-valor-fijo') !== '1') {
				monto = montoProd.value;
			}
			datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(encodeURIComponent(valor))+"&campo="+(encodeURIComponent(campo))+"&tipoCliente="+(encodeURIComponent(tipoCliente || ''))+"&monto="+(encodeURIComponent(monto));
			$.ajax({
				type: "POST",
				url: "ajax/ajax-productos.php",
				data: datos,
				success: function(data) {
					var response = JSON.parse(data);
					if(response.success) {
						$('#resp').empty().hide().html(response.message).show(1);
						enviada.setAttribute('data-valor-actual', enviada.value);
						mostrarMontoDescuento(enviada);
						hideAjaxOverlay();
					} else {
						hideAjaxOverlay();
						notificarCotizacion(response.message, 'error');
						enviada.value = valorAnterior;
						restaurarMontoDescuento(enviada);
					}
				},
				error: function() {
					hideAjaxOverlay();
					notificarCotizacion('Error al actualizar el producto', 'error');
					enviada.value = valorAnterior;
					restaurarMontoDescuento(enviada);
				}
			});
		}


		function combos(enviada){
			var campo = enviada.getAttribute('data-campo') || enviada.title;
			var producto = enviada.name;
			var proceso = 11;
			var valor = enviada.value;
			var valorAnterior = enviada.getAttribute('data-valor-actual');
			var fila = enviada.closest ? enviada.closest('tr') : null;
			var montoEl = fila ? fila.querySelector('input.dcto-valor') : null;
			var monto = montoEl ? montoEl.value : '';
			mostrarMontoDescuento(enviada);
			if (montoEl && montoEl.getAttribute('data-valor-fijo') !== '1') {
				monto = montoEl.value;
			}
			
			showAjaxOverlay('Actualizando combo...');
			
			$('#resp').empty().hide().html("Esperando...").show(1);
				datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(encodeURIComponent(valor))+"&campo="+(encodeURIComponent(campo))+"&monto="+(encodeURIComponent(monto));
					$.ajax({
						type: "POST",
						url: "ajax/ajax-productos.php",
						data: datos,
						success: function(data){
							hideAjaxOverlay();
							var texto = String(data || '');
							var aviso = texto.match(/alert\("([\s\S]*?)"\)/);
							if (aviso) {
								notificarCotizacion(aviso[1], 'error');
								if (valorAnterior !== null) enviada.value = valorAnterior;
								restaurarMontoDescuento(enviada);
								return;
							}
							enviada.setAttribute('data-valor-actual', enviada.value);
							mostrarMontoDescuento(enviada);
							if (texto.replace(/\s/g, '') !== '') {
								$('#resp').empty().hide().html(texto).show(1);
							}
						},
						error: function() {
							hideAjaxOverlay();
							notificarCotizacion('Error al actualizar el combo', 'error');
							if (valorAnterior !== null) enviada.value = valorAnterior;
							restaurarMontoDescuento(enviada);
						}
					});
		}	

		function servicios(enviada){
			var campo = enviada.getAttribute('data-campo') || enviada.title;
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
</head>
<body class="clientes-page">

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

<style>
	.clientes-page .cotiz-editar-tabs { border: 0; margin: 0 0 1rem; display: flex; flex-wrap: wrap; gap: 0.4rem; }
	.clientes-page .cotiz-editar-tabs > li { float: none; margin: 0; }
	.clientes-page .cotiz-editar-tabs > li > a {
		border: 1px solid #e2e8f0;
		border-radius: 8px;
		background: #fff;
		color: #334155;
		font-weight: 600;
		font-size: 0.8125rem;
		padding: 0.5rem 0.85rem;
		margin: 0;
	}
	.clientes-page .cotiz-editar-tabs > li > a:hover { background: #f8fafc; color: #0f172a; }
	.clientes-page .cotiz-editar-tabs > li.active > a,
	.clientes-page .cotiz-editar-tabs > li.active > a:hover,
	.clientes-page .cotiz-editar-tabs > li.active > a:focus {
		background: #1d4ed8;
		border-color: #1d4ed8;
		color: #fff;
	}
	.clientes-page .cotiz-editar-tabs > li.active > a[href="#itemsCotizados"],
	.clientes-page .cotiz-editar-tabs > li.active > a[href="#itemsCotizados"]:hover,
	.clientes-page .cotiz-editar-tabs > li.active > a[href="#itemsCotizados"]:focus {
		background: linear-gradient(135deg, #059669 0%, #047857 100%);
		border-color: #047857;
		color: #fff;
	}
	.clientes-page .cotiz-editar-tabs > li.active > a[href="#enviarCotizacion"],
	.clientes-page .cotiz-editar-tabs > li.active > a[href="#enviarCotizacion"]:hover,
	.clientes-page .cotiz-editar-tabs > li.active > a[href="#enviarCotizacion"]:focus {
		background: #b91c1c;
		border-color: #991b1b;
		color: #fff;
	}
	.clientes-page .content-widgets,
	.clientes-page .content-widgets.gray,
	.clientes-page .content-widgets.light-gray {
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 14px;
		box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
		margin-bottom: 1rem;
		overflow: visible;
	}
	.clientes-page .widget-head {
		background: #0f172a;
		border: 0;
		border-radius: 13px 13px 0 0;
		color: #fff;
		padding: 0.9rem 1.15rem;
	}
	.clientes-page .widget-head.bondi-blue {
		background: #1d4ed8;
	}
	.clientes-page .widget-head.orange {
		background: #b91c1c;
	}
	.clientes-page .widget-head.green {
		background: linear-gradient(135deg, #059669 0%, #047857 100%);
		border: 0;
		border-radius: 13px 13px 0 0;
		color: #fff;
		padding: 0.9rem 1.15rem;
	}
	.clientes-page .widget-head h3 { color: #fff; font-size: 1rem; font-weight: 700; margin: 0; }
	.clientes-page .widget-head .cotiz-cliente { margin: 0.2rem 0 0; color: rgba(255, 255, 255, 0.92); font-size: 0.875rem; font-weight: 600; }
	.clientes-page .widget-container { background: #fff; border-radius: 0 0 13px 13px; padding: 1rem 1.15rem 1.15rem; }
	#productos.is-cargando #data-table { display: none; }
	#productos .items-cargando { display: none; }
	#productos.is-cargando .items-cargando {
		align-items: center;
		color: #047857;
		display: flex;
		flex-direction: column;
		font-weight: 700;
		gap: 10px;
		justify-content: center;
		min-height: 180px;
	}
	#productos .items-cargando p { margin: 0; }
	#productos .items-cargando .spinner-modern {
		border-right-color: #047857;
		border-top-color: #059669;
		height: 42px;
		margin: 0;
		width: 42px;
	}
	.clientes-page .form-horizontal .control-label { color: #334155; font-weight: 600; font-size: 0.8125rem; }
	.clientes-page .form-horizontal input[type="text"],
	.clientes-page .form-horizontal input[type="number"],
	.clientes-page .form-horizontal input[type="email"],
	.clientes-page .form-horizontal input[type="date"],
	.clientes-page .form-horizontal select,
	.clientes-page .form-horizontal textarea,
	.clientes-page #itemsCotizados input[type="text"],
	.clientes-page #itemsCotizados input[type="number"],
	.clientes-page #itemsCotizados textarea {
		border: 1px solid #cbd5e1;
		border-radius: 8px;
		box-shadow: none;
		color: #0f172a;
	}
	.clientes-page .btn { border-radius: 8px; font-weight: 600; }
	.clientes-page .btn-pdf-2 { background: #ea580c; border-color: #c2410c; color: #fff; }
	.clientes-page .btn-pdf-2:hover { background: #c2410c; color: #fff; }
	.clientes-page .miga-tema ul.breadcrumb > li.miga-tema-acciones { display: flex; flex-wrap: wrap; align-items: center; gap: 0.45rem; margin-left: auto; }
	.clientes-page .miga-tema ul.breadcrumb > li.miga-tema-acciones a.btn,
	.clientes-page .miga-tema ul.breadcrumb > li.miga-tema-acciones a.btn:hover,
	.clientes-page .miga-tema ul.breadcrumb > li.miga-tema-acciones a.btn i { color: #fff; }
	.clientes-page .miga-tema-acciones a.btn-success { background: #b91c1c; border-color: #991b1b; color: #fff; }
	.clientes-page .miga-tema-acciones a.btn-success:hover { background: #991b1b; color: #fff; }
	.clientes-page .miga-tema-acciones a.btn-info { background: #047857; border-color: #065f46; color: #fff; }
	.clientes-page .miga-tema-acciones a.btn-info:hover { background: #065f46; color: #fff; }
	.clientes-page .miga-tema-acciones a.btn-warning { background: #1d4ed8; border-color: #1e40af; color: #fff; }
	.clientes-page .miga-tema-acciones a.btn-warning:hover { background: #1e40af; color: #fff; }
	.clientes-page .alert { border-radius: 10px; }
	.clientes-page ul.breadcrumb {
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 10px;
		padding: 0.65rem 1rem;
	}
	.clientes-page ul.breadcrumb.cotiz-flujo { background: #fffbeb; border-color: #fde68a; }
	.clientes-page .cotiz-estado {
		margin: 0 0 0.75rem;
		padding: 0.75rem 1rem;
		border-radius: 10px;
		font-weight: 600;
		font-size: 0.875rem;
	}
	.clientes-page .cotiz-estado.is-ok { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
	.clientes-page .cotiz-estado.is-bloqueo { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
	#enviarCotizacion .mceLayout,
	#enviarCotizacion .mceIframeContainer iframe {
		height: 280px !important;
	}
	.clientes-page #itemsCotizados .cotiz-dcto { display: inline-flex; flex-direction: column; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden; line-height: 1.2; background: #fff; }
	.clientes-page #itemsCotizados .cotiz-dcto .precio-par-fila { display: flex; align-items: center; gap: 0.35rem; margin: 0; padding: 0 0.35rem; border-bottom: 1px solid #cbd5e1; font-weight: 600; color: #0f172a; }
	.clientes-page #itemsCotizados .cotiz-dcto .precio-par-fila:last-child { border-bottom: 0; }
	.clientes-page #itemsCotizados .cotiz-dcto .precio-par-fila span { flex: 0 0 auto; min-width: 2.4rem; font-size: 0.75rem; }
	.clientes-page #itemsCotizados .cotiz-dcto input[type="text"] { width: 78px !important; height: 22px; margin: 0; padding: 0; border: 0; background: #fff !important; text-align: right; box-shadow: none; }
	.clientes-page #itemsCotizados .table { font-size: 0.75rem; border-color: #e2e8f0; }
	.clientes-page #itemsCotizados .table thead th {
		background: #f8fafc;
		color: #475569;
		font-size: 0.6875rem;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.03em;
		border-color: #e2e8f0;
	}
	.clientes-page #itemsCotizados .table td,
	.clientes-page #itemsCotizados .table th { padding: 0.4rem 0.45rem; vertical-align: top; }
	.clientes-page #itemsCotizados tfoot td { background: #f8fafc; font-size: 0.8125rem; }
	.clientes-page .tab-content { overflow: visible; background: transparent; border: 0; padding: 0; }
	.clientes-page fieldset.default {
		box-sizing: border-box;
		width: 100%;
		max-width: 100%;
		min-width: 0;
		margin: 0 0 1rem;
		padding: 0.75rem 1rem 0.25rem;
		border: 1px solid #e2e8f0;
		border-radius: 12px;
		background: #f8fafc;
	}
	.clientes-page fieldset.default .control-group,
	.clientes-page fieldset.default .controls,
	.clientes-page fieldset.default .cotiz-campo-accion {
		min-width: 0;
		max-width: 100%;
	}
	.clientes-page fieldset.default legend {
		width: auto;
		margin: 0 0 0.5rem;
		padding: 0 0.35rem;
		border: 0;
		font-size: 0.75rem;
		font-weight: 700;
		letter-spacing: 0.04em;
		text-transform: uppercase;
		color: #047857;
		line-height: 1.2;
	}
	.clientes-page fieldset.default .control-group { margin-bottom: 0.65rem; }
	.clientes-page .cotiz-campo-accion { display: flex; align-items: center; gap: 0.5rem; }
	.clientes-page .cotiz-campo-accion .cotiz-select {
		flex: 1 1 0;
		width: 0;
		min-width: 0;
		max-width: 100%;
		height: 38px;
		margin: 0;
		padding: 0 0.7rem;
		border: 1px solid #cbd5e1;
		border-radius: 8px;
		background: #fff;
		color: #0f172a;
		font-size: 0.875rem;
		box-shadow: none;
	}
	.clientes-page .cotiz-campo-accion .btn {
		flex: 0 0 auto;
		height: 38px;
		margin: 0;
		padding: 0 0.85rem;
		line-height: 36px;
		white-space: nowrap;
		border-radius: 8px;
	}
	.clientes-page .cotiz-campo-accion .select2-container { flex: 1 1 0; width: 0 !important; min-width: 0; max-width: 100%; }
	.clientes-page .cotiz-campo-accion .select2-container .select2-selection--single {
		height: 38px;
		border: 1px solid #cbd5e1;
		border-radius: 8px;
		background: #fff;
	}
	.clientes-page .cotiz-campo-accion .select2-container .select2-selection__rendered {
		line-height: 36px;
		padding-left: 0.7rem;
		color: #0f172a;
		font-size: 0.875rem;
	}
	.clientes-page .cotiz-campo-accion .select2-container .select2-selection__placeholder { color: #94a3b8; }
	.clientes-page .cotiz-campo-accion .select2-container--focus .select2-selection--single,
	.clientes-page .cotiz-campo-accion .select2-container--open .select2-selection--single { background: #fff; color: #0f172a; }
	.clientes-page .cotiz-campo-accion .select2-container .select2-selection__arrow { height: 36px; }
	.clientes-page .cotiz-campo-accion .select2-container--disabled .select2-selection--single { background: #f1f5f9; }
	.select2-container--open { z-index: 10060; }
	.select2-container--open .select2-dropdown {
		border: 1px solid #cbd5e1;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 8px 18px rgba(15, 23, 42, 0.12);
	}
	.select2-container--default .select2-search--dropdown { padding: 0.35rem 0.45rem; background: #f8fafc; }
	.select2-container--default .select2-search--dropdown .select2-search__field {
		height: 28px;
		margin: 0;
		padding: 0 0.55rem;
		border: 1px solid #cbd5e1;
		border-radius: 6px;
		box-shadow: none;
		font-size: 0.8125rem;
		line-height: 26px;
	}
	.select2-container--default .select2-results__option { padding: 0.28rem 0.6rem; font-size: 0.8125rem; }
	.select2-container--default .select2-results__group {
		padding: 0.3rem 0.6rem;
		font-size: 0.6875rem;
		font-weight: 700;
		letter-spacing: 0.03em;
		text-transform: uppercase;
		color: #64748b;
	}
	@media (max-width: 768px) {
		.clientes-page .cotiz-campo-accion { flex-wrap: wrap; align-items: stretch; }
		.clientes-page .cotiz-campo-accion .cotiz-select,
		.clientes-page .cotiz-campo-accion .select2-container { flex: 1 1 100%; width: 100% !important; }
		.clientes-page .cotiz-campo-accion .btn { flex: 1 1 auto; }
	}
	#product-select + .select2-container .select2-selection__choice:has(.combo-prod-off),
	.select2-results__option .combo-prod-off {
		background-color: #fee2e2 !important;
		border-color: #fca5a5 !important;
		color: #991b1b !important;
		font-weight: 700;
	}
	#product-select + .select2-container .select2-selection__choice:has(.combo-prod-ok) {
		background-color: #d1fae5 !important;
		border-color: #6ee7b7 !important;
		color: #065f46 !important;
		font-weight: 700;
	}
	.select2-results__option .combo-prod-ok {
		color: #065f46 !important;
		font-weight: 700;
	}
</style>

<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
    
	<div class="main-wrapper">
		<div class="container-fluid clientes-page-inner">
			<?php
			$versionActualCotizacion = Cotizacion::obtenerVersionCotizacion($resultadoD['cotiz_version']);
			$versionMiga = ((int) $resultadoD['cotiz_version'] > 0) ? ' (V ' . (int) $resultadoD['cotiz_version'] . ')' : '';
			$migaTema = [
				['texto' => 'Cotizaciones', 'url' => 'cotizaciones.php'],
				['texto' => 'Cotización #' . (int) $resultadoD['cotiz_id'] . $versionMiga],
			];
			ob_start();
			if (Modulos::validarRol([78], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
				<a href="cotizaciones-agregar.php" class="btn btn-success"><i class="icon-plus"></i> Agregar nuevo</a>
			<?php }
			if (Modulos::validarRol([50], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
				<a href="reportes/formato-cotizacion-1_pdf.php?id=<?= (int) $_GET['id']; ?>" class="btn btn-info" target="_blank"><i class="icon-print"></i> PDF 1</a>
				<a href="reportes/formato-cotizacion-3_pdf.php?id=<?= (int) $_GET['id']; ?>" class="btn btn-pdf-2" target="_blank"><i class="icon-print"></i> PDF 2</a>
			<?php }
			if (
				empty($ofimaPedidosBloqueados) &&
				$resultadoD['cotiz_vendida'] != 1 &&
				Modulos::validarRol([263], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) &&
				!empty($ticketAsociado) &&
				$resultadoD['cotiz_es_precotizacion'] != 1
			) { ?>
				<a href="bd_create/cotizaciones-generar-pedido.php?id=<?= (int) $resultadoD['cotiz_id']; ?>" class="btn btn-warning" onClick="if(!confirm('Desea generar pedido de esta cotización?')){return false;}"><i class="icon-money"></i> Generar pedido</a>
			<?php }
			$migaTemaAcciones = ob_get_clean();
			include 'includes/miga-tema.php';
			?>

            <?php include("includes/notificaciones.php");?>

			<?php
			$camposCotizacionDisabled = '';
			$faltaContactoCotizacion = empty($resultadoD['cotiz_contacto']);

			if ($resultadoD['cotiz_vendida'] == Cotizacion::COTIZACION_VENDIDA) {
				$camposCotizacionDisabled = 'disabled';

				//Pedido
				$predicado = [
					'pedid_cotizacion' => $resultadoD['cotiz_id'],
					'pedid_id_empresa' => $idEmpresa
				];

				$pedidoAsociado = Pedido::Select($predicado);
				$pedidoAsociadoDatos = mysqli_fetch_array($pedidoAsociado, MYSQLI_BOTH) ?: [];
				$pedidoIdAsociado = (string) ($pedidoAsociadoDatos[Pedido::$primaryKey] ?? '');

				//Remisión
				$remisionAsociadaDatos = [];
				if ($pedidoIdAsociado !== '') {
					$predicado = [
						'remi_pedido' => $pedidoIdAsociado,
						'remi_id_empresa' => $idEmpresa
					];

					$remisionAsociada = Remision::Select($predicado);
					$remisionAsociadaDatos = mysqli_fetch_array($remisionAsociada, MYSQLI_BOTH) ?: [];
				}
				$remisionIdAsociada = (string) ($remisionAsociadaDatos[Remision::$primaryKey] ?? '');

				$linkRemision = '#';
				$breadCrumbRemision = 'Remisión Pendiente';

				if ($remisionIdAsociada !== '') {
					$linkRemision = 'remisionbdg.php?busqueda='.$remisionIdAsociada;
					$breadCrumbRemision = 'Remisión Nro. '.$remisionIdAsociada;
				}

				//Factura
				$facturaAsociadaDatos = [];
				if ($remisionIdAsociada !== '') {
					$predicado = [
						'factura_remision' => $remisionIdAsociada,
						'factura_id_empresa' => $idEmpresa
					];

					$facturaAsociada = Factura::Select($predicado);
					$facturaAsociadaDatos = mysqli_fetch_array($facturaAsociada, MYSQLI_BOTH) ?: [];
				}
				$facturaIdAsociada = (string) ($facturaAsociadaDatos[Factura::$primaryKey] ?? '');

				$linkFactura = '#';
				$breadCrumbFactura = 'Factura Pendiente';

				if ($facturaIdAsociada !== '') {
					$linkFactura = 'facturas.php?busqueda='.$facturaIdAsociada;
					$breadCrumbFactura = 'Factura Nro. '.$facturaIdAsociada;
				}
			?>
				<p class="cotiz-estado is-ok">
					Esta cotización ya generó pedido en la siguiente fecha: <?=$resultadoD['cotiz_fecha_vendida'];?>.
				</p>

				<ul class="breadcrumb cotiz-flujo">
					<?php if (!empty($ticketAsociado)) {?>
						<li><a href="clientes-tikets-editar.php?id=<?=$ticketAsociado['tik_id'];?>">Ticket Nro. <?=$ticketAsociado['tik_id'];?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<?php }?>
					<li><a href="#">Cotización Nro. <?=$resultadoD['cotiz_id'];?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<li><a href="pedidos.php?busqueda=<?= htmlspecialchars($pedidoIdAsociado, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">Pedido Nro.<?= htmlspecialchars($pedidoIdAsociado !== '' ? $pedidoIdAsociado : 'pendiente', ENT_QUOTES, 'UTF-8'); ?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<li><a href="<?=$linkRemision;?>" target="_blank"><?=$breadCrumbRemision;?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<li><a href="<?=$linkFactura;?>" target="_blank"><?=$breadCrumbFactura;?></a><span class="divider"></li>
				</ul>

				<?php if ($faltaContactoCotizacion) { ?>
				<p class="cotiz-estado is-bloqueo">Falta el contacto. Puede elegir la sucursal y el contacto, y guardar solo esos datos.</p>
				<?php } else { ?>
				<p class="cotiz-estado is-bloqueo">No es posible hacer más cambios en esta cotización.</p>
				<?php } ?>
			<?php
			} else if(!empty($ticketAsociado)) {
			?>
				<ul class="breadcrumb cotiz-flujo">
					<li><a href="clientes-tikets-editar.php?id=<?=$ticketAsociado['tik_id'];?>">Ticket Nro. <?=$ticketAsociado['tik_id'];?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<li><a href="#">Cotización Nro. <?=$resultadoD['cotiz_id'];?></a><span class="divider"></span></li>
				</ul>
			<?php
			}

			if (!empty($ofimaPedidosBloqueados) && $resultadoD['cotiz_vendida'] != 1 && $resultadoD['cotiz_es_precotizacion'] != 1) { ?>
				<div class="cotiz-estado is-bloqueo"><?= htmlspecialchars(ofimaMensajePedidosBloqueados(), ENT_QUOTES, 'UTF-8'); ?></div>
			<?php } else if ($resultadoD['cotiz_vendida'] != 1 && empty($ticketAsociado) && $resultadoD['cotiz_es_precotizacion'] != 1) { ?>
				<div class="cotiz-estado is-bloqueo">No es posible generar pedido en una cotización sin ticket comercial asociado.</div>
			<?php } else if ($resultadoD['cotiz_es_precotizacion'] == 1 && $resultadoD['cotiz_vendida'] != 1) { ?>
				<div class="cotiz-estado is-bloqueo">No es posible generar pedido con una pre-cotización.</div>
			<?php }

			$versionActualCotizacion = $versionActualCotizacion ?? Cotizacion::obtenerVersionCotizacion($resultadoD['cotiz_version']);
			?>

		<ul class="nav nav-tabs cotiz-editar-tabs" id="myTab1">
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
									<p class="cotiz-cliente"><?= htmlspecialchars((string) $resultadoD['cli_nombre'], ENT_QUOTES, 'UTF-8'); ?></p>
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
										if($resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA || !empty($faltaContactoCotizacion)){
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
												<div class="cotiz-campo-accion">
												<select class="cotiz-select" name="cliente" required onChange="clientes(this)" <?=$camposCotizacionDisabled;?>>
													<option value="">Escoja un cliente</option>
													<?php
													$sqlClientesCotiz = "SELECT cli.cli_id, cli.cli_nombre, cli.cli_categoria, 
														CASE 
															WHEN cli.cli_categoria = '".CLI_CATEGORIA_DEALER."' THEN '(DEALER)'
															ELSE ''
														END AS categoria
														FROM clientes cli
														WHERE cli.cli_id_empresa='".$idEmpresa."'
														AND (cli.cli_integrado_ofima = 1 OR cli.cli_id='".(int) $cliente."')";
													$sqlClientesCotiz = Cliente::appendFiltrosVisibilidadAnalyticsWhere(
														$sqlClientesCotiz,
														(int) ($_SESSION['id'] ?? 0),
														!Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
														(int) $cliente
													);
													$sqlClientesCotiz .= " ORDER BY cli.cli_categoria, cli.cli_nombre";
													$conOp = $conexionBdPrincipal->query($sqlClientesCotiz);

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
												<?php if (Modulos::validarRol([11], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && $resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA) {?>
													<a href="clientes-editar.php?id=<?=$cliente;?>" class="btn btn-info" target="_blank">Editar cliente</a>
												<?php } ?>
												<button type="button" class="btn btn-info" id="btnBuscarClientes">Buscar clientes</button>
												</div>
											</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Sucursal</label>
										<div class="controls">
											<div class="cotiz-campo-accion">
											<select class="cotiz-select" name="sucursal" required <?= ($camposCotizacionDisabled !== '' && empty($faltaContactoCotizacion)) ? $camposCotizacionDisabled : ''; ?>>
												<option value="">Escoja una sucursal</option>
												<?php
												foreach($sucursales as $sucursal){
												?>
													<option value="<?=$sucursal['sucu_id'];?>" <?php if($resultadoD['cotiz_sucursal']==$sucursal['sucu_id'] || count($sucursales) == 1){echo "selected";} ?>><?=$sucursal['sucu_nombre'];?></option>
												<?php 
												}
												?>
											</select>
											<?php if (Modulos::validarRol([83], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
											<a href="clientes-sucursales.php?cte=<?=$cliente;?>" class="btn btn-info" target="_blank">Ver sucursales</a>
											<?php } ?>
											</div>
										</div>
								</div>
										
									<div class="control-group">
										<label class="control-label">Contacto</label>
										<div class="controls">
											<div class="cotiz-campo-accion">
											<select class="cotiz-select" name="contacto" required <?= ($camposCotizacionDisabled !== '' && empty($faltaContactoCotizacion)) ? $camposCotizacionDisabled : ''; ?>>
												<option value="">Escoja un contacto</option>
												<?php
												foreach($contactos as $contacto){
												?>
													<option value="<?=$contacto['cont_id'];?>" <?php if($resultadoD['cotiz_contacto']==$contacto['cont_id'] || count($contactos) == 1){echo "selected";}?>><?=strtoupper($contacto['cont_nombre'])." (".$contacto['cont_email'].")";?></option>
												<?php
												}
												?>
											</select>
											<?php if (Modulos::validarRol([44], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
											<a href="clientes-contactos.php?cte=<?=$cliente;?>" class="btn btn-info" target="_blank">Ver contactos</a>
											<?php } ?>
											</div>
										</div>
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
														SELECT p.prod_id, p.prod_nombre, p.prod_habilitado, cp.czpp_cantidad
														FROM cotizacion_productos cp
														INNER JOIN productos p ON p.prod_id = cp.czpp_producto AND p.prod_id_empresa = '".$idEmpresa."'
														WHERE cp.czpp_cotizacion = '".$_GET["id"]."' AND cp.czpp_tipo = ".CZPP_TIPO_COTZ."
														ORDER BY p.prod_nombre
													");

													while ($resProducto = mysqli_fetch_array($consultaProductos, MYSQLI_BOTH)) {
														$productoHabilitado = ((int) ($resProducto['prod_habilitado'] ?? 0) === 1) ? 1 : 0;
													?>
														<option selected value="<?= $resProducto['prod_id']; ?>" data-habilitado="<?= $productoHabilitado; ?>"><?= $resProducto['prod_id'] . ". " . strtoupper($resProducto['prod_nombre']) . " - [HAY " . $resProducto['czpp_cantidad'] . "]"; ?></option>
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
											SELECT tik_id, tik_asunto_principal, tik_fecha_creacion
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
															<option value="<?=$resOp['tik_id'];?>"><?="Ticket # ".$resOp['tik_id']." - ".strtoupper($resOp['tik_asunto_principal'])." (".$resOp['tik_fecha_creacion'].")";?></option>
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
										if($resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA || !empty($faltaContactoCotizacion)){
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
					
					<div class="content-widgets light-gray is-cargando" id="productos">
						<div class="widget-head green">
							<h3>PRODUCTOS</h3>
						</div>
						<div class="widget-container">
							<div class="items-cargando" id="itemsCargando">
								<div class="spinner-modern"></div>
								<p>Cargando productos...</p>
							</div>
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
										<span class="valor-numerico"><?=!empty($totalDescuento) ? number_format($totalDescuento,0,",",".") : 0;?>
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
	<?php include("includes/drawer-buscar-clientes.php");?>
	<?php include("includes/drawer-crear-cliente.php");?>
	<?php include("includes/pie.php");?>
	<script src="js/Cotizaciones.js?v=20261120a"></script>
</div>

<script>
// ========================================
// SISTEMA DE DETECCIÓN DE ERRORES Y TIMEOUT DE SEGURIDAD
// ========================================

/**
 * Función para ocultar el overlay y mostrar un mensaje de error si es necesario
 * Hacerla global para que pueda ser llamada desde los botones
 */
window.ocultarOverlayConSeguridad = function(mostrarError) {
	const overlay = document.getElementById('loading-overlay');
	if (!overlay) return;
	
	if (mostrarError) {
		// Cambiar el contenido del overlay para mostrar error
		const content = overlay.querySelector('.loading-content-modern');
		if (content) {
			content.innerHTML = `
				<div style="text-align: center;">
					<i class="icon-exclamation-sign" style="font-size: 48px; color: #dc3545; margin-bottom: 15px;"></i>
					<h3 style="color: #dc3545; margin: 0 0 10px 0;">Error al cargar la página</h3>
					<p style="color: #666; margin: 0 0 20px 0;">Ha ocurrido un error durante la carga. Por favor, intente recargar la página.</p>
					<button onclick="location.reload()" class="btn btn-primary" style="padding: 10px 20px; font-size: 14px;">
						<i class="icon-refresh"></i> Recargar página
					</button>
				</div>
			`;
			// Cambiar fondo a rojo claro
			overlay.style.background = 'linear-gradient(135deg, rgba(220, 53, 69, 0.95) 0%, rgba(200, 35, 51, 0.95) 100%)';
		}
	} else {
		// Ocultar normalmente
		overlay.classList.add('hidden');
		setTimeout(function() {
			overlay.style.display = 'none';
		}, 500);
	}
};

/**
 * Función para detectar errores de PHP en el DOM
 */
function detectarErroresPHP() {
	if (!document.body) return false;
	
	const bodyText = document.body.innerHTML;
	const bodyTextLower = bodyText.toLowerCase();
	
	// Buscar patrones específicos de errores de PHP
	// Estos son los formatos comunes de errores de PHP
	const patronesError = [
		/<b>Fatal error<\/b>[\s\S]*?<br\s*\/?>/i,
		/<b>Warning<\/b>[\s\S]*?<br\s*\/?>/i,
		/<b>Parse error<\/b>[\s\S]*?<br\s*\/?>/i,
		/Maximum execution time of \d+ seconds exceeded/i,
		/Allowed memory size of \d+ bytes exhausted/i,
		/Fatal error:[\s\S]{0,200}in <b>[\s\S]{0,200}<\/b>/i,
		/Warning:[\s\S]{0,200}in <b>[\s\S]{0,200}<\/b>/i
	];
	
	// Verificar si hay errores visibles en el DOM
	for (let i = 0; i < patronesError.length; i++) {
		if (patronesError[i].test(bodyText)) {
			// Verificar que el error esté visible (no oculto por CSS)
			const mainContent = document.querySelector('.main-wrapper, .layout, .container-fluid');
			
			// Si no hay contenido principal cargado, definitivamente hay un error
			if (!mainContent) {
				return true;
			}
			
			// Si el error aparece antes del contenido principal, es un error real
			const errorMatch = bodyText.match(patronesError[i]);
			if (errorMatch) {
				const errorIndex = bodyText.indexOf(errorMatch[0]);
				const mainIndex = bodyText.indexOf(mainContent.innerHTML);
				
				// Si el error aparece antes del contenido principal, es un error real
				if (errorIndex < mainIndex || mainIndex === -1) {
					return true;
				}
			}
		}
	}
	
	// Verificar también si hay texto de error visible en el body (sin etiquetas HTML de error)
	// Esto captura errores que pueden aparecer como texto plano
	const textoVisible = document.body.innerText || document.body.textContent || '';
	const textoVisibleLower = textoVisible.toLowerCase();
	
	const indicadoresTexto = [
		'fatal error',
		'maximum execution time',
		'allowed memory size',
		'parse error'
	];
	
	for (let i = 0; i < indicadoresTexto.length; i++) {
		if (textoVisibleLower.indexOf(indicadoresTexto[i]) !== -1) {
			// Verificar que no sea parte del contenido normal de la página
			// Si aparece en los primeros 5000 caracteres, probablemente es un error
			if (textoVisibleLower.indexOf(indicadoresTexto[i]) < 5000) {
				const mainContent = document.querySelector('.main-wrapper, .layout, .container-fluid');
				// Si no hay contenido principal, es un error
				if (!mainContent) {
					return true;
				}
			}
		}
	}
	
	return false;
}

// Timeout de seguridad máximo (15 segundos)
const TIMEOUT_MAXIMO = 15000;
// Variable global para controlar si el overlay ya fue ocultado
window.overlayOcultado = false;

// Manejador de errores global de JavaScript
window.addEventListener('error', function(e) {
	console.error('Error de JavaScript detectado:', e.error);
	// Si hay un error de JavaScript y el overlay sigue visible, mostrar mensaje de error
	if (!window.overlayOcultado) {
		setTimeout(function() {
			if (!window.overlayOcultado) {
				window.ocultarOverlayConSeguridad(true);
				window.overlayOcultado = true;
			}
		}, 1000);
	}
}, true);

// Timeout de seguridad: si después de X segundos el overlay sigue visible, ocultarlo
setTimeout(function() {
	if (!window.overlayOcultado) {
		console.warn('Timeout de seguridad: ocultando overlay después de ' + (TIMEOUT_MAXIMO/1000) + ' segundos');
		// Verificar si hay errores antes de ocultar
		if (detectarErroresPHP()) {
			window.ocultarOverlayConSeguridad(true);
		} else {
			// Si no hay errores detectados pero el overlay sigue visible, puede ser un problema de carga lenta
			// Mostrar un mensaje informativo
			const overlay = document.getElementById('loading-overlay');
			if (overlay) {
				const content = overlay.querySelector('.loading-content-modern');
				if (content) {
					content.innerHTML = `
						<div style="text-align: center;">
							<i class="icon-time" style="font-size: 48px; color: #ffc107; margin-bottom: 15px;"></i>
							<h3 style="color: #856404; margin: 0 0 10px 0;">Carga lenta detectada</h3>
							<p style="color: #666; margin: 0 0 20px 0;">La página está tardando más de lo esperado. Puede recargar o esperar un momento más.</p>
							<button onclick="location.reload()" class="btn btn-warning" style="padding: 10px 20px; font-size: 14px; margin-right: 10px;">
								<i class="icon-refresh"></i> Recargar
							</button>
							<button onclick="window.ocultarOverlayConSeguridad(false); window.overlayOcultado = true;" class="btn btn-default" style="padding: 10px 20px; font-size: 14px;">
								Continuar esperando
							</button>
						</div>
					`;
				}
			}
		}
		window.overlayOcultado = true;
	}
}, TIMEOUT_MAXIMO);

// Ocultar overlay cuando el DOM esté listo (antes de que se carguen todos los recursos)
document.addEventListener('DOMContentLoaded', function() {
	// Verificar errores inmediatamente cuando el DOM esté listo
	if (detectarErroresPHP()) {
		console.error('Error detectado en DOMContentLoaded');
		if (!window.overlayOcultado) {
			window.ocultarOverlayConSeguridad(true);
			window.overlayOcultado = true;
		}
		return;
	}
	
	// Si el DOM está listo y no hay errores, esperar un poco más antes de ocultar
	// para dar tiempo a que se carguen los recursos
	setTimeout(function() {
		if (!window.overlayOcultado && !detectarErroresPHP()) {
			window.ocultarOverlayConSeguridad(false);
			window.overlayOcultado = true;
		}
	}, 500);
});

// Ocultar overlay cuando la página esté completamente cargada
window.addEventListener('load', function() {
	// Verificar si hay errores antes de ocultar
	if (detectarErroresPHP()) {
		console.error('Error detectado en window.load');
		if (!window.overlayOcultado) {
			window.ocultarOverlayConSeguridad(true);
			window.overlayOcultado = true;
		}
		return;
	}
	
	// Esperar un pequeño delay para que se vea el overlay
	setTimeout(function() {
		if (!window.overlayOcultado) {
			window.ocultarOverlayConSeguridad(false);
			window.overlayOcultado = true;
		}
	}, 300); // 300ms de delay para que el usuario vea el overlay brevemente
});

// También ocultar si jQuery está listo (para páginas con muchos AJAX)
$(document).ready(function() {
	// Verificar errores nuevamente cuando jQuery esté listo
	if (detectarErroresPHP()) {
		console.error('Error detectado después de jQuery ready');
		if (!window.overlayOcultado) {
			window.ocultarOverlayConSeguridad(true);
			window.overlayOcultado = true;
		}
		return;
	}
	
	// Este es un respaldo adicional
	setTimeout(function() {
		if (!window.overlayOcultado) {
			window.ocultarOverlayConSeguridad(false);
			window.overlayOcultado = true;
		}
	}, 2000); // Timeout de 2 segundos
	
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
	if (hash === '#productos') {
		var pestanaItems = document.querySelector('a[href="#itemsCotizados"]');
		if (pestanaItems && $.fn.tab) $(pestanaItems).tab('show');
	} else if (hash) {
		const tabId = hash.substring(1);
		const $tabLink = $('a[href="' + hash + '"]');
		
		if ($tabLink.length > 0) {
			const lazyLoad = $tabLink.data('lazy-load');
			
			// Activar el tab
			if ($.fn.tab) {
				$tabLink.tab('show');
			}
			
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
