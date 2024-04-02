<?php
include("sesion.php");

$idPagina = 171;
include("includes/verificar-paginas.php");
include("includes/head.php");

$consulta = $conexionBdAdmin->query("SELECT * FROM clientes_orion WHERE clio_id='" . $_GET["id"] . "'");
$resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);
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
					<div class="span6">
						<div class="content-widgets gray">
							<div class="widget-head bondi-blue">
								<h3> <?= $paginaActual['pag_nombre']; ?></h3>
							</div>
							<div class="widget-container">
								<form class="form-horizontal" method="post" action="bd_update/actualizar-cliente-orion.php" enctype="multipart/form-data">
									<input type="hidden" name="id" value="<?= $_GET["id"]; ?>">

									<fieldset class="default">
										<legend>Datos básicos</legend>

										<?php if ($resultadoD['clio_contrato'] != "") { ?>
											<p align="left" style="margin: 10px;"><a href="files/contratos/<?= $resultadoD['clio_contrato']; ?>" target="_blank">Descargar</a></p>
										<?php } ?>

										<div class="control-group">
											<label class="control-label">Contrato</label>
											<div class="controls">
												<input type="file" class="span8" name="contrato">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Empresa (*)</label>
											<div class="controls">
												<input type="text" class="span8" name="nombre" style="text-transform:uppercase;" required value="<?= $resultadoD['clio_empresa']; ?>">
											</div>
										</div>


										<div class="control-group">
											<label class="control-label">Email</label>
											<div class="controls">
												<input type="email" class="span8" name="email" style="text-transform:lowercase;" value="<?= $resultadoD['clio_email']; ?>">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Teléfono</label>
											<div class="controls">
												<input type="text" class="span4" name="telefono" value="<?= $resultadoD['clio_telefono']; ?>">
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">Contacto principal</label>
											<div class="controls">
												<input type="text" class="span6" name="contacto" style="text-transform:uppercase;" value="<?= $resultadoD['clio_contacto_principal']; ?>">
											</div>
										</div>


										<div class="control-group">
											<label class="control-label">Fecha inicio</label>
											<div class="controls">
												<input type="date" class="span4" name="inicio" style="text-transform:uppercase;" value="<?= $resultadoD['clio_fecha_inicio']; ?>">
											</div>
										</div>


										<div class="control-group">
											<label class="control-label">Fecha fin</label>
											<div class="controls">
												<input type="date" class="span4" name="fin" style="text-transform:uppercase;" value="<?= $resultadoD['clio_fecha_fin']; ?>">
											</div>
										</div>

									</fieldset>


									<div class="form-actions">
										<a href="clientes-orion.php;" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
										<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									</div>
								</form>

							</div>
						</div>
					</div>
					<div class="span6">
						<div class="content-widgets gray">
							<div class="widget-head bondi-blue">
								<h3> <?= $paginaActual['pag_nombre']; ?></h3>
							</div>
							<div class="widget-container">

								<legend>Modulos</legend>
								<div class="widget-container">

									<ul class="nav nav-tabs" id="myTab1">
										<?php
										$queryModulos = "SELECT * FROM modulos WHERE mod_padre IS NULL";
										$resultModulos = $conexionBdAdmin->query($queryModulos);
										$conta = 1;
										while ($rowModulos = $resultModulos->fetch_assoc()) {
										?>
											<li <?php if ($conta == 2) echo 'class="active"'; ?> id="mod<?= $rowModulos['mod_id']; ?>"><a onclick="mostrarModulos(<?= $rowModulos['mod_id']; ?>, <?= $_GET['id']; ?>)"><i class="icon-tasks"></i> <?= $rowModulos['mod_nombre']; ?></a></li>
										<?php
											$conta++;
										}
										?>
									</ul>

									<form class="form-horizontal" method="post" action="bd_update/actualizar-modulos-cliente.php">
										<input type="hidden" name="id" value="<?= $resultadoD['clio_id']; ?>">
										<div class="tab-content">
											<div id="divTableModulos"></div>
										</div>

										<select id="ModulosSeleccionadas" name="ModulosS[]" multiple style="display: none;">
											<?php
											$consultaModulo = $conexionBdAdmin->query("SELECT mxe_id_modulo FROM modulos_empresa WHERE mxe_id_empresa = '" . $resultadoD['clio_id'] . "'");
											while ($page = $consultaModulo->fetch_assoc()) {
												echo '<option value="' . $page["mxe_id_modulo"] . '" id="pag-' . $page["mxe_id_modulo"] . '" selected>' . $page["mxe_id_modulo"] . '</option>';
											}
											?>
										</select>

										<div class="form-actions">
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include("includes/pie.php"); ?>
</body>
<script src="js/Modulos.js"></script>
<script type="text/javascript">
	$(document).ready(mostrarModulos(2, <?= $resultadoD['clio_id']; ?>));
</script>

</html>