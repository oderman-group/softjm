<?php include("sesion.php");?>
<?php
$idPagina = 110;
$paginaActual['pag_nombre'] = "Enviar portafolios";
?>
<?php include("verificar-paginas.php");?>
<?php include("head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>

<?php
if($_GET["em"]==3){
	mysql_query("UPDATE clientes SET cli_estado_mercadeo=3, cli_estado_mercadeo_fecha=now(), cli_estado_mercadeo_usuario='".$_SESSION["id"]."' WHERE cli_id='".$_GET["cte"]."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}	
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
<?php include("texto-editor.php");?>
</head>
<body>
<div class="layout">
	<?php include("encabezado.php");?>
    
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php" enctype="multipart/form-data">
                            <input type="hidden" name="idSql" value="47">
							<fieldset class="default">
								<legend>Opciones</legend>
								
									<div class="control-group">
                                        <label class="control-label">Clientes</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja varias opciones..." class="chzn-select span8" multiple tabindex="2" name="clientes[]">
                                                <option value=""></option>
                                                <?php
                                                $conOp = mysql_query("SELECT * FROM clientes",$conexion);
                                                while($resOp = mysql_fetch_array($conOp)){
                                                ?>
                                                    <option value="<?=$resOp[0];?>" <?php if($resOp[0]==$_GET["cte"]){echo "selected";}?>><?=$resOp[1]." (".$resOp['cli_email'].")";?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                   </div>
								
									<div class="control-group">
                                        <label class="control-label">Portafolios</label>
                                        <div class="controls">
                                            <select data-placeholder="Escoja varias opciones..." class="chzn-select span8" multiple tabindex="2" name="portafolios[]">
                                                <option value=""></option>
                                                
                                                <option value="1">Portafolio Topografía</option>
                                                <option value="2">Portafolio Construcción y Arquitectura</option>
												<option value="3">Portafolio Accesorios</option>
												<option value="4">Portafolio Agricultura</option>
												<option value="5">Portafolio Cartografía</option>
                                               
                                               <?php if($_SESSION["bd"]=='odermancom_orioncrm_exacta'){?>
                                                    <option value="6">Portafolio Exacta Ing.</option>
                                                <?php }?>

                                                <option value="7">Brochure Laboratorio</option>

                                                <option value="8">Portafolio Drones.</option>
                                                <option value="9">Portafolio Estaciones totales</option>
                                                
                                            </select>
                                        </div>
                                   </div>
                                   
                               </fieldset>
                               
                                
                               
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-envelope"></i> Enviar Portafolios</button>
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