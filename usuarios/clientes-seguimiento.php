<?php include("sesion.php"); ?>
<?php
$idPagina = 12;
$paginaActual['pag_nombre'] = "Seguimiento de clientes";
?>

<?php include("includes/verificar-paginas.php"); ?>
<?php include("includes/head.php"); ?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('" . $_SESSION["id"] . "', '" . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "', '" . $idPagina . "', now(),'" . $_SERVER['HTTP_REFERER'] . "')", $conexion);
if (mysql_errno() != 0) {
	echo mysql_error();
	exit();
}
?>
<?php
$tiket = mysql_fetch_array(mysql_query("SELECT * FROM clientes_tikets WHERE tik_id='" . $_GET["idTK"] . "'", $conexion));
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

<?php include("includes/funciones-js.php"); ?>
</head>

<body>
	<div class="layout">
		<?php include("includes/encabezado.php"); ?>

		
		<div class="main-wrapper">
			<div class="container-fluid">

				<div class="navbar">
					<div class="navbar-inner">
						<div class="container">
							<!--<a href="#" class="brand"><img src="images/logo-small.png" width="99" height="40" alt="Falgun"></a>-->
							<div class="nav-collapse collapse navbar-responsive-collapse">
								<ul class="nav">
									<li><a href="javascript:history.go(-1);"><i class="icon-arrow-left"></i> Regresar</a></li>
									<?php if (isset($_GET["idTK"]) or isset($_GET["cte"])) { ?>
										<li class="active"><a href="clientes-seguimiento-agregar.php?idTK=<?= $_GET["idTK"]; ?>&cte=<?= $_GET["cte"]; ?>"><i class="icon-plus"></i> Agregar nuevo</a></li>
									<?php } ?>
									<li><a href="usuarios-filtro.php"><i class="icon-file"></i> Informe de usuarios</a></li>
									<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#">Más opciones <b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="clientes-seguimiento-filtro.php?idTK=<?= $_GET["idTK"]; ?>&cte=<?= $_GET["cte"]; ?>">Imprimir informe</a></li>
										</ul>
									</li>
								</ul>

								<form action="<?= $_SERVER['PHP_SELF']; ?>" method="GET" class="navbar-search pull-left">
									<input type="text" placeholder="Búsqueda y pulse Enter..." name="busqueda" class="search-query span4" value="<?= $_GET["busqueda"]; ?>">
								</form>

							</div>
							<!-- /.nav-collapse -->
						</div>
					</div>
					<!-- /navbar-inner -->
				</div>

				<div class="alert alert-info">
					<i class="icon-exclamation-sign"></i>
					<strong>Información!</strong>
					Haga click sobre el estado del seguimiento para cambiar su estado a pendiente o completado.
				</div>

				<div align="center" style="margin:10px; font-size:20px;">
					<?php
					$a = $configu['conf_agno_inicio'];
					while ($a <= date("Y")) {
						if ($a == $_GET["a"])
							echo '<a style="font-weight:bold;">' . $a . '</a>&nbsp;&nbsp;&nbsp;';
						else
							echo '<a href="clientes-seguimiento.php?a=' . $a . '&m=' . $_GET["m"] . '&u=' . $_GET["u"] . '">' . $a . '</a>&nbsp;&nbsp;&nbsp;';
						$a++;
					}
					?>
				</div>

				<div align="center" style="margin:10px; font-size:18px;">
					<?php
					$m = 1;
					$meses = array("", "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
					while ($m <= 12) {
						if ($m == $_GET["m"])
							echo '<a style="font-weight:bold;">' . $meses[$m] . '</a>&nbsp;&nbsp;&nbsp;';
						else
							echo '<a href="clientes-seguimiento.php?m=' . $m . '&a=' . $_GET["a"] . '&u=' . $_GET["u"] . '">' . $meses[$m] . '</a>&nbsp;&nbsp;&nbsp;';
						$m++;
					}
					?>
				</div>


				<?php
				$filtro = "";
				if (isset($_GET["busqueda"]) and $_GET["busqueda"] != "") {
					$filtro .= " AND (cseg_id LIKE '%" . $_GET["busqueda"] . "%' OR cseg_observacion LIKE '%" . $_GET["busqueda"] . "%' OR cseg_asunto LIKE '%" . $_GET["busqueda"] . "%')";
				}

				$orden = 'ORDER BY cseg_id DESC';
				if (isset($_GET["seg"]) and $_GET["seg"] != "" and is_numeric($_GET["seg"])) {
					$orden = 'ORDER BY cseg_id=' . $_GET["seg"] . ' DESC';
				}

				$filtroUsuario = " AND (cseg_usuario_responsable='" . $_SESSION["id"] . "' OR cseg_usuario_encargado='" . $_SESSION["id"] . "')";

				if ($_GET["estado"] == 1) {
					$filtro .= ' AND cseg_realizado=1';
					$filtroUsuario = '';
				}
				if ($_GET["estado"] == 2) {
					$filtro .= ' AND cseg_realizado IS NULL';
					$filtroUsuario = '';
				}
				if (is_numeric($_GET["idTK"])) {
					$filtro .= " AND cseg_tiket='" . $_GET["idTK"] . "'";
					$filtroUsuario = '';
				}
				if (is_numeric($_GET["cte"])) {
					$filtro .= " AND cseg_cliente='" . $_GET["cte"] . "'";
					$filtroUsuario = '';
				}
				if (is_numeric($_GET["a"])) {
					$filtro .= " AND YEAR(cseg_fecha_reporte)='" . $_GET["a"] . "'";
					$filtroUsuario = '';
				}
				if (is_numeric($_GET["m"])) {
					$filtro .= " AND MONTH(cseg_fecha_reporte)='" . $_GET["m"] . "'";
					$filtroUsuario = '';
				}

				if ($_GET["inf"] == 1) {
					$SQL = "SELECT * FROM cliente_seguimiento
				INNER JOIN clientes ON cli_id=cseg_cliente
				INNER JOIN localidad_ciudades ON ciu_id=cli_ciudad
				INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
				WHERE cseg_usuario_responsable='" . $_GET["usuario"] . "' AND cseg_fecha_reporte>='" . $_GET["desde"] . "' AND cseg_fecha_reporte<='" . $_GET["hasta"] . "' AND (cseg_canal='" . $_GET["canal"] . "' OR cseg_canal='" . $_GET["canalDos"] . "')
				";
				} elseif ($_GET["inf"] == 2) {
					$SQL = "SELECT * FROM cliente_seguimiento
				INNER JOIN clientes ON cli_id=cseg_cliente
				INNER JOIN localidad_ciudades ON ciu_id=cli_ciudad
				INNER JOIN usuarios ON usr_id=cseg_usuario_encargado
				WHERE cseg_usuario_encargado='" . $_GET["usuario"] . "' AND cseg_fecha_proximo_contacto>='" . $_GET["desde"] . "' AND cseg_fecha_proximo_contacto<='" . $_GET["hasta"] . "' AND (cseg_canal='" . $_GET["canal"] . "' OR cseg_canal='" . $_GET["canalDos"] . "') AND (cseg_realizado IS NULL OR cseg_realizado=0)
				";
				} else {
					$SQL = "SELECT * FROM cliente_seguimiento
				INNER JOIN clientes ON cli_id=cseg_cliente
				INNER JOIN localidad_ciudades ON ciu_id=cli_ciudad
				INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
				WHERE cseg_id=cseg_id $filtroUsuario $filtro  
				$orden
				";
				}
				?>


				<div class="row-fluid">
					<div class="span12">
						<p style="font-size: 11px;">
							DT = Consiguió datos | CZ = Hubo cotización | VT = Hubo venta
						</p>

						<p style="margin: 10px;"><?php include("includes/paginacion.php"); ?></p>

						<div class="content-widgets light-gray">
							<div class="widget-head green">
								<h3><?= $paginaActual['pag_nombre']; ?> : <b><?= $tiket['tik_asunto_principal']; ?></b></h3>
							</div>
							<div class="widget-container">
								<?php include("includes/notificaciones.php"); ?>
								<p></p>
								<table class="table table-striped table-bordered" id="data-table">
									<thead>
										<tr>
											<th>No</th>
											<th>Cliente</th>
											<th>Contacto</th>
											<th>Seguimiento</th>
											<th>DT</th>
											<th>CZ</th>
											<th>VT</th>
											<th>Estado</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($_GET["inf"] == 1) {
											$consulta = mysql_query("SELECT * FROM cliente_seguimiento
								INNER JOIN clientes ON cli_id=cseg_cliente
								INNER JOIN localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
								WHERE cseg_usuario_responsable='" . $_GET["usuario"] . "' AND cseg_fecha_reporte>='" . $_GET["desde"] . "' AND cseg_fecha_reporte<='" . $_GET["hasta"] . "' AND (cseg_canal='" . $_GET["canal"] . "' OR cseg_canal='" . $_GET["canalDos"] . "')
								LIMIT $inicio, $limite
								", $conexion);
										} elseif ($_GET["inf"] == 2) {
											$consulta = mysql_query("SELECT * FROM cliente_seguimiento
								INNER JOIN clientes ON cli_id=cseg_cliente
								INNER JOIN localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN usuarios ON usr_id=cseg_usuario_encargado
								WHERE cseg_usuario_encargado='" . $_GET["usuario"] . "' AND cseg_fecha_proximo_contacto>='" . $_GET["desde"] . "' AND cseg_fecha_proximo_contacto<='" . $_GET["hasta"] . "' AND (cseg_canal='" . $_GET["canal"] . "' OR cseg_canal='" . $_GET["canalDos"] . "') AND (cseg_realizado IS NULL OR cseg_realizado=0)
								LIMIT $inicio, $limite
								", $conexion);
										} else {
											$consulta = mysql_query("SELECT * FROM cliente_seguimiento
								INNER JOIN clientes ON cli_id=cseg_cliente
								INNER JOIN localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
								WHERE cseg_id=cseg_id $filtroUsuario $filtro  
								$orden
								LIMIT $inicio, $limite
								", $conexion);
										}


										$opcionesSino = array("NO", "SI");
										$no = 1;
										while ($res = mysql_fetch_array($consulta)) {

											$encargado = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='" . $res['cseg_usuario_encargado'] . "'", $conexion));

											$contacto = mysql_fetch_array(mysql_query("SELECT * FROM contactos 
								INNER JOIN sucursales ON sucu_id=cont_sucursal
								WHERE cont_id='" . $res['cseg_contacto'] . "'", $conexion));

											if ($datosUsuarioActual[3] != 1) {
												$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='" . $_SESSION["id"] . "' AND zpu_zona='" . $res['cli_zona'] . "'", $conexion));
												if ($numZ == 0) continue;
											}

											if ($res['cseg_id'] == $_GET['seg']) {
												$fondoColor = 'style="background:#99DFC6; font-weight:bold;"';
											} else {
												$fondoColor = '';
											}
											switch ($res['cseg_realizado']) {
												case 1:
													$html = '<span class="label label-success">Completado</span>';
													break;
												default:
													$html = '<a href="sql.php?id=' . $res['cseg_id'] . '&get=28" class="label label-important">Pendiente</a>';
													break;
											}

											$ticketR = mysql_fetch_array(mysql_query("SELECT * FROM clientes_tikets WHERE tik_id='" . $res['cseg_tiket'] . "'", $conexion));
										?>
											<tr>
												<td <?= $fondoColor; ?>><?= $no; ?></td>
												<td <?= $fondoColor; ?>>
													<?php echo "<b>Nombre</b>:" . $res['cli_nombre']; ?>
													<?php if ($res['cli_telefono'] != "") echo "<br><b>Tel:</b> " . $res['cli_telefono']; ?>
													<?php if ($res['cli_celular'] != "") echo "<br><b>Cel:</b> " . $res['cli_celular']; ?>
													<?php if ($res['cli_email'] != "") echo "<br><b>Email:</b> " . $res['cli_email']; ?>
													<?php echo "<br><b>Ciudad:</b> " . $res['ciu_nombre']; ?>

													<h4 style="margin-top:10px;">
														<a href="clientes-seguimiento-agregar.php?idTK=<?= $res['cseg_tiket']; ?>" data-toggle="tooltip" title="Nuevo Seguimiento"><i class="icon-plus"></i></a>
														<a href="clientes-seguimiento-editar.php?id=<?= $res[0]; ?>&idTK=<?= $_GET["idTK"]; ?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>&nbsp;
														<a href="sql.php?id=<?= $res[0]; ?>&get=4&idTK=<?= $_GET["idTK"]; ?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>

													</h4>
												</td>
												<td <?= $fondoColor; ?>>
													<?php echo "<b>Nombre</b>:" . $contacto['cont_nombre']; ?>
													<?php if ($contacto['cont_telefono'] != "") echo "<br><b>Tel:</b> " . $contacto['cont_telefono']; ?>
													<?php if ($contacto['cont_email'] != "") echo "<br><b>Email:</b> " . $contacto['cont_email']; ?>
													<?php if ($contacto['cont_sucursal'] != "") echo "<br><b>SUCURSAL:</b> " . $contacto['sucu_nombre']; ?>
												</td>
												<td <?= $fondoColor; ?>>
													<b>Ticket:</b> <?= "<b>" . $ticketR['tik_id'] . "</b> - " . $ticketR['tik_asunto_principal']; ?>
													<hr>
													<h5 style="font-weight:bold;">GESTIÓN</h5>
													<b>Fecha:</b> <?= $res['cseg_fecha_contacto']; ?>&nbsp;|&nbsp;
													<b>Responsable:</b> <?= $res['usr_nombre']; ?><br>
													<span style="color:#009; font-size: 10px;"><?= $res['cseg_observacion']; ?></span>

													<?php if ($res['cseg_fecha_proximo_contacto'] != '0000-00-00') { ?>
														<p>
															<h5 style="font-weight:bold;">PRÓXIMO CONTACTO</h5>
															<b>Fecha:</b> <?= $res['cseg_fecha_proximo_contacto']; ?>&nbsp;|&nbsp;
															<b>Encargado:</b> <?= $encargado['usr_nombre']; ?><br>
															<span style="color:#009; font-size: 10px;"><?= $res['cseg_asunto']; ?></span>
														</p>
													<?php } ?>
												</td>
												<td <?= $fondoColor; ?>><?= $opcionesSino[$res['cseg_consiguio_datos']]; ?></td>
												<td <?= $fondoColor; ?>><?= $opcionesSino[$res['cseg_cotizo']]; ?></td>
												<td <?= $fondoColor; ?>><?= $opcionesSino[$res['cseg_vendio']]; ?></td>
												<td <?= $fondoColor; ?>><?= $html; ?></td>
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