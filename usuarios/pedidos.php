<?php
include("sesion.php");

$idPagina = 151;
include("includes/verificar-paginas.php");
include("includes/head.php");

require_once RUTA_PROYECTO.'/usuarios/class/Pedido.php';
require_once RUTA_PROYECTO.'/usuarios/includes/api-ofima-conexion.php';
$ofimaPedidosBloqueados = ofimaPedidosBloqueados($conexionBdPrincipal, (int) $idEmpresa);
$ofimaIntegracionActiva = ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa);
$ofimaBotonEnvio = $ofimaIntegracionActiva && empty($ofimaPedidosBloqueados);
$puedeTimelinePedido = Modulos::validarRol([237], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$puedeEliminarPedido = Modulos::validarRol([372], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$puedePdfPedido = Modulos::validarRol([373], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$puedeRemisionPedido = Modulos::validarRol([374], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
?>
<link href="css/clientes-listado.css" rel="stylesheet">
<link href="css/tablecloth.css" rel="stylesheet">
<style>
	.clientes-page .clientes-panel { overflow: visible; border-radius: 14px; }
	.clientes-page .clientes-panel-header { border-radius: 13px 13px 0 0; }
	.clientes-page .clientes-panel-body { border-radius: 0 0 13px 13px; }
	.clientes-page .clientes-table thead th { position: sticky; top: 0; z-index: 2; }
	.clientes-page .clientes-table tbody tr.is-remision td { background: #ecfdf5; }
	.clientes-page #data-table { table-layout: fixed; font-size: 0.75rem; }
	.clientes-page #data-table thead th { font-size: 0.625rem; padding: 0.4rem 0.35rem; white-space: normal; }
	.clientes-page #data-table tbody td { padding: 0.35rem 0.4rem; line-height: 1.25; }
	.clientes-page #data-table th:nth-child(1),
	.clientes-page #data-table td:nth-child(1) { width: 36px; }
	.clientes-page #data-table th:nth-child(2),
	.clientes-page #data-table td:nth-child(2) { width: 52px; }
	.clientes-page #data-table th:nth-child(3),
	.clientes-page #data-table td:nth-child(3) { width: 92px; }
	.clientes-page #data-table th:nth-child(7),
	.clientes-page #data-table td:nth-child(7) { width: 88px; }
	.clientes-page #data-table th:nth-child(8),
	.clientes-page #data-table td:nth-child(8) { width: 64px; }
	.clientes-page #data-table th.col-ofima,
	.clientes-page #data-table td.col-ofima { width: 56px; text-align: center; }
	.clientes-page #data-table th.col-acciones,
	.clientes-page #data-table td.cotiz-acciones { width: 96px; }
	.clientes-page #data-table td.col-ofima { padding: 0; }
	.pedidos-icono-ofima {
		display: flex; align-items: center; justify-content: center;
		width: 100%; min-height: 46px; line-height: 1; font-size: 1.05rem;
		color: inherit; text-decoration: none; cursor: default; background: transparent; border: 0;
	}
	a.pedidos-icono-ofima { cursor: pointer; }
	a.pedidos-icono-ofima:hover, a.pedidos-icono-ofima:focus { text-decoration: none; }
	.pedidos-icono-ofima.is-ok, a.pedidos-icono-ofima.is-ok { color: #16a34a; }
	.pedidos-icono-ofima.is-error, a.pedidos-icono-ofima.is-error { color: #b91c1c; }
	.pedidos-icono-ofima.is-off, a.pedidos-icono-ofima.is-off { color: #cbd5e1; }
	.clientes-page #data-table th:nth-child(4),
	.clientes-page #data-table td:nth-child(4),
	.clientes-page #data-table th:nth-child(5),
	.clientes-page #data-table td:nth-child(5),
	.clientes-page #data-table th:nth-child(6),
	.clientes-page #data-table td:nth-child(6) { overflow-wrap: anywhere; }
	.clientes-page .clientes-table-wrap + .productos-paginacion { margin: 0.65rem 0 0; font-size: 0.75rem; }
	.clientes-page .clientes-table-wrap { overflow: visible; }
	.clientes-page .cotiz-acciones { white-space: nowrap; text-align: right; }
	.clientes-page .cotiz-acciones .btn-group > .btn.dropdown-toggle {
		border-radius: 8px;
		padding-left: 0.9rem;
		padding-right: 0.9rem;
	}
	.clientes-page .cotiz-aviso {
		margin: 0 0 1rem; padding: 0.75rem 1rem; border-radius: 10px;
		background: #eff6ff; border: 1px solid #bfdbfe; color: #1e3a8a; font-size: 0.875rem;
	}
	.clientes-page .cotiz-aviso.is-ok { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
	.clientes-page .cotiz-aviso.is-error { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
	.clientes-page .btn-ofima-pendiente { background: #ea580c; border-color: #c2410c; color: #fff; }
	.clientes-page .btn-ofima-pendiente:hover { background: #c2410c; color: #fff; }
	.clientes-page .btn-ofima-ok { background: #047857; border-color: #065f46; color: #fff; }
	.clientes-page .btn-ofima-ok:hover { background: #065f46; color: #fff; }
	.clientes-page .btn-ofima-error { background: #b91c1c; border-color: #991b1b; color: #fff; }
	.clientes-page .btn-ofima-error:hover { background: #991b1b; color: #fff; }
	.clientes-page .btn-ofima-msg { background: #fff; border: 1px solid #cbd5e1; color: #334155; }
	.productos-paginacion {
		display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
		gap: 0.75rem 1rem; margin: 0 0 0.75rem; padding: 0.5rem 0.75rem;
		font-size: 0.8125rem; line-height: 1; color: #475569;
		background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
	}
	.productos-paginacion-grupo, .productos-paginacion form { display: flex; align-items: center; gap: 0.4rem; margin: 0; }
	.productos-paginacion label { margin: 0; line-height: 34px; }
	.productos-paginacion select, .productos-paginacion input[type="number"], .productos-paginacion button,
	.productos-paginacion a, .productos-paginacion .es-actual {
		box-sizing: border-box; height: 34px; min-height: 34px; margin: 0; padding: 0 0.7rem;
		border-radius: 8px; font-size: 0.8125rem; line-height: 32px; vertical-align: middle;
	}
	.productos-paginacion select, .productos-paginacion input[type="number"] { border: 1px solid #cbd5e1; background: #fff; color: #0f172a; }
	.productos-paginacion input[type="number"] { width: 4.2rem; text-align: center; }
	.productos-paginacion a, .productos-paginacion .es-actual, .productos-paginacion .es-puntos {
		display: inline-flex; align-items: center; justify-content: center; min-width: 34px;
		border: 1px solid #cbd5e1; background: #fff; color: #334155; text-decoration: none; font-weight: 600;
	}
	.productos-paginacion a:hover { background: #e2e8f0; color: #0f172a; }
	.productos-paginacion .es-actual { background: #1d4ed8; border-color: #1d4ed8; color: #fff; }
	.productos-paginacion .es-puntos { border: 0; background: transparent; min-width: 1.2rem; height: auto; }
	.productos-paginacion button { border: 0; background: #1d4ed8; color: #fff; font-weight: 600; cursor: pointer; }
</style>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.tablecloth.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/ZeroClipboard.js"></script>
<script src="js/dataTables.bootstrap.js"></script>
<script src="js/TableTools.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script src="js/bootbox.js"></script>
<script type="text/javascript">
	$(function() {
		if ($('#data-table').length && !$('body').hasClass('clientes-page')) {
			$('#data-table').dataTable({
				"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
			});
		}
	});
</script>
</head>
<body class="clientes-page">
<div class="layout">
	<?php include("includes/encabezado.php"); ?>
	<div class="main-wrapper">
		<div class="container-fluid clientes-page-inner">
			<div class="clientes-hero">
				<div>
					<h1 class="clientes-hero-title"><?= htmlspecialchars($paginaActual['pag_nombre'] ?? 'Pedidos'); ?></h1>
					<p class="clientes-hero-subtitle">Consulte pedidos, remisiones y el envío a Ofima</p>
				</div>
				<div class="clientes-hero-actions">
					<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
				</div>
			</div>
			<?php include("includes/notificaciones.php"); ?>
			<section class="clientes-panel">
				<div class="clientes-panel-header">
					<div>
						<h3>Listado de pedidos</h3>
						<p>Busque por ID, cliente o responsable</p>
					</div>
				</div>
				<div class="clientes-panel-body">
					<?php if (!empty($ofimaPedidosBloqueados)) { ?>
					<div class="cotiz-aviso">
						<?= htmlspecialchars(ofimaMensajePedidosBloqueados(), ENT_QUOTES, 'UTF-8'); ?>
						Solo puede consultar y generar PDF.
					</div>
					<?php } ?>
					<?php if (isset($_GET['ofima'])) { ?>
					<div class="cotiz-aviso <?= $_GET['ofima'] === '1' ? 'is-ok' : 'is-error'; ?>">
						<?= $_GET['ofima'] === '1' ? 'El pedido se envió a Ofima.' : 'No se pudo enviar el pedido a Ofima. El detalle está en el panel.'; ?>
					</div>
					<?php } ?>

					<form method="get" action="pedidos.php" class="clientes-busqueda-principal">
						<?php if (!empty($_GET['cte'])) { ?>
							<input type="hidden" name="cte" value="<?= htmlspecialchars((string) $_GET['cte'], ENT_QUOTES, 'UTF-8'); ?>">
						<?php } ?>
						<label class="clientes-busqueda-label" for="pedidosBusqueda">Buscar</label>
						<div class="clientes-busqueda-input-wrap">
							<i class="icon-search clientes-busqueda-icon" aria-hidden="true"></i>
							<input type="search" id="pedidosBusqueda" name="busqueda" value="<?php if (isset($_GET['busqueda'])) echo htmlspecialchars($_GET['busqueda'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="ID, cliente o responsable" autocomplete="off">
							<button type="submit" class="clientes-busqueda-btn">Buscar</button>
						</div>
						<?php if (!empty($_GET['busqueda'])) { ?>
						<p class="clientes-busqueda-hint">
							Buscando «<?= htmlspecialchars($_GET['busqueda'], ENT_QUOTES, 'UTF-8'); ?>».
							<a href="pedidos.php<?= !empty($_GET['cte']) ? '?cte='.urlencode($_GET['cte']) : ''; ?>">Quitar búsqueda</a>
						</p>
						<?php } ?>
					</form>

					<?php include("includes/pedidos-listado-consulta.php"); ?>

					<div class="clientes-table-wrap">
						<table class="clientes-table" id="data-table">
							<thead>
								<tr>
									<th>No.</th>
									<th>ID</th>
									<th>Fecha documento</th>
									<th>Cliente</th>
									<th>Responsable</th>
									<th>Vendedor</th>
									<th>Estado</th>
									<th># Cotización</th>
									<?php if (!empty($ofimaIntegracionActiva)) { ?>
									<th class="col-ofima">Ofima</th>
									<?php } ?>
									<th class="col-acciones"></th>
								</tr>
							</thead>
							<tbody id="pedidos_buscar">
								<?php include("includes/pedidos-listado-filas.php"); ?>
							</tbody>
						</table>
					</div>
					<?php include("includes/pedidos-listado-paginacion.php"); ?>
				</div>
			</section>
		</div>
	</div>
</div>
<div id="drawerOfimaOverlay"></div>
<aside id="drawerOfimaError" aria-hidden="true">
	<div class="drawer-header">
		<h2 id="drawerOfimaTitulo">Error al enviar a Ofima</h2>
		<button type="button" class="drawer-close" id="cerrarOfimaMensaje" aria-label="Cerrar">×</button>
	</div>
	<div class="drawer-body">
		<p class="drawer-label" id="drawerOfimaRespuestaLabel">Respuesta de Ofima</p>
		<pre id="drawerOfimaTexto"></pre>
		<p class="drawer-label is-second" id="drawerOfimaEnviadoLabel" hidden>JSON enviado</p>
		<pre id="drawerOfimaEnviado" class="is-enviado" hidden></pre>
	</div>
</aside>
<?php if (isset($_GET['ofima']) && $_GET['ofima'] !== '1') {
	$ofimaErrorEnvio = (isset($_GET['ofima_msg']) && $_GET['ofima_msg'] !== '') ? $_GET['ofima_msg'] : 'No se pudo enviar el pedido a Ofima.';
	$busquedaOfima = isset($_GET['busqueda']) ? (string) $_GET['busqueda'] : '';
	if ($busquedaOfima !== '' && !empty($pedidosFilas)) {
		foreach ($pedidosFilas as $filaOfima) {
			if ((string) $filaOfima['pedid_id'] === $busquedaOfima && !empty($filaOfima['pedid_ofima_respuesta'])) {
				$ofimaErrorEnvio = (string) $filaOfima['pedid_ofima_respuesta'];
				break;
			}
		}
	}
?>
<script>window.OFIMA_ERROR_ENVIO = <?= json_encode($ofimaErrorEnvio, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;</script>
<?php } ?>
<?php include("includes/pie.php"); ?>
<script>
	(function () {
		var drawer = document.getElementById('drawerOfimaError');
		var overlay = document.getElementById('drawerOfimaOverlay');
		var drawerTexto = document.getElementById('drawerOfimaTexto');
		var drawerTitulo = document.getElementById('drawerOfimaTitulo');
		function formatearJson(texto) {
			try { return JSON.stringify(JSON.parse(texto), null, 2); } catch (e) { return texto; }
		}
		function separarMensaje(texto) {
			var respuesta = texto || 'Sin mensaje de Ofima.';
			var enviado = '';
			try {
				var data = JSON.parse(texto);
				if (data && typeof data === 'object' && !Array.isArray(data) && Object.prototype.hasOwnProperty.call(data, 'enviado') && Object.prototype.hasOwnProperty.call(data, 'respuesta')) {
					respuesta = typeof data.respuesta === 'string' ? data.respuesta : JSON.stringify(data.respuesta);
					enviado = typeof data.enviado === 'string' ? data.enviado : JSON.stringify(data.enviado);
				}
			} catch (e) {}
			return { respuesta: formatearJson(respuesta), enviado: enviado ? formatearJson(enviado) : '' };
		}
		function esRespuestaOfimaExitosa(texto, estado) {
			if (String(estado) === '1') return true;
			if (String(estado) === '2') return false;
			var fuente = String(texto || '');
			try {
				var data = JSON.parse(fuente);
				if (data && typeof data === 'object' && data.respuesta != null) {
					fuente = typeof data.respuesta === 'string' ? data.respuesta : JSON.stringify(data.respuesta);
					try { data = JSON.parse(fuente); } catch (e2) { data = { mensaje: fuente }; }
				}
				if (data && typeof data === 'object') {
					var mensaje = String(data.mensaje || data.message || '');
					if (/exitos/i.test(mensaje) || data.success === true) return true;
				}
			} catch (e) {}
			return /pedido creado exitosamente/i.test(fuente);
		}
		function abrirMensaje(texto, titulo, estado) {
			if (!drawer || !drawerTexto) return;
			var exitoso = esRespuestaOfimaExitosa(texto, estado);
			drawer.classList.toggle('is-ok', exitoso);
			if (drawerTitulo) drawerTitulo.textContent = exitoso ? 'Enviado a Ofima' : (titulo || 'Error al enviar a Ofima');
			var partes = separarMensaje(texto);
			drawerTexto.textContent = partes.respuesta;
			var labelEnv = document.getElementById('drawerOfimaEnviadoLabel');
			var preEnv = document.getElementById('drawerOfimaEnviado');
			if (labelEnv && preEnv) {
				preEnv.textContent = partes.enviado;
				preEnv.hidden = !partes.enviado;
				labelEnv.hidden = !partes.enviado;
			}
			drawer.classList.add('is-open');
			if (overlay) overlay.classList.add('is-open');
			drawer.setAttribute('aria-hidden', 'false');
		}
		function cerrarMensaje() {
			if (!drawer) return;
			drawer.classList.remove('is-open');
			if (overlay) overlay.classList.remove('is-open');
			drawer.setAttribute('aria-hidden', 'true');
		}
		document.getElementById('cerrarOfimaMensaje').addEventListener('click', cerrarMensaje);
		if (overlay) overlay.addEventListener('click', cerrarMensaje);
		if (window.OFIMA_ERROR_ENVIO) abrirMensaje(window.OFIMA_ERROR_ENVIO, 'Error al enviar a Ofima');
		document.addEventListener('click', function (e) {
			var boton = e.target.closest ? e.target.closest('.js-ofima-mensaje') : null;
			if (!boton) return;
			e.preventDefault();
			abrirMensaje(boton.getAttribute('data-mensaje') || '', 'Respuesta de Ofima', boton.getAttribute('data-estado'));
		});

		var tbody = document.getElementById('pedidos_buscar');
		var paginacionWrap = document.getElementById('pedidosPaginacionContenedor');
		if (!tbody || !paginacionWrap) return;
		var requestId = 0;
		function paramsActuales() {
			return new URLSearchParams(window.location.search);
		}
		function actualizarUrl(pagina, por) {
			var params = paramsActuales();
			params.delete('ofima');
			params.delete('ofima_msg');
			if (por) params.set('por', String(por));
			if (pagina && pagina > 1) params.set('inicio', String(pagina));
			else params.delete('inicio');
			var query = params.toString();
			window.history.replaceState({}, '', 'pedidos.php' + (query ? '?' + query : ''));
		}
		function cargarListado(pagina, por) {
			var params = paramsActuales();
			params.delete('ofima');
			params.delete('ofima_msg');
			if (por) params.set('por', String(por));
			if (pagina && pagina > 1) params.set('inicio', String(pagina));
			else params.delete('inicio');
			var id = ++requestId;
			tbody.style.opacity = '0.55';
			fetch('ajax/ajax-pedidos-listado.php?' + params.toString(), { credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (data) {
					if (id !== requestId) return;
					tbody.style.opacity = '';
					if (!data || !data.success) return;
					tbody.innerHTML = data.html || '';
					var padre = paginacionWrap.parentNode;
					var tmp = document.createElement('div');
					tmp.innerHTML = data.pagination || '';
					var nueva = tmp.firstElementChild;
					if (nueva && padre) {
						padre.replaceChild(nueva, paginacionWrap);
						paginacionWrap = document.getElementById('pedidosPaginacionContenedor');
					}
					actualizarUrl(data.pagina || 1, data.por);
				})
				.catch(function () {
					if (id !== requestId) return;
					tbody.style.opacity = '';
				});
		}
		document.addEventListener('click', function (e) {
			var link = e.target.closest ? e.target.closest('a.js-pedidos-pagina') : null;
			if (!link || !link.closest('.productos-paginacion')) return;
			e.preventDefault();
			var pagina = parseInt(link.getAttribute('data-pagina'), 10) || 1;
			var porSel = document.getElementById('pedidosPorPagina');
			cargarListado(pagina, porSel ? parseInt(porSel.value, 10) : null);
		});
		document.addEventListener('change', function (e) {
			if (!e.target || !e.target.classList || !e.target.classList.contains('js-pedidos-por-pagina')) return;
			cargarListado(1, parseInt(e.target.value, 10) || 10);
		});
		document.addEventListener('submit', function (e) {
			if (!e.target || e.target.id !== 'formIrPaginaPedido') return;
			e.preventDefault();
			var input = document.getElementById('pedidosIrPagina');
			var pagina = parseInt(input && input.value, 10);
			var maximo = parseInt(input && input.max, 10) || 1;
			if (!pagina || pagina < 1) pagina = 1;
			if (pagina > maximo) pagina = maximo;
			var porSel = document.getElementById('pedidosPorPagina');
			cargarListado(pagina, porSel ? parseInt(porSel.value, 10) : null);
		});
	})();
</script>
</body>
</html>
