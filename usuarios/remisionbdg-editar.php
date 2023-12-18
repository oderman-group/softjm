<?php 
include("sesion.php");

$idPagina = 150;
include("includes/verificar-paginas.php");
include("includes/head.php");

$consulta=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisionbdg 
INNER JOIN clientes ON cli_id=remi_cliente
INNER JOIN usuarios ON usr_id=remi_creador
WHERE remi_id='".$_GET["id"]."' AND remi_id_empresa='".$idEmpresa."'");
$resultadoD = mysqli_fetch_array($consulta);

if(is_numeric($_GET["cte"])){
	$cliente = $_GET["cte"]; 
}else{
	$cliente = $resultadoD['remi_cliente'];
}
?>
<!-- styles -->

<link href="css/chosen.css" rel="stylesheet">


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
<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>
<?php include("includes/texto-editor.php");?>


<?php if($resultadoD['remi_vendida']!=1){?>

	<script type="text/javascript">
	function productos(enviada){
		var idRemision = <?=$_GET['id'];?>;

		var campo = enviada.title;
		var producto = enviada.name;
		var proceso = 8;
		var combo = enviada.alt;
		if(combo==1){
			var proceso = 11;
		}
		var valor = enviada.value;
		$('#resp').empty().hide().html("Esperando...").show(1);
		datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo)+"&idRemision="+(idRemision);
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
						<li><a href="remisionbdg.php">Remisiones</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            
            <?php include("includes/notificaciones.php");?>
			
			
			<p>
				<?php if (Modulos::validarRol([149], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
				<a href="remisionbdg-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
				<?php }?>
				<?php if (Modulos::validarRol([376], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
				<a href="reportes/formato-remision-1.php?id=<?=$_GET["id"];?>" class="btn btn-success" target="_blank"><i class="icon-print"></i> Imprimir</a>
				<?php }?>
				
				<!--
				<a href="sql.php?get=44&id=<?=$_GET["id"];?>" class="btn btn-warning" onClick="if(!confirm('Desea Enviar este mensaje al correo del contacto?')){return false;}"><i class="icon-envelope"></i> Enviar por correo</a>
				-->
				<?php if (Modulos::validarRol([377], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
				<a href="bd_create/remisionbdg-generar-factura.php?id=<?=$resultadoD[0];?>" class="btn btn-info" onClick="if(!confirm('Desea generar factura de esta remisión?')){return false;}"><i class="icon-money"></i> Generar factura</a>
				<?php }?>
			</p>	
								
			
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_update/remisionbdg-actualizar.php">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
							<input type="hidden" name="monedaActual" value="<?=$resultadoD['remi_moneda'];?>">
                            	   
                               <script type="application/javascript">
									function clientes(datos){
										id = datos.value;
										location.href = "remisionesbdg-editar.php?id=<?=$_GET["id"];?>&cte="+id+"#productos";
									}
								</script>
								
								<div class="form-actions">
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
												<?php if (Modulos::validarRol([149], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
            						<a href="remisionbdg-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
												<?php }?>
								</div>
								

								<?php if($configuracion['conf_proveedor_cotizacion'] == 1){?>
								
								<div class="control-group">
									 <label class="control-label">Escoja un proveedor</label>
									 <div class="controls">
										 <select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="proveedor" onChange="provee(this)" required>
											 <option value=""></option>
											 <?php
											 $conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM proveedores WHERE prov_id_empresa='".$idEmpresa."'");
											 while($resOp = mysqli_fetch_array($conOp)){
											 ?>
												 <option value="<?=$resOp[0];?>" <?php if($resultadoD['remi_proveedor']==$resOp[0]) echo "selected";?>><?=$resOp['prov_nombre'];?></option>
											 <?php
											 }
											 ?>
										 </select>
									 </div>
									<?php if (Modulos::validarRol([125], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									 <a href="proveedores-editar.php?id=<?=$resultadoD['remi_proveedor'];?>" class="btn btn-info" target="_blank">Editar proveedor</a>
									<?php }?>
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
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$cliente."' AND cli_id_empresa='".$idEmpresa."'");
											while($resOp = mysqli_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp['cli_id'];?>" <?php if($cliente==$resOp['cli_id']){echo "selected";}?>><?=$resOp['cli_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
									<?php if (Modulos::validarRol([11], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
								   <a href="clientes-editar.php?id=<?=$cliente;?>" class="btn btn-info" target="_blank">Editar cliente</a>
									<?php }?>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Sucursal</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="sucursal" required>
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM sucursales 
											WHERE sucu_cliente_principal='".$cliente."'");
											$numOp = mysqli_num_rows($conOp);
											if($numOp==0){
												//Crear automáticamente la sucursal
												mysqli_query($conexionBdPrincipal,"INSERT INTO sucursales(sucu_cliente_principal, sucu_ciudad, sucu_direccion, sucu_telefono, sucu_celular, sucu_nombre)VALUES('".$cliente."', '".$clienteInfo['cli_ciudad']."', '".$clienteInfo['cli_direccion']."', '".$clienteInfo['cli_telefono']."', '".$clienteInfo['cli_celular']."','Sede principal (Automática)')");
												
												echo '<script type="text/javascript">window.location.href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'";</script>';
												exit();
											}
											while($resOp = mysqli_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['remi_sucursal']==$resOp[0]){echo "selected";}?>><?=$resOp[7];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
									<?php if (Modulos::validarRol([83], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
								   <a href="clientes-sucursales.php?cte=<?=$cliente;?>" class="btn btn-info" target="_blank">Ver sucursales</a>
									<?php }?>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Contacto</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="contacto" required>
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos 
											WHERE cont_cliente_principal='".$cliente."'");
											$numOp = mysqli_num_rows($conOp);
											if($numOp==0){
												//Crear automáticamente el contacto
												mysqli_query($conexionBdPrincipal,"INSERT INTO contactos(cont_nombre, cont_cliente_principal)VALUES('Contacto principal (Automático)', '".$cliente."')");
												
												echo '<script type="text/javascript">window.location.href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'";</script>';
												exit();
											}
											while($resOp = mysqli_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['remi_contacto']==$resOp[0]){echo "selected";}?>><?=strtoupper($resOp[1])." (".$resOp[3].")";?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
									<?php if (Modulos::validarRol([44], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="clientes-contactos.php?cte=<?=$cliente;?>" class="btn btn-info" target="_blank">Ver contactos</a>
									<?php }?>
                               </div>	
                               
							</fieldset>
								
								<div class="control-group">
									<label class="control-label">Usuario Influyente</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="influyente">
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_bloqueado!=1 AND usr_id_empresa='".$idEmpresa."' ORDER BY usr_nombre");
											while($resOp = mysqli_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['remi_vendedor']==$resOp[0]){echo "selected";}?>><?=strtoupper($resOp[4])." (".$resOp[5].")";?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Fecha de la propuesta</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaPropuesta" required value="<?=$resultadoD['remi_fecha_propuesta'];?>">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Fecha de vencimiento</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaVencimiento" required value="<?=$resultadoD['remi_fecha_vencimiento'];?>">
									</div>
								</div>
								
								<?php
								if($clienteInfo['cli_credito']==1){
									$msjCredito = "Este cliente tiene crédito con la compañía.";
									$colorCredito = 'aquamarine';
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
                                            <option value="1" <?php if($resultadoD['remi_forma_pago']==1)echo "selected";?>>Contado</option>
                                            <option value="2" <?php if($resultadoD['remi_forma_pago']==2)echo "selected";?>>Crédito</option>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Moneda</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="moneda">
											<option value=""></option>
                                            <option value="1" <?php if($resultadoD['remi_moneda']==1)echo "selected";?>>COP</option>
                                            <option value="2" <?php if($resultadoD['remi_moneda']==2)echo "selected";?>>USD</option>
                                    	</select>
                                    </div>
                               </div>	
								
							   <div class="control-group">
										<label class="control-label">Combos</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span10" tabindex="2" name="combo[]" multiple>
												<option value=""></option>
												<?php
												$conOp = $conexionBdPrincipal->query("SELECT combo_id, combo_nombre FROM combos WHERE combo_id_empresa='".$idEmpresa."'
												ORDER BY combo_nombre");
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){

													$consultaCotizacionP=$conexionBdPrincipal->query("SELECT czpp_cotizacion, czpp_tipo, czpp_combo  FROM cotizacion_productos WHERE czpp_combo='".$resOp[0]."' AND czpp_cotizacion='".$resultadoD['remi_id']."' AND czpp_tipo='".CZPP_TIPO_COTZ."'");
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
											<select data-placeholder="Escoja una opción..." class="chzn-select span10" tabindex="2" name="producto[]" multiple>
												<option value=""></option>
												<?php
												$filtroProd = '';
												if(is_numeric($resultadoD['remi_proveedor']) and $resultadoD['remi_proveedor']!='0' and $resultadoD['remi_proveedor']!=''){ $filtroProd .=" AND prod_proveedor='".$resultadoD['remi_proveedor']."'";}

												$conOp = $conexionBdPrincipal->query("SELECT prod_id, prod_referencia, prod_nombre, prod_existencias, prod_categoria FROM productos 
												WHERE prod_id=prod_id AND prod_id_empresa='".$idEmpresa."' $filtroProd
												ORDER BY prod_nombre");
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){

													if($resOp['prod_categoria'] == 28 and !Modulos::validarRol([392], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) ){
														continue;
													}
													$consultaCotizacionP=$conexionBdPrincipal->query("SELECT czpp_producto, czpp_cotizacion 
														FROM cotizacion_productos 
														WHERE czpp_producto='".$resOp[0]."' AND czpp_cotizacion='".$resultadoD['remi_id']."'");
													$productoN = $consultaCotizacionP->num_rows;

												?>
													<option <?php if($productoN>0){echo "selected";}?> value="<?=$resOp['prod_id'];?>"><?=$resOp['prod_id'].". ".$resOp['prod_referencia']." ".strtoupper($resOp['prod_nombre'])." - [HAY ".$resOp['prod_existencias']."]";?></option>
												<?php
												}
												?>
											</select>
										</div>
								   </div>
								
								
									<div class="control-group">
										<label class="control-label">Servicios</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span10" tabindex="2" name="servicio[]" multiple>
												<option value=""></option>
												<?php
												$conOp = $conexionBdPrincipal->query("SELECT serv_id, serv_nombre FROM servicios WHERE serv_id_empresa='".$idEmpresa."'
												ORDER BY serv_nombre");
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													
													$consultaCotizacionP=$conexionBdPrincipal->query("SELECT czpp_servicio, czpp_cotizacion FROM cotizacion_productos WHERE czpp_servicio='".$resOp[0]."' AND czpp_cotizacion='".$resultadoD['remi_id']."'");
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
										<label class="control-label">Observaciones</label>
										<div class="controls">
											<textarea rows="5" cols="80" style="width: 80%" class="tinymce-simple" name="notas"><?=$resultadoD['remi_observaciones'];?></textarea>
										</div>
									</div>
								
                               <div class="form-actions">
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									
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
								<th>Bodega</th>
                                <th>Cant.</th> 
                                <th>Valor Base</th>
                                <th>IVA</th>
								<th>Dcto.</th>
                                <th>SUBTOTAL</th>
							</tr>
							</thead>
							<tbody>
								
							<!-- COMBOS -->
							<?php
							$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM combos
							INNER JOIN cotizacion_productos ON czpp_combo=combo_id AND czpp_cotizacion='".$_GET["id"]."'
							WHERE combo_id_empresa='".$idEmpresa."'
							ORDER BY czpp_orden");
							while($prod = mysqli_fetch_array($productos)){
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
									<?php if (Modulos::validarRol([378], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="bd_delete/remisionbdg-productos-eliminar.php?idItem=<?=$prod['czpp_id'];?>&id=<?=$_GET["id"];?>" onClick="if(!confirm('Desea eliminar este registro?')){return false;}"><i class="icon-trash"></i></a>
									<?php }?>
									<?php if (Modulos::validarRol([175], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="combos-editar.php?id=<?=$prod['combo_id'];?>" target="_blank"><?=$prod['combo_nombre'];?></a><br>
									<?php }?>
									<span style="font-size: 9px; color: teal;">
									<?php
									$productosCombo = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos 
									INNER JOIN productos_categorias ON catp_id=prod_categoria
									INNER JOIN combos_productos ON copp_producto=prod_id AND copp_combo='".$prod['combo_id']."'
									WHERE prod_id_empresa='".$idEmpresa."'
									ORDER BY copp_id");
									while($prodCombo = mysqli_fetch_array($productosCombo)){
										echo $prodCombo['prod_nombre']." (".$prodCombo['copp_cantidad']." Unds.).<br>";
									}
									?>
									</span>
										
									<p><textarea title="czpp_observacion" name="<?=$prod['czpp_id'];?>" onChange="productos(this)" style="width: 300px;" rows="4"><?=$prod['czpp_observacion'];?></textarea></p>
								</td>

								<td>&nbsp;</td>

                                <td><input type="number" title="czpp_cantidad" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_cantidad'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
                                <td><input type="text" title="czpp_valor" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_valor'];?>" onChange="productos(this)" style="width: 200px;"></td>
                                <td><input type="text" title="czpp_impuesto" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_impuesto'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
								<td><input type="text" title="czpp_descuento" name="<?=$prod['czpp_id'];?>" alt="1" value="<?=$prod['czpp_descuento'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
                                <td><?=$simbolosMonedas[$resultadoD['remi_moneda']];?><?=number_format($valorTotal,0,",",".");?></td>
							</tr>
							<?php 
								$no ++;
							}
							?>
								
							<!-- PRODUCTOS -->	
                            <?php
							$no = 1;
							$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos
						INNER JOIN cotizacion_productos ON czpp_producto=prod_id AND czpp_cotizacion='".$_GET["id"]."'
						WHERE prod_id_empresa='".$idEmpresa."'
						ORDER BY czpp_orden");
							while($prod = mysqli_fetch_array($productos)){
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
									<?php if (Modulos::validarRol([378], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="bd_delete/remisionbdg-productos-eliminar.php?idItem=<?=$prod['czpp_id'];?>&id=<?=$_GET["id"];?>" onClick="if(!confirm('Desea eliminar este registro?')){return false;}"><i class="icon-trash"></i></a>
									<?php }?>	
									<?php if (Modulos::validarRol([379], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="bd_update/remisionbdg-productos-replicar.php?idItem=<?=$prod['czpp_id'];?>&id=<?=$_GET["id"];?>" onClick="if(!confirm('Desea replicar este producto?')){return false;}"><i class="icon-retweet"></i></a>
									<?php }?>	
									<?php if (Modulos::validarRol([38], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="productos-editar.php?id=<?=$prod['prod_id'];?>" target="_blank"><?=$prod['prod_nombre'];?></a><br>
									<?php }?>	
										
									<p><textarea title="czpp_observacion" name="<?=$prod['czpp_id'];?>" onChange="productos(this)" style="width: 300px;" rows="4"><?=$prod['czpp_observacion'];?></textarea></p>
								</td>
								<td>
									<select data-placeholder="Escoja una opción..." class="chzn-select" tabindex="2" title="czpp_bodega" name="<?=$prod['czpp_id'];?>" onChange="productos(this)">
                                                <option value=""></option>
                                                <?php
                                                $conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM bodegas WHERE bod_id_empresa='".$idEmpresa."'", $conexion);
                                                while ($resOp = mysqli_fetch_array($conOp)) {
													$numPpb = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_bodegas WHERE prodb_producto='".$prod['prod_id']."' AND prodb_bodega='".$resOp[0]."'"));
                                                ?>
                                                    <option value="<?= $resOp[0]; ?>" <?php if($resOp[0] == $prod['czpp_bodega']){echo "selected";} ?> ><?= $resOp[1]." (Hay ".$numPpb['prodb_existencias'].")"; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
								</td>
                                <td><input type="number" title="czpp_cantidad" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_cantidad'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
                                <td><input type="text" title="czpp_valor" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_valor'];?>" onChange="productos(this)" style="width: 200px;"></td>
                                <td><input type="text" title="czpp_impuesto" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_impuesto'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
								<td><input type="text" title="czpp_descuento" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_descuento'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
                                <td><?=$simbolosMonedas[$resultadoD['remi_moneda']];?><?=number_format($valorTotal,0,",",".");?></td>
							</tr>
							<?php 
								$no ++;
							}
							?>	
								
								<!-- SERVICIOS -->
							<?php
							$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM servicios
							INNER JOIN cotizacion_productos ON czpp_servicio=serv_id AND czpp_cotizacion='".$_GET["id"]."'
							WHERE serv_id_empresa='".$idEmpresa."'
							ORDER BY czpp_orden");
							while($prod = mysqli_fetch_array($productos)){
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
									<?php if (Modulos::validarRol([378], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="bd_delete/remisionbdg-productos-eliminar.php?idItem=<?=$prod['czpp_id'];?>&id=<?=$_GET["id"];?>" onClick="if(!confirm('Desea eliminar este registro?')){return false;}"><i class="icon-trash"></i></a>
									<?php }?>
									<?php if (Modulos::validarRol([156], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="servicios-editar.php?id=<?=$prod['serv_id'];?>" target="_blank"><?=$prod['serv_nombre'];?></a>
									<?php }?>									
										
									<p><textarea title="czpp_observacion" name="<?=$prod['czpp_id'];?>" onChange="productos(this)" style="width: 300px;" rows="4"><?=$prod['czpp_observacion'];?></textarea></p>
								</td>
								<td>&nbsp;</td>
                                <td><input type="number" title="czpp_cantidad" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_cantidad'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
                                <td><input type="text" title="czpp_valor" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_valor'];?>" onChange="productos(this)" style="width: 200px;"></td>
                                <td><input type="text" title="czpp_impuesto" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_impuesto'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
								<td><input type="text" title="czpp_descuento" name="<?=$prod['czpp_id'];?>" value="<?=$prod['czpp_descuento'];?>" onChange="productos(this)" style="width: 50px; text-align: center;"></td>
                                <td><?=$simbolosMonedas[$resultadoD['remi_moneda']];?><?=number_format($valorTotal,0,",",".");?></td>
							</tr>
							<?php 
								$no ++;
							}
							?>
							
							<?php
							$total = $subtotal - $totalDescuento;
							$total +=$resultado['remi_envio'] + $totalIva;

							if(!is_numeric($subtotal) && $subtotal<1){
								$subtotal=0;
							}
							if(!is_numeric($totalDescuento) && $totalDescuento<1){
								$totalDescuento=0;
							}
							if(!is_numeric($totalIva) && $totalIva<1){
								$totalIva=0;
							}
							if(!is_numeric($total) && $total<1){
								$total=0;
							}
							?>	
							</tbody>
							<tfoot>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="8">SUBTOTAL</td>
									<td><?=$simbolosMonedas[$resultadoD['remi_moneda']];?><?=number_format($subtotal,0,",",".");?></td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="8">DESCUENTO</td>
									<td><?=$simbolosMonedas[$resultadoD['remi_moneda']];?><?=number_format($totalDescuento,0,",",".");?></td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="8">IVA</td>
									<td><?=$simbolosMonedas[$resultadoD['remi_moneda']];?><?=number_format($totalIva,0,",",".");?></td>
								</tr>
								<tr style="font-weight: bold; font-size: 16px;">
									<td style="text-align: right;" colspan="8">TOTAL NETO</td>
									<td><?=$simbolosMonedas[$resultadoD['remi_moneda']];?><?=number_format($total,0,",",".");?></td>
								</tr>
							</tfoot>	
								
							</table>
							
							
								<div class="form-actions">
									
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									
									
									<?php if (Modulos::validarRol([376], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="reportes/formato-remision-1.php?id=<?=$_GET["id"];?>" class="btn btn-success" target="_blank"><i class="icon-print"></i> Imprimir</a>
									<?php }?>	
								</div>
							</form>
							
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<?php include("includes/pie.php");?>
</div>
</body>
</html>