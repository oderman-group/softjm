<?php 
include("sesion.php");

$idPagina = 151;
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
            <?php include("includes/notificaciones.php");?>
            <p>
            <a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
				<!--
            <a href="#pedidos-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>-->
            </p>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?></h3>
						</div>
                            <?php
							$filtro = '';
							if($_GET["busqueda"]!=""){$filtro .= " AND (pedid_id='".$_GET["busqueda"]."' OR cli_nombre='".$_GET["busqueda"]."' OR usr_nombre='".$_GET["busqueda"]."')";}
								
							if(isset($_GET["cte"]) and $_GET["cte"]!=""){
								$SQL = "SELECT * FROM pedidos
								INNER JOIN clientes ON cli_id=pedid_cliente AND cli_id='".$_GET["cte"]."'
								ORDER BY pedid_id DESC";
							}else{
								$SQL = "SELECT * FROM pedidos
								INNER JOIN clientes ON cli_id=pedid_cliente
								INNER JOIN usuarios ON usr_id=pedid_creador
								WHERE pedid_id=pedid_id $filtro
								ORDER BY pedid_id DESC";
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
								<th>#Cotización</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php								
							if(isset($_GET["cte"]) and $_GET["cte"]!=""){
								$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM pedidos
								INNER JOIN clientes ON cli_id=pedid_cliente AND cli_id='".$_GET["cte"]."'
								WHERE pedid_id_empresa='".$idEmpresa."'
								ORDER BY pedid_id DESC
								LIMIT $inicio, $limite
								");
							}else{
								$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM pedidos
								INNER JOIN clientes ON cli_id=pedid_cliente
								INNER JOIN usuarios ON usr_id=pedid_creador
								WHERE pedid_id=pedid_id AND pedid_id_empresa='".$idEmpresa."' $filtro
								ORDER BY pedid_id DESC
								LIMIT $inicio, $limite
								");
							}
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
								if($datosUsuarioActual[3]!=1){
									$numZ = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$res['cli_zona']."'"));
									if($numZ==0) continue;
								}
								
								$vendedor = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$res['pedid_vendedor']."' AND usr_id_empresa='".$idEmpresa."'"), MYSQLI_BOTH);

								$generoRemision = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisionbdg WHERE remi_pedido='" . $res['pedid_id'] . "' AND remi_id_empresa='".$idEmpresa."'"), MYSQLI_BOTH);


											$infoRem = '';
											$fondoPedido = '';
											if($generoRemision[0]!=""){
												$infoRem = 'Este pedido ya generó la remisión con ID: '.$generoRemision[0].". En la fecha: ".$generoRemision['remi_fecha_creacion'];
												$fondoPedido = 'aquamarine';
											}

											$nombreCliente="";
											if(!empty($res['cli_nombre'])){
												$nombreCliente=strtoupper($res['cli_nombre']);
											}
											$nombreResponsable="";
											if(!empty($res['usr_nombre'])){
												$nombreResponsable=strtoupper($res['usr_nombre']);
											}
											$nombreVendedor="";
											if(!empty($vendedor['usr_nombre'])){
												$nombreVendedor=strtoupper($vendedor['usr_nombre']);
											}
							?>
							<tr>
							<td><?= $no; ?></td>	
							<td style="background-color: <?= $fondoPedido; ?>;" title="<?=$infoRem;?>"><?=$res['pedid_id'];?></td>
                                <td><?=$res['pedid_fecha_propuesta'];?></td>
                                <td><?=$nombreCliente;?></td>
								<td><?=$nombreResponsable;?></td>
								<td><?=$nombreVendedor;?></td>
								<td><a href="pedidos-timeline.php?id=<?=$res['pedid_id'];?>" target="_blank">En camino</a></td>
								<td><?=$res['pedid_cotizacion'];?></td>
                                <td>
									<div class="btn-group">
										<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<?php if($_SESSION["id"]==$res['pedid_creador'] or $_SESSION["id"]==$res['pedid_vendedor']){?>
											<!--
											<li><a href="#pedidos-editar.php?id=<?=$res[0];?>#productos"> Editar</a></li>
											
											<li><a href="bd_delete/pedidos-anular.php?id=<?=$res[0];?>" onClick="if(!confirm('Desea anular el registro?')){return false;}">Anular</a></li>-->
											
											<li><a href="bd_delete/pedidos-eliminar.php?id=<?=$res[0];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}">Eliminar</a></li>
											<?php }?>
											
											<li><a href="reportes/formato-pedido-1.php?id=<?=$res[0];?>" target="_blank">Imprimir</a></li>

											<?php if($generoRemision[0]==""){?>
											
												<li><a href="bd_create/pedidos-generar-remision.php?id=<?=$res[0];?>" onClick="if(!confirm('Desea generar remisión de este pedido?')){return false;}">Generar remisión</a></li>

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