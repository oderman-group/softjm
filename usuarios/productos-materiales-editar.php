<?php include("sesion.php");?>
<?php
$idPagina = 70;
$paginaActual['pag_nombre'] = "Editar materiales";
?>
<?php include("verificar-paginas.php");?>
<?php include("head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<?php
$resultadoD = mysql_fetch_array(mysql_query("SELECT * FROM productos_materiales WHERE ppmt_id='".$_GET["id"]."'",$conexion));
?>
<?php
$producto = mysql_fetch_array(mysql_query("SELECT * FROM productos_soptec WHERE prod_id='".$_GET["pdto"]."'",$conexion));
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
                        <li><a href="productos-materiales.php?pdto=<?=$_GET["pdto"];?>"><b><?=$producto['prod_nombre'];?></b> / Materiales</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            <p><a href="productos-materiales-agregar.php?pdto=<?=$_GET["pdto"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a></p>
            <?php include("notificaciones.php");?>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<form class="form-horizontal" method="post" action="sql.php" enctype="multipart/form-data">
                            <input type="hidden" name="idSql" value="31">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
                            <input type="hidden" name="pdto" value="<?=$_GET["pdto"];?>">
                            
                            	<div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" class="span4" name="nombre" value="<?php echo $resultadoD[5];?>">
									</div>
								</div>
                            
                            	<div class="control-group">
									<label class="control-label">Tipo de material</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="tipo" onChange="material(this)">
											<option value=""></option>
                                            <option value="1" <?php if($resultadoD[2]==1){echo "selected";}?>>Documento</option>
                                            <option value="2" <?php if($resultadoD[2]==2){echo "selected";}?>>Video</option>
                                            <option value="3" <?php if($resultadoD[2]==3){echo "selected";}?>>Software</option>
                                    	</select>
                                    </div>
                               </div>
                               
                               <script type="text/javascript">
							    function material(valor){
									if(valor.value==1 || valor.value==3){
										document.getElementById("documento").style.display="block";
										document.getElementById("video").style.display="none";
									}else{
										document.getElementById("documento").style.display="none";
										document.getElementById("video").style.display="block";
									}
								}
                               </script>
                               
                                <div class="control-group" id="documento" style="display:block;">
									<label class="control-label">Escoja Documento/Software</label>
									<div class="controls">
										<input type="file" class="span4" name="documento" value="<?=$resultadoD[1];?>">
									</div>
                                    <?php if($resultadoD[2]==1 or $resultadoD[2]==3){echo '<a href="files/materiales/'.$resultadoD[1].'" target="_blank">'.$resultadoD[1].'</a>';}?>
								</div>
                                
                                <div class="control-group" id="video" style="display:block;">
									<label class="control-label">Código de Video (YouTube)</label>
									<div class="controls">
										<input type="text" class="span4" name="video" value="<?php if($resultadoD[2]==2){echo $resultadoD[1];}?>"> <span style="color:#C00;">Mira la imagen de ejemplo.</span>
									</div>
                                    <p style="margin-top:10px;"><img src="files/CodigoYoutube.png"></p>  
								</div>
                               
                               
                               <div class="control-group">
									<label class="control-label">Visible</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="activo">
											<option value=""></option>
                                            <option value="1" <?php if($resultadoD[3]==1){echo "selected";}?>>SI</option>
                                            <option value="0" <?php if($resultadoD[3]==0){echo "selected";}?>>NO</option>
                                    	</select>
                                    </div>
                               </div>

                                
                               
								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                                    <button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
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