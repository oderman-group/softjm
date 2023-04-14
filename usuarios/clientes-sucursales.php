<?php
include("sesion.php");

$idPagina = 83;
$paginaActual['pag_nombre'] = "Sucursales";
include("includes/verificar-paginas.php");
include("includes/head.php");
$consultaClientes=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_GET["cte"]."'");
$cliente = mysqli_fetch_array($consultaClientes, MYSQLI_BOTH);
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
						<ul class="top-right-toolbar">
							<li><a data-toggle="dropdown" class="dropdown-toggle blue-violate" href="#" title="Users"><i class="icon-user"></i></a>
							</li>
							<li><a href="#" class="green" title="Upload"><i class=" icon-upload-alt"></i></a></li>
							<li><a href="#" class="bondi-blue" title="Settings"><i class="icon-cogs"></i></a></li>
						</ul>
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
                <a href="clientes-sucursales-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
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
                                <th>Ciudad</th>
                                <th>Cliente</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM sucursales
							INNER JOIN clientes ON cli_id=sucu_cliente_principal 
							INNER JOIN orioncrmcom_dev_crm_admin.localidad_ciudades ON ciu_id=sucu_ciudad 
							INNER JOIN orioncrmcom_dev_crm_admin.localidad_departamentos ON dep_id=ciu_departamento
							WHERE sucu_cliente_principal='".$_GET["cte"]."'");
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
							?>
							<tr>
								<td><?=$no;?></td>
                                <td><?=$res['sucu_nombre'];?></td>
                                <td><?=$res['sucu_telefono'];?></td>
                                <td><?=$res['sucu_celular'];?></td>
                                <td><?=$res['ciu_nombre'].", ".$res['dep_nombre'];?></td>
                                <td><?=$res['cli_nombre'];?></td>
                                <td><h4>
                                    <a href="clientes-sucursales-editar.php?id=<?=$res[0];?>&cte=<?=$_GET["cte"];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
                                    <a href="sql.php?id=<?=$res[0];?>&get=23" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
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