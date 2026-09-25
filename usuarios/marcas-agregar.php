<?php 
include("sesion.php");

$idPagina = 34;

include("includes/verificar-paginas.php");
include_once(RUTA_PROYECTO."/usuarios/includes/api-ofima-conexion.php");
$ofimaActiva = ofimaIntegracionActiva($conexionBdPrincipal, (int) $_SESSION["dataAdicional"]["id_empresa"]);
include("includes/head.php");
?>
<!-- styles -->
<link href="css/chosen.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
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
						<li><a href="marcas.php">Marcas</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_create/marcas-guardar.php">
                                
                                <div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" class="span4" name="nombre">
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Código Ofima <?php if ($ofimaActiva) { ?><span class="text-error">*</span><?php } ?></label>
									<div class="controls">
										<input type="text" class="span4" name="cod_ofima" maxlength="50" <?php if ($ofimaActiva) { echo 'required'; } ?>>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Habilitada</label>
									<div class="controls">
										<label><input type="checkbox" name="habilitada" value="1" checked> La marca está en funcionamiento</label>
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