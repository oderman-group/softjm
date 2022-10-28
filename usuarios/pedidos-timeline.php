<?php include("sesion.php");?>
<?php
$idPagina = 19;
$paginaActual['pag_nombre'] = "Timeline de pedidos";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<?php
$resultadoD = mysql_fetch_array(mysql_query("SELECT * FROM pedidos 
WHERE pedid_id='".$_GET["id"]."'",$conexion));
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



<style>

.timeline{
  --uiTimelineMainColor: var(--timelineMainColor, #222);
  --uiTimelineSecondaryColor: var(--timelineSecondaryColor, #fff);

  position: relative;
  padding-top: 3rem;
  padding-bottom: 3rem;
}

.timeline:before{
  content: "";
  width: 4px;
  height: 100%;
  background-color: var(--uiTimelineMainColor);

  position: absolute;
  top: 0;
}

.timeline__group{
  position: relative;
}

.timeline__group:not(:first-of-type){
  margin-top: 4rem;
}

.timeline__year{
  padding: .5rem 1.5rem;
  color: var(--uiTimelineSecondaryColor);
  background-color: var(--uiTimelineMainColor);

  position: absolute;
  left: 0;
  top: 0;
}

.timeline__box{
  position: relative;
}

.timeline__box:not(:last-of-type){
  margin-bottom: 30px;
}

.timeline__box:before{
  content: "";
  width: 100%;
  height: 2px;
  background-color: var(--uiTimelineMainColor);

  position: absolute;
  left: 0;
  z-index: -1;
}

.timeline__date{
  min-width: 65px;
  position: absolute;
  left: 0;

  box-sizing: border-box;
  padding: .5rem 1.5rem;
  text-align: center;

  background-color: var(--uiTimelineMainColor);
  color: var(--uiTimelineSecondaryColor);
}

.timeline__day{
  font-size: 2rem;
  font-weight: 700;
  display: block;
}

.timeline__month{
  display: block;
  font-size: .8em;
  text-transform: uppercase;
}

.timeline__post{
  padding: 1.5rem 2rem;
  border-radius: 2px;
  border-left: 3px solid var(--uiTimelineMainColor);
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .12), 0 1px 2px 0 rgba(0, 0, 0, .24);
  background-color: var(--uiTimelineSecondaryColor);
}

@media screen and (min-width: 641px){

  .timeline:before{
    left: 30px;
  }

  .timeline__group{
    padding-top: 55px;
  }

  .timeline__box{
    padding-left: 80px;
  }

  .timeline__box:before{
    top: 50%;
    transform: translateY(-50%);  
  }  

  .timeline__date{
    top: 50%;
    margin-top: -27px;
  }
}

@media screen and (max-width: 640px){

  .timeline:before{
    left: 0;
  }

  .timeline__group{
    padding-top: 40px;
  }

  .timeline__box{
    padding-left: 20px;
    padding-top: 70px;
  }

  .timeline__box:before{
    top: 90px;
  }    

  .timeline__date{
    top: 0;
  }
}

.timeline{
  --timelineMainColor: #4557bb;
  font-size: 16px;
}

/*
=====
DEMO
=====
*/

@media (min-width: 768px){

  html{
    font-size: 62.5%;
  }
}

@media (max-width: 767px){

  html{
    font-size: 55%;
  }
}

body{
  
  font-size: 1.6rem;
  color: #222;

  background-color: #f0f0f0;
  margin: 0;
  -webkit-overflow-scrolling: touch;   
  overflow-y: scroll;
  
  display: flex;
  flex-direction: column;
}

p{
  margin-top: 0;
  margin-bottom: 1.5rem;
  line-height: 1.5;
}

p:last-child{
  margin-bottom: 0;
}

.page{
  max-width: 800px;
  padding: 0rem 2rem 3rem;
  margin-left: auto;
  margin-right: auto;
  order: 1;
}

/*
=====
LinkedIn
=====
*/

.linkedin{
  background-color: #fff;
  text-align: center;
}

.linkedin__container{
  max-width: 1000px;
  padding: 10px;
  margin-left: auto;
  margin-right: auto;  
}

.linkedin__text{
  margin-top: 0;
  margin-bottom: 0;
}

.linkedin__link{
  color: #ff5c5c;
}
</style>


</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
            <?php include("includes/notificaciones.php");?>
			<div class="row-fluid">
				<div class="span4">
					
					<div class="content-widgets gray">
						<div class="widget-head green">
							<h3> Editar Pedido</h3>
						</div>
						<div class="widget-container">
							
						<form class="form-horizontal" method="post" action="sql.php">
                            <input type="hidden" name="idSql" value="72">
							<input type="hidden" name="id" value="<?=$_GET["id"];?>">
                            	
                                <div class="control-group">
									<label class="control-label">#Pedido</label>
									<div class="controls">
										<input type="text" class="span12" name="idPedido" value="<?=$resultadoD['pedid_id'];?>" readonly>
									</div>
								</div>
							
								<div class="control-group">
									<label class="control-label">Fecha Documento</label>
									<div class="controls">
										<input type="date" class="span12" name="fecha" value="<?=$resultadoD['pedid_fecha_propuesta'];?>">
									</div>
								</div>
                                

                                <div class="control-group">
									<label class="control-label">Estado Actual</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="estado">
											<option value=""></option>
											<option value="1" <?php if($resultadoD['pedid_estado']==1){echo "selected";}?>>En preparación</option>
											<option value="2" <?php if($resultadoD['pedid_estado']==2){echo "selected";}?>>En camino</option>
											<option value="3" <?php if($resultadoD['pedid_estado']==3){echo "selected";}?>>Entregado</option>
                                    	</select>
                                    </div>
                               </div>
							
								<div class="control-group">
									<label class="control-label">Empresa de envío</label>
									<div class="controls">
										<input type="text" class="span12" name="empresaEnvio" value="<?=$resultadoD['pedid_empresa_envio'];?>">
									</div>
								</div>
							
								<div class="control-group">
									<label class="control-label">Código de seguimiento</label>
									<div class="controls">
										<input type="text" class="span12" name="codigoSeguimiento" value="<?=$resultadoD['pedid_codigo_seguimiento'];?>">
									</div>
								</div>
                               
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
								</div>
							</form>	
	
						</div>
					</div>
					
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> Añadir Novedades</h3>
						</div>
						<div class="widget-container">
							
						<form class="form-horizontal" method="post" action="sql.php">
                            <input type="hidden" name="idSql" value="73">
							<input type="hidden" name="id" value="<?=$_GET["id"];?>">
                            	
                            <div class="control-group">
									<label class="control-label">Día</label>
									<div class="controls">
										<input type="text" class="span6" name="dia" value="<?=date("d");?>">
									</div>
								</div>
							<div class="control-group">
									<label class="control-label">Mes corto</label>
									<div class="controls">
										<input type="text" class="span6" name="mes" value="<?=date("M");?>">
									</div>
								</div>
							
							
                                

                                <div class="control-group">
									<label class="control-label">Estado</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción..." class="chzn-select span12" tabindex="2" name="estado">
											<option value=""></option>
											<option value="1" <?php if($resultadoD['pedid_estado']==1){echo "selected";}?>>En preparación</option>
											<option value="2" <?php if($resultadoD['pedid_estado']==2){echo "selected";}?>>En camino</option>
											<option value="3" <?php if($resultadoD['pedid_estado']==3){echo "selected";}?>>Entregado</option>
                                    	</select>
                                    </div>
                               </div>
							
							<div class="control-group">
									<label class="control-label">Novedad</label>
									<div class="controls">
										<input type="text" class="span12" name="novedad">
									</div>
								</div>
                               
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Añadir novedad</button>
								</div>
							</form>	
	
						</div>
					</div>
					
					
				</div>
				
				<div class="span8">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							
							<div class="page">
  <div class="timeline">
    <div class="timeline__group">
      <span class="timeline__year">EN PREPARACIÓN</span>
      
		<?php
		$preparacionNov = mysql_query("SELECT * FROM pedidos_novedades WHERE pednov_pedido='".$_GET["id"]."' AND pednov_estado=1",$conexion);
		while($preparacion = mysql_fetch_array($preparacionNov)){
		?>
		
			<div class="timeline__box">
			<div class="timeline__date">
			  <span class="timeline__day"><?=$preparacion['pednov_dia'];?></span>
			  <span class="timeline__month"><?=$preparacion['pednov_mes'];?></span>
			</div>
			<div class="timeline__post">
			  <div class="timeline__content">
				<p><?=$preparacion['pednov_novedad'];?></p>
			  </div>
			</div>
		  </div>
		
		<?php }?>
		
    </div>
	  
	  
    <div class="timeline__group">
      <span class="timeline__year">EN CAMINO</span>
      
		<?php
		$encaminoNov = mysql_query("SELECT * FROM pedidos_novedades WHERE pednov_pedido='".$_GET["id"]."' AND pednov_estado=2",$conexion);
		while($encamino = mysql_fetch_array($encaminoNov)){
		?>
		
			<div class="timeline__box">
			<div class="timeline__date">
			  <span class="timeline__day"><?=$encamino['pednov_dia'];?></span>
			  <span class="timeline__month"><?=$encamino['pednov_mes'];?></span>
			</div>
			<div class="timeline__post">
			  <div class="timeline__content">
				<p><?=$encamino['pednov_novedad'];?></p>
			  </div>
			</div>
		  </div>
		
		<?php }?>
		

		
    </div>
	  
	  
    <div class="timeline__group">
      <span class="timeline__year">ENTREGADO</span>
      
		
		
    </div>
  </div>
</div>


							
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