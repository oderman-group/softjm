<?php include("sesion.php");?>
<?php
$idPagina = 89;
$paginaActual['pag_nombre'] = "Agregar Tikets de clientes";
?>
<?php include("verificar-paginas.php");?>
<?php include("head.php");?>

<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>

<?php
if($_GET["em"]==4){
	mysql_query("UPDATE clientes SET cli_estado_mercadeo=4, cli_estado_mercadeo_fecha=now(), cli_estado_mercadeo_usuario='".$_SESSION["id"]."' WHERE cli_id='".$_GET["cte"]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}	
}	
?>

<?php
$tiket = mysql_fetch_array(mysql_query("SELECT * FROM clientes_tikets WHERE tik_id='".$_GET["idTK"]."'",$conexion));
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
<?php include("funciones-js.php");?>

<?php include("texto-editor.php");?>
</head>
<body>
<div class="layout">
	<?php include("encabezado.php");?>
    
    
    
	
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
						
                        <ul class="top-right-toolbar">
							<li><a data-toggle="dropdown" class="dropdown-toggle blue-violate" href="#" title="Users"><i class="icon-user"></i></a>
							</li>
							<li><a href="#" class="green" title="Upload"><i class=" icon-upload-alt"></i></a></li>
							<li><a href="#" class="bondi-blue" title="Settings"><i class="icon-cogs"></i></a></li>
						</ul>
                        
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
							<form class="form-horizontal" method="post" action="sql.php">
                            <input type="hidden" name="idSql" value="39">
                            	
								
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
											$conOp = mysql_query("SELECT * FROM clientes",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
												if($datosUsuarioActual[3]!=1){
													$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$resOp['cli_zona']."'",$conexion));
													
													$numCliente = mysql_num_rows(mysql_query("SELECT * FROM clientes_usuarios WHERE cliu_usuario='".$_SESSION["id"]."' AND cliu_cliente='".$resOp['cli_id']."'",$conexion));
									
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
								$clienteInfo = mysql_fetch_array(mysql_query("SELECT * FROM clientes WHERE cli_id='".$_GET['cte']."'",$conexion));
								?>
								<div class="control-group">
									<label class="control-label">Sucursal (*)</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="sucursal" required>
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM sucursales 
											WHERE sucu_cliente_principal='".$_GET["cte"]."'",$conexion);
											$numOp = mysql_num_rows($conOp);
											if($numOp==0){
												//Crear automáticamente la sucursal
												mysql_query("INSERT INTO sucursales(sucu_cliente_principal, sucu_ciudad, sucu_direccion, sucu_telefono, sucu_celular, sucu_nombre)VALUES('".$_GET["cte"]."', '".$clienteInfo['cli_ciudad']."', '".$clienteInfo['cli_direccion']."', '".$clienteInfo['cli_telefono']."', '".$clienteInfo['cli_celular']."','Sede principal (Automática)')",$conexion);
												
												echo '<script type="text/javascript">window.location.href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'";</script>';
												exit();
											}
											while($resOp = mysql_fetch_array($conOp)){
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
											$conOp = mysql_query("SELECT * FROM tikets_asuntos",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
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
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="origenNegocio">
											<option value="0"></option>
                                            <?php
											$opciones = array("N/A","LLamada mercadeo","Email Marketing","Sitio Web","Publicidad","Cliente existente","Recomendación","Exhibición","Otro");
											for($i=1; $i<=8; $i++){
												if($_GET["origenNegocio"]==$i)echo '<option value="'.$i.'" selected>'.$opciones[$i].'</option>';
												else echo '<option value="'.$i.'">'.$opciones[$i].'</option>';	
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
								
								<?php }?>
                            	
                                <!--
                                <div class="control-group">
									<label class="control-label">Observaciones</label>
									<div class="controls">
                                        <textarea rows="5" cols="80" style="width: 80%" name="observaciones"></textarea>
									</div>
								</div>
								-->
                                
                               
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
	<?php include("pie.php");?>
</div>
</body>
</html>