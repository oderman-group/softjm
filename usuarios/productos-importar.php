<?php
include("sesion.php");

$idPagina = 21;
$idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];
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

include("includes/funciones-js.php");

include("includes/texto-editor.php");
?>
</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
	<div class="main-wrapper">
		<div class="container-fluid">
			<?php include("includes/notificaciones.php"); ?>
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
                        <li><a href="productos.php">Productos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head green">
							<h3> Exportar productos</h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="get" action="productos-exportar.php">
                               <div class="control-group">
									<label class="control-label">Grupo 1</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="grupo1">
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=1 AND catp_id_empresa='".$idEmpresa."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
												<option value="<?=$resOp[0];?>"><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
                               <div class="control-group">
									<label class="control-label">Grupo 2</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="grupo2">
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=2  AND catp_id_empresa='".$idEmpresa."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Marcas</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="marca">
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM marcas WHERE mar_id_empresa='".$idEmpresa."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
								<div class="control-group">
									<label class="control-label">Qué productos?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="tipoProductos">
											<option value="1">Todos</option>
											<option value="2">Sólo los de la SOTRE</option>
											<option value="3">Sólo los predeterminados</option>
                                    	</select>
                                    </div>
                               </div>
								<?php }else{?>
									<input type="hidden" name="tipoProductos" value="1">
								<?php }?>
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Exportar</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span12">
					<!--
					<div style="background-color: antiquewhite; padding: 5px; margin: 10px;">
					<h2>Información importante</h2>
						<p><b>1.</b> Descargue la plantilla de excel. <a href="productos-exportar.php" target="_blank">[Descargar plantilla]</a></p>
						<p><b>2.</b> Llene la información de sus productos en la planilla descargada y guardela con ese mismo formato que ya trae la plantilla (Excel 97-2003).</p>
						<p><b>3.</b> Suba la planilla de excel.</p>
					</div>
					-->
					
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>

						<div class="row-fluid">
							<div class="span12">
								<div class="hero-unit">
									<h2>Existencias</h2>
									<p>
										Las existencias ya no serán tomadas en cuenta desde este archivo. Todas las existencias se manejan por bodegas.
									</p>
								</div>
							</div>
						</div>

						<div class="widget-container">
							<form class="form-horizontal" method="post" action="productos-importar-excel.php" enctype="multipart/form-data">
                                <fieldset class="default" style="background:#FFC;">
                                   	<legend>Información básica</legend>
									<div class="control-group">
										<div class="controls">
											<div class="fileupload fileupload-new" data-provides="fileupload">
												<div class="input-append">
													<div class="uneditable-input span3">
														<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
													</div>
													<span class="btn btn-file"><span class="fileupload-new">Seleccionar plantilla</span><span class="fileupload-exists">Cambiar</span>
													<input type="file" name="planilla"/>
													</span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
												</div>
											</div>
										</div>
									</div>
                                
									<div class="control-group">
										<label class="control-label">Coloque el número de la última fila hasta donde quiere que el archivo sea leido</label>
										<div class="controls">
											<input type="number" class="span3" name="filaFinal" id="filaFinal" value="200" required><br>
											<span style="font-size: 12px; color:#6017dc;">Fila hasta donde hay información de los estudiantes y acudientes. Esto se usa para evitar que se lean filas que no tienen información.</span>
										</div>
									</div> 
                                </fieldset>   
								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button type="submit" class="btn btn-info"><i class="icon-save"></i> Iniciar importación</button>
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