<?php 
include("sesion.php");

$idPagina = 242;

include("includes/verificar-paginas.php");
include("includes/head.php");
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
include("includes/funciones-js.php");
include("includes/texto-editor.php");
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
						<li><a href="remisiones.php">Remisiones</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="bd_create/remisiones-guardar.php" enctype="multipart/form-data">
								<h3>ESCOJA UN CLIENTE EXISTENTE  Ó</h3>
								<script type="application/javascript">
									function clientes(dato){
										var id = dato.value;
										location.href = "remisiones-agregar.php?cte="+id;
									}
								</script>
								<div class="row">
									<div class="control-group">
										<label class="control-label">Cliente</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente" onChange="clientes(this)">
												<option value="">--</option>
													<?php
													$consultaSelect = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id_empresa='".$idEmpresa."'");
													while($datosSelect = mysqli_fetch_array($consultaSelect, MYSQLI_BOTH)){
														//Solo Vendedores externos
														if($datosUsuarioActual[3] == 14){
															$consultaZonas=mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$datosSelect['cli_zona']."'");
															$numZ = mysqli_num_rows($consultaZonas);
															if($numZ==0) continue;
														}
													?>
													<option value="<?=$datosSelect[0];?>" <?php if($_GET['cte']==$datosSelect[0]){echo "selected";} ?>><?=strtoupper($datosSelect['cli_nombre']);?></option>
													<?php }?>
											</select>
										</div>
									</div>
									<?php 
										if(!empty($_GET["cte"])){
									?>
									<div class="control-group">
										<label class="control-label">Contactos</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="contacto">
												<option value="">--</option>
												<?php
												$consultaSelect = mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_cliente_principal='".$_GET["cte"]."'");
												while($datosSelect = mysqli_fetch_array($consultaSelect, MYSQLI_BOTH)){
												?>
												<option value="<?=$datosSelect[0];?>" <?php if($_GET['contacto']==$datosSelect[0]){echo "selected";} ?>><?=strtoupper($datosSelect['cont_nombre']);?></option>
												<?php }?>
											</select>
										</div>
									</div>
									<?php }?>
								</div>

								<?php 
									if(empty($_GET["cte"])){
								?>
								<hr>
								<h3>CREE UN NUEVO CLIENTE</h3>
								<div class="row">
									<div class="control-group">
										<label class="control-label">NIT</label>
										<div class="controls">
											<input type="text" class="span8" name="usuarioCliente">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Ciudad</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="ciudadCliente">
												<option value="">--</option>
												<?php
												$conOp = mysqli_query($conexionBdAdmin,"SELECT * FROM localidad_ciudades 
												INNER JOIN localidad_departamentos ON dep_id=ciu_departamento 
												ORDER BY ciu_nombre");
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
												?>
													<option value="<?=$resOp['ciu_id'];?>"><?=$resOp['ciu_nombre'].", ".$resOp['dep_nombre'];?></option>
												<?php
												}
												?>
											</select>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">Nombre</label>
										<div class="controls">
											<input type="text" class="span8" name="nombreCliente">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Email</label>
										<div class="controls">
											<input type="text" class="span8" name="emailCliente">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Celular</label>
										<div class="controls">
											<input type="text" class="span8" name="celularCliente">
										</div>
									</div>
								</div>
								<?php }?>
								
								<hr>
								<h4>CONTACTO NUEVO (Si no existe)</h4>
								<div class="row">
									<div class="control-group">
										<label class="control-label">Nombre</label>
										<div class="controls">
											<input type="text" class="span8" name="nombreContacto">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Email</label>
										<div class="controls">
											<input type="text" class="span8" name="emailContacto">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Celular</label>
										<div class="controls">
											<input type="text" class="span8" name="celularContacto">
										</div>
									</div>
								</div>

								<hr>
								<h4 class="card-title">Datos básicos</h4>
								<div class="row">
									<div class="control-group">
										<label class="control-label">Tipo de equipo</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="tipoEquipo">
												<option value="">--</option>
												<option value="1">Estación total</option>
												<option value="2">Teodolito</option>
												<option value="3">Nivel</option>
												
												<option value="4">GPS</option>
												<option value="5">Nivel digital</option>
												<option value="6">Distanciómetro</option>
												<option value="7">Nivel laser</option>
												<option value="8">Semi-estación</option>
												<option value="9">Colector</option>
												<option value="10">Brújula</option>
												<option value="11">Bastón</option>
												<option value="12">Trípode</option>
												<option value="13">Prisma</option>
												<option value="14">Batería</option>
												<option value="15">Radio</option>
												<option value="16">Estuche</option>
												<option value="17">Drone</option>
												<option value="18">MATENIMIENTO GENERAL DRON</option>
											</select>
										</div>
									</div>	
									
									<div class="control-group">
										<label class="control-label">Referencia</label>
										<div class="controls">
											<input type="text" class="span8" name="referencia">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Nuevo o usado?</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="tiposEquipos" required>
												<option value="">--</option>
												<option value="1">Nuevo</option>
												<option value="2">Usado</option>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Marca</label>
										<div class="controls">
											<input type="text" class="span8" name="marca">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Serial</label>
										<div class="controls">
											<input type="text" class="span8" name="serial">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Precisión angular "</label>
										<div class="controls">
											<input type="text" class="span8" name="pAngular" maxlength="1">
										</div>
									</div>	
									
									<div class="control-group">
										<label class="control-label">Precisión a distancia</label>
										<div class="controls">
											<input type="text" class="span8" name="pDistancia" value="2mm + 2ppm">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Tiempo de entrega</label>
										<div class="controls">
											<input type="text" class="span8" name="tiempoEntrega" value="DE 1 A 3 DÍAS">
										</div>
									</div>	
									
									<div class="control-group">
										<label class="control-label">Días para reclamar el equipo</label>
										<div class="controls">
											<input type="text" class="span8" name="tiempoReclamar" value="20">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Vigencia cerificado (meses)</label>
										<div class="controls">
											<input type="text" class="span8" name="vigenciaCerificado" value="6">
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">DETALLES</label>
										<div class="controls">
											<textarea rows="7" style="width: 66%;" class="tinymce-simple" name="detalles" placeholder="Escriba los detalles..."></textarea>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">OBSERVACIONES DE ENTRADA</label>
										<div class="controls">
											<textarea rows="7" style="width: 66%;" class="tinymce-simple" name="descripcion" placeholder="Observaciones de entrada"></textarea>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">OBSERVACIONES DE SALIDA</label>
										<div class="controls">
											<textarea rows="7" style="width: 66%;" class="tinymce-simple" name="obsSalida" placeholder="Observaciones de salida"></textarea>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="control-group">
										<label class="control-label">Servicios</label>
										<div class="controls">
											<select  data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" multiple="multiple" name="servicios[]">
												<?php
												$consultaSelect = mysqli_query($conexionBdPrincipal,"SELECT * FROM servicios WHERE serv_id_empresa='".$idEmpresa."'");
												while($datosSelect = mysqli_fetch_array($consultaSelect, MYSQLI_BOTH)){
												?>
												<option value="<?=$datosSelect[0];?>"><?=strtoupper($datosSelect['serv_nombre']);?></option>
												<?php }?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Imagen</label>
										<div class="controls">
											<input type="file" class="span8" name="imagen" >
										</div>
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
		</div>
	</div>
	<?php include("includes/pie.php");?>
</div>
</body>
</html>