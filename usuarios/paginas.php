<?php
include("sesion.php");

$idPagina = 73;

include("verificar-paginas.php");
include("head.php");
?>
<!-- styles -->
<link href="css/tablecloth.css" rel="stylesheet">
<!--============javascript===========-->
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
	$(function () {
		$('#data-table').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
		});
	});
	$(function () {
		$('.tbl-simple').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
		});
	});	
	$(function () {
		$(".tbl-paper-theme").tablecloth({
		theme: "paper"
		});
	});
	$(function () {
		$(".tbl-dark-theme").tablecloth({
		theme: "dark"
		});
	});
	$(function () {
		$('.tbl-paper-theme,.tbl-dark-theme').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
		});
	});
</script>
<?php include("funciones-js.php");?>
</head>
<body>
	<div class="layout">
		<?php include("encabezado.php");?>		
		<div class="main-wrapper">
			<div class="container-fluid">
				<div class="row-fluid ">
					<p><a href="paginas-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar Pagina</a></p>
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets light-gray">
								<div class="widget-head green">
									<h3><?=$paginaActual['pag_nombre'];?></h3>
								</div>
								<div class="widget-container">
									<p></p>
									<table class="table table-striped table-bordered" id="data-table">
										<thead>
											<tr>
												<th>No</th>
												<th>Nombre</th>
												<th>Tipo CRUD</th>
												<th>Modulo</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$consulta = $conexionBdAdmin->query("SELECT * FROM paginas");
											$no = 1;
											while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){    
                                                $nombreModulo='[SIN MODULO]';
												if($res['pag_id_modulo']!=0){                                            
                                                    $consultaNombreMod = $conexionBdAdmin->query("SELECT * FROM modulos WHERE mod_id='".$res['pag_id_modulo']."'");
                                                    $resNombreModulo = mysqli_fetch_array($consultaNombreMod, MYSQLI_BOTH);                                                    
                                                    $nombreModulo=$resNombreModulo['mod_nombre'];
                                                }
											?>
											<tr>
												<td><?=$no;?></td>
												<td><?=$res['pag_nombre'];?></td>
												<td><?=$tipoCrud[$res['pag_tipo_crud']];?></td>
												<td><?=$nombreModulo;?></td>
												<td><h4>
													<a href="paginas-editar.php?id=<?=$res[0];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
													<a href="bd_delete/paginas-eliminar.php?id=<?=$res[0];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
												</h4></td>
											</tr>
											<?php $no++;}?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include("pie.php");?>
	</div>
</body>
</html>