<?php
include("sesion.php");
$idPagina = 2;
include("includes/verificar-paginas.php");
include("includes/head.php");
?>
	<script src="js/jquery.js"></script>
	
	<link href="css/tablecloth.css" rel="stylesheet">

	<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/accordion.nav.js"></script>
	<script src="js/jquery.tablecloth.js"></script>
	<script src="js/jquery.dataTables.js"></script>
	<script src="js/ZeroClipboard.js"></script>
	<script src="js/dataTables.bootstrap.js"></script>
	<script src="js/TableTools.js"></script>
	<script src="js/custom.js"></script>
	<script src="js/respond.min.js"></script>
	<script src="js/ios-orientationchange-fix.js"></script>
	<script src="js/bootbox.js"></script>
	<link rel="stylesheet" href="css/modal/jquery-ui.css">
	
	<?php include("includes/overlay.php");?>

	<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  	<!-- DevExtreme theme -->
	<link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/23.2.5/css/dx.light.css">

	<!-- DevExtreme libraries (reference only one of them) 
	<script type="text/javascript" src="../assets/devexpress/web/dx.all.js"></script> -->  
	<script type="text/javascript" src="https://cdn3.devexpress.com/jslib/23.2.5/js/dx.all.js"></script>  
	<script src="https://cdn3.devexpress.com/jslib/22.2.3/js/localization/dx.messages.es.js"></script>

	<!-- Exportar a excel -->
	<script src="../librerias/devexpress/web/polyfill.min.js"></script>
	<script src="../librerias/devexpress/web/exceljs.min.js"></script>
	<script src="../librerias/devexpress/web/FileSaver.min.js"></script>
<?php include("includes/funciones-js.php");?>
</head>
<body>

	<div class="layout">
		<?php include("includes/encabezado.php");?>

		<div class="main-wrapper">
			<div class="container-fluid">
				<?php include("includes/notificaciones.php");?>

				<div class="row-fluid">
					
					<div class="span12">
						<div class="content-widgets light-gray">
							<div class="widget-head green ">
								<h3>Metas por usuarios</h3>
							</div>

							<div class="widget-container form-horizontal">	
								<div class="control-group hide">
									<label class="control-label">id_empresa</label>
									<div class="controls">
										<input type="text" id="txtIdEmpresa" value="<?=$_SESSION["dataAdicional"]["id_empresa"];?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Usuarios</label>
									<div class="controls">
										<div class="span6" id="cmbUsuarios"></div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Metas</label>
									<div class="controls">
										<div class="span6" id="cmbMetas"></div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Periodo</label>
									<div class="controls">
										<div class="span6" id="dtPeriodo"></div>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Meta</label>
									<div class="controls">
										<div class="span6" id="txtValorMeta"></div>
									</div>
								</div>								
								<div class="control-group">
									<div class="controls">
										<button class="btn btn-info" id="btnLimpiar">Limpiar formulario</button>
										<button class="btn btn-success" id="btnGuardar">Guardar</button>
										<br>
										<b><small id="txtInfoRegistro">Para modificar un registro es necesario seleccionarlo de la tabla.</small></b>
									</div>
								</div>
								<br>
								<a href="usuarios.php" class="btn btn-gray">Regresar a usuarios</a>
								<button class="btn btn-danger" id="btnEliminar">Eliminar registro</button>								
								<hr>
								<div class="control-group">    
									<div id="grdDatos"></div>
								</div> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="js/usuarios-metas.js" type="module"> </script>
	<?php include("includes/pie.php"); ?>
	</div>

</body>
</html>
