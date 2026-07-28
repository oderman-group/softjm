<?php
include("sesion.php");
$idPagina = 9;
include("includes/verificar-paginas.php");
include("includes/head.php");

include(RUTA_PROYECTO . "/usuarios/class/Cliente.php");
require_once RUTA_PROYECTO . '/usuarios/includes/clientes-listado-preparar.php';
?>
<link href="css/crm-etiquetas.css" rel="stylesheet">
<link href="css/clientes-listado.css" rel="stylesheet">

<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/dataTables.bootstrap.js"></script>
<script src="js/custom.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script type="text/javascript">
	$(function() {
		var $table = $('#data-table');
		if (!$table.length || $table.find('tbody tr.clientes-empty-row').length) {
			return;
		}

		// Evita el alert modal de DataTables 1.9 ante filas inconsistentes.
		if ($.fn.dataTableExt) {
			$.fn.dataTableExt.sErrMode = 'mute';
		}

		$table.dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t",
			"bPaginate": false,
			"bInfo": false,
			"aaSorting": []
		});
	});
</script>

<?php include("includes/funciones-js.php"); ?>
</head>

<body class="clientes-page">
	<?php if (!empty($listadoPermisos['agregarCliente'])) {
		include("includes/drawer-crear-cliente.php");
	} ?>
	<div class="layout">
		<?php include("includes/encabezado.php"); ?>

		<div class="main-wrapper">
			<div class="container-fluid clientes-page-inner">

				<div class="clientes-hero">
					<div>
						<h1 class="clientes-hero-title"><?= htmlspecialchars($paginaActual['pag_nombre'] ?? 'Clientes'); ?></h1>
						<p class="clientes-hero-subtitle">Gestión de cartera, prospectos y relaciones comerciales</p>
					</div>
					<div class="clientes-hero-actions">
						<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
						<?php if ($listadoPermisos['agregarCliente']) { ?>
							<a href="clientes-agregar.php" class="btn btn-success js-abrir-crear-cliente" aria-haspopup="dialog"><i class="icon-plus"></i> Agregar cliente</a>
						<?php } ?>
						<?php if ($listadoPermisos['importarClientes']) { ?>
							<a href="clientes-importar.php" class="btn btn-info"><i class="icon-upload"></i> Importar</a>
						<?php } ?>
					</div>
				</div>

				<?php
				$clientesKpiTotal = intval($clientesCartera['total'] ?? 0);
				$clientesKpiRegistrados = intval($clientesResumenAnual['registrados_anio'] ?? 0);
				$clientesKpiConvertidos = intval($clientesResumenAnual['nuevos_clientes'] ?? 0);
				?>
				<section class="clientes-collapsible is-collapsed" data-collapsible="kpi" aria-labelledby="clientes-kpi-toggle">
					<button type="button" class="clientes-collapsible-toggle" id="clientes-kpi-toggle" aria-expanded="false" aria-controls="clientes-kpi-body">
						<span class="clientes-collapsible-heading">
							<span class="clientes-collapsible-title">Indicadores de cartera</span>
							<span class="clientes-collapsible-summary">
								<?= $clientesKpiTotal; ?> activos · <?= $clientesKpiRegistrados; ?> registrados · <?= $clientesKpiConvertidos; ?> convertidos
							</span>
						</span>
						<i class="icon-chevron-down clientes-collapsible-icon" aria-hidden="true"></i>
					</button>
					<div class="clientes-collapsible-body" id="clientes-kpi-body" hidden>
						<div class="clientes-kpi-grid">
							<div class="clientes-kpi-card is-primary">
								<div class="clientes-kpi-label">Cartera activa</div>
								<div class="clientes-kpi-value"><?= $clientesKpiTotal; ?></div>
								<div class="clientes-kpi-hint">
									<?= intval($clientesCartera['clientes'] ?? 0); ?> clientes ·
									<?= intval($clientesCartera['prospectos'] ?? 0); ?> prospectos
								</div>
							</div>
							<div class="clientes-kpi-card is-success">
								<div class="clientes-kpi-label">Registrados <?= $clientesAnioActual; ?></div>
								<div class="clientes-kpi-value"><?= $clientesKpiRegistrados; ?></div>
								<div class="clientes-kpi-hint">Nuevos registros en el año</div>
							</div>
							<div class="clientes-kpi-card is-warning">
								<div class="clientes-kpi-label">Nuevos clientes</div>
								<div class="clientes-kpi-value"><?= intval($clientesNuevosEsteMes); ?></div>
								<div class="clientes-kpi-hint">
									<a href="clientes.php?clientesNuevos=1">Este mes · ver listado</a>
								</div>
							</div>
							<div class="clientes-kpi-card is-info">
								<div class="clientes-kpi-label">Top compras <?= $clientesAnioActual; ?></div>
								<div class="clientes-kpi-value"><?= intval($clienteConMasVenta['cantidad'] ?? 0); ?></div>
								<div class="clientes-kpi-hint">
									<?php if (!empty($clienteConMasVenta['factura_cliente'])) { ?>
										<a href="clientes-editar.php?id=<?= intval($clienteConMasVenta['factura_cliente']); ?>" target="_blank">
											<?= htmlspecialchars($clienteConMasVenta['nombreCliente'] ?? 'Sin datos'); ?>
										</a>
									<?php } else { ?>
										Sin datos de facturación
									<?php } ?>
								</div>
							</div>
							<div class="clientes-kpi-card is-danger">
								<div class="clientes-kpi-label">Convertidos <?= $clientesAnioActual; ?></div>
								<div class="clientes-kpi-value"><?= $clientesKpiConvertidos; ?></div>
								<div class="clientes-kpi-hint">Por fecha de ingreso como cliente</div>
							</div>
						</div>
					</div>
				</section>

				<?php include("includes/notificaciones.php"); ?>

				<div class="clientes-layout">
					<aside class="clientes-sidebar">
						<div class="clientes-sidebar-header">
							<h4>Departamentos</h4>
						</div>
						<div class="clientes-sidebar-body">
							<?php
							$dptoActivo = isset($_GET['dpto']) ? intval($_GET['dpto']) : 0;
							$totalTodos = array_sum($conteoPorDepartamento);
							?>
							<a href="clientes.php?<?= http_build_query(array_diff_key($filtrosGetPreservados, ['dpto' => ''])); ?>"
							   class="clientes-depto-link<?= $dptoActivo === 0 ? ' is-active' : ''; ?>">
								<span>Todos</span>
								<span class="clientes-depto-count"><?= intval($totalTodos); ?></span>
							</a>
							<?php foreach ($departamentosListado as $depto) {
								$idDepto = intval($depto['dep_id']);
								$paramsDepto = $filtrosGetPreservados;
								$paramsDepto['dpto'] = $idDepto;
								?>
								<a href="clientes.php?<?= http_build_query($paramsDepto); ?>"
								   class="clientes-depto-link<?= $dptoActivo === $idDepto ? ' is-active' : ''; ?>">
									<span><?= htmlspecialchars($depto['dep_nombre']); ?></span>
									<span class="clientes-depto-count"><?= intval($conteoPorDepartamento[$idDepto] ?? 0); ?></span>
								</a>
							<?php } ?>
						</div>
					</aside>

					<div>
						<div class="clientes-panel">
							<div class="clientes-panel-header">
								<div>
									<h3>Listado de clientes</h3>
									<p>Consulte, filtre y acceda al detalle de cada registro</p>
								</div>
								<div class="clientes-panel-toolbar">
									<?php if ($listadoPermisos['imprimirInforme']) { ?>
										<a href="clientes-filtro.php" class="clientes-toolbar-btn"><i class="icon-print"></i> Informe</a>
									<?php } ?>
									<?php if ($listadoPermisos['exportarExcel']) { ?>
										<a href="excel_exportar/clientes-exportar.php?dpto=<?= isset($_GET['dpto']) ? intval($_GET['dpto']) : ''; ?>" target="_blank" class="clientes-toolbar-btn"><i class="icon-download"></i> Excel</a>
									<?php } ?>
									<?php if ($listadoPermisos['verPapelera']) { ?>
										<a href="clientes.php?pap=1" class="clientes-toolbar-btn"><i class="icon-trash"></i> Papelera</a>
									<?php } ?>
									<?php if ($listadoPermisos['cambiarClaves']) { ?>
										<a href="bd_update/clientes-actualizar-claves.php" class="clientes-toolbar-btn" onclick="return confirm('¿Desea ejecutar esta acción?');"><i class="icon-key"></i> Claves</a>
									<?php } ?>
								</div>
							</div>
							<div class="clientes-panel-body">

								<div class="clientes-chips-row">
									<?php
									$paramsTodos = array_diff_key($filtrosGetPreservados, ['categoria' => '', 'tipoDoc' => '', 'grupo' => '']);
									$categoriaActiva = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
									$tipoDocActivo   = isset($_GET['tipoDoc']) ? intval($_GET['tipoDoc']) : 0;
									$grupoActivo     = isset($_GET['grupo']) ? intval($_GET['grupo']) : 0;
									?>
									<div class="clientes-chips">
										<a href="clientes.php?<?= http_build_query($paramsTodos); ?>" class="clientes-chip<?= $categoriaActiva === 0 && $tipoDocActivo === 0 && $grupoActivo === 0 ? ' is-active' : ''; ?>">Todos</a>
										<?php
										$categoriasChip = [1 => 'Prospecto', 2 => 'Cliente', 3 => 'Dealer'];
										foreach ($categoriasChip as $catId => $catLabel) {
											$paramsCat = $filtrosGetPreservados;
											$paramsCat['categoria'] = $catId;
											?>
											<a href="clientes.php?<?= http_build_query($paramsCat); ?>" class="clientes-chip<?= $categoriaActiva === $catId ? ' is-active' : ''; ?>"><?= $catLabel; ?></a>
										<?php } ?>
										<a href="clientes.php?<?= http_build_query(array_merge($filtrosGetPreservados, ['tipoDoc' => 2])); ?>" class="clientes-chip<?= $tipoDocActivo === 2 ? ' is-active' : ''; ?>">NIT</a>
										<a href="clientes.php?<?= http_build_query(array_merge($filtrosGetPreservados, ['tipoDoc' => 3])); ?>" class="clientes-chip<?= $tipoDocActivo === 3 ? ' is-active' : ''; ?>">Cédula</a>
									</div>
									<div class="clientes-grupo-select">
										<label for="filtroGrupoCliente">Grupo</label>
										<select id="filtroGrupoCliente" name="grupo">
											<option value="">Todos los grupos</option>
											<?php foreach ($gruposDealerListado as $grupo) {
												$idGrupo = intval($grupo['deal_id']);
												$conteoGrupo = intval($conteoPorGrupoDealer[$idGrupo] ?? 0);
												?>
												<option value="<?= $idGrupo; ?>"<?= $grupoActivo === $idGrupo ? ' selected' : ''; ?>>
													<?= htmlspecialchars($grupo['deal_nombre']); ?> (<?= $conteoGrupo; ?>)
												</option>
											<?php } ?>
										</select>
									</div>
								</div>

								<?php
								$clientesFiltrosCampos = ['buscar', 'fecha_registro_inicio', 'fecha_registro_fin', 'fecha_ingreso_inicio', 'fecha_ingreso_fin'];
								$clientesFiltrosActivos = 0;
								foreach ($clientesFiltrosCampos as $campoFiltro) {
									if (isset($_GET[$campoFiltro]) && $_GET[$campoFiltro] !== '') {
										$clientesFiltrosActivos++;
									}
								}
								?>
								<div class="clientes-filtros-panel clientes-collapsible is-collapsed" data-collapsible="filtros">
									<button type="button" class="clientes-collapsible-toggle" id="clientes-filtros-toggle" aria-expanded="false" aria-controls="clientes-filtros-body">
										<span class="clientes-collapsible-heading">
											<span class="clientes-collapsible-title">Filtros de búsqueda</span>
											<?php if ($clientesFiltrosActivos > 0) { ?>
												<span class="clientes-collapsible-badge"><?= $clientesFiltrosActivos; ?> activo<?= $clientesFiltrosActivos === 1 ? '' : 's'; ?></span>
											<?php } else { ?>
												<span class="clientes-collapsible-summary">Buscar por nombre, documento o fechas</span>
											<?php } ?>
										</span>
										<i class="icon-chevron-down clientes-collapsible-icon" aria-hidden="true"></i>
									</button>
									<div class="clientes-collapsible-body" id="clientes-filtros-body" hidden>
										<form method="GET" action="">
											<?php foreach ($filtrosGetPreservados as $clave => $valor) {
												if (in_array($clave, ['fecha_registro_inicio', 'fecha_registro_fin', 'fecha_ingreso_inicio', 'fecha_ingreso_fin'], true)) {
													continue;
												}
												?>
												<input type="hidden" name="<?= htmlspecialchars($clave); ?>" value="<?= htmlspecialchars((string) $valor); ?>">
											<?php } ?>
											<div class="filtros-grid">
												<div class="filtro-item filtro-item--full">
													<label>Buscar <?php if (!empty($_GET['buscar'])) { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['buscar' => ''])); ?>">× quitar</a><?php } ?></label>
													<input type="text" name="buscar" id="btn_buscar" value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>" placeholder="Nombre o documento del cliente...">
												</div>
												<div class="filtro-item">
													<label>Creación inicio <?php if (!empty($_GET['fecha_registro_inicio'])) { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['fecha_registro_inicio' => ''])); ?>">×</a><?php } ?></label>
													<input type="date" name="fecha_registro_inicio" value="<?= isset($_GET['fecha_registro_inicio']) ? htmlspecialchars($_GET['fecha_registro_inicio']) : ''; ?>">
												</div>
												<div class="filtro-item">
													<label>Creación fin <?php if (!empty($_GET['fecha_registro_fin'])) { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['fecha_registro_fin' => ''])); ?>">×</a><?php } ?></label>
													<input type="date" name="fecha_registro_fin" value="<?= isset($_GET['fecha_registro_fin']) ? htmlspecialchars($_GET['fecha_registro_fin']) : ''; ?>">
												</div>
												<div class="filtro-item">
													<label>Ingreso cliente inicio <?php if (!empty($_GET['fecha_ingreso_inicio'])) { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['fecha_ingreso_inicio' => ''])); ?>">×</a><?php } ?></label>
													<input type="date" name="fecha_ingreso_inicio" value="<?= isset($_GET['fecha_ingreso_inicio']) ? htmlspecialchars($_GET['fecha_ingreso_inicio']) : ''; ?>">
												</div>
												<div class="filtro-item">
													<label>Ingreso cliente fin <?php if (!empty($_GET['fecha_ingreso_fin'])) { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['fecha_ingreso_fin' => ''])); ?>">×</a><?php } ?></label>
													<input type="date" name="fecha_ingreso_fin" value="<?= isset($_GET['fecha_ingreso_fin']) ? htmlspecialchars($_GET['fecha_ingreso_fin']) : ''; ?>">
												</div>
											</div>
											<div class="clientes-filtros-acciones">
												<button type="submit" class="clientes-btn-primary"><i class="icon-search"></i> Filtrar</button>
												<a href="clientes.php" class="clientes-btn-secondary">Limpiar filtros</a>
												<button type="button" class="clientes-btn-primary" id="btnSubmitBuscar"><i class="icon-search"></i> Buscar en vivo</button>
											</div>
										</form>
									</div>
								</div>

								<div class="clientes-pagination">
									<?php include("includes/clientes-listado-filtros.php"); ?>
									<?php include("includes/paginacion.php"); ?>
								</div>
								<p class="clientes-leyenda">TK = Tickets · SG = Seguimientos · SC = Sucursales · CT = Contactos · FC = Facturas · RM = Remisiones</p>

								<div class="clientes-table-wrap">
									<table class="clientes-table" id="data-table">
										<thead>
											<tr>
												<th>No</th>
												<th>Ubicación</th>
												<th>Información</th>
												<th>TK</th>
												<th>SG</th>
												<th>SC</th>
												<th>CT</th>
												<th>FC</th>
												<th>RM</th>
											</tr>
										</thead>
										<tbody id="clientes_buscar">
										<?php
										include("includes/clientes-listado-cargar.php");
										include("includes/clientes-listado-render-filas.php");
										?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php include __DIR__ . '/includes/clientes-listado-analytics.php'; ?>

			</div>
		</div>
	</div>

	<script>
		(function () {
			function toggleCollapsible(section) {
				var toggle = section.querySelector('.clientes-collapsible-toggle');
				var body = section.querySelector('.clientes-collapsible-body');
				if (!toggle || !body) return;

				var willExpand = section.classList.contains('is-collapsed');
				section.classList.toggle('is-collapsed', !willExpand);
				toggle.setAttribute('aria-expanded', willExpand ? 'true' : 'false');
				if (willExpand) {
					body.removeAttribute('hidden');
					if (section.id === 'clientesAnalytics' && typeof window.initClientesAnalyticsCharts === 'function') {
						window.initClientesAnalyticsCharts();
					}
				} else {
					body.setAttribute('hidden', '');
				}
			}

			document.querySelectorAll('.clientes-collapsible').forEach(function (section) {
				var toggle = section.querySelector('.clientes-collapsible-toggle');
				if (!toggle) return;
				toggle.addEventListener('click', function () {
					toggleCollapsible(section);
				});
			});

			var grupoSelect = document.getElementById('filtroGrupoCliente');
			if (grupoSelect) {
				grupoSelect.addEventListener('change', function () {
					var params = new URLSearchParams(window.location.search);
					if (this.value) {
						params.set('grupo', this.value);
					} else {
						params.delete('grupo');
					}
					params.delete('inicio');
					var query = params.toString();
					window.location.href = 'clientes.php' + (query ? '?' + query : '');
				});
			}

			var btnBuscar = document.getElementById('btnSubmitBuscar');
			var inputBuscar = document.getElementById('btn_buscar');
			if (btnBuscar) {
				btnBuscar.addEventListener('click', buscar);
			}
			if (inputBuscar) {
				inputBuscar.addEventListener('keydown', function (event) {
					if (event.key === 'Enter') {
						event.preventDefault();
						buscar();
					}
				});
			}

			function buscar() {
				var valor = document.getElementById('btn_buscar').value;
				var tbody = document.getElementById('clientes_buscar');
				tbody.innerHTML = '';

				var params = new URLSearchParams(window.location.search);
				params.set('buscar', valor);
				params.set('inicio', '<?= isset($_GET["inicio"]) ? intval($_GET["inicio"]) : 1 ?>');
				params.set('limite', '<?= intval($limite ?? ($configuracion['conf_paginacion'] ?? 50)) ?>');

				fetch('fetch-buscar-clientes.php?' + params.toString(), { method: 'GET' })
					.then(function (response) { return response.text(); })
					.then(function (data) { tbody.innerHTML = data; })
					.catch(function (error) { console.error('Error:', error); });
			}
		})();
	</script>

	<?php include("includes/drawer-notas-internas-cliente.php"); ?>
	<script src="js/clientes-listado-charts.js"></script>
	<?php include("includes/pie.php"); ?>
</body>
</html>
