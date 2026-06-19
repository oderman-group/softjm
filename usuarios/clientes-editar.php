<?php 
include("sesion.php");
$idPagina = 11;
include("includes/verificar-paginas.php");

require_once RUTA_PROYECTO . '/usuarios/class/Notificacion.php';
if (!empty($_GET['not']) && is_numeric($_GET['not'])) {
    Notificacion::marcarVista($conexionBdPrincipal, (int) $_GET['not'], (int) $_SESSION['id']);
}

include("includes/head.php");

include(RUTA_PROYECTO."/usuarios/class/Cliente.php");
require_once("includes/cliente-editar-preparar.php");
?>
<link href="css/chosen.css" rel="stylesheet">
<link href="css/jquery.gritter.css" rel="stylesheet">
<link href="css/clientes-editar.css" rel="stylesheet">
<link href="css/drawer-formulario-cliente.css" rel="stylesheet">
<link href="css/crm-etiquetas.css" rel="stylesheet">
<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-fileupload.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.tagsinput.js"></script>
<script src="js/chosen.jquery.js"></script>
<script src="js/bootstrap-colorpicker.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script src="js/date.js"></script>
<script src="js/daterangepicker.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script src="js/jquery.gritter.js"></script>
<script>
	function mostrar(data) {
		if(data.value == "Colombia"){
			document.getElementById("local").style.display = "block";
			document.getElementById("extrangero").style.display = "none";
		}else{
			document.getElementById("local").style.display = "none";
			document.getElementById("extrangero").style.display = "block";
		}
	}

	function mostrarNombreEvento(data) {
		if(data.value == 4){
			document.getElementById("eventoNombre").style.display = "block";
		} else {
			document.getElementById("eventoNombre").style.display = "none";
		}
	}
</script>

<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>

<?php include("includes/funciones-js.php");?>

<?php include("includes/texto-editor.php");?>

</head>
<body class="cliente-editar-page">
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
    
	<div class="main-wrapper">
		<div class="container-fluid cliente-editar-inner">
			<div class="row-fluid ">
				<div class="span12">
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="clientes.php">Clientes</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?= htmlspecialchars($resultadoD['cli_nombre']) ?></li>
					</ul>
				</div>
			</div>

            <div class="cliente-editar-actions">
						<?php if (Modulos::validarRol([10], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
				<a href="clientes-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
						<?php } ?>
						<?php if (Modulos::validarRol([368], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
				<a href="enviar_correos/clientes-enviar-credenciales.php?id=<?=$clienteId;?>" class="btn btn-info" onClick="if(!confirm('Desea ejecutar esta accion?')){return false;}"><i class="icon-envelope"></i> Enviar credenciales</a>
						<?php } ?>
			</div>

            <?php include("includes/notificaciones.php");?>
            <?php include("includes/cliente-editar-resumen.php"); ?>
			
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray cliente-editar-widget">
						<div class="widget-head bondi-blue">
							<h3>Ficha del cliente</h3>
						</div>

						<div class="widget-container">

									<ul class="nav nav-tabs cliente-editar-tabs" id="myTab1">
										<li class="active"><a href="#user"><i class="icon-tasks"></i> Información</a></li>
										<li><a href="#sucursales"><i class=" icon-home"></i> Sucursales <span class="tab-count"><?= intval($contadoresCliente['sucursales'] ?? 0) ?></span></a></li>
										<li><a href="#task"><i class=" icon-group"></i> Contactos <span class="tab-count"><?= intval($contadoresCliente['contactos'] ?? 0) ?></span></a></li>
										<li><a href="#tickets"><i class=" icon-list"></i> Tickets <span class="tab-count"><?= intval($contadoresCliente['tickets'] ?? 0) ?></span></a></li>
										<li><a href="#seguimientos"><i class=" icon-list-alt"></i> Seguimientos <span class="tab-count"><?= intval($contadoresCliente['seguimientos'] ?? 0) ?></span></a></li>
										<li><a href="#notas-internas"><i class="icon-comment"></i> Notas internas <span class="tab-count" id="tabCountNotasInternas"><?= intval($contadoresCliente['notas_internas'] ?? 0) ?></span></a></li>
										<li><a href="#cotizacion"><i class=" icon-file"></i> Cotizaciones <span class="tab-count"><?= intval($contadoresCliente['cotizaciones'] ?? 0) ?></span></a></li>
										<li><a href="#facturas"><i class=" icon-list-alt"></i> Facturación <span class="tab-count"><?= intval($contadoresCliente['facturas'] ?? 0) ?></span></a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="user">
											<?php include("includes/cliente-editar-formulario.php"); ?>
										</div>
										<div class="tab-pane" id="sucursales">
											<div class="row-fluid">
				<div class="span12">
					
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3>Sucursales</h3>
						</div>
						<div class="widget-container">
							<?php if (Modulos::validarRol([84], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<p><a href="#" class="btn btn-danger js-abrir-sucursal-drawer-crear" data-cliente-id="<?=$clienteId;?>"><i class="icon-plus"></i> Agregar sucursal</a></p>
							<?php } ?>
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>Nombre</th>
								<th>Telefono</th>
                                <th>Celular</th>
                                <th>Ciudad</th>
								<th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$consulta = $conexionBdPrincipal->query("SELECT * FROM sucursales
							INNER JOIN clientes ON cli_id=sucu_cliente_principal 
							INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=sucu_ciudad 
							INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
							WHERE sucu_cliente_principal='".$clienteId."' AND cli_id_empresa='".$idEmpresa."'");
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
							?>
							<tr>
								<td><?=$no;?></td>
                                <td><?=$res['sucu_nombre'];?></td>
                                <td><?=$res['sucu_telefono'];?></td>
                                <td><?=$res['sucu_celular'];?></td>
                                <td><?=$res['ciu_nombre'].", ".$res['dep_nombre'];?></td>
								<td><h4 style="margin-top:10px;">
								<?php if (Modulos::validarRol([85], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="#" class="js-abrir-sucursal-drawer-editar" data-sucursal-id="<?=$res['sucu_id'];?>" data-cliente-id="<?=$clienteId;?>" data-toggle="tooltip" title="Editar sucursal"><i class="icon-edit"></i></a>
								<?php } ?>
								</h4></td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
										</div>

										<div class="tab-pane" id="task">
										<div class="row-fluid">
											
												<div class="span12">
													<div class="content-widgets light-gray">
														<div class="widget-head green">
															<h3>Contactos</h3>
														</div>
														<div class="widget-container">
														<?php if (Modulos::validarRol([45], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
															<p><a href="#" class="btn btn-danger js-abrir-contacto-drawer-crear" data-cliente-id="<?=$clienteId;?>"><i class="icon-plus"></i> Agregar contacto</a></p>
														<?php } ?>
														
															<table class="table table-striped table-bordered" id="data-table">
															<thead>
															<tr>
																<th>No</th>
																<th>Nombre</th>
																<th>Telefono</th>
																<th>Celular</th>
																<th>Email</th>
																<th>Sucursal</th>
																<th>&nbsp;</th>
															</tr>
															</thead>
															<tbody>
															<?php
															$consulta = $conexionBdPrincipal->query("
																SELECT c.*, s.sucu_nombre
																FROM contactos c
																INNER JOIN clientes ON cli_id = c.cont_cliente_principal
																LEFT JOIN sucursales s ON s.sucu_id = c.cont_sucursal
																WHERE c.cont_cliente_principal = '" . $clienteId . "'
															");
															$no = 1;
															while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
																$sucursalNombre = !empty($res['sucu_nombre']) ? $res['sucu_nombre'] : '[Sin sucursal]';
															?>
															<tr>
																<td><?=$no;?></td>
																<td><?=$res['cont_nombre'];?></td>
																<td><?=$res['cont_telefono'];?></td>
																<td><?=$res['cont_celular'];?></td>
																<td><?=$res['cont_email'];?></td>
																<td><?=$sucursalNombre;?></td>
																<td><h4>
																<?php if (Modulos::validarRol([46], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																	<a href="#" class="js-abrir-contacto-drawer-editar" data-contacto-id="<?=$res['cont_id'];?>" data-cliente-id="<?=$clienteId;?>" data-toggle="tooltip" title="Editar contacto"><i class="icon-edit"></i></a>
																<?php } ?>
																</h4></td>
															</tr>
															<?php $no++;}?>
															</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										
										<div class="tab-pane" id="tickets">
										<div class="row-fluid">
												<div class="span12">
													<div class="content-widgets light-gray">
														<div class="widget-head green">
															<h3>Tickets</h3>
														</div>
														<div class="widget-container">
															<p>
															<?php if (Modulos::validarRol([89], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																<a href="clientes-tikets-agregar.php?cte=<?=$clienteId;?>" class="btn btn-danger" target="_blank"><i class="icon-plus"></i> Agregar ticket</a>
															<?php } ?>
															</p>
															<table class="table table-striped table-bordered" id="data-table">
															<thead>
															<tr>
																<th>No</th>
																<th>Tipo</th>
																<th>Fecha Inicio.</th>
																<th>Asunto principal</th>
																<th>Resposable</th>
																<th>Estado</th>
																<th>Prioridad</th>
																<th>#Seg</th>
																<th></th>
															</tr>
															</thead>
															<tbody>
															<?php
															$consulta = $conexionBdPrincipal->query("
																SELECT t.*, u.usr_nombre,
																	(SELECT COUNT(*) FROM cliente_seguimiento cs WHERE cs.cseg_tiket = t.tik_id) AS num_seg
																FROM clientes_tikets t
																INNER JOIN clientes ON cli_id = t.tik_cliente
																INNER JOIN usuarios u ON u.usr_id = t.tik_usuario_responsable
																WHERE t.tik_cliente = '" . $clienteId . "'
															");
															$no = 1;
															while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
																$numSeg = intval($res['num_seg']);

																switch($res['tik_tipo_tiket']){
																	case 1: $tipoS = 'Comercial'; $etiquetaT='success'; break;
																	case 2: $tipoS = 'Servicio técnico'; $etiquetaT='info'; break;
																	case 3: $tipoS = 'Soporte operativo'; $etiquetaT='important'; break;
																}

																switch($res['tik_estado']){
																	case 1: $estado = 'Abierto'; $etiquetaE='important'; break;
																	case 2: $estado = 'Cerrado'; $etiquetaE='info'; break;
																}

																switch($res['tik_prioridad']){
																	case 1: $prioridad = 'Normal'; $etiquetaP='success'; break;
																	case 2: $prioridad = 'Urgente'; $etiquetaP='warning'; break;
																	case 3: $prioridad = 'Muy Urgente'; $etiquetaP='important'; break;
																}
															?>
															<tr>
																<td><?=$no;?></td>
																<td><span class="badge badge-<?=$etiquetaT;?>"><?=$tipoS;?></span></td>
																<td><?=$res['tik_fecha_creacion'];?></td>
																<td><?=$res['tik_asunto_principal'];?></td>
																<td><?=$res['usr_nombre'];?></td>
																<td><span class="label label-<?=$etiquetaE;?>"><?=$estado;?></span></td>
																<td><span class="label label-<?=$etiquetaP;?>"><?=$prioridad;?></span></td>
																<td>
																	<?php if (Modulos::validarRol([12], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																		<a href="#" class="js-abrir-ticket-seguimientos-drawer" data-ticket-id="<?=$res[0];?>" data-cliente-id="<?=$clienteId;?>" data-toggle="tooltip" title="Ver seguimientos del ticket"><span class="label label-info"><?=$numSeg;?></span></a>
																	<?php } ?>
																</td>
																<td><h4>
																<?php if (Modulos::validarRol([90], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																	<a href="#" class="js-abrir-ticket-drawer" data-ticket-id="<?=$res[0];?>" data-cliente-id="<?=$clienteId;?>" data-toggle="tooltip" title="Ver ticket"><i class="icon-edit"></i></a>
																<?php } ?>
																	<!--<a href="sql.php?id=<?=$res[0];?>&get=24" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>-->
																</h4></td>
															</tr>
															<?php $no++;}?>
															</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										
										<div class="tab-pane" id="seguimientos">
										<div class="row-fluid">
												<div class="span12">
													<div class="content-widgets light-gray">
														<div class="widget-head green">
															<h3>Seguimientos</h3>
														</div>
														<div class="widget-container">
															<p>
															<?php if (Modulos::validarRol([13], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
															<a href="clientes-seguimiento-agregar.php?cte=<?=$clienteId;?>" class="btn btn-danger" target="_blank"><i class="icon-plus"></i> Agregar seguimiento</a>
															<?php } ?>	
															</p>
															<div class="accordion" id="accordion2">
															
															<?php
															$consulta = $conexionBdPrincipal->query("
																SELECT cs.*, c.cli_zona,
																	u.usr_nombre,
																	enc.usr_nombre AS encargado_nombre,
																	cont.cont_nombre, cont.cont_telefono, cont.cont_email
																FROM cliente_seguimiento cs
																INNER JOIN clientes c ON c.cli_id = cs.cseg_cliente
																INNER JOIN usuarios u ON u.usr_id = cs.cseg_usuario_responsable
																LEFT JOIN usuarios enc ON enc.usr_id = cs.cseg_usuario_encargado AND enc.usr_id_empresa = '" . $idEmpresa . "'
																LEFT JOIN contactos cont ON cont.cont_id = cs.cseg_contacto
																WHERE cs.cseg_cliente = '" . $clienteId . "'
															");
															$no = 1;
															while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
																if ($zonasUsuarioPermitidas !== null) {
																	if (!in_array(intval($res['cli_zona']), $zonasUsuarioPermitidas, true)) {
																		continue;
																	}
																}

																$fondoColor = '';
																if(isset($_GET['seg'])){ 
																	if($res['cseg_id']==$_GET['seg']){$fondoColor = 'style="background:#99DFC6; font-weight:bold;"'; }
																}
																
																

																switch($res['cseg_realizado']){
																	case 1: 
																		$html = '<span class="label label-success">Completado</span>';
																		$colorFondoSeguimiento = 'cornflowerblue'; 
																	break;

																	default: 
																		$html = '<a href="bd_update/cliente-seguimiento-estado-update.php?id='.$res['cseg_id'].'&get=28" class="label label-important">Pendiente</a>';
																		$colorFondoSeguimiento = 'crimson';
																	break;
																}
															?>

															
						<div class="accordion-group">
							<div class="accordion-heading">
								<a href="#collapse<?=$res[0];?>" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle" style="
    background: <?=$colorFondoSeguimiento;?>;"><?=$res['cseg_fecha_reporte'];?> - <?=$res['usr_nombre'];?></a>
							</div>
							<div class="accordion-body collapse" id="collapse<?=$res[0];?>">
								<div class="accordion-inner">
									<?php
									if (!empty($res['cont_nombre'])) {
										echo "<b>Nombre</b>:" . $res['cont_nombre'];
									}

									if (!empty($res['cont_telefono'])) echo "<br><b>Tel:</b> " . $res['cont_telefono'];
									if (!empty($res['cont_email'])) echo "<br><b>Email:</b> " . $res['cont_email'];
									
									echo $res['cseg_observacion'];
									?>

									<p>
										<h5 style="font-weight:bold;">Próximo contacto</h5>
										<b>Fecha:</b> <?=$res['cseg_fecha_proximo_contacto'];?><br>
										<b>Encargado:</b> <?= htmlspecialchars($res['encargado_nombre'] ?? '—') ?>
									</p>

									<h4 style="margin-top:10px;">
																			<?php if (Modulos::validarRol([14], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																			<a href="#" class="js-abrir-seguimiento-drawer" data-seguimiento-id="<?=$res[0];?>" data-cliente-id="<?=$clienteId;?>" data-toggle="tooltip" title="Ver seguimiento"><i class="icon-edit"></i></a>&nbsp;
																			<?php } ?>
																			<?php if (Modulos::validarRol([382], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && false) {?>
																			<a href="sql.php?id=<?=$res[0];?>&get=4" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
																			<?php // codigo 4 no se encontro en el archivo sql.php ?>
																			<?php } ?>
																	</h4>

																	<?=$html;?>
								</div>
							</div>
						</div>

					

																
															
															<?php $no++;}?>
															</div>
														
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="tab-pane" id="notas-internas">
											<?php include("includes/cliente-notas-internas-tab.php"); ?>
										</div>
					
										<div class="tab-pane" id="cotizacion">
											<div class="row-fluid">
												<div class="span12">
													<div class="content-widgets light-gray">
														<div class="widget-head green">
															<h3>Cotización</h3>
														</div>
														<div class="widget-container">
															<p>
															<?php if (Modulos::validarRol([78], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
															<a href="cotizaciones-agregar.php?cte=<?=$clienteId;?>" class="btn btn-danger" target="_blank"><i class="icon-plus"></i> Agregar cotización</a>
															<?php } ?>
															</p>
															<table class="table table-striped table-bordered" id="data-table">
															<thead>
															<tr>
																<th>ID</th>
																<th>TIPO</th>
																<th>Fecha Propuesta</th>
																<th>Productos</th>
																<th>Responsable</th>
																<th>Vendedor</th>
																<th></th>
															</tr>
															</thead>
															<tbody>
															<?php
															$consulta = $conexionBdPrincipal->query("
																SELECT cot.*,
																	creador.usr_nombre AS creador_nombre,
																	vendedor.usr_nombre AS vendedor_nombre
																FROM cotizacion cot
																INNER JOIN clientes ON cli_id = cot.cotiz_cliente AND cli_id = '" . $clienteId . "'
																INNER JOIN usuarios creador ON creador.usr_id = cot.cotiz_creador
																LEFT JOIN usuarios vendedor ON vendedor.usr_id = cot.cotiz_vendedor AND vendedor.usr_id_empresa = '" . $idEmpresa . "'
																WHERE cot.cotiz_id_empresa = '" . $idEmpresa . "'
															");

															$filasCotiz = [];
															$idsCotiz = [];
															while ($filaCotiz = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
																$filasCotiz[] = $filaCotiz;
																$idsCotiz[] = intval($filaCotiz['cotiz_id']);
															}

															$productosPorCotiz = [];
															$combosPorCotiz = [];
															$pedidosPorCotiz = [];
															if (!empty($idsCotiz)) {
																$idsSql = implode(',', $idsCotiz);

																$qProductos = $conexionBdPrincipal->query("
																	SELECT cp.czpp_cotizacion, p.prod_nombre
																	FROM cotizacion_productos cp
																	INNER JOIN productos p ON p.prod_id = cp.czpp_producto
																	WHERE cp.czpp_cotizacion IN (" . $idsSql . ")
																");
																while ($prod = mysqli_fetch_array($qProductos, MYSQLI_ASSOC)) {
																	$productosPorCotiz[intval($prod['czpp_cotizacion'])][] = $prod['prod_nombre'];
																}

																$qCombos = $conexionBdPrincipal->query("
																	SELECT cp.czpp_cotizacion, cb.combo_nombre
																	FROM cotizacion_productos cp
																	INNER JOIN combos cb ON cb.combo_id = cp.czpp_combo
																	WHERE cp.czpp_cotizacion IN (" . $idsSql . ")
																	  AND cp.czpp_tipo = " . CZPP_TIPO_COTZ . "
																");
																while ($comb = mysqli_fetch_array($qCombos, MYSQLI_ASSOC)) {
																	$combosPorCotiz[intval($comb['czpp_cotizacion'])][] = $comb['combo_nombre'];
																}

																$qPedidos = $conexionBdPrincipal->query("
																	SELECT pedid_cotizacion, pedid_id
																	FROM pedidos
																	WHERE pedid_cotizacion IN (" . $idsSql . ")
																	  AND pedid_id_empresa = '" . $idEmpresa . "'
																");
																while ($ped = mysqli_fetch_array($qPedidos, MYSQLI_ASSOC)) {
																	$pedidosPorCotiz[intval($ped['pedid_cotizacion'])] = intval($ped['pedid_id']);
																}
															}

															$no = 1;
															foreach ($filasCotiz as $res) {
																$cotizId = intval($res['cotiz_id']);
																$yaGeneroPedido = !empty($pedidosPorCotiz[$cotizId]);

																$fondoCotiz = '';
																if ($res['cotiz_vendida'] == 1) {
																	$fondoCotiz = 'aquamarine';
																}

																$tipoCotizacion = 'COTIZACIÓN';
																if ($res['cotiz_es_precotizacion'] == 1) {
																	$tipoCotizacion = '<span style="background-color:yellow;">PRE-COTIZACIÓN</span>';
																}
															?>
															<tr>
																<td style="background-color: <?=$fondoCotiz;?>;"><?=$res['cotiz_id'];?></td>
																<td><?= $tipoCotizacion; ?></td>
																<td><?=$res['cotiz_fecha_propuesta'];?></td>
																<td>
																	<?php
																	$i = 1;
																	foreach ($productosPorCotiz[$cotizId] ?? [] as $nombreProd) {
																		echo '<b>' . $i . '.</b> ' . htmlspecialchars($nombreProd) . ', ';
																		$i++;
																	}
																	$i = 1;
																	foreach ($combosPorCotiz[$cotizId] ?? [] as $nombreCombo) {
																		if ($i === 1) {
																			echo '<br><b>Combos:</b><br>';
																		}
																		echo '<b>' . $i . '.</b> ' . htmlspecialchars($nombreCombo) . ', ';
																		$i++;
																	}
																	?>
																</td>
																<td><?php if (!empty($res['creador_nombre'])) echo strtoupper($res['creador_nombre']); ?></td>
																<td><?php if (!empty($res['vendedor_nombre'])) echo strtoupper($res['vendedor_nombre']); ?></td>
																<td>
																	<div class="btn-group">
																		<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span>
																		</button>
																		<ul class="dropdown-menu">
																			<?php if($_SESSION["id"]==$res['cotiz_creador'] or $_SESSION["id"]==$res['cotiz_vendedor'] or $datosUsuarioActual[3]==1){?>
																			<?php if (Modulos::validarRol([79], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																			<li><a href="cotizaciones-editar.php?id=<?=$res['cotiz_id'];?>#productos"> Editar</a></li>
																			<?php } ?>

																			<!--<li><a href="sql.php?id=<?=$res['cotiz_id'];?>&get=22" onClick="if(!confirm('Desea eliminar el registro?')){return false;}">Eliminar</a></li>-->

																			<?php } //el codigo 22 no se encontro en el archivo sql?>
																			<?php if (Modulos::validarRol([50], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																			<li><a href="reportes/formato-cotizacion-1_pdf.php?id=<?=$res['cotiz_id'];?>" target="_blank">Imprimir (Formato 1)</a></li>
																			<li><a href="reportes/formato-cotizacion-3_pdf.php?id=<?=$res['cotiz_id'];?>" target="_blank">Imprimir (Formato 2)</a></li>
																			<?php } ?>
																			
																			<?php if (Modulos::validarRol([380], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																			<li><a href="sql.php?get=46&id=<?=$res['cotiz_id'];?>" onClick="if(!confirm('Desea replicar este registro?')){return false;}">Replicar</a></li>
																			<?php } ?>		
																			<?php //el codigo 46 no se encontro en el archivo sql ?> 
																			<?php if (
																				!$yaGeneroPedido &&
																				Modulos::validarRol([381], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) &&
																				!empty($res['cotiz_ticket']) &&
																				$res['cotiz_es_precotizacion'] != 1
																				) {?>
																					<li><a href="bd_create/cotizaciones-generar-pedido.php?id=<?= $res['cotiz_id']; ?>" onClick="if(!confirm('Desea generar pedido de esta cotización?')){return false;}">Generar pedido</a></li>
																			<?php } ?>		
																			<?php //el codigo 48 no se encontro en el archivo sql ?> 
																		</ul>
																	</div>
																</td>
															</tr>
															<?php $no++; }
															?>
															</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="tab-pane" id="facturas">
										<div class="row-fluid">
												<div class="span12">
													<div class="content-widgets light-gray">
														<div class="widget-head green">
															<h3>Facturación</h3>
														</div>
														<div class="widget-container">
															<p>
															<?php if (Modulos::validarRol([260], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
															<a href="facturacion-agregar.php?cte=<?=$clienteId;?>" class="btn btn-danger" target="_blank"><i class="icon-plus"></i> Agregar factura</a>
															<?php } ?>
															</p>
															<table class="table table-striped table-bordered" id="data-table">
															<thead>
															<tr>
																<th>No</th>
																<th>Tipo</th>
																<th>#Factura</th>
																<th>Fecha</th>
																<th>Vence</th>
																<th>Detalles</th>
																<th></th>
															</tr>
															</thead>
															<tbody>
															<?php
															$consulta = $conexionBdPrincipal->query("
																SELECT f.*, c.ciu_nombre, d.dep_nombre,
																	COALESCE((
																		SELECT SUM(fpab_valor)
																		FROM facturacion_abonos
																		WHERE fpab_factura = f.fact_id
																	), 0) AS total_abonos
																FROM facturacion f
																INNER JOIN clientes ON cli_id = f.fact_cliente
																INNER JOIN " . BDADMIN . ".localidad_ciudades c ON c.ciu_id = cli_ciudad
																INNER JOIN " . BDADMIN . ".localidad_departamentos d ON d.dep_id = c.ciu_departamento
																WHERE f.fact_cliente = '" . $clienteId . "'
																  AND f.fact_id_empresa = '" . $idEmpresa . "'
															");
															$no = 1;
															while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
								
								
																$impuestos = $res['fact_valor'] * $res['fact_impuestos']/100;
																$retencion = $res['fact_valor'] * $res['fact_retencion']/100;
																$descuento = $res['fact_valor'] * $res['fact_descuento']/100;

																$valorReal = ($res['fact_valor'] + $impuestos) - ($retencion + $descuento);

																$saldoFinal = $valorReal - floatval($res['total_abonos']);

																switch($res['fact_estado']){
																	case 1: $estadoF = 'Pagada'; $etiquetaF='success'; break;
																	case 2: $estadoF = 'Por pagar'; $etiquetaF='warning'; break;
																	case 3: $estadoF = 'Anulada'; $etiquetaF='important'; break;
																}
																switch($res['fact_tipo']){
																	case 1: $tipoF = 'Ingreso'; $etiquetaT='success'; break;
																	case 2: $tipoF = 'Egreso'; $etiquetaT='important'; break;
																}
															?>
															<tr>
																<td><?=$no;?></td>
																 <td><span class="label label-<?=$etiquetaT;?>"><?=$tipoF;?></span></td>
																<td>
																	<b>Sistema:</b> <?=$res['fact_id'];?><br>
																	<b>Física:</b> <?=$res['fact_numero_fisica'];?>
																</td>
																<td><?=$res['fact_fecha_real'];?></td>
																<td><?=$res['fact_fecha_vencimiento'];?></td>
																<td>
																	<p><b>Descripcion:</b> <?=$res['fact_descripcion'];?></p>
																	<b>Impuestos:</b> $<?=number_format($impuestos,0,",",".")." (".$res['fact_impuestos']."%)";?><br>
																	<b>Retención:</b> $<?=number_format($retencion,0,",",".")." (".$res['fact_retencion']."%)";?><br>
																	<b>Descuento:</b> $<?=number_format($descuento,0,",",".")." (".$res['fact_descuento']."%)";?><br>
																</td>
																<td><h4 style="margin-top:10px;">
																<?php if (Modulos::validarRol([261], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
                                	<a href="facturacion-editar.php?id=<?=$res['fact_id'];?>" data-toggle="tooltip" title="Editar" target="_blank"><i class="icon-edit"></i></a>&nbsp;
																<?php } ?>
                                    <?php //el codigo 6 no se encontro en el archivo sql ?> 
									<!--<a href="sql.php?id=<?=$res['fact_id'];?>&get=6" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>&nbsp;-->
																<?php if (Modulos::validarRol([92], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
                                    <a href="#" onClick='window.open("facturacion-abonos.php?fact=<?=$res['fact_id'];?>","abonos","width=1200,height=800,menubar=no")' data-toggle="tooltip" title="Abonos"><i class="icon-money"></i></a>&nbsp;
																<?php } ?>
																<?php if (Modulos::validarRol([367], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
                                    <a href="reportes/formato-factura-1.php?id=<?=$res['fact_id'];?>" data-toggle="tooltip" title="Imprimir factura" target="_blank"><i class="icon-print"></i></a>&nbsp;
																<?php } ?>
																<?php if (Modulos::validarRol([311], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
                                    <a href="bd_create/replicar-factura-guardar.php?get=19&id=<?=$res['fact_id'];?>" data-toggle="tooltip" onClick="if(!confirm('Desea replicar este registro?')){return false;}" title="Replicar factura"><i class="icon-repeat"></i></a>&nbsp;
																<?php } ?>
                                    <?php if($saldoFinal>0 && Modulos::validarRol([299], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
                                    <a href="bd_create/abono-automatico-agregar.php?get=26&id=<?=$res['fact_id'];?>" data-toggle="tooltip" onClick="if(!confirm('Desea generar un abono automático por el saldo pendiente de esta factura?')){return false;}" title="Abono automático y saldar factura"><i class="icon-retweet"></i></a>
                                    <?php }?>
                                </h4></td>
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
					</div>
				</div>

			</div>
            

		</div>
	</div>
	<?php include("includes/drawer-sucursal-cliente.php"); ?>
	<?php include("includes/drawer-contacto-cliente.php"); ?>
	<?php include("includes/drawer-seguimiento-cliente.php"); ?>
	<?php include("includes/drawer-ticket-seguimientos-cliente.php"); ?>
	<?php include("includes/drawer-ticket-cliente.php"); ?>
	<?php include("includes/pie.php");?>
</div>
</body>
</html>
