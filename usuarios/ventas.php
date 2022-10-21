	<?php include("sesion.php"); ?>
	<?php
	$idPagina = 170;
	$paginaActual['pag_nombre'] = "Ventas";
	?>
	<?php include("verificar-paginas.php"); ?>
	<?php include("head.php"); ?>
	<?php
	mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('" . $_SESSION["id"] . "', '" . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "', '" . $idPagina . "', now(),'" . $_SERVER['HTTP_REFERER'] . "')", $conexion);
	if (mysql_errno() != 0) {
		echo mysql_error();
		exit();
	}
	$suc = 1;
	if(isset($_GET['suc'])){
		$suc = $_GET['suc'];
	}
	$metricas = mysql_fetch_array(mysql_query("SELECT * FROM metricas WHERE met_id='".$suc."'", $conexion));
	?>
	<!-- styles -->
	<link href="css/jquery.gritter.css" rel="stylesheet">
	<!--[if IE 7]>
	            <link rel="stylesheet" href="css/font-awesome-ie7.min.css">
	        <![endif]-->
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
				<?php include("encabezado.php"); ?>

				
				<div class="main-wrapper">
					<div class="container-fluid">
						<?php include("notificaciones.php"); ?>



<div class="row-fluid">
	<div class="span12">
		<div class="switch-board gray">
			<ul class="clearfix switch-item">
				<li><a href="#" class="brown" data-toggle="modal" data-target="#exampleModal"><i class="icon-user"></i><span>Vendedor #1</span></a></li>
				<li><a href="facturas.php" class="green"><i class="icon-shopping-cart"></i><span>Facturación</span></a></li>
				<!--
				<li><a href="#" class=" bondi-blue"><i class="icon-time"></i><span>Events</span></a></li>
				<li><a href="#" class=" dark-yellow"><i class="icon-file-alt"></i><span>Post</span></a></li>
				<li><a href="#" class=" blue"><i class="icon-copy"></i><span>Documents</span></a></li>
				<li><a href="#" class="orange"><i class="icon-cogs"></i><span>Facturación</span></a></li>
				<li><a href="#" class=" blue-violate"><i class="icon-lightbulb"></i><span>Support</span></a></li>
				<li><a href="#" class=" magenta"><i class="icon-bar-chart"></i><span>Statistics</span></a></li>-->
			</ul>
		</div>
	</div>
</div>


<div class="navbar">
	<div class="navbar-inner">
		<div class="container">

			<div class="nav-collapse collapse navbar-responsive-collapse">
				<ul class="nav">
					<li class="active"><a href="ventas.php">Inicio</a></li>
					<li><a href="metricas.php?id=1">Métricas</a></li>
				</ul>
				<form action="#" class="navbar-search pull-left">
					<input type="text" placeholder="Search" class="search-query span4">
				</form>
				
				<ul class="nav pull-right">
					<li><a href="#">Link</a></li>
					<li class="divider-vertical"></li>
					<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#">Sucursales <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<?php
							$sucursales = mysql_query("SELECT * FROM sucursales_propias",$conexion);
							while($sucu = mysql_fetch_array($sucursales)){
							?>
								<li><a href="ventas.php?suc=<?=$sucu['sucp_id'];?>"><?=$sucu['sucp_nombre'];?></a></li>
							<?php }?>

							<li class="divider"></li>
							<li><a href="#">Separated link</a></li>
						</ul>
					</li>
				</ul>
			</div>
			<!-- /.nav-collapse -->
		</div>
	</div>
	<!-- /navbar-inner -->
</div>


<p style="color: black; background-color: mediumaquamarine; padding: 10px; font-weight: bold;">Estos valores están automáticamente filtrados desde el primero de este mes hasta la fecha de hoy. Puede cambiarlos utilizando los campos del filtro.</p>




<div class="row-fluid">
	<div class="span4">
		<div class="content-widgets gray">
			<div class="widget-head bondi-blue">
				<h3> Filtros</h3>
			</div>
			<div class="widget-container">
				<form class="form-horizontal" method="get" action="ventas.php"> 

					<div class="control-group">
						<label class="control-label">Vendedor</label>
						<div class="controls">
							<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="vendedor">
								<option value="">Todos</option>
								<?php
								$conOp = mysql_query("SELECT * FROM usuarios",$conexion);
								while($resOp = mysql_fetch_array($conOp)){
									?>
									<option value="<?=$resOp[0];?>" <?php if($resOp[0] == $_GET["vendedor"]) echo "selected";?> ><?=$resOp['usr_nombre'];?></option>
									<?php
								}
								?>
							</select>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Asesor</label>
						<div class="controls">
							<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="asesor">
								<option value="">Todos</option>
								<?php
								$conOp = mysql_query("SELECT * FROM usuarios",$conexion);
								while($resOp = mysql_fetch_array($conOp)){
									?>
									<option value="<?=$resOp[0];?>" <?php if($resOp[0] == $_GET["asesor"]) echo "selected";?> ><?=$resOp['usr_nombre'];?></option>
									<?php
								}
								?>
							</select>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Desde</label>
						<div class="controls">
							<input type="date" class="span12" name="desde" value="<?=$_GET['desde'];?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Hasta</label>
						<div class="controls">
							<input type="date" class="span12" name="hasta" value="<?=$_GET['hasta'];?>">
						</div>
					</div>

					<hr>
					<div class="control-group">
									<label class="control-label">Ciudad</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="ciudad">
											<option value="">Todos</option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM localidad_ciudades INNER JOIN localidad_departamentos ON dep_id=ciu_departamento ORDER BY ciu_nombre",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp['ciu_id'];?>" <?php if($resOp['ciu_id'] == $_GET['ciudad']){echo "selected";}?>><?=$resOp['ciu_nombre'].", ".$resOp['dep_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>

					<hr>
					<div class="control-group">
						<label class="control-label">ID factura</label>
						<div class="controls">
							<input type="text" class="span6" name="factura" value="<?=$_GET['factura'];?>">
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
								<th>Barra</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
											/*$consulta = mysql_query("SELECT czpp_cotizacion, czpp_producto, czpp_valor, czpp_cantidad,
											(czpp_valor*czpp_cantidad) as total,
											(czpp_descuento/100) as dcto,
											(czpp_valor*czpp_cantidad) * (czpp_descuento/100) as vlrDcto,
											(czpp_valor*czpp_cantidad) - ((czpp_valor*czpp_cantidad) * (czpp_descuento/100)) as totalConDcto,
											(czpp_impuesto/100) as iva,
											((czpp_valor*czpp_cantidad) - ((czpp_valor*czpp_cantidad) * (czpp_descuento/100))) * (czpp_impuesto/100) as vlrIva,
											(czpp_valor*czpp_cantidad) - ((czpp_valor*czpp_cantidad) * (czpp_descuento/100)) + ((czpp_valor*czpp_cantidad) - ((czpp_valor*czpp_cantidad) * (czpp_descuento/100))) * (czpp_impuesto/100) as totalNeto

											FROM cotizacion_productos

											where czpp_tipo IN(4);
												", $conexion);
												*/
												$no = 1;

												$filtroFactura = '';
												$filtroRemision = '';
												//$_GET["desde"] = date("Y-m")."-01";
												//$_GET["hasta"] = date("Y-m-d");
												if($_GET["desde"]!="" or $_GET["hasta"]!=""){
													$filtroFactura .= " AND factura_fecha_propuesta BETWEEN '".$_GET["desde"]."' AND '".$_GET["hasta"]."'";
													$filtroRemision .= " AND rem_fecha BETWEEN '".$_GET["desde"]."' AND '".$_GET["hasta"]."'";
												}

												if($_GET["vendedor"]!=""){
													$filtroFactura .= " AND factura_vendedor='".$_GET["vendedor"]."'";
													$filtroRemision .= " AND rem_asesor='".$_GET["vendedor"]."'";
												}

												if($_GET["asesor"]!=""){
													$filtroFactura .= " AND factura_creador='".$_GET["asesor"]."'";
													//$filtroRemision .= " AND rem_asesor='".$_GET["vendedor"]."'";
												}

												if($_GET["factura"]!=""){
													$filtroFactura .= " AND factura_id='".$_GET["factura"]."'";
												}

												if($_GET["ciudad"]!=""){
													$filtroCliente .= " AND cli_ciudad='".$_GET["ciudad"]."'";
												}


												$consultaTotal = mysql_query("SELECT * FROM cotizacion_productos
													INNER JOIN facturas ON factura_id=czpp_cotizacion AND factura_vendedor IS NOT NULL AND factura_tipo=1 $filtroFactura
													INNER JOIN clientes ON cli_id=factura_cliente $filtroCliente
													WHERE czpp_tipo IN(4) AND czpp_valor>0 AND czpp_cantidad>0
													GROUP BY czpp_id
													",$conexion);

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

												while($datos = mysql_fetch_array($consultaTotal)){

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

													$porcentajeGral = ($sumaTotal / $metricas['met_meta_venta_mes']) * 100;

												}
												?>
												
												<tr>
													<td>1</td>
													<td>Ventas Totales</td>
													<td>$<?= number_format($sumaTotal, 2, ",", "."); ?></td>
													<td>
															<?=number_format($porcentajeGral, 2, ",", ".");?>%<br>
															<div class="progress progress-info progress-striped">
																<div class="bar" style="width: <?=$porcentajeGral;?>%">
																</div>
															</div>
														</td>
													<!--
													<td>
														<div class="btn-group">
															<button class="btn btn-info" data-original-title="Edit"><i class="icon-edit"></i></button><div class="tooltip-arrow"></div></div>
															<button class="btn btn-danger" data-original-title="Delete"><i class="icon-remove"></i></button>
															<button class="btn btn-success" data-original-title="Publish"><i class=" icon-ok"></i></button>
														</div>
													</td>
												-->
												</tr>

												<tr>
													<td>2</td>
													<td>Descuentos Totales</td>
													<td>$<?= number_format($SumaDcto, 2, ",", "."); ?></td>
													<td>&nbsp;</td>
												</tr>


												<tr>
													<td>3</td>
													<td>Ventas totales con descuento</td>
													<td>$<?= number_format($sumaTotalConDcto, 2, ",", "."); ?></td>
													<td>&nbsp;</td>
												</tr>

												<tr>
													<td>4</td>
													<td>Impuestos totales</td>
													<td>$<?= number_format($sumaTotalIva, 2, ",", "."); ?></td>
													<td>&nbsp;</td>
												</tr>

												<tr>
													<td>5</td>
													<td>Ventas totales con impuesto</td>
													<td>$<?= number_format($sumaTotalFinal, 2, ",", "."); ?></td>
													<td>&nbsp;</td>
												</tr>


											</tbody>
										</table>
									</div>
								</div>
							</div>



						</div>


						<div class="row-fluid">
							<div class="span7">
								<div class="content-widgets light-gray">
									<div class="widget-head green">
										<h3>VENDEDORES</h3>
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
												$sumaTotalVendedores = 0;
												$sumaVentas = 0;

												$ventasMejores = 0;

												$consultaVendedores = mysql_query("SELECT factura_vendedor, UCASE(usr_nombre) AS vendedor, sum( (czpp_valor*czpp_cantidad) ) AS sumaTotal, AVG( (czpp_valor*czpp_cantidad) ) AS promVentas, SUM(czpp_descuento) AS Totaldctos, AVG(czpp_descuento) AS promDcto, COUNT(*) AS numVentas, sucp_nombre, usr_id, usr_meta_ventas
													FROM cotizacion_productos
													INNER JOIN facturas ON factura_id=czpp_cotizacion AND factura_vendedor IS NOT NULL AND factura_tipo=1 $filtroFactura
													INNER JOIN usuarios ON usr_id=factura_vendedor
													INNER JOIN sucursales_propias ON sucp_id=usr_sucursal
													INNER JOIN clientes ON cli_id=factura_cliente $filtroCliente
													WHERE czpp_tipo=4 AND czpp_cantidad>0
													GROUP BY factura_vendedor
													ORDER BY sumaTotal DESC
													",$conexion);

												while($datosVendedores = mysql_fetch_array($consultaVendedores)){
													$sumaTotalVendedores += $datosVendedores['sumaTotal'];
													$sumaVentas += $datosVendedores['numVentas'];

													if($no == 1){
														mysql_query("UPDATE usuarios SET usr_mejor_vendedor=0",$conexion);

														mysql_query("UPDATE usuarios SET usr_mejor_vendedor=1 WHERE usr_id='".$datosVendedores['usr_id']."'",$conexion);
														$ventasMejores = 	$datosVendedores['sumaTotal'];
													}

													$porcentaje = ($datosVendedores['sumaTotal'] / $datosVendedores['usr_meta_ventas']) * 100;
													
													?>

													<tr>
														<td><?=$no;?></td>
														<td><?=$datosVendedores['vendedor'];?></td>
														<td><?=$datosVendedores['sucp_nombre'];?></td>
														<td><a href="facturas.php?usuario=<?=$datosVendedores['usr_id'];?>&desde=<?=$_GET['desde'];?>&hasta=<?=$_GET['hasta'];?>" target="_blank">$<?= number_format($datosVendedores['sumaTotal'], 2, ",", "."); ?></a></td>
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

												<tfoot>
													<tr style="font-weight: bold;">
														<td colspan="3">TOTAL</td>
														<td>$<?= number_format($sumaTotalVendedores, 2, ",", "."); ?></td>
														<td><?= number_format($sumaVentas, 0, ",", "."); ?></td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
								</div>

								<div class="span5">
								<div class="content-widgets light-gray">
									<div class="widget-head green">
										<h3>SUCURSALES</h3>
									</div>
									<div class="widget-container">
										<p></p>
										<table class="data-grid table tbl-serach responsive dataTable" id="data-table">
											<thead>
												<tr>
													<th>No.</th>
													<th>Sucursal</th>
													<th>Total Ventas</th>
													<th>Num. Ventas</th>
													<th>Prom. Ventas</th>
													<th>Suma Dctos.</th>
													<th>Prom. Dctos.</th>
												</tr>
											</thead>
											<tbody>
												<?php

												$no = 1;
												$sumaTotalSucursales = 0;
												$consultaSucursales = mysql_query("SELECT factura_vendedor, sum( (czpp_valor*czpp_cantidad) ) AS sumaTotal,
												AVG( (czpp_valor*czpp_cantidad) ) AS promVentas, SUM(czpp_descuento) AS Totaldctos, AVG(czpp_descuento) AS promDcto, COUNT(*) AS numVentas,
												sucp_nombre
												FROM cotizacion_productos
													INNER JOIN facturas ON factura_id=czpp_cotizacion AND factura_vendedor IS NOT NULL AND factura_tipo=1 $filtroFactura
													INNER JOIN usuarios ON usr_id=factura_vendedor
                          							INNER JOIN sucursales_propias ON sucp_id=usr_sucursal
                          							INNER JOIN clientes ON cli_id=factura_cliente $filtroCliente
													WHERE czpp_tipo=4 AND czpp_cantidad>0
													GROUP BY  sucp_id
													ORDER BY sumaTotal DESC
													",$conexion);

												while($datosSucursales = mysql_fetch_array($consultaSucursales)){
													$sumaTotalSucursales += $datosSucursales['sumaTotal'];
													?>

													<tr>
														<td><?=$no;?></td>
														<td><?=$datosSucursales['sucp_nombre'];?></td>
														<td>$<?= number_format($datosSucursales['sumaTotal'], 2, ",", "."); ?></td>
														<td align="center"><?= $datosSucursales['numVentas']; ?></td>
														<td>$<?= number_format($datosSucursales['promVentas'], 2, ",", "."); ?></td>

														<td><?=$datosSucursales['Totaldctos'];?>%</td>
														<td><?=number_format($datosSucursales['promDcto'], 2, ",", ".");?>%</td>
													</tr>

													<?php $no++;}?>

												</tbody>

												<tfoot>
													<tr style="font-weight: bold;">
														<td colspan="2">TOTAL</td>
														<td>$<?= number_format($sumaTotalSucursales, 2, ",", "."); ?></td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>

								<div class="content-widgets light-gray">
									<div class="widget-head green">
										<h3>OTROS DATOS</h3>
									</div>
									<div class="widget-container">
										<p></p>
										<table class="data-grid table tbl-serach responsive dataTable" id="data-table">
											<thead>
												<tr>
													<th>No.</th>
													<th>Descripción</th>
													<th>Dato</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$no = 1;
												$consultaRemisiones = mysql_query("SELECT COUNT(*) as totalRem
												FROM remisiones
													WHERE rem_id=rem_id $filtroRemision
													",$conexion);

												$datosRemisiones = mysql_fetch_array($consultaRemisiones);
													?>

													<tr>
														<td>1</td>
														<td>Total servicio técnico</td>
														<td><?= number_format($datosRemisiones['totalRem'], 0, ",", "."); ?></td>
													</tr>

													<tr>
														<td>2</td>
														<td>Meta de este mes</td>
														<td>$<?= number_format($metricas['met_meta_venta_mes'], 0, ",", "."); ?></td>
													</tr>

													<tr>
														<td>3</td>
														<td>Punto de equilibrio</td>
														<td>$<?= number_format($metricas['met_punto_equilibrio'], 0, ",", "."); ?></td>
													</tr>

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
				$mejorVendedor = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_mejor_vendedor=1 LIMIT 0,1",$conexion));	
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
											<h3>$<?= number_format($ventasMejores, 2, ",", "."); ?></h3>
											<h4>GANA BONO DE: $<?= number_format($metricas['met_bonificacion_mes'], 0, ",", "."); ?></h4>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
											<button type="button" class="btn btn-primary">Enviar felicitación</button>
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


				<?php include("pie.php"); ?>
			</div>
		</body>

		</html>