<?php 
include("sesion.php");

$idPagina = 77;

include("verificar-paginas.php");
include("head.php");
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
<script src="js/bootbox.js"></script>

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

	function confirmar(e) {
		bootbox.confirm("Desea enviar esta cotización?", function(result) {
			var id = e.id;
			var cont = e.name;
			if (result == true) {
				window.location.href = "sql.php?get=44&id=" + id + "&cont=" + cont;
			}
		})
	};
</script>
</head>

<body>
	<div class="layout">
		<?php include("encabezado.php"); ?>
		<div class="main-wrapper">
			<div class="container-fluid">
				<?php include("notificaciones.php"); ?>

				<p>
					<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
					<a href="cotizaciones-agregar.php?cte='<?php if(isset($_GET['cte'])) echo $_GET['cte'];?>'" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
					<a href="cotizaciones.php?dctoEspecial=1" class="btn btn-warning"><i class="icon-ok-sign"></i> Cotizaciones con descuentos especiales</a>
				</p>

				<div class="row-fluid">
					<div class="span12">
						<div class="content-widgets light-gray">
							<div class="widget-head green">
								<h3><?= $paginaActual['pag_nombre']; ?></h3>
							</div>
							<div class="widget-container">
								<p></p>

								<?php
								$filtro = '';
								if(isset($_GET['q'])){
									if ($_GET["q"] != "") {
										$filtro .= " AND cotiz_id='" . $_GET["q"] . "'";
									}
								}

								if(isset($_GET['dctoEspecial'])){
									if ($_GET["dctoEspecial"] != "") {
										$filtro .= " AND cotiz_descuentos_especiales=1";
									}
								}

								//Consulta de contar registros Solo para paginación
								if (isset($_GET["cte"]) and $_GET["cte"] != "") {
									$SQL = "SELECT cotiz_id FROM cotizacion
								INNER JOIN clientes ON cli_id=cotiz_cliente AND cli_id='" . $_GET["cte"] . "'
								ORDER BY cotiz_id DESC
								";
								} else {
									$SQL = "SELECT cotiz_id FROM cotizacion
								INNER JOIN clientes ON cli_id=cotiz_cliente
								INNER JOIN usuarios ON usr_id=cotiz_creador
								WHERE cotiz_id=cotiz_id $filtro
								ORDER BY cotiz_id DESC
								";
								}
								?>

								<p style="margin: 10px;"><?php include("paginacion.php"); ?></p>

								<div style="border:thin; border-style:solid; height:150px; margin:10px;">
									<h4 align="center">-Búsqueda por ID-</h4>
									<p>
										<form class="form-horizontal" action="<?= $_SERVER['PHP_SELF']; ?>" method="get">
											<div class="search-box">
												<div class="input-append input-icon">
													<input class="search-input" placeholder="ID..." type="text" name="q" value="<?php if(isset($_GET['q'])) echo $_GET['q'];?>">
													<i class=" icon-search"></i>
													<input class="btn" type="submit" name="buscar" value="Buscar">
												</div>
												<?php if(isset($_GET['q'])){ if ($_GET["q"] != "") { ?> <a href="<?= $_SERVER['PHP_SELF']; ?>" class="btn btn-warning"><i class="icon-minus"></i> Quitar Filtro</a> <?php }} ?>
											</div>
										</form>
									</p>
								</div>

								<table class="table table-striped table-bordered" id="data-table">
									<thead>
										<tr>
										<th>No.</th>
											<th>ID</th>
											<th>Fecha Propuesta</th>
											<th>Cliente</th>
											<th>Descripción</th>
											<th>Responsable</th>
											<th>Vendedor</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if (isset($_GET["cte"]) and $_GET["cte"] != "") {
											$consulta = $conexionBdPrincipal->query("SELECT cotiz_id, cotiz_fecha_propuesta, cotiz_creador, cotiz_vendedor, cotiz_vendida, cli_id, cli_nombre, cli_zona
												FROM cotizacion
								INNER JOIN clientes ON cli_id=cotiz_cliente AND cli_id='" . $_GET["cte"] . "'
								ORDER BY cotiz_id DESC
								LIMIT $inicio, $limite
								");
										} else {
											$consulta = $conexionBdPrincipal->query("SELECT cotiz_id, cotiz_fecha_propuesta, cotiz_creador, cotiz_vendedor, cotiz_vendida, 
												cli_id, cli_nombre, cli_zona,
												usr_id, usr_nombre 
												FROM cotizacion
								INNER JOIN clientes ON cli_id=cotiz_cliente
								INNER JOIN usuarios ON usr_id=cotiz_creador
								WHERE cotiz_id=cotiz_id $filtro
								ORDER BY cotiz_id DESC
								LIMIT $inicio, $limite
								");
										}
										$no = 1;
										while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {

											if ($datosUsuarioActual[3] != 1) {
												$consultaNumZ = $conexionBdPrincipal->query("SELECT * FROM zonas_usuarios 
												WHERE zpu_usuario='" . $_SESSION["id"] . "' AND zpu_zona='" . $res['cli_zona'] . "'");
												$numZ = $consultaNumZ->num_rows;
												if ($numZ == 0) continue;
											}

											if ($datosUsuarioActual[3] == 14 and $res['cotiz_creador'] != $_SESSION["id"] and $res['cotiz_vendedor'] != $_SESSION["id"]) {
												continue;
											}

											$vendedor = mysqli_fetch_array($conexionBdPrincipal->query("SELECT usr_id, usr_nombre FROM usuarios 
												WHERE usr_id='" . $res['cotiz_vendedor'] . "'"), MYSQLI_BOTH);

											$fondoCotiz = '';
											if ($res['cotiz_vendida'] == 1) {
												$fondoCotiz = 'aquamarine';
											}

											$generoPedido = mysqli_fetch_array($conexionBdPrincipal->query("SELECT pedid_id, pedid_fecha_creacion FROM pedidos 
												WHERE pedid_cotizacion='" . $res['cotiz_id'] . "'"), MYSQLI_BOTH);

											$infoPedido = '';
											if(isset($generoPedido['pedid_id'])){
												if($generoPedido['pedid_id']!=""){$infoPedido = 'Esta cotización ya generó el pedido con ID: '.$generoPedido['pedid_id'].". En la fecha: ".$generoPedido['pedid_fecha_creacion'];}
											}
										?>
											<tr>
											<td><?= $no; ?></td>
												<td style="background-color: <?= $fondoCotiz; ?>;" title="<?=$infoPedido;?>"><?= $res['cotiz_id']; ?></td>
												<td><?= $res['cotiz_fecha_propuesta']; ?></td>
												<td><?= strtoupper($res['cli_nombre']); ?></td>
												<td>
													
													<?php
													$productos = $conexionBdPrincipal->query("SELECT prod_nombre FROM cotizacion_productos
										INNER JOIN productos ON prod_id=czpp_producto
										WHERE czpp_cotizacion='" . $res['cotiz_id'] . "'
										");
													$i = 1;
													while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
														if($i==1){echo "<b>Productos:</b><br>";}
														echo "<b>" . $i . ".</b> " . $prod['prod_nombre'] . ", ";
														$i++;
													}
													?>

													<?php
													$combos = $conexionBdPrincipal->query("SELECT combo_nombre FROM cotizacion_productos
										INNER JOIN combos ON combo_id=czpp_combo
										WHERE czpp_cotizacion='" . $res['cotiz_id'] . "'
										");
													$i = 1;
													while ($comb = mysqli_fetch_array($combos, MYSQLI_BOTH)) {
														if($i==1){echo "<br><b>Combos:</b><br>";}
														echo "<b>" . $i . ".</b> " . $comb['combo_nombre'] . ", ";
														$i++;
													}
													?>

										<?php
													$servicios =$conexionBdPrincipal->query("SELECT serv_nombre FROM cotizacion_productos
										INNER JOIN servicios ON serv_id=czpp_servicio
										WHERE czpp_cotizacion='" . $res['cotiz_id'] . "'
										");
													$i = 1;
													while ($serv = mysqli_fetch_array($servicios, MYSQLI_BOTH)) {
														if($i==1){echo "<br><b>Servicios:</b><br>";}
														echo "<b>" . $i . ".</b> " . $serv['serv_nombre'] . ", ";
														$i++;
													}
													?>

												</td>
												<td><?= strtoupper($res['usr_nombre']); ?></td>
												<td><?= strtoupper($vendedor['usr_nombre']); ?></td>
												<td>
													<div class="btn-group">
														<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span>
														</button>
														<ul class="dropdown-menu">
															<?php if ($_SESSION["id"] == $res['cotiz_creador'] or $_SESSION["id"] == $res['cotiz_vendedor'] or $datosUsuarioActual[3] == 1) { ?>
																<li><a href="cotizaciones-editar.php?id=<?= $res['cotiz_id']; ?>#productos"> Editar</a></li>

																<li><a href="bd_delete/cotizaciones-eliminar.php?id=<?= $res['cotiz_id']; ?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}">Eliminar</a></li>
															<?php } ?>

															<li><a href="reportes/formato-cotizacion-1.php?id=<?= $res['cotiz_id']; ?>" target="_blank">Imprimir</a></li>

															<li><a href="bd_create/cotizaciones-replicar.php?id=<?= $res['cotiz_id']; ?>" onClick="if(!confirm('Desea replicar este registro?')){return false;}">Replicar</a></li>

																<?php if(isset($generoPedido['pedid_id'])){if($generoPedido['pedid_id']==""){?>
															<li><a href="bd_create/cotizaciones-genera-pedido.php?id=<?= $res['cotiz_id']; ?>" onClick="if(!confirm('Desea generar pedido de esta cotización?')){return false;}">Generar pedido</a></li>
															<?php }}?>


														</ul>
													</div>
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
	<?php include("pie.php"); ?>
	</div>
</body>

</html>