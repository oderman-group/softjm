<?php 
include("sesion.php");

$idPagina = 259;
$paginaActual['pag_nombre'] = "Productos a clientes";
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
            <?php include("includes/notificaciones.php");?>
            <p>
            <a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
						<?php if (Modulos::validarRol([260], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
            <a href="facturacion-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
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
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Productos</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							if(isset($_GET["cte"]) and $_GET["cte"]!=""){
								$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM facturacion
								INNER JOIN clientes ON cli_id=fact_cliente
								INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
								WHERE fact_cliente='".$_GET["cte"]."' AND fact_id_empresa='".$idEmpresa."'");
							}else{
								$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM facturacion
								INNER JOIN clientes ON cli_id=fact_cliente
								INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
								WHERE  fact_id_empresa='".$idEmpresa."'
								");
							}
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
								$Cpd = mysqli_query($conexionBdPrincipal,"SELECT * FROM facturacion_productos
								INNER JOIN productos_soptec ON prod_id=fpp_producto
								WHERE fpp_factura='".$res['fact_id']."'");
								
								$consultaAbonos=mysqli_query($conexionBdPrincipal,"SELECT sum(fpab_valor) FROM facturacion_abonos WHERE fpab_factura='".$res['fact_id']."'");
								$abonos = mysqli_fetch_array($consultaAbonos, MYSQLI_BOTH);
								
								
								$impuestos = $res['fact_valor'] * $res['fact_impuestos']/100;
								$retencion = $res['fact_valor'] * $res['fact_retencion']/100;
								$descuento = $res['fact_valor'] * $res['fact_descuento']/100;
								
								$valorReal = ($res['fact_valor'] + $impuestos) - ($retencion + $descuento);
								
								$saldoFinal = $valorReal - $abonos[0];

								if(!Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
									$consultaNumZonas=mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$res['cli_zona']."'");
									$numZ = mysqli_num_rows($consultaNumZonas);
									if($numZ==0) continue;
								}
							?>
							<tr>
								<td><?=$no;?></td>

                                <td><?=$res['fact_fecha_real'];?></td>
                                <td>
                                	<?php echo "<b>Nit</b>: ". $res['cli_usuario']."<br>";?>
									<?php echo "<b>Nombre</b>: ". $res['cli_nombre']."<br>";?>
                                    <?php echo "<b>Ciudad</b>: ". $res['ciu_nombre'].", ".$res['dep_nombre']." (<b>03".$res['dep_indicativo']."</b>)";?>
                                    <h4 style="margin-top:10px;">
																		<?php if (Modulos::validarRol([13], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																			<a href="facturacion-editar.php?id=<?=$res['fact_id'];?>&cte=<?=$_GET['cte'];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>&nbsp;
																		<?php } ?>
																		<?php if (Modulos::validarRol([58], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																			<a href="bd_delete/facturacion-eliminar.php?id=<?=$res['fact_id'];?>&cte=<?=$_GET['cte'];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
																		<?php } ?>
                                </h4>
                                </td>

                                
                                <td><?php 
								while($pds = mysqli_fetch_array($Cpd, MYSQLI_BOTH)){
									if (Modulos::validarRol([68], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
										echo '<a href="productos-materiales.php?pdto='.$pds['prod_id'].'" title="Ver materiales">'.$pds['prod_nombre']."</a><br>";
										}
								}
								?></td>
                                
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