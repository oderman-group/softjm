<?php 
include("sesion.php");

$idPagina = 249;

include("includes/verificar-paginas.php");
include("includes/head.php");

$_GET["idTK"]=!empty($_GET["idTK"])?$_GET["idTK"]:"";

$consultaRemision=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones 
INNER JOIN clientes ON cli_id=rem_cliente
INNER JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
INNER JOIN usuarios ON usr_id=rem_asesor
WHERE rem_id='".$_GET["id"]."' AND rem_id_empresa='".$idEmpresa."'");
$remision = mysqli_fetch_array($consultaRemision, MYSQLI_BOTH);

$consultaContacto=mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='".$remision["rem_contacto"]."'");
$contacto = mysqli_fetch_array($consultaContacto, MYSQLI_BOTH);
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
						<li><a href="remisiones-editar.php?id=<?=$_GET["id"];?>">Editar remisiones</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span8">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<ul class="list-unstyled m-t-40">
								<?php	
								$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones_seguimiento
								INNER JOIN usuarios ON usr_id=remseg_usuario
								WHERE remseg_id_remisiones='".$_GET["id"]."'
								ORDER BY remseg_id DESC
								");
								$conRegistros = 1;
								while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
									$html = '<a href="cliente-seguimiento-actualizar.php?id='.$resultado['remseg_id'].'&idTK='.$_GET["idTK"].'" class="label label-warning">No notificado</a>';
									if($resultado['remseg_notificar_cliente']==1){
										$html = '<span class="label label-success">Notificado</span>';
									}
									switch($resultado['remseg_visto_cliente']){
										case 0: $estado = 'No revisado'; $color='red'; break;
										case 1: $estado = 'Visto ('.$resultado['remseg_fecha_visto'].')'; $color='blue'; break;
									}
								?>
								<li class="media">
									<img class="m-r-15" src="../v2.0/assets/images/users/2.jpg" width="60" alt="<?=$resultado['usr_nombre'];?>">
									<div class="media-body">
										<h5 class="mt-0 mb-1" style="font-weight: bold;"><?=$resultado['usr_nombre'];?></h5>
										<span style="color: darkgray; font-size: 10px;"><?=$resultado['remseg_fecha'];?></span>
										<div class="dropdown-divider"></div>
										<?=$resultado['remseg_comentario'];?>
										
										<p><?=$html;?></p>
										
										<?php if($resultado['remseg_notificar_cliente']==1){?>
											<p style="font-size: 9px; color:<?=$color;?>; font-weight:bold;"><?=$estado;?></p>
										<?php }?>
										
										<?php if($resultado['remseg_archivo']!=""){?>
											<p><a href="files/adjuntos/<?=$resultado['remseg_archivo'];?>" style="text-decoration: underline;" target="_blank">Descargar Archivo</a></p>
										<?php }?>
										
									</div>
								</li>
								<hr>
								<?php }?>
								
							</ul>
							<?php if($remision['rem_estado']==1){?>
								<form class="form-horizontal" method="post" action="bd_create/seguimiento-equipo-agregar.php" enctype="multipart/form-data">
									<input type="hidden" name="idSql" value="49">
									<input type="hidden" name="id" value="<?=$_GET["id"];?>">
									<input type="hidden" name="cliente" value="<?=$remision["rem_cliente"];?>">
									<input type="hidden" name="contacto" value="<?=$remision["rem_contacto"];?>">
									
									<h5>Observaciones</h5>
									<div class="row">
										<div class="control-group">
											<label class="control-label">Escoja una ya existente</label>
											<div class="controls">
												<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="obsLista">
													<option value="">--</option>
													<?php
													$observaciones = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones_seguimiento 
													GROUP BY remseg_comentario
													ORDER BY remseg_comentario
													");		 
													while($obs = mysqli_fetch_array($observaciones, MYSQLI_BOTH)){
													?>
														<option value="<?=$obs['remseg_comentario'];?>"><?=$obs['remseg_comentario'];?></option>
													<?php
													}
													?>	
												</select>
											</div>
										</div>

										<div class="control-group">
											<label class="control-label">O Escriba la observación</label>
											<div class="controls">
												<textarea rows="5" cols="80" style="width: 66%;" class="tinymce-simple" name="observaciones" placeholder="Escriba la observación..."></textarea>
											</div>
										</div>
									
										<div class="control-group">
											<label class="control-label">Archivo</label>
											<div class="controls">
												<input type="file" class="span8" name="archivo">
											</div>
										</div>
									
										<div class="control-group">
											<label class="control-label">Notificar al cliente</label>
											<div class="controls">											
												<input type="checkbox" id="customCheck3" value="1" name="notfCliente">
											</div>
										</div>
									</div>

									<div class="form-actions">
										<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
										<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									</div>
								</form>
							<?php }?>
						</div>
					</div>
				</div>

				<div class="span4">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3><?=$remision['rem_equipo'];?></h3>
						</div>
						<div class="widget-container">
							<div class="control-group">
								<label class="control-label"><b>Referencia:</b></label>
								<div class="controls" style="margin-left: 20px;">											
									<?=$remision['rem_referencia'];?>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label"><b>Serial:</b></label>
								<div class="controls" style="margin-left: 20px;">											
									<?=$remision['rem_serial'];?>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label"><b>Estado:</b></label>
								<div class="controls" style="margin-left: 20px;">											
									<?=$estadosRemision[$remision['rem_estado']];?>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label"><b>Asesor:</b></label>
								<div class="controls" style="margin-left: 20px;">											
									<?=$remision['usr_nombre'];?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="span4">
					<div class="content-widgets gray">
						<div class="widget-container" style="text-align: center;">
							<h4><?=$remision['cli_nombre'];?></h4>
                                <div class="profile-pic m-b-20 m-t-20">
                                    <img src="../v2.0/assets/images/users/5.jpg" width="150" style="border-radius: 70px;" alt="user">
                                    <h4 class="m-t-20 m-b-0"><?=$remision['ciu_nombre'].", ".$remision['dep_nombre'];?></h4>
                                    <a href="mailto:<?=$remision['cli_email'];?>"><?=$remision['cli_email'];?></a><br>
									<?=$remision['cli_telefono'];?>
                                </div>
								<hr>
								<h5>
									<?php
									// Assuming $contacto is an array and you want to uppercase the 'cont_nombre' value
									if (isset($contacto['cont_nombre']) && $contacto['cont_nombre'] !== null) {
										$uppercasedNombre = strtoupper($contacto['cont_nombre']);
										echo '<h5>' . $uppercasedNombre . '</h5>';
									} else {
										// Handle the case where $contacto['cont_nombre'] is not set or is null
										// You might set a default value, display an error message, or take appropriate action
									}
									?>
								</h5>
								<p>
									<?=$contacto['cont_celular'];?><br>
									<?=$contacto['cont_email'];?><br>
								</p>
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