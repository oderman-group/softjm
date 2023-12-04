<?php
include("sesion.php"); 
$idPagina = 1;
include("includes/head.php");
?>

<!-- styles -->
<link href="css/jquery.gritter.css" rel="stylesheet">
<link href="css/tablecloth.css" rel="stylesheet">
<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.sparkline.js"></script>
<script src="js/bootstrap-fileupload.js"></script>
<script src="js/jquery.metadata.js"></script>
<script src="js/jquery.tablesorter.min.js"></script>
<script src="js/jquery.tablecloth.js"></script>
<script src="js/jquery.flot.js"></script>
<script src="js/jquery.flot.selection.js"></script>
<script src="js/excanvas.js"></script>
<script src="js/jquery.flot.pie.js"></script>
<script src="js/jquery.flot.stack.js"></script>
<script src="js/jquery.flot.time.js"></script>
<script src="js/jquery.flot.tooltip.js"></script>
<script src="js/jquery.flot.resize.js"></script>
<script src="js/jquery.collapsible.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.gritter.js"></script>
<script src="js/tiny_mce/jquery.tinymce.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>


<script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"></script>
<script src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"></script>
<script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"></script>
<script src="https://cdn.anychart.com/releases/v8/js/anychart-pyramid-funnel.min.js?hcode=c11e6e3cfefb406e8ce8d99fa8368d33"></script>
<link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css?hcode=c11e6e3cfefb406e8ce8d99fa8368d33" type="text/css" rel="stylesheet">
<link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css?hcode=c11e6e3cfefb406e8ce8d99fa8368d33" type="text/css" rel="stylesheet">

<style type="text/css">
	#productos {
		width: 100%;
		height: 100%;
		margin: 0;
		padding: 0;
	}
</style>


<script>
	anychart.onDocumentReady(function() {
		// create column chart
		var chart = anychart.column();

		// turn on chart animation
		chart.animation(true);

		// set chart title text settings
		chart.title('10 productos más vendidos');

		// create area series with passed data
		var series = chart.column([
			<?= $cotizacionesResultados; ?>
		]);

		// set series tooltip settings
		series.tooltip().titleFormat('{%X}');

		series.tooltip()
			.position('center-top')
			.anchor('center-bottom')
			.offsetX(0)
			.offsetY(5)
			.format('{%Value}{groupsSeparator: }');

		// set scale minimum
		chart.yScale().minimum(0);

		// set yAxis labels formatter
		chart.yAxis().labels().format('{%Value}{groupsSeparator: }');

		// tooltips position and interactivity settings
		chart.tooltip().positionMode('point');
		chart.interactivity().hoverMode('by-x');

		// axes titles
		chart.xAxis().title('Productos');
		chart.yAxis().title('Valor');

		// set container id for the chart
		chart.container('productos');

		// initiate chart drawing
		chart.draw();
	});
</script>


<?php include("includes/funciones-js.php"); ?>

</head>

<body>
	<div class="layout">
		<?php include("includes/encabezado.php"); ?>

		

		<div class="main-wrapper">

			<div class="container-fluid">


				<div id="cargarAxios"></div>


				<div class="row-fluid">

					<div class="span3">
						<div class="board-widgets small-widget" style="background-color: #fbbd01;">
							<a href="panel-menu.php?p=1"><span class="widget-icon icon-phone"></span><span class="widget-label">CRM</span></a>
						</div>
					</div>


					<?php if ($datosUsuarioActual[3] == 1 or $datosUsuarioActual[3] == 9 or $datosUsuarioActual[3] == 10 or $datosUsuarioActual[3] == 15 or $datosUsuarioActual[3] == 14) { ?>
						<div class="span3">
							<div class="board-widgets small-widget" style="background-color: #eb4132;">
								<a href="cambio-version.php?idSesion=<?= $_SESSION["id"]; ?>"><span class="widget-icon icon-cogs"></span><span class="widget-label">Servicio técnico</span></a>
							</div>
						</div>
					<?php } ?>


				</div>


				<div class="row-fluid">
					<?php if ($_SESSION["id"] == 128 or $_SESSION["id"] == 15 or $_SESSION["id"] == 17) { ?>

						<div class="span3">
							<div class="board-widgets small-widget" style="background-color: dimgray;">
								<a href="ventas.php"><span class="widget-icon icon-folder-close"></span><span class="widget-label">Ventas</span></a>
							</div>
						</div>
					<?php } ?>


				</div>



				<style>
					#containerg1 {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
}
				</style>
				
				<script>
anychart.onDocumentReady(function () {

// create data
var data = [
  ["Mafe", 100000],
  ["Leidy", 12000],
  ["Jaime", 103000],
  ["Karen", 10000],
  ["Jhon", 9000]
];

// create a chart
var chart = anychart.column();

// create a column series and set the data
var series = chart.column(data);

// set the chart title
chart.title("Ventas del mes");

// set the titles of the axes
chart.xAxis().title("Asesores");
chart.yAxis().title("Ventas, $");

// set the container id
chart.container("containerg1");

// initiate drawing the chart
chart.draw();
});

				</script>

				<div class="row-fluid">
					<div class="span6">

					<!--<div id="containerg1"></div>-->


					</div>
				</div>

			</div>




		</div>



		<?php include("includes/pie.php"); ?>
	</div>
</body>

</html>