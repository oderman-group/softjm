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
									<?php
											$listaPaginasInforme = [
												["url" => "clientes-filtro.php", "id" => 103, "nombre" => "1. Listado de clientes","target_blank" => false],
												["url" => "tikets-filtros.php", "id" => 107, "nombre" => "2. Tickets","target_blank" => false],
												["url" => "clientes-seguimiento-filtro.php", "id" => 101, "nombre" => "3. Seguimientos","target_blank" => false],
												["url" => "usuarios-filtro.php", "id" => 106, "nombre" => "4. Gestión de usuarios","target_blank" => false],
												["url" => "facturas-filtros.php", "id" => 102, "nombre" => "5. Facturas","target_blank" => false],
												["url" => "remisiones-filtros.php", "id" => 105, "nombre" => "6. Informe de servicios (Remisiones)","target_blank" => false],
												["url" => "historial-acciones-filtro.php", "id" => 66, "nombre" => "7. Historial de acciones","target_blank" => false],
												["url" => "productos-filtros.php", "id" => 122, "nombre" => "8. Productos","target_blank" => false],
												["url" => "cotizacion-filtros.php", "id" => 127, "nombre" => "9. Cotizaciones","target_blank" => false],
												["url" => "reportes/precios-dealer.php", "id" => 347, "nombre" => "10. Precios Dealer","target_blank" => true],
												["url" => "comisiones-filtros.php", "id" => 168, "nombre" => "11. Comisiones ventas","target_blank" => false],
												["url" => "hprecios-filtros.php", "id" => 169, "nombre" => "12. Historial de precios","target_blank" => false],
												["url" => "reportes/combos.php", "id" => 348, "nombre" => "13. Combo","target_blank" => true],
												["url" => "reportes/historial-dctos-especiales.php", "id" => 349, "nombre" => "14. Descuentos especiales en cotizaciones","target_blank" => true],
												["url" => "reportes/historial-costos-cotizaciones.php", "id" => 350, "nombre" => "15. Historial costos y utilidad en cotizaciones","target_blank" => true],
												["url" => "reportes/combos-dealer.php", "id" => 351, "nombre" => "16. Combos Dealer","target_blank" => true],
												["url" => "cotizacion-servicios-filtros.php", "id" => 274, "nombre" => "17. Informe cotizaciones con servicios","target_blank" => false],
											]; 

												foreach ($listaPaginasInforme as $pagina) {
													if (Modulos::validarRol([$pagina['id']], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
															$target = ($pagina['target_blank']) ? ' target="_blank"' : '';
															echo '<li><a href="'.$pagina['url'].'"'.$target.'>'.$pagina['nombre'].'</a></li>';
													}
											}
										?>
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
										<?php
											$listaPaginasGraficas = [
												["url" => "#graficos/1.php", "id" => 352, "nombre" => "filtro", "label" => "1. Cotizaciones y ventas por usuarios"],
												["url" => "#graficos/2.php", "id" => 353, "nombre" => "masCotizaciones", "label" => "2. Los clientes con más cotizaciones"],
												["url" => "#graficos/3.php", "id" => 354, "nombre" => "cotizacionesValor", "label" => "3. Las cotizaciones de mayor valor"],
												["url" => "#graficos/4.php", "id" => 345, "nombre" => "gestionComercial", "label" => "4. Gestión comercial"],
												["url" => "#graficos/5.php", "id" => 355, "nombre" => "embudoNegocios", "label" => "5. Embudo de negocios"],
												["url" => "#graficos/6.php", "id" => 356, "nombre" => "tickets", "label" => "6. Tickets"],
												["url" => "#graficos/7.php", "id" => 357, "nombre" => "seguimientos", "label" => "7. Seguimientos"],
												["url" => "#graficos/8.php", "id" => 358, "nombre" => "gestionSeguimientos", "label" => "8. Gestión en seguimientos"],
												["url" => "#graficos/9.php", "id" => 359, "nombre" => "productosVendidos", "label" => "9. Los producos más vendidos"],
												["url" => "#graficos/10.php", "id" => 360, "nombre" => "usuariosVisitas", "label" => "10. Los usurios con más visitas"],
												["url" => "#graficos/11.php", "id" => 361, "nombre" => "sTecnico", "label" => "11. Los clientes con más servicio técnico"],
												["url" => "#graficos/12.php", "id" => 362, "nombre" => "proyectos", "label" => "12. Progreso de proyectos"],
												["url" => "#graficos/13.php", "id" => 363, "nombre" => "canales", "label" => "13. Canales en los seguimientos"],
												["url" => "#graficos/14.php", "id" => 364, "nombre" => "gestionMercadeo", "label" => "14. Gestión de mercadeo"],
												["url" => "#graficos/15.php", "id" => 365, "nombre" => "ventas", "label" => "15. Ventas"],
											];

											foreach ($listaPaginasGraficas as $pagina) {
												if (Modulos::validarRol([$pagina['id']], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
														echo '<li> <a href="'.$pagina['url'].'" name="'.$pagina['nombre'].'" onClick="modalFiltro(this)">'.$pagina['label'].'</a></li>';
												}
										}
										?>
									</ul>
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