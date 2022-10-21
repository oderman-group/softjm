<?php include("sesion.php"); ?>
<?php
$resultadoD = mysql_fetch_array(mysql_query("SELECT * FROM proveedores WHERE prov_id='" . $_GET["id"] . "'", $conexion));

$idPagina = 125;
$paginaActual['pag_nombre'] = "Editar proveedores";
?>
<?php include("verificar-paginas.php"); ?>
<?php include("head.php"); ?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('" . $_SESSION["id"] . "', '" . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "', '" . $idPagina . "', now(),'" . $_SERVER['HTTP_REFERER'] . "')", $conexion);
if (mysql_errno() != 0) {
	echo mysql_error();
	exit();
}
?>

<!-- styles -->

<!--[if IE 7]>
<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
<![endif]-->
<link href="css/chosen.css" rel="stylesheet">


<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->

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
<script type="text/javascript">
	/*====TAGS INPUT====*/
	$(function() {
		$('#tags_1').tagsInput({
			width: 'auto'
		});
		$('#tags_2').tagsInput({
			width: 'auto',
			onChange: function(elem, elem_tags) {
				var languages = ['php', 'ruby', 'javascript'];
				$('.tag', elem_tags).each(function() {
					if ($(this).text().search(new RegExp('\\b(' + languages.join('|') + ')\\b')) >= 0) $(this).css('background-color', 'yellow');
				});
			}
		});
	});
	/*====Select Box====*/
	$(function() {
		$(".chzn-select").chosen();
		$(".chzn-select-deselect").chosen({
			allow_single_deselect: true
		});
	});
	/*====Color Picker====*/
	$(function() {
		$('.colorpicker').colorpicker({
			format: 'hex'
		});
		$('.pick-color').colorpicker();
	});
	/*====DATE Time Picker====*/
	$(function() {
		$('#datetimepicker1').datetimepicker({
			language: 'pt-BR'
		});
	});
	$(function() {
		$('#datetimepicker3').datetimepicker({
			pickDate: false
		});
	});
	$(function() {
		$('#datetimepicker4').datetimepicker({
			pickTime: false
		});
	});
	/*DATE RANGE PICKER*/
	$(function() {
		$('#reportrange').daterangepicker({
				ranges: {
					'Today': ['today', 'today'],
					'Yesterday': ['yesterday', 'yesterday'],
					'Last 7 Days': [Date.today().add({
						days: -6
					}), 'today'],
					'Last 30 Days': [Date.today().add({
						days: -29
					}), 'today'],
					'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
					'Last Month': [Date.today().moveToFirstDayOfMonth().add({
						months: -1
					}), Date.today().moveToFirstDayOfMonth().add({
						days: -1
					})]
				},
				opens: 'left',
				format: 'MM/dd/yyyy',
				separator: ' to ',
				startDate: Date.today().add({
					days: -29
				}),
				endDate: Date.today(),
				minDate: '01/01/2012',
				maxDate: '12/31/2013',
				locale: {
					applyLabel: 'Submit',
					fromLabel: 'From',
					toLabel: 'To',
					customRangeLabel: 'Custom Range',
					daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
					monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
					firstDay: 1
				},
				showWeekNumbers: true,
				buttonClasses: ['btn-danger']
			},
			function(start, end) {
				$('#reportrange span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString('MMMM d, yyyy'));
			});
		//Set the initial state of the picker label
		$('#reportrange span').html(Date.today().add({
			days: -29
		}).toString('MMMM d, yyyy') + ' - ' + Date.today().toString('MMMM d, yyyy'));
	});
	$(function() {
		$('#reservation').daterangepicker();
	});
</script>

<script type="text/javascript">
	function clientesVerificar(enviada) {
		var usuario = enviada.value;
		var opcion = 2;
		$('#resp').empty().hide().html("esperando...").show(1);
		datos = "usuario=" + (usuario) +
			"&opcion=" + (opcion);
		$.ajax({
			type: "POST",
			url: "ajax-clientes-verificar.php",
			data: datos,
			success: function(data) {
				$('#resp').empty().hide().html(data).show(1);
			}
		});

	}
</script>
<?php include("funciones-js.php"); ?>

<?php include("texto-editor.php"); ?>
</head>

<body>
	<div class="layout">
		<?php include("encabezado.php"); ?>

		

		<div class="main-wrapper">
			<div class="container-fluid">
				<div class="row-fluid ">
					<div class="span12">
						<div class="primary-head">
							<h3 class="page-header"><?= $paginaActual['pag_nombre']; ?></h3>

							<ul class="top-right-toolbar">
								<li><a data-toggle="dropdown" class="dropdown-toggle blue-violate" href="#" title="Users"><i class="icon-user"></i></a>
								</li>
								<li><a href="#" class="green" title="Upload"><i class=" icon-upload-alt"></i></a></li>
								<li><a href="#" class="bondi-blue" title="Settings"><i class="icon-cogs"></i></a></li>
							</ul>

						</div>
						<ul class="breadcrumb">
							<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
							<li><a href="proveedores.php">Proveedores</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
								<form class="form-horizontal" method="post" action="sql.php" enctype="multipart/form-data">
									<input type="hidden" name="idSql" value="71">
									<input type="hidden" name="id" value="<?= $_GET["id"]; ?>">

									<fieldset class="default">
										<legend>Datos básicos</legend>

										<?php if ($resultadoD['prov_logo'] != "") { ?>
											<p align="left" style="margin: 10px;"><img src="files/proveedores/<?= $resultadoD['prov_logo']; ?>" width="80"></p>
										<?php } ?>

										<div class="control-group">
											<label class="control-label">Logo</label>
											<div class="controls">
												<input type="file" class="span8" name="logo">
											</div>
										</div>


										<div class="control-group">
											<label class="control-label">DNI</label>
											<div class="controls">
												<input type="text" class="span4" name="dni" autocomplete="off" onChange="clientesVerificar(this)" value="<?= $resultadoD['prov_documento']; ?>">
												<span style="color:#F03;">Este valor sin puntos ni espacios.</span>
											</div>
											<span id="resp"></span>
										</div>



										<div class="control-group">
											<label class="control-label">Nombre (*)</label>
											<div class="controls">
												<input type="text" class="span8" name="nombre" style="text-transform:uppercase;" required value="<?= $resultadoD['prov_nombre']; ?>">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Régimen</label>
											<div class="controls">
												<input type="text" class="span6" name="regimen" style="text-transform:uppercase;" value="<?= $resultadoD['prov_tipo_regimen']; ?>">
											</div>
										</div>


										<div class="control-group">
											<label class="control-label">Email</label>
											<div class="controls">
												<input type="email" class="span8" name="email" style="text-transform:lowercase;" value="<?= $resultadoD['prov_email']; ?>">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Teléfono</label>
											<div class="controls">
												<input type="text" class="span4" name="telefono" value="<?= $resultadoD['prov_telefono']; ?>">
											</div>
										</div>


										<div class="control-group">
											<label class="control-label">Pais</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="pais">
													<option value="1"></option>
													<?php
													$conOp = mysql_query("SELECT * FROM localidad_paises ORDER BY pais_nombre", $conexion);
													while ($resOp = mysql_fetch_array($conOp)) {
													?>
														<option value="<?= $resOp[0]; ?>" <?php if ($resultadoD['prov_pais'] == $resOp['pais_id']) {
																							echo "selected";
																						} ?>><?= $resOp['pais_nombre']; ?></option>
													<?php
													}
													?>
												</select>
											</div>
										</div>


										<div class="control-group">
											<label class="control-label">Ciudad</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="ciudad">
													<option value="1"></option>
													<?php
													$conOp = mysql_query("SELECT * FROM localidad_ciudades INNER JOIN localidad_departamentos ON dep_id=ciu_departamento ORDER BY ciu_nombre", $conexion);
													while ($resOp = mysql_fetch_array($conOp)) {
													?>
														<option value="<?= $resOp['ciu_id']; ?>" <?php if ($resultadoD['prov_ciudad'] == $resOp['ciu_id']) {
																										echo "selected";
																									} ?>><?= $resOp['ciu_nombre'] . ", " . $resOp['dep_nombre']; ?></option>
													<?php
													}
													?>
												</select>
											</div>
										</div>


										<div class="control-group">
											<label class="control-label">Ciudad internacional</label>
											<div class="controls">
												<input type="text" class="span6" name="otraCiudad" value="<?= $resultadoD['prov_otra_ciudad']; ?>">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Dirección</label>
											<div class="controls">
												<input type="text" class="span6" name="direccion" style="text-transform:uppercase;" value="<?= $resultadoD['prov_direccion']; ?>">
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
		<?php include("pie.php"); ?>
	</div>
</body>

</html>