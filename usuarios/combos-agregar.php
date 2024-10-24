<?php
include("sesion.php");
include(RUTA_PROYECTO."/usuarios/class/Utilidades.php");

$idPagina = 174;

include("includes/verificar-paginas.php");
include("includes/head.php");

$numPaquetes = Utilidades::getNextIdSequence($conexionBdPrincipal, MAINBD, "combos");
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
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="combos.php">Combos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_create/combos-guardar.php" enctype="multipart/form-data">
                                
                                <div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" class="span8" name="nombre" value="P <?=$numPaquetes;?> - ">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Imagen</label>
									<div class="controls">
										<input type="file" class="span8" name="foto">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Descripción</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 80%" name="descripcion"></textarea>
									</div>
								</div>
								
								<div class="control-group">
										<label class="control-label">Productos</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span10" tabindex="2" name="producto[]" multiple>
												<option value=""></option>
												<?php
												$conOp = $conexionBdPrincipal->query("SELECT * FROM productos 
												INNER JOIN productos_categorias ON catp_id=prod_categoria 
												ORDER BY prod_nombre");
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
												?>
													<option value="<?=$resOp['prod_id'];?>"><?=$resOp['prod_nombre']." - [HAY ".$resOp['prod_existencias']."]";?></option>
												<?php
												}
												?>
											</select>
										</div>
								   </div>
								
								<div class="control-group">
									<label class="control-label">Descuento</label>
									<div class="controls">
										<input type="text" class="span2" name="dcto">
									</div>
								</div>

								<!--
								<div class="control-group">
									<label class="control-label">Descuento Dealer</label>
									<div class="controls">
										<input type="text" class="span2" name="dctoDealer">
									</div>
								</div>
								-->

								<input type="hidden" class="span2" name="dctoDealer" value="0">
								
                                   
                               <div class="control-group">
									<label class="control-label">Estado</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="estado">
											<option value="0"></option>
											<option value="1">Activo</option>
											<option value="0">Inactivo</option>
                                    	</select>
                                    </div>
                               </div>

                               <div class="control-group">
									<label class="control-label">Descuento Máximo en Cotización</label>
									<div class="controls">
										<input type="text" class="span2" name="descuentoMax">
									</div>
								</div>
 
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<button type="button" class="btn btn-danger">Cancelar</button>
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