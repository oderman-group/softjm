<?php
include("sesion.php");

$idPagina = 79;

include("includes/verificar-paginas.php");
include("includes/head.php");
$consultaCliente=$conexionBdPrincipal->query("SELECT * FROM cotizacion 
INNER JOIN clientes ON cli_id=cotiz_cliente
INNER JOIN contactos ON cont_id=cotiz_contacto
WHERE cotiz_id='".$_GET["id"]."' AND cotiz_id_empresa='".$idEmpresa."'");
$resultadoD = mysqli_fetch_array($consultaCliente, MYSQLI_BOTH);

if(isset($_GET["cte"])){
	if(is_numeric($_GET["cte"])){
		$cliente = $_GET["cte"]; 
	}
}else{
	$cliente = $resultadoD['cotiz_cliente'];
}


require_once RUTA_PROYECTO.'/usuarios/class/Cotizacion.php';
require_once RUTA_PROYECTO.'/usuarios/class/Pedido.php';
require_once RUTA_PROYECTO.'/usuarios/class/Remision.php';
require_once RUTA_PROYECTO.'/usuarios/class/Factura.php';
require_once RUTA_PROYECTO.'/usuarios/class/Combo.php';
require_once RUTA_PROYECTO.'/usuarios/class/Tickets.php';

$ticketAsociado = Ticket::obtenerDatosTikcetPorIdCotizacion($resultadoD['cotiz_id'], $conexionBdPrincipal);
?>

<link href="css/chosen.css" rel="stylesheet">
<link href="../assets-login/plugins/select2/css/select2.css" rel="stylesheet" />
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
<script src="../assets-login/plugins/select2/js/select2.js"></script>
<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>
<?php include("includes/texto-editor.php");?>


<?php if($resultadoD['cotiz_vendida']!=1){?>

	<script type="text/javascript">
		function productos(enviada){
			var tipoCliente   = enviada.alt;
			var campo         = enviada.title;
			var producto      = enviada.name;
			var proceso       = 2;
			var valor         = enviada.value;
			var valorAnterior = enviada.getAttribute('data-valor-actual');
			
			
			$('#resp').empty().hide().html("Esperando...").show(1);
			datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo)+"&tipoCliente="+(tipoCliente);
			$.ajax({
				type: "POST",
				url: "ajax/ajax-productos.php",
				data: datos,
				success: function(data) {
					var response = JSON.parse(data);
					if(response.success) {
						$('#resp').empty().hide().html(response.message).show(1);
					} else {
						$('#resp').empty().hide().html('').show(1);
						alert(response.message);
						enviada.value = valorAnterior;
					}

				}
			});
		}


		function combos(enviada){
			var campo = enviada.title;
			var producto = enviada.name;
			var proceso = 11;
			var valor = enviada.value;
			$('#resp').empty().hide().html("Esperando...").show(1);
				datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo);
					$.ajax({
						type: "POST",
						url: "ajax/ajax-productos.php",
						data: datos,
						success: function(data){
						$('#resp').empty().hide().html(data).show(1);
						}
					});
		}	

		function servicios(enviada){
			var campo = enviada.title;
			var producto = enviada.name;
			var proceso = 12;
			var valor = enviada.value;
			$('#resp').empty().hide().html("Esperando...").show(1);
				datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo);
					$.ajax({
						type: "POST",
						url: "ajax/ajax-productos.php",
						data: datos,
						success: function(data){
						$('#resp').empty().hide().html(data).show(1);
						}
					});
		}
	</script>
<?php }?>
<?php
		require '../usuarios/class/CotizacionesEditar.php';

		if (!empty($_POST['action']) && $_POST['action'] === 'generarTablaProductos') {
			$htmlTablaProductos = CotizacionesEditar::generarTablaProductos($conexionBdPrincipal, $resultadoD,$simbolosMonedas, $idEmpresa);
			echo $htmlTablaProductos;
			exit; 
		}

		if (!empty($_POST['action']) && $_POST['action'] === 'generarTablacombos') {
			$htmlTablaCombos = CotizacionesEditar::generarTablacombos($conexionBdPrincipal, $resultadoD,$simbolosMonedas, $idEmpresa);
			echo $htmlTablaCombos;
			exit; 
		}

		if (!empty($_POST['action']) && $_POST['action'] === 'generarTablaServicios') {
			$htmlTablaServicios = CotizacionesEditar::generarTablaServicios($conexionBdPrincipal, $resultadoD,$simbolosMonedas, $idEmpresa);
			echo $htmlTablaServicios;
			exit; 
		}
?>
</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>                        
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="cotizaciones.php">Cotizaciones</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            
            <?php include("includes/notificaciones.php");?>

			<p>
				<?php if (Modulos::validarRol([78], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
				<a href="cotizaciones-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
				<?php } ?>
				<?php if (Modulos::validarRol([50], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
				<a href="reportes/formato-cotizacion-1_pdf.php?id=<?=$_GET["id"];?>" class="btn btn-success" target="_blank"><i class="icon-print"></i> Imprimir</a>
				<?php } ?>
				
				<?php
				if(
					$resultadoD['cotiz_vendida'] != 1 && 
					Modulos::validarRol([263], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) &&
					!empty($ticketAsociado) && 
					$resultadoD['cotiz_es_precotizacion'] != 1
				) {
				?>
					<a href="bd_create/cotizaciones-generar-pedido.php?id=<?= $resultadoD['cotiz_id']; ?>" class="btn btn-info" onClick="if(!confirm('Desea generar pedido de esta cotización?')){return false;}"><i class="icon-money"></i> Generar pedido</a>
				<?php
				} else if ($resultadoD['cotiz_vendida'] != 1 && empty($ticketAsociado) && $resultadoD['cotiz_es_precotizacion'] != 1) {
				?>
					<div class="alert alert-warning">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<i class="icon-warning-sign"></i><strong>Ticket pendiente!</strong> No es posible generar pedido en una cotización sin ticket comercial asociado.
					</div>
				<?php } else if ($resultadoD['cotiz_es_precotizacion'] == 1) {?>
					<div class="alert alert-warning">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<i class="icon-warning-sign"></i><strong>Precotización!</strong> No es posible generar pedido con una PRE-cotización.
					</div>
				<?php }?>
			</p>
			
			<?php
			$camposCotizacionDisabled = '';

			if ($resultadoD['cotiz_vendida'] == Cotizacion::COTIZACION_VENDIDA) {
				$camposCotizacionDisabled = 'disabled';

				//Pedido
				$predicado = [
					'pedid_cotizacion' => $resultadoD['cotiz_id'],
					'pedid_id_empresa' => $idEmpresa
				];

				$pedidoAsociado = Pedido::Select($predicado);
				$pedidoAsociadoDatos = mysqli_fetch_array($pedidoAsociado, MYSQLI_BOTH);

				//Remisión
				$predicado = [
					'remi_pedido' => $pedidoAsociadoDatos[Pedido::$primaryKey],
					'remi_id_empresa' => $idEmpresa
				];

				$remisionAsociada = Remision::Select($predicado);
				$remisionAsociadaDatos = mysqli_fetch_array($remisionAsociada, MYSQLI_BOTH);

				$linkRemision = '#';
				$breadCrumbRemision = 'Remisión Pendiente';

				if (!empty($remisionAsociadaDatos[Remision::$primaryKey])) {
					$linkRemision = 'remisionbdg.php?busqueda='.$remisionAsociadaDatos[Remision::$primaryKey];
					$breadCrumbRemision = 'Remisión Nro. '.$remisionAsociadaDatos[Remision::$primaryKey];
				}

				//Factura
				$predicado = [
					'factura_remision' => $remisionAsociadaDatos[Remision::$primaryKey],
					'factura_id_empresa' => $idEmpresa
				];

				$facturaAsociada = Factura::Select($predicado);
				$facturaAsociadaDatos = mysqli_fetch_array($facturaAsociada, MYSQLI_BOTH);

				$linkFactura = '#';
				$breadCrumbFactura = 'Factura Pendiente';

				if (!empty($facturaAsociadaDatos[Factura::$primaryKey])) {
					$linkFactura = 'remisionbdg.php?busqueda='.$facturaAsociadaDatos[Factura::$primaryKey];
					$breadCrumbFactura = 'Factura Nro. '.$facturaAsociadaDatos[Factura::$primaryKey];
				}
			?>
				<p style="color: black; background-color: aquamarine; padding: 10px; font-weight: bold;">
					Esta cotización ya generó pedido en la siguiente fecha: <?=$resultadoD['cotiz_fecha_vendida'];?>. 
				</p>

				<ul class="breadcrumb" style="background: antiquewhite;">
					<?php if (!empty($ticketAsociado)) {?>
						<li><a href="clientes-tikets-editar.php?id=<?=$ticketAsociado['tik_id'];?>">Ticket Nro. <?=$ticketAsociado['tik_id'];?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<?php }?>
					<li><a href="#">Cotización Nro. <?=$resultadoD['cotiz_id'];?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<li><a href="pedidos.php?busqueda=<?=$pedidoAsociadoDatos[Pedido::$primaryKey];?>" target="_blank">Pedido Nro.<?=$pedidoAsociadoDatos[Pedido::$primaryKey];?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<li><a href="<?=$linkRemision;?>" target="_blank"><?=$breadCrumbRemision;?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<li><a href="<?=$linkFactura;?>" target="_blank"><?=$breadCrumbFactura;?></a><span class="divider"></li>
				</ul>

				<p style="color: black; background-color: gold; padding: 10px; font-weight: bold;"> No es posible hacer más cambios en esta cotización.</p>
			<?php
			} else if(!empty($ticketAsociado)) {
			?>
				<ul class="breadcrumb" style="background: antiquewhite;">
					<li><a href="clientes-tikets-editar.php?id=<?=$ticketAsociado['tik_id'];?>">Ticket Nro. <?=$ticketAsociado['tik_id'];?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
					<li><a href="#">Cotización Nro. <?=$resultadoD['cotiz_id'];?></a><span class="divider"></span></li>
				</ul>
			<?php
			}

			$versionActualCotizacion = Cotizacion::obtenerVersionCotizacion($resultadoD['cotiz_version']);
			?>

			<ul class="nav nav-tabs" id="myTab1">
				<li class="active"><a href="#cotizacion"><i class="icon-file-alt"></i> Información de la cotización</a></li>
				<li><a href="#itemsCotizados"><i class="icon-list"></i> Items cotizados</a></li>
				<li><a href="#enviarCotizacion"><i class="icon-envelope"></i> Enviar cotización por correo</a></li>
				<li><a href="#cotizacionesAsociadas"><i class="icon-retweet"></i> Cotizaciones asociadas</a></li>
				<li><a href="#seguimientos"><i class="icon-list-ol"></i> Seguimientos</a></li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="cotizacion">
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head bondi-blue">
									<h3> <?=$paginaActual['pag_nombre'];?> #<?=$resultadoD['cotiz_id'];?> <?=$versionActualCotizacion;?> <?php if (!empty($resultadoD['cotiz_ultima_modificacion'])) echo " - Ultima modificación: " . $resultadoD['cotiz_ultima_modificacion'];?></h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="post" action="bd_update/cotizaciones-actualizar.php">
									<input type="hidden" name="id" id="id" value="<?=$_GET["id"];?>">
									<input type="hidden" name="monedaActual" value="<?=$resultadoD['cotiz_moneda'];?>">
										
									<script type="application/javascript">
											function clientes(datos){
												id = datos.value;
												idCotizacion = <?=$_GET["id"];?>;
												datos = "idCotizacion="+(idCotizacion)+"&idCliente="+(id);

												$.ajax({
													type: "POST",
													url: "ajax/ajax-cotizaciones-actualizar.php",
													data: datos,
													success: function(data) {
														var response = JSON.parse(data);
														if(response.success) {
															location.href = "cotizaciones-editar.php?id="+idCotizacion+"&cte="+id+"#productos";
														} else {
															alert(response.message);
														}

													}
												});
												
											}
										</script>
										
										<div class="form-actions">
											<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
											<?php
											if($resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA){
											?>
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
											<?php }?>
										</div>
										

										<?php if($configuracion['conf_proveedor_cotizacion'] == 1){?>
										
										<div class="control-group">
											<label class="control-label">Escoja un proveedor</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="proveedor" onChange="provee(this)" required>
													<option value=""></option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT prov_id, prov_nombre FROM proveedores WHERE prov_id_empresa='".$idEmpresa."'");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>" <?php if($resultadoD['cotiz_proveedor']==$resOp[0]) echo "selected";?>><?=$resOp['prov_nombre'];?></option>
													<?php
													}
													?>
												</select>
											</div>
											
											
													<?php if (Modulos::validarRol([125], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
													<a href="proveedores-editar.php?id=<?=$resultadoD['cotiz_proveedor'];?>" class="btn btn-info" target="_blank">Editar proveedor</a>
													<?php } ?>
											
											
										</div>
		
										<?php }?>

										
									<fieldset class="default">
										<legend>Datos del cliente</legend>	
										
									<div class="control-group">
											<label class="control-label">Cliente</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente" required onChange="clientes(this)" <?=$camposCotizacionDisabled;?>>
													<option value=""></option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT cli_id, cli_nombre, cli_categoria, 
														CASE 
															WHEN cli_categoria = '".CLI_CATEGORIA_DEALER."' THEN '(DEALER)'
															ELSE ''
														END AS 'categoria'	
														FROM clientes 
														WHERE cli_id_empresa='".$idEmpresa."'
														ORDER BY cli_categoria, cli_nombre
														");

														$categoriaActual = 1;
														$nombreCategoria = ['','Prospectos', 'Clientes', 'Dealer'];
														echo '<optgroup label="'.$nombreCategoria[1].'">';

													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){

														if ($categoriaActual != $resOp['cli_categoria']) {
															echo '</optgroup>';
															echo '<optgroup label="'.$nombreCategoria[$resOp['cli_categoria']].'">';
															$categoriaActual = $resOp['cli_categoria'];
														}

														$disabled = '';
														
														if(!Modulos::validarRol([393], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) and $resOp['cli_categoria']== CLI_CATEGORIA_DEALER) {
															$disabled = 'disabled';
														}	
														

													?>
														<option value="<?=$resOp['cli_id'];?>" <?php if($cliente==$resOp['cli_id']){echo "selected";}  echo $disabled; ?>><?=$resOp['cli_nombre']." ".$resOp['categoria'];?></option>
													<?php
													}
													?>
												</select>
											</div>
											<?php if (Modulos::validarRol([11], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && $resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA) {?>
													<a href="clientes-editar.php?id=<?=$cliente;?>" class="btn btn-info" target="_blank">Editar cliente</a>
											<?php } ?>
									</div>
									
									<div class="control-group">
											<label class="control-label">Sucursal</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="sucursal" required <?=$camposCotizacionDisabled;?>>
													<option value=""></option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT sucu_id, sucu_nombre FROM sucursales WHERE sucu_cliente_principal='".$cliente."'");
													$numOp = $conOp->num_rows;
													if($numOp==0){
														//Crear automáticamente la sucursal
														$conexionBdPrincipal->query("INSERT INTO sucursales(sucu_cliente_principal, sucu_ciudad, sucu_direccion, sucu_telefono, sucu_celular, sucu_nombre)VALUES('".$cliente."', '".$clienteInfo['cli_ciudad']."', '".$clienteInfo['cli_direccion']."', '".$clienteInfo['cli_telefono']."', '".$clienteInfo['cli_celular']."','Sede principal (Automática)')");
														
														echo '<script type="text/javascript">window.location.href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'";</script>';
														exit();
													}

													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
														
													?>
														<option value="<?=$resOp[0];?>" <?php if($resultadoD['cotiz_sucursal']==$resOp[0] || $numOp == 1){echo "selected";} echo $disabled; ?>><?=$resOp['sucu_nombre'];?></option>
													<?php 
													}
													?>
												</select>
											</div>
											<?php if (Modulos::validarRol([83], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
										<a href="clientes-sucursales.php?cte=<?=$cliente;?>" class="btn btn-info" target="_blank">Ver sucursales</a>
											<?php } ?>
									</div>
										
										<div class="control-group">
											<label class="control-label">Contacto</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="contacto" required <?=$camposCotizacionDisabled;?>>
													<option value=""></option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT cont_id, cont_nombre, cont_email FROM contactos 
													WHERE cont_cliente_principal='".$cliente."'");
													$numOp = $conOp->num_rows;
													if($numOp==0){
														//Crear automáticamente el contacto
														$conexionBdPrincipal->query("INSERT INTO contactos(cont_nombre, cont_cliente_principal)VALUES('Contacto principal (Automático)', '".$cliente."')");
														
														echo '<script type="text/javascript">window.location.href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'";</script>';
														exit();
													}
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp[0];?>" <?php if($resultadoD['cotiz_contacto']==$resOp[0] || $numOp == 1){echo "selected";}?>><?=strtoupper($resOp['cont_nombre'])." (".$resOp['cont_email'].")";?></option>
													<?php
													}
													?>
												</select>
											</div>
											<?php if (Modulos::validarRol([44], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
											<a href="clientes-contactos.php?cte=<?=$cliente;?>" class="btn btn-info" target="_blank">Ver contactos</a>
											<?php } ?>
									</div>	
									
									</fieldset>
										
										<div class="control-group">
											<label class="control-label">Usuario Influyente</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="influyente" <?=$camposCotizacionDisabled;?>>
													<option value=""></option>
													<?php
													$conOp = $conexionBdPrincipal->query("SELECT usr_id, usr_nombre, usr_email FROM usuarios WHERE usr_bloqueado!=1 AND usr_id_empresa='".$idEmpresa."' ORDER BY usr_nombre");
													while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													?>
														<option value="<?=$resOp['usr_id'];?>" <?php if($resultadoD['cotiz_vendedor']==$resOp['usr_id']){echo "selected";}?>><?=strtoupper($resOp['usr_nombre'])." (".$resOp['usr_email'].")";?></option>
													<?php
													}
													?>
												</select>
											</div>
									</div>
									
									<div class="control-group">
											<label class="control-label">Fecha de la propuesta</label>
											<div class="controls">
												<input type="date" class="span4" name="fechaPropuesta" required value="<?=$resultadoD['cotiz_fecha_propuesta'];?>" <?=$camposCotizacionDisabled;?>>
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label">Fecha de vencimiento</label>
											<div class="controls">
												<input type="date" class="span4" name="fechaVencimiento" required value="<?=$resultadoD['cotiz_fecha_vencimiento'];?>" <?=$camposCotizacionDisabled;?>>
											</div>
										</div>
										
										<?php
										if (!Cotizacion::esCotizacionVendida($resultadoD['cotiz_id'], $idEmpresa)) {
											if(isset($clienteInfo['cli_credito'])){
												if($clienteInfo['cli_credito']==1){
													$msjCredito = "Este cliente tiene crédito con la compañía.";
													$colorCredito = 'aquamarine';
												}
											}else{
												$msjCredito = "Este cliente aún NO tiene crédito con la compañía.";
												$colorCredito = 'gold';
											}
											?>
											<p style="color: black; background-color: <?=$colorCredito;?>; padding: 10px; font-weight: bold;"><?=$msjCredito;?></p>
										<?php }?>
										
										<div class="control-group">
											<label class="control-label">Forma de pago</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="formaPago" <?=$camposCotizacionDisabled;?>>
													<option value=""></option>
													<option value="1" <?php if($resultadoD['cotiz_forma_pago']==1)echo "selected";?>>Contado</option>
													<option value="2" <?php if($resultadoD['cotiz_forma_pago']==2)echo "selected";?>>Crédito</option>
												</select>
											</div>
									</div>

									<script type="text/javascript">
										function getPay(pay){
											let msg;
											
											if(pay.value == 1){
												msg = 'La cotización cambiará a pesos colombianos. Es decir que se tomará el valor en dolares y se multiplicará por el TRM de venta actual.';
											}else if(pay.value == 2){
												msg = 'La cotización cambiará a dólares americanos. Es decir que se tomará el valor en pesos y se dividirá entre el TRM de compra actual.';
											}

											alert(msg);
										}
									</script>
										
										<div class="control-group">
											<label class="control-label">Moneda</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="moneda" onChange="getPay(this)" <?=$camposCotizacionDisabled;?>>

													<option value=""></option>
													<option value="1" <?php if($resultadoD['cotiz_moneda']==1)echo "selected";?>>COP</option>
													<option value="2" <?php if($resultadoD['cotiz_moneda']==2)echo "selected";?>>USD</option>
												</select>
											</div>
									</div>	
										
										<div class="control-group">
												<label class="control-label">Combos</label>
												<div class="controls">
													<select data-placeholder="Escoja una opción..." class="span10" tabindex="2" name="combo[]" multiple id="combos-select" <?=$camposCotizacionDisabled;?>>
														<option value=""></option>
														<?php
														$conOp = $conexionBdPrincipal->query("SELECT czpp_cotizacion, czpp_tipo, czpp_combo, combo_id, combo_nombre FROM cotizacion_productos
														INNER JOIN combos ON combo_id=czpp_combo AND combo_id_empresa='".$idEmpresa."'
														WHERE czpp_cotizacion='".$resultadoD['cotiz_id']."' AND czpp_tipo='".CZPP_TIPO_COTZ."'
														ORDER BY combo_nombre");
														while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
														?>
															<option selected value="<?=$resOp['combo_id'];?>"><?=$resOp['combo_nombre'];?></option>
														<?php
														}
														?>
													</select>
												</div>
										</div>
										
										<div class="control-group">
												<label class="control-label">Productos</label>
												<div class="controls">
													<select data-placeholder="Escoja una opción..." class="span10" tabindex="2" name="producto[]" multiple id="product-select" <?=$camposCotizacionDisabled;?>>
													<?php
														$consultaProductos = $conexionBdPrincipal->query("SELECT czpp_id, czpp_valor, czpp_cantidad, czpp_descuento, czpp_impuesto, czpp_orden, czpp_observacion, czpp_descuento_especial, czpp_aprobado_usuario, czpp_aprobado_fecha,prod_descuento2, prod_costo, prod_id, prod_nombre, prod_descripcion_corta, prod_utilidad, czpp_tipo FROM cotizacion_productos
														INNER JOIN productos ON prod_id=czpp_producto AND prod_id_empresa='".$idEmpresa."'
														WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo=".CZPP_TIPO_COTZ."
														ORDER BY prod_nombre");

														while ($resProducto = mysqli_fetch_array($consultaProductos, MYSQLI_BOTH)) {
														?>
															<option selected value="<?= $resProducto['prod_id']; ?>"><?= $resProducto['prod_id'] . ". " . strtoupper($resProducto['prod_nombre']) . " - [HAY " . $resProducto['czpp_cantidad'] . "]"; ?></option>
														<?php
															}
														?>
													</select>
												</div>
											</div>


										<div class="control-group">
											<label class="control-label">Ocultar descuento de combos</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span2" tabindex="2" name="dctoCombos" <?=$camposCotizacionDisabled;?>>
													<option value=""></option>
													<option value="1" <?php if($resultadoD['cotiz_ocultar_descuento_combo']==1)echo "selected";?>>SI</option>
													<option value="0" <?php if($resultadoD['cotiz_ocultar_descuento_combo']=='0')echo "selected";?>>NO</option>
												</select>
											</div>
									</div>

									<?php if (!Cotizacion::esCotizacionVendida($resultadoD['cotiz_id'], $idEmpresa)) {?>
										<p style="color: black; background-color: mediumaquamarine; padding: 10px; font-weight: bold;">Escoja SÍ, si desea solicitar a la Administración, que a esta cotización se le hagan algunos descuentos especiales en los items cotizados.</p>
									<?php }?>

									<div class="control-group">
											<label class="control-label">Requiere un descuento especial?</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span2" tabindex="2" name="dctoEspecial" <?=$camposCotizacionDisabled;?>>
													<option value=""></option>
													<option value="1" <?php if($resultadoD['cotiz_descuentos_especiales']==1)echo "selected";?>>SI</option>
													<option value="0" <?php if($resultadoD['cotiz_descuentos_especiales']=='0')echo "selected";?>>NO</option>
												</select>
											</div>
									</div>
										
										
										
											<div class="control-group">
												<label class="control-label">Observaciones</label>
												<div class="controls">
													<textarea rows="5" cols="80" style="width: 80%" class="tinymce-simple" name="notas" <?=$camposCotizacionDisabled;?>><?=$resultadoD['cotiz_observaciones'];?></textarea>
												</div>
											</div>
										
										<div class="control-group">
											<label class="control-label">Costo Envío</label>
											<div class="controls">
												<input type="text" class="span4" name="envio" value="<?=$resultadoD['cotiz_envio'];?>" <?=$camposCotizacionDisabled;?>>
											</div>
										</div>

										<?php
										$envio = $resultadoD['cotiz_envio'];

										if (empty($resultadoD['cotiz_ticket'])) {
											$consultaTickets = $conexionBdPrincipal->query("SELECT * FROM clientes_tikets 
											WHERE tik_cliente='".$resultadoD['cotiz_cliente']."'
											AND tik_id_cotizacion IS NULL
											AND tik_tipo_tiket = 1
											AND tik_estado = 1
											AND tik_tipo_negocio = 1
											");
											$numTickets = $consultaTickets->num_rows;
										?>

											<div class="control-group">
												<label class="control-label">Asociar a un ticket</label>
												<div class="controls">
													<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="ticket" <?=$camposCotizacionDisabled;?>>
														<option value="TICKET_AUTO">Deseo que el ticket se cree automáticamente</option>
														<option value="NO_TICKET" selected>NO deseo asociar ningun ticket a esta cotización por el momento</option>
														<?php
														if ($numTickets > 0) {
															echo '<optgroup label="Tickets disponibles">';
															while ($resOp = mysqli_fetch_array($consultaTickets, MYSQLI_BOTH)) {
														?>
																<option value="<?=$resOp[0];?>"><?="Ticket # ".$resOp[0]." - ".strtoupper($resOp[1])." (".$resOp[3].")";?></option>
														<?php
														}
															echo '</optgroup>';
															}
														?>
													</select>
												</div>
											</div>
										<?php } else {?>
											<input type="hidden" name="ticket" value="<?=$resultadoD['cotiz_ticket'];?>">
										<?php }?>
										
									<div class="form-actions">
											<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
											<?php
											if($resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA){
											?>
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
											<?php }?>
										</div>
										
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- LISTADO DE LO QUE SE ESTÁ COTIZANDO -->	
				<div class="tab-pane" id="itemsCotizados">
					<div class="row-fluid">
						<div class="span12">
							
							<span id="resp"></span>
							
							<div class="content-widgets light-gray" id="productos">
								<div class="widget-head green">
									<h3>PRODUCTOS</h3>
								</div>
								<div class="widget-container">
									<p></p>
									<table class="table table-striped table-bordered" id="data-table">
									<thead>
									<tr>
										<th>No</th>
										<th>Orden</th>
										<th>Producto/Servicio</th>
										<th>Cant.</th> 
										<th>Valor Base</th>
										<th>IVA</th>
										<th>Dcto.</th>
										<?php 
										$colspan = 7;
										if($resultadoD['cotiz_descuentos_especiales'] == 1){
											$colspan = 8;
										?>
										<th>Dcto. Especial</th>
										<?php }?>

										<th>SUBTOTAL</th>
									</tr>
									</thead>
									<tbody id="tableBody"></tbody>
									<tfoot>
										<tr style="font-weight: bold; font-size: 16px;">
											<td style="text-align: right;" colspan="<?=$colspan;?>">SUBTOTAL</td>
											<td id="subtotal">
											<span class="moneda-simbolo">
												<?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
												<span class="valor-numerico"><?=!empty($subtotal) ? number_format($subtotal,0,",",".") : 0;?>
												</span>
											</td>
										</tr>
										<tr style="font-weight: bold; font-size: 16px;">
											<td style="text-align: right;" colspan="<?=$colspan;?>">DESCUENTO</td>
											<td id="totalDiscount"><span class="moneda-simbolo">
												<?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
												<span class="valor-numerico"><?=!empty($totalDescuento) ? number_format($envio,0,",",".") : 0;?>
												</span></td>
										</tr>
										<tr style="font-weight: bold; font-size: 16px;">
											<td style="text-align: right;" colspan="<?=$colspan;?>">IVA</td>
											<td id="totalIva"><span class="moneda-simbolo">
												<?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
												<span class="valor-numerico"><?=!empty($totalIva) ? number_format($totalIva,0,",",".") : 0;?>
												</span></td>
										</tr>
										<tr style="font-weight: bold; font-size: 16px;">
											<td style="text-align: right;" colspan="<?=$colspan;?>">ENVÍO</td>
											<td><?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?><?php if(!empty($envio)) echo number_format($envio,0,",","."); else echo 0;?>
												</td>
										</tr>
										<tr style="font-weight: bold; font-size: 16px;">
											<td style="text-align: right;" colspan="<?=$colspan;?>">TOTAL NETO</td>
											<td id="total"><span class="moneda-simbolo">
												<?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
												<span class="valor-numerico"><?=!empty($subtotal) ? number_format($subtotal,0,",",".") : 0;?>
												</span></td>
										</tr>
									</tfoot>	
										
									</table>

									<?php
									if(Modulos::validarRol([394], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){?>

										<p style="color: black; background-color: #d8ff0038; padding: 15px; font-weight: bold; font-size: 16px;">Esta cotización deja una utilidad aproximada de $<span id="utilidadTotal">0</span>
									<?php }?>
									
									
										<div class="form-actions">
											
											<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
											<?php
											if($resultadoD['cotiz_vendida'] != Cotizacion::COTIZACION_VENDIDA){
											?>
											<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
											<?php }?>
											
												
											<?php if (Modulos::validarRol([50], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
											<a href="reportes/formato-cotizacion-1_pdf.php?id=<?=$_GET["id"];?>" class="btn btn-success" target="_blank"><i class="icon-print"></i> Imprimir</a>
											<?php } ?>
										</div>
									</form>
									
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="enviarCotizacion">
					<div class="row-fluid">
						<div class="span12">
							<div class="content-widgets gray">
								<div class="widget-head orange">
									<h3> Enviar cotización por correo</h3>
								</div>
								<div class="widget-container">
									<form class="form-horizontal" method="post" action="enviar_correos/cotizaciones-enviar-correo.php">
									<input type="hidden" name="id" value="<?=$_GET["id"];?>">
										<div class="control-group">
											<label class="control-label">Nombre del contacto</label>
											<div class="controls">
												<input type="text" class="span8" name="destinoNombre" readonly value="<?=$resultadoD['cont_nombre'];?>">
											</div>
										</div>	

										<div class="control-group">
											<label class="control-label">Email</label>
											<div class="controls">
												<input type="text" class="span8" name="destinoEmail" readonly value="<?=$resultadoD['cont_email'];?>">
											</div>
										</div>	

										<div class="control-group">
											<label class="control-label">Asunto</label>
											<div class="controls">
												<input type="text" class="span8" name="asunto" required value="COTIZACIÓN #<?=$resultadoD['cotiz_id'];?>">
											</div>
										</div>	

										<div class="control-group">
											<label class="control-label">Mensaje</label>
											<div class="controls">
												<textarea rows="7" cols="80" style="width: 80%" class="tinymce-simple" name="mensaje"><?=strtoupper($resultadoD['cli_nombre']);?><br>
													<?=strtoupper($resultadoD['cont_nombre']);?><br><br>
													<br>
													<?=$configuracion['conf_emsj_cotizacion'];?>
												</textarea>
											</div>
										</div>

										<?php if (!empty($resultadoD['cont_email']) && filter_var($resultadoD['cont_email'], FILTER_VALIDATE_EMAIL)) { ?>
											<div class="form-actions">
												<button type="submit" class="btn btn-info"><i class="icon-envelope"></i> Enviar cotización</button>
											</div>
										<?php } else {?>
											<div class="alert alert-warning">
												<button type="button" class="close" data-dismiss="alert">&times;</button>
												<i class="icon-warning-sign"></i><strong>Email inválido!</strong> Este contacto no tiene email registrado o el que tiene está incorrecto.
											</div>
										<?php }?>
									</form>	
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane" id="cotizacionesAsociadas">
					<?php include("cotizaciones-relacionadas.php");?>
				</div>

				<div class="tab-pane" id="seguimientos">
					<?php
					$consulta = $conexionBdPrincipal->query("SELECT * FROM cliente_seguimiento
					INNER JOIN clientes ON cli_id=cseg_cliente
					INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
					INNER JOIN clientes_tikets ON tik_id=cseg_tiket AND tik_id_cotizacion = ".$resultadoD['cotiz_id']);
					$no = 1;
					?>
					<div class="row-fluid">
						<div class="span12">
							<?php
							while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
								$rutaFoto = "files/fotos/".$res['usr_foto'];

								if (!empty($res['usr_foto']) && file_exists($rutaFoto)) {
									$foto = $rutaFoto;
								} else {
									$rutaFoto = "images/item-pic.png";
								}
							?>
								<div class="media">
									<a href="#" class="pull-left media-thumb">
										<img src="<?=$rutaFoto;?>" width="34" height="34" alt="user">
									</a>
									<div class="media-body ">
										<h4 class="media-heading"><?=$res['cseg_fecha_reporte'];?> - <?=$res['usr_nombre'];?></h4>
										<p><?=$res['cseg_observacion'];?></p>
									</div>
								</div>
							<?php }?>
						</div>
					</div>
				</div>

			</div>


		</div>
	</div>
	<?php include("includes/pie.php");?>
	<script src="js/Cotizaciones.js"></script>
</div>
</body>
</html>
