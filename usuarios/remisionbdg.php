<?php 
include("sesion.php");

$idPagina = 148;

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
<script src="js/bootbox.js"></script>

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
			
			function confirmar (e) {
                bootbox.confirm("Desea enviar esta cotización?", function (result) {
                    var id = e.id;
					var cont = e.name;
					if(result == true){
						window.location.href="sql.php?get=44&id="+id+"&cont="+cont;
					}
            })
			};
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
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            <?php include("includes/notificaciones.php");?>
            <p>
            <a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
            <a href="remisionbdg-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
            </p>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<?php
							$filtro = '';
							if($_GET["busqueda"]!=""){$filtro .= " AND (remi_id='".$_GET["busqueda"]."' OR cli_nombre='".$_GET["busqueda"]."' OR usr_nombre='".$_GET["busqueda"]."')";}	
							
							if($datosUsuarioActual['usr_tipo']!=1){
								$filtro.=' AND cli_ciudad!="1122"';
							}
								
							if(isset($_GET["cte"]) and $_GET["cte"]!=""){
								$SQL="SELECT * FROM remisionbdg
								INNER JOIN clientes ON cli_id=remi_cliente AND cli_id='".$_GET["cte"]."'
								ORDER BY remi_id DESC";
							}else{
								$SQL="SELECT * FROM remisionbdg
								INNER JOIN clientes ON cli_id=remi_cliente
								INNER JOIN usuarios ON usr_id=remi_creador
								WHERE remi_id=remi_id $filtro
								ORDER BY remi_id DESC";
							}
						?>
						<div class="widget-container">
                            <div style="border:thin; border-style:solid; height:150px; margin:10px; padding:10px;">
                                <h4 align="center">-Busqueda general y paginación-</h4>
                                <p> 
                                    <form class="form-horizontal" style="text-align: right;" action="<?=$_SERVER['PHP_SELF'];?>" method="get">
                                        <div class="search-box">
                                            <div class="input-append input-icon">
                                                <input placeholder="Buscar..." type="text" name="busqueda" value="<?php if(isset($_GET["busqueda"])) echo $_GET["busqueda"]; ?>">
                                                <i class=" icon-search"></i>
                                                <input class="btn" type="submit" value="Buscar">
                                            </div>
                                            <?php if(isset($_GET["busqueda"]) and $_GET["busqueda"]!=""){?> <a href="<?=$_SERVER['PHP_SELF'];?>" class="btn btn-warning"><i class="icon-minus"></i> Quitar Filtro</a> <?php } ?>
                                        </div>
                                    </form>
                                    <p style="margin: 10px;"><?php include("includes/paginacion.php");?></p> 
                                </p>
                            </div>
						
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
							<th>No.</th>	
							<th>ID</th>
                                <th>Fecha Documento</th>
                                <th>Cliente</th>
								<th>Responsable</th>
								<th>Vendedor</th>
								<th>Estado</th>
								<th>#Pedido</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							if(isset($_GET["cte"]) and $_GET["cte"]!=""){
								$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisionbdg
								INNER JOIN clientes ON cli_id=remi_cliente AND cli_id='".$_GET["cte"]."'
								ORDER BY remi_id DESC
								LIMIT $inicio, $limite
								");
							}else{
								$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisionbdg
								INNER JOIN clientes ON cli_id=remi_cliente
								INNER JOIN usuarios ON usr_id=remi_creador
								WHERE remi_id=remi_id $filtro
								ORDER BY remi_id DESC
								LIMIT $inicio, $limite
								");
							}
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
								if($datosUsuarioActual[3]!=1){
									$consultaZonas=mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$res['cli_zona']."'");
									$numZ = mysqli_num_rows($consultaZonas);
									if($numZ==0) continue;
								}
								
								$consultaVendedor=mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$res['remi_vendedor']."'");
								$vendedor = mysqli_fetch_array($consultaVendedor, MYSQLI_BOTH);


								$consultaFactura=mysqli_query($conexionBdPrincipal,"SELECT * FROM facturas WHERE factura_remision='" . $res['remi_id'] . "'");
								$generoFactura = mysqli_fetch_array($consultaFactura, MYSQLI_BOTH);

											$infoFac = '';
											$fondoRemision = '';
											if($generoFactura[0]!=""){
												$infoFac = 'Esta remisión ya generó la factura con ID: '.$generoFactura[0].". En la fecha: ".$generoFactura['factura_fecha_creacion'];
												$fondoRemision = 'aquamarine';
											}
							?>
							<tr>
							<td><?= $no; ?></td>	
							<td style="background-color: <?= $fondoRemision; ?>;" title="<?=$infoFac;?>"><?=$res['remi_id'];?></td>
                                <td><?=$res['remi_fecha_propuesta'];?></td>
                                <td><?=strtoupper($res['cli_nombre']);?></td>
								<td><?=strtoupper($res['usr_nombre']);?></td>
								<td><?=strtoupper($vendedor['usr_nombre']);?></td>
								<td><?=$res['remi_estado'];?></td>
								<td><?=$res['remi_pedido'];?></td>
                                <td>
									<div class="btn-group">
										<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<?php if($_SESSION["id"]==$res['remi_creador'] or $_SESSION["id"]==57 or $_SESSION["id"]==$res['remi_vendedor'] or $datosUsuarioActual[3]==13){?>
											<li><a href="remisionbdg-editar.php?id=<?=$res[0];?>#productos"> Editar</a></li>
											<?php }?>
											
											<?php if($_SESSION["id"]==$res['remi_creador'] or $_SESSION["id"]==57 or $_SESSION["id"]==$res['remi_vendedor']){?>
											<li><a href="bd_delete/remisionbdg-eliminar.php?id=<?=$res[0];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}">Eliminar</a></li>
											<?php }?>
											
											<li><a href="reportes/formato-remision-1.php?id=<?=$res[0];?>" target="_blank">Imprimir</a></li>

											<?php if($generoFactura[0]==""){?>
											
											<li><a href="bd_create/remisionbdg-generar-factura.php?id=<?=$res[0];?>" onClick="if(!confirm('Desea generar factura de esta remisión?')){return false;}">Generar Factura</a></li>

											<?php }?>
											
										</ul>
									</div>
								</td>
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