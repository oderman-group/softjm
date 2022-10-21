<?php include("sesion.php");?>
<?php
$idPagina = 101;
$paginaActual['pag_nombre'] = "Filtro seguimiento de clientes";
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
						<li><a href="clientes.php">Clientes</a><span class="divider"><i class="icon-angle-right"></i></span></li>
                        <li><a href="clientes-seguimiento.php?idTK=<?=$_GET["idTK"];?>&cte=<?=$_GET["cte"];?>">Seguimiento de clientes</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="reportes/clientes-seguimiento.php" target="_blank">  
                               <div class="control-group">
									<label class="control-label">Usuario responsable</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="usuarioR">
											<option value="">Todos</option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM usuarios",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=strtoupper($resOp['usr_nombre']);?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>

                               <div class="control-group">
									<label class="control-label">Departamento</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="departamento">
											<option value="">Todos</option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM localidad_departamentos ORDER BY dep_nombre",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp['dep_id'];?>"><?=$resOp['dep_nombre'];?></option>
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
											<option value="">Todos</option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM localidad_ciudades INNER JOIN localidad_departamentos ON dep_id=ciu_departamento ORDER BY ciu_nombre",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp['ciu_id'];?>"><?=$resOp['ciu_nombre'].", ".$resOp['dep_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>

                               <div class="control-group">
									<label class="control-label">Tipo de documento</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="tipoDocumento">
											<option value="">Todos</option>
                                            <option value="2">NIT</option>
                                            <option value="3">Cédula</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Cliente</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="cliente">
											<option value="">Todos</option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM clientes",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp['cli_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Tipo de seguimiento</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="tipoS">
											<option value="">Todos</option>
                                            <option value="1">Comercial</option>
                                            <option value="2">Soporte</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               
                               <div class="control-group">
									<label class="control-label">Con cotización</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="cotizacion">
											<option value="">Todos</option>
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Con venta</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="venta">
											<option value="">Todos</option>
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Consiguió datos</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="datos">
											<option value="">Todos</option>
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Desde</label>
									<div class="controls">
										<input type="date" class="span3" name="desde">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Hasta</label>
									<div class="controls">
										<input type="date" class="span3" name="hasta">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Ordenar por</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="orden">
											<option value="cseg_id"></option>
                                            <option value="cseg_usuario_responsable">Usuario responsabe</option>
                                            <option value="cseg_cliente">Cliente</option>
                                            <option value="cseg_tipo">Tipo de seguimiento</option>
                                            <option value="cseg_cotizacion">Cotización</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Forma de orden</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="formaOrden">
											<option value="ASC"></option>
                                            <option value="ASC">Ascendente</option>
                                            <option value="DESC">Descendente</option>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Formato de informe</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="formato">
											<option value="1"></option>
                                            <option value="1">Para imprimir</option>
                                            <option value="2">Excel</option>
                                    	</select>
                                    </div>
                               </div>
                               
                              
								<div class="form-actions">
                                	<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Generar informe</button>
								</div>
                              
                                

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