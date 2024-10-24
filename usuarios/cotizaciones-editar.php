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
			var tipoCliente = enviada.alt;
			var campo       = enviada.title;
			var producto    = enviada.name;
			var proceso     = 2;
			var valor       = enviada.value;
			
			$('#resp').empty().hide().html("Esperando...").show(1);
				datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo)+"&tipoCliente="+(tipoCliente);
					$.ajax({
						type: "POST",
						url: "ajax/ajax-productos.php",
						data: datos,
						success: function(data){
						$('#resp').empty().hide().html(data).show(1);
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
			$htmlTablaProductos = CotizacionesEditar::generarTablaProductos($conexionBdPrincipal, $resultadoD,$simbolosMonedas);
			echo $htmlTablaProductos;
			exit; 
		}

		if (!empty($_POST['action']) && $_POST['action'] === 'generarTablacombos') {
			$htmlTablaCombos = CotizacionesEditar::generarTablacombos($conexionBdPrincipal, $resultadoD,$simbolosMonedas);
			echo $htmlTablaCombos;
			exit; 
		}

		if (!empty($_POST['action']) && $_POST['action'] === 'generarTablaServicios') {
			$htmlTablaServicios = CotizacionesEditar::generarTablaServicios($conexionBdPrincipal, $resultadoD,$simbolosMonedas);
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
								
                               <div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-envelope"></i> Enviar cotización</button>
								</div>
							</form>	
						</div>
					</div>
				</div>
			</div>
			
			<p>
				<?php if (Modulos::validarRol([78], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
				<a href="cotizaciones-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
				<?php } ?>
				<?php if (Modulos::validarRol([50], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
				<a href="reportes/formato-cotizacion-1.php?id=<?=$_GET["id"];?>" class="btn btn-success" target="_blank"><i class="icon-print"></i> Imprimir</a>
				<?php } ?>
				
				<?php
				if($resultadoD['cotiz_vendida']!=1 && Modulos::validarRol([263], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
				?>
					<a href="bd_create/cotizaciones-generar-pedido.php?id=<?= $resultadoD['cotiz_id']; ?>" class="btn btn-info" onClick="if(!confirm('Desea generar pedido de esta cotización?')){return false;}"><i class="icon-money"></i> Generar pedido</a>
				<?php
				}
				?>
			</p>
			
			<?php
			if($resultadoD['cotiz_vendida']==1){
			?>
				<p style="color: black; background-color: aquamarine; padding: 10px; font-weight: bold;">Esta cotización ya generó pedido en la siguiente fecha: <?=$resultadoD['cotiz_fecha_vendida'];?>.</p>
				<p style="color: black; background-color: gold; padding: 10px; font-weight: bold;"> No es posible hacer más cambios en esta cotización.</p>
			<?php
			}
			?>	
								
			
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_update/cotizaciones-actualizar.php">
                            <input type="hidden" name="id" id="id" value="<?=$_GET["id"];?>">
							<input type="hidden" name="monedaActual" value="<?=$resultadoD['cotiz_moneda'];?>">
                            	   
                               <script type="application/javascript">
									function clientes(datos){
										id = datos.value;
										location.href = "cotizaciones-editar.php?id=<?=$_GET["id"];?>&cte="+id+"#productos";
									}
								</script>
								
								<div class="form-actions">
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<?php
									if($resultadoD['cotiz_vendida']!=1){
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
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente" required onChange="clientes(this)">
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT cli_id, cli_nombre, cli_categoria, 
												CASE 
													WHEN cli_categoria = '".CLI_CATEGORIA_DEALER."' THEN '(DEALER)'
													ELSE ''
												END AS 'categoria'	
												FROM clientes WHERE cli_id_empresa='".$idEmpresa."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){

												$disabled = '';
												
												if(!Modulos::validarRol([393], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) and $resOp['cli_categoria']== CLI_CATEGORIA_DEALER){
													$disabled = 'disabled';
												}	
												

											?>
                                            	<option value="<?=$resOp['cli_id'];?>" <?php if($cliente==$resOp['cli_id']){echo "selected";}  echo $disabled; ?>><?=$resOp['cli_nombre']." ".$resOp['categoria'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
									<?php if (Modulos::validarRol([11], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
								   <a href="clientes-editar.php?id=<?=$cliente;?>" class="btn btn-info" target="_blank">Editar cliente</a>
									<?php } ?>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Sucursal</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="sucursal" required>
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
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['cotiz_sucursal']==$resOp[0]){echo "selected";} echo $disabled; ?>><?=$resOp['sucu_nombre'];?></option>
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
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="contacto" required>
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
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['cotiz_contacto']==$resOp[0]){echo "selected";}?>><?=strtoupper($resOp['cont_nombre'])." (".$resOp['cont_email'].")";?></option>
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
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="influyente">
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
										<input type="date" class="span4" name="fechaPropuesta" required value="<?=$resultadoD['cotiz_fecha_propuesta'];?>">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Fecha de vencimiento</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaVencimiento" required value="<?=$resultadoD['cotiz_fecha_vencimiento'];?>">
									</div>
								</div>
								
								<?php
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
								
								<div class="control-group">
									<label class="control-label">Forma de pago</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="formaPago">
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
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="moneda" onChange="getPay(this)">

											<option value=""></option>
                                            <option value="1" <?php if($resultadoD['cotiz_moneda']==1)echo "selected";?>>COP</option>
                                            <option value="2" <?php if($resultadoD['cotiz_moneda']==2)echo "selected";?>>USD</option>
                                    	</select>
                                    </div>
                               </div>	
								
								<div class="control-group">
										<label class="control-label">Combos</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="span10" tabindex="2" name="combo[]" multiple id="combos-select">
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
											<select data-placeholder="Escoja una opción..." class="span10" tabindex="2" name="producto[]" multiple id="product-select">
											<?php
            									$consultaProductos = $conexionBdPrincipal->query("SELECT czpp_id, czpp_valor, czpp_cantidad, czpp_descuento, czpp_impuesto, czpp_orden, czpp_observacion, czpp_descuento_especial, czpp_aprobado_usuario, czpp_aprobado_fecha,prod_descuento2, prod_costo, prod_id, prod_nombre, prod_descripcion_corta, prod_utilidad FROM cotizacion_productos
												INNER JOIN productos ON prod_id=czpp_producto AND prod_id_empresa='".$idEmpresa."'
												WHERE czpp_cotizacion='" . $_GET["id"] . "' ORDER BY prod_nombre");

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
										<label class="control-label">Servicios</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="span10" tabindex="2" name="servicio[]" multiple id="servicios-select">
												<option value=""></option>
												<?php
												$conOp = $conexionBdPrincipal->query("SELECT czpp_servicio, czpp_cotizacion, serv_id, serv_nombre FROM cotizacion_productos
												INNER JOIN servicios ON serv_id=czpp_servicio AND serv_id_empresa='".$idEmpresa."' 
												WHERE czpp_cotizacion='".$resultadoD['cotiz_id']."'
												ORDER BY serv_nombre");
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
												?>
													<option selected value="<?=$resOp['serv_id'];?>"><?=$resOp['serv_id'].". ".$resOp['serv_nombre'];?></option>
												<?php
												}
												?>
											</select>
										</div>
								   </div>


								   <div class="control-group">
									<label class="control-label">Ocultar descuento de combos</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span2" tabindex="2" name="dctoCombos">
											<option value=""></option>
                                            <option value="1" <?php if($resultadoD['cotiz_ocultar_descuento_combo']==1)echo "selected";?>>SI</option>
                                            <option value="0" <?php if($resultadoD['cotiz_ocultar_descuento_combo']=='0')echo "selected";?>>NO</option>
                                    	</select>
                                    </div>
                               </div>


                               <p style="color: black; background-color: mediumaquamarine; padding: 10px; font-weight: bold;">Escoja SÍ, si desea solicitar a la Administración, que a esta cotización se le hagan algunos descuentos especiales en los items cotizados.</p>

                               <div class="control-group">
									<label class="control-label">Requiere un descuento especial?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span2" tabindex="2" name="dctoEspecial">
											<option value=""></option>
                                            <option value="1" <?php if($resultadoD['cotiz_descuentos_especiales']==1)echo "selected";?>>SI</option>
                                            <option value="0" <?php if($resultadoD['cotiz_descuentos_especiales']=='0')echo "selected";?>>NO</option>
                                    	</select>
                                    </div>
                               </div>
								
								
								
									<div class="control-group">
										<label class="control-label">Observaciones</label>
										<div class="controls">
											<textarea rows="5" cols="80" style="width: 80%" class="tinymce-simple" name="notas"><?=$resultadoD['cotiz_observaciones'];?></textarea>
										</div>
									</div>
								
								<div class="control-group">
									<label class="control-label">Costo Envío</label>
									<div class="controls">
										<input type="text" class="span4" name="envio" value="<?=$resultadoD['cotiz_envio'];?>">
									</div>
								</div>
								
                               <div class="form-actions">
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<?php
									if($resultadoD['cotiz_vendida']!=1){
									?>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<?php }?>
								</div>
								
						</div>
					</div>
				</div>
			</div>
			
			<!-- LISTADO DE LO QUE SE ESTÁ COTIZANDO -->	
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
									<td><?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?><?=!empty($envio) ? number_format($envio,0,",",".") : 0;?>
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

								<p style="color: black; background-color: <?=$colorCredito;?>; padding: 15px; font-weight: bold; font-size: 16px;">Esta cotización deja una utilidad aproximada de $<?=!empty($sumaUtilidad) ? number_format( ($sumaUtilidad) ,0,",",".") : 0;?></p>
							<?php }?>
							
							
								<div class="form-actions">
									
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<?php
									if($resultadoD['cotiz_vendida']!=1){
									?>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<?php }?>
									
										
									<?php if (Modulos::validarRol([50], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="reportes/formato-cotizacion-1.php?id=<?=$_GET["id"];?>" class="btn btn-success" target="_blank"><i class="icon-print"></i> Imprimir</a>
									<?php } ?>
								</div>
							</form>
							
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
