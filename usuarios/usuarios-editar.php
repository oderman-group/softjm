<?php 
include("sesion.php");

$idPagina = 4;

include("includes/verificar-paginas.php");
include("includes/head.php");
$consulta=$conexionBdAdmin->query("SELECT u.*, GROUP_CONCAT(r.utipo_id) AS roles
FROM ".MAINBD.".usuarios AS u
LEFT JOIN usuarios_roles AS ru ON u.usr_id = ru.upr_id_usuario 
LEFT JOIN ".MAINBD.".usuarios_tipos AS r ON ru.upr_id_rol  = r.utipo_id 
WHERE u.usr_id = '".$_GET["id"]."'
GROUP BY u.usr_id;");
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
<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>
<?php include("includes/funciones-js.php");?>
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
						<li><a href="usuarios.php">Usuarios</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>


            
            <p>
							<?php
								if (Modulos::validarRol([3], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
									echo '<a href="usuarios-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>&nbsp;';
								}
								if (Modulos::validarRol([176], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
									echo '<a href="usuarios-editar-zonas.php?id='.$_GET["id"].'" class="btn btn-info"><i class="icon-edit"></i> Editar zonas</a>';
								}
							?>           	

            </p>
            
            <?php include("includes/notificaciones.php");?>
			<span style="color: blue; font-size: 15px;" id="respuestaUsuario"></span>
			<div class="row-fluid">
				<div class="span8">

					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							
							<img src="files/fotos/<?=$resultadoD['usr_foto'];?>" width="100">

							<form class="form-horizontal" method="post" action="bd_update/actualizar-usuarios.php" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">

                            <div class="control-group">
                                        <label class="control-label">Foto de perfil
										    <button class="tooltipp">Elija la imagen desee para su empresa.</button>
                                            <i class="fa-solid fa-circle-question"></i>
										</label>
                                        <div class="controls">
                                            <input type="file" class="span4" name="foto" multiple>
                                        </div>
                                    </div>
                            	
                                <div class="control-group">
									<label class="control-label">Usuario</label>
									<div class="controls">
								    <?php
                      $usuarioCompleto = $resultadoD['usr_login'];
                      list($nombreUsuario, $dominioOrganizacion) = explode('@', $usuarioCompleto, 2);
                    ?>
                      <input type="text" class="span4" name="usuario" id="usuario" pattern="[A-Za-z0-9]+" data-id-usuario="<?=$_GET["id"];?>" oninput="validarUsuario(this)" autofocus  value="<?= $nombreUsuario; ?>" required>
                      <input type="text" class="span3" value="<?=  '@' . $dominioOrganizacion; ?>" readonly
                        name="dominio">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" class="span8" name="nombre" value="<?=$resultadoD['usr_nombre'];?>">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Email</label>
									<div class="controls">
										<input type="email" class="span4" name="email" value="<?=$resultadoD['usr_email'];?>" data-id-usuario="<?=$_GET["id"];?>" oninput="validarEmail(this)">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Tipo de usuario
									        <button class="tooltipp">Elija el rol al cual corespende el usuario.</button>
                                            <i class="fa-solid fa-circle-question"></i>
									</label>
									<input type="hidden" id="hasRolAdmin" name="hasRolAdmin" value="0">
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="tipoU[]" multiple id="tipoU">
											<?php
													$roles = explode(',', $resultadoD['roles']);
													$conOp = $conexionBdPrincipal->query("SELECT * FROM usuarios_tipos  WHERE utipo_id_empresa =  '".$_SESSION["dataAdicional"]["id_empresa"]."'");
													while ($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)) {
														$selected = (is_array($roles) && in_array($resOp[0], $roles)) ? 'selected' : '';

														if($resOp[0] == ADMIN && $selected == 'selected') {
															echo '<script>document.getElementById("hasRolAdmin").value=1;</script>';
														}

												?>
															<option value="<?=$resOp[0];?>" <?=$selected;?>><?=$resOp[1];?></option>
												<?php
													}
												?>
											</select>

											<script>
												// Variable global para controlar si el alert ya se mostró
												let hasRolAdmin = document.getElementById("hasRolAdmin").value;
												let alertPara1YaMostrado = hasRolAdmin == 1 ? true : false;

												console.log(hasRolAdmin, alertPara1YaMostrado);

												$(document).ready(function() {

													// Inicializa Chosen.js en el select
													// Esto es crucial para que la librería funcione si no lo has hecho ya.
													$('#tipoU').chosen();

													// Escucha el evento 'change' en el elemento select original
													$('#tipoU').on('change', function(event) {
														// 'this' se refiere al elemento select en el contexto de jQuery
														const valoresSeleccionados = $(this).val();

														// Comprueba si el valor '1' está en el array Y si el alert no se ha mostrado
														if (valoresSeleccionados && valoresSeleccionados.includes('1') && !alertPara1YaMostrado) {
															alert("¡Este rol Admin Principal tiene demasiados privilegios en la plataforma. Tenga presente a qué usuarios se lo asigna.!");
															alertPara1YaMostrado = true; // Activa la bandera
														}
														
														// Si el valor '1' ya no está seleccionado, restablece la bandera
														else if (valoresSeleccionados && !valoresSeleccionados.includes('1') && alertPara1YaMostrado) {
															alertPara1YaMostrado = false;
														}

														console.log('Valores seleccionados en este momento:', valoresSeleccionados);
													});

												});
											</script>
                                    </div>
                               </div>

							   <div class="control-group">
									<label class="control-label">A qué sucursal pertenece?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="sucursal">
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM sucursales_propias WHERE sucp_id_empresa =  '".$_SESSION["dataAdicional"]["id_empresa"]."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['usr_sucursal']==$resOp[0]) echo "selected";?>><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Bloqueado
									        <button class="tooltipp">Indica si el usuario está o no bloqueado.</button>
                                            <i class="fa-solid fa-circle-question"></i>
									</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="bloqueado">
											<option value="0"></option>
                                            	<option value="1" <?php if($resultadoD['usr_bloqueado']==1) echo "selected";?>>SI</option>
												<option value="0" <?php if($resultadoD['usr_bloqueado']=='0') echo "selected";?>>NO</option>
                                    	</select>
                                    </div>
                               </div>

                               
								<div class="control-group">
									<label class="control-label">Área</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="area">
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdPrincipal->query("SELECT * FROM areas WHERE ar_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['usr_area']==$resOp[0]) echo "selected";?>><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                              
								
                              
                               <div class="control-group">
									<label class="control-label">Ciudad</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="ciudad">
											<option value=""></option>
                                            <?php
											$conOp = $conexionBdAdmin->query("SELECT * FROM localidad_ciudades INNER JOIN localidad_departamentos ON dep_id=ciu_departamento ORDER BY ciu_nombre");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp['ciu_id'];?>" <?php if($resultadoD['usr_ciudad']==$resOp['ciu_id']){echo "selected";}?>><?=$resOp['ciu_nombre'].", ".$resOp['dep_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>


                                



								 <div class="control-group">
									<label class="control-label">Intentos de acceso fallidos
									        <button class="tooltipp">Escoja el numero de intentos permitidos.</button>
                                            <i class="fa-solid fa-circle-question"></i>
									</label>
									<div class="controls">
										<input type="text" class="span2" name="fallidos" value="<?=$resultadoD['usr_intentos_fallidos'];?>">
									</div>
								</div>

								<hr>

								<div class="control-group">
									<label class="control-label">Meta de ventas
									        <button class="tooltipp">Metas a cumplir para el proximo mes.</button>
                                            <i class="fa-solid fa-circle-question"></i>
									</label>
									<div class="controls">
										<input type="text" class="span4" name="metaVentas" value="<?=$resultadoD['usr_meta_ventas'];?>">
									</div>
								</div>


                               
								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button id="btnEnviar" type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
								</div>
							</form>
						</div>
					</div>
                </div>


                <div class="span4">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> Cambiar contraseña</h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_update/actualizar-claves-usuarios.php">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
                                
                                
                                <div class="control-group">
									<label class="control-label">Contraseña</label>
									<div class="controls">
										<input type="text" class="span12" name="clave" required>
									</div>
								</div>
                               
								<div class="form-actions">
                                    <button type="submit" class="btn btn-info"><i class="icon-save"></i> Cambiar contraseña</button>
								</div>
							</form>
						</div>
					</div>
                </div>
                
			</div>

		</div>
	</div>
	<script src="js/Usuarios.js" ></script>
	<?php include("includes/pie.php");?>
</div>
</body>
</html>
