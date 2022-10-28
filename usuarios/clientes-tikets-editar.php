<?php include("sesion.php");?>
<?php
$idPagina = 90;
$paginaActual['pag_nombre'] = "Editar Tickets de clientes";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<?php
$resultadoD = mysql_fetch_array(mysql_query("SELECT * FROM clientes_tikets WHERE tik_id='".$_GET["id"]."'",$conexion));

if($resultadoD[0]==""){
	echo '<script type="text/javascript">window.location.href="clientes-tikets.php";</script>';
	exit();
}
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
<script type="text/javascript">
    /*====TAGS INPUT====*/
    $(function () {
        $('#tags_1').tagsInput({
            width: 'auto'
        });
        $('#tags_2').tagsInput({
            width: 'auto',
            onChange: function (elem, elem_tags) {
                var languages = ['php', 'ruby', 'javascript'];
                $('.tag', elem_tags).each(function () {
                    if ($(this).text().search(new RegExp('\\b(' + languages.join('|') + ')\\b')) >= 0) $(this).css('background-color', 'yellow');
                });
            }
        });
    });
    /*====Select Box====*/
    $(function () {
        $(".chzn-select").chosen();
        $(".chzn-select-deselect").chosen({
            allow_single_deselect: true
        });
    });
    /*====Color Picker====*/
    $(function () {
        $('.colorpicker').colorpicker({
            format: 'hex'
        });
        $('.pick-color').colorpicker();
    });
    /*====DATE Time Picker====*/
    $(function () {
        $('#datetimepicker1').datetimepicker({
            language: 'pt-BR'
        });
    });
    $(function () {
        $('#datetimepicker3').datetimepicker({
            pickDate: false
        });
    });
    $(function () {
        $('#datetimepicker4').datetimepicker({
            pickTime: false
        });
    });
    /*DATE RANGE PICKER*/
    $(function () {
        $('#reportrange').daterangepicker({
            ranges: {
                'Today': ['today', 'today'],
                'Yesterday': ['yesterday', 'yesterday'],
                'Last 7 Days': [Date.today().add({
                    days: -6
                }), 'today'],
                'Last 30 Days': [Date.today().add({
                    days: -29
                }), 'today'],
                'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
                'Last Month': [Date.today().moveToFirstDayOfMonth().add({
                    months: -1
                }), Date.today().moveToFirstDayOfMonth().add({
                    days: -1
                })]
            },
            opens: 'left',
            format: 'MM/dd/yyyy',
            separator: ' to ',
            startDate: Date.today().add({
                days: -29
            }),
            endDate: Date.today(),
            minDate: '01/01/2012',
            maxDate: '12/31/2013',
            locale: {
                applyLabel: 'Submit',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom Range',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            },
            showWeekNumbers: true,
            buttonClasses: ['btn-danger']
        },
        function (start, end) {
            $('#reportrange span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString('MMMM d, yyyy'));
        });
        //Set the initial state of the picker label
        $('#reportrange span').html(Date.today().add({
            days: -29
        }).toString('MMMM d, yyyy') + ' - ' + Date.today().toString('MMMM d, yyyy'));
    });
    $(function () {
        $('#reservation').daterangepicker();
    });
</script>
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
						
                        <ul class="top-right-toolbar">
							<li><a data-toggle="dropdown" class="dropdown-toggle blue-violate" href="#" title="Users"><i class="icon-user"></i></a>
							</li>
							<li><a href="#" class="green" title="Upload"><i class=" icon-upload-alt"></i></a></li>
							<li><a href="#" class="bondi-blue" title="Settings"><i class="icon-cogs"></i></a></li>
						</ul>
                        
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="clientes-tikets.php?cte=<?=$_GET["cte"];?>">Tickets de clientes</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span9">
					
					<p>
						
						
						<a href="sql.php?get=24&id=<?=$_GET["id"];?>" class="btn btn-danger" onClick="if(!confirm('Desea eliminar este ticket?')){return false;}"><i class="icon icon-trash"></i> Eliminar Ticket</a>
					</p>
					
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php">
                            <input type="hidden" name="idSql" value="40">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
                            
                                <div class="control-group">
									<label class="control-label">Cliente (*)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="cliente">
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM clientes",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
												if($datosUsuarioActual[3]!=1){
													$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$resOp['cli_zona']."'",$conexion));
													
													$numCliente = mysql_num_rows(mysql_query("SELECT * FROM clientes_usuarios WHERE cliu_usuario='".$_SESSION["id"]."' AND cliu_cliente='".$resOp['cli_id']."'",$conexion));
									
													if($numZ == 0 and $numCliente == 0) continue;
												}
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['tik_cliente']==$resOp['cli_id']){echo "selected";}else{echo "disabled";}?>><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Asunto principal (*)</label>
									<div class="controls">
										<input type="text" class="span8" name="asuntoP" style="font-weight:bold;" value="<?=$resultadoD['tik_asunto_principal'];?>">
									</div>
								</div>
                               
                               <div class="control-group">
									<label class="control-label">Fecha de inicio</label>
									<div class="controls">
										<input type="date" class="span6" name="fechaInicio" value="<?=$resultadoD['tik_fecha_creacion'];?>">
									</div>
								</div>
                               
								
                               
								
								<!--
								<div class="control-group">
									<label class="control-label">Asunto principal</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="asuntoP">
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM tikets_asuntos",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['tik_asunto_principal']==$resOp[0]){echo "selected";}?>><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
									</div>
								</div>
								-->
								
								<?php if($resultadoD['tik_tipo_tiket']!=3){?>
								<fieldset class="default">
									<legend style="background-color: darkblue; color: white;">Negociación</legend>
									
								<div class="control-group">
									<label class="control-label">Valor ($)</label>
									<div class="controls">
										<input type="number" class="span6" name="valor" style="font-weight:bold;" value="<?=$resultadoD['tik_valor'];?>">
										<span style="color:#009;">Digite sólo el valor numérico. Sin puntos, ni comas, ni simbolos.</span>
									</div>
								</div>
									
								<div class="control-group">
									<label class="control-label">Etapa</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="etapa" onChange="razones(this)">
											<option value="1"></option>
                                            <?php
											$opciones = array("N/A","En progreso","En espera","Propuesta/Cotización","Negociación/Revisión","Cerrado y ganado","Cerrado y perdido");
											for($i=1; $i<=6; $i++){
												if($resultadoD['tik_etapa']==$i)echo '<option value="'.$i.'" selected>'.$opciones[$i].'</option>';
												else echo '<option value="'.$i.'">'.$opciones[$i].'</option>';	
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
											for($i=1; $i<=4; $i++){
												if($resultadoD['tik_razon_perdido']==$i)echo '<option value="'.$i.'" selected>'.$negociosPerdidos[$i].'</option>';
												else echo '<option value="'.$i.'">'.$negociosPerdidos[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>	
								
									
								<div class="control-group">
									<label class="control-label">Tipo negocio</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="tipoNegocio">
											<option value="1"></option>
                                            <?php
											$opciones = array("N/A","Venta","Servicio","Servicio Post venta");
											for($i=1; $i<=3; $i++){
												if($resultadoD['tik_tipo_negocio']==$i)echo '<option value="'.$i.'" selected>'.$opciones[$i].'</option>';
												else echo '<option value="'.$i.'">'.$opciones[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>
									
								<div class="control-group">
									<label class="control-label">Origen del negocio</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="origenNegocio">
											<option value="1"></option>
                                            <?php
											$opciones = array("N/A","LLamada mercadeo","Email Marketing","Sitio Web","Publicidad","Cliente existente","Recomendación","Exhibición","Otro");
											for($i=1; $i<=8; $i++){
												if($resultadoD['tik_origen_negocio']==$i)echo '<option value="'.$i.'" selected>'.$opciones[$i].'</option>';
												else echo '<option value="'.$i.'">'.$opciones[$i].'</option>';	
											}
											?>
                                    	</select>
                                    </div>
                               </div>	
									
							</fieldset>
							<?php }?>	
                                
                                <div class="control-group">
									<label class="control-label">Estado ticket</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="estado">
											<option value="0"></option>
                                            <option value="1" <?php if($resultadoD['tik_estado']==1){echo "selected";}?>>Abierto</option>
                                            <option value="2" <?php if($resultadoD['tik_estado']==2){echo "selected";}?>>Cerrado</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Tipo de ticket</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="tipoS">
											<option value="1"></option>
                                            <option value="1" <?php if($resultadoD['tik_tipo_tiket']==1){echo "selected";}?>>Comercial</option>
                                            <option value="3" <?php if($resultadoD['tik_tipo_tiket']==3){echo "selected";}?>>Soporte operativo</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Prioridad</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="prioridad">
											<option value="1"></option>
                                            <option value="1" <?php if($resultadoD['tik_prioridad']==1){echo "selected";}?>>Normal</option>
                                            <option value="2" <?php if($resultadoD['tik_prioridad']==2){echo "selected";}?>>Urgente</option>
                                            <option value="3" <?php if($resultadoD['tik_prioridad']==3){echo "selected";}?>>Muy Urgente</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Canal de contacto</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="canal">
											<option value="4"></option>
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
										<input type="text" class="span4" name="equipo" value="<?=$resultadoD['tik_equipo'];?>">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Referencia</label>
									<div class="controls">
										<input type="text" class="span4" name="referencia" value="<?=$resultadoD['tik_referencia'];?>">
									</div>
								</div>
								-->
                            	
                                <!--
                                <div class="control-group">
									<label class="control-label">Observaciones</label>
									<div class="controls">
                                        <textarea name="observaciones" rows="5" style="width: 80%"><?=$resultadoD['tik_observaciones'];?></textarea>
									</div>
								</div>
								-->
                               
								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				
				<div class="span3">
					<p>
						<a href="clientes-seguimiento-agregar.php?idTK=<?=$_GET["id"];?>" class="btn btn-info"><i class="icon icon-plus"></i> Agregar seguimiento</a>
					</p>
					
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> Seguimientos</h3>
						</div>
						<div class="widget-container" style="font-size: 10px;">
							<?php
							$seguimientos = mysql_query("SELECT * FROM cliente_seguimiento
							INNER JOIN clientes ON cli_id=cseg_cliente
							INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
							WHERE cseg_tiket='".$_GET["id"]."'
							ORDER BY cseg_id DESC
							",$conexion);
							while($seg = mysql_fetch_array($seguimientos)){
							?>
								<p>
									<a href="clientes-seguimiento-editar.php?id=<?=$seg['cseg_id'];?>&idTK=<?=$seg['cseg_tiket'];?>" title="Editar"><?=$seg['cseg_fecha_contacto'];?></a><br>
									<?=$seg['cseg_observacion'];?><br>
									<b><?=$seg['usr_nombre'];?></b>
								</p>
							<?php
							}
							?>
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