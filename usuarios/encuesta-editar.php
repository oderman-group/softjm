<?php include("sesion.php");?>
<?php
$idPagina = 52;
$tituloPagina = "Editar Encuesta";
?>
<?php include("verificar-paginas.php");?>
<?php include("head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<?php
$resultadoD = mysql_fetch_array(mysql_query("SELECT * FROM encuesta_satisfaccion WHERE encs_id='".$_GET["id"]."'",$conexion));
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
						<li><a href="encuesta.php">Encuestas</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$tituloPagina;?></li>
					</ul>
				</div>
			</div>
            <p><a href="encuesta-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a></p>
            <?php include("notificaciones.php");?>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$tituloPagina;?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php">
                            <input type="hidden" name="idSql" value="29">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
  
                               <div class="control-group">
									<label class="control-label">Fecha</label>
									<div class="controls">
										<input type="text" class="span4" name="fecha" value="<?=$resultadoD['encs_fecha'];?>" readonly>
									</div>
								</div>
                               
                               <div class="control-group">
									<label class="control-label">Cliente</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="cliente">
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM clientes",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp['cli_id'];?>" <?php if($resultadoD['encs_cliente']==$resOp[0]){echo "selected";}?>><?=$resOp['cli_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Usuario que atendió</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="usuario">
											<option value=""></option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM usuarios",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp['usr_id'];?>" <?php if($resultadoD['encs_atendido']==$resOp[0]){echo "selected";}?>><?=$resOp['usr_nombre'];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                               
                               <div class="control-group">
									<label class="control-label">Producto que compró o cotizó</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="producto">
											<option value="0"></option>
                                            <option value="0">Ninguno</option>
                                            <?php
											$conOp = mysql_query("SELECT * FROM productos",$conexion);
											while($resOp = mysql_fetch_array($conOp)){
											?>
                                            	<option value="<?=$resOp['prod_id'];?>" <?php if($resultadoD['encs_producto']==$resOp[0]){echo "selected";}?>><?=$resOp['prod_nombre'];?></option>
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

                            
                            <h3 style="font-weight:bold;">Resultados de la encuesta (No editable)</h3>
                            <table width="100%" border="1" rules="all" align="center">
							<tr align="center" style="font-weight:bold; height:30px;">
                                <td align="left">Preguntas</td> 
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
							</tr>
                            <tr align="center">
                                <td align="left">¿El tiempo de atencion por parte del funcinario fue el adecuado?</td> 
                                <?php for($i=1; $i<=5; $i++){?>
                                	<td><input type="radio" value="<?=$i;?>" name="p1" disabled <?php if($i==$resultadoD['encs_p1']) echo "checked";?>></td>
                                <?php }?>
							</tr>
                            <tr align="center">
                                <td align="left">¿El trato por parte del funcionario fue cordial?</td> 
                                <?php for($i=1; $i<=5; $i++){?>
                                	<td><input type="radio" value="<?=$i;?>" name="p2" disabled <?php if($i==$resultadoD['encs_p2']) echo "checked";?>></td>
                                <?php }?>
							</tr>
                            <tr align="center">
                                <td align="left">¿Le fue resuelta su duda?</td> 
                                <?php for($i=1; $i<=5; $i++){?>
                                	<td><input type="radio" value="<?=$i;?>" name="p3" disabled <?php if($i==$resultadoD['encs_p3']) echo "checked";?>></td>
                                <?php }?>
							</tr>
                            <tr align="center">
                                <td align="left">Califique de 1-5 la asesoria prestada, siendo 1 la calificación mas baja y 5 la mas alta</td> 
                                <?php for($i=1; $i<=5; $i++){?>
                                	<td><input type="radio" value="<?=$i;?>" name="p4" disabled <?php if($i==$resultadoD['encs_p4']) echo "checked";?>></td>
                                <?php }?>
							</tr>
                            <tr align="center">
                                <td align="left">Califique de 1-5 la atención prestada, siendo 1 la calificación mas baja y 5 la mas alta</td> 
                                <?php for($i=1; $i<=5; $i++){?>
                                	<td><input type="radio" value="<?=$i;?>" name="p5" disabled <?php if($i==$resultadoD['encs_p5']) echo "checked";?>></td>
                                <?php }?>
							</tr>
                            <tr>
                                <td>Coloque aquí las Observaciones</td> 
                                <td colspan="5"><?=$resultadoD['encs_observaciones'];?></td>
							</tr>
							</table>
                                

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