<?php
include("sesion.php");

$idPagina = 142;

include("includes/verificar-paginas.php");
include_once(RUTA_PROYECTO."/usuarios/includes/api-ofima-conexion.php");
$ofimaActiva = ofimaIntegracionActiva($conexionBdPrincipal, (int) $_SESSION["dataAdicional"]["id_empresa"]);
include("includes/head.php");
?>
<!-- styles -->
<link href="css/tablecloth.css" rel="stylesheet">
<link href="css/clientes-listado.css" rel="stylesheet">
<style>
	.clientes-page .clientes-panel { overflow: visible; border-radius: 14px; }
	.clientes-page .clientes-panel-header { border-radius: 13px 13px 0 0; }
	.clientes-page .clientes-panel-body { border-radius: 0 0 13px 13px; }
	.bodegas-aviso {
		margin: 0 0 1rem;
		padding: 0.75rem 1rem;
		border: 1px solid #bae6fd;
		border-radius: 10px;
		background: #f0f9ff;
		color: #0c4a6e;
		font-size: 0.875rem;
		font-weight: 600;
	}
	.clientes-page .clientes-table-wrap { max-height: calc(100vh - 220px); overflow: auto; }
	.clientes-page .clientes-table { font-size: 0.7rem; }
	.clientes-page .clientes-table thead th {
		position: sticky;
		top: 0;
		z-index: 5;
		background: #f8fafc;
		white-space: nowrap;
		font-size: 0.62rem;
		padding: 0.4rem 0.35rem;
		letter-spacing: 0.02em;
	}
	.clientes-page .clientes-table th,
	.clientes-page .clientes-table td { border-right: 1px solid #e2e8f0; }
	.clientes-page .clientes-table th:last-child,
	.clientes-page .clientes-table td:last-child { border-right: 0; }
	.clientes-page .clientes-table tbody td,
	.clientes-page .clientes-table thead th { text-align: center; vertical-align: middle !important; }
	.clientes-page .clientes-table tbody td { padding: 0.35rem 0.35rem; }
	.clientes-page .clientes-table tbody tr:nth-child(even) td,
	.clientes-page .clientes-table tbody tr:nth-child(even):hover td { background: #f1f5f9; }
	.clientes-page .clientes-table tbody tr:nth-child(odd) td,
	.clientes-page .clientes-table tbody tr:nth-child(odd):hover td { background: #fff; }
	.clientes-page .clientes-table tbody tr:hover { background: transparent; }
	.clientes-page .clientes-table tbody tr.bodega-deshabilitada td,
	.clientes-page .clientes-table tbody tr.bodega-deshabilitada:hover td {
		background: #e2e8f0;
		color: #94a3b8;
	}
	.clientes-page .clientes-table tbody tr.bodega-deshabilitada td a { color: #94a3b8; }
	.clientes-page .clientes-table td.col-acciones h4 {
		display: flex;
		justify-content: center;
		align-items: center;
		gap: 0.35rem;
		margin: 0;
	}
	.clientes-page .clientes-table td.col-acciones h4 a {
		display: inline-block;
		transform-origin: center;
		transition: transform .15s ease;
		color: #059669;
	}
	.clientes-page .clientes-table td.col-acciones h4 a:hover { transform: scale(1.45); }
	body:not(.ver-habilitados) tr.bodega-habilitada,
	body:not(.ver-no-habilitados) tr.bodega-deshabilitada { display: none; }
	.bodegas-filtro {
		display: inline-flex;
		align-items: center;
		gap: 0.2rem;
		padding: 0.2rem;
		background: rgba(255, 255, 255, 0.18);
		border: 1px solid rgba(255, 255, 255, 0.35);
		border-radius: 10px;
	}
	.bodegas-filtro label {
		position: relative;
		margin: 0;
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		padding: 0.35rem 0.7rem;
		border-radius: 8px;
		font-size: 0.75rem;
		font-weight: 700;
		color: #fff;
		line-height: 1;
		white-space: nowrap;
	}
	.bodegas-filtro input { position: absolute; opacity: 0; pointer-events: none; }
	.bodegas-filtro label:has(input:checked) {
		background: #fff;
		color: #047857;
	}
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
	/*$( function () {
		  // Set the classes that TableTools uses to something suitable for Bootstrap
		  $.extend( true, $.fn.DataTable.TableTools.classes, {
			  "container": "btn-group",
			  "buttons": {
				  "normal": "btn",
				  "disabled": "btn disabled"
			  },
			  "collection": {
				  "container": "DTTT_dropdown dropdown-menu",
				  "buttons": {
					  "normal": "",
					  "disabled": "disabled"
				  }
			  }
		  } );
		  // Have the collection use a bootstrap compatible dropdown
		  $.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
			  "collection": {
				  "container": "ul",
				  "button": "li",
				  "liner": "a"
			  }
		  } );
		  });
		  */
	$(function() {
		if ($('#data-table').length && !$('body').hasClass('clientes-page')) {
		$('#data-table').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
			/*"oTableTools": {
			"aButtons": [
				"copy",
				"print",
				{
					"sExtends":    "collection",
					"sButtonText": 'Save <span class="caret" />',
					"aButtons":    [ "csv", "xls", "pdf" ]
				}
			]
		}*/
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
</script>
</head>

<body class="clientes-page ver-habilitados">
	<div class="layout">
		<?php include("includes/encabezado.php"); ?>
		<div class="main-wrapper">
			<div class="container-fluid clientes-page-inner">
				<div class="clientes-hero">
					<div>
						<h1 class="clientes-hero-title"><?= htmlspecialchars($paginaActual['pag_nombre'] ?? 'Bodegas'); ?></h1>
						<p class="clientes-hero-subtitle">Existencias por bodega</p>
					</div>
					<div class="clientes-hero-actions">
						<?php if (!$ofimaActiva && Modulos::validarRol([143], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
							<a href="bodegas-agregar.php" class="btn btn-success"><i class="icon-plus"></i> Agregar nuevo</a>
						<?php } ?>
						<?php if (!$ofimaActiva && Modulos::validarRol([147], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
							<a href="bodegas-transferir.php" class="btn btn-info"><i class="icon-random"></i> Transferir productos</a>
						<?php } ?>
					</div>
				</div>
				<?php include("includes/notificaciones.php"); ?>
				<?php if ($ofimaActiva) { ?>
				<div class="bodegas-aviso">
					Con la integración Ofima activa, las bodegas y las transferencias de productos solo se gestionan desde Ofima.
				</div>
				<?php } ?>
				<section class="clientes-panel">
					<div class="clientes-panel-header">
						<div>
							<h3>Listado de bodegas</h3>
						</div>
						<div class="bodegas-filtro">
							<label><input type="radio" name="filtroHabBodega" value="habilitados" checked> Habilitadas</label>
							<label><input type="radio" name="filtroHabBodega" value="deshabilitados"> Deshabilitadas</label>
							<label><input type="radio" name="filtroHabBodega" value="todos"> Todas</label>
						</div>
					</div>
					<div class="clientes-panel-body">
						<div class="clientes-table-wrap">
								<table class="clientes-table" id="data-table">
									<thead>
										<tr>
											<th>No</th>
											<th>COD.</th>
											<th>Referencia</th>
											<th>Creación</th>
											<th>Nombre</th>
											<th>Ciudad</th>
											<th>Productos</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$consulta = $conexionBdPrincipal->query("SELECT * FROM ".MAINBD.".bodegas 
										INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=bod_ciudad
										INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
										WHERE bod_id_empresa =  '".$_SESSION["dataAdicional"]["id_empresa"]."'
										ORDER BY bod_habilitada DESC, bod_nombre ASC");
										$no = 1;
										while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
											$consultaProductosBodegas = $conexionBdPrincipal->query("SELECT * FROM productos_bodegas 
											WHERE prodb_bodega='".$res[0]."'");
											$cantProd = $consultaProductosBodegas->num_rows;
											$habilitada = !isset($res['bod_habilitada']) || (int) $res['bod_habilitada'] === 1;
										?>
											<tr class="<?= $habilitada ? 'bodega-habilitada' : 'bodega-deshabilitada'; ?>">
												<td><?= $no; ?></td>
												<td><?= $res['bod_id']; ?></td>
												<td><?= htmlspecialchars((string) ($res['bod_referencia'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
												<td><?= $res['bod_fecha_creacion']; ?></td>
												<td><?= $res['bod_nombre']; ?></td>
												<td><?= $res['ciu_nombre'].", ".$res['dep_nombre']; ?></td>
												<td>
													<a 
														href="bodegas-productos.php?bod=<?=$res[0];?>"
														style="text-decoration: underline;"
													>
														<?= $cantProd; ?>
													</a>
												</td>
												<td class="col-acciones">
													<h4>
													<?php if($res[0] != 1){
														if (!$ofimaActiva && Modulos::validarRol([144], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
															echo '<a href="bodegas-editar.php?id='.$res[0].'" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a> ';
														}
														if (Modulos::validarRol([222], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && false) {
														?>													
															<a href="bd_delete/bodegas-eliminar.php?id=<?php echo $res[0]; ?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
														<?php
														}									
													}?>
													</h4>
												</td>
											</tr>
										<?php $no++;
										} ?>
									</tbody>
								</table>
						</div>
					</div>
				</section>


			</div>
		</div>
	</div>
	<?php include("includes/pie.php"); ?>
	</div>
	<script>
		$('input[name="filtroHabBodega"]').on('change', function () {
			var modo = $('input[name="filtroHabBodega"]:checked').val();
			$('body').toggleClass('ver-habilitados', modo === 'habilitados' || modo === 'todos');
			$('body').toggleClass('ver-no-habilitados', modo === 'deshabilitados' || modo === 'todos');
		});
	</script>
</body>

</html>
