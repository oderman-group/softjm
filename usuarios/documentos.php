<?php
include("sesion.php");

$idPagina = 319;

include("includes/verificar-paginas.php");
include("includes/head.php");
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
<?php include("includes/funciones-js.php");?>
</head>
<body>
	<div class="layout">
		<?php include("includes/encabezado.php");?>		
		<div class="main-wrapper">
			<div class="container-fluid">
				<div class="row-fluid ">
				<?php if(Modulos::validarRol([320], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && $idEmpresa == ID_COMPANY_OWNER) {
                ?>
					<p><a href="documentos-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a></p>
				<?php } ?>
					<?php include("includes/notificaciones.php");?>
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
												<th>Id</th>
												<th>Nombre</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$consulta = $conexionBdAdmin->query("SELECT * FROM documentos");
											
											$no = 1;
											while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
											?>
											<tr>
												<td><?=$no;?></td>
												<td><?=$res['doc_id'];?></td>
												<td><?=$res['doc_nombre'];?></td>
												
												<td><h4>
												<?php 
												if(Modulos::validarRol([322], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && $idEmpresa == ID_COMPANY_OWNER) {
               									 ?>
													<a href="documento-editar.php?id=<?=$res[0];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
												<?php 
												} 
												if(Modulos::validarRol([323], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
                								?>
													<a href="documento-configuracion.php?id=<?=$res[0];?>" data-toggle="tooltip" title="Configuración"><span class="widget-icon icon-cogs"></span></a>
												<?php 
												} 
												if(Modulos::validarRol([327], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && $idEmpresa == ID_COMPANY_OWNER) {
                								
												?>
													<a href="bd_delete/documento-eliminar.php?id=<?=$res[0];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
													</h4></td>
												<?php } ?>
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
		<?php include("includes/pie.php");?>
	</div>
</body>
</html>