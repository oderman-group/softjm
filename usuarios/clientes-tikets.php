<?php 
include("sesion.php");

$idPagina = 88;
$paginaActual['pag_nombre'] = "Tickets de clientes";
include("includes/verificar-paginas.php");
include("includes/head.php");
$consultaDatos=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_GET["cte"]."'");
$cliente = mysqli_fetch_array($consultaDatos, MYSQLI_BOTH);
?>
<!-- styles -->


<link href="css/tablecloth.css" rel="stylesheet">

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
						<ul class="breadcrumb">
							<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
							<li><a href="clientes.php">Clientes</a><span class="divider"><i class="icon-angle-right"></i></span></li>
							<li class="active"><?=$paginaActual['pag_nombre'];?> de <b><?=$cliente['cli_nombre'];?></b></li>
						</ul>
					</div>
				</div>
				<?php include("includes/notificaciones.php");?>
				<p>
					<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
					<a href="clientes-tikets-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
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
							?>

							<?php
							if ($datosUsuarioActual[3] == 1) {
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
											<th>Estado</th>
											<th>Prioridad</th>
											<th>Seg.</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($datosUsuarioActual[3] == 1) {
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

											if ($datosUsuarioActual[3] != 1) {
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
												<td><?= $res['usr_nombre']; ?></td>
												<td><a href="sql.php?get=29&id=<?= $res[0]; ?>" onClick="if(!confirm('Recuerde completar todos los seguimientos pendientes, en caso de tenerlos, antes de cerrar el ticket. Desea continuar con el cierre del ticket?')){return false;}"><span class="label label-<?= $etiquetaE; ?>"><?= $estado; ?></span></a></td>
												<td><span class="label label-<?= $etiquetaP; ?>"><?= $prioridad; ?></span></td>
												<td align="center" style="background:<?= $color2; ?>;"><a href="clientes-seguimiento.php?idTK=<?= $res[0]; ?>" target="_blank"><?= $numeros[0]; ?></a></td>
												<td>
													<h4>
														<!--
                                	<a href="clientes-seguimiento.php?idTK=<?= $res[0]; ?>&emg=1" data-toggle="tooltip" title="Seguimiento" target="new"><i class="icon-list-alt"></i></a>
									-->

														<a href="clientes-tikets-editar.php?id=<?= $res[0]; ?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
														<a href="bd_delete/clientes-tikets-eliminar.php?id=<?=$res[0];?>&cte=<?=$_GET["cte"];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
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