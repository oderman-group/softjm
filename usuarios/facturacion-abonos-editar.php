<?php include("sesion.php");?>
<?php
$idPagina = 94;
$paginaActual['pag_nombre'] = "Editar abonos a Factura";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<?php
$resultadoD = mysql_fetch_array(mysql_query("SELECT * FROM facturacion_abonos 
INNER JOIN usuarios ON usr_id=fpab_responsable_registro
WHERE fpab_id='".$_GET["id"]."'",$conexion));
$usuarioMod = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='".$resultadoD[9]."'",$conexion));
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
						<li><a href="facturacion-abonos.php?fact=<?=$_GET["fact"];?>">Abonos a Facturas</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            <p>
                <a href="facturacion-abonos-agregar.php?fact=<?=$_GET["fact"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
            </p>
            <?php include("includes/notificaciones.php");?>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php" enctype="multipart/form-data">
                            <input type="hidden" name="idSql" value="42">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
                            <input type="hidden" name="fact" value="<?=$_GET["fact"];?>">
                            	   
                                
                                <div class="control-group">
									<label class="control-label">Fecha de la factura</label>
									<div class="controls">
										<input type="date" class="span3" name="fecha" value="<?=$resultadoD['fpab_fecha_abono'];?>">
									</div>
								</div>
                                
                                
                                <div class="control-group">
									<label class="control-label">Valor ($)</label>
									<div class="controls">
										<input type="number" class="span3" name="valor" value="<?=$resultadoD['fpab_valor'];?>">
                                        <span style="color:#F00;">Digite sólo el valor numérico. Sin puntos, ni comas, ni simbolos.</span>
									</div>
								</div>
                                
                                
                                <div class="control-group">
									<label class="control-label">Medio de pago</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span3" tabindex="2" name="medio">
											<option value=""></option>
                                            <option value="1" <?php if($resultadoD['fpab_medio_pago']==1){echo "selected";}?>>Consignación</option>
                                            <option value="2" <?php if($resultadoD['fpab_medio_pago']==2){echo "selected";}?>>Transferencia</option>
                                            <option value="3" <?php if($resultadoD['fpab_medio_pago']==3){echo "selected";}?>>P. Web</option>
                                            <option value="4" <?php if($resultadoD['fpab_medio_pago']==4){echo "selected";}?>>Efectivo</option>
                                            <option value="5" <?php if($resultadoD['fpab_medio_pago']==5){echo "selected";}?>>Cheque</option>
                                            <option value="6" <?php if($resultadoD['fpab_medio_pago']==6){echo "selected";}?>>Efecty</option>
                                            <option value="7" <?php if($resultadoD['fpab_medio_pago']==7){echo "selected";}?>>Gana</option>
                                            <option value="8" <?php if($resultadoD['fpab_medio_pago']==8){echo "selected";}?>>Otro medio</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <a href="files/comprobantes/<?=$resultadoD['fpab_comprobante'];?>" target="_blank"><?=$resultadoD['fpab_comprobante'];?></a>
                               <div class="control-group">
									<label class="control-label">Comprobante</label>
									<div class="controls">
										<div class="fileupload fileupload-new" data-provides="fileupload">
											<div class="input-append">
												<div class="uneditable-input span3">
													<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
												</div>
												<span class="btn btn-file"><span class="fileupload-new">Seleccionar archivo</span><span class="fileupload-exists">Cambiar</span>
												<input type="file" name="archivo"/>
												</span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
											</div>
										</div>
									</div>
								</div>
                                
                               
                                <div class="control-group">
									<label class="control-label">Observaciones</label>
									<div class="controls">
										<textarea rows="5" cols="80" style="width: 80%" class="tinymce-simple" name="observaciones"><?=$resultadoD['fpab_observaciones'];?></textarea>
									</div>
								</div>
                                <hr>
                                <div class="control-group">
									<label class="control-label">Fecha del registro</label>
									<div class="controls">
										<input type="text" class="span3" name="fechaR" value="<?=$resultadoD['fpab_fecha_registro'];?>" readonly>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Responsable del registro</label>
									<div class="controls">
										<input type="text" class="span3" name="responsable" value="<?=$resultadoD['usr_nombre'];?>" readonly>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Fecha última modificación</label>
									<div class="controls">
										<input type="text" class="span3" name="fechaUltima" value="<?=$resultadoD['fpab_fecha_ultima_modificacion'];?>" readonly>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Responsable de la modificación</label>
									<div class="controls">
										<input type="text" class="span3" name="responsableMod" value="<?=$usuarioMod['usr_nombre'];?>" readonly>
									</div>
								</div>
                               
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