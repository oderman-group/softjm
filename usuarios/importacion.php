<?php 
include("sesion.php");

$idPagina = 133;
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
						window.location.href="enviar-cotizaciones-correo.php?get=44&id="+id+"&cont="+cont;
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
						<?php if (Modulos::validarRol([134], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
            <a href="importacion-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
						<?php } ?>
            </p>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<p></p>
							
							<div style="border:thin; border-style:solid; height:150px; margin:10px;">
                            	<h4 align="center">-Búsqueda por ID-</h4>
                                <p> 
                                    <form class="form-horizontal" action="<?=$_SERVER['PHP_SELF'];?>" method="get">
                                        <div class="search-box">
                                            <div class="input-append input-icon">
                                                <input class="search-input" placeholder="ID..." type="text" name="q" value="<?=$_GET["q"];?>">
                                                <i class=" icon-search"></i>
                                                <input class="btn" type="submit" name="buscar" value="Buscar">
                                            </div>
                                            <?php if($_GET["q"]!=""){?> <a href="<?=$_SERVER['PHP_SELF'];?>" class="btn btn-warning"><i class="icon-minus"></i> Quitar Filtro</a> <?php } ?>
                                        </div>
                                    </form> 
                                </p>
                            </div>
						
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>ID</th>
								<th>Concepto</th>
                                <th>Fecha</th>
                                <th>Proveedor</th>
								<th>Responsable</th>
								<th>Productos</th>
								<th>Liquidada</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro = '';
							if($_GET["q"]!=""){$filtro .= " AND imp_id='".$_GET["q"]."'";}	
								
							
								$consulta = mysqli_query($conexionBdPrincipal, "SELECT * FROM importaciones
								INNER JOIN proveedores ON prov_id=imp_proveedor
								INNER JOIN usuarios ON usr_id=imp_responsable
								WHERE imp_id=imp_id AND imp_id_empresa='".$idEmpresa."' $filtro
								");
							
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
								
							?>
							<tr>
								<td><?=$res['imp_id'];?></td>
								<td><?=$res['imp_concepto'];?></td>
                                <td><?=$res['imp_fecha'];?></td>
                                <td><?=strtoupper($res['prov_nombre']);?></td>
								
								<td><?=strtoupper($res['usr_nombre']);?></td>
								<td>
									<?php
										$productos = mysqli_query($conexionBdPrincipal, "SELECT * FROM cotizacion_productos
										INNER JOIN productos ON prod_id=czpp_producto
										WHERE czpp_cotizacion='".$res['imp_id']."'
										");
										$i = 1;
										while($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)){
											echo "<b>".$i.".</b> ".$prod['prod_nombre'].", ";
											$i++;
										}
									?>
								</td>
								<td>NO</td>
                                <td>
									<div class="btn-group">
										<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<?php if($_SESSION["id"]==$res['cotiz_creador'] or $_SESSION["id"]==$res['cotiz_vendedor'] or Modulos::validarRol([135], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){?>
											<li><a href="importacion-editar.php?id=<?=$res['imp_id'];?>#productos"> Editar</a></li>
											<?php }?>
											
											<li><a href="#reportes/formato-cotizacion-1.php?id=<?=$res['cotiz_id'];?>" target="_blank">Imprimir</a></li>
											
											<!--
											<li><a href="sql.php?get=48&id=<?=$res['cotiz_id'];?>" onClick="if(!confirm('Desea generar pedido de esta cotización?')){return false;}">Generar pedido</a></li>
											
											<li><a href="sql.php?get=56&id=<?=$res['cotiz_id'];?>" onClick="if(!confirm('Desea generar factura de esta cotización?')){return false;}">Generar factura</a></li>
											-->
											
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