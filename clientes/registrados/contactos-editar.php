<?php 
include("sesion.php");

$tituloPagina = "Editar Contactos";

include("head.php");

$resultadoD = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM contactos WHERE cont_id='".$_GET["id"]."' AND cont_cliente_principal={$_SESSION["id_cliente"]}"), MYSQLI_BOTH);
?>
<link href="css/chosen.css" rel="stylesheet">
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
<?php include("funciones-js.php");?>

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
                        
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
                        <li><a href="contactos.php">Mis Contactos</a><span class="divider"><i class="icon-angle-right"></i></span></li>
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
							<form class="form-horizontal" method="post" action="contactos-actualizar.php">
                            <input type="hidden" name="idSql" value="6">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
                            	   
                               
                               <div class="control-group">
									<label class="control-label">Sucursal</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="sucursal">
											<option value=""></option>
                                            <?php
											$conOp = mysqli_query($conexionBdPrincipal,"SELECT * FROM sucursales WHERE sucu_cliente_principal='".$_SESSION["id_cliente"]."'");
											while($resOp = mysqli_fetch_array($conOp, MYSQLI_BOTH)){
											?>
                                            	<option value="<?=$resOp[0];?>" <?php if($resultadoD['cont_sucursal']==$resOp[0]) echo "selected";?>><?=$resOp[7];?></option>
                                            <?php
											}
											?>
                                    	</select>
                                    </div>
                               </div>
                                
                                <div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" class="span4" name="nombre" value="<?=$resultadoD['cont_nombre'];?>">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Email</label>
									<div class="controls">
										<input type="email" class="span4" name="email" value="<?=$resultadoD['cont_email'];?>">
									</div>
								</div>
                                
                                <div class="control-group">
									<label class="control-label">Celular</label>
									<div class="controls">
										<input type="text" class="span4" name="celular" maxlength="10" value="<?=$resultadoD['cont_celular'];?>">
                                        <span style="color:#F03;">Este valor sin puntos ni espacios. (3135912073)</span>
									</div>
								</div>
								
								
								<div class="control-group">
									<label class="control-label">Rol</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span4" tabindex="2" name="rol">
											<option value="3"></option>
											<option value="1" <?php if($resultadoD['cont_rol']==1) echo "selected";?>>Topógrafo</option>
											<option value="2" <?php if($resultadoD['cont_rol']==2) echo "selected";?>>Adminsitrativo</option>
											<option value="3" <?php if($resultadoD['cont_rol']==3) echo "selected";?>>Otro</option>
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