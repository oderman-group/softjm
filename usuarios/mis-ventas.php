<?php 
include("sesion.php");
$idPagina = 158;
include("includes/verificar-paginas.php");
include("includes/head.php");
?>
	<!-- styles -->
	<link rel="stylesheet" href="css/font-awesome.css">
	<link href="css/jquery.gritter.css" rel="stylesheet">
	<!--[if IE 7]>
	            <link rel="stylesheet" href="css/font-awesome-ie7.min.css">
	        <![endif]-->


	<!--[if IE 7]>
	            <link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
	        <![endif]-->
	<!--[if IE 8]>
	            <link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
	        <![endif]-->
	<!--[if IE 9]>
	            <link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
	        <![endif]-->
	        	<link href="css/tablecloth.css" rel="stylesheet">
	        	<link href="css/responsive-tables.css" rel="stylesheet">
	        	<link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
	        	<!--fav and touch icons -->
	        	<link rel="shortcut icon" href="ico/favicon.ico">
	        	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
	        	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
	        	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
	        	<link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
	        	<!--============j avascript===========-->
	        	<script src="js/jquery.js"></script>
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
	        	<script type="text/javascript">
		/*$( function () {
			  // Set the classes that TableTools uses to something suitable for Bootstrap
			  $.extend( true, $.fn.DataTable.TableTools.classes, {
				  "container": "btn-group",
				  "buttons": {
					  "normal": "btn",
					  "disabled": "btn disabled"
				  },
				  "collection": {
					  "container": "DTTT_dropdown dropdown-menu",
					  "buttons": {
						  "normal": "",
						  "disabled": "disabled"
					  }
				  }
			  } );
			  // Have the collection use a bootstrap compatible dropdown
			  $.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
				  "collection": {
					  "container": "ul",
					  "button": "li",
					  "liner": "a"
				  }
			  } );
			  });
			  */
			  $(function() {
			  	$('#data-table').dataTable({
			  		"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
				/*"oTableTools": {
				"aButtons": [
					"copy",
					"print",
					{
						"sExtends":    "collection",
						"sButtonText": 'Save <span class="caret" />',
						"aButtons":    [ "csv", "xls", "pdf" ]
					}
				]
			}*/
		});
			  });
			  $(function() {
			  	$('.tbl-simple').dataTable({
			  		"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
			  	});
			  });

			  $(function() {
			  	$(".tbl-paper-theme").tablecloth({
			  		theme: "paper"
			  	});
			  });

			  $(function() {
			  	$(".tbl-dark-theme").tablecloth({
			  		theme: "dark"
			  	});
			  });
			  $(function() {
			  	$('.tbl-paper-theme,.tbl-dark-theme').dataTable({
			  		"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
			  	});


			  });
			</script>
		</head>

		<body>
			<div class="layout">
				<?php include("includes/encabezado.php"); ?>

				
				<div class="main-wrapper">
					<div class="container-fluid">
						<?php include("includes/notificaciones.php"); ?>



<div class="row-fluid">
	<div class="span12">
		<div class="switch-board gray">
			<ul class="clearfix switch-item">
				<li><a href="#" class="brown" data-toggle="modal" data-target="#exampleModal"><i class="icon-user"></i><span>Vendedor #1</span></a></li>
			</ul>
		</div>
	</div>
</div>



<div class="row-fluid">
	<div class="span4">
		<div class="content-widgets gray">
			<div class="widget-head bondi-blue">
				<h3> Filtros</h3>
			</div>
			<div class="widget-container">
				<form class="form-horizontal" method="get" action="mis-ventas.php"> 


					<div class="control-group">
						<label class="control-label">Desde</label>
						<div class="controls">
							<input type="date" class="span12" name="desde" value="<?php if(isset($_GET['desde'])) echo $_GET['desde'];?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Hasta</label>
						<div class="controls">
							<input type="date" class="span12" name="hasta" value="<?php if(isset($_GET['hasta'])) echo $_GET['hasta'];?>">
						</div>
					</div>


					<div class="form-actions">
						<button type="submit" class="btn btn-info"><i class="icon-save"></i> Filtrar</button>
					</div>



				</div>
			</div>



		</div>	

		<div class="span8">
			<div class="content-widgets light-gray">
				<div class="widget-head green">
					<h3><?= $paginaActual['pag_nombre']; ?></h3>
				</div>
				<div class="widget-container">
					<p></p>
					<table class="data-grid table tbl-serach responsive dataTable" id="data-table">
						<thead>
							<tr>
								<th>No.</th>
								<th>Descripción</th>
								<th>Valor</th>
							</tr>
						</thead>
						<tbody>
							<?php
												$no = 1;

												$filtroFactura = '';
												if(isset($_GET["desde"]) and $_GET["desde"]!="" or isset($_GET["hasta"]) and  $_GET["hasta"]!=""){$filtroFactura .= " AND factura_fecha_propuesta BETWEEN '".$_GET["desde"]."' AND '".$_GET["hasta"]."'";}


												$consultaTotal = $conexionBdPrincipal->query("SELECT * FROM cotizacion_productos
													INNER JOIN facturas ON factura_id=czpp_cotizacion AND factura_vendedor IS NOT NULL AND factura_vendedor='".$_SESSION['id']."' $filtroFactura
													WHERE czpp_tipo IN('".CZPP_TIPO_FACT."') AND czpp_valor>0 AND czpp_cantidad>0
													GROUP BY czpp_id
													");

												$total = 0;
												$sumaTotal = 0;
												$VlrDcto = 0;
												$totalConDcto = 0;
												$SumaDcto = 0;
												$sumaTotalConDcto = 0;
												$VlrIva = 0;
												$sumaTotalIva = 0;
												$totalFinal = 0;
												$sumaTotalFinal = 0;

												while($datos = mysqli_fetch_array($consultaTotal, MYSQLI_BOTH)){

													$total = ($datos['czpp_valor'] * $datos['czpp_cantidad']);
													$sumaTotal += $total;

													$VlrDcto = ($total * ($datos['czpp_descuento']/100));
													$SumaDcto += $VlrDcto;

													$totalConDcto = ($total - $VlrDcto);
													$sumaTotalConDcto += $totalConDcto;

													$VlrIva = ($totalConDcto * ($datos['czpp_impuesto']/100));
													$sumaTotalIva += $VlrIva;

													$totalFinal = $totalConDcto + $VlrIva;
													$sumaTotalFinal += $totalFinal;

												}
												?>
												
												<tr>
													<td>1</td>
													<td>Ventas Totales</td>
													<td>$<?= number_format($sumaTotal, 2, ",", "."); ?></td>
												</tr>

												<tr>
													<td>2</td>
													<td>Descuentos Totales</td>
													<td>$<?= number_format($SumaDcto, 2, ",", "."); ?></td>
												</tr>


												<tr>
													<td>3</td>
													<td>Ventas totales con descuento</td>
													<td>$<?= number_format($sumaTotalConDcto, 2, ",", "."); ?></td>
												</tr>

												<tr>
													<td>4</td>
													<td>Impuestos totales</td>
													<td>$<?= number_format($sumaTotalIva, 2, ",", "."); ?></td>
												</tr>

												<tr>
													<td>5</td>
													<td>Ventas totales con impuesto</td>
													<td>$<?= number_format($sumaTotalFinal, 2, ",", "."); ?></td>
												</tr>


											</tbody>
										</table>
									</div>
								</div>
							</div>



						</div>


						<div class="row-fluid">
							<div class="span12">
								<div class="content-widgets light-gray">
									<div class="widget-head green">
										<h3><?= $paginaActual['pag_nombre']; ?></h3>
									</div>
									<div class="widget-container">
										<p></p>
										<table class="data-grid table tbl-serach responsive dataTable" id="data-table">
											<thead>
												<tr>
													<th>No.</th>
													<th>Vendedor</th>
													<th>Sucursal</th>
													<th>Total Ventas</th>
													<th>Num. Ventas</th>
													<th>Prom. Ventas</th>
													<th>Suma Dctos.</th>
													<th>Prom. Dctos.</th>
													<th>Meta</th>
													<th>Barra</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$no = 1;

												$consultaVendedores = $conexionBdPrincipal->query("SELECT factura_vendedor, UCASE(usr_nombre) AS vendedor, sum( (czpp_valor*czpp_cantidad) ) AS sumaTotal, AVG( (czpp_valor*czpp_cantidad) ) AS promVentas, SUM(czpp_descuento) AS Totaldctos, AVG(czpp_descuento) AS promDcto, COUNT(*) AS numVentas, sucp_nombre, usr_id, usr_meta_ventas
													FROM cotizacion_productos
													INNER JOIN facturas ON factura_id=czpp_cotizacion AND factura_vendedor IS NOT NULL AND factura_vendedor='".$_SESSION['id']."' $filtroFactura
													INNER JOIN usuarios ON usr_id=factura_vendedor
													INNER JOIN sucursales_propias ON sucp_id=usr_sucursal
													WHERE czpp_tipo='".CZPP_TIPO_FACT."' AND czpp_cantidad>0
													GROUP BY factura_vendedor
													ORDER BY sumaTotal DESC
													");

												while($datosVendedores = mysqli_fetch_array($consultaVendedores, MYSQLI_BOTH)){

													$porcentaje = ($datosVendedores['sumaTotal'] / $datosVendedores['usr_meta_ventas']) * 100;
													
													?>

													<tr>
														<td><?=$no;?></td>
														<td><?=$datosVendedores['vendedor'];?></td>
														<td><?=$datosVendedores['sucp_nombre'];?></td>
														<td>$<?= number_format($datosVendedores['sumaTotal'], 2, ",", "."); ?></td>
														<td align="center"><?= $datosVendedores['numVentas']; ?></td>
														<td>$<?= number_format($datosVendedores['promVentas'], 2, ",", "."); ?></td>

														<td><?=$datosVendedores['Totaldctos'];?>%</td>
														<td><?=number_format($datosVendedores['promDcto'], 2, ",", ".");?>%</td>
														<td>$<?=number_format($datosVendedores['usr_meta_ventas'], 2, ",", ".");?></td>
														<td>
															<?=number_format($porcentaje, 2, ",", ".");?>%<br>
															<div class="progress progress-info progress-striped">
																<div class="bar" style="width: <?=$porcentaje;?>%">
																</div>
															</div>
														</td>
													</tr>

													<?php $no++;}?>

												</tbody>
											</table>
										</div>
									</div>
								</div>
							

						</div>



						</div>
					</div>
				</div>

				<?php
				$consultaMejorVendedor = $conexionBdPrincipal->query("SELECT * FROM usuarios 
				WHERE usr_mejor_vendedor=1 AND usr_id_empresa={$_SESSION['dataAdicional']['id_empresa']} LIMIT 0,1");
				$mejorVendedor = mysqli_fetch_array($consultaMejorVendedor, MYSQLI_BOTH);	
				?>

<!-- Modal -->
							<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel"><?=$mejorVendedor['usr_nombre'];?></h5>

											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body" style="text-align: center;">
											<img src="files/fotos/<?=$mejorVendedor['usr_foto'];?>" width="250">
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
										</div>
									</div>
								</div>
							</div>

							<script>
		/*
	$( document ).ready(function() {
	    $('#exampleModal').modal('toggle')
	});*/

</script>


				<?php include("includes/pie.php"); ?>
			</div>
		</body>

		</html>