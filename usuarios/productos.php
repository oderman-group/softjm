<?php
include("sesion.php");

$idPagina = 36;

$tabla = 'productos';
$pk = 'prod_id';
$idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];
include("includes/verificar-paginas.php");
include("includes/head.php");
?>
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
</script>

<script type="text/javascript">
	function productos(enviada) {
		var campo = enviada.title;
		var producto = enviada.name;
		var proceso = 1;
		var valor = enviada.value;


		if (campo == 'prod_costo') {

			/* 
		document.getElementById("costo"+producto).value=valor;
		
		var utilidad = (parseFloat(enviada.alt)/100);
		var precioNuevo = (parseFloat(valor) + (parseFloat(valor)*parseFloat(utilidad)));
		var precioNuevoIva = (parseFloat(precioNuevo) + (parseFloat(precioNuevo)*0.19));
		  alert(utilidad);
		  
		document.getElementById("precioLista"+producto).innerHTML="$"+precioNuevo.toLocaleString();
		document.getElementById("precioListaIva"+producto).innerHTML="$"+precioNuevoIva.toLocaleString();
		*/
		}


		if (campo == 'prod_utilidad') {
			document.getElementById("utilidad" + producto).value = valor;

			var costo = enviada.alt;
			var utilidad = (valor / 100);
			var precioNuevo = (parseFloat(costo) + (parseFloat(costo) * parseFloat(utilidad)));
			var precioNuevoIva = (parseFloat(precioNuevo) + (parseFloat(precioNuevo) * 0.19));

			document.getElementById("precioLista" + producto).innerHTML = "$" + precioNuevo.toLocaleString();
			document.getElementById("precioListaIva" + producto).innerHTML = "$" + precioNuevoIva.toLocaleString();
		}

		$('#resp').empty().hide().html("Esperando...").show(1);
		datos = "producto=" + (producto) + "&proceso=" + (proceso) + "&valor=" + (valor) + "&campo=" + (campo) + "&tabla=" + $("#tabla").val() + "&pk=" + $("#pk").val();
		$.ajax({
			type: "POST",
			url: "ajax/ajax-productos.php",
			data: datos,
			success: function(data) {
				$('#resp').empty().hide().html(data).show(1);
			}
		});
	}

	function pred(enviada) {
		var valorActual = enviada.title;
		var producto = enviada.name;
		var proceso = 6;

		if (valorActual == 0) {
			document.getElementById("p" + producto).innerHTML = "SI";
			document.getElementById("p" + producto).title = 1;
		}

		if (valorActual == 1) {
			document.getElementById("p" + producto).innerHTML = "NO";
			document.getElementById("p" + producto).title = 0;
		}

		$('#resp').empty().hide().html("Esperando...").show(1);
		datos = "producto=" + (producto) + "&proceso=" + (proceso) + "&valorActual=" + (valorActual);
		$.ajax({
			type: "POST",
			url: "ajax/ajax-productos.php",
			data: datos,
			success: function(data) {
				$('#resp').empty().hide().html(data).show(1);
			}
		});
	}

	function visweb(enviada) {
		var valorActual = enviada.title;
		var producto = enviada.name;
		var proceso = 10;

		if (valorActual == 0) {
			document.getElementById("vw" + producto).innerHTML = "SI";
			document.getElementById("vw" + producto).title = 1;
		}

		if (valorActual == 1) {
			document.getElementById("vw" + producto).innerHTML = "NO";
			document.getElementById("vw" + producto).title = 0;
		}

		$('#resp').empty().hide().html("Esperando...").show(1);
		datos = "producto=" + (producto) + "&proceso=" + (proceso) + "&valorActual=" + (valorActual);
		$.ajax({
			type: "POST",
			url: "ajax/ajax-productos.php",
			data: datos,
			success: function(data) {
				$('#resp').empty().hide().html(data).show(1);
			}
		});
	}
</script>

<?php include("includes/funciones-js.php"); ?>

<?php
$columna = '';
if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) {
	$columna = 'columnas1';
?>

	<style type="text/css">
		#scrolly {
			width: 1400px;
			height: 600px;
			overflow: auto;
			overflow-y: auto;
			margin: 0 auto;
			white-space: nowrap
		}

		.columnas1 {
			width: 200px !important;
			/*text-overflow:ellipsis;*/
			white-space: nowrap;
			overflow: auto;
		}
	</style>

<?php
}
?>


</head>

<body>


	<input type="hidden" value="<?= $tabla; ?>" name="tabla" id="tabla">
	<input type="hidden" value="<?= $pk; ?>" name="pk" id="pk">

	<div class="layout">
		<?php include("includes/encabezado.php"); ?>

		

		<div class="main-wrapper">
			<div class="container-fluid">
				<?php include("includes/notificaciones.php"); ?>
				<span id="resp"></span>


				<p>
					<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10 or $datosUsuarioActual['usr_tipo'] == 13) { ?>
						<a href="productos-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
						<a href="productos-condiciones.php" class="btn btn-warning"><i class="icon-random"></i> Condicionar productos</a>
						<a href="productos-store.php" class="btn btn-info"><i class="icon-th-large"></i> Editar Productos Store JM</a>
						<a href="productos-predeterminados.php" class="btn btn-danger"><i class="icon-th-large"></i> Editar Productos predeterminados</a>
					<?php } ?>

					<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10 or $datosUsuarioActual['usr_tipo'] == 13) { ?>
						<a href="productos-importar.php" class="btn btn-success"><i class="icon-file"></i> Importar excel</a>
						<a href="guardar-precios.php" class="btn btn-danger" onClick="if(!confirm('Desea guardar los precios actuales en el historial?')){return false;}"><i class="icon-save"></i> Guardar precios en historial</a>
					<?php } ?>
				</p>



				<p><b>Precio de lista</b> = Costo + la utilidad. | <b>Descuentos</b> = Se aplican sobre el precio de lista.</p>

				<p>
					<a href="productos.php?todo=1" style="text-decoration: underline; font-weight: bold; color: navy; font-size: 16px;">[VER TODOS]</a>&nbsp;|&nbsp;
					<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) { ?>
						<a href="productos.php?web=1" style="text-decoration: underline;">Tienda/Visible Web</a>&nbsp;|&nbsp;
						<a href="productos.php?pdt=1" style="text-decoration: underline;">Predeterminados</a>&nbsp;|&nbsp;
						<a href="productos.php?nopdt=1" style="text-decoration: underline;">No predeterminados</a>&nbsp;|&nbsp;
						<a href="productos.php?utilidad=1" style="text-decoration: underline;">Sin utilidad</a>&nbsp;|&nbsp;
						<a href="productos.php?stock=1" style="text-decoration: underline;">Sin existencias</a>&nbsp;|&nbsp;
						<a href="productos.php?nodctomax=1" style="text-decoration: underline;">Sin descuento máximo</a>&nbsp;|&nbsp;
					<?php } ?>
				</p>


				<p>
					<div class="btn-group">
						<button class="btn btn-primary">Grupo 1</button>
						<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<?php
							$grupos1 = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=1 AND catp_id_empresa='".$idEmpresa."'");
							while ($grupo1 = mysqli_fetch_array($grupos1, MYSQLI_BOTH)) {
							?>
								<li><a href="productos.php?grupo1=<?= $grupo1[0]; ?>" style="color:<?= $color; ?>"><?= $grupo1['catp_nombre']; ?></a></li>
							<?php } ?>
						</ul>
					</div>

					<div class="btn-group">
						<button class="btn btn-primary">Grupo 2</button>
						<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<?php
							$grupos2 = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=2 AND catp_id_empresa='".$idEmpresa."'");
							while ($grupo2 = mysqli_fetch_array($grupos2, MYSQLI_BOTH)) {
							?>
								<li><a href="productos.php?grupo2=<?= $grupo2[0]; ?>" style="color:<?= $color; ?>"><?= $grupo2['catp_nombre']; ?></a></li>
							<?php } ?>
						</ul>
					</div>

					<div class="btn-group">
						<button class="btn btn-primary">Marca</button>
						<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<?php
							$marcas = $conexionBdPrincipal->query("SELECT * FROM marcas WHERE mar_id_empresa='".$idEmpresa."'");
							while ($marca = mysqli_fetch_array($marcas, MYSQLI_BOTH)) {
							?>
								<li><a href="productos.php?marca=<?= $marca[0]; ?>" style="color:<?= $color; ?>"><?= $marca[1]; ?></a></li>
							<?php } ?>
						</ul>
					</div>
				</p>
				<p>
					<form method="get" action="productos.php" style="text-align: center; margin-top: 10px;">
						<div class="control-group">
							<div class="controls">
								<input type="text" class="span8" name="busqueda" placeholder="Búsqueda en todos los registros..." value="<?php if(isset($_GET['busqueda'])){$_GET['busqueda'];}?>" required><br>
								<button type="submit" class="btn btn-info"> Buscar</button>
								<a href="productos.php" type="submit" class="btn btn-danger"> Quitar filtro</a>
							</div>
						</div>

					</form>
				</p>
				<div class="row-fluid">
					<div class="span12">
						<div class="content-widgets light-gray">
							<div class="widget-head green">
								<h3><?= $paginaActual['pag_nombre']; ?></h3>

							</div>

							<div id="scrolly" class="widget-container">

								<table class="table table-striped table-bordered" id="data-table" style="font-size: 9px;">
									<thead>
										<tr>
											<th>No</th>
											<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) { ?>
												<th>PDT</th>
												<th>VIS. WEB</th>
											<?php } ?>
											<th>CÓDIGO</th>
											<th>Nombre</th>
											<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10 or $datosUsuarioActual['usr_tipo'] == 13) { ?>
												<th>Costo</th>
											<?php } ?>
											<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) { ?>
												<th>Utilidad (%)</th>
											<?php } ?>
											<th title="Sobre el precio de lista.">Dcto. Max. (%)</th>

											<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) { ?>
												<th>Comisión (%)</th>
												<th>Comisión externo (%)</th>
												<th title="Sobre el precio de lista.">Utilidad Web. (%)</th>
												<th>Precio web</th>
												<th title="Sobre el precio de lista.">Utilidad Dealer. (%)</th>	
											<?php } ?>

											<th>Precio lista</th>
											<th>Precio lista</br>Segun dolar hoy</th>
											<th>Precio lista (USD)</th>

											<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) { ?>
												<th>Precio lista + IVA</th>
												<th>Precio dealer</th>	
											<?php } ?>

											<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) { ?>
												<th>Grupo 1</th>
											<?php } ?>
											<th>Grupo 2</th>
											<th>Marca</th>
											<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) { ?>
												<th>P. Fábrica<br>USD</th>
												<th>Flete<br>USD</th>
												<th>Aduana<br>USD</th>
											<?php } ?>
											<th>Existencia</th>
											<!--<th>Precio Min.</th>
											<th>Comisión estimada</th>-->
										</tr>
									</thead>
									<tbody>
										<?php
										$limite = 100;
										if(isset($_GET["todo"])){
											if ($_GET["todo"] == 1) {
												$limite = 10000;
											}
										}

										$filtro = '';
										if(isset($_GET["grupo1"])){
											if (is_numeric($_GET["grupo1"])) {
												$filtro .= " AND prod_grupo1='" . $_GET["grupo1"] . "'";
											}
										}
										if(isset($_GET["grupo2"])){
											if (is_numeric($_GET["grupo2"])) {
												$filtro .= " AND prod_categoria='" . $_GET["grupo2"] . "'";
											}
										}
										if(isset($_GET["marca"])){
											if (is_numeric($_GET["marca"])) {
												$filtro .= " AND prod_marca='" . $_GET["marca"] . "'";
											}
										}
										if(isset($_GET["web"])){
											if (is_numeric($_GET["web"])) {
												$filtro .= " AND prod_visible_web=1";
											}
										}
										if(isset($_GET["pdt"])){
											if (is_numeric($_GET["pdt"])) {
												$filtro .= " AND prod_precio_predeterminado=1";
											}
										}
										if(isset($_GET["utilidad"])){
											if ($_GET["utilidad"] == 1) {
												$filtro .= " AND prod_utilidad=0 OR prod_utilidad=''";
											}
										}
										if(isset($_GET["nopdt"])){
											if (is_numeric($_GET["nopdt"])) {
												$filtro .= " AND prod_precio_predeterminado=0";
											}
										}
										if(isset($_GET["stock"])){
											if (is_numeric($_GET["stock"])) {
												$filtro .= " AND prod_existencias<=0";
											}
										}
										if(isset($_GET["nodctomax"])){
											if (is_numeric($_GET["nodctomax"])) {
												$filtro .= " AND prod_descuento1<=0";
											}
										}
										if(isset($_GET["busqueda"])){
											if ($_GET["busqueda"] != "") {
												$filtro .= " AND (prod_referencia LIKE '%" . $_GET["busqueda"] . "%' OR prod_nombre LIKE '%" . $_GET["busqueda"] . "%')";
											}
										}

										$consulta = $conexionBdPrincipal->query("SELECT * FROM productos LEFT JOIN productos_categorias ON catp_id=prod_categoria WHERE prod_id=prod_id AND prod_id_empresa='".$idEmpresa."' $filtro LIMIT 0, $limite");
										$no = 1;
										$visible = array("SI", "SI", "NO");
										$estadoVisible = array(2, 2, 1);
										$comision=0;
										$precioListaUSD=0;
										$precioWeb=0;
										while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {

											$consultaGrupo1=$conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_id='" . $res['prod_grupo1'] . "' AND catp_id_empresa='".$idEmpresa."'");
											$grupo1 = mysqli_fetch_array($consultaGrupo1, MYSQLI_BOTH);

											$consultaMarca=$conexionBdPrincipal->query("SELECT * FROM marcas WHERE mar_id='" . $res['prod_marca'] . "' AND mar_id_empresa='".$idEmpresa."' ");
											$marca = mysqli_fetch_array($consultaMarca, MYSQLI_BOTH);

											/*$dcto1 = $res['prod_descuento1']/100;
											$precioMinimo = $res['prod_precio'] - ($res['prod_precio']*$dcto1);*/

											$utilidadDealer = $res['prod_descuento2'] / 100;

											if(!empty($res['prod_costo'])){
											$precioDealer = $res['prod_costo'] + ($res['prod_costo'] * $utilidadDealer);
											}

											$utilidadWeb = 0;
											if(!empty($res['prod_descuento_web'])){
												$utilidadWeb = $res['prod_descuento_web'] / 100;
											}
											if(!empty($res['prod_costo'])){
											$precioWeb = $res['prod_costo'] + ($res['prod_costo'] * $utilidadWeb);
											}

											if(!empty($res['prod_utilidad']) AND !empty($res['prod_costo_dolar'])){
											$precioListaUSD = productosPrecioListaUSD($res['prod_utilidad'], $res['prod_costo_dolar']);
											}

											if(!empty($res['prod_comision'])){
											$comision = $res['prod_comision'] / 100;
											}
											$valorComision = ($res['prod_precio'] * $comision);

											$precioConIva = $res['prod_precio'] + ($res['prod_precio'] * 0.19);

											$precioListaDolarHoy = ($precioListaUSD * $configuracion['conf_trm_venta']);
										?>
											<tr>
												<td><?= $no; ?></td>

												<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) { ?>
													<td><a href="#" onClick="pred(this)" name="<?= $res[$pk]; ?>" title="<?= $res['prod_precio_predeterminado']; ?>" id="p<?= $res[$pk]; ?>"><?=$opcionSINO[$res['prod_precio_predeterminado']];?></a></td>

													<td><a href="#" onClick="visweb(this)" name="<?= $res[$pk]; ?>" title="<?= $res['prod_visible_web']; ?>" id="vw<?= $res[$pk];?>"><?= $opcionSINO[$res['prod_visible_web']]; ?></a></td>
												<?php } ?>

												<td align="center" style="font-weight: bold;">
													<input type="text" title="prod_referencia" name="<?= $res[$pk]; ?>" value="<?= $res['prod_referencia']; ?>" style="width: 60px; text-align: center" onChange="productos(this)" <?php if (!$datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) {echo "disabled";} ?>>
													<span style="visibility: hidden;"><?= $res['prod_referencia']; ?></span>
												</td>

												<td>
													<div class="<?= $columna; ?>">
														<?= $res['prod_nombre']; ?>
														<h4>
															<a href="productos-editar.php?id=<?= $res[0]; ?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
															<a href="bd_delete/productos-eliminar.php?id=<?= $res[0]; ?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
															<!--<a href="productos-materiales.php?pdto=<?= $res[0]; ?>" data-toggle="tooltip" title="Materiales"><i class="icon-folder-open"></i></a>-->
															<a href="productos-galeria.php?id=<?= $res[0]; ?>" data-toggle="tooltip" title="Galería"><i class="icon-picture"></i></a>
															<a href="bodegas-productos.php?prod=<?= $res[0]; ?>" data-toggle="tooltip" title="Bodegas por productos"><i class="icon-pushpin"></i></a>
															<a href="productos-historial-precios.php?prod=<?= $res[0]; ?>" data-toggle="tooltip" title="Historial de precios"><i class="icon-time"></i></a>
															<a href="bd_create/productos-replicar-guardar.php?prod=<?= $res[0]; ?>" data-toggle="tooltip" title="Replicar a productos de soporte" onClick="if(!confirm('Desea replicar este producto a soporte operativo?')){return false;}"><i class="icon-repeat"></i></a>
														</h4>
													</div>
												</td>

												<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10 or $datosUsuarioActual['usr_tipo'] == 13) { ?>
													<td>
														COP: <input id="costo<?= $res['prod_id']; ?>" type="text" alt="<?= $res['prod_utilidad']; ?>" title="prod_costo" name="<?= $res[$pk]; ?>" value="<?= $res['prod_costo']; ?>" style="width: 80px; text-align: center" onChange="productos(this)"><br>

														USD: <input type="text" title="prod_costo_dolar" name="<?= $res[$pk]; ?>" value="<?= $res['prod_costo_dolar']; ?>" style="width: 80px; text-align: center" onChange="productos(this)">
													</td>
													<?php } ?>	

													<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) { ?>

													<td>
														<input id="utilidad<?= $res['prod_id']; ?>" type="text" alt="<?= $res['prod_costo']; ?>" title="prod_utilidad" name="<?= $res[$pk]; ?>" value="<?= $res['prod_utilidad']; ?>" style="width: 40px; text-align: center" onChange="productos(this)">
														<span style="visibility: hidden;"><?= $res['prod_utilidad']; ?></span>
													</td>
												<?php } ?>


												<td>
													<input type="text" title="prod_descuento1" name="<?= $res[$pk]; ?>" value="<?= $res['prod_descuento1']; ?>" style="width: 40px; text-align: center" onChange="productos(this)" <?php if ($_SESSION["id"] != 7 and $_SESSION["id"] != 15) {echo "disabled";} ?>>
													<span style="visibility: hidden;"><?= $res['prod_descuento1']; ?></span>
												</td>

												<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) { ?>
													<td>
														<input type="text" title="prod_comision" name="<?= $res[$pk]; ?>" value="<?= $res['prod_comision']; ?>" style="width: 40px; text-align: center" onChange="productos(this)">
														<span style="visibility: hidden;"><?= $res['prod_comision']; ?></span>
													</td>

													<td>
														<input type="text" title="prod_comision_externo" name="<?= $res[$pk]; ?>" value="<?= $res['prod_comision_externo']; ?>" style="width: 40px; text-align: center" onChange="productos(this)">
														<span style="visibility: hidden;"><?= $res['prod_comision_externo']; ?></span>
													</td>

													<td>
														<input type="text" title="prod_descuento_web" name="<?= $res[$pk]; ?>" value="<?= $res['prod_descuento_web']; ?>" style="width: 40px; text-align: center" onChange="productos(this)">
														<span style="visibility: hidden;"><?= $res['prod_descuento_web']; ?></span>
													</td>

													<td>$<?= number_format($precioWeb, 0, ",", "."); ?></td>

													<td>
														<input type="text" title="prod_descuento2" name="<?= $res[$pk]; ?>" value="<?= $res['prod_descuento2']; ?>" style="width: 40px; text-align: center" onChange="productos(this)">
														<span style="visibility: hidden;"><?= $res['prod_descuento2']; ?></span>
													</td>

													

													
												<?php
													} 
													$num=0;
													if(!empty($res['prod_precio'])){
														$num=$res['prod_precio'];
													}
												?>
												<td id="precioLista<?= $res['prod_id']; ?>">$<?= number_format($num, 0, ",", "."); ?></td>

												<td id="precioListaDolarHoy<?= $res['prod_id']; ?>">$<?= number_format($precioListaDolarHoy, 0, ",", "."); ?></td>

												<td id="precioListaUSD<?= $res['prod_id']; ?>">USD <?= number_format($precioListaUSD, 2, ",", "."); ?></td>

												<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) { ?>
													<td id="precioListaIva<?= $res['prod_id']; ?>">$<?= number_format($precioConIva, 0, ",", "."); ?></td>

													<td>$<?= number_format($precioDealer, 0, ",", "."); ?></td>

													
												<?php } ?>

												<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) { ?>
													<td><?= $grupo1['catp_nombre']; ?></td>
												<?php } ?>

												<td><?= $res['catp_nombre']; ?></td>
												<td><?= $marca['mar_nombre']; ?></td>



												<?php if ($datosUsuarioActual['usr_tipo'] == 1 or $datosUsuarioActual['usr_tipo'] == 10) { ?>
													<td>
														<input type="text" title="prod_precio_fabrica" name="<?= $res[$pk]; ?>" value="<?= $res['prod_precio_fabrica']; ?>" style="width: 80px; text-align: center" onChange="productos(this)">
														<span style="visibility: hidden;"><?= $res['prod_precio_fabrica']; ?></span>
													</td>

													<td>
														<input type="text" title="prod_flete" name="<?= $res[$pk]; ?>" value="<?= $res['prod_flete']; ?>" style="width: 80px; text-align: center" onChange="productos(this)">
														<span style="visibility: hidden;"><?= $res['prod_flete']; ?></span>
													</td>

													<td>
														<input type="text" title="prod_aduana" name="<?= $res[$pk]; ?>" value="<?= $res['prod_aduana']; ?>" style="width: 80px; text-align: center" onChange="productos(this)">
														<span style="visibility: hidden;"><?= $res['prod_aduana']; ?></span>
													</td>
												<?php } ?>
												<td align="center" style="font-weight: bold;">
													<input type="text" title="prod_existencias" name="<?= $res[$pk]; ?>" value="<?= $res['prod_existencias']; ?>" style="width: 60px; text-align: center" onChange="productos(this)" disabled>
													<span style="visibility: hidden;"><?= $res['prod_existencias']; ?></span>
												</td>
												<!--<td>$<?= number_format($precioMinimo, 0, ",", "."); ?></td>
												<td>$<?= number_format($valorComision, 0, ",", "."); ?></td>-->
											</tr>
										<?php $no++;
										} ?>
									</tbody>
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