<?php
include("sesion.php");
$idPagina = 416;
include("includes/verificar-paginas.php");
include("includes/head.php");
?>
	<script src="js/jquery.js"></script>

	<!-- Exportar a excel -->
	<script src="../librerias/devexpress/web/polyfill.min.js"></script>
	<script src="../librerias/devexpress/web/exceljs.min.js"></script>
	<script src="../librerias/devexpress/web/FileSaver.min.js"></script>

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
<?php include("includes/funciones-js.php");?>
</head>
<body>

	<div class="layout">
		<?php include("includes/encabezado.php");?>

		<div class="main-wrapper">
			<div class="container-fluid">
				<?php include("includes/notificaciones.php");?>

				<div class="row-fluid">

					<div class="span2">
						<div class="content-widgets light-gray">
							<div class="widget-head green">
								<h5 align="center" style="color:white;">Listado de KPIs</h5>
							</div>

							<div class="widget-container">
								<a href="#" style="margin-bottom:10px;" id="kpi1">1. Número de ventas</a><br>
								<a href="#" style="margin-bottom:10px;" id="kpi2">2. Promedio de valor venta por factura</a><br>
								<a href="#" style="margin-bottom:10px;" id="kpi3">3. Tiempo (días) Promedio de Cierre de Ventas</a><br>
								<a href="#" style="margin-bottom:10px;" id="kpi4">4. Cumplimiento de la cuota comercial</a><br>
								<a href="#" style="margin-bottom:10px;" id="kpi5">5. Tasa de conversión de prospecto a cliente</a><br>
								<a href="#" style="margin-bottom:10px;" id="kpi6">6. Ejecución de Demostraciones</a><br>
								<a href="#" style="margin-bottom:10px;" id="kpi7">7. Número de llamadas enviadas por ejecutivo de prospección</a><br>
								<a href="#" style="margin-bottom:10px;" id="kpi8">8. Clientes efectivos por evento</a><br>
								<a href="#" style="margin-bottom:10px;" id="kpi9">9. Nuevos subdistribuidores</a><br>
								<a href="#" style="margin-bottom:10px;" id="kpi10">10. Captación de clientes instituciones</a><br>

								<a href="#" style="margin-bottom:10px;" id="kpi11">11. Número de visitas realizadas</a><br>
								<!--
								<a href="#" style="margin-bottom:10px;" id="kpi12">12. Tasa de éxito de las licitaciones</a><br>
								<a href="#" style="margin-bottom:10px;" id="kpi13">13. Participación en Eventos</a><br>
								-->

							</div>
						</div>
					</div>

					<div class="span10">
						<div class="content-widgets light-gray">
							<div class="widget-head green ">
								<h3><?=$paginaActual['pag_nombre'];?> </h3>
							</div>

							<div class="widget-container">
								<div class="row">
									<div class="span12" align="center">
										<strong style="font-weight: bold; font-size: 30px;"> <span id="divEncabezadoPki">SELECCIONA UN KPI</span></strong>
									</div>
								</div>
								<br>
								<div class="row ">
									<div class="span1">
									</div>
									<div class="span11">
										<div class="form-group row">
											<div id="grdDatos"></div>
											<br><hr><br>
											<div id="grdDatosChart"></div>
											<br><br>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="js/kpis.js" type="module"> </script>
	<?php include("includes/pie.php"); ?>
	</div>

</body>
</html>