<?php 
include("sesion.php");

$idPagina = 235;

include("includes/verificar-paginas.php");
include("includes/head.php");

$consultaCodigo=mysqli_query($conexionBdPrincipal,"SELECT * FROM cupones WHERE cupo_id='".$_GET['id']."'");
$resultadoD = mysqli_fetch_array($consultaCodigo, MYSQLI_BOTH);
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
                        <li><a href="cupones.php">Cupones</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_update/cupones-actualizar.php" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
							
                                <div class="control-group">
									<label class="control-label">Código</label>
									<div class="controls">
										<input type="text" class="span4" name="codigo" readonly value="<?=$resultadoD['cupo_codigo']?>">
										<span style="color:#009;">Éste código es generado automáticamente al crearse el cupo.</span>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Compra mínima</label>
									<div class="controls">
										<input type="number" class="span3" name="compraMinima" value="<?=$resultadoD['cupo_compra_minima']?>">
                                        <span style="color:#009;">Digite sólo el valor numérico. Sin puntos, ni comas, ni simbolos.</span>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Descuento (%)</label>
									<div class="controls">
										<input type="text" class="span1" name="descuento" maxlength="2" value="<?=$resultadoD['cupo_descuento']?>">
                                        <span style="color:#009;">Digite el valor sin el signo (%). Ejemplo 10</span>
									</div>
								</div>
								
                                <div class="control-group">
									<label class="control-label">Fecha de vencimiento</label>
									<div class="controls">
										<input type="date" class="span3" name="fechaVencimiento" value="<?=$resultadoD['cupo_vencimiento']?>">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Cliente asignado</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente">
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
												if($datosUsuarioActual[3]!=1){
													$consultaZonas=mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$resOp['cli_zona']."'");
													$numZ = mysqli_num_rows($consultaZonas);
													if($numZ==0) continue;
												}
											?>
                                            	<option value="<?=$resOp['cli_id'];?>" <?php if($resultadoD['cupo_cliente']==$resOp['cli_id']) echo "selected";?>><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Estado</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span3" tabindex="2" name="estado">
											<option value=""></option>
                                            <option value="1" <?php if($resultadoD['cupo_activo']==1) echo "selected";?>>Activo</option>
                                            <option value="0" <?php if($resultadoD['cupo_activo']==0) echo "selected";?>>Inactivo</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Redimido</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span3" tabindex="2" name="redimido">
											<option value=""></option>
                                            <option value="1" <?php if($resultadoD['cupo_redimido']==1) echo "selected";?>>SI</option>
                                            <option value="0" <?php if($resultadoD['cupo_redimido']==0) echo "selected";?>>NO</option>
                                    	</select>
                                    </div>
                               </div>
                               
								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button type="submit" class="btn btn-info"><i class="icon-money"></i> Generar cupón</button>
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