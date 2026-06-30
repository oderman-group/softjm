<?php 
include("sesion.php");

$idPagina = 88;
$paginaActual['pag_nombre'] = "Tickets de clientes";
include("includes/verificar-paginas.php");
include("includes/head.php");

require_once RUTA_PROYECTO . '/usuarios/class/Tickets.php';
require_once RUTA_PROYECTO . '/usuarios/includes/clientes-tikets-preparar.php';
?>
<link href="css/clientes-tikets.css" rel="stylesheet">

<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/dataTables.bootstrap.js"></script>
<script src="js/custom.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script type="text/javascript">
	$(function() {
		$('#data-table').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t",
			"bPaginate": false,
			"bInfo": false,
			"aaSorting": []
		});
	});
</script>

<?php include("includes/funciones-js.php"); ?>
</head>

<body class="tickets-page">
	<div class="layout">
		<?php include("includes/encabezado.php"); ?>

		<div class="main-wrapper">
			<div class="container-fluid tickets-page-inner">

				<div class="tickets-hero">
					<div>
						<h1 class="tickets-hero-title">Tickets de clientes</h1>
						<p class="tickets-hero-subtitle">
							<?php if ($clienteIdPagina > 0) { ?>
								Seguimiento comercial de <strong><?= htmlspecialchars($cliente['cli_nombre'] ?? ''); ?></strong>
							<?php } else { ?>
								Vista general de oportunidades y seguimientos comerciales
							<?php } ?>
						</p>
					</div>
					<div class="tickets-hero-actions">
						<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
						<?php if ($ticketsPermisos['agregarTicket'] && $clienteIdPagina > 0) { ?>
							<a href="clientes-tikets-agregar.php?cte=<?= $clienteIdPagina; ?>" class="btn btn-danger"><i class="icon-plus"></i> Nuevo ticket</a>
						<?php } ?>
					</div>
				</div>

				<div class="tickets-kpi-grid">
					<div class="tickets-kpi-card is-primary">
						<div class="tickets-kpi-label">Tickets <?= $ticketsAnioActual; ?></div>
						<div class="tickets-kpi-value"><?= intval($ticketsResumenAnual['total'] ?? 0); ?></div>
						<div class="tickets-kpi-hint">Creados en el año en curso</div>
					</div>
					<div class="tickets-kpi-card is-warning">
						<div class="tickets-kpi-label">Abiertos</div>
						<div class="tickets-kpi-value"><?= intval($ticketsResumenAnual['abiertos'] ?? 0); ?></div>
						<div class="tickets-kpi-hint">Requieren gestión activa</div>
					</div>
					<div class="tickets-kpi-card is-info">
						<div class="tickets-kpi-label">Con cotización</div>
						<div class="tickets-kpi-value"><?= intval($ticketsResumenAnual['con_cotizacion'] ?? 0); ?></div>
						<div class="tickets-kpi-hint">Oportunidades con propuesta</div>
					</div>
					<div class="tickets-kpi-card is-success">
						<div class="tickets-kpi-label">Efectividad comercial</div>
						<div class="tickets-kpi-value"><?= $porcentajeEfectivo; ?>%</div>
						<div class="tickets-kpi-hint"><?= $ticketsComercialesEfectivos; ?> ganados de <?= $totalTicketsComerciales; ?> cerrados</div>
					</div>
					<div class="tickets-kpi-card is-danger">
						<div class="tickets-kpi-label">No efectivos</div>
						<div class="tickets-kpi-value"><?= $porcentajeNoEfectivo; ?>%</div>
						<div class="tickets-kpi-hint"><?= $ticketsComercialesNoEfectivos; ?> tickets perdidos</div>
					</div>
				</div>

				<?php include("includes/notificaciones.php"); ?>

				<div class="tickets-panel">
					<div class="tickets-panel-header">
						<div>
							<h3>Listado de tickets</h3>
							<p>Consulte, filtre y abra el detalle rápido desde cada registro</p>
						</div>
					</div>
					<div class="tickets-panel-body">

						<div class="tickets-filtros-panel">
							<form method="GET" action="">
								<?php if ($clienteIdPagina > 0) { ?><input type="hidden" name="cte" value="<?= $clienteIdPagina; ?>"><?php } ?>
								<div class="filtros-titulo">Filtros de búsqueda</div>
								<div class="filtros-grid">
									<div class="filtro-item filtro-item--full">
										<label>Buscar <?php if (!empty($_GET['busqueda'])) { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['busqueda' => ''])); ?>">× quitar</a><?php } ?></label>
										<input type="text" name="busqueda" value="<?= isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>" placeholder="ID de ticket o asunto...">
									</div>
									<div class="filtro-item">
										<label>Estado</label>
										<select name="estado">
											<option value="">Todos</option>
											<option value="1" <?= (isset($_GET['estado']) && $_GET['estado'] == '1') ? 'selected' : ''; ?>>Abierto</option>
											<option value="2" <?= (isset($_GET['estado']) && $_GET['estado'] == '2') ? 'selected' : ''; ?>>Cerrado</option>
										</select>
									</div>
									<div class="filtro-item filtro-item--wide">
										<label>Responsable</label>
										<select name="resp">
											<option value="">Todos</option>
											<?php foreach ($usuariosFiltroTickets as $resResp) {
                                                $sel = (isset($_GET['resp']) && $_GET['resp'] == $resResp['usr_id']) ? ' selected' : '';
                                                echo '<option value="' . (int)$resResp['usr_id'] . '"' . $sel . '>' . htmlspecialchars($resResp['usr_nombre']) . '</option>';
                                            } ?>
										</select>
									</div>
									<div class="filtro-item">
										<label>Prioridad</label>
										<select name="prioridad">
											<option value="">Todas</option>
											<option value="1" <?= (isset($_GET['prioridad']) && $_GET['prioridad'] == '1') ? 'selected' : ''; ?>>Normal</option>
											<option value="2" <?= (isset($_GET['prioridad']) && $_GET['prioridad'] == '2') ? 'selected' : ''; ?>>Urgente</option>
											<option value="3" <?= (isset($_GET['prioridad']) && $_GET['prioridad'] == '3') ? 'selected' : ''; ?>>Muy urgente</option>
										</select>
									</div>
									<div class="filtro-item">
										<label>Tipo</label>
										<select name="tipo">
											<option value="">Todos</option>
											<option value="1" <?= (isset($_GET['tipo']) && $_GET['tipo'] == '1') ? 'selected' : ''; ?>>Comercial</option>
											<option value="3" <?= (isset($_GET['tipo']) && $_GET['tipo'] == '3') ? 'selected' : ''; ?>>Soporte operativo</option>
										</select>
									</div>
									<div class="filtro-item filtro-item--wide">
										<label>Etapa</label>
										<select name="etapa">
											<option value="">Todas</option>
											<?php for ($i = 1; $i <= 6; $i++) { ?>
												<option value="<?= $i; ?>" <?= (isset($_GET['etapa']) && $_GET['etapa'] == $i) ? 'selected' : ''; ?>><?= $opcionesEtapa[$i]; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="filtro-item">
										<label>Desde</label>
										<input type="date" name="fecha_inicio" value="<?= isset($_GET['fecha_inicio']) ? htmlspecialchars($_GET['fecha_inicio']) : ''; ?>">
									</div>
									<div class="filtro-item">
										<label>Hasta</label>
										<input type="date" name="fecha_fin" value="<?= isset($_GET['fecha_fin']) ? htmlspecialchars($_GET['fecha_fin']) : ''; ?>">
									</div>
								</div>
								<div class="tickets-filtros-acciones">
									<button type="submit" class="tickets-btn-primary">Aplicar filtros</button>
									<a href="?<?= $clienteIdPagina > 0 ? 'cte=' . $clienteIdPagina : ''; ?>" class="tickets-btn-secondary">Limpiar</a>
								</div>
							</form>
						</div>

						<div class="tickets-pagination"><?php include("includes/paginacion.php"); ?></div>

						<div class="tickets-table-wrap">
							<table class="tickets-table" id="data-table">
								<thead>
									<tr>
										<th>No</th>
										<th>ID</th>
										<th>Tipo</th>
										<th>Fecha</th>
										<?php if ($clienteIdPagina <= 0) { ?><th>Cliente</th><?php } ?>
										<th>Sucursal</th>
										<th>Asunto</th>
										<th>Responsable</th>
										<th>Cotización</th>
										<th>Estado</th>
										<th class="col-etapa">Etapa</th>
										<th>Prioridad</th>
										<th>Seg.</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
                                    $consultaTickets = $conexionBdPrincipal->query(
                                        Ticket::sqlFilasListado(
                                            $ticketsWhere,
                                            $usuarioIdTickets,
                                            $ticketsPermisos['verTodos'],
                                            $ticketsPermisos['restringirZona'],
                                            intval($inicio),
                                            intval($limite)
                                        )
                                    );
                                    $filasTickets = [];
                                    while ($consultaTickets && ($filaTicket = mysqli_fetch_array($consultaTickets, MYSQLI_ASSOC))) {
                                        $filasTickets[] = $filaTicket;
                                    }
                                    include __DIR__ . '/includes/clientes-tikets-render-filas.php';
                                    ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<?php include __DIR__ . '/includes/clientes-tikets-analytics.php'; ?>

			</div>
		</div>
	</div>

	<?php include("includes/drawer-seguimiento-cliente.php"); ?>
	<?php include("includes/drawer-ticket-seguimientos-cliente.php"); ?>
	<?php include("includes/drawer-ticket-cliente.php"); ?>
	<?php include("includes/drawer-cotizacion-cliente.php"); ?>
	<script src="js/clientes-tikets-charts.js"></script>
	<?php include("includes/pie.php"); ?>
</body>
</html>
