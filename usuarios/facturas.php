<?php include("sesion.php"); ?>
<?php
$idPagina = 126;
$paginaActual['pag_nombre'] = "Facturas";
?>
<?php include("includes/verificar-paginas.php"); ?>
<?php include("includes/head.php"); ?>
<!-- styles -->


<link href="css/tablecloth.css" rel="stylesheet">

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
	$(function() {
		$('#data-table').dataTable({
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
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
				window.location.href = "enviar-cotizaciones-correo.php?get=44&id=" + id + "&cont=" + cont;
			}
		})
	};
</script>
</head>

<body>
	<div class="layout">
		<?php include("includes/encabezado.php"); ?>

		
		<div class="main-wrapper">
			<div class="container-fluid">
				<?php include("includes/notificaciones.php"); ?>
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
								<h3><?= $paginaActual['pag_nombre']; ?></h3>
							</div>
							<div class="widget-container">
								<?php
									$filtro = '';
									if ($_GET["busqueda"] != "") {
										$filtro .= " AND factura_id='" . $_GET["busqueda"] . "'";
									}
									if ($_GET["usuario"] != "") {
										$filtro .= " AND factura_vendedor='" . $_GET["usuario"] . "'";
									}

									if($_GET["desde"]!="" or $_GET["hasta"]!=""){
												$filtro .= " AND factura_fecha_propuesta BETWEEN '".$_GET["desde"]."' AND '".$_GET["hasta"]."'";
											}
									

									if (isset($_GET["cte"]) and $_GET["cte"] != "") {
										$SQL = "SELECT * FROM facturas
										LEFT JOIN clientes ON cli_id=factura_cliente AND cli_id='" . $_GET["cte"] . "'
										ORDER BY factura_id DESC";
									} else {
										$SQL = "SELECT * FROM facturas
										LEFT JOIN clientes ON cli_id=factura_cliente
										LEFT JOIN proveedores ON prov_id=factura_proveedor
										INNER JOIN usuarios ON usr_id=factura_creador
										WHERE factura_id=factura_id $filtro
										ORDER BY factura_id DESC";
									}
								?>
								<div style="border:thin; border-style:solid; height:150px; margin:10px; padding:10px;">
									<h4 align="center">-Busqueda general y paginación-</h4>
									<p> 
										<form class="form-horizontal" style="text-align: right;" action="<?=$_SERVER['PHP_SELF'];?>" method="get">
											<div class="search-box">
												<div class="input-append input-icon">
													<input placeholder="Buscar..." type="text" name="busqueda" value="<?php if(isset($_GET["busqueda"])) echo $_GET["busqueda"]; ?>">
													<i class=" icon-search"></i>
													<input class="btn" type="submit" value="Buscar">
												</div>
												<?php if(isset($_GET["busqueda"]) and $_GET["busqueda"]!=""){?> <a href="<?=$_SERVER['PHP_SELF'];?>" class="btn btn-warning"><i class="icon-minus"></i> Quitar Filtro</a> <?php } ?>
											</div>
										</form>
										<p style="margin: 10px;"><?php include("includes/paginacion.php");?></p> 
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
											<th>Comisión<br>Vendedor<br><?=$configuracion['conf_comision_vendedores'];?>% </th>
											<th>Acumulado<br>Cliente<br><?=$configuracion['conf_porcentaje_clientes'];?>%</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if (isset($_GET["cte"]) and $_GET["cte"] != "") {
											$consulta = mysqli_query($conexionBdPrincipal, "SELECT * FROM facturas
											LEFT JOIN clientes ON cli_id=factura_cliente AND cli_id='" . $_GET["cte"] . "'
											WHERE factura_id_empresa='".$idEmpresa."'
											ORDER BY factura_id DESC
											LIMIT $inicio, $limite
											");
										} else {
											$consulta = mysqli_query($conexionBdPrincipal, "SELECT * FROM facturas
											LEFT JOIN clientes ON cli_id=factura_cliente
											LEFT JOIN proveedores ON prov_id=factura_proveedor
											INNER JOIN usuarios ON usr_id=factura_creador
											WHERE factura_id=factura_id AND factura_id_empresa='".$idEmpresa."' $filtro
											ORDER BY factura_id DESC
											LIMIT $inicio, $limite
											");
										}
										$no = 1;
										$sumaFacturasSinIva = 0;
										$sumaFacturasConIva = 0;
										while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
											
											$czppFactura=$res['factura_id'];
											if($res['factura_concepto']=="Traída de remisión"){
												$czppFactura=$res['factura_remision'];
											}

											if ($datosUsuarioActual[3] != 1) {
												$consultaZona=mysqli_query($conexionBdPrincipal, "SELECT * FROM zonas_usuarios WHERE zpu_usuario='" . $_SESSION["id"] . "' AND zpu_zona='" . $res['cli_zona'] . "'");
												$numZ = mysqli_num_rows($consultaZona);
												if ($numZ == 0) continue;
											}

											if ($datosUsuarioActual[3] == 14 and $res['factura_creador'] != $_SESSION["id"] and $res['factura_vendedor'] != $_SESSION["id"]) {
												continue;
											}

											$consultaVendedor=mysqli_query($conexionBdPrincipal, "SELECT * FROM usuarios WHERE usr_id='" . $res['factura_vendedor'] . "' AND usr_id_empresa='".$idEmpresa."'");
											$vendedor = mysqli_fetch_array($consultaVendedor, MYSQLI_BOTH);


											
											$consultaTotal = mysqli_query($conexionBdPrincipal, "SELECT * FROM cotizacion_productos
													WHERE czpp_cotizacion='".$czppFactura."' AND czpp_valor>0 AND czpp_cantidad>0
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
													$descuento = is_numeric($datos['czpp_descuento']) ? $datos['czpp_descuento'] : 0;
													$VlrDcto = ($total * ($descuento/100));
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
												


											$pCom = $configuracion['conf_comision_vendedores'] / 100;

											$comision = ($sumaTotalConDcto * $pCom);

											$pCliente = $configuracion['conf_porcentaje_clientes'] / 100;

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

											$nombreCliente="";
											if(!empty($res['cli_nombre'])){
												$nombreCliente=strtoupper($res['cli_nombre']);
											}
											$nombreProveedor="";
											if(!empty($res['prov_nombre'])){
												$nombreProveedor=strtoupper($res['prov_nombre']);
											}
											$nombreResponsable="";
											if(!empty($res['usr_nombre'])){
												$nombreResponsable=strtoupper($res['usr_nombre']);
											}
											$nombreVendedor="";
											if(!empty($vendedor['usr_nombre'])){
												$nombreVendedor=strtoupper($vendedor['usr_nombre']);
											}

										?>
											<tr>
											<td><?= $no; ?></td>	
											<td><?= $res['factura_id']; ?></td>
												<td><span class="badge badge-<?= $nacionEtiqueta[$res['factura_extranjera']]; ?>"><?= $nacionFactura[$res['factura_extranjera']]; ?></span></td>
												<td><?= $res['factura_fecha_propuesta']; ?></td>
												<td><?= $res['factura_concepto']; ?></td>
												<td><?= $nombreCliente; ?></td>
												<td><?= $nombreProveedor; ?></td>
												<td>
													<?php
													$productos = mysqli_query($conexionBdPrincipal, "SELECT * FROM cotizacion_productos
													INNER JOIN productos ON prod_id=czpp_producto
													WHERE czpp_cotizacion='" . $czppFactura . "'
													");
													$i = 1;
													while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
														echo "<b>" . $i . ".</b> " . $prod['prod_nombre'] . "</br>";
														$i++;
													}
													?>
												</td>
												<td><?= $nombreResponsable; ?></td>
												<td><?= $nombreVendedor; ?></td>
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

															<li><a href="redimir-factura-clientes-actualizar.php?get=66&id=<?= $res['factura_id']; ?>" onClick="if(!confirm('Desea redimir este saldo para el cliente?')){return false;}">Redimir saldo</a></li>

															<li><a href="redimir-factura-vendedor-actualizar.php?get=67&id=<?= $res['factura_id']; ?>" onClick="if(!confirm('Desea saldar esta comisión para el vendedor?')){return false;}">Saldar comisión</a></li>

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
	<?php include("includes/pie.php"); ?>
	</div>
</body>

</html>