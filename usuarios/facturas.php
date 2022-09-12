<?php include("sesion.php"); ?>
<?php
$idPagina = 126;
$tituloPagina = "Facturas";
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
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="css/font-awesome.css">
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
<script src="js/bootbox.js"></script>

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

	function confirmar(e) {
		bootbox.confirm("Desea enviar esta cotización?", function(result) {
			var id = e.id;
			var cont = e.name;
			if (result == true) {
				window.location.href = "sql.php?get=44&id=" + id + "&cont=" + cont;
			}
		})
	};
</script>
</head>

<body>
	<div class="layout">
		<?php include("encabezado.php"); ?>

		<?php include("barra-izq.php"); ?>
		<div class="main-wrapper">
			<div class="container-fluid">
				<?php include("notificaciones.php"); ?>
				<p>
					<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
					<a href="facturas-agregar.php?cte=<?= $_GET["cte"]; ?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar factura de venta</a>
					<a href="facturas-compra-agregar.php" class="btn btn-warning"><i class="icon-plus"></i> Agregar factura de compra nacional</a>
					<a href="fce-agregar.php" class="btn btn-success"><i class="icon-plus"></i> Agregar factura de compra extrajera</a>
				</p>
				<div class="row-fluid">
					<div class="span12">
						<div class="content-widgets light-gray">
							<div class="widget-head green">
								<h3><?= $tituloPagina; ?></h3>
							</div>
							<div class="widget-container">
								<p></p>

								<div style="border:thin; border-style:solid; height:150px; margin:10px;">
									<h4 align="center">-Búsqueda por ID-</h4>
									<p>
									<form class="form-horizontal" action="<?= $_SERVER['PHP_SELF']; ?>" method="get">
										<div class="search-box">
											<div class="input-append input-icon">
												<input class="search-input" placeholder="ID..." type="text" name="q" value="<?= $_GET["q"]; ?>">
												<i class=" icon-search"></i>
												<input class="btn" type="submit" name="buscar" value="Buscar">
											</div>
											<?php if ($_GET["q"] != "") { ?> <a href="<?= $_SERVER['PHP_SELF']; ?>" class="btn btn-warning"><i class="icon-minus"></i> Quitar Filtro</a> <?php } ?>
										</div>
									</form>
									</p>
								</div>

								<table class="table table-striped table-bordered" id="data-table">
									<thead>
										<tr>
										<th>No.</th>	
										<th>ID</th>
											<th>&nbsp;</th>
											<th>Fecha</th>
											<th>Concepto</th>
											<th>Cliente</th>
											<th>Proveedor</th>
											<th>Productos</th>
											<th>Responsable</th>
											<th>Vendedor</th>
											<th>Tipo</th>
											<th>Rem.</th>
											<th>Valor con Dcto sin IVA </th>
											<th>Valor con IVA</th>
											<th>Comisión<br>Vendedor<br><?=$configu['conf_comision_vendedores'];?>% </th>
											<th>Acumulado<br>Cliente<br><?=$configu['conf_porcentaje_clientes'];?>%</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$filtro = '';
										if ($_GET["q"] != "") {
											$filtro .= " AND factura_id='" . $_GET["q"] . "'";
										}
										if ($_GET["usuario"] != "") {
											$filtro .= " AND factura_vendedor='" . $_GET["usuario"] . "'";
										}

										if($_GET["desde"]!="" or $_GET["hasta"]!=""){
													$filtro .= " AND factura_fecha_propuesta BETWEEN '".$_GET["desde"]."' AND '".$_GET["hasta"]."'";
												}
										

										if (isset($_GET["cte"]) and $_GET["cte"] != "") {
											$consulta = mysql_query("SELECT * FROM facturas
								LEFT JOIN clientes ON cli_id=factura_cliente AND cli_id='" . $_GET["cte"] . "'
								ORDER BY factura_id DESC
								", $conexion);
										} else {
											$consulta = mysql_query("SELECT * FROM facturas
								LEFT JOIN clientes ON cli_id=factura_cliente
								LEFT JOIN proveedores ON prov_id=factura_proveedor
								INNER JOIN usuarios ON usr_id=factura_creador
								WHERE factura_id=factura_id $filtro
								ORDER BY factura_id DESC
								", $conexion);
										}
										$no = 1;
										$sumaFacturasSinIva = 0;
										$sumaFacturasConIva = 0;
										while ($res = mysql_fetch_array($consulta)) {
											if ($datosUsuarioActual[3] != 1) {
												$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='" . $_SESSION["id"] . "' AND zpu_zona='" . $res['cli_zona'] . "'", $conexion));
												if ($numZ == 0) continue;
											}

											if ($datosUsuarioActual[3] == 14 and $res['factura_creador'] != $_SESSION["id"] and $res['factura_vendedor'] != $_SESSION["id"]) {
												continue;
											}

											$vendedor = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='" . $res['factura_vendedor'] . "'", $conexion));


											
											$consultaTotal = mysql_query("SELECT * FROM cotizacion_productos
													WHERE czpp_cotizacion='".$res['factura_id']."' AND czpp_tipo=4 AND czpp_valor>0 AND czpp_cantidad>0
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


												}

												//Para el total al pie de pagina
												if($res['factura_tipo'] == 1){

													$sumaFacturasSinIva += $sumaTotalConDcto;
													$sumaFacturasConIva += $sumaTotalFinal;

												}
												


											$pCom = $configu['conf_comision_vendedores'] / 100;

											$comision = ($sumaTotalConDcto * $pCom);

											$pCliente = $configu['conf_porcentaje_clientes'] / 100;

											$aCliente = ($sumaTotalConDcto * $pCliente);

											//Color redimido clientes
											$colorRedimido = 'tomato';
											if($res['factura_redimido_cliente']==1){
													$colorRedimido = 'aquamarine';
											}

											//Color redimido vendedores
											$colorRedimidoV = 'tomato';
											if($res['factura_redimido_vendedor']==1){
													$colorRedimidoV = 'aquamarine';
											}

										?>
											<tr>
											<td><?= $no; ?></td>	
											<td><?= $res['factura_id']; ?></td>
												<td><span class="badge badge-<?= $nacionEtiqueta[$res['factura_extranjera']]; ?>"><?= $nacionFactura[$res['factura_extranjera']]; ?></span></td>
												<td><?= $res['factura_fecha_propuesta']; ?></td>
												<td><?= $res['factura_concepto']; ?></td>
												<td><?= strtoupper($res['cli_nombre']); ?></td>
												<td><?= strtoupper($res['prov_nombre']); ?></td>
												<td>
													<?php
													$productos = mysql_query("SELECT * FROM cotizacion_productos
													INNER JOIN productos ON prod_id=czpp_producto
													WHERE czpp_cotizacion='" . $res['factura_id'] . "' AND czpp_tipo=4
													", $conexion);
													$i = 1;
													while ($prod = mysql_fetch_array($productos)) {
														echo "<b>" . $i . ".</b> " . $prod['prod_nombre'] . ", ";
														$i++;
													}
													?>
												</td>
												<td><?= strtoupper($res['usr_nombre']); ?></td>
												<td><?= strtoupper($vendedor['usr_nombre']); ?></td>
												<td><?= $tipoFactura[$res['factura_tipo']]; ?></td>
												<td><?= $res['factura_remision']; ?></td>
												<td align="center">$<?= number_format($sumaTotalConDcto, 0, ".", "."); ?></td>
												<td align="center">$<?= number_format($sumaTotalFinal, 0, ".", "."); ?></td>
												<td align="center" style="background-color: <?=$colorRedimidoV;?>;">$<?= number_format($comision, 0, ".", "."); ?></td>
												<td align="center" style="background-color: <?=$colorRedimido;?>;">$<?= number_format($aCliente, 0, ".", "."); ?></td>

												<td>
													<div class="btn-group">
														<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span>
														</button>
														<ul class="dropdown-menu">
															<?php if ($_SESSION["id"] == $res['factura_creador'] or $_SESSION["id"] == $res['factura_vendedor'] or $datosUsuarioActual[3] == 1) {
																if ($res['factura_tipo'] == 2 and $res['factura_extranjera'] == '0') {
															?>
																	<li><a href="facturas-compra-editar.php?id=<?= $res['factura_id']; ?>#productos"> Editar</a></li>
																<?php
																}
																if ($res['factura_tipo'] == 2 and $res['factura_extranjera'] == 1) {
																?>
																	<li><a href="fce-editar.php?id=<?= $res['factura_id']; ?>#productos"> Editar</a></li>
															<?php
																}
															}
															?>

															<!--<li><a href="#reportes/formato-cotizacion-1.php?id=<?= $res['cotiz_id']; ?>" target="_blank">Imprimir</a></li>

															<li><a href="#sql.php?get=46&id=<?= $res['cotiz_id']; ?>" onClick="if(!confirm('Desea replicar este registro?')){return false;}">Replicar</a></li>-->

															<li><a href="sql.php?get=66&id=<?= $res['factura_id']; ?>" onClick="if(!confirm('Desea redimir este saldo para el cliente?')){return false;}">Redimir saldo</a></li>

															<li><a href="sql.php?get=67&id=<?= $res['factura_id']; ?>" onClick="if(!confirm('Desea saldar esta comisión para el vendedor?')){return false;}">Saldar comisión</a></li>

															<!--
											<li><a href="sql.php?get=48&id=<?= $res['cotiz_id']; ?>" onClick="if(!confirm('Desea generar pedido de esta cotización?')){return false;}">Generar pedido</a></li>
											
											<li><a href="sql.php?get=56&id=<?= $res['cotiz_id']; ?>" onClick="if(!confirm('Desea generar factura de esta cotización?')){return false;}">Generar factura</a></li>
											-->

														</ul>
													</div>
												</td>
											</tr>
										<?php $no++;
										} ?>
									</tbody>

									<tfoot>
										<tr style="height: 30px; font-weight: bold; font-size: 16px;">
											<td colspan="12" style="text-align: right;">Total</td>
											<td>$<?= number_format($sumaFacturasSinIva, 0, ".", ".");?></td>
											<td>$<?= number_format($sumaFacturasConIva, 0, ".", ".");?></td>
											<td colspan="3">&nbsp;</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>


			</div>
		</div>
	</div>
	<?php include("pie.php"); ?>
	</div>
</body>

</html>