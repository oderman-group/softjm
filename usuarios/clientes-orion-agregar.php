<?php 
include("sesion.php");

$idPagina = 187;

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

<script type="text/javascript">
	function clientesVerificar(enviada) {
		var usuario = enviada.value;
		var opcion = 2;
		$('#resp').empty().hide().html("esperando...").show(1);
		datos = "usuario=" + (usuario) +
			"&opcion=" + (opcion);
		$.ajax({
			type: "POST",
			url: "ajax/ajax-clientes-verificar.php",
			data: datos,
			success: function(data) {
				$('#resp').empty().hide().html(data).show(1);
			}
		});

	}
</script>
<?php include("includes/funciones-js.php"); ?>

<?php include("includes/texto-editor.php"); ?>
</head>

<body>
	<div class="layout">
		<?php include("includes/encabezado.php"); ?>

		

		<div class="main-wrapper">
			<div class="container-fluid">
				<div class="row-fluid ">
					<div class="span12">
						<div class="primary-head">
							<h3 class="page-header"><?= $paginaActual['pag_nombre']; ?></h3>
						</div>
						<ul class="breadcrumb">
							<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
							<li><a href="clientes-orion.php">Clientes ORION</a><span class="divider"><i class="icon-angle-right"></i></span></li>
							<li class="active"><?= $paginaActual['pag_nombre']; ?></li>
						</ul>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<div class="content-widgets gray">
							<div class="widget-head bondi-blue">
								<h3> <?= $paginaActual['pag_nombre']; ?></h3>
							</div>
							<div class="widget-container">
								<form class="form-horizontal" method="post" action="bd_create/guardar-clientes-orion.php" enctype="multipart/form-data">

									<fieldset class="default">
										<legend>Datos básicos</legend>


										<div class="control-group">
											<label class="control-label">Contrato</label>
											<div class="controls">
												<input type="file" class="span8" name="contrato">
											</div>
										</div>




										<div class="control-group">
											<label class="control-label">Empresa (*)</label>
											<div class="controls">
												<input type="text" class="span8" name="nombre" style="text-transform:uppercase;" required>
											</div>
										</div>


										<div class="control-group">
											<label class="control-label">Email</label>
											<div class="controls">
												<input type="email" class="span8" name="email" style="text-transform:lowercase;">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Teléfono</label>
											<div class="controls">
												<input type="text" class="span4" name="telefono">
											</div>
										</div>

                                
										<div class="control-group">
											<label class="control-label">Modulos</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="modulo[]" multiple>
													<option value=""></option>
													<?php
													$conOp = $conexionBdAdmin->query("SELECT * FROM modulos");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>" ><?=$resOp['mod_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
										</div>


										<div class="control-group">
											<label class="control-label">Contacto principal</label>
											<div class="controls">
												<input type="text" class="span6" name="contacto" style="text-transform:uppercase;">
											</div>
										</div>


										<div class="control-group">
											<label class="control-label">Fecha inicio</label>
											<div class="controls">
												<input type="date" class="span4" name="inicio" style="text-transform:uppercase;">
											</div>
										</div>


										<div class="control-group">
											<label class="control-label">Fecha fin</label>
											<div class="controls">
												<input type="date" class="span4" name="fin" style="text-transform:uppercase;">
											</div>
										</div>

									</fieldset>


									<div class="form-actions">
										<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
										<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									</div>


							</div>
						</div>
					</div>
				</div>


				<!--
             <div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?= $paginaActual['pag_nombre']; ?></h3>
						</div>
						<div class="widget-container">
                                
                                <div class="control-group">
									<label class="control-label">Alcance</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 100%" class="tinymce-simple" name="alcance"></textarea>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Primera auditoría</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 100%" class="tinymce-simple" name="pa"></textarea>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Segunda auditoría</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 100%" class="tinymce-simple" name="sa"></textarea>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Renovación</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 100%" class="tinymce-simple" name="renovacion"></textarea>
									</div>
								</div>

								<div class="form-actions">
									<a href="clientes.php" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
								</div>
                              
                            </form>    

						</div>
					</div>
				</div>
			</div>
            -->


			</div>
		</div>
		<?php include("includes/pie.php"); ?>
	</div>
</body>

</html>