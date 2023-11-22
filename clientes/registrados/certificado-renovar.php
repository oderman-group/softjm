<?php 
include("sesion.php");

$idPagina = 254;
$tituloPagina = "Renovar certificado";

include("verificar-paginas.php");
include("head.php");

$resultadoD = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes 
WHERE cli_id='".$_SESSION["id_cliente"]."'"), MYSQLI_BOTH);

$equipo = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones WHERE rem_id='".$_GET["id"]."' 
AND rem_id_empresa={$_SESSION['id_empresa']}"), MYSQLI_BOTH);
?>
<link href="css/chosen.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<link href="css/theme-wooden.css" rel="stylesheet">
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
                        
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="renovar-certificado.php" target="_blank">
                            <input type="hidden" name="idSql" value="3">
                            <input type="hidden" name="idCertificado" value="<?=$_GET['id'];?>">
                            	
                                <div class="control-group">
									<label class="control-label">Certificado</label>
									<div class="controls">
										<input type="text" class="span3" name="certificado" value="C<?=$_GET['id'];?>" required readonly>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Equipo</label>
									<div class="controls">
										<input type="text" class="span3" name="equipo" value="<?=$equipo['rem_equipo'];?>" required readonly>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Serial</label>
									<div class="controls">
										<input type="text" class="span3" name="serial" value="<?=$equipo['rem_serial'];?>" required readonly>
									</div>
								</div>
                                
								<span style="color: blue;">Los precios mostrados a continuación no tienen IVA.</span>
								
                                <div class="control-group">
									<label class="control-label">Servicio</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span6" tabindex="2" name="servicio" required>
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM servicios WHERE serv_id_empresa='".$_SESSION["id_empresa"]."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
                                                $precio = !empty($resOp['serv_precio']) ? $resOp['serv_precio'] : 0;
											?>
                                            	<option value="<?=$resOp[0];?>"><?=$resOp['serv_nombre']." ($".number_format($precio,0,",",".").")";?></option>
                                            <?php
											}
											?>
                                    	</select>
									</div>
									
								</div>
								
								<div class="control-group">
									<label class="control-label">&nbsp;</label>
									<div class="controls">
									<div style="width: 600px; height: 150px; overflow-y: scroll; background-color: white;">
									<strong>Términos y condiciones para actualización de certificado</strong><br><br>
-          El cliente deberá presentar un informe con firma del topógrafo y número de tarjeta profesional donde indica que el equipo fue inspeccionado en cierres angulares y de distancias y que los errores percibidos son acordes a la precisión del instrumento.<br>

-          JMendoza equipos ni el jefe del departamento técnico se harán responsables del error en campo ni de trabajos realizados con el instrumento, teniendo en cuenta que el topógrafo es quien verifico y aprobó su buen funcionamiento.<br>

-          Esta renovación se hará 6 meses después de un mantenimiento y el equipo deberá ser presentado para mantenimiento una vez pasen 6 meses después de la renovación.<br>

-          Por ningún motivo se realizara actualización de certificado a los equipos que 6 meses antes no se les haya realizado el mantenimiento.<br>

-          Esta campaña es direccionada a los Topografos que tienen dificultades para el desplazamiento del equipo a los laboratorios técnicos, por la lejanía de sus obras o su disponibilidad de tiempo pero que están en las condiciones para definir si está o no ajustado su instrumento, sin embargo para JMendoza equipos lo mejor sería que el equipo tuviera mantenimiento cada 6 meses en aras de preservar la vida útil del mismo. 
									</div>
									</div>		
								</div>
								
								<div class="control-group">
									<label class="control-label">He leído y acepto los términos</label>
									<div class="controls">
										<input type="checkbox" class="span2" name="terminos" value="1" required>
									</div>
								</div>
                                
                               
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Continuar al pago</button>
									<a href="documentos.php" class="btn btn-danger">Cancelar</a>
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