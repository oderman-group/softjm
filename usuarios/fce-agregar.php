<?php include("sesion.php");?>
<?php
$idPagina = 131;
$paginaActual['pag_nombre'] = "Agregar factura compra extranjera";
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
						<li><a href="facturas.php">Facturas</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
                            <input type="hidden" name="idSql" value="84">
							<input type="hidden" name="fce" value="1">
                            <input type="hidden" name="preferencia" value="3"> 


							<div class="control-group">
									<label class="control-label">Concepto</label>
									<div class="controls">
										<input type="text" class="span8" name="concepto" required>
									</div>
								</div>

								
								<div class="control-group">
									 <label class="control-label">Escoja un proveedor</label>
									 <div class="controls">
										 <select data-placeholder="Escoja una opción..." class="chzn-select span8" tabindex="2" name="proveedor" required>
											 <option value=""></option>
											 <?php
											 $conOp = mysql_query("SELECT * FROM proveedores",$conexion);
											 while($resOp = mysql_fetch_array($conOp)){
											 ?>
												 <option value="<?=$resOp[0];?>"><?=$resOp['prov_nombre'];?></option>
											 <?php
											 }
											 ?>
										 </select>
									 </div>
									
								</div>
 
								
									
                               
								<div class="control-group">
									<label class="control-label">Fecha de la propuesta</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaPropuesta" required value="<?=date("Y-m-d");?>">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">Fecha de vencimiento</label>
									<div class="controls">
										<input type="date" class="span4" name="fechaVencimiento" required value="<?=date("Y-m-d");?>">
									</div>
								</div>
								
								
								
								<div class="control-group">
									<label class="control-label">Forma de pago</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="formaPago">
											<option value=""></option>
                                            <option value="1" selected>Contado</option>
                                            <option value="2">Crédito</option>
                                    	</select>
                                    </div>
                               </div>
								
								<div class="control-group">
									<label class="control-label">Moneda</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="moneda">
											<option value=""></option>
                                            <option value="1" selected>USD</option>
                                            <option value="2">EURO</option>
                                    	</select>
                                    </div>
							   </div>
							   

								
								<div class="control-group">
										<label class="control-label">Productos</label>
										<div class="controls">
											<select data-placeholder="Escoja una opción..." class="chzn-select span10" tabindex="2" name="producto[]" multiple>
												<option value=""></option>
												<?php
												$conOp = mysql_query("SELECT * FROM productos 
												INNER JOIN productos_categorias ON catp_id=prod_categoria
												ORDER BY prod_nombre
												",$conexion);
												while($resOp = mysql_fetch_array($conOp)){
												?>
													<option value="<?=$resOp['prod_id'];?>"><?=$resOp['prod_id'].". ".$resOp['prod_referencia']." ".$resOp['prod_nombre']." - [HAY ".$resOp['prod_existencias']."]";?></option>
												<?php
												}
												?>
											</select>
										</div>
								   </div>
								
									
								
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-arrow-right"></i> Continuar</button>
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