<?php include("sesion.php");?>
<?php
$idPagina = 21;
$paginaActual['pag_nombre'] = "Importar productos";
?>
<?php include("verificar-paginas.php");?>
<?php include("head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
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
                        <li><a href="productos.php">Productos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head green">
							<h3> Exportar productos</h3>
						</div>
						<div class="widget-container">
							
							<form class="form-horizontal" method="get" action="productos-exportar.php"> 
                                   
                               <div class="control-group">
									<label class="control-label">Grupo 1</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="grupo1">
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM productos_categorias WHERE catp_grupo=1",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['prod_grupo1']==$resOp[0]){echo "selected";}?>><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
                               <div class="control-group">
									<label class="control-label">Grupo 2</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="grupo2">
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM productos_categorias",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Marcas</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="marca">
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM marcas",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
								
								<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15){?>
								<div class="control-group">
									<label class="control-label">Qué productos?</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="tipoProductos">
											<option value="1">Todos</option>
											<option value="2">Sólo los de la SOTRE</option>
											<option value="3">Sólo los predeterminados</option>
                                    	</select>
                                    </div>
                               </div>
								<?php }else{?>
									<input type="hidden" name="tipoProductos" value="1">
								<?php }?>
								
 
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Exportar</button>
								</div>
                              
							</form>  

						</div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				
				<div class="span12">
					
					<!--
					<div style="background-color: antiquewhite; padding: 5px; margin: 10px;">
					<h2>Información importante</h2>
						<p><b>1.</b> Descargue la plantilla de excel. <a href="productos-exportar.php" target="_blank">[Descargar plantilla]</a></p>
						<p><b>2.</b> Llene la información de sus productos en la planilla descargada y guardela con ese mismo formato que ya trae la plantilla (Excel 97-2003).</p>
						<p><b>3.</b> Suba la planilla de excel.</p>
					</div>
					-->
					
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>

						<div class="row-fluid">
				<div class="span12">
					<div class="hero-unit">
						<h2>Existencias</h2>
						<p>
							Las existencias ya no serán tomadas en cuenta desde este archivo. Todas las existencias se manejan por bodegas.
						</p>
					</div>
				</div>
			</div>

						<div class="widget-container">
							<form class="form-horizontal" method="post" action="productos-importar-excel.php" enctype="multipart/form-data">
                            	
                                <fieldset class="default" style="background:#FFC;">
                                   	<legend>Información básica</legend>
                                <div class="control-group">
									<div class="controls">
										<div class="fileupload fileupload-new" data-provides="fileupload">
											<div class="input-append">
												<div class="uneditable-input span3">
													<i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
												</div>
												<span class="btn btn-file"><span class="fileupload-new">Seleccionar plantilla</span><span class="fileupload-exists">Cambiar</span>
												<input type="file" name="planilla"/>
												</span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remover</a>
											</div>
										</div>
									</div>
								</div>
                                
                                </fieldset>
                                
                                
                                

								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button type="submit" class="btn btn-info"><i class="icon-save"></i> Iniciar importación</button>
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