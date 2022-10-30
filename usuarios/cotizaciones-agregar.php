<?php
include("sesion.php");

$idPagina = 78;

include("includes/verificar-paginas.php");
include("includes/head.php");
?>
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
						
                        <ul class="top-right-toolbar">
							<li><a data-toggle="dropdown" class="dropdown-toggle blue-violate" href="#" title="Users"><i class="icon-user"></i></a>
							</li>
							<li><a href="#" class="green" title="Upload"><i class=" icon-upload-alt"></i></a></li>
							<li><a href="#" class="bondi-blue" title="Settings"><i class="icon-cogs"></i></a></li>
						</ul>
                        
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="cotizaciones.php">Cotizaciones</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_create/cotizaciones-guardar.php">

								<script type="application/javascript">
									function clientes(datoscliente){
										var id = datoscliente.value;
										location.href = "cotizaciones-agregar.php?cte="+id;
									}
								</script>
								
                               <div class="control-group">
									<label class="control-label">Escoja un cliente</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente" onChange="clientes(this)" required>
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM clientes");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){

												if($datosUsuarioActual[3]!=1){
													$consultaNumZ = $conexionBdPrincipal->query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$resOp['cli_zona']."'");
													$numZ = $consultaNumZ->num_rows;
													if($numZ==0) continue;
												}

												$disabled = '';
												$dealer = '';
												if($resOp['cli_categoria']==3){
													$dealer = '(DEALER)';

													if($datosUsuarioActual['usr_tipo']!=1){
														$disabled = 'disabled';
													}	
												}


											?>
                                            	<option value="<?=$resOp[0];?>" <?php if(isset($_GET["cte"]) and $_GET["cte"]!="" and $_GET["cte"]==$resOp[0]) echo "selected"; echo $disabled; ?>><?=$resOp[1]." ".$dealer;?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
								   
								   <?php if(is_numeric($_GET['cte'])){?>
								   		<a href="clientes-editar.php?id=<?=$_GET["cte"];?>" class="btn btn-info" target="_blank">Editar cliente</a>
								   <?php }?>
								   
							   </div>

							   <?php if(is_numeric($_GET['cte'])){?>

							   <?php if($configuracion['conf_proveedor_cotizacion'] == 1){?>

								<script type="application/javascript">

									function provee(datos){
										var idP = datos.value;
										var cte = <?=$_GET["cte"];?>;

										location.href = `cotizaciones-agregar.php?prov=${idP}&cte=${cte}`;
									}
									</script>
								
								<div class="control-group">
									 <label class="control-label">Escoja un proveedor</label>
									 <div class="controls">
										 <select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="proveedor" onChange="provee(this)" required>
											 <option value=""></option>
											 <?php
											 $conOp = $conexionBdPrincipal->query("SELECT * FROM proveedores");
											 while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											 ?>
												 <option value="<?=$resOp[0];?>" <?php if(isset($_GET["prov"]) and $_GET["prov"]!="" and $_GET["prov"]==$resOp[0]) echo "selected";?>><?=$resOp['prov_nombre'];?></option>
											 <?php
											 }
											 ?>
										 </select>
									 </div>
									
									<?php if(is_numeric($_GET['prov'])){?>
											<a href="proveedores-editar.php?id=<?=$_GET["prov"];?>" class="btn btn-info" target="_blank">Editar proveedor</a>
									<?php }?>
									
								</div>
 
									<?php }else{?>
									<input type="hidden" name="proveedor" value="0">
									<?php }?>
								
								
								<?php
								$consultaCli=$conexionBdPrincipal->query("SELECT * FROM clientes WHERE cli_id='".$_GET['cte']."'");
								$clienteInfo = mysqli_fetch_array($consultaCli, MYSQLI_BOTH);
								?>
								<div class="control-group">
									<label class="control-label">Sucursal</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="sucursal" required>
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM sucursales WHERE sucu_cliente_principal='".$_GET["cte"]."'");
											$numOp = $conOp->num_rows;
											if($numOp==0){
												//Crear automáticamente la sucursal
												$conexionBdPrincipal->query("INSERT INTO sucursales(sucu_cliente_principal, sucu_ciudad, sucu_direccion, sucu_telefono, sucu_celular, sucu_nombre)VALUES('".$_GET["cte"]."', '".$clienteInfo['cli_ciudad']."', '".$clienteInfo['cli_direccion']."', '".$clienteInfo['cli_telefono']."', '".$clienteInfo['cli_celular']."','Sede principal (Automática)')");
												
												echo '<script type="text/javascript">window.location.href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'";</script>';
												exit();
											}
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp[7];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
									<a href="clientes-sucursales.php?cte=<?=$_GET["cte"];?>" class="btn btn-info" target="_blank">Ver sucursales</a>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Contacto</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="contacto" required>
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM contactos WHERE cont_cliente_principal='".$_GET["cte"]."'");
											$numOp = $conOp->num_rows;
											if($numOp==0){
												//Crear automáticamente el contacto
												$conexionBdPrincipal->query("INSERT INTO contactos(cont_nombre, cont_cliente_principal)VALUES('Contacto principal (Automático)', '".$_GET["cte"]."')");
												
												echo '<script type="text/javascript">window.location.href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'";</script>';
												exit();
											}
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=strtoupper($resOp[1])." (".$resOp[3].")";?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
									<a href="clientes-contactos.php?cte=<?=$_GET["cte"];?>" class="btn btn-info" target="_blank">Ver contactos</a>
                               </div>	
                               
								<div class="control-group">
									<label class="control-label">Usuario Influyente</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="influyente" required>
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_bloqueado!=1 ORDER BY usr_nombre");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=strtoupper($resOp[4])." (".$resOp[5].")";?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Fecha de la propuesta</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaPropuesta" required value="<?=date("Y-m-d");?>">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Fecha de vencimiento</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaVencimiento" required value="<?=date("Y-m-d");?>">
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
                                            <option value="1" <?php if($clienteInfo['cli_credito']!=1)echo "selected";?>>Contado</option>
                                            <option value="2" <?php if($clienteInfo['cli_credito']==1)echo "selected";?>>Crédito</option>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Moneda</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="moneda">
											<option value=""></option>
                                            <option value="1" selected>COP</option>
                                            <option value="2">USD</option>
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
												if(is_numeric($_GET["prov"])){ $filtroProd .=" AND prod_proveedor='".$_GET["prov"]."'";}

												$conOp = $conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_id=prod_id $filtroProd ORDER BY prod_nombre ");
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
													
													if($resOp['prod_categoria'] == 28 and ($datosUsuarioActual[3]!=1 and $datosUsuarioActual[3]!=9) ){
														continue;
													}
												?>
													<option value="<?=$resOp['prod_id'];?>"><?=$resOp['prod_id'].". ".$resOp['prod_referencia']." ".strtoupper($resOp['prod_nombre'])." - [HAY ".$resOp['prod_existencias']."]";?></option>
												<?php
												}
												?>
											</select>
										</div>
								   </div>
								
									<div class="control-group">
										<label class="control-label">Combos</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span10" tabindex="2" name="combo[]" multiple>
												<option value=""></option>
												<?php
												$conOp = $conexionBdPrincipal->query("SELECT * FROM combos ORDER BY combo_nombre");
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
												?>
													<option value="<?=$resOp['combo_id'];?>"><?=$resOp['combo_nombre'];?></option>
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
												$conOp = $conexionBdPrincipal->query("SELECT * FROM servicios ORDER BY serv_nombre");
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
												?>
													<option value="<?=$resOp['serv_id'];?>"><?=$resOp['serv_id'].". ".$resOp['serv_nombre'];?></option>
												<?php
												}
												?>
											</select>
										</div>
								   </div>
								
								
									
								
									<div class="control-group">
										<label class="control-label">Observaciones</label>
										<div class="controls">
											<textarea rows="5" cols="80" style="width: 80%" class="tinymce-simple" name="notas"></textarea>
										</div>
									</div>	
								
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-arrow-right"></i> Continuar</button>
								</div>

						</div>
					</div>
				</div>
			</div>
				

			<?php }?>	
            

		</div>
	</div>
	<?php include("includes/pie.php");?>
</div>
</body>
</html>