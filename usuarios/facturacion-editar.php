<?php 
include("sesion.php");

$idPagina = 261;
$paginaActual['pag_nombre'] = "Editar productos asignados";

include("includes/verificar-paginas.php");
include("includes/head.php");

$consulta=mysqli_query($conexionBdPrincipal,"SELECT * FROM facturacion 
INNER JOIN usuarios ON usr_id=fact_usuario_responsable
WHERE fact_id='".$_GET["id"]."'");
$resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);
$consultaUsuariosMod=mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='".$resultadoD[9]."'");
$usuarioMod = mysqli_fetch_array($consultaUsuariosMod, MYSQLI_BOTH);
?>
<!-- styles -->

<link href="css/chosen.css" rel="stylesheet">


<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-fileupload.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.tagsinput.js"></script>
<script src="js/chosen.jquery.js"></script>
<script src="js/bootstrap-colorpicker.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script src="js/date.js"></script>
<script src="js/daterangepicker.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>
<?php include("includes/funciones-js.php");?>

<?php include("includes/texto-editor.php");?>
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
						<li><a href="facturacion.php">Facturación</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            <p>
                <a href="facturacion-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
            </p>
            <?php include("includes/notificaciones.php");?>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_update/facturacion-actualizar.php">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
                            <input type="hidden" name="cte" value="<?=$_GET["cte"];?>">
                            	   
                                
                                
                                <div class="control-group">
									<label class="control-label">Fecha de la factura</label>
									<div class="controls">
										<input type="date" class="span3" name="fechaFactura" value="<?=$resultadoD['fact_fecha_real'];?>">
									</div>
								</div>

                                
                                <div class="control-group">
									<label class="control-label">Cliente</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente">
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
												if($datosUsuarioActual[3]!=1){
													$consultaNumZona=mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$resOp['cli_zona']."'");
													$numZ = mysqli_num_rows($consultaNumZona);
													if($numZ==0) continue;
												}
											?>
                                            	<option value="<?=$resOp['cli_id'];?>" <?php if($resultadoD['fact_cliente']==$resOp['cli_id']){echo "selected";}?>><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>

                                
                                <div class="control-group">
									<label class="control-label">Productos</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="producto[]" multiple>
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_soptec INNER JOIN productos_categorias ON catp_id=prod_categoria ORDER BY prod_nombre");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
												$consultaProductos=mysqli_query($conexionBdPrincipal,"SELECT * FROM facturacion_productos WHERE fpp_producto='".$resOp[0]."' AND fpp_factura='".$resultadoD['fact_id']."'");
												$productoN = mysqli_num_rows($consultaProductos);
											?>
                                            	<option <?php if($productoN>0){echo "selected";}?> value="<?=$resOp['prod_id'];?>"><?=$resOp['prod_nombre'].", ".$resOp['catp_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>


                                <hr>
                                <div class="control-group">
									<label class="control-label">Fecha del registro</label>
									<div class="controls">
										<input type="text" class="span3" name="fechaR" value="<?=$resultadoD['fact_fecha'];?>" readonly>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Responsable del registro</label>
									<div class="controls">
										<input type="text" class="span3" name="responsable" value="<?=$resultadoD['usr_nombre'];?>" readonly>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Fecha última modificación</label>
									<div class="controls">
										<input type="text" class="span3" name="fechaUltima" value="<?=$resultadoD['fact_ultima_modificacion'];?>" readonly>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Responsable de la modificación</label>
									<div class="controls">
										<input type="text" class="span3" name="responsableMod" value="<?=$usuarioMod['usr_nombre'];?>" readonly>
									</div>
								</div>
                               
								<div class="form-actions">
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
								</div>
							</form>
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