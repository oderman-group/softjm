<?php include("sesion.php");?>
<?php
$idPagina = 14;
$paginaActual['pag_nombre'] = "Editar Seguimiento de clientes";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<?php
$tiket = mysql_fetch_array(mysql_query("SELECT * FROM clientes_tikets WHERE tik_id='".$_GET["idTK"]."'",$conexion));
$resultadoD = mysql_fetch_array(mysql_query("SELECT * FROM cliente_seguimiento WHERE cseg_id='".$_GET["id"]."'",$conexion));
$cliente = mysql_fetch_array(mysql_query("SELECT * FROM clientes WHERE cli_id='".$resultadoD["cseg_cliente"]."'",$conexion));
?>
<!-- styles -->

<!--[if IE 7]>
<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
<![endif]-->
<link href="css/chosen.css" rel="stylesheet">


<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->

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
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?>: <?=$cliente["cli_nombre"];?></h3>
						
                        <ul class="top-right-toolbar">
							<li><a data-toggle="dropdown" class="dropdown-toggle blue-violate" href="#" title="Users"><i class="icon-user"></i></a>
							</li>
							<li><a href="#" class="green" title="Upload"><i class=" icon-upload-alt"></i></a></li>
							<li><a href="#" class="bondi-blue" title="Settings"><i class="icon-cogs"></i></a></li>
						</ul>
                        
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
							$infoTicket = mysql_fetch_array(mysql_query("SELECT * FROM clientes_tikets
							INNER JOIN clientes ON cli_id=tik_cliente
							INNER JOIN usuarios ON usr_id=tik_usuario_responsable
							WHERE tik_id='".$resultadoD["cseg_tiket"]."'",$conexion));
							
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
								<div class="controls">
									$<?=number_format($infoTicket['tik_valor'],0,",",".");?>
								</div>
							</div>
							
							<div class="control-group">
									<label class="control-label" style="font-weight: bold;">Etapa</label>
									<div class="controls">
										
                                            <?php
											$opciones = array("N/A","En progreso","En espera","Propuesta/Cotización","Negociación/Revisión","Cerrado y ganado","Cerrado y perdido");
											for($i=1; $i<=6; $i++){
												
												if($infoTicket['tik_etapa']==$i) {echo '<span style="color:green; font-weight:bold; font-size:13px;">'.$opciones[$i].'</span><br>';}
												
												else {echo '<a href="sql.php?get=41&idtk='.$infoTicket['tik_id'].'&etapa='.$i.'">'.$opciones[$i].'</a><br>';}
											}
											?>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label" style="font-weight: bold;">Tipo negocio</label>
									<div class="controls">
									
                                            <?php
											$opciones = array("N/A","Venta","Servicio","Servicio Post venta");
											for($i=1; $i<=3; $i++){
												if($infoTicket['tik_tipo_negocio']==$i)echo $opciones[$i];	
											}
											?>
                                    	
                                    </div>
                               </div>
									
								<div class="control-group">
									<label class="control-label" style="font-weight: bold;">Origen del negocio</label>
									<div class="controls">
										
                                            <?php
											$opciones = array("N/A","LLamada mercadeo","Email Marketing","Sitio Web","Publicidad","Cliente existente","Recomendación","Exhibición","Otro");
											for($i=1; $i<=8; $i++){
												if($infoTicket['tik_origen_negocio']==$i)echo $opciones[$i];	
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
					<p><a href="clientes-seguimiento-agregar.php?idTK=<?=$_GET["idTK"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a></p>
					
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?>: <?=$cliente["cli_nombre"];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php" enctype="multipart/form-data">
                            <input type="hidden" name="idSql" value="8">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
                            
                            <input type="hidden" name="idTK" value="<?=$_GET["idTK"];?>">
                            <input type="hidden" name="tipoS" value="<?=$tiket["tik_tipo_tiket"];?>">
                            <input type="hidden" name="cliente" value="<?=$resultadoD["cseg_cliente"];?>">
                            
                            	
                                <div class="control-group">
									<label class="control-label">Fecha del contacto</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaContacto" value="<?=$resultadoD[9];?>" readonly>
									</div>
								</div>
                                
                               
                               <div class="control-group">
									<label class="control-label">Contacto</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="contacto" required>
											<option value="0"></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM contactos WHERE cont_cliente_principal='".$tiket["tik_cliente"]."'",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['cseg_contacto']==$resOp['cont_id']){echo "selected";}?>><?=$resOp['cont_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
								   
								   <a href="#" onClick='window.open("clientes-contactos-agregar.php?cte=<?=$cliente;?>","contactos","width=1200,height=800,menubar=no")' class="btn btn-danger"><i class="icon-plus"></i> Agregar contactos</a>
                                    <p style="margin-top:10px; font-weight:bold;">Cuando termine de crear el contacto, cierre la ventana emergente y actualice esta pantalla (F5)</p>
                               </div>
                               
								<div class="control-group">
									<label class="control-label">¿Cómo fue el contacto?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="formaContacto">
											<option value="1"></option>
                                            <?php
											$opciones = array("","La empresa contactó al cliente","El cliente contactó  a la empresa");
											for($i=1; $i<=2; $i++){
												if($resultadoD['cseg_forma_contacto']==$i)echo '<option value="'.$i.'" selected>'.$opciones[$i].'</option>';
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
												if($resultadoD['cseg_canal']==$i)echo '<option value="'.$i.'" selected>'.$opciones[$i].'</option>';
												else echo '<option value="'.$i.'">'.$opciones[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                                <div class="control-group">
									<label class="control-label">Observaciones</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 80%" name="observaciones"><?=$resultadoD['cseg_observacion'];?></textarea>
									</div>
								</div>
								
								<?php if($infoTicket['tik_tipo_tiket']!=3){?>
                                <div class="control-group">
									<label class="control-label">¿Se consiguió datos?</label>
									<div class="controls">
                                        <input type="checkbox" value="1" name="datos" <?php if($resultadoD['cseg_consiguio_datos']==1) echo "checked";?> >
										<span style="color:#00078A;">Para llamadas de mercadeo</span>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">¿Hubo cotización?</label>
									<div class="controls">
                                        <input type="checkbox" value="1" name="cotizo" <?php if($resultadoD['cseg_cotizo']==1) echo "checked";?> >
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">¿Hubo venta?</label>
									<div class="controls">
                                        <input type="checkbox" value="1" name="vendio" <?php if($resultadoD['cseg_vendio']==1) echo "checked";?>>
									</div>
								</div>
								
                                <div class="control-group">
									<label class="control-label"># Cotización</label>
									<div class="controls">
										<input type="text" class="span4" name="cotizacion" value="<?=$resultadoD[8];?>" style="font-weight:bold;">
									</div>
								</div>
								<?php }?>
								
								<div class="control-group">
									<label class="control-label">Archivo</label>
									<div class="controls">
										<input type="file" class="span6" name="archivo" style="font-weight:bold;">
									</div>
								</div>
								
								<?php if($resultadoD['cseg_archivo']!=""){?>
									<a href="sql.php?get=32&id=<?=$resultadoD['cseg_id'];?>" onClick="if(!confirm('Desea eliminar este archivo?')){return false;}"><i class="icon-trash"></i></a>&nbsp;&nbsp;
									<a href="files/adjuntos/<?=$resultadoD['cseg_archivo'];?>" target="_blank"><?=$resultadoD['cseg_archivo'];?></a>
								<?php }?>
                                
                                <hr>
                                <div class="control-group">
									<label class="control-label">Fecha próximo contacto</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaPC" value="<?=$resultadoD[5];?>">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Asunto a tratar</label>
									<div class="controls">
                                        <textarea rows="5" cols="80" style="width: 80%" name="asunto"><?=$resultadoD[6];?></textarea>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Encargado del próximo contacto</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="encargado">
											<option value="0"></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM usuarios",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['cseg_usuario_encargado']==$resOp[0]) echo "selected";?>><?=$resOp[4];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
								<fieldset class="default">
									<legend>Complementario</legend>
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
                                
                               <?php 
								if($resultadoD['cseg_varios']>=1 and $resultadoD['cseg_usuario_encargado']==0){
									echo "<span style='color:blue; font-size:14px;'>Los encargados aún no han revisado este pendiente. Por ahora no es posible hacer cambios.</span>";
								}else{?>
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<button type="button" class="btn btn-danger">Cancelar</button>
								</div>
								<?php }?>
								
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