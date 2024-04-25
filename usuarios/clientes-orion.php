<?php include("sesion.php");

$idPagina = 139;

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
            <p><a href="clientes-orion-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a></p>
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
								<th>Empresa</th>
								<th>Contacto</th>
								<th>Inicio</th>
								<th>Fin</th>
								<th>Aviso previo</th>
								<th>Contrato</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$consulta = $conexionBdAdmin->query("SELECT * FROM clientes_orion");
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
							?>
							<tr>
								<td><?=$no;?></td>
                                <td><?=$res['clio_id'];?></td>
                                <td><?=$res['clio_empresa'];?></td>
								<td><?=$res['clio_contacto_principal'];?></td>
								<td><?=$res['clio_fecha_inicio'];?></td>
								<td><?=$res['clio_fecha_fin'];?></td>
								<td><?=$res['clio_aviso_previo'];?></td>
								<td>
									<?php if (!empty($res['clio_contrato']) && file_exists('files/contratos/'.$res['clio_contrato'])) { ?>
										<a href="files/contratos/<?=$res['clio_contrato'];?>" target="_blank">Descargar</a>
									<?php } ?>
								</td>

                                <td><h4>
									<a href="#roles-editar.php?id=<?=$res[0];?>" data-toggle="tooltip" title="Enviar recordatorio"><i class="icon-envelope"></i></a>
                                	<a href="clientes-orion-editar.php?id=<?=$res[0];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
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
	<?php include("includes/pie.php");?>
</div>
</body>
</html>