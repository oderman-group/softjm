<?php 
include("sesion.php");

$idPagina = 77;
include("includes/verificar-paginas.php");
include("includes/head.php");
require_once RUTA_PROYECTO.'/usuarios/includes/api-ofima-conexion.php';
$ofimaPedidosBloqueados = ofimaPedidosBloqueados($conexionBdPrincipal, (int) $idEmpresa);
$ofimaBotonEnvio = ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa) && empty($ofimaPedidosBloqueados);
$puedeEditarCotiz = Modulos::validarRol([79], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$puedePdfCotiz = Modulos::validarRol([50], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$puedeReplicarCotiz = Modulos::validarRol([253], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$puedeGenerarPedidoCotiz = Modulos::validarRol([263], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$cotizFiltrosActivos = 0;
foreach (array('usuario_resp', 'tipo_cot', 'genero_pedido', 'fecha_inicio', 'fecha_fin') as $campoFiltroCotiz) {
	if (isset($_GET[$campoFiltroCotiz]) && $_GET[$campoFiltroCotiz] !== '') {
		$cotizFiltrosActivos++;
	}
}
?>
<link href="css/clientes-listado.css" rel="stylesheet">
<link href="css/tablecloth.css" rel="stylesheet">
<style>
	.clientes-page .clientes-panel { overflow: visible; border-radius: 14px; }
	.clientes-page .clientes-panel-header { border-radius: 13px 13px 0 0; }
	.clientes-page .clientes-panel-body { border-radius: 0 0 13px 13px; }
	.clientes-page .clientes-table thead th { position: sticky; top: 0; z-index: 2; }
	.clientes-page .clientes-table tbody tr.is-vendida td { background: #ecfdf5; }
	.clientes-page #data-table { table-layout: fixed; font-size: 0.75rem; }
	.clientes-page #data-table thead th { font-size: 0.625rem; padding: 0.4rem 0.35rem; white-space: normal; }
	.clientes-page #data-table tbody td { padding: 0.35rem 0.4rem; line-height: 1.25; }
	.clientes-page #data-table th:nth-child(1),
	.clientes-page #data-table td:nth-child(1) { width: 36px; }
	.clientes-page #data-table th:nth-child(2),
	.clientes-page #data-table td:nth-child(2) { width: 52px; }
	.clientes-page #data-table th:nth-child(3),
	.clientes-page #data-table td:nth-child(3) { width: 88px; }
	.clientes-page #data-table th:nth-child(4),
	.clientes-page #data-table td:nth-child(4) { width: 92px; }
	.clientes-page #data-table th:nth-child(6),
	.clientes-page #data-table td:nth-child(6) { width: 22%; }
	.clientes-page #data-table th:nth-child(9),
	.clientes-page #data-table td:nth-child(9) { width: 96px; }
	.clientes-page #data-table td.col-items { font-size: 0.6875rem; line-height: 1.3; }
	.clientes-page #data-table td.col-cliente { overflow: visible; }
	.clientes-page #data-table td.col-cliente .select2-container { width: 100% !important; }
	.clientes-page #data-table td.col-cliente .select2-container .select2-selection--single {
		height: 30px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff;
	}
	.clientes-page #data-table td.col-cliente .select2-selection__rendered {
		line-height: 28px; font-size: 0.75rem; color: #1d4ed8; padding-left: 0.4rem;
	}
	.clientes-page #data-table td.col-cliente .select2-selection__arrow { height: 28px; }
	.select2-container--open { z-index: 10060; }
	.select2-dropdown { border-radius: 8px; border-color: #cbd5e1; overflow: hidden; }
	.select2-container--open .select2-search--dropdown { padding: 0.35rem 0.45rem; background: #f8fafc; }
	.select2-container--open .select2-search__field {
		height: 28px; margin: 0; border-radius: 6px; font-size: 0.8125rem;
	}
	.select2-results__option { padding: 0.28rem 0.6rem; font-size: 0.8125rem; }
	.clientes-page #data-table th:nth-child(5),
	.clientes-page #data-table td:nth-child(5),
	.clientes-page #data-table th:nth-child(7),
	.clientes-page #data-table td:nth-child(7),
	.clientes-page #data-table th:nth-child(8),
	.clientes-page #data-table td:nth-child(8) { overflow-wrap: anywhere; }
	.clientes-page .clientes-table-wrap + .productos-paginacion { margin: 0.65rem 0 0; font-size: 0.75rem; }
	.clientes-page .clientes-table-wrap { overflow: visible; }
	.clientes-page .cotiz-acciones { white-space: nowrap; text-align: right; }
	.clientes-page .cotiz-acciones .btn-group > .btn.dropdown-toggle {
		border-radius: 8px;
		padding-left: 0.9rem;
		padding-right: 0.9rem;
	}
	.clientes-page .cotiz-aviso {
		margin: 0 0 1rem;
		padding: 0.75rem 1rem;
		border-radius: 10px;
		background: #eff6ff;
		border: 1px solid #bfdbfe;
		color: #1e3a8a;
		font-size: 0.875rem;
	}
	.clientes-page .btn-ofima-pendiente { background: #ea580c; border-color: #c2410c; color: #fff; }
	.clientes-page .btn-ofima-pendiente:hover { background: #c2410c; color: #fff; }
	.clientes-page .btn-ofima-ok { background: #047857; border-color: #065f46; color: #fff; }
	.clientes-page .btn-ofima-ok:hover { background: #065f46; color: #fff; }
	.clientes-page .btn-ofima-error { background: #b91c1c; border-color: #991b1b; color: #fff; }
	.clientes-page .btn-ofima-error:hover { background: #991b1b; color: #fff; }
	.clientes-page .btn-ofima-msg { background: #fff; border: 1px solid #cbd5e1; color: #334155; }
	.productos-paginacion {
		display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
		gap: 0.75rem 1rem; margin: 0; padding: 0.5rem 0.75rem;
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

<!--============j avascript===========-->
<link href="../assets-login/plugins/select2/css/select2.css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="../assets-login/plugins/select2/js/select2.js"></script>
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
	$(function() {
		$('.tbl-simple').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
		});
	});

	$(function() {
		$(".tbl-paper-theme").tablecloth({
			theme: "paper"
		});
	});

	$(function() {
		$(".tbl-dark-theme").tablecloth({
			theme: "dark"
		});

	});
	$(function() {
		$('.tbl-paper-theme,.tbl-dark-theme').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
		});


	});

	function confirmar(e) {
		bootbox.confirm("Desea enviar esta cotización?", function(result) {
			var id = e.id;
			var cont = e.name;
			if (result == true) {
				window.location.href = "enviar-cotizaciones-correo.php?get=44&id=" + id + "&cont=" + cont;
			}
		})
	};
</script>
</head>

<body class="clientes-page">
	<div class="layout">
		<?php include("includes/encabezado.php"); ?>
		<div class="main-wrapper">
			<div class="container-fluid clientes-page-inner">
				<div class="clientes-hero">
					<div>
						<h1 class="clientes-hero-title"><?= htmlspecialchars($paginaActual['pag_nombre'] ?? 'Cotizaciones'); ?></h1>
					</div>
					<div class="clientes-hero-actions">
						<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
						<?php if (Modulos::validarRol([78], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
						<a href="cotizaciones-agregar.php?cte=<?php if(isset($_GET['cte'])) echo htmlspecialchars($_GET['cte'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-success"><i class="icon-plus"></i> Agregar nuevo</a>
						<?php } ?>
						<a href="cotizaciones.php?dctoEspecial=1<?= isset($_GET['cte']) ? '&cte='.urlencode($_GET['cte']) : ''; ?>" class="btn btn-warning"><i class="icon-ok-sign"></i> Descuentos especiales</a>
					</div>
				</div>
				<?php include("includes/notificaciones.php"); ?>

				<section class="clientes-panel">
					<div class="clientes-panel-header">
						<div>
							<h3>Listado de cotizaciones</h3>
							<p>Filtre por responsable, tipo, pedido y fecha</p>
						</div>
					</div>
					<div class="clientes-panel-body">
						<?php if (!empty($ofimaPedidosBloqueados)) { ?>
						<div class="cotiz-aviso"><?= htmlspecialchars(ofimaMensajePedidosBloqueados(), ENT_QUOTES, 'UTF-8'); ?></div>
						<?php } ?>

						<div class="clientes-filtros-panel clientes-collapsible<?= $cotizFiltrosActivos > 0 ? '' : ' is-collapsed'; ?>" data-collapsible="filtros">
							<button type="button" class="clientes-collapsible-toggle" id="cotiz-filtros-toggle" aria-expanded="<?= $cotizFiltrosActivos > 0 ? 'true' : 'false'; ?>" aria-controls="cotiz-filtros-body">
								<span class="clientes-collapsible-heading">
									<span class="clientes-collapsible-title">Filtros</span>
									<?php if ($cotizFiltrosActivos > 0) { ?>
										<span class="clientes-collapsible-badge"><?= $cotizFiltrosActivos; ?> activo<?= $cotizFiltrosActivos === 1 ? '' : 's'; ?></span>
									<?php } else { ?>
										<span class="clientes-collapsible-summary">Responsable, tipo, pedido y fechas</span>
									<?php } ?>
								</span>
								<i class="icon-chevron-down clientes-collapsible-icon" aria-hidden="true"></i>
							</button>
							<div class="clientes-collapsible-body" id="cotiz-filtros-body"<?= $cotizFiltrosActivos > 0 ? '' : ' hidden'; ?>>
								<form method="GET" action="">
									<?php if(isset($_GET["cte"])) { ?><input type="hidden" name="cte" value="<?= htmlspecialchars($_GET["cte"], ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
									<?php if(!empty($_GET["q"])) { ?><input type="hidden" name="q" value="<?= htmlspecialchars($_GET["q"], ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
									<?php if(!empty($_GET["dctoEspecial"])) { ?><input type="hidden" name="dctoEspecial" value="1"><?php } ?>
									<div class="filtros-grid">
										<div class="filtro-item">
											<label>Usuario responsable <?php if(isset($_GET["usuario_resp"]) && $_GET["usuario_resp"]!="") { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['usuario_resp' => ''])) ?>">×</a><?php } ?></label>
											<select name="usuario_resp">
												<option value="">Todos</option>
												<?php
												$consultaUsuarios = mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id_empresa='".$idEmpresa."' AND usr_bloqueado != 1 ORDER BY usr_nombre");
												while($usuario = mysqli_fetch_array($consultaUsuarios, MYSQLI_BOTH)){
													$selected = (isset($_GET["usuario_resp"]) && $_GET["usuario_resp"]==$usuario['usr_id']) ? "selected" : "";
													echo '<option value="'.$usuario['usr_id'].'" '.$selected.'>'.htmlspecialchars($usuario['usr_nombre'], ENT_QUOTES, 'UTF-8').'</option>';
												}
												?>
											</select>
										</div>
										<div class="filtro-item">
											<label>Tipo <?php if(isset($_GET["tipo_cot"]) && $_GET["tipo_cot"]!="") { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['tipo_cot' => ''])) ?>">×</a><?php } ?></label>
											<select name="tipo_cot">
												<option value="">Todos</option>
												<option value="0" <?= (isset($_GET["tipo_cot"]) && $_GET["tipo_cot"]=="0") ? "selected" : ""; ?>>Cotización</option>
												<option value="1" <?= (isset($_GET["tipo_cot"]) && $_GET["tipo_cot"]=="1") ? "selected" : ""; ?>>Pre-cotización</option>
											</select>
										</div>
										<div class="filtro-item">
											<label>Generó pedido <?php if(isset($_GET["genero_pedido"]) && $_GET["genero_pedido"]!="") { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['genero_pedido' => ''])) ?>">×</a><?php } ?></label>
											<select name="genero_pedido">
												<option value="">Todos</option>
												<option value="1" <?= (isset($_GET["genero_pedido"]) && $_GET["genero_pedido"]=="1") ? "selected" : ""; ?>>Sí</option>
												<option value="0" <?= (isset($_GET["genero_pedido"]) && $_GET["genero_pedido"]=="0") ? "selected" : ""; ?>>No</option>
											</select>
										</div>
										<div class="filtro-item">
											<label>Fecha inicio <?php if(isset($_GET["fecha_inicio"]) && $_GET["fecha_inicio"]!="") { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['fecha_inicio' => ''])) ?>">×</a><?php } ?></label>
											<input type="date" name="fecha_inicio" value="<?= isset($_GET["fecha_inicio"]) ? htmlspecialchars($_GET["fecha_inicio"], ENT_QUOTES, 'UTF-8') : ""; ?>">
										</div>
										<div class="filtro-item">
											<label>Fecha fin <?php if(isset($_GET["fecha_fin"]) && $_GET["fecha_fin"]!="") { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['fecha_fin' => ''])) ?>">×</a><?php } ?></label>
											<input type="date" name="fecha_fin" value="<?= isset($_GET["fecha_fin"]) ? htmlspecialchars($_GET["fecha_fin"], ENT_QUOTES, 'UTF-8') : ""; ?>">
										</div>
									</div>
									<div class="clientes-filtros-acciones">
										<button type="submit" class="clientes-btn-primary">Filtrar</button>
										<a href="cotizaciones.php<?= isset($_GET["cte"]) ? "?cte=".urlencode($_GET["cte"]) : ""; ?>" class="clientes-btn-secondary">Limpiar</a>
									</div>
								</form>
							</div>
						</div>

						<form method="get" action="<?= $_SERVER['PHP_SELF']; ?>" class="clientes-busqueda-principal">
							<?php
							foreach (array('cte', 'usuario_resp', 'tipo_cot', 'genero_pedido', 'fecha_inicio', 'fecha_fin', 'dctoEspecial') as $campoOculto) {
								if (isset($_GET[$campoOculto]) && $_GET[$campoOculto] !== '') {
									echo '<input type="hidden" name="'.htmlspecialchars($campoOculto, ENT_QUOTES, 'UTF-8').'" value="'.htmlspecialchars((string) $_GET[$campoOculto], ENT_QUOTES, 'UTF-8').'">';
								}
							}
							?>
							<label class="clientes-busqueda-label" for="cotizBusquedaId">Buscar por ID</label>
							<div class="clientes-busqueda-input-wrap">
								<i class="icon-search clientes-busqueda-icon" aria-hidden="true"></i>
								<input type="search" id="cotizBusquedaId" name="q" value="<?php if (isset($_GET['q'])) echo htmlspecialchars($_GET['q'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="Número de cotización" autocomplete="off">
								<button type="submit" class="clientes-busqueda-btn">Buscar</button>
							</div>
							<?php if (!empty($_GET['q']) || !empty($_GET['dctoEspecial'])) { ?>
							<p class="clientes-busqueda-hint">
								<?php if (!empty($_GET['q'])) { ?>ID <?= htmlspecialchars($_GET['q'], ENT_QUOTES, 'UTF-8'); ?>. <?php } ?>
								<?php if (!empty($_GET['dctoEspecial'])) { ?>Solo descuentos especiales. <?php } ?>
								<a href="cotizaciones.php<?= isset($_GET['cte']) ? '?cte='.urlencode($_GET['cte']) : ''; ?>">Quitar búsqueda</a>
							</p>
							<?php } ?>
						</form>

								<?php include("includes/cotizaciones-listado-consulta.php"); ?>

								<div class="clientes-table-wrap">
								<table class="clientes-table" id="data-table">
									<thead>
										<tr>
										<th>No.</th>
											<th>ID</th>
											<th>Tipo</th>
											<th>Fecha propuesta</th>
											<th>Cliente</th>
											<th>Ítems incluidos</th>
											<th>Responsable</th>
											<th>Vendedor</th>
											<th></th>
										</tr>
									</thead>
									<tbody id="cotizaciones_buscar">
										<?php include("includes/cotizaciones-listado-filas.php"); ?>
									</tbody>
								</table>
								</div>
								<?php include("includes/cotizaciones-listado-paginacion.php"); ?>
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
	<?php include("includes/pie.php"); ?>
	<script>
		(function () {
			document.querySelectorAll('.clientes-collapsible').forEach(function (section) {
				var toggle = section.querySelector('.clientes-collapsible-toggle');
				var body = section.querySelector('.clientes-collapsible-body');
				if (!toggle || !body) return;
				toggle.addEventListener('click', function () {
					var willExpand = section.classList.contains('is-collapsed');
					section.classList.toggle('is-collapsed', !willExpand);
					toggle.setAttribute('aria-expanded', willExpand ? 'true' : 'false');
					if (willExpand) body.removeAttribute('hidden');
					else body.setAttribute('hidden', '');
				});
			});

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
			document.addEventListener('click', function (e) {
				var boton = e.target.closest ? e.target.closest('.js-ofima-mensaje') : null;
				if (!boton) return;
				e.preventDefault();
				abrirMensaje(boton.getAttribute('data-mensaje') || '', 'Respuesta de Ofima', boton.getAttribute('data-estado'));
			});

			function activarClientesCotizacion() {
				if (!window.jQuery || !jQuery.fn.select2) return;
				jQuery('#cotizaciones_buscar select.js-cotiz-cliente').each(function () {
					var $sel = jQuery(this);
					if ($sel.data('select2')) return;
					$sel.select2({
						width: '100%',
						minimumInputLength: 2,
						dropdownParent: jQuery(document.body),
						ajax: {
							url: 'ajax/ajax-buscar-clientes-cotizacion.php',
							dataType: 'json',
							delay: 300,
							data: function (params) { return { q: params.term, habilitados: 1 }; },
							processResults: function (data) {
								var lista = (data && data.clientes) || [];
								return {
									results: lista.map(function (cliente) {
										return { id: cliente.id, text: String(cliente.nombre || '').toUpperCase() };
									})
								};
							}
						},
						language: {
							inputTooShort: function () { return 'Escriba al menos 2 letras. Solo clientes habilitados.'; },
							noResults: function () { return 'No hay clientes habilitados'; },
							searching: function () { return 'Buscando...'; }
						}
					});
					$sel.on('select2:selecting', function (e) {
						var nombre = (e.params && e.params.args && e.params.args.data && e.params.args.data.text) || 'el cliente elegido';
						if (!window.confirm('¿Cambiar el cliente de esta cotización a ' + nombre + '?')) {
							e.preventDefault();
						}
					});
					$sel.on('select2:select', function (e) {
						var elegido = e.params.data;
						var previo = $sel.attr('data-previo');
						var cuerpo = new FormData();
						cuerpo.append('idCliente', elegido.id);
						cuerpo.append('idCotizacion', $sel.attr('data-cotiz'));
						$sel.prop('disabled', true);
						fetch('ajax/ajax-cotizaciones-actualizar.php', { method: 'POST', body: cuerpo, credentials: 'same-origin' })
							.then(function (r) { return r.json(); })
							.then(function (data) {
								$sel.prop('disabled', false);
								if (!data || !data.success) {
									$sel.val(previo).trigger('change.select2');
									window.alert((data && data.message) || 'No se pudo cambiar el cliente.');
									return;
								}
								$sel.attr('data-previo', String(elegido.id));
							})
							.catch(function () {
								$sel.prop('disabled', false);
								$sel.val(previo).trigger('change.select2');
								window.alert('No se pudo cambiar el cliente.');
							});
					});
				});
			}
			activarClientesCotizacion();

			var tbody = document.getElementById('cotizaciones_buscar');
			var paginacionWrap = document.getElementById('cotizacionesPaginacionContenedor');
			if (!tbody || !paginacionWrap) return;
			var requestId = 0;

			function paramsActuales() {
				return new URLSearchParams(window.location.search);
			}
			function actualizarUrl(pagina, por) {
				var params = paramsActuales();
				if (por) params.set('por', String(por));
				if (pagina && pagina > 1) params.set('inicio', String(pagina));
				else params.delete('inicio');
				var query = params.toString();
				window.history.replaceState({}, '', 'cotizaciones.php' + (query ? '?' + query : ''));
			}
			function cargarListado(pagina, por) {
				var params = paramsActuales();
				if (por) params.set('por', String(por));
				if (pagina && pagina > 1) params.set('inicio', String(pagina));
				else params.delete('inicio');
				var id = ++requestId;
				tbody.style.opacity = '0.55';
				fetch('ajax/ajax-cotizaciones-listado.php?' + params.toString(), { credentials: 'same-origin' })
					.then(function (r) { return r.json(); })
					.then(function (data) {
						if (id !== requestId) return;
						tbody.style.opacity = '';
						if (!data || !data.success) return;
						tbody.innerHTML = data.html || '';
						activarClientesCotizacion();
						var padre = paginacionWrap.parentNode;
						var tmp = document.createElement('div');
						tmp.innerHTML = data.pagination || '';
						var nueva = tmp.firstElementChild;
						if (nueva && padre) {
							padre.replaceChild(nueva, paginacionWrap);
							paginacionWrap = document.getElementById('cotizacionesPaginacionContenedor');
						}
						actualizarUrl(data.pagina || 1, data.por);
					})
					.catch(function () {
						if (id !== requestId) return;
						tbody.style.opacity = '';
					});
			}
			document.addEventListener('click', function (e) {
				var link = e.target.closest ? e.target.closest('a.js-cotizaciones-pagina') : null;
				if (!link || !link.closest('.productos-paginacion')) return;
				e.preventDefault();
				var pagina = parseInt(link.getAttribute('data-pagina'), 10) || 1;
				var porSel = document.getElementById('cotizacionesPorPagina');
				cargarListado(pagina, porSel ? parseInt(porSel.value, 10) : null);
			});
			document.addEventListener('change', function (e) {
				if (!e.target || !e.target.classList || !e.target.classList.contains('js-cotizaciones-por-pagina')) return;
				cargarListado(1, parseInt(e.target.value, 10) || 10);
			});
			document.addEventListener('submit', function (e) {
				if (!e.target || e.target.id !== 'formIrPaginaCotizacion') return;
				e.preventDefault();
				var input = document.getElementById('cotizacionesIrPagina');
				var pagina = parseInt(input && input.value, 10);
				var maximo = parseInt(input && input.max, 10) || 1;
				if (!pagina || pagina < 1) pagina = 1;
				if (pagina > maximo) pagina = maximo;
				var porSel = document.getElementById('cotizacionesPorPagina');
				cargarListado(pagina, porSel ? parseInt(porSel.value, 10) : null);
			});
		})();
	</script>
</body>

</html>