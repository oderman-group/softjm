<?php 
include("sesion.php");

$idPagina = 13;
$paginaActual['pag_nombre'] = "Agregar Seguimiento de clientes";

include("includes/verificar-paginas.php");
include("includes/head.php");

if(isset($_GET["idTK"]) and is_numeric($_GET["idTK"])){
	$consultaTikets=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_tikets WHERE tik_id='".$_GET["idTK"]."'");
	$tiket = mysqli_fetch_array($consultaTikets, MYSQLI_BOTH);
	$tiketID = $_GET["idTK"];
	$cliente = $tiket["tik_cliente"];
	$tipoSeguimiento = $tiket["tik_tipo_tiket"];
}elseif(isset($_GET["cte"]) and is_numeric($_GET["cte"])){
	$tiketID = ""; // vacío porque lo vamos a crear
	$cliente = $_GET["cte"];
	$tipoSeguimiento = 1; //Comercial por defecto
}else{
	//Lo devuelve a los clientes
	echo '<script type="text/javascript">window.location.href="clientes.php?msg=9";</script>';
	exit();
}
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
    
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="clientes-seguimiento.php?idTK=<?=$_GET["idTK"];?>&cte=<?=$_GET["cte"];?>">Seguimiento de clientes</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			<?php include("includes/notificaciones.php");?>
            <div class="row-fluid">
				
				<div class="span3">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> Ticket</h3>
							<?php
							$consultaInfoTikets=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_tikets
							INNER JOIN clientes ON cli_id=tik_cliente
							INNER JOIN usuarios ON usr_id=tik_usuario_responsable
							WHERE tik_id='".$_GET["idTK"]."'");
							$infoTicket = mysqli_fetch_array($consultaInfoTikets, MYSQLI_BOTH);
							
							?>
						</div>
						<div class="widget-container" style="font-size: 10px;">
							
							<div class="control-group">
								<label class="control-label" style="font-weight: bold;">Cliente</label>
								<div class="controls">
									<?=$infoTicket['cli_nombre'];?>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" style="font-weight: bold;">Tipo Ticket</label>
								<div class="controls">
									<?=$tipoTicket[$infoTicket['tik_tipo_tiket']];?>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" style="font-weight: bold;">Asunto principal</label>
								<div class="controls">
									<?=$infoTicket['tik_asunto_principal'];?>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" style="font-weight: bold;">Fecha inicio</label>
								<div class="controls">
									<?=$infoTicket['tik_fecha_creacion'];?>
								</div>
							</div>
							
							<?php if($infoTicket['tik_tipo_tiket']!=3){?>
							<div class="control-group">
								<label class="control-label" style="font-weight: bold;">Valor</label>
								<?php
									$valor=0;
									if(!empty($infoTicket['tik_valor']) AND $infoTicket['tik_valor']>0){
										$valor=$infoTicket['tik_valor'];
									}
								?>
								<div class="controls">
									$<?=number_format($valor,0,",",".");?>
								</div>
							</div>
							
							<div class="control-group">
									<label class="control-label" style="font-weight: bold;">Etapa</label>
									<div class="controls">
										
                                            <?php
											for($i=1; $i<=6; $i++){
												
												if($infoTicket['tik_etapa']==$i) {echo '<span style="color:green; font-weight:bold; font-size:13px;">'.$opcionesEtapa[$i].'</span><br>';}
												
												else {echo '<a href="bd_update/cliente-tikets-actualizar.php?get=41&idtk='.$infoTicket['tik_id'].'&etapa='.$i.'">'.$opcionesEtapa[$i].'</a><br>';}
											}
											?>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label" style="font-weight: bold;">Tipo negocio</label>
									<div class="controls">
									
                                            <?php
											for($i=1; $i<=3; $i++){
												if($infoTicket['tik_tipo_negocio']==$i)echo $opcionesTipoNegocio[$i];	
											}
											?>
                                    	
                                    </div>
                               </div>
									
								<div class="control-group">
									<label class="control-label" style="font-weight: bold;">Origen del negocio</label>
									<div class="controls">
										
                                            <?php
											for($i=1; $i<=8; $i++){
												if($infoTicket['tik_origen_negocio']==$i)echo $opcionesOrigenNegocio[$i];	
											}
											?>
                                    </div>
                               </div>
							<?php }?>
							
							<div class="control-group">
								<label class="control-label" style="font-weight: bold;">Responsable</label>
								<div class="controls">
									<?=$infoTicket['usr_nombre'];?>
								</div>
							</div>
							
							<div align="center" style="padding: 5px;">
									<a href="clientes-tikets-editar.php?id=<?=$infoTicket['tik_id'];?>" class="btn btn-primary">Editar ticket</a>
							</div>
							
						</div>
					</div>
				</div>
				
				<div class="span9">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_create/clientes-seguimiento-guardar.php" enctype="multipart/form-data">
                            
                            <input type="hidden" name="idTK" value="<?=$tiketID;?>">
                            <input type="hidden" name="tipoS" value="<?=$tipoSeguimiento;?>">
                            <input type="hidden" name="cliente" value="<?=$cliente;?>">
                            
                               
                               <div class="control-group">
									<label class="control-label">Contacto</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="contacto" required>
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_cliente_principal='".$cliente."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp['cont_nombre']." (".$resOp['cont_email']." - ".$resOp['cont_telefono'].")";?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                                    <a href="#" onClick='window.open("clientes-contactos-agregar.php?cte=<?=$cliente;?>","contactos","width=1200,height=800,menubar=no")' class="btn btn-danger"><i class="icon-plus"></i> Agregar contactos</a>
                                    <p style="margin-top:10px; font-weight:bold;">Cuando termine de crear el contacto, cierre la ventana emergente y actualice esta pantalla (F5)</p>
                               </div>
								
								<?php if($tiketID==""){?>
								   <div class="control-group">
									<label class="control-label"><b>¿Asociar a un Ticket ya existente?</b></label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="tiketCreado">
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_tikets WHERE tik_cliente='".$cliente."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp['tik_asunto_principal'];?></option>
                                            <?php
											}
											?>
                                    	</select>
										<span style="color:#009;">Opcional.</span>
                                    </div>
									   
                               	  </div>
								  <?php }?>
                            	
                                <div class="control-group">
									<label class="control-label">Fecha del contacto</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaContacto" value="<?=date("Y-m-d");?>" readonly>
                                        <span style="color:#009;">Esta fecha es la de HOY y se guardará automáticamente.</span>
									</div>
								</div>
                                
								<div class="control-group">
									<label class="control-label">¿Cómo fue el contacto?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="formaContacto">
											<option value="1"></option>
                                            <?php
											$opciones = array("","La empresa contactó al cliente","El cliente contactó  a la empresa");
											for($i=1; $i<=2; $i++){
												if($resultadoD['cseg_canal_proximo_contacto']==$i)echo '<option value="'.$i.'" selected>'.$opciones[$i].'</option>';
												else echo '<option value="'.$i.'">'.$opciones[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
                                <div class="control-group">
									<label class="control-label">Canal de contacto</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="canal">
											<option value="4"></option>
                                            <?php
											$opciones = array("","Facebook","WhatsApp","Fijo","Celular","Personal","Skype","Otro","Correo", "Sitio Web");
											for($i=1; $i<=9; $i++){
												if($resultadoD['tik_canal']==$i)echo '<option value="'.$i.'" selected>'.$opciones[$i].'</option>';
												else echo '<option value="'.$i.'">'.$opciones[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                                
                               
                                <div class="control-group">
									<label class="control-label">Observaciones</label>
									<div class="controls">
										<textarea name="observaciones" style="width: 80%"></textarea>
									</div>
								</div>
                                
								<?php if($infoTicket['tik_tipo_tiket']!=3){?>
                                <div class="control-group">
									<label class="control-label">¿Se consiguió datos?</label>
									<div class="controls">
                                        <input type="checkbox" value="1" name="datos">
										<span style="color:#00078A;">Para llamadas de mercadeo</span>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">¿Hubo cotización?</label>
									<div class="controls">
                                        <input type="checkbox" value="1" name="cotizo">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">¿Hubo venta?</label>
									<div class="controls">
                                        <input type="checkbox" value="1" name="vendio">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label"># Cotización</label>
									<div class="controls">
										<input type="text" class="span4" name="cotizacion" style="font-weight:bold;">
									</div>
								</div>

								<?php
								include_once(RUTA_PROYECTO."/usuarios/class/Api/JmEquipos.php");
								$data = Api_JmEquipos::getData(Api_JmEquipos::JM_URL_PORTAFOLIOS);
								?>
								
								<div class="control-group">
                                        <label class="control-label">Enviar portafolios</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja varias opciones..." class="chzn-select span6" multiple tabindex="2" name="portafolios[]">
												<?php foreach($data['data'] as $portafolio) {?>
                                                    <option value="<?=$portafolio['cata_archivo'];?>"><?=$portafolio['cata_nombre'];?></option>
                                                <?php }?>

												<?php if($_SESSION["bd"]=='odermancom_orioncrm_exacta'){?>
                                                    <option value="6">Portafolio Exacta Ing.</option>
                                                <?php }?>
                                            </select>
                                        </div>
                                   </div>
								<?php }?>
								
								<div class="control-group">
									<label class="control-label">Archivo</label>
									<div class="controls">
										<input type="file" class="span4" name="archivo" style="font-weight:bold;">
									</div>
								</div>
                                
								<fieldset class="default">
								<legend>Próximo contacto</legend>

                                <div class="control-group">
									<label class="control-label">Fecha próximo contacto</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaPC">
										<a href="calendario.php?id=<?=$_SESSION["id"];?>" target="_blank" style="color:#009; text-decoration: underline;"><i class="icon icon-calendar"></i> Ver mi calendario</a>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Medio de contacto</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="canalPC" required>
											<option value="3"></option>
                                            <?php
											$opciones = array("","WhatsApp","Fijo","Celular","Visitar al cliente","El cliente me visita","Skype", "Otro","Correo","Sitio Web");
											for($i=1; $i<=9; $i++){
												if($resultadoD['cseg_canal_proximo_contacto']==$i)echo '<option value="'.$i.'" selected>'.$opciones[$i].'</option>';
												else echo '<option value="'.$i.'">'.$opciones[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                                
                                <div class="control-group">
									<label class="control-label">Asunto a tratar</label>
									<div class="controls">
                                        <textarea name="asunto" style="width: 80%"></textarea>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Encargado del próximo contacto</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="encargado[]" multiple>
											<option value="0"></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_bloqueado!=1 AND usr_id_empresa='".$idEmpresa."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
											<option value="<?=$resOp['usr_id'];?>"><?=$resOp['usr_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
							</fieldset>	
								
								<fieldset class="default">
								<legend>Complementario</legend>
								<div class="control-group">
									<label class="control-label">Cerrar ticket</label>
									<div class="controls">
                                        <input type="checkbox" value="1" name="cerrarTK">
                                        <span style="color:navy;">Este se toma como el último seguimiento y el ticket quedará cerrado.</span>
									</div>
								</div>
                               
                               <div class="control-group">
									<label class="control-label">Notificar de inmediato al encargado</label>
									<div class="controls">
                                        <input type="checkbox" value="1" name="notf">
                                        <span style="color:#F03;">Llegará una notificación inmediata al encargado</span>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Notificar al cliente</label>
									<div class="controls">
                                        <input type="checkbox" value="1" name="notfCliente">
                                        <span style="color:#00078A;">También llegará una notificación inmediata al cliente</span>
									</div>
								</div>
								</fieldset>
                               
								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
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
