<?php include("sesion.php");?>
<?php
$idPagina = 85;
$tituloPagina = "Editar sucursales";
?>
<?php include("verificar-paginas.php");?>
<?php include("head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<?php
$resultadoD = mysql_fetch_array(mysql_query("SELECT * FROM sucursales WHERE sucu_id='".$_GET["id"]."'",$conexion));
?>
<!-- styles -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="css/font-awesome.css">
<!--[if IE 7]>
<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
<![endif]-->
<link href="css/chosen.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<link href="css/theme-blue.css" rel="stylesheet">

<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->
<link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
<!--fav and touch icons -->
<link rel="shortcut icon" href="ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
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
<?php include("texto-editor.php");?>
</head>
<body>
<div class="layout">
	<?php include("encabezado.php");?>
    
    <?php include("barra-izq.php");?>
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$tituloPagina;?></h3>
						
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
                        <li><a href="clientes-sucursales.php?cte=<?=$_GET["cte"];?>">Sucursales</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$tituloPagina;?></li>
					</ul>
				</div>
			</div>
            <?php include("notificaciones.php");?>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$tituloPagina;?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php">
                            <input type="hidden" name="idSql" value="38">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
                            <input type="hidden" name="cte" value="<?=$_GET["cte"];?>">
                            	   
                                <div class="control-group">
									<label class="control-label">Cliente Principal</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="cliente">
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM clientes",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['sucu_cliente_principal']==$resOp[0]) echo "selected";?>><?=$resOp[1];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                                
                                <div class="control-group">
									<label class="control-label">Nombre Sucursal</label>
									<div class="controls">
										<input type="text" class="span4" name="nombre" value="<?=$resultadoD['sucu_nombre'];?>">
									</div>
								</div>

                                
                                <div class="control-group">
									<label class="control-label">Teléfono</label>
									<div class="controls">
										<input type="text" class="span4" name="telefono" value="<?=$resultadoD['sucu_telefono'];?>">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Celular</label>
									<div class="controls">
										<input type="text" class="span4" name="celular" maxlength="10" value="<?=$resultadoD['sucu_celular'];?>">
                                        <span style="color:#F03;">Este valor sin puntos ni espacios. (3135912073)</span>
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Teléfonos complementarios</label>
									<div class="controls">
										<input type="text" class="span4" name="telefonos" value="<?=$resultadoD['sucu_telefonos'];?>">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Dirección</label>
									<div class="controls">
										<input type="text" class="span4" name="direccion" value="<?=$resultadoD['sucu_direccion'];?>">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Ciudad</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="ciudad">
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM localidad_ciudades INNER JOIN localidad_departamentos ON dep_id=ciu_departamento ORDER BY ciu_nombre",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp['ciu_id'];?>" <?php if($resultadoD['sucu_ciudad']==$resOp['ciu_id']){echo "selected";}?>><?=$resOp['ciu_nombre'].", ".$resOp['dep_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                                
                               
                                
                               
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<button type="button" class="btn btn-danger">Cancelar</button>
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