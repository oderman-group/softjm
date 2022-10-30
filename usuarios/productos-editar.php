<?php include("sesion.php"); ?>
<?php
$idPagina = 38;
$paginaActual['pag_nombre'] = "Editar productos";
?>
<?php include("includes/verificar-paginas.php"); ?>
<?php include("includes/head.php"); ?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('" . $_SESSION["id"] . "', '" . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "', '" . $idPagina . "', now(),'" . $_SERVER['HTTP_REFERER'] . "')", $conexion);
if (mysql_errno() != 0) {
	echo mysql_error();
	exit();
}
?>
<?php
$resultadoD = mysql_fetch_array(mysql_query("SELECT * FROM productos WHERE prod_id='" . $_GET["id"] . "'", $conexion));

$precioListaUSD = productosPrecioListaUSD($resultadoD['prod_utilidad'], $resultadoD['prod_costo_dolar']);
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
  function repetidosVerificar(enviada){
  var idUnico = enviada.value;
  var opcion = 3;	  
	  $('#resp').empty().hide().html("esperando...").show(1);
		datos = "idUnico="+(idUnico)+
				"&opcion="+(opcion);
			   $.ajax({
				   type: "POST",
				   url: "ajax-clientes-verificar.php",
				   data: datos,
				   success: function(data){
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

							<ul class="top-right-toolbar">
								<li><a data-toggle="dropdown" class="dropdown-toggle blue-violate" href="#" title="Users"><i class="icon-user"></i></a>
								</li>
								<li><a href="#" class="green" title="Upload"><i class=" icon-upload-alt"></i></a></li>
								<li><a href="#" class="bondi-blue" title="Settings"><i class="icon-cogs"></i></a></li>
							</ul>

						</div>
						<ul class="breadcrumb">
							<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
							<li><a href="productos.php">Productos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
							<li class="active"><?= $paginaActual['pag_nombre']; ?></li>
						</ul>
					</div>
				</div>
				<p>
				<a href="productos-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>&nbsp;&nbsp;
				<a href="bodegas-productos.php?prod=<?= $_GET["id"]; ?>" class="btn btn-warning"><i class="icon-pushpin"></i> Ver en bodegas</a>
				</p>


				<?php include("includes/notificaciones.php"); ?>
				<div class="row-fluid">

					<div class="span3">
						<div class="content-widgets gray">
							<div class="widget-head bondi-blue">
								<h3>FOTO<h3>
							</div>
							<?php if ($resultadoD['prod_foto'] != "") { ?>
								<p align="center"><img src="files/productos/<?= $resultadoD['prod_foto']; ?>" width="80"></p>
							<?php } ?>
						</div>
					</div>

					<div class="span9">
						<div class="content-widgets gray">
							<div class="widget-head bondi-blue">
								<h3> <?= $paginaActual['pag_nombre']; ?></h3>
							</div>
							<div class="widget-container">
								<form class="form-horizontal" method="post" action="sql.php" enctype="multipart/form-data">
									<input type="hidden" name="idSql" value="20">
									<input type="hidden" name="id" value="<?= $_GET["id"]; ?>">

									<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10 or $datosUsuarioActual['usr_tipo'] == 13) { ?>
										<div class="control-group">
											<label class="control-label">CÓDIGO</label>
											<div class="controls">
												<input type="text" class="span4" name="referencia" value="<?= $resultadoD['prod_referencia']; ?>" onChange="repetidosVerificar(this)">
											</div>
											<span id="resp"></span>
										</div>
									<?php } else { ?>
										<input type="hidden" class="span4" name="referencia" value="<?= $resultadoD['prod_referencia']; ?>">
									<?php } ?>

									<div class="control-group">
										<label class="control-label">Existencias</label>
										<div class="controls">
											<input type="text" class="span4" name="cant" value="<?= $resultadoD['prod_existencias']; ?>" readonly>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">Nombre (*)</label>
										<div class="controls">
											<input type="text" class="span10" name="nombre" value="<?= $resultadoD['prod_nombre']; ?>" required>
										</div>
									</div>



									<div class="control-group">
										<label class="control-label">Foto</label>
										<div class="controls">
											<input type="file" class="span8" name="foto">
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">Descripción corta</label>
										<div class="controls">
											<textarea rows="5" cols="80" style="width: 80%" name="descripcion"><?= $resultadoD['prod_descripcion_corta']; ?></textarea>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">Descripción larga</label>
										<div class="controls">
											<textarea rows="10" cols="80" style="width: 80%" name="descripcionLarga"><?= $resultadoD['prod_descripcion_larga']; ?></textarea>
											
										</div>
										<span style="color: navy;"> Esta descripción solo se mostrará en la tienda virtual.</span>
									</div>
									


									<?php if ($configuracion['conf_proveedor_cotizacion'] == 1) { ?>

										<div class="control-group">
											<label class="control-label">Proveedor</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="proveedor" required>
													<option value=""></option>
													<?php
													$conOp = mysql_query("SELECT * FROM proveedores", $conexion);
													while ($resOp = mysql_fetch_array($conOp)) {
													?>
														<option value="<?= $resOp[0]; ?>" <?php if ($resultadoD['prod_proveedor'] == $resOp[0]) echo "selected"; ?>><?= $resOp['prov_nombre']; ?></option>
													<?php
													}
													?>
												</select>
											</div>


											<a href="proveedores-editar.php?id=<?= $resultadoD['prod_proveedor']; ?>" class="btn btn-info" target="_blank">Editar proveedor</a>


										</div>

									<?php } else { ?>
										<input type="hidden" name="proveedor" value="0">
									<?php } ?>

									<div class="control-group">
										<label class="control-label">Grupo 1 (*)</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="grupo1" required>
												<option value=""></option>
												<?php
												$conOp = mysql_query("SELECT * FROM productos_categorias WHERE catp_grupo=1", $conexion);
												while ($resOp = mysql_fetch_array($conOp)) {
												?>
													<option value="<?= $resOp[0]; ?>" <?php if ($resultadoD['prod_grupo1'] == $resOp[0]) {
																						echo "selected";
																					} ?>><?= $resOp[1]; ?></option>
												<?php
												}
												?>
											</select>
										</div>
										<a href="categoriasp-editar.php?id=<?= $resultadoD['prod_grupo1']; ?>" class="btn btn-info" target="_blank">Editar grupo 1</a>
									</div>

									<div class="control-group">
										<label class="control-label">Grupo 2 (*)</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="categoria" required>
												<option value=""></option>
												<?php
												$conOp = mysql_query("SELECT * FROM productos_categorias WHERE catp_grupo=2", $conexion);
												while ($resOp = mysql_fetch_array($conOp)) {
												?>
													<option value="<?= $resOp[0]; ?>" <?php if ($resultadoD['prod_categoria'] == $resOp[0]) {
																						echo "selected";
																					} ?>><?= $resOp[1]; ?></option>
												<?php
												}
												?>
											</select>
										</div>
										<a href="categoriasp-editar.php?id=<?= $resultadoD['prod_categoria']; ?>" class="btn btn-info" target="_blank">Editar grupo 2</a>
									</div>

									<div class="control-group">
										<label class="control-label">Marca (*)</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="marca" required>
												<option value=""></option>
												<?php
												$conOp = mysql_query("SELECT * FROM marcas", $conexion);
												while ($resOp = mysql_fetch_array($conOp)) {
												?>
													<option value="<?= $resOp[0]; ?>" <?php if ($resultadoD['prod_marca'] == $resOp[0]) {
																						echo "selected";
																					} ?>><?= $resOp[1]; ?></option>
												<?php
												}
												?>
											</select>
										</div>
										<a href="marcas-editar.php?id=<?= $resultadoD['prod_marca']; ?>" class="btn btn-info" target="_blank">Editar marca</a>
									</div>

									<?php if ($datosUsuarioActual['usr_tipo'] == 1) { ?>
										<div class="control-group">
											<label class="control-label">Costo COP ($)</label>
											<div class="controls">
												<input type="text" class="span6" name="costo" value="<?= $resultadoD['prod_costo']; ?>">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Costo USD ($)</label>
											<div class="controls">
												<input type="text" class="span6" name="costoDolar" value="<?= $resultadoD['prod_costo_dolar']; ?>">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Utilidad (%)</label>
											<div class="controls">
												<input type="text" class="span2" name="utilidad" value="<?= $resultadoD['prod_utilidad']; ?>">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Precio lista ($)</label>
											<div class="controls">
												<input type="text" class="span6" name="precio1" value="<?= number_format($resultadoD['prod_precio'], 0, ",", "."); ?>" readonly>
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Precio lista (USD)</label>
											<div class="controls">
												<input type="text" class="span6" name="precioUSD" value="<?= number_format($precioListaUSD, 0, ",", "."); ?>" readonly>
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Dcto. máximo (%)</label>
											<div class="controls">
												<input type="text" class="span2" name="dcto1" value="<?= $resultadoD['prod_descuento1']; ?>">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Comisión venta (%)</label>
											<div class="controls">
												<input type="text" class="span2" name="comision" value="<?= $resultadoD['prod_comision']; ?>">
											</div>
										</div>
									<?php } else { ?>
										<input type="hidden" class="span6" name="costo" value="<?= $resultadoD['prod_costo']; ?>">
										<input type="hidden" class="span6" name="costoDolar" value="<?= $resultadoD['prod_costo_dolar']; ?>">
										<input type="hidden" class="span2" name="utilidad" value="<?= $resultadoD['prod_utilidad']; ?>">
										<input type="hidden" class="span2" name="dcto1" value="<?= $resultadoD['prod_descuento1']; ?>">
										<input type="hidden" class="span2" name="comision" value="<?= $resultadoD['prod_comision']; ?>">
									<?php } ?>


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
		<?php include("includes/pie.php"); ?>
	</div>
</body>

</html>