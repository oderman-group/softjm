<?php include("sesion.php"); ?>
<?php
$idPagina = 1;
$tituloPagina = "Inicio";
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
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/jquery.gritter.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="css/font-awesome.css">
<!--[if IE 7]>
<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
<![endif]-->
<link href="css/tablecloth.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<link href="css/theme-blue.css" rel="stylesheet">

<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->
<link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
<!--fav and touch icons -->
<link rel="shortcut icon" href="ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
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

<?php
$clientes = mysql_query("SELECT fpp_producto, prod_nombre, COUNT(fpp_id) AS cant FROM facturacion_productos
INNER JOIN productos ON prod_id=fpp_producto
GROUP BY fpp_producto ORDER BY cant DESC LIMIT 0,10
", $conexion);
while ($cte = mysql_fetch_array($clientes)) {
	//if($cte[3]==0) continue;
	$cotizacionesResultados .= "['#" . $cte[0] . " - " . $cte[1] . "', '" . $cte[2] . "'],";
}
?>

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


<?php include("funciones-js.php"); ?>

</head>

<body>
	<div class="layout">
		<?php include("encabezado.php"); ?>

		<?php include("barra-izq.php"); ?>

		<div class="main-wrapper">

			<div class="container-fluid">

				<!--
            <div class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<i class="icon-exclamation-sign"></i><strong>Warning!</strong> Best check yo self, you're not looking too good.
			</div>

            
     <p>
		 <a href="#" name="usuarios-agregar.php" onClick="axiosAjax(this)">Botón 1</a>
		 <a href="#" name="usuarios-editar.php?id=16" onClick="axiosAjax(this)">Botón 2</a>
			</p> -->

				<div id="cargarAxios"></div>


				<div class="row-fluid">

					<div class="span3">
						<div class="board-widgets small-widget" style="background-color: #fbbd01;">
							<a href="panel-menu.php?p=1"><span class="widget-icon icon-phone"></span><span class="widget-label">CRM</span></a>
						</div>
					</div>

					<div class="span3">
						<div class="board-widgets small-widget" style="background-color: #31a952;">
							<a href="panel-menu.php?p=2"><span class="widget-icon icon-briefcase"></span><span class="widget-label">ADMINISTRATIVO</span></a>
						</div>
					</div>

					<div class="span3">
						<div class="board-widgets small-widget" style="background-color: #4086f4;">
							<a href="panel-menu.php?p=3"><span class="widget-icon icon-desktop"></span><span class="widget-label">Soporte operativo</span></a>
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
					<?php if ($_SESSION["id"] == 7 or $_SESSION["id"] == 15 or $_SESSION["id"] == 17) { ?>
						<div class="span3">
							<div class="board-widgets small-widget" style="background-color: #eb4132;">
								<a href="panel-menu.php?p=4"><span class="widget-icon icon-file-alt"></span><span class="widget-label">Configuración</span></a>
							</div>
						</div>

						<!--
				<div class="span3">
					<div class="board-widgets small-widget" style="background-color: #fbbd01;">
						<a href="store-pedidos.php"><span class="widget-icon icon-shopping-cart"></span><span class="widget-label">TIENDA VIRTUAL</span></a>
					</div>
				</div>-->

						<div class="span3">
							<div class="board-widgets small-widget" style="background-color: #31a952;">
								<a href="#"><span class="widget-icon icon-folder-open"></span><span class="widget-label">Contabilidad</span></a>
							</div>
						</div>

						<div class="span3">
							<div class="board-widgets small-widget" style="background-color: dimgray;">
								<a href="ventas.php"><span class="widget-icon icon-folder-close"></span><span class="widget-label">Ventas</span></a>
							</div>
						</div>
					<?php } ?>


				</div>

				<?php if ($datosUsuarioActual[3] == 1 or $datosUsuarioActual[3] == 15) { ?>
					<div class="row-fluid ">
						<div class="span12">
							<div class="primary-head">
								<h3 class="page-header">Informes</h3>
							</div>
						</div>
					</div>


					<p>&nbsp;</p>

					<div class="row-fluid">

						<div class="span4">

							<div class="content-widgets gray">
								<div class="widget-head orange">
									<h3><i class="icon-file-alt"></i> Generales</h3>
								</div>
								<div class="widget-container">
									<ul class="sample-noty">
										<li><a href="clientes-filtro.php">1. Listado de clientes</a></li>
										<li><a href="tikets-filtros.php">2. Tickets</a></li>
										<li><a href="clientes-seguimiento-filtro.php">3. Seguimientos</a></li>
										<li><a href="usuarios-filtro.php">4. Gestión de usuarios</a></li>
										<li><a href="facturacion-filtros.php">5. Facturas</a></li>
										<li><a href="remisiones-filtros.php">6. Informe de servicios (Remisiones)</a></li>
										<li><a href="historial-acciones-filtro.php">7. Historial de acciones</a></li>
										<li><a href="productos-filtros.php">8. Productos</a></li>
										<li><a href="cotizacion-filtros.php">9. Cotizaciones</a></li>
										<li><a href="reportes/precios-dealer.php" target="_blank">10. Precios Dealer</a></li>
										<li><a href="comisiones-filtros.php">11. Comisiones ventas</a></li>
										<li><a href="hprecios-filtros.php">12. Historial de precios</a></li>
										<li><a href="reportes/combos.php" target="_blank">13. Combos</a></li>
										<li><a href="reportes/historial-dctos-especiales.php" target="_blank">14. Descuentos especiales en cotizaciones</a></li>
										<li><a href="reportes/historial-costos-cotizaciones.php" target="_blank">15. Historial costos y utilidad en cotizaciones</a></li>
										<li><a href="reportes/combos-dealer.php" target="_blank">16. Combos Dealer</a></li>
									</ul>
								</div>
							</div>

						</div>


						<div class="span4">

							<div class="content-widgets gray">
								<div class="widget-head green">
									<h3><i class="icon-download"></i> Exportar Excel</h3>
								</div>
								<div class="widget-container">
									<ul class="sample-noty">
										<?php if ($_SESSION["id"] == 7 or $_SESSION["id"] == 15 or $_SESSION["id"] == 17) { ?>
											<li> <a href="excel-exportar.php?exp=1">1. Clientes</a></li>
										<?php } ?>
										<li> <a href="excel-exportar.php?exp=2">2. Informe de servicios (Remisiones)</a></li>
										<li> <a href="excel-exportar.php?exp=3">3. Tickets</a></li>
										<?php if ($_SESSION["id"] == 7 or $_SESSION["id"] == 15 or $_SESSION["id"] == 17) { ?>
											<li> <a href="excel-exportar.php?exp=6">4. Comisiones</a></li>
										<?php } ?>
									</ul>
								</div>
							</div>

						</div>

						<?php include("cuadros-dialogos.php"); ?>

						<script>
							function modalFiltro(datos) {
								document.getElementById(datos.name).setAttribute("open", "true");
							}

							function modalFiltroClose(datos) {
								document.getElementById(datos.name).removeAttribute("open");
							}
						</script>


						<div class="span4">

							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3><i class="icon-bar-chart"></i> Gráficos estadísticos</h3>
								</div>
								<div class="widget-container">
									<ul class="sample-noty">
										<li> <a href="#graficos/1.php" name="filtro" onClick="modalFiltro(this)">1. Cotizaciones y ventas por usuarios</a></li>
										<li> <a href="#graficos/2.php" name="masCotizaciones" onClick="modalFiltro(this)">2. Los clientes con más cotizaciones</a></li>
										<li> <a href="#graficos/3.php" name="cotizacionesValor" onClick="modalFiltro(this)">3. Las cotizaciones de mayor valor</a></li>
										<li> <a href="#graficos/4.php" name="gestionComercial" onClick="modalFiltro(this)">4. Gestión comercial</a></li>
										<li> <a href="#graficos/5.php" name="embudoNegocios" onClick="modalFiltro(this)">5. Embudo de negocios</a></li>
										<li> <a href="#graficos/6.php" name="tickets" onClick="modalFiltro(this)">6. Tickets</a></li>
										<li> <a href="#graficos/7.php" name="seguimientos" onClick="modalFiltro(this)">7. Seguimientos</a></li>
										<li> <a href="#graficos/8.php" name="gestionSeguimientos" onClick="modalFiltro(this)">8. Gestión en seguimientos</a></li>
										<li> <a href="#graficos/9.php" name="productosVendidos" onClick="modalFiltro(this)">9. Los producos más vendidos</a></li>
										<li> <a href="#graficos/10.php" name="usuariosVisitas" onClick="modalFiltro(this)">10. Los usurios con más visitas</a></li>
										<li> <a href="#graficos/11.php" name="sTecnico" onClick="modalFiltro(this)">11. Los clientes con más servicio técnico</a></li>
										<li> <a href="#graficos/12.php" name="proyectos" onClick="modalFiltro(this)">12. Progreso de proyectos</a></li>
										<li> <a href="#graficos/13.php" name="canales" onClick="modalFiltro(this)">13. Canales en los seguimientos</a></li>
										<li> <a href="#graficos/14.php" name="gestionMercadeo" onClick="modalFiltro(this)">14. Gestión de mercadeo</a></li>
										<li> <a href="#graficos/15.php" name="ventas" onClick="modalFiltro(this)">15. Ventas</a></li>
									</ul>
								</div>
							</div>

						</div>




					</div>

				<?php } ?>



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



		<?php include("pie.php"); ?>
	</div>
</body>

</html>