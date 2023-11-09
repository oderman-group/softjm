<?php 
include("sesion.php");

$idPagina = 260;
$paginaActual['pag_nombre'] = "Agregar producto al cliente";

include("includes/verificar-paginas.php");
include("includes/head.php");
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
                        <li><a href="facturacion.php">Facturas</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_create/facturacion-guardar.php" enctype="multipart/form-data">
                            <input type="hidden" name="cte" value="<?=$_GET["cte"];?>">

                                
                                <div class="control-group">
									<label class="control-label">Fecha</label>
									<div class="controls">
										<input type="date" class="span3" name="fechaFactura" required value="<?=date("Y-m-d");?>">
									</div>
								</div>
                                
                                
                                <div class="control-group">
									<label class="control-label">Cliente</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente">
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id_empresa='".$idEmpresa."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
												if($datosUsuarioActual[3]!=1){
													$consultaNumZonas=mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$resOp['cli_zona']."'");
													$numZ = mysqli_num_rows($consultaNumZonas);
													if($numZ==0) continue;
												}
											?>
                                            	<option value="<?=$resOp['cli_id'];?>" <?php if(isset($_GET["cte"]) and $_GET["cte"]!="" and $_GET["cte"]==$resOp[0]) echo "selected";?>><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                                
                                <div class="control-group">
									<label class="control-label">Productos</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span10" tabindex="2" name="producto[]" multiple>
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_soptec 
											LEFT JOIN productos_categorias ON catp_id=prod_categoria
											WHERE prod_id_empresa='".$idEmpresa."'
											ORDER BY prod_nombre");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
												$consultaMaterial=mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_materiales 
												WHERE ppmt_producto='".$resOp['prod_id']."'");
												$materiales = mysqli_num_rows($consultaMaterial);
												if($materiales == 0) {continue;}
											?>
                                            	<option value="<?=$resOp['prod_id'];?>"><?=$resOp['prod_referencia']." - ".$resOp['prod_nombre'].", ".$resOp['catp_nombre']." (".$materiales." Mat.)";?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>

                               
								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button type="submit" class="btn btn-info"><i class="icon-money"></i> Guardas cambios</button>
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