<?php include("sesion.php"); ?>
<?php
$idPagina = 88;
$paginaActual['pag_nombre'] = "Tickets de clientes";
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
									<li class="active"><a href="clientes-tikets-agregar.php?cte=<?= $_GET["cte"]; ?>&tipo=<?= $_GET["tipo"]; ?>"><i class="icon-plus"></i> Agregar nuevo</a></li>
									<li><a href="tikets-filtros.php"><i class="icon-file"></i> Sacar informe</a></li>
									<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#">Más opciones <b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="excel-exportar.php?exp=3">Exportar todo a excel</a></li>
											<li class="divider"></li>
											<li class="nav-header">Nav header</li>
											<li><a href="#">Separated link</a></li>
										</ul>
									</li>
								</ul>

								<form action="<?= $_SERVER['PHP_SELF']; ?>" method="GET" class="navbar-search pull-left">
									<input type="text" placeholder="Búsqueda y pulse Enter..." name="busqueda" class="search-query span4" value="<?= $_GET["busqueda"]; ?>">
								</form>

								<ul class="nav pull-right">
									<li><a href="#">Link</a></li>
									<li class="divider-vertical"></li>
									<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#">Dropdown <b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="#">Action</a></li>
											<li><a href="#">Another action</a></li>
											<li><a href="#">Something else here</a></li>
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

				<div class="row-fluid">
					<div class="span12">
						<div class="content-widgets light-gray">
							<div class="widget-head green">
								<h3><?= $paginaActual['pag_nombre']; ?></h3>
							</div>

							<?php
							$filtro = "";
							if (isset($_GET["busqueda"]) and $_GET["busqueda"] != "") {
								$filtro .= " AND (tik_id LIKE '%" . $_GET["busqueda"] . "%' OR tik_asunto_principal LIKE '%" . $_GET["busqueda"] . "%')";
							}
							if (isset($_GET["cte"]) and $_GET["cte"] != "") {
								$filtro .= " AND tik_cliente='" . $_GET["cte"] . "'";
							}
							if (isset($_GET["resp"]) and $_GET["resp"] != "") {
								$filtro .= " AND tik_usuario_responsable='" . $_GET["resp"] . "'";
							}
							if (isset($_GET["tipo"]) and $_GET["tipo"] != "") {
								$filtro .= " AND tik_tipo_tiket='" . $_GET["tipo"] . "'";
							}
							?>

							<?php
							if ($datosUsuarioActual[3] == 1) {
								$SQL = "SELECT * FROM clientes_tikets
								INNER JOIN clientes ON cli_id=tik_cliente
								INNER JOIN usuarios ON usr_id=tik_usuario_responsable
								WHERE tik_id=tik_id $filtro
								ORDER BY tik_id DESC
								";
							} else {
								$SQL = "SELECT * FROM clientes_tikets
								INNER JOIN clientes ON cli_id=tik_cliente
								INNER JOIN usuarios ON usr_id=tik_usuario_responsable
								WHERE tik_usuario_responsable='" . $_SESSION["id"] . "' $filtro
								ORDER BY tik_id DESC
								";
							}
							?>

							<p style="margin: 10px;"><?php include("includes/paginacion.php"); ?></p>

							<div class="widget-container">
								<?php include("includes/notificaciones.php"); ?>
								<p></p>
								<table class="table table-striped table-bordered" id="data-table" style="font-size: 10px;">
									<thead>
										<tr>
											<th>No</th>
											<th>ID</th>
											<th>Tipo</th>
											<th>F. Inicio</th>
											<th>Cliente</th>
											<th>Sucursal</th>
											<th>Asunto</th>
											<th>Resposable</th>
											<th>Estado</th>
											<th>Prioridad</th>
											<th>Seg.</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($datosUsuarioActual[3] == 1) {
											$consulta = mysql_query("SELECT * FROM clientes_tikets
											INNER JOIN clientes ON cli_id=tik_cliente
											INNER JOIN usuarios ON usr_id=tik_usuario_responsable
											WHERE tik_id=tik_id $filtro
											ORDER BY tik_id DESC
											LIMIT $inicio, $limite
											", $conexion);
										} else {
											$consulta = mysql_query("SELECT * FROM clientes_tikets
											INNER JOIN clientes ON cli_id=tik_cliente
											INNER JOIN usuarios ON usr_id=tik_usuario_responsable
											WHERE tik_usuario_responsable='" . $_SESSION["id"] . "' $filtro
											ORDER BY tik_id DESC
											LIMIT $inicio, $limite
											", $conexion);
										}
										$no = 1;
										while ($res = mysql_fetch_array($consulta)) {

											$asuntos = mysql_fetch_array(mysql_query("SELECT * FROM tikets_asuntos WHERE tkpas_id='" . $res["tik_asunto_principal"] . "'", $conexion));

											if ($datosUsuarioActual[3] != 1) {
												$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='" . $_SESSION["id"] . "' AND zpu_zona='" . $res['cli_zona'] . "'", $conexion));
												if ($numZ == 0) continue;
											}

											$sucursal = mysql_fetch_array(mysql_query("SELECT * FROM sucursales WHERE sucu_id='" . $res['tik_sucursal'] . "'", $conexion));
											switch ($res['tik_tipo_tiket']) {
												case 1:
													$tipoS = 'Comercial';
													$etiquetaT = 'success';
													break;
												case 3:
													$tipoS = 'Soporte operativo';
													$etiquetaT = 'important';
													break;
											}

											switch ($res['tik_estado']) {
												case 1:
													$estado = 'Abierto';
													$etiquetaE = 'important';
													break;
												case 2:
													$estado = 'Cerrado';
													$etiquetaE = 'info';
													break;
											}

											switch ($res['tik_prioridad']) {
												case 1:
													$prioridad = 'Normal';
													$etiquetaP = 'success';
													break;
												case 2:
													$prioridad = 'Urgente';
													$etiquetaP = 'warning';
													break;
												case 3:
													$prioridad = 'Muy Urgente';
													$etiquetaP = 'important';
													break;
											}

											$numeros = mysql_fetch_array(mysql_query("
								SELECT
								(SELECT count(cseg_id) FROM cliente_seguimiento WHERE cseg_tiket='" . $res['tik_id'] . "')
								", $conexion));
										?>
											<tr>
												<td><?= $no; ?></td>
												<td><?= $res['tik_id']; ?></td>
												<td><span class="badge badge-<?= $etiquetaT; ?>"><?= $tipoS; ?></span></td>
												<td><?= $res['tik_fecha_creacion']; ?></td>
												<td>
													<?php echo "<b>Nombre</b>:" . $res['cli_nombre']; ?>
													<?php if ($res['cli_telefono'] != "") echo "<br><b>Tel:</b> " . $res['cli_telefono']; ?>
													<?php if ($res['cli_email'] != "") echo "<br><b>Email:</b> " . $res['cli_email']; ?>
												</td>
												<td><?= $sucursal['sucu_nombre']; ?></td>
												<td><?= $res['tik_asunto_principal']; ?></td>
												<td><?= $res['usr_nombre']; ?></td>
												<td><a href="sql.php?get=29&id=<?= $res[0]; ?>" onClick="if(!confirm('Recuerde completar todos los seguimientos pendientes, en caso de tenerlos, antes de cerrar el ticket. Desea continuar con el cierre del ticket?')){return false;}"><span class="label label-<?= $etiquetaE; ?>"><?= $estado; ?></span></a></td>
												<td><span class="label label-<?= $etiquetaP; ?>"><?= $prioridad; ?></span></td>
												<td align="center" style="background:<?= $color2; ?>;"><a href="clientes-seguimiento.php?idTK=<?= $res[0]; ?>" target="_blank"><?= $numeros[0]; ?></a></td>
												<td>
													<h4>
														<!--
                                	<a href="clientes-seguimiento.php?idTK=<?= $res[0]; ?>&emg=1" data-toggle="tooltip" title="Seguimiento" target="new"><i class="icon-list-alt"></i></a>
									-->

														<a href="clientes-tikets-editar.php?id=<?= $res[0]; ?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
														<a href="sql.php?id=<?= $res[0]; ?>&get=24" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
													</h4>
												</td>
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