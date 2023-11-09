<?php
include("sesion.php");

$idPagina = 44;
$paginaActual['pag_nombre'] = "Contactos";

include("includes/verificar-paginas.php");
include("includes/head.php");
$consultaDatos=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_GET["cte"]."' AND cli_id_empresa='".$idEmpresa."'");
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
                <a href="clientes-contactos-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
            </p>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?> de <b><?=$cliente['cli_nombre'];?></b></h3>
						</div>
						<div class="widget-container">
							<p></p>
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>Nombre</th>
								<th>Telefono</th>
                                <th>Celular</th>
                                <th>Email</th>
                                <th>Cliente</th>
                                <th>Sucursal</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos INNER JOIN clientes ON cli_id=cont_cliente_principal WHERE cont_cliente_principal='".$_GET["cte"]."'");
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
								$consultaSucursal=mysqli_query($conexionBdPrincipal,"SELECT * FROM sucursales WHERE sucu_id='".$res['cont_sucursal']."'");
								$sucursal = mysqli_fetch_array($consultaSucursal, MYSQLI_BOTH);
							?>
							<tr>
								<td><?=$no;?></td>
                                <td><?=$res['cont_nombre'];?></td>
                                <td><?=$res['cont_telefono'];?></td>
                                <td><?=$res['cont_celular'];?></td>
                                <td><?=$res['cont_email'];?></td>
                                <td><?=$res['cli_nombre'];?></td>
                                <td><?=$sucursal['sucu_nombre'];?></td>
                                <td><h4>
                                    <a href="clientes-contactos-editar.php?id=<?=$res[0];?>&cte=<?=$_GET["cte"];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
                                    <a href="bd_delete/clientes-contactos-eliminar.php?id=<?=$res[0];?>&cte=<?=$_GET["cte"];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
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