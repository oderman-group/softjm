<?php include("sesion.php");?>
<?php 
$idPagina = 120;
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<!-- styles -->
<link href="css/jquery.gritter.css" rel="stylesheet">
<!--[if IE 7]>
<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
<![endif]-->
<link href="css/tablecloth.css" rel="stylesheet">


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
								<li><a href="clientes-filtro.php">1. Listado de clientes</a></li>
								<li><a href="tikets-filtros.php">2. Tickets</a></li>
								<li><a href="clientes-seguimiento-filtro.php">3. Seguimientos</a></li>
								<li><a href="usuarios-filtro.php">4. Gestión de usuarios</a></li>
								<li><a href="facturacion-filtros.php">5. Facturas</a></li>
								<li><a href="remisiones-filtros.php">6. Informe de servicios (Remisiones)</a></li>
								<li><a href="historial-acciones-filtro.php">7. Historial de acciones</a></li>
								<li><a href="productos-filtros.php">8. Productos</a></li>
								<li><a href="cotizacion-filtros.php">9. Cotizaciones</a></li>
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
								<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15 or $_SESSION["id"]==17){?>
								<li> <a href="excel-exportar.php?exp=1">1. Clientes</a></li>
								<?php }?>
								<li> <a href="excel-exportar.php?exp=2">2. Informe de servicios (Remisiones)</a></li>
								<li> <a href="excel-exportar.php?exp=3">3. Tickets</a></li>
							</ul>
						</div>
					</div>
					
				</div>
				
				
				<div class="span4">
	
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3><i class="icon-bar-chart"></i> Gráficos estadísticos</h3>
						</div>
						<div class="widget-container">
							<ul class="sample-noty">
								<li> <a href="graficos/1.php" target="_blank">1. Cotizaciones por usuarios</a></li>
								<li> <a href="graficos/2.php" target="_blank">2. Los clientes con más cotizaciones</a></li>
								<li> <a href="graficos/3.php" target="_blank">3. Las cotizaciones de mayor valor</a></li>
								<li> <a href="graficos/4.php" target="_blank">4. Gestión comercial</a></li>
								<li> <a href="graficos/5.php" target="_blank">5. Embudo de negocios</a></li>
								<li> <a href="graficos/6.php" target="_blank">6. Tickets</a></li>
								<li> <a href="graficos/7.php" target="_blank">7. Seguimientos</a></li>
								<li> <a href="graficos/8.php" target="_blank">8. Gestión en seguimientos</a></li>
								<li> <a href="graficos/9.php" target="_blank">9. Los producos más vendidos</a></li>
								<li> <a href="graficos/10.php" target="_blank">10. Los usurios con más visitas</a></li>
								<li> <a href="graficos/11.php" target="_blank">11. Los clientes con más servicio técnico</a></li>
								<li> <a href="graficos/12.php" target="_blank">12. Progreso de proyectos</a></li>
								<li> <a href="graficos/13.php" target="_blank">13. Canales en los seguimientos</a></li>
							</ul>
						</div>
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