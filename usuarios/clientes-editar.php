<?php 
include("sesion.php");
$idPagina = 11;
include("includes/verificar-paginas.php");
include("includes/head.php");

$consulta = $conexionBdPrincipal->query("SELECT * FROM clientes WHERE cli_id='".$_GET["id"]."'");
$resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);
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
</script>

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
    
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>  
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="clientes.php">Clientes</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            <p>
				<a href="clientes-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>

				<a href="enviar_correos/clientes-enviar-credenciales.php?id=<?=$_GET["id"];?>" class="btn btn-info" onClick="if(!confirm('Desea ejecutar esta accion?')){return false;}"><i class="icon-envelope"></i> Enviar credenciales</a>
			</p>

            <?php include("includes/notificaciones.php");?>
			
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>

						<div class="widget-container">

									<ul class="nav nav-tabs" id="myTab1">
										<li class="active"><a href="#user"><i class="icon-tasks"></i> Información</a></li>
										<li><a href="#sucursales"><i class=" icon-home"></i> Sucursales</a></li>
										<li><a href="#task"><i class=" icon-group"></i> Contactos</a></li>
										<li><a href="#tickets"><i class=" icon-list"></i> Tickets</a></li>
										<li><a href="#seguimientos"><i class=" icon-list-alt"></i> Seguimientos</a></li>
										<li><a href="#cotizacion"><i class=" icon-list"></i> Cotizaciones</a></li>
										<li><a href="#facturas"><i class=" icon-list-alt"></i> Facturas</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="user">
											
											<form class="form-horizontal" method="post" action="bd_update/clientes-actualizar.php">

												<input type="hidden" name="id" value="<?=$_GET["id"];?>">
													<?php
													if($datosUsuarioActual['usr_tipo']==1){$campoC = "text";} else{$campoC = "password";}
													?>
													<fieldset class="default">
														<legend>Datos básicos</legend>
														
													<div class="control-group">
														<label class="control-label">Tipo de documento</label>
														<div class="controls">
															<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="tipoDocumento">
																<option value="1"></option>
																<option value="2" <?php if($resultadoD['cli_tipo_documento']==2){echo "selected";}?>>NIT</option>
																<option value="3" <?php if($resultadoD['cli_tipo_documento']==3){echo "selected";}?>>Cédula</option>
															</select>
														</div>
												   </div>
														
													<div class="control-group">
														<label class="control-label">Documento</label>
														<div class="controls">
															<input type="text" class="span4" name="usuarioCliente" value="<?=$resultadoD['cli_usuario'];?>" autocomplete="off" placeholder="Documento" title="Documento">
															Contraseña
															<input type="<?php echo $campoC;?>" class="span4" name="claveCliente" value="<?=$resultadoD['cli_clave'];?>" autocomplete="off" placeholder="Contraseña" title="Contraseña">
														</div>
													</div>
													
													<div class="control-group">
														<label class="control-label">Clave documentos</label>
														<div class="controls">
															<input type="<?php echo $campoC;?>" class="span4" name="claveDocumentos" value="<?=$resultadoD['cli_clave_documentos'];?>" autocomplete="off" required>
														</div>
													</div>
													

													<div class="control-group">
														<label class="control-label">Nombre (*)</label>
														<div class="controls">
															<input type="text" class="span6" name="nombre" value="<?=$resultadoD['cli_nombre'];?>" style="text-transform:uppercase;" required>
															SIGLA  (Nombre corto)
															<input type="text" class="span4" name="sigla" style="text-transform:uppercase;" value="<?=$resultadoD['cli_sigla'];?>">
														</div>
													</div>

													<div class="control-group">
														<label class="control-label">Email</label>
														<div class="controls">
															<input type="email" class="span6" name="email" value="<?=$resultadoD['cli_email'];?>" style="text-transform:lowercase;">
														</div>
													</div>

													<div class="control-group">
														<label class="control-label">Teléfono</label>
														<div class="controls">
															<input type="text" class="span4" name="telefono" value="<?=$resultadoD['cli_telefono'];?>">
														</div>
													</div>

													<div class="control-group">
														<label class="control-label">Celular</label>
														<div class="controls">
															<input type="text" class="span4" name="celular" value="<?=$resultadoD['cli_celular'];?>" maxlength="10">
															<span style="color:#F03;">Este valor sin puntos ni espacios. (3135912073)</span>
														</div>
													</div>

													<div class="control-group">
														<label class="control-label">Teléfonos complementarios</label>
														<div class="controls">
															<input type="text" class="span8" name="telefonos" value="<?=$resultadoD['cli_telefonos'];?>">
														</div>
													</div>

													<div class="control-group">
														<label class="control-label">Dirección</label>
														<div class="controls">
															<input type="text" class="span4" name="direccion" value="<?=$resultadoD['cli_direccion'];?>">
														</div>
													</div>   
													
													<div class="control-group">
															<label class="control-label">Pais</label>
															<div class="controls">
																<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="pais" onChange="mostrar(this)">
																	<option value=""></option>
																	<?php
																	$service_url = 'https://restcountries.com/v3.1/all';
																	$jsonObject = json_decode(file_get_contents($service_url),true);
																	foreach ($jsonObject as $object){
																	$nombrePais=$object["name"]["common"];
																	?>
																		<option value="<?=$nombrePais;?>"  <?php if($resultadoD['cli_pais']==$nombrePais){echo "selected";}?>><?=$nombrePais;?></option>
																	<?php
																	}
																	?>
																</select>
															</div>
													</div>
													
													<?php if(($resultadoD['cli_pais'] == "Colombia") || ($resultadoD['cli_pais'] == "1122")){ 
														$displayCol="display: block;";
														$displayExtr="display: none;";
													}else{ 
														$displayCol="display: none;";
														$displayExtr="display: block;";
													}?>

													<div id="local" style="<?=$displayCol;?>">
														<div class="control-group">
																<label class="control-label">Ciudad</label>
																<div class="controls">
																	<select data-placeholder="Escoja una opción..." class="span4" tabindex="2" name="ciudad">
																		<option value=""></option>
																		<?php
																		$conOp = $conexionBdAdmin->query("SELECT * FROM localidad_ciudades 
																		INNER JOIN localidad_departamentos ON dep_id=ciu_departamento 
																		ORDER BY ciu_nombre");
																		while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
																		?>
																			<option value="<?=$resOp['ciu_id'];?>" <?php if($resultadoD['cli_ciudad']==$resOp['ciu_id']){echo "selected";}?>><?=$resOp['ciu_nombre'].", ".$resOp['dep_nombre'];?></option>
																		<?php
																		}
																		?>
																	</select>
																</div>
														</div>
													</div>
													
													<div id="extrangero" style="<?=$displayExtr;?>">
														<div class="control-group">
															<label class="control-label">Ciudad</label>
															<div class="controls">
																<input type="text" class="span4" name="ciuExtra" value="<?=$resultadoD['cli_ciudad_extranjera'];?>">
															</div>
														</div>
													</div>

												   <div class="control-group">
														<label class="control-label">Zona</label>
														<div class="controls">
															<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="zona" disabled>
																<option value=""></option>
																<?php
																$conOp = $conexionBdPrincipal->query("SELECT * FROM zonas");
																while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
																?>
																	<option value="<?=$resOp['zon_id'];?>" <?php if($resultadoD['cli_zona']==$resOp['zon_id']){echo "selected";}?>><?=$resOp['zon_nombre'];?></option>
																<?php
																}
																?>
															</select>
														</div>
												   </div>
												   </fieldset>

												   <fieldset class="default">
														<legend>Datos comerciales</legend>
													<div class="control-group">
														<label class="control-label">Estado</label>
														<div class="controls">
															<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="categoria">
																<option value=""></option>
																<option value="1" <?php if($resultadoD['cli_categoria']==1){echo "selected";}?>>Prospecto</option>
																<option value="2" <?php if($resultadoD['cli_categoria']==2){echo "selected";}?>>Cliente</option>
																<option value="3" <?php if($resultadoD['cli_categoria']==3){echo "selected";}?>>Dealer</option>
															</select>
														</div>
												   </div>

												   <div class="control-group">
														<label class="control-label">Nivel</label>
														<div class="controls">
															<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="nivel">
																<option value=""></option>
																<option value="1" <?php if($resultadoD['cli_nivel']==1){echo "selected";}?>>Leads (Seguidor o Suscripor)</option>
																<option value="2" <?php if($resultadoD['cli_nivel']==2){echo "selected";}?>>Interesado (Cotiza o llama)</option>
																<option value="3" <?php if($resultadoD['cli_nivel']==3){echo "selected";}?>>Prospecto (En proceso)</option>
																<option value="4" <?php if($resultadoD['cli_nivel']==4){echo "selected";}?>>Cliente A (Compró 1 vez)</option>
																<option value="5" <?php if($resultadoD['cli_nivel']==5){echo "selected";}?>>Cliente B (Compró 2 veces)</option>
																<option value="6" <?php if($resultadoD['cli_nivel']==6){echo "selected";}?>>Cliente C (Compró 3 o más veces)</option>
															</select>
														</div>
												   </div>
													   
													<div class="control-group">
														<label class="control-label">¿Ha realizado servicios?</label>
														<div class="controls">
															<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="servicios">
																<option value="1">--</option>
																<option value="1" <?php if($resultadoD['cli_servicios']==1){echo "selected";}?>>Aún no</option>
																<option value="2" <?php if($resultadoD['cli_servicios']==2){echo "selected";}?>>1 Vez</option>
																<option value="3" <?php if($resultadoD['cli_servicios']==3){echo "selected";}?>>2 veces</option>
																<option value="4" <?php if($resultadoD['cli_servicios']==4){echo "selected";}?>>3 o más veces</option>
															</select>
														</div>
												   </div>
													   
													  <div class="control-group">
														<label class="control-label">¿Tiene crédito?</label>
														<div class="controls">
															<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="credito">
																<option value="0">--</option>
																<option value="1" <?php if($resultadoD['cli_credito']==1){echo "selected";}?>>SI</option>
																<option value="0" <?php if($resultadoD['cli_credito']=='0'){echo "selected";}?>>NO</option>
															</select>
														</div>
												   </div> 

												   <div class="control-group">
														<label class="control-label">Fecha de registro</label>
														<div class="controls">
															<?=$resultadoD['cli_fecha_registro'];?>
														</div>
													</div>

												   <div class="control-group">
														<label class="control-label">Fecha que se volvió cliente (En caso de que sea cliente)</label>
														<div class="controls">
															<input type="date" class="span4" name="fechaIngreso" value="<?=$resultadoD['cli_fecha_ingreso'];?>">
														</div>
													</div>
													   
													 <div class="control-group">
														<label class="control-label">Fecha Incio (Uso CRM)</label>
														<div class="controls">
															<input type="date" class="span4" name="fechaInicioUso" value="<?=$resultadoD['cli_inicio_uso'];?>" readonly>
														</div>
														 <span style="color: navy;">A partirde esta fecha tiene un año de acceso al CRM.</span>
													</div> 
													   
													<div class="control-group">
														<label class="control-label">Referencia de llegada</label>
														<div class="controls">
															<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="referencia">
																<option value=""></option>
																<?php
																for($i=1; $i<=12; $i++){
																	if($resultadoD['cli_referencia']==$i)echo '<option value="'.$i.'" selected>'.$referenciaLlegada[$i].'</option>';
																	else echo '<option value="'.$i.'">'.$referenciaLlegada[$i].'</option>';	
																}
																?>
															</select>
														</div>
												   </div>   

													<div class="control-group">
														<label class="control-label">Grupos</label>
														<div class="controls">
															<select data-placeholder="Escoja una opción..." class="chzn-select span8" multiple tabindex="2" name="grupos[]">
																<option value=""></option>
																<?php
																$conOp = $conexionBdPrincipal->query("SELECT * FROM dealer");
																while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
																	$consulta = $conexionBdPrincipal->query("SELECT * FROM clientes_categorias WHERE cpcat_cliente='".$resultadoD['cli_id']."' AND cpcat_categoria='".$resOp[0]."'");
																	$numD = $consulta->num_rows;
																?>
																	<option value="<?=$resOp[0];?>" <?php if($numD>0){echo "selected";}?>><?=$resOp[1];?></option>
																<?php
																}
																?>
															</select>
														</div>
												   </div>
													   
													   <div class="control-group">
														<label class="control-label">Saldo disponible</label>
														<div class="controls">
															<input type="text" class="span4" name="saldo" value="<?=$resultadoD['cli_saldo'];?>" maxlength="10">
															<span style="color:#F03;">Este valor sin puntos ni espacios. (10000)</span>
														</div>
													</div>
													   
												   </fieldset>
													
													<fieldset class="default">
														<legend>Asesor asociado</legend>
														<div class="control-group">
														<label class="control-label">Asesor</label>
														<div class="controls">
															<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="asesor">
																<option value=""></option>
																<?php
																$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_bloqueado!=1 ORDER BY usr_nombre");
																while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
																	$conultaAsociacion = $conexionBdPrincipal->query("SELECT * FROM clientes_usuarios WHERE cliu_usuario='".$resOp[0]."' AND cliu_cliente='".$_GET["id"]."'");
																	$asociacion = $conultaAsociacion->num_rows;
																?>
																	<option value="<?=$resOp[0];?>" <?php if($asociacion>0){echo "selected";}?>><?=strtoupper($resOp[4]);?></option>
																<?php
																}
																?>
															</select>
														</div>
												   </div>
														
												   </fieldset> 
												
												   <fieldset class="default">
														<legend>Datos de retiro</legend>

														<div class="control-group">
															<label class="control-label">Retirado</label>
															<div class="controls">
																<select data-placeholder="Escoja una opción..." class="chzn-select span2" tabindex="2" name="retirado">
																	<option value=""></option>
																	<option value="1" <?php if($resultadoD['cli_retirado']==1){echo "selected";}?>>SI</option>
																	<option value="0" <?php if($resultadoD['cli_retirado']!=1){echo "selected";}?>>NO</option>
																</select>
															</div>
														</div>

														<div class="control-group">
															<label class="control-label">Fecha del retiro</label>
															<div class="controls">
																<input type="date" class="span4" name="retiroFecha" value="<?=$resultadoD['cli_fecha_retiro'];?>">
															</div>
														</div>

														<div class="control-group">
															<label class="control-label">Responsables del retiro</label>
															<div class="controls">
																<input type="text" class="span6" name="retiroResponsable" value="<?=$resultadoD['cli_responsable_retiro'];?>">
															</div>
														</div>

														<div class="control-group">
															<label class="control-label">Causa del retiro</label>
															<div class="controls">
																<textarea rows="5" cols="80" style="width: 80%" class="tinymce-simple" name="retiroCausa"><?=$resultadoD['cli_causa_retiro'];?></textarea>
															</div>
														</div>
												   </fieldset> 

													<div class="form-actions">
														<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
														<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
													</div>
												  </form>
										</div>
										
										<div class="tab-pane" id="sucursales">
											<div class="row-fluid">
				<div class="span12">
					
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3>Sucursales</h3>
						</div>
						<div class="widget-container">
							<p><a href="clientes-sucursales-agregar.php?cte=<?=$_GET["id"];?>" class="btn btn-danger" target="_blank"><i class="icon-plus"></i> Agregar sucursal</a></p>
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>Nombre</th>
								<th>Telefono</th>
                                <th>Celular</th>
                                <th>Ciudad</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$consulta = $conexionBdPrincipal->query("SELECT * FROM sucursales
							INNER JOIN clientes ON cli_id=sucu_cliente_principal 
							INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=sucu_ciudad 
							INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
							WHERE sucu_cliente_principal='".$_GET["id"]."' AND cli_id_empresa='".$idEmpresa."'");
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
							?>
							<tr>
								<td><?=$no;?></td>
                                <td><?=$res['sucu_nombre'];?></td>
                                <td><?=$res['sucu_telefono'];?></td>
                                <td><?=$res['sucu_celular'];?></td>
                                <td><?=$res['ciu_nombre'].", ".$res['dep_nombre'];?></td>
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
															<p><a href="clientes-contactos-agregar.php?cte=<?=$_GET["id"];?>" class="btn btn-danger" target="_blank"><i class="icon-plus"></i> Agregar contacto</a></p>
														
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
															$consulta = $conexionBdPrincipal->query("SELECT * FROM contactos 
															INNER JOIN clientes ON cli_id=cont_cliente_principal 
															WHERE cont_cliente_principal='".$_GET["id"]."'");
															$no = 1;
															while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){

																$consultaSucursal = $conexionBdPrincipal->query("SELECT * FROM sucursales 
																WHERE sucu_id='".$res['cont_sucursal']."'");
																$sucursal = mysqli_fetch_array($consultaSucursal, MYSQLI_BOTH);

																$sucursalNombre = isset($sucursal['sucu_nombre']) ? $sucursal['sucu_nombre'] : '[Sin sucursal]';
															?>
															<tr>
																<td><?=$no;?></td>
																<td><?=$res['cont_nombre'];?></td>
																<td><?=$res['cont_telefono'];?></td>
																<td><?=$res['cont_celular'];?></td>
																<td><?=$res['cont_email'];?></td>
																<td><?=$sucursalNombre;?></td>
																<td><h4>
																	<a href="clientes-contactos-editar.php?id=<?=$res[0];?>&cte=<?=$_GET["id"];?>" data-toggle="tooltip" title="Editar" target="_blank"><i class="icon-edit"></i></a>
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
																<a href="clientes-tikets-agregar.php?cte=<?=$_GET["id"];?>" class="btn btn-danger" target="_blank"><i class="icon-plus"></i> Agregar ticket</a>
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
															$consulta = $conexionBdPrincipal->query("SELECT * FROM clientes_tikets
															INNER JOIN clientes ON cli_id=tik_cliente
															INNER JOIN usuarios ON usr_id=tik_usuario_responsable
															WHERE tik_cliente='".$_GET["id"]."'");
															$no = 1;
															while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
																$consultaNumSeg = $conexionBdPrincipal->query("SELECT * FROM cliente_seguimiento WHERE cseg_tiket='".$res['tik_id']."'");
																$numSeg = $consultaNumSeg->num_rows;
																
																

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
																<td><a href="clientes-seguimiento.php?idTK=<?=$res[0];?>" target="_blank"><span class="label label-info"><?=$numSeg;?></span><b></b></td>
																<td><h4>
																	<a href="clientes-tikets-editar.php?id=<?=$res[0];?>" data-toggle="tooltip" title="Editar" target="_blank"><i class="icon-edit"></i></a>
																	<a href="sql.php?id=<?=$res[0];?>&get=24" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
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
															<a href="clientes-seguimiento-agregar.php?cte=<?=$_GET["id"];?>" class="btn btn-danger" target="_blank"><i class="icon-plus"></i> Agregar seguimiento</a>
															</p>
															<table class="table table-striped table-bordered" id="data-table">
															<thead>
															<tr>
																<th>No</th>
																<th>Contacto</th>
																<th>Seguimiento</th>
																<th>#Cotización</th>
																<th>Estado</th>
															</tr>
															</thead>
															<tbody>
															<?php
															$consulta = $conexionBdPrincipal->query("SELECT * FROM cliente_seguimiento
															INNER JOIN clientes ON cli_id=cseg_cliente
															INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
															WHERE cseg_cliente='".$_GET["id"]."'");
															$no = 1;
															while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
																$consultaEncargado = $conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_id='".$res['cseg_usuario_encargado']."'");
																$encargado = mysqli_fetch_array($consultaEncargado, MYSQLI_BOTH);

																$consultaContacto = $conexionBdPrincipal->query("SELECT * FROM contactos WHERE cont_id='".$res['cseg_contacto']."'");
																$contacto = mysqli_fetch_array($consultaContacto, MYSQLI_BOTH);
																if($datosUsuarioActual[3]!=1){
																	$consultaNumZ = $conexionBdPrincipal->query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$res['cli_zona']."'");
																	$numZ = $consultaNumZ->num_rows;
																	if($numZ ==0 ) continue;
																}

																$fondoColor = '';
																if(isset($_GET['seg'])){ 
																	if($res['cseg_id']==$_GET['seg']){$fondoColor = 'style="background:#99DFC6; font-weight:bold;"'; }
																}
																
																

																switch($res['cseg_realizado']){
																	case 1: $html = '<span class="label label-success">Completado</span>'; break;
																	default: $html = '<a href="sql.php?id='.$res['cseg_id'].'&get=28" class="label label-important">Pendiente</a>'; break;
																}
															?>
															<tr>
																<td <?=$fondoColor;?>><?=$no;?></td>
																<td <?=$fondoColor;?>>
																	<?php
																	if(isset($contacto['cont_nombre'])){
																		echo "<b>Nombre</b>:". $contacto['cont_nombre'];
																	}
																		
																	?>
																	<?php 
																	if(isset($res['cont_telefono'])) echo "<br><b>Tel:</b> ". $res['cont_telefono'];
																	?>
																	<?php if(isset($res['cont_email'])) echo "<br><b>Email:</b> ". $res['cont_email'];?>
																	
																	<h4 style="margin-top:10px;">
																			<a href="clientes-seguimiento-editar.php?id=<?=$res[0];?>&cte=<?=$_GET["id"];?>" data-toggle="tooltip" title="Editar" target="_blank"><i class="icon-edit"></i></a>&nbsp;
																			<a href="sql.php?id=<?=$res[0];?>&get=4" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
																	</h4>
																</td>
																<td <?=$fondoColor;?>>
																	<b>Fecha:</b> <?=$res['cseg_fecha_reporte'];?><br>
																	<b>Responsable:</b> <?=$res['usr_nombre'];?><br>
																	<b>Observación:</b><br>
																	<span style="color:#009;"><?=$res['cseg_observacion'];?></span>
																	<p>
																		<h5 style="font-weight:bold;">Próximo contacto</h5>
																		<b>Fecha:</b> <?=$res['cseg_fecha_proximo_contacto'];?><br>
																		<b>Encargado:</b> <?=$encargado['usr_nombre'];?>
																	</p>
																</td>
																<td <?=$fondoColor;?>><?=$res['cseg_cotizacion'];?></td>
																<td <?=$fondoColor;?>><?=$html;?></td>
															</tr>
															<?php $no++;}?>
															</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
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
															<a href="cotizaciones-agregar.php?cte=<?=$_GET["id"];?>" class="btn btn-danger" target="_blank"><i class="icon-plus"></i> Agregar cotización</a>
															</p>
															<table class="table table-striped table-bordered" id="data-table">
															<thead>
															<tr>
																<th>ID</th>
																<th>Fecha Propuesta</th>
																<th>Productos</th>
																<th>Responsable</th>
																<th>Vendedor</th>
																<th></th>
															</tr>
															</thead>
															<tbody>
															<?php
															$consulta = $conexionBdPrincipal->query("SELECT * FROM cotizacion
															INNER JOIN clientes ON cli_id=cotiz_cliente AND cli_id='".$_GET["id"]."'
															INNER JOIN usuarios ON usr_id=cotiz_creador
															");
															$no = 1;
															while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
																$consultaVendedor = $conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_id='".$res['cotiz_vendedor']."'");
																$vendedor = mysqli_fetch_array($consultaVendedor, MYSQLI_BOTH);
																
																$fondoCotiz = '';
																if($res['cotiz_vendida']==1){
																	$fondoCotiz = 'aquamarine';
																}
															?>
															<tr>
																<td style="background-color: <?=$fondoCotiz;?>;"><?=$res['cotiz_id'];?></td>
																<td><?=$res['cotiz_fecha_propuesta'];?></td>
																<td>
																	<?php
																		$productos = $conexionBdPrincipal->query("SELECT * FROM cotizacion_productos
																		INNER JOIN productos ON prod_id=czpp_producto
																		WHERE czpp_cotizacion='".$res['cotiz_id']."'
																		");
																		$i = 1;
																		while($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)){
																			echo "<b>".$i.".</b> ".$prod['prod_nombre'].", ";
																			$i++;
																		}
																	?>

																</td>
																<td><?php if(isset($res['usr_nombre'])) echo strtoupper($res['usr_nombre']);?></td>
																<td><?php if(isset($vendedor['usr_nombre'])) echo strtoupper($vendedor['usr_nombre']);?></td>
																<td>
																	<div class="btn-group">
																		<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span>
																		</button>
																		<ul class="dropdown-menu">
																			<?php if($_SESSION["id"]==$res['cotiz_creador'] or $_SESSION["id"]==$res['cotiz_vendedor'] or $datosUsuarioActual[3]==1){?>
																			<li><a href="cotizaciones-editar.php?id=<?=$res['cotiz_id'];?>#productos"> Editar</a></li>

																			<li><a href="sql.php?id=<?=$res['cotiz_id'];?>&get=22" onClick="if(!confirm('Desea eliminar el registro?')){return false;}">Eliminar</a></li>
																			<?php }?>

																			<li><a href="reportes/formato-cotizacion-1.php?id=<?=$res['cotiz_id'];?>" target="_blank">Imprimir</a></li>

																			<li><a href="sql.php?get=46&id=<?=$res['cotiz_id'];?>" onClick="if(!confirm('Desea replicar este registro?')){return false;}">Replicar</a></li>

																			<li><a href="sql.php?get=48&id=<?= $res['cotiz_id']; ?>" onClick="if(!confirm('Desea generar pedido de esta cotización?')){return false;}">Generar pedido</a></li>

																		</ul>
																	</div>
																</td>
															</tr>
															<?php $no++;}?>
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
															<a href="facturacion-agregar.php?cte=<?=$_GET["id"];?>" class="btn btn-danger" target="_blank"><i class="icon-plus"></i> Agregar factura</a>
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
															$consulta = $conexionBdPrincipal->query("SELECT * FROM facturacion
															INNER JOIN clientes ON cli_id=fact_cliente
															INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
															INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
															WHERE fact_cliente='".$_GET["id"]."' AND fact_id_empresa='".$idEmpresa."'");
															$no = 1;
															while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
																$consultaAbonos = $conexionBdPrincipal->query("SELECT sum(fpab_valor) FROM facturacion_abonos WHERE fpab_factura='".$res['fact_id']."'");
																$abonos = mysqli_fetch_array($consultaAbonos, MYSQLI_BOTH);
								
								
																$impuestos = $res['fact_valor'] * $res['fact_impuestos']/100;
																$retencion = $res['fact_valor'] * $res['fact_retencion']/100;
																$descuento = $res['fact_valor'] * $res['fact_descuento']/100;

																$valorReal = ($res['fact_valor'] + $impuestos) - ($retencion + $descuento);

																$saldoFinal = $valorReal - $abonos[0];

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
                                	<a href="facturacion-editar.php?id=<?=$res['fact_id'];?>" data-toggle="tooltip" title="Editar" target="_blank"><i class="icon-edit"></i></a>&nbsp;
                                    <a href="sql.php?id=<?=$res['fact_id'];?>&get=6" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>&nbsp;
                                    <a href="#" onClick='window.open("facturacion-abonos.php?fact=<?=$res['fact_id'];?>","abonos","width=1200,height=800,menubar=no")' data-toggle="tooltip" title="Abonos"><i class="icon-money"></i></a>&nbsp;
                                    <a href="reportes/formato-factura-1.php?id=<?=$res['fact_id'];?>" data-toggle="tooltip" title="Imprimir factura" target="_blank"><i class="icon-print"></i></a>&nbsp;
                                    <a href="sql.php?get=19&id=<?=$res['fact_id'];?>" data-toggle="tooltip" onClick="if(!confirm('Desea replicar este registro?')){return false;}" title="Replicar factura"><i class="icon-repeat"></i></a>&nbsp;
                                    <?php if($saldoFinal>0){?>
                                    <a href="sql.php?get=26&id=<?=$res['fact_id'];?>" data-toggle="tooltip" onClick="if(!confirm('Desea generar un abono automático por el saldo pendiente de esta factura?')){return false;}" title="Abono automático y saldar factura"><i class="icon-retweet"></i></a>
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
	<?php include("includes/pie.php");?>
</div>
</body>
</html>