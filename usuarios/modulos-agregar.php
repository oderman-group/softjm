<?php include("sesion.php");

$idPagina = 182;

include("includes/verificar-paginas.php");
include("includes/head.php");
?>

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
						<li><a href="modulos.php">Modulos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_create/modulos-guardar.php">
                                
                                <div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" class="span6" name="nombre">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Modulo Padre</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="moduloPadre">
											<option value=""></option>
                                            <?php
											$consulta = $conexionBdAdmin->query("SELECT * FROM modulos");
											while($resOp = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp['mod_id'];?>"><?=$resOp['mod_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>

                                <div class="form-actions">
								<?php if(Modulos::validarRol([183], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
               					 ?>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<?php } ?>
									<button type="button" class="btn btn-danger">Cancelar</button>
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