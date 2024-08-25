<?php 
include("sesion.php");

$idPagina = 89;
$paginaActual['pag_nombre'] = "Agregar Tikets de clientes";
include("includes/verificar-paginas.php");
include("includes/head.php");
if($_GET["em"]==4){
	mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_estado_mercadeo=4, cli_estado_mercadeo_fecha=now(), cli_estado_mercadeo_usuario='".$_SESSION["id"]."' WHERE cli_id='".$_GET["cte"]."' AND cli_id_empresa='".$idEmpresa."'");
}

$tiket = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_tikets WHERE tik_id='".$_GET["idTK"]."'"), MYSQLI_BOTH);
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
<?php include("includes/funciones-js.php");?>

<?php include("includes/texto-editor.php");?>
</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
    
	
	<?php
	$tipoTicket = $_GET["tipo"];
	if(!is_numeric($_GET["tipo"])){
		$tipoTicket = 1;
	}
	?>
	
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>
                        
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li><a href="clientes-tikets.php?cte=<?=$_GET["cte"];?>&tipo=<?=$tipoTicket;?>">Tikets de clientes</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_create/clientes-tikets-guardar.php">
                            <input type="hidden" name="cte" value="<?=$_GET["cte"];?>">
                            	
								
								<script type="application/javascript">
										function clientes(datos){
											var id = datos.value;
											var tipo = <?=$tipoTicket;?>;
											location.href = "clientes-tikets-agregar.php?cte="+id+"&tipo="+tipo;
										}
									</script>
								
                                <div class="control-group">
									<label class="control-label">Escoja un cliente (*)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente" onChange="clientes(this)" required>
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_ciudad!=".CIUDADES_INTERNACIONALES." AND cli_id_empresa='".$idEmpresa."'");
											if($datosUsuarioActual['usr_tipo']==1){
												$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id_empresa='".$idEmpresa."'");
											}
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
												if($datosUsuarioActual[3]!=1){
													$numZ = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$resOp['cli_zona']."'"));
													
													$numCliente = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_usuarios WHERE cliu_usuario='".$_SESSION["id"]."' AND cliu_cliente='".$resOp['cli_id']."'"));
									
													if($numZ == 0 and $numCliente == 0) continue;
												}
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if(isset($_GET["cte"]) and $_GET["cte"]!="" and $_GET["cte"]==$resOp[0]) echo "selected";?>><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<?php if(is_numeric($_GET['cte'])){
								$clienteInfo = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_GET['cte']."' AND cli_id_empresa='".$idEmpresa."'"), MYSQLI_BOTH);
								?>
								<div class="control-group">
									<label class="control-label">Sucursal (*)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="sucursal" required>
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM sucursales 
											WHERE sucu_cliente_principal='".$_GET["cte"]."'");
											$numOp = mysqli_num_rows($conOp);
											if($numOp==0){
												//Crear automáticamente la sucursal
												mysqli_query($conexionBdPrincipal,"INSERT INTO sucursales(sucu_cliente_principal, sucu_ciudad, sucu_direccion, sucu_telefono, sucu_celular, sucu_nombre)VALUES('".$_GET["cte"]."', '".$clienteInfo['cli_ciudad']."', '".$clienteInfo['cli_direccion']."', '".$clienteInfo['cli_telefono']."', '".$clienteInfo['cli_celular']."','Sede principal (Automática)')");
												
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
                               </div>
                               
								
                               <div class="control-group">
									<label class="control-label">Asunto principal</label>
									<div class="controls">
										<input type="text" class="span8" name="asuntoP" style="font-weight:bold;" required>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Fecha de inicio</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaInicio" value="<?=date("Y-m-d");?>">
									</div>
								</div>
								
								<!--
								<div class="control-group">
									<label class="control-label">Asunto principal</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="asuntoP">
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM tikets_asuntos");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
									</div>
								</div>
								-->
								
								<?php if(isset($tipoTicket) and $tipoTicket!=3){?>
								<fieldset class="default">
									<legend style="background-color: darkblue; color: white;">Negociación</legend>
									
								<div class="control-group">
									<label class="control-label">Valor ($)</label>
									<div class="controls">
										<input type="number" class="span4" name="valor" style="font-weight:bold;">
										<span style="color:#009;">Digite sólo el valor numérico. Sin puntos, ni comas, ni simbolos.</span>
									</div>
								</div>
									
								<div class="control-group">
									<label class="control-label">Etapa</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="etapa" onChange="razones(this)">
											<option value="1"></option>
                                            <?php
											for($i=1; $i<=6; $i++){
												if($resultadoD['tik_etapa']==$i)echo '<option value="'.$i.'" selected>'.$opcionesEtapa[$i].'</option>';
												else echo '<option value="'.$i.'">'.$opcionesEtapa[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>
									
								<script type="application/javascript">
											function razones(datos){
												var opcionEscogida = datos.value;
												if(opcionEscogida == 5){
													document.getElementById("razonGanado").style.visibility="visible";
												}else{
													document.getElementById("razonGanado").style.visibility="hidden";
												}
												
												if(opcionEscogida == 6){
													document.getElementById("razonPerdido").style.visibility="visible";
												}else{
													document.getElementById("razonPerdido").style.visibility="hidden";
												}
											}
										</script>	
									
								<div class="control-group" id="razonGanado" style="visibility: hidden;">
									<label class="control-label">¿Por qué se ganó el negocio?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="razonGanado">
											<option value="0"></option>
                                            <?php
											for($i=1; $i<=3; $i++){
												if($resultadoD['tik_razon_ganado']==$i)echo '<option value="'.$i.'" selected>'.$negociosGanados[$i].'</option>';
												else echo '<option value="'.$i.'">'.$negociosGanados[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>
									
								<div class="control-group" id="razonPerdido" style="visibility: hidden;">
									<label class="control-label">¿Por qué se perdió el negocio?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="razonPerdido">
											<option value="0"></option>
                                            <?php
											for($i=1; $i<=3; $i++){
												if($resultadoD['tik_razon_ganado']==$i)echo '<option value="'.$i.'" selected>'.$negociosGanados[$i].'</option>';
												else echo '<option value="'.$i.'">'.$negociosGanados[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>	
								
								<div class="control-group">
									<label class="control-label">Tipo negocio</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="tipoNegocio">
											<option value="0"></option>
                                            <?php
											for($i=1; $i<=3; $i++){
												if($resultadoD['tik_tipo_negocio']==$i)echo '<option value="'.$i.'" selected>'.$opcionesTipoNegocio[$i].'</option>';
												else echo '<option value="'.$i.'">'.$opcionesTipoNegocio[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>
									
								<div class="control-group">
									<label class="control-label">Origen del negocio</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="origenNegocio">
											<option value="0"></option>
                                            <?php
											for($i=1; $i<=8; $i++){
												if($_GET["origenNegocio"]==$i)echo '<option value="'.$i.'" selected>'.$opcionesOrigenNegocio[$i].'</option>';
												else echo '<option value="'.$i.'">'.$opcionesOrigenNegocio[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>	
									
							</fieldset>
								
							<?php }?>	
                               
                               <div class="control-group">
									<label class="control-label">Tipo de ticket</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="tipoS">
											<option value="1"></option>
                                            <option value="1" <?php if($tipoTicket==1){echo "selected";}?>>Comercial</option>
                                            <option value="3" <?php if($tipoTicket==3){echo "selected";}?>>Soporte operativo</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Prioridad</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="prioridad">
											<option value="1"></option>
                                            <option value="1" selected>Normal</option>
                                            <option value="2">Urgente</option>
                                            <option value="3">Muy Urgente</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Canal de contacto</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="canal">
											<option value="1"></option>
                                            <?php
											$opciones = array("","Facebook","WhatsApp","Fijo","Celular","Personal","Skype","Otro");
											for($i=1; $i<=7; $i++){
												if($resultadoD['tik_canal']==$i)echo '<option value="'.$i.'" selected>'.$opciones[$i].'</option>';
												else echo '<option value="'.$i.'">'.$opciones[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
								<!--
                               <div class="control-group">
									<label class="control-label">Equipo</label>
									<div class="controls">
										<input type="text" class="span4" name="equipo">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Referencia</label>
									<div class="controls">
										<input type="text" class="span4" name="referencia">
									</div>
								</div>
								-->
                                
                                <div class="control-group">
									<label class="control-label">Observaciones</label>
									<div class="controls">
                                        <textarea rows="5" cols="80" style="width: 80%" class="tinymce-simple" name="observaciones"></textarea>
									</div>
								</div>
								
								<?php }?>
                            	
								
                                
                               
								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									
									<?php if(is_numeric($_GET['cte'])){?>
                                    <button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
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