<?php 
include("sesion.php");

$idPagina = 88;
$paginaActual['pag_nombre'] = "Tickets de clientes";
include("includes/verificar-paginas.php");
include("includes/head.php");

if (!empty($_GET["cte"])) {
	$consultaDatos=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes 
	WHERE cli_id='".$_GET["cte"]."' AND cli_id_empresa='".$idEmpresa."'");
	$cliente = mysqli_fetch_array($consultaDatos, MYSQLI_BOTH);
}

require_once RUTA_PROYECTO.'/usuarios/class/Tickets.php';
?>
<!-- styles -->


<link href="css/tablecloth.css" rel="stylesheet">
<style>
	/* KPIs tickets - tarjetas elegantes */
	.tickets-kpi-wrap { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 24px; }
	.tickets-kpi-card { flex: 1; min-width: 220px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.06); transition: box-shadow .2s; }
	.tickets-kpi-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); }
	.tickets-kpi-card.efectivos { border-left: 4px solid #059669; }
	.tickets-kpi-card.no-efectivos { border-left: 4px solid #dc2626; }
	.tickets-kpi-card .kpi-label { font-size: 13px; color: #64748b; margin-bottom: 8px; font-weight: 500; }
	.tickets-kpi-card .kpi-value { font-size: 28px; font-weight: 700; line-height: 1.2; }
	.tickets-kpi-card.efectivos .kpi-value { color: #059669; }
	.tickets-kpi-card.no-efectivos .kpi-value { color: #dc2626; }
	.tickets-kpi-card .kpi-bar-wrap { height: 8px; background: #f1f5f9; border-radius: 4px; margin-top: 12px; overflow: hidden; }
	.tickets-kpi-card .kpi-bar { height: 100%; border-radius: 4px; transition: width .5s ease; }
	.tickets-kpi-card.efectivos .kpi-bar { background: linear-gradient(90deg, #059669, #10b981); }
	.tickets-kpi-card.no-efectivos .kpi-bar { background: linear-gradient(90deg, #dc2626, #ef4444); }
	.tickets-kpi-card .kpi-sub { font-size: 11px; color: #94a3b8; margin-top: 6px; }
	/* Panel de filtros */
	.tickets-filtros-panel { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px; margin-bottom: 24px; }
	.tickets-filtros-panel .filtros-titulo { font-size: 15px; font-weight: 600; color: #334155; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px; }
	.tickets-filtros-panel .filtros-titulo:before { content: ""; display: inline-block; width: 4px; height: 18px; background: #2563eb; border-radius: 2px; }
	.tickets-filtros-panel .filtros-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 14px 20px; }
	.tickets-filtros-panel .filtro-item label { display: block; font-size: 11px; color: #64748b; margin-bottom: 4px; font-weight: 500; }
	.tickets-filtros-panel .filtro-item label .quitar { color: #dc2626; text-decoration: none; margin-left: 4px; }
	.tickets-filtros-panel .filtro-item label .quitar:hover { text-decoration: underline; }
	.tickets-filtros-panel .filtro-item input,
	.tickets-filtros-panel .filtro-item select { width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; }
	.tickets-filtros-panel .filtros-acciones { margin-top: 18px; padding-top: 16px; border-top: 1px solid #e2e8f0; display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
	.tickets-filtros-panel .btn-filtrar { padding: 8px 18px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; }
	.tickets-filtros-panel .btn-filtrar:hover { background: #1d4ed8; }
	.tickets-filtros-panel .btn-limpiar { padding: 8px 18px; background: #fff; color: #475569; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; font-size: 13px; }
	.tickets-filtros-panel .btn-limpiar:hover { background: #f1f5f9; color: #334155; }
	@media (max-width: 768px) { .tickets-filtros-panel .filtros-grid { grid-template-columns: 1fr 1fr; } }
</style>

<!--============j avascript===========-->
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
<script type="text/javascript">
	$(function() {
		$('#data-table').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
		});
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
</script>

<?php include("includes/funciones-js.php"); ?>
</head>

<body>
	<div class="layout">
		<?php include("includes/encabezado.php"); ?>

		
		<div class="main-wrapper">
			<div class="container-fluid">
				<div class="row-fluid ">
					<div class="span12">
						<div class="primary-head">
							<h3 class="page-header"><?=$paginaActual['pag_nombre'];?> de <b><?=$cliente['cli_nombre'];?></b></h3>
						</div>	
					</div>
				</div>

				<?php
				$totalTicketsComerciales = Ticket::obtenerTotalTicketsComerciales($conexionBdPrincipal);
				$ticketsComercialesEfectivos = Ticket::obtenerTicketsComercialesEfectivos($conexionBdPrincipal);
				$porcentajeEfectivo = $totalTicketsComerciales > 0 ? round(($ticketsComercialesEfectivos / $totalTicketsComerciales) * 100, 1) : 0;
				$ticketsComercialesNoEfectivos = Ticket::obtenerTicketsComercialesNoEfectivos($conexionBdPrincipal);
				$porcentajeNoEfectivo = $totalTicketsComerciales > 0 ? round(($ticketsComercialesNoEfectivos / $totalTicketsComerciales) * 100, 1) : 0;
				?>
				<div class="tickets-kpi-wrap">
					<div class="tickets-kpi-card efectivos">
						<div class="kpi-label">Tickets comerciales efectivos</div>
						<div class="kpi-value"><?= $porcentajeEfectivo; ?>%</div>
						<div class="kpi-bar-wrap"><div class="kpi-bar" style="width: <?= min($porcentajeEfectivo, 100); ?>%;"></div></div>
						<div class="kpi-sub"><?= $ticketsComercialesEfectivos; ?> de <?= $totalTicketsComerciales; ?> tickets</div>
					</div>
					<div class="tickets-kpi-card no-efectivos">
						<div class="kpi-label">Tickets comerciales no efectivos</div>
						<div class="kpi-value"><?= $porcentajeNoEfectivo; ?>%</div>
						<div class="kpi-bar-wrap"><div class="kpi-bar" style="width: <?= min($porcentajeNoEfectivo, 100); ?>%;"></div></div>
						<div class="kpi-sub"><?= $ticketsComercialesNoEfectivos; ?> de <?= $totalTicketsComerciales; ?> tickets</div>
					</div>
				</div>

				<?php include("includes/notificaciones.php");?>
				<p>
					<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
					<?php if( Modulos::validarRol(['89'], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) ) {?>
						<a href="clientes-tikets-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
					<?php }?>
				</p>

				<div class="row-fluid">
					<div class="span12">
						<div class="content-widgets light-gray">
							<div class="widget-head green">
								<h3><?= $paginaActual['pag_nombre']; ?></h3>
							</div>

							<?php
							$filtro = "";
							if (isset($_GET["busqueda"]) and $_GET["busqueda"] != "") {
								$filtro .= " AND (tik_id LIKE '%" . $_GET["busqueda"] . "%' OR tik_asunto_principal LIKE '%" . $_GET["busqueda"] . "%')";
							}
							if (isset($_GET["cte"]) and $_GET["cte"] != "") {
								$filtro .= " AND tik_cliente='" . $_GET["cte"] . "'";
							}
							if (isset($_GET["resp"]) and $_GET["resp"] != "") {
								$filtro .= " AND tik_usuario_responsable='" . $_GET["resp"] . "'";
							}
							if (isset($_GET["tipo"]) and $_GET["tipo"] != "") {
								$filtro .= " AND tik_tipo_tiket='" . $_GET["tipo"] . "'";
							}
							if (isset($_GET["estado"]) and $_GET["estado"] != "") {
								$filtro .= " AND tik_estado='" . $_GET["estado"] . "'";
							}
							if (isset($_GET["prioridad"]) and $_GET["prioridad"] != "") {
								$filtro .= " AND tik_prioridad='" . $_GET["prioridad"] . "'";
							}
							if (isset($_GET["etapa"]) and $_GET["etapa"] != "") {
								$filtro .= " AND tik_etapa='" . $_GET["etapa"] . "'";
							}
							if (isset($_GET["fecha_inicio"]) and $_GET["fecha_inicio"] != "") {
								$filtro .= " AND tik_fecha_creacion >= '" . $_GET["fecha_inicio"] . " 00:00:00'";
							}
							if (isset($_GET["fecha_fin"]) and $_GET["fecha_fin"] != "") {
								$filtro .= " AND tik_fecha_creacion <= '" . $_GET["fecha_fin"] . " 23:59:59'";
							}
							if(Modulos::validarRol([385], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
								$filtro.=' AND cli_ciudad!="1122"';
							}
							?>

							<!-- Panel de filtros -->
							<div class="tickets-filtros-panel">
								<form method="GET" action="">
									<?php if(isset($_GET["cte"])) { ?><input type="hidden" name="cte" value="<?= htmlspecialchars($_GET["cte"]); ?>"><?php } ?>
									<div class="filtros-titulo">Filtros</div>
									<div class="filtros-grid">
										<div class="filtro-item" style="grid-column: 1 / -1;">
											<label>Buscar por ID o asunto <?php if(isset($_GET["busqueda"]) && $_GET["busqueda"]!="") { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['busqueda' => ''])); ?>">× quitar</a><?php } ?></label>
											<input type="text" name="busqueda" value="<?= isset($_GET["busqueda"]) ? htmlspecialchars($_GET["busqueda"]) : ""; ?>" placeholder="ID ticket o asunto...">
										</div>
										<div class="filtro-item">
											<label>Estado <?php if(isset($_GET["estado"]) && $_GET["estado"]!="") { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['estado' => ''])); ?>">×</a><?php } ?></label>
											<select name="estado">
												<option value="">Todos</option>
												<option value="1" <?= (isset($_GET["estado"]) && $_GET["estado"]=="1") ? "selected" : ""; ?>>Abierto</option>
												<option value="2" <?= (isset($_GET["estado"]) && $_GET["estado"]=="2") ? "selected" : ""; ?>>Cerrado</option>
											</select>
										</div>
										<div class="filtro-item">
											<label>Responsable <?php if(isset($_GET["resp"]) && $_GET["resp"]!="") { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['resp' => ''])); ?>">×</a><?php } ?></label>
											<select name="resp">
												<option value="">Todos</option>
												<?php
												$conResp = mysqli_query($conexionBdPrincipal, "SELECT usr_id, usr_nombre FROM usuarios WHERE usr_bloqueado != 1 ORDER BY usr_nombre");
												while ($resResp = mysqli_fetch_array($conResp)) {
													$sel = (isset($_GET["resp"]) && $_GET["resp"] == $resResp['usr_id']) ? ' selected' : '';
													echo '<option value="' . (int)$resResp['usr_id'] . '"' . $sel . '>' . htmlspecialchars($resResp['usr_nombre']) . '</option>';
												}
												?>
											</select>
										</div>
										<div class="filtro-item">
											<label>Prioridad <?php if(isset($_GET["prioridad"]) && $_GET["prioridad"]!="") { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['prioridad' => ''])); ?>">×</a><?php } ?></label>
											<select name="prioridad">
												<option value="">Todas</option>
												<option value="1" <?= (isset($_GET["prioridad"]) && $_GET["prioridad"]=="1") ? "selected" : ""; ?>>Normal</option>
												<option value="2" <?= (isset($_GET["prioridad"]) && $_GET["prioridad"]=="2") ? "selected" : ""; ?>>Urgente</option>
												<option value="3" <?= (isset($_GET["prioridad"]) && $_GET["prioridad"]=="3") ? "selected" : ""; ?>>Muy Urgente</option>
											</select>
										</div>
										<div class="filtro-item">
											<label>Tipo <?php if(isset($_GET["tipo"]) && $_GET["tipo"]!="") { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['tipo' => ''])); ?>">×</a><?php } ?></label>
											<select name="tipo">
												<option value="">Todos</option>
												<option value="1" <?= (isset($_GET["tipo"]) && $_GET["tipo"]=="1") ? "selected" : ""; ?>>Comercial</option>
												<option value="3" <?= (isset($_GET["tipo"]) && $_GET["tipo"]=="3") ? "selected" : ""; ?>>Soporte operativo</option>
											</select>
										</div>
										<div class="filtro-item">
											<label>Etapa <?php if(isset($_GET["etapa"]) && $_GET["etapa"]!="") { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['etapa' => ''])); ?>">×</a><?php } ?></label>
											<select name="etapa">
												<option value="">Todas</option>
												<?php for($i=1; $i<=6; $i++){ ?>
													<option value="<?= $i; ?>" <?= (isset($_GET["etapa"]) && $_GET["etapa"]==$i) ? "selected" : ""; ?>><?= $opcionesEtapa[$i]; ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="filtro-item">
											<label>Fecha inicio <?php if(isset($_GET["fecha_inicio"]) && $_GET["fecha_inicio"]!="") { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['fecha_inicio' => ''])); ?>">×</a><?php } ?></label>
											<input type="date" name="fecha_inicio" value="<?= isset($_GET["fecha_inicio"]) ? htmlspecialchars($_GET["fecha_inicio"]) : ""; ?>">
										</div>
										<div class="filtro-item">
											<label>Fecha fin <?php if(isset($_GET["fecha_fin"]) && $_GET["fecha_fin"]!="") { ?><a class="quitar" href="?<?= http_build_query(array_diff_key($_GET, ['fecha_fin' => ''])); ?>">×</a><?php } ?></label>
											<input type="date" name="fecha_fin" value="<?= isset($_GET["fecha_fin"]) ? htmlspecialchars($_GET["fecha_fin"]) : ""; ?>">
										</div>
									</div>
									<div class="filtros-acciones">
										<button type="submit" class="btn-filtrar">Aplicar filtros</button>
										<a href="?<?= isset($_GET["cte"]) ? "cte=".htmlspecialchars($_GET["cte"]) : ""; ?>" class="btn-limpiar">Limpiar todos</a>
									</div>
								</form>
							</div>

							<?php
							if (Modulos::validarRol([384], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
								$SQL = "SELECT * FROM clientes_tikets
								INNER JOIN clientes ON cli_id=tik_cliente
								INNER JOIN usuarios ON usr_id=tik_usuario_responsable
								WHERE tik_id=tik_id $filtro
								ORDER BY tik_id DESC
								";
							} else {
								$SQL = "SELECT * FROM clientes_tikets
								INNER JOIN clientes ON cli_id=tik_cliente
								INNER JOIN usuarios ON usr_id=tik_usuario_responsable
								WHERE tik_usuario_responsable='" . $_SESSION["id"] . "' $filtro
								ORDER BY tik_id DESC
								";
							}
							?>

							<p style="margin: 10px;"><?php include("includes/paginacion.php"); ?></p>

							<div class="widget-container">
								<?php include("includes/notificaciones.php"); ?>
								<p></p>
								<table class="table table-striped table-bordered" id="data-table" style="font-size: 10px;">
									<thead>
										<tr>
											<th>No</th>
											<th>ID</th>
											<th>Tipo</th>
											<th>F. Inicio</th>
											<th>Cliente</th>
											<th>Sucursal</th>
											<th>Asunto</th>
											<th>Resposable</th>
											<th>Nro. Cotización</th>
											<th>Estado</th>
											<th>Etapa</th>
											<th>Prioridad</th>
											<th>Seg.</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if (Modulos::validarRol([384], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
											$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_tikets
											INNER JOIN clientes ON cli_id=tik_cliente
											INNER JOIN usuarios ON usr_id=tik_usuario_responsable
											WHERE tik_id=tik_id $filtro
											ORDER BY tik_id DESC
											LIMIT $inicio, $limite
											");
										} else {
											$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_tikets
											INNER JOIN clientes ON cli_id=tik_cliente
											INNER JOIN usuarios ON usr_id=tik_usuario_responsable
											WHERE tik_usuario_responsable='" . $_SESSION["id"] . "' $filtro
											ORDER BY tik_id DESC
											LIMIT $inicio, $limite
											");
										}
										$no = 1;
										while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {

											$consultaAsuntos=mysqli_query($conexionBdPrincipal,"SELECT * FROM tikets_asuntos WHERE tkpas_id='" . $res["tik_asunto_principal"] . "'");
											$asuntos = mysqli_fetch_array($consultaAsuntos, MYSQLI_BOTH);

											if (!Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
												$consultaNumZ=mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='" . $_SESSION["id"] . "' AND zpu_zona='" . $res['cli_zona'] . "'");
												$numZ = mysqli_num_rows($consultaNumZ);
												if ($numZ == 0) continue;
											}

											$consultaSucursal=mysqli_query($conexionBdPrincipal,"SELECT * FROM sucursales WHERE sucu_id='" . $res['tik_sucursal'] . "'");
											$sucursal = mysqli_fetch_array($consultaSucursal, MYSQLI_BOTH);
											switch ($res['tik_tipo_tiket']) {
												case 1:
													$tipoS = 'Comercial';
													$etiquetaT = 'success';
													break;
												case 3:
													$tipoS = 'Soporte operativo';
													$etiquetaT = 'important';
													break;
											}

											switch ($res['tik_estado']) {
												case 1:
													$estado = 'Abierto';
													$etiquetaE = 'important';
													break;
												case 2:
													$estado = 'Cerrado';
													$etiquetaE = 'info';
													break;
											}

											switch ($res['tik_prioridad']) {
												case 1:
													$prioridad = 'Normal';
													$etiquetaP = 'success';
													break;
												case 2:
													$prioridad = 'Urgente';
													$etiquetaP = 'warning';
													break;
												case 3:
													$prioridad = 'Muy Urgente';
													$etiquetaP = 'important';
													break;
											}

											$consultaNumeros=mysqli_query($conexionBdPrincipal,"SELECT (SELECT count(cseg_id) FROM cliente_seguimiento WHERE cseg_tiket='" . $res['tik_id'] . "') ");
											$numeros = mysqli_fetch_array($consultaNumeros, MYSQLI_BOTH);
										?>
											<tr>
												<td><?= $no; ?></td>
												<td><?= $res['tik_id']; ?></td>
												<td><span class="badge badge-<?= $etiquetaT; ?>"><?= $tipoS; ?></span></td>
												<td><?= $res['tik_fecha_creacion']; ?></td>
												<td>
													<?php echo "<b>Nombre</b>:" . $res['cli_nombre']; ?>
													<?php if ($res['cli_telefono'] != "") echo "<br><b>Tel:</b> " . $res['cli_telefono']; ?>
													<?php if ($res['cli_email'] != "") echo "<br><b>Email:</b> " . $res['cli_email']; ?>
												</td>
												<td><?= $sucursal['sucu_nombre']; ?></td>
												<td><?= $res['tik_asunto_principal']; ?></td>
												<td><?= $res['usr_nombre'];?></td>
												<td><a href='cotizaciones-editar.php?id=<?= $res['tik_id_cotizacion'];?>'><?= $res['tik_id_cotizacion'];?></a></td>
												<td><span class="label label-<?= $etiquetaE; ?>"><?= $estado; ?></span></td>
												<td><?= $opcionesEtapa[$res['tik_etapa']]; ?></td>
												<td><span class="label label-<?= $etiquetaP; ?>"><?= $prioridad; ?></span></td>
												<td align="center" style="background:<?= $color2; ?>;">
												<?php if( Modulos::validarRol(['12'], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) ) {?>
													<a href="clientes-seguimiento.php?idTK=<?= $res[0]; ?>" target="_blank"><?= $numeros[0]; ?></a>
												<?php } else {?>
													<?= $numeros[0]; ?>
												<?php }?>
												</td>
												<td>
													<h4>
														<?php if( Modulos::validarRol(['90'], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) ) {?>
															<a href="clientes-tikets-editar.php?id=<?= $res[0]; ?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
														<?php }?>

														<?php if( Modulos::validarRol(['91'], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && false) {?>
															<a href="bd_delete/clientes-tikets-eliminar.php?id=<?=$res[0];?>&cte=<?=$_GET["cte"];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
														<?php }?>
													</h4>
												</td>
											</tr>
										<?php $no++;
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>


			</div>
		</div>
	</div>
	<?php include("includes/pie.php"); ?>
	</div>
</body>

</html>
