<?php
include("sesion.php");

$idPagina = 145;
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


				<p>
					<a href="productos.php" class="btn btn-primary"><i class="icon-arrow"></i> Ir a productos</a>
					<?php if(isset($_GET["prod"])){?>
					<a href="bodegas-productos-agregar.php?prod=<?=$_GET["prod"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
					<?php }?>
					<a href="reportes/bodegasprod.php" target="_blank" class="btn btn-success"><i class="icon-file"></i> Sacar informe</a>
				</p>

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
										<th>S</th>
										<th>Cant.</th>
											<th>No</th>
											<th>Bodega</th>
											<th>Producto</th>
											<th>Existencias</th>
											<th>Actualización</th>
											<th>Responsable</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$filtro = '';
										if(isset($_GET["bod"])){
											if ($_GET["bod"] != "") {
												$filtro .= " AND prodb_bodega='" . $_GET["bod"] . "'";
											}
										}
										if(isset($_GET["prod"])){
											if ($_GET["prod"] != "") {
												$filtro .= " AND prodb_producto='" . $_GET["prod"] . "'";
											}
										}


										$consulta = $conexionBdPrincipal->query("SELECT * FROM productos_bodegas INNER JOIN productos ON prod_id=prodb_producto INNER JOIN bodegas ON bod_id=prodb_bodega LEFT JOIN usuarios ON usr_id=prodb_usuario_actualizacion WHERE prodb_id=prodb_id AND prod_id_empresa='".$idEmpresa."' AND bod_id_empresa='".$idEmpresa."' $filtro");
										$no = 1;
										while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
										?>
											<tr>
											<td><input type="checkbox"></td>
											<td><input type="number" style="width: 50px;"></td>
												<td><?= $no; ?></td>
												<td><?= $res['bod_nombre']; ?></td>
												<td><?= $res['prod_nombre']; ?></td>
												<td><?= $res['prodb_existencias']; ?></td>
												<td><?= $res['prodb_fecha_actualizacion']; ?></td>
												<td><?= $res['usr_nombre']; ?></td>
												<td>
													<h4>

														<a href="bodegas-productos-agregar.php?id=<?= $res[0]; ?>&prod=<?= $res['prod_id']; ?>&bod=<?= $res['bod_id']; ?>&ex=<?= $res['prodb_existencias']; ?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
														<a href="bd_delete/productos-bodegas-eliminar.php?id=<?= $res[0]; ?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>

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