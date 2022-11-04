<?php
include("sesion.php");

$idPagina = 154;

include("includes/verificar-paginas.php");
include("includes/head.php");
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

<body>
	<div class="layout">
		<?php include("includes/encabezado.php"); ?>
		<div class="main-wrapper">
			<div class="container-fluid">
				<?php include("includes/notificaciones.php"); ?>
				<p><a href="servicios-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a></p>
				<div class="row-fluid">
					<div class="span12">
						<div class="content-widgets light-gray">
							<div class="widget-head green">
								<h3><?= $paginaActual['pag_nombre']; ?></h3>
							</div>
							<div class="widget-container">
								<p></p>
								<table class="table table-striped table-bordered" id="data-table">
									<thead>
										<tr>
											<th>No</th>
											<th>Nombre</th>
											<th>Precio</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$consulta = $conexionBdPrincipal->query("SELECT * FROM servicios");
										$no = 1;
										while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
										?>
											<tr>
												<td><?= $no; ?></td>
												<td><?= $res['serv_nombre']; ?></td>
												<td>$<?= number_format($res['serv_precio'], 0, ",", "."); ?></td>
												<td>
													<h4>
														<a href="servicios-editar.php?id=<?= $res[0]; ?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
														<a href="sql.php?id=<?= $res[0]; ?>&get=49" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
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