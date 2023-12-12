<?php 
include("sesion.php");
$idPagina = 246;

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
						<li><a href="remisiones.php">Remisiones</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            <?php include("includes/notificaciones.php");?>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="reportes/remisiones-imprimir-varias.php" target="_blank">
								<h4 class="card-title">Datos básicos</h4>
								<div class="control-group">
									<label class="control-label">Remisiones</label>
									<div class="controls">
										<select  data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" multiple="multiple" name="remisiones[]">
											<?php
											$consultaSelect = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones
											INNER JOIN clientes ON cli_id=rem_cliente
											WHERE rem_id_empresa='".$idEmpresa."'
											");
											while($datosSelect = mysqli_fetch_array($consultaSelect, MYSQLI_BOTH)){
											?>
											<option value="<?=$datosSelect[0];?>"><?=strtoupper($datosSelect['rem_id']." - ".$datosSelect['rem_equipo']." - ".$datosSelect['cli_nombre']);?></option>
											<?php }?>
										</select>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Estado</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="estado">
											<option value="">--</option>
											<option value="1">Entrada</option>
											<option value="2">Salida</option>
										</select>
									</div>
								</div>

								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<button type="submit" class="btn btn-info"> Imprimir</button>
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