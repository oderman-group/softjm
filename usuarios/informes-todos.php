<?php 
include("sesion.php");

$idPagina = 120;

include("includes/verificar-paginas.php");
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


<?php include("includes/funciones-js.php");?>

</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
	
	<div class="main-wrapper">
		<div class="container-fluid">
			

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
										<li><a href="facturas-filtros.php">5. Facturas</a></li>
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
										<li><a href="cotizacion-servicios-filtros.php">17. Informe cotizaciones con servicios</a></li>
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
										<li> <a href="excel-exportar.php?exp=2">1. Informe de servicios (Remisiones)</a></li>
										<li> <a href="excel-exportar.php?exp=3">2. Tickets</a></li>
										<?php if ($_SESSION["id"] == 7 or $_SESSION["id"] == 15 or $_SESSION["id"] == 17) { ?>
											<li> <a href="excel-exportar.php?exp=6">3. Comisiones</a></li>
										<?php } ?>
									</ul>
								</div>
							</div>

						</div>

						<?php include("includes/cuadros-dialogos.php"); ?>

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

		</div>
	</div>
	<?php include("includes/pie.php");?>
</div>
</body>
</html>