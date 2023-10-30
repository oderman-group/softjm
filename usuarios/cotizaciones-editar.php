<?php
include("sesion.php");

$idPagina = 79;

include("includes/verificar-paginas.php");
include("includes/head.php");

$consultaCliente=$conexionBdPrincipal->query("SELECT * FROM cotizacion 
INNER JOIN clientes ON cli_id=cotiz_cliente
INNER JOIN contactos ON cont_id=cotiz_contacto
WHERE cotiz_id='".$_GET["id"]."'");
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

		  var campo = enviada.title;
		  var producto = enviada.name;
		  var proceso = 2;
		  var valor = enviada.value;
		  
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
	</script>
<?php }?>
<?php
		require '../usuarios/class/CotizacionesEditar.php';
		if (isset($_POST['action']) && $_POST['action'] === 'generarTablaProductos') {
			$htmlTablaProductos = CotizacionesEditar::generarTablaProductos($conexionBdPrincipal, $resultadoD,$simbolosMonedas);
			echo $htmlTablaProductos;
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
				<a href="cotizaciones-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
				<a href="reportes/formato-cotizacion-1.php?id=<?=$_GET["id"];?>" class="btn btn-success" target="_blank"><i class="icon-print"></i> Imprimir</a>
				
				<?php
				if($resultadoD['cotiz_vendida']!=1){
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
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
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
											 $conOp = $conexionBdPrincipal->query("SELECT prov_id, prov_nombre FROM proveedores");
											 while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											 ?>
												 <option value="<?=$resOp[0];?>" <?php if($resultadoD['cotiz_proveedor']==$resOp[0]) echo "selected";?>><?=$resOp['prov_nombre'];?></option>
											 <?php
											 }
											 ?>
										 </select>
									 </div>
									
									
											<a href="proveedores-editar.php?id=<?=$resultadoD['cotiz_proveedor'];?>" class="btn btn-info" target="_blank">Editar proveedor</a>
									
									
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
													WHEN cli_categoria = 3 THEN '(DEALER)'
													ELSE ''
												END AS 'categoria'	
												FROM clientes");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){

												$disabled = '';
												
												if($datosUsuarioActual['usr_tipo']!=1 and $resOp['cli_categoria']==3){
													$disabled = 'disabled';
												}	
												

											?>
                                            	<option value="<?=$resOp['cli_id'];?>" <?php if($cliente==$resOp['cli_id']){echo "selected";}  echo $disabled; ?>><?=$resOp['cli_nombre']." ".$resOp['categoria'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
								   <a href="clientes-editar.php?id=<?=$cliente;?>" class="btn btn-info" target="_blank">Editar cliente</a>
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
								   <a href="clientes-sucursales.php?cte=<?=$cliente;?>" class="btn btn-info" target="_blank">Ver sucursales</a>
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
									<a href="clientes-contactos.php?cte=<?=$cliente;?>" class="btn btn-info" target="_blank">Ver contactos</a>
                               </div>	
                               
							</fieldset>
								
								<div class="control-group">
									<label class="control-label">Usuario Influyente</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="influyente">
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT usr_id, usr_nombre, usr_email FROM usuarios WHERE usr_bloqueado!=1 ORDER BY usr_nombre");
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
											<select data-placeholder="Escoja una opción..." class="chzn-select span10" tabindex="2" name="combo[]" multiple>
												<option value=""></option>
												<?php
												$conOp = $conexionBdPrincipal->query("SELECT combo_id, combo_nombre FROM combos 
												ORDER BY combo_nombre");
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){

													$consultaCotizacionP=$conexionBdPrincipal->query("SELECT czpp_cotizacion, czpp_tipo, czpp_combo  FROM cotizacion_productos WHERE czpp_combo='".$resOp[0]."' AND czpp_cotizacion='".$resultadoD['cotiz_id']."' AND czpp_tipo=1");
													$productoN = $consultaCotizacionP->num_rows;
												?>
													<option <?php if($productoN>0){echo "selected";}?> value="<?=$resOp['combo_id'];?>"><?=$resOp['combo_nombre'];?></option>
												<?php
												}
												?>
											</select>
										</div>
								   </div>
								
								<div class="control-group">
										<label class="control-label">Productos</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción1..." class="span10" tabindex="2" name="producto[]" multiple id="product-select">
											<?php
            						$consultaProductos = $conexionBdPrincipal->query("SELECT czpp_id, czpp_valor, czpp_cantidad, czpp_descuento, 
																																			czpp_impuesto, czpp_orden, czpp_observacion, czpp_descuento_especial, czpp_aprobado_usuario, czpp_aprobado_fecha,prod_descuento2, prod_costo, prod_id, prod_nombre, prod_descripcion_corta, prod_utilidad
                                                            FROM productos
                                                            INNER JOIN cotizacion_productos ON czpp_producto=prod_id AND czpp_cotizacion='" . $_GET["id"] . "'
                                                            ORDER BY czpp_orden");

												while ($resProducto = mysqli_fetch_array($consultaProductos, MYSQLI_BOTH)) {
														$productoN = !empty($resProducto['czpp_id']) ? 1 : 0;
												?>
													<option <?php if ($productoN > 0) {
														echo "selected";
												} ?>
													value="<?= $resProducto['prod_id']; ?>"><?= $resProducto['prod_id'] . ". " . strtoupper($resProducto['prod_nombre']) . " - [HAY " . $resProducto['czpp_cantidad'] . "]"; ?></option>
												<?php
													}
												?>
											</select>
										</div>
									</div>
									<script>
										$(document).ready(function () {
												let productSelect = $("#product-select").select2({
														placeholder: "Escoja una opción...",
														multiple: true,
														minimumInputLength: 3,
														ajax: {
																type: "GET",
																url: "../usuarios/ajax/ajax-buscar-productos.php",
																dataType: "json",
																processResults: function (items) {
																		return {
																				results: items.map(function (item) {
																						return {
																								id: item.id,
																								text: item.text,
																						};
																				})
																		};
																}
														}
												});
												
												productSelect.on("change", function (e) {
														const producto = productSelect.val() || [];
														const url = new URL(window.location.href);
														const id = url.searchParams.get("id");
														$.ajax({
																type: "POST",
																url: "../usuarios/ajax/ajax-actualizar-productos-cotizacion.php",
																data: {
																	producto,
																	id
																},
																success: function (response) {
																		actulizarTablaProductos()	
																}
														});
												});

												productSelect.on("select2:clear", function (e) {
														console.log("clear")
												});

											function actulizarTablaProductos(){
												$.ajax({
														type: "POST",
														url: "", 
														data: {
																action: "generarTablaProductos"
                    					},
														success: function(response) {
															let bodyStart = response.indexOf('<body>');
															let bodyEnd = response.indexOf('</body>');
															let bodyContent = response.slice(bodyStart + 6, bodyEnd);
															$('#tableBody .producto').remove();
															$('#tableBody').append(bodyContent);
                    				}
                					});
											}
											actulizarTablaProductos()	
										});
									</script>
								
									<div class="control-group">
										<label class="control-label">Servicios</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span10" tabindex="2" name="servicio[]" multiple>
												<option value=""></option>
												<?php
												$conOp = $conexionBdPrincipal->query("SELECT serv_id, serv_nombre FROM servicios 
												ORDER BY serv_nombre");
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													
													$consultaCotizacionP=$conexionBdPrincipal->query("SELECT czpp_servicio, czpp_cotizacion FROM cotizacion_productos WHERE czpp_servicio='".$resOp[0]."' AND czpp_cotizacion='".$resultadoD['cotiz_id']."'");
													$productoN = $consultaCotizacionP->num_rows;
												?>
													<option <?php if($productoN>0){echo "selected";}?> value="<?=$resOp['serv_id'];?>"><?=$resOp['serv_id'].". ".$resOp['serv_nombre'];?></option>
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
							<tbody id="tableBody">
								
							<!-- COMBOS -->
							<?php
							$no = 1;
							$productos = $conexionBdPrincipal->query("SELECT * FROM combos
							INNER JOIN cotizacion_productos ON czpp_combo=combo_id AND czpp_cotizacion='".$_GET["id"]."'
							ORDER BY czpp_orden");
							$sumaUtilidad = 0;
							$totalIva = 0;
							$subtotal=0;
							$totalDescuento=0;
							$totalCantidad=0;
							while($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)){
								$dcto = 0;
								$valorTotal = 0;

								$valorTotal = ($prod['czpp_valor'] * $prod['czpp_cantidad']);

								if($prod['czpp_cantidad']>0 and $prod['czpp_descuento']>0){
									$dcto = ($valorTotal * ($prod['czpp_descuento']/100));
									$totalDescuento += $dcto;	
								}

								$valorConDcto = $valorTotal - $dcto;

								$totalIva += ($valorConDcto * ($prod['czpp_impuesto']/100));

								$subtotal +=$valorTotal;
								
								
								$totalCantidad += $prod['czpp_cantidad'];

								$consultaPreciosCombos=$conexionBdPrincipal->query("SELECT SUM(copp_cantidad*prod_precio) FROM combos_productos
								INNER JOIN productos ON prod_id=copp_producto
								WHERE copp_combo='".$prod['combo_id']."'");
								$precioNormalCombo = mysqli_fetch_array($consultaPreciosCombos, MYSQLI_BOTH);

								$productosDelCombo = $conexionBdPrincipal->query("SELECT * FROM combos_productos
								INNER JOIN productos ON prod_id=copp_producto
								WHERE copp_combo='".$prod['combo_id']."'
								");

								$sumaCostosProductosCombos = 0;
								$precioDealer = 0;
								while($pdCombo = mysqli_fetch_array($productosDelCombo, MYSQLI_BOTH)){

									$sumaCostosProductosCombos += $pdCombo['prod_costo'];

									$utilidadDealer = $pdCombo['prod_descuento2'] / 100;
									$precioDealer = $pdCombo['prod_costo'] + ($pdCombo['prod_costo'] * $utilidadDealer);
									$subtotalDealer = ($precioDealer * $pdCombo['copp_cantidad']);
									$totalDealer +=$subtotalDealer;

								}

								$sumaUtilidad += ($prod['czpp_valor'] - $sumaCostosProductosCombos);

								
							?>
							<tr>
								<td><?=$no;?></td>
								<td><input type="number" title="czpp_orden" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_orden'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
                                <td>
									<a href="bd_delete/cotizaciones-productos-eliminar.php?idItem=<?=$prod['czpp_id'];?>&id=<?=$_GET["id"];?>" onClick="if(!confirm('Desea eliminar este registro?')){return false;}"><i class="icon-trash"></i></a>
									<a href="combos-editar.php?id=<?=$prod['combo_id'];?>" target="_blank"><?=$prod['combo_nombre'];?></a><br>
									<?php if($prod['combo_descuento']!="" and $resultadoD['cotiz_ocultar_descuento_combo']=='0'){?>
										<span><b>Precio Normal:</b> $<?=number_format($precioNormalCombo[0],0,".",".");?></span><br>
										<span><b>Descuento:</b> <?=$prod['combo_descuento'];?>%</span><br>
									<?php }?>
									<span style="font-size: 9px; color: darkblue;"><?=$prod['combo_descripcion'];?></span><br>
									<span style="font-size: 9px; color: teal;">
									<?php
									$productosCombo = $conexionBdPrincipal->query("SELECT copp_id, copp_combo, copp_producto, copp_cantidad,
										prod_id, prod_nombre
									   FROM productos
									INNER JOIN combos_productos ON copp_producto=prod_id AND copp_combo='".$prod['combo_id']."'
									ORDER BY copp_id");
									while($prodCombo = mysqli_fetch_array($productosCombo, MYSQLI_BOTH)){
										echo $prodCombo['prod_nombre']." (".$prodCombo['copp_cantidad']." Unds.).<br>";
									}
									?>
									</span>
										
									<p><textarea title="czpp_observacion" name="<?=$prod['czpp_id'];?>" onChange="productos(this)" style="width: 300px;" rows="4"><?=$prod['czpp_observacion'];?></textarea></p>
								</td>
                                <td><input type="number" title="czpp_cantidad" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_cantidad'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
                                <td>
									<?php
									if($resultadoD['cli_categoria']==3 and $datosUsuarioActual['usr_tipo']==1){
										echo "<b>Precio Dealer: $".number_format($totalDealer, 0, ",", ".")."</b><br>";
									}
									?>
                                	<input type="text" title="czpp_valor" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_valor'];?>" onChange="productos(this)" style="width: 200px;" disabled><br>
                                	<?php
									if($datosUsuarioActual['usr_tipo']==1){
										echo "
										<b>Costo: $".number_format($sumaCostosProductosCombos, 0, ",", ".")."</b><br>
										<b>Valor Utilidad: $".number_format(($prod['czpp_valor'] - $sumaCostosProductosCombos), 0, ",", ".")."</b><br>
										";
									}
									?>
                                </td>
                                <td><input type="text" title="czpp_impuesto" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_impuesto'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
                                <td><input type="text" title="czpp_descuento" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_descuento'];?>" onChange="combos(this)" style="width: 50px; text-align: center;"></td>

                                <?php if($resultadoD['cotiz_descuentos_especiales'] == 1){?>
									<td>
										<input type="text" title="czpp_descuento_especial" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_descuento_especial'];?>" onChange="combos(this)" style="width: 50px; text-align: center;">

										<?php
									if($datosUsuarioActual['usr_tipo']==1 and $prod['czpp_aprobado_usuario']=="" and $prod['czpp_descuento_especial']>0){
										
										?>

										<br><a href="sql.php?get=70&idItem=<?=$prod['czpp_id'];?>" class="btn btn-success"> <i class="icon-ok-sign"></i> </a>


									<?php }
									$consultaDctoEspecial=$conexionBdPrincipal->query("SELECT usr_id, usr_nombre FROM usuarios WHERE usr_id='".$prod['czpp_aprobado_usuario']."'");
									$usuarioDctoEspecialAprobar = mysqli_fetch_array($consultaDctoEspecial, MYSQLI_BOTH);
									?>

									<br><span style="font-size:10px; color:gray;">

										<?=$prod['czpp_aprobado_fecha'];?><br>
										<?=$usuarioDctoEspecialAprobar['usr_nombre'];?>
											
										</span>
										
									</td>
								<?php }?>

								<td>
										<span class="moneda-simbolo"><?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?></span>
										<span class="valor-numerico"><?=number_format($valorTotal, 0, ",", ".");?></span>
								</td>

							</tr>
							<?php 
								$no ++;
							}
							?>	
								
								<!-- SERVICIOS -->
							<?php
							$productos = $conexionBdPrincipal->query("SELECT * FROM servicios
							INNER JOIN cotizacion_productos ON czpp_servicio=serv_id AND czpp_cotizacion='".$_GET["id"]."'
							ORDER BY czpp_orden");
							while($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)){
								$dcto = 0;
								$valorTotal = 0;

								$valorTotal = ($prod['czpp_valor'] * $prod['czpp_cantidad']);

								if($prod['czpp_cantidad']>0 and $prod['czpp_descuento']>0){
									$dcto = ($valorTotal * ($prod['czpp_descuento']/100));
									$totalDescuento += $dcto;	
								}

								$valorConDcto = $valorTotal - $dcto;

								$totalIva += ($valorConDcto * ($prod['czpp_impuesto']/100));

								$subtotal +=$valorTotal;	
								
								
								$totalCantidad += $prod['czpp_cantidad'];
							?>
							<tr>
								<td><?=$no;?></td>
								<td><input type="number" title="czpp_orden" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_orden'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
                                <td>
									<a href="bd_delete/cotizaciones-productos-eliminar.php?idItem=<?=$prod['czpp_id'];?>&id=<?=$_GET["id"];?>" onClick="if(!confirm('Desea eliminar este registro?')){return false;}"><i class="icon-trash"></i></a>
									<a href="servicios-editar.php?id=<?=$prod['serv_id'];?>" target="_blank"><?=$prod['serv_nombre'];?></a>
										
									<p><textarea title="czpp_observacion" name="<?=$prod['czpp_id'];?>" onChange="productos(this)" style="width: 300px;" rows="4"><?=$prod['czpp_observacion'];?></textarea></p>
								</td>
                                <td><input type="number" title="czpp_cantidad" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_cantidad'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
                                <td><input type="text" title="czpp_valor" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_valor'];?>" onChange="productos(this)" style="width: 200px;"></td>
                                <td><input type="text" title="czpp_impuesto" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_impuesto'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
								<td><input type="text" title="czpp_descuento" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_descuento'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
								<?php if($resultadoD['cotiz_descuentos_especiales'] == 1){?>
									<td><input type="text" title="czpp_descuento_especial" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_descuento_especial'];?>" onChange="combos(this)" style="width: 50px; text-align: center;"></td>
								<?php }?>
									<td>
											<span class="moneda-simbolo"><?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?></span>
											<span class="valor-numerico"><?=number_format($valorTotal, 0, ",", ".");?></span>
									</td>
							</tr>
							<?php 
								$no ++;
							}
							?>
							
							<?php
							if($resultadoD['cotiz_envio']==''){$envio=0;}else{$envio=$resultadoD['cotiz_envio'];}
							$total = $subtotal- $totalDescuento + $totalIva + $envio;
							?>	
							</tbody>
							<tfoot>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="<?=$colspan;?>">SUBTOTAL</td>
									<td id="subtotal">
									<span class="moneda-simbolo">
										<?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
										<span class="valor-numerico"><?=number_format($subtotal,0,",",".");?>
										</span>
									</td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="<?=$colspan;?>">DESCUENTO</td>
									<td id="totalDiscount"><span class="moneda-simbolo">
										<?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
										<span class="valor-numerico"><?=number_format($totalDescuento,0,",",".");?>
										</span></td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="<?=$colspan;?>">IVA</td>
									<td id="totalIva"><span class="moneda-simbolo">
										<?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
										<span class="valor-numerico"><?=number_format($totalIva,0,",",".");?>
										</span></td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="<?=$colspan;?>">ENVÍO</td>
									<td><?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?><?=number_format($envio,0,",",".");?>
										</td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="<?=$colspan;?>">TOTAL NETO</td>
									<td id="total"><span class="moneda-simbolo">
										<?=$simbolosMonedas[$resultadoD['cotiz_moneda']];?> </span>
										<span class="valor-numerico"><?=number_format($subtotal,0,",",".");?>
										</span></td>
								</tr>
							</tfoot>	
								
							</table>

							<?php
							if($datosUsuarioActual['usr_tipo']==1){?>

								<p style="color: black; background-color: <?=$colorCredito;?>; padding: 15px; font-weight: bold; font-size: 16px;">Esta cotización deja una utilidad aproximada de $<?=number_format( ($sumaUtilidad) ,0,",",".");?></p>
							<?php }?>
							
							
								<div class="form-actions">
									
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<?php
									if($resultadoD['cotiz_vendida']!=1){
									?>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<?php }?>
									
										
									<a href="reportes/formato-cotizacion-1.php?id=<?=$_GET["id"];?>" class="btn btn-success" target="_blank"><i class="icon-print"></i> Imprimir</a>
								</div>
							</form>
							
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<?php include("includes/pie.php");?>
	<script>
  // Función para recalcular totales
  function recalculate() {
    let subtotal = 0;
    let totalDiscount = 0;
    let totalIva = 0;

    $("#data-table tbody tr").each(function () {
      let quantity = parseInt($(this).find("input[title='czpp_cantidad']").val()) || 0;
      let value = parseFloat($(this).find("input[title='czpp_valor']").val()) || 0;
      let discount = parseFloat($(this).find("input[title='czpp_descuento']").val()) || 0;
      let subtotalRow = quantity * value;
      let discountAmount = (subtotalRow * discount) / 100;
      let rowTotal = subtotalRow - discountAmount;
      let rowIva = (rowTotal * parseFloat($(this).find("input[title='czpp_impuesto']").val())) / 100;

      subtotal += subtotalRow;
      totalDiscount += discountAmount;
      totalIva += rowIva;
    });

    $("#subtotal .valor-numerico").text(subtotal.toLocaleString('es-CO', { minimumFractionDigits: 0 }));
    $("#totalDiscount .valor-numerico").text(totalDiscount.toLocaleString('es-CO', { minimumFractionDigits: 0 }));
    $("#totalIva .valor-numerico" ).text(totalIva.toLocaleString('es-CO', { minimumFractionDigits: 0 }));

    let envio = parseFloat($("input[name='envio']").val()) || 0;
    let total = subtotal - totalDiscount + totalIva + envio;
    $("#total .valor-numerico").text(total.toLocaleString('es-CO', { minimumFractionDigits: 0 }));
  }

  $("#data-table tbody").on("input", "input[title='czpp_cantidad'], input[title='czpp_valor'], input[title='czpp_descuento'], input[title='czpp_impuesto']", recalculate);

  $("input[name='envio']").on("input", recalculate);

  let observer = new MutationObserver(function (mutations) {
    recalculate();
  });

  let tbody = document.getElementById("data-table").getElementsByTagName("tbody")[0];

  let config = { childList: true };

  observer.observe(tbody, config);

  recalculate();
</script>

<script>
    // Función para actualizar una subtotal parcial específica
    function updatePartialSubtotal(row) {
        let cantidad = parseFloat(row.find("input[title='czpp_cantidad']").val()) || 0;
        let valor = parseFloat(row.find("input[title='czpp_valor']").val()) || 0;
        let descuento = parseFloat(row.find("input[title='czpp_descuento']").val()) || 0;
        let iva = parseFloat(row.find("input[title='czpp_iva']").val()) || 0;

        let subtotalParcial = cantidad * valor;
        // let subtotalParcial = cantidad * valor - (cantidad * valor * descuento / 100);
        let ivaAmount = (subtotalParcial * iva / 100);
        subtotalParcial += ivaAmount;
        let valorNumeric = row.find('.valor-numerico');
        valorNumeric.text(subtotalParcial.toLocaleString('es-CO', { minimumFractionDigits: 0 }));
    }


    function recalculatePartialSubtotals() {
        $('#data-table tbody tr').each(function () {
            let row = $(this);
            updatePartialSubtotal(row);
        });
    }
    $('#data-table tbody').on('input', 'input[title^="czpp_"]', function () {
        let row = $(this).closest('tr');
        updatePartialSubtotal(row);
    });
    recalculatePartialSubtotals();
</script>

<script>
	// Controlador de eventos para hacer clic en los enlaces de eliminación
	$(document).on("click", ".delete-product", function (e) {
	    e.preventDefault(); 
	    if (!confirm('¿Desea eliminar este registro?')) {
	        return;
	    }
	  const idItem = $(this).data("id"); 

	  const select2Element = $("#product-select"); 
		
		const currentSelectedValues = select2Element.val();

		const newSelectedValues = currentSelectedValues.filter(value => value != idItem);

		select2Element.val(newSelectedValues|| []);
		select2Element.trigger("change");
	});
</script>
</div>
</body>
</html>