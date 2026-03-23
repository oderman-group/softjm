<?php
include("sesion.php");

$idPagina = 13;
$paginaActual['pag_nombre'] = "Agregar Seguimiento de clientes";

include("includes/verificar-paginas.php");
include("includes/head.php");

require_once RUTA_PROYECTO.'/usuarios/class/Tickets.php';

if(isset($_GET["idTK"]) and is_numeric($_GET["idTK"]) && $_GET["idTK"] > 0){
	$consultaTikets=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_tikets WHERE tik_id='".$_GET["idTK"]."'");
	$tiket = mysqli_fetch_array($consultaTikets, MYSQLI_BOTH);
	$tiketID = $_GET["idTK"];
	$cliente = $tiket["tik_cliente"];
	$tipoSeguimiento = $tiket["tik_tipo_tiket"];

	$estadoTicket = Ticket::getEstado($tiketID, $conexionBdPrincipal);
}elseif(isset($_GET["cte"]) and is_numeric($_GET["cte"])){
	$tiketID = ""; // vacío porque lo vamos a crear
	$estadoTicket = 1; //Asumimos que está abierto para que lo deje crear o asociar a uno existente.
	$cliente = $_GET["cte"];
	$tipoSeguimiento = 1; //Comercial por defecto
}else{
	//Lo devuelve a los clientes
	echo '<script type="text/javascript">window.location.href="clientes.php?msg=9";</script>';
	exit();
}
?>
<link href="css/chosen.css" rel="stylesheet">
<link href="css/jquery.gritter.css" rel="stylesheet">

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
<script src="js/bootbox.js"></script>
<script src="js/jquery.gritter.js"></script>
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
							<h3> Ticket <b><?php if(!empty($tiketID)) echo "Nro. ".$tiketID;?></b></h3>
							<?php
							$consultaInfoTikets=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_tikets
							INNER JOIN clientes ON cli_id=tik_cliente
							INNER JOIN usuarios ON usr_id=tik_usuario_responsable
							WHERE tik_id='".$_GET["idTK"]."'");
							$infoTicket = mysqli_fetch_array($consultaInfoTikets, MYSQLI_BOTH);
							
							?>
							<input type="hidden" id="tik_tipo_tiket" value="<?=$infoTicket['tik_tipo_tiket'];?>">
							<input type="hidden" id="tik_tipo_negocio" value="<?=$infoTicket['tik_tipo_negocio'];?>">
						</div>
						<div class="widget-container" style="font-size: 10px;">
							
							<div class="control-group">
								<label class="control-label" style="font-weight: bold;">Cliente</label>
								<div class="controls">
									<a href="clientes-editar.php?id=<?=$infoTicket['cli_id'];?>"><?=$infoTicket['cli_nombre'];?></a>
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
												
												if($infoTicket['tik_etapa']==$i) {
													echo '<span style="color:green; font-weight:bold; font-size:13px;">'.$opcionesEtapa[$i].'</span><br>';
												}
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

							<?php if (Modulos::validarRol([90], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && $estadoTicket == 1) {?>
								<div align="center" style="padding: 5px;">
										<a href="clientes-tikets-editar.php?id=<?=$infoTicket['tik_id'];?>" class="btn btn-primary">Editar ticket</a>
								</div>
							<?php }?>
							
						</div>
					</div>

					<?php if (!empty($tiket['tik_id_cotizacion'])) {?>
						<div class="board-widgets green">
							<div class="board-widgets-head clearfix">
								<h4 class="pull-left"><i class="icon-inbox"></i> Cotización Asociada </h4>
								
							</div>
							<div class="board-widgets-content">
								<span class="n-counter">#<?=$tiket['tik_id_cotizacion'];?></span><span class="n-sources">Número de cotización</span>
							</div>
							<div class="board-widgets-botttom">
								<a href="cotizaciones-editar.php?id=<?=$tiket['tik_id_cotizacion'];?>">Ir a la cotización <i class="icon-double-angle-right"></i></a>
							</div>
						</div>
					<?php }?>
				</div>
<?php
// Análisis de canales de contacto más usados para el cliente y usuario actual
$consultaCanal = mysqli_query($conexionBdPrincipal, "SELECT cseg_canal, COUNT(*) as count FROM cliente_seguimiento WHERE cseg_cliente = '$cliente' AND cseg_usuario_responsable = '" . $_SESSION["id"] . "' GROUP BY cseg_canal ORDER BY count DESC LIMIT 1");
$canalMasUsado = mysqli_fetch_array($consultaCanal, MYSQLI_BOTH);
$canalSeleccionado = $canalMasUsado['cseg_canal'] ?? 4; // Default a Celular si no hay datos

$consultaCanalPC = mysqli_query($conexionBdPrincipal, "SELECT cseg_canal_proximo_contacto, COUNT(*) as count FROM cliente_seguimiento WHERE cseg_cliente = '$cliente' AND cseg_usuario_responsable = '" . $_SESSION["id"] . "' GROUP BY cseg_canal_proximo_contacto ORDER BY count DESC LIMIT 1");
$canalPCMasUsado = mysqli_fetch_array($consultaCanalPC, MYSQLI_BOTH);
$canalPCSeleccionado = $canalPCMasUsado['cseg_canal_proximo_contacto'] ?? 3; // Default a Celular si no hay datos
if(!empty($tiketID) && $estadoTicket == 1){
    $consultaNumSeguimientos = mysqli_query($conexionBdPrincipal, "SELECT COUNT(*) as total FROM cliente_seguimiento WHERE cseg_tiket = '$tiketID'");
    $numSeguimientos = mysqli_fetch_array($consultaNumSeguimientos, MYSQLI_BOTH)['total'];
    if($numSeguimientos > 0){
        $consultaUltimoSeguimiento = mysqli_query($conexionBdPrincipal, "SELECT * FROM cliente_seguimiento WHERE cseg_tiket = '$tiketID' ORDER BY cseg_id DESC LIMIT 1");
        $ultimoSeguimiento = mysqli_fetch_array($consultaUltimoSeguimiento, MYSQLI_BOTH);
        $mostrarModalUltimo = true;
        // Usar el canal del próximo contacto del último seguimiento como seleccionado para el canal actual
        $canalSeleccionado = $ultimoSeguimiento['cseg_canal_proximo_contacto'] ?? $canalSeleccionado;
    }
}
?>

				
				
				<div class="span9">
					<p>Los campos marcados con (*) son obligatorios.</p>
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="bd_create/clientes-seguimiento-guardar.php" enctype="multipart/form-data" id="formAgregarSeguimiento" novalidate>
                            <?php if ($estadoTicket == 1) {?>
                            <input type="hidden" name="idTK" value="<?=$tiketID;?>">
                            <input type="hidden" name="tipoS" value="<?=$tipoSeguimiento;?>">
                            <input type="hidden" name="cliente" value="<?=$cliente;?>">
							<div id="seguimientoFormAlert" class="alert alert-error" style="display:none; margin: 10px 0;"></div>
                            
                               
                               <div class="control-group">
									<label class="control-label">Contacto (*)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="contacto" id="contacto" required>
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_cliente_principal='".$cliente."'");
											$numContactos = mysqli_num_rows($conOp);
											$selected = '';

											if ($numContactos == 1) {
												$selected = 'selected';
											}

											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?=$selected;?>><?=$resOp['cont_nombre']." (".$resOp['cont_email']." - ".$resOp['cont_telefono'].")";?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                                    <a href="#" onClick='window.open("clientes-contactos-agregar.php?cte=<?=$cliente;?>","contactos","width=1200,height=800,menubar=no")' class="btn btn-danger"><i class="icon-plus"></i> Agregar contactos</a>
                                    <p style="margin-top:10px; font-weight:bold;">Cuando termine de crear el contacto, cierre la ventana emergente y actualice esta pantalla (F5)</p>
                               </div>
								
								<?php if(empty($tiketID)){?>
									<div class="alert alert-info">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<i class="icon-exclamation-sign"></i><strong>Ticket automático!</strong> Si no desea asociar este seguimiento a un ticket ya creado entonces se creará uno automáticamente para este seguimiento.
									</div>

								   <div class="control-group">
									<label class="control-label"><b>¿Asociar a un Ticket ya existente?</b></label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="tiketCreado">
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_tikets 
											WHERE tik_cliente='".$cliente."'
											AND tik_estado=1
											");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp['tik_asunto_principal'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
									   
                               	  </div>
								  <?php }?>
                            	
                                <div class="control-group">
									<label class="control-label">Fecha del contacto (*)</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaContacto" value="<?=date("Y-m-d");?>" readonly>
                                        <span style="color:#009;">Esta fecha es la de HOY y se guardará automáticamente.</span>
									</div>
								</div>
                                
								<div class="control-group">
									<label class="control-label">¿Cómo fue el contacto? (*)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="formaContacto" id="formaContacto" required>
											<option value="1"></option>
                                            <?php
											$opciones = array("","La empresa contactó al cliente","El cliente contactó  a la empresa");
											for($i=1; $i<=2; $i++){
												if ($i == 1)
													echo '<option value="'.$i.'" selected>'.$opciones[$i].'</option>';
												else 
													echo '<option value="'.$i.'">'.$opciones[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
                                <div class="control-group">
         <label class="control-label">Canal de contacto (*)</label>
         <div class="controls">
          <select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="canal" id="canal" required>
           <option value=""></option>
                                             <?php
           $opciones = array("","Facebook","WhatsApp","Fijo","Celular","Personal","Skype","Otro","Correo", "Sitio Web");
           for($i=1; $i<=9; $i++){
            $selected = ($i == $canalSeleccionado) ? 'selected' : '';
            echo '<option value="'.$i.'" '.$selected.'>'.$opciones[$i].'</option>';
           }
           ?>
                                     	</select>
                                     </div>
                                </div>
                                
                               
                                <div class="control-group">
									<label class="control-label">Observaciones/Descripción (*)</label>
									<div class="controls">
										<textarea name="observaciones" id="observaciones" style="width: 80%" required></textarea>
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
									<label class="control-label">¿Hubo demostración?</label>
									<div class="controls">
                                        <input type="checkbox" value="1" name="demostracion">
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">¿Hubo visita?</label>
									<div class="controls">
                                        <input type="checkbox" value="1" name="visita">
									</div>
								</div>
								
								<?php if (empty($tiket['tik_id_cotizacion'])) {
									$sql = "SELECT cotiz_id, cotiz_fecha_propuesta, cotiz_creador, cotiz_vendedor, cotiz_vendida, 
									cli_id, cli_nombre, cli_zona,
									usr_id, usr_nombre 
									FROM cotizacion
									INNER JOIN clientes ON cli_id=cotiz_cliente
									INNER JOIN usuarios ON usr_id=cotiz_creador
									WHERE cotiz_id=cotiz_id AND cotiz_id_empresa='".$idEmpresa."'
									AND cotiz_ticket IS NULL
									AND cotiz_cliente=".$cliente."
									ORDER BY cotiz_id DESC
									";
									$conOp = mysqli_query($conexionBdPrincipal, $sql);
									$numCotizaciones = $conOp->num_rows;
									if ($numCotizaciones > 0) {
								?>
									<div class="control-group">
										<label class="control-label"># Cotización</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cotizacion">
												<option value=""></option>
												<?php
												while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
												?>
													<option value="<?=$resOp['cotiz_id'];?>"><?=$resOp['cotiz_id']." - ".$resOp['cotiz_fecha_propuesta']." (".$resOp['cli_nombre'].")";?></option>
												<?php
												}
												?>
											</select>
										</div>
									</div>
								<?php } else {
									// Solo mostrar el mensaje si el ticket es comercial y el tipo de negocio es venta
									if($infoTicket['tik_tipo_tiket'] == 1 && $infoTicket['tik_tipo_negocio'] == 1){
								?>
									<div class="alert alert-info">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<i class="icon-exclamation-sign"></i><strong>Sin cotización!</strong> No hay cotizaciones para este cliente que podamos asociar a este proceso. Pero no se preocupe, puede crear una y asociarla más tarde. <a href="cotizaciones-agregar.php?cte=<?=$tiket['tik_cliente'];?>&ticket=<?=$tiket['tik_id'];?>" target="_blank" class="btn btn-danger">Crear cotización</a>
									</div>
								<?php }
								}?>
								<?php } else {?>
									<input type="hidden" class="span4" name="cotizacion" value="<?=$tiket['tik_id_cotizacion'];?>">
								<?php }?>

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
                                
								<fieldset class="default" id="campos_controlados">
								<legend>Próximo contacto</legend>

                                <div class="control-group">
         <label class="control-label">Fecha próximo contacto (*)</label>
         <div class="controls">
          <input type="date" class="span4" name="fechaPC" value="<?=date('Y-m-d', strtotime('+1 day'));?>" required id="fechaPC">
          <a href="#" data-toggle="modal" data-target="#calendarModal" style="color:#009; text-decoration: underline;"><i class="icon icon-calendar"></i> Ver mi calendario</a>
         </div>
        </div>

								<div class="control-group">
									<label class="control-label">Hora próximo contacto (*)</label>
									<div class="controls">
										<input type="time" class="span2" name="horaPC" value="<?=date('H:i');?>" required id="horaPC">
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">Recordatorio (Minutos antes) (*)</label>
									<div class="controls">
										<input type="number" min="0" step="5" value="10" class="span2" name="minutosRecordarAntes" required id="minutosRecordarAntes">
									</div>
								</div>

								<script>
								document.addEventListener('DOMContentLoaded', function() {
									document.getElementById('minutosRecordarAntes').value = 10;
								});
								</script>
								
								<div class="control-group">
         <label class="control-label">Medio de contacto (*)</label>
         <div class="controls">
          <select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="canalPC" data-required="true" id="canalPC">
           <option value="">Escoja una opción...</option>
                                             <?php
											$opciones = array("","WhatsApp","Fijo","Celular","Visitar al cliente","El cliente me visita","Skype", "Otro","Correo","Sitio Web");
											for($i=1; $i<=9; $i++){
												$selected = ($i == $canalPCSeleccionado) ? 'selected' : '';
												echo '<option value="'.$i.'" '.$selected.'>'.$opciones[$i].'</option>';
											}
											?>
                                     	</select>
                                     </div>
                                </div>
                                
                                <div class="control-group">
									<label class="control-label">Asunto a tratar (*)</label>
									<div class="controls">
                                        <textarea name="asunto" style="width: 80%" required id="asunto"></textarea>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Encargado del próximo contacto (*)</label>
									<div class="controls">
										<select 
											class="chzn-select span8" 
											tabindex="2" 
											name="encargado[]" 
											multiple 
											required
											id="encargado"
											data-required="true"
										>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios 
											WHERE usr_bloqueado!=1 AND usr_id_empresa='".$idEmpresa."'");

											while ($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)) {
												$selected = '';

												if ($resOp['usr_id'] == $_SESSION['id']) {
													$selected = 'selected';
												}
											?>
												<option value="<?=$resOp['usr_id'];?>" <?=$selected;?>><?=$resOp['usr_nombre'];?></option>
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
											<input type="checkbox" value="1" name="cerrarTK" id="miCheckboxControl">
											<span style="color:navy;">Este se toma como el último seguimiento y el ticket quedará cerrado.</span>
										</div>
									</div>
								
									<div class="control-group">
										<label class="control-label">Notificar de inmediato al encargado</label>
										<div class="controls">
											<input type="checkbox" value="1" name="notf">
											<span style="color:#00078A;">Llegará una notificación inmediata al encargado</span>
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
										<button type="submit" class="btn btn-info" id="btnGuardarSeguimiento"><i class="icon-save"></i> <span class="btn-text">Guardar cambios</span></button>
									</div>
								<?php } else {?>
									<div class="alert alert-info">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<i class="icon-exclamation-sign"></i><strong>Ticket cerrado!</strong> No es posible hacer cambios en un ticket cerrado.
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
<?php if(isset($mostrarModalUltimo) && $mostrarModalUltimo){ ?>
<script>
$(document).ready(function(){
    $('#ultimoSeguimientoModal').modal('show');
});
</script>
<?php } ?>
<script src="js/seguimientos.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	// Validación personalizada para campos con Chosen que son requeridos
	$('#formSeguimiento').on('submit', function(e) {
		// Sincronizar valores por defecto antes de validar
		// Asegurarse de que los selects con valores selected en HTML tengan esos valores establecidos
		$('select.chzn-select').each(function() {
			var $select = $(this);
			var selectedOptions = $select.find('option[selected]');
			
			if (selectedOptions.length > 0) {
				if ($select.attr('multiple')) {
					var selectedValues = [];
					selectedOptions.each(function() {
						var val = $(this).val();
						if (val && val !== '') {
							selectedValues.push(val);
						}
					});
					if (selectedValues.length > 0) {
						var currentVal = $select.val();
						// Si el valor actual no coincide con los valores por defecto, actualizarlo
						if (!currentVal || (Array.isArray(currentVal) && currentVal.length === 0) || 
							(Array.isArray(currentVal) && JSON.stringify(currentVal.sort()) !== JSON.stringify(selectedValues.sort()))) {
							$select.val(selectedValues);
							$select.trigger('chosen:updated');
						}
					}
				} else {
					var selectedValue = selectedOptions.first().val();
					if (selectedValue && selectedValue !== '') {
						var currentVal = $select.val();
						if (!currentVal || currentVal === '') {
							$select.val(selectedValue);
							$select.trigger('chosen:updated');
						}
					}
				}
			}
		});
		var isValid = true;
		var firstInvalidField = null;
		var errorMessages = [];
		
		// Obtener todos los campos select con Chosen que son requeridos
		$('select.chzn-select[data-required="true"]').each(function() {
			var $select = $(this);
			var fieldName = $select.attr('name');
			var $controlGroup = $select.closest('.control-group');
			var $fieldset = $controlGroup.closest('fieldset');
			var fieldLabel = $controlGroup.find('label.control-label').text().replace('(*)', '').trim();
			
			// Solo validar si el campo tiene el atributo data-required activo
			if ($select.attr('data-required') !== 'true') {
				return; // Saltar si no es requerido
			}
			
			// Verificar si el campo está visible
			// Si está dentro de un fieldset, verificar que el fieldset esté visible
			// Si no está dentro de un fieldset, verificar que el control-group esté visible
			var isVisible = $controlGroup.is(':visible');
			if ($fieldset.length > 0) {
				isVisible = isVisible && $fieldset.is(':visible');
			}
			
			if (!isVisible) {
				return; // Saltar campos ocultos
			}
			
			// Obtener el valor del select original (no del elemento visual de Chosen)
			// Primero verificar si hay valores por defecto en el HTML que no se han aplicado
			var selectedOptions = $select.find('option[selected]');
			var fieldValue;
			
			if (selectedOptions.length > 0) {
				// Si hay opciones marcadas como selected en el HTML, asegurarse de que estén aplicadas
				if ($select.attr('multiple')) {
					var defaultValues = [];
					selectedOptions.each(function() {
						var val = $(this).val();
						if (val && val !== '') {
							defaultValues.push(val);
						}
					});
					if (defaultValues.length > 0) {
						var currentVal = $select.val();
						// Si no hay valor o está vacío, aplicar el valor por defecto
						if (!currentVal || (Array.isArray(currentVal) && currentVal.length === 0)) {
							$select.val(defaultValues);
							// Leer el valor después de establecerlo
							fieldValue = $select.val();
						} else {
							fieldValue = currentVal;
						}
					} else {
						fieldValue = $select.val();
					}
				} else {
					var defaultValue = selectedOptions.first().val();
					if (defaultValue && defaultValue !== '') {
						var currentVal = $select.val();
						// Si no hay valor o está vacío, aplicar el valor por defecto
						if (!currentVal || currentVal === '') {
							$select.val(defaultValue);
							// Leer el valor después de establecerlo
							fieldValue = $select.val();
						} else {
							fieldValue = currentVal;
						}
					} else {
						fieldValue = $select.val();
					}
				}
			} else {
				// Si no hay valores por defecto, usar el valor actual
				fieldValue = $select.val();
			}
			
			// Para selects múltiples, verificar que al menos uno esté seleccionado
			if ($select.attr('multiple')) {
				// Para múltiples, fieldValue debería ser un array o null
				var hasValue = false;
				if (Array.isArray(fieldValue)) {
					hasValue = fieldValue.length > 0 && fieldValue.some(function(v) { return v && v !== ''; });
				} else if (fieldValue !== null && fieldValue !== undefined && fieldValue !== '') {
					hasValue = true;
				}
				
				if (!hasValue) {
					isValid = false;
					if (!firstInvalidField) {
						firstInvalidField = $select;
					}
					errorMessages.push('Debe seleccionar al menos una opción en: ' + fieldLabel);
				}
			} else {
				// Para selects simples, verificar que tenga un valor válido (no vacío ni null)
				var hasValue = false;
				if (fieldValue !== null && fieldValue !== undefined && fieldValue !== '') {
					hasValue = true;
				}
				
				if (!hasValue) {
					isValid = false;
					if (!firstInvalidField) {
						firstInvalidField = $select;
					}
					errorMessages.push('Debe seleccionar una opción en: ' + fieldLabel);
				}
			}
		});
		
		// Verificar otros campos requeridos normales (sin Chosen)
		$('input[required], textarea[required]').each(function() {
			var $field = $(this);
			var $fieldset = $field.closest('fieldset');
			var $controlGroup = $field.closest('.control-group');
			
			// Solo validar si el campo es visible
			// Si está dentro de un fieldset, verificar que el fieldset esté visible
			// Si no está dentro de un fieldset, verificar que el control-group esté visible
			var isVisible = $field.is(':visible') && $controlGroup.is(':visible');
			if ($fieldset.length > 0) {
				isVisible = isVisible && $fieldset.is(':visible');
			}
			
			// Solo validar si el campo tiene el atributo required activo y está visible
			if (isVisible && $field.prop('required')) {
				var fieldValue = $field.val();
				var fieldLabel = $controlGroup.find('label.control-label').text().replace('(*)', '').trim();
				
				if (!fieldValue || (typeof fieldValue === 'string' && fieldValue.trim() === '')) {
					isValid = false;
					if (!firstInvalidField) {
						firstInvalidField = $field;
					}
					errorMessages.push('Debe llenar el campo: ' + fieldLabel);
				}
			}
		});
		
		if (!isValid) {
			e.preventDefault();
			e.stopPropagation();
			
			// Mostrar mensaje de error
			var message = 'Por favor, complete los siguientes campos obligatorios:\n\n' + errorMessages.join('\n');
			alert(message);
			
			// Hacer scroll al primer campo inválido
			if (firstInvalidField) {
				var $controlGroup = firstInvalidField.closest('.control-group');
				if ($controlGroup.length) {
					$('html, body').animate({
						scrollTop: $controlGroup.offset().top - 100
					}, 500);
					
					// Si es un campo con Chosen, abrir el dropdown
					if (firstInvalidField.hasClass('chzn-select')) {
						firstInvalidField.trigger('chosen:open');
					} else {
						firstInvalidField.focus();
					}
				}
			}
			
			return false;
		}
		
		return true;
	});
	
	// Asegurar que los valores por defecto se reflejen correctamente en Chosen después de inicializarse
	// Esperar a que Chosen termine de inicializar todos los selects
	// Usar múltiples timeouts para asegurar que Chosen esté completamente inicializado
	setTimeout(function() {
		$('select.chzn-select').each(function() {
			var $select = $(this);
			// Verificar si el select tiene opciones seleccionadas por defecto en el HTML
			var selectedOptions = $select.find('option[selected]');
			
			if (selectedOptions.length > 0) {
				// Si hay opciones seleccionadas, asegurarse de que el valor del select coincida
				if ($select.attr('multiple')) {
					// Para múltiples, obtener todos los valores seleccionados
					var selectedValues = [];
					selectedOptions.each(function() {
						var val = $(this).val();
						if (val && val !== '') {
							selectedValues.push(val);
						}
					});
					if (selectedValues.length > 0) {
						$select.val(selectedValues);
						$select.trigger('chosen:updated');
					}
				} else {
					// Para simples, obtener el primer valor seleccionado
					var selectedValue = selectedOptions.first().val();
					if (selectedValue && selectedValue !== '') {
						$select.val(selectedValue);
						$select.trigger('chosen:updated');
					}
				}
			} else if ($select.val()) {
				// Si ya tiene valor pero no está marcado como selected, actualizar Chosen
				$select.trigger('chosen:updated');
			}
		});
	}, 300);
});
</script>

<!-- Modal for Calendar -->
<div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="calendarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="width: 95%; max-width: 1400px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="calendarModalLabel">Mi Calendario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="padding: 0;">
        <iframe src="calendario-modal.php?id=<?=$_SESSION["id"];?>" width="100%" height="1000" frameborder="0"></iframe>
      </div>
    </div>
  </div>
</div>

</body>
</html>
<?php if(isset($mostrarModalUltimo) && $mostrarModalUltimo){ ?>
<!-- Modal for Last Follow-up -->
<div class="modal fade" id="ultimoSeguimientoModal" tabindex="-1" role="dialog" aria-labelledby="ultimoSeguimientoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ultimoSeguimientoModalLabel">Recordatorio del Último Seguimiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
        $dias = array('Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado');
        $meses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
        $fechaContacto = strtotime($ultimoSeguimiento['cseg_fecha_contacto']);
        $diaSemana = $dias[date('w', $fechaContacto)];
        $dia = date('d', $fechaContacto);
        $mes = $meses[date('n', $fechaContacto)];
        $anio = date('Y', $fechaContacto);
        $fechaFormateada = $diaSemana . ', ' . $dia . ' de ' . $mes . ' de ' . $anio;

        $fechaProximo = strtotime($ultimoSeguimiento['cseg_fecha_proximo_contacto'] . ' ' . $ultimoSeguimiento['cseg_hora_proximo_contacto']);
        $diaSemanaP = $dias[date('w', $fechaProximo)];
        $diaP = date('d', $fechaProximo);
        $mesP = $meses[date('n', $fechaProximo)];
        $anioP = date('Y', $fechaProximo);
        $horaP = date('H:i', $fechaProximo);
        $fechaProximaFormateada = $diaSemanaP . ', ' . $diaP . ' de ' . $mesP . ' de ' . $anioP . ' a las ' . $horaP;
        ?>
        <p><strong>Fecha del contacto:</strong> <?=$fechaFormateada;?></p>
        <p><strong>Canal de contacto:</strong> <?php $opcionesCanal = array("","Facebook","WhatsApp","Fijo","Celular","Personal","Skype","Otro","Correo", "Sitio Web"); echo $opcionesCanal[$ultimoSeguimiento['cseg_canal']];?></p>
        <p><strong>Observaciones:</strong> <?=$ultimoSeguimiento['cseg_observacion'];?></p>
        <p><strong>Próximo contacto:</strong> <?=$fechaProximaFormateada;?></p>
        <p><strong>Medio de próximo contacto:</strong> <?php $opcionesCanalPC = array("","WhatsApp","Fijo","Celular","Visitar al cliente","El cliente me visita","Skype", "Otro","Correo","Sitio Web"); echo $opcionesCanalPC[$ultimoSeguimiento['cseg_canal_proximo_contacto']];?></p>
        <p><strong>Asunto a tratar:</strong> <?=$ultimoSeguimiento['cseg_asunto'];?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<!-- Modal for Calendar -->
