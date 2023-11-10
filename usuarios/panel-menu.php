<?php 
include("sesion.php");

$idPagina = "195";

include("includes/verificar-paginas.php");
include("includes/head.php");
?>
<!-- styles -->
<link href="css/jquery.gritter.css" rel="stylesheet">
<link href="css/tablecloth.css" rel="stylesheet">

<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.sparkline.js"></script>
<script src="js/bootstrap-fileupload.js"></script>
<script src="js/jquery.metadata.js"></script>
<script src="js/jquery.tablesorter.min.js"></script>
<script src="js/jquery.tablecloth.js"></script>
<script src="js/jquery.flot.js"></script>
<script src="js/jquery.flot.selection.js"></script>
<script src="js/excanvas.js"></script>
<script src="js/jquery.flot.pie.js"></script>
<script src="js/jquery.flot.stack.js"></script>
<script src="js/jquery.flot.time.js"></script>
<script src="js/jquery.flot.tooltip.js"></script>
<script src="js/jquery.flot.resize.js"></script>
<script src="js/jquery.collapsible.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.gritter.js"></script>
<script src="js/tiny_mce/jquery.tinymce.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script>
/*===============================================
TEXT EDITOR
==================================================*/

        $(function() {
		$('textarea.chat-inputbox').tinymce({
			script_url : 'js/tiny_mce/tiny_mce.js',
			theme : "simple"
			});
		});

/*===============================================
TBALE THEMES
==================================================*/
$(function() {
        $(".paper-table").tablecloth({
          theme: "paper",
          striped: true,
          sortable: true,
          condensed: false
        });
      });
	  
//$(function(){
		// global setting override
        /*
		$.extend($.gritter.options, {
		    class_name: 'gritter-light', // for light notifications (can be added directly to $.gritter.add too)
		    position: 'bottom-left', // possibilities: bottom-left, bottom-right, top-left, top-right
			fade_in_speed: 100, // how fast notifications fade in (string or int)
			fade_out_speed: 100, // how fast the notices fade out
			time: 3000 // hang on the screen for...
		});
        */
/**=========================
ONLOAD NOTIFICATION 
==============================**/

/**=========================
SPARKLINE MINI CHART
==============================**/
$(function () {
    //CLIENTES
	$(".line-min-chart").sparkline([<?=$clientes[0];?>, <?=$clientes[1];?>, <?=$clientes[2];?>, <?=$clientes[3];?>, <?=$clientes[4];?>, <?=$clientes[5];?>, <?=$clientes[6];?>, <?=$clientes[7];?>, <?=$clientes[8];?>, <?=$clientes[9];?>, <?=$clientes[10];?>, <?=$clientes[11];?>], {
        type: 'line',
        width: '100',
        height: '40',
        lineColor: '#2b2b2b',
        fillColor: '#e5e5e5',
        lineWidth: 2,
        highlightSpotColor: '#0e8e0e',
        spotRadius: 3,
        drawNormalOnTop: true
    });
    //VENTAS
	$(".bar-min-chart").sparkline([<?=$ventas[0];?>, <?=$ventas[1];?>, <?=$ventas[2];?>, <?=$ventas[3];?>, <?=$ventas[4];?>, <?=$ventas[5];?>, <?=$ventas[6];?>, <?=$ventas[7];?>, <?=$ventas[8];?>, <?=$ventas[9];?>, <?=$ventas[10];?>, <?=$ventas[11];?>], {
        type: 'bar',
        height: '40',
        barWidth: 8,
        barSpacing: 3,
        barColor: '#007f00'
    });
    $(".pie-min-chart").sparkline([3, 5, 2, 10, 8], {
        type: 'pie',
        width: '40',
        height: '40'
    });
	/* facturación*/
    $(".tristate-min-chart").sparkline([1, 1, 0, 1, -1, -1, 1, -1, 0, 0, 1, 1], {
        type: 'tristate',
        height: '40',
        posBarColor: '#bf005f',
        negBarColor: '#ff7f00',
        zeroBarColor: '#545454',
        barWidth: 4,
        barSpacing: 1
    });
});
/**=========================
LEFT NAV ICON ANIMATION 
==============================**/
$(function () {
    $(".left-primary-nav a").hover(function () {
        $(this).stop().animate({
            fontSize: "30px"
        }, 200);
    }, function () {
        $(this).stop().animate({
            fontSize: "24px"
        }, 100);
    });
});
</script>
<script type="text/javascript">
/*===============================================
FLOT BAR CHART
==================================================*/

    var data7_1 = [
        [1354586000000, 153],
        [1354587000000, 658],
        [1354588000000, 198],
        [1354589000000, 663],
        [1354590000000, 801],
        [1354591000000, 1080],
        [1354592000000, 353],
        [1354593000000, 749],
        [1354594000000, 523],
        [1354595000000, 258],
        [1354596000000, 688],
        [1354597000000, 364]
    ];
    var data7_2 = [
        [1354586000000, 53],
        [1354587000000, 65],
        [1354588000000, 98],
        [1354589000000, 83],
        [1354590000000, 80],
        [1354591000000, 108],
        [1354592000000, 120],
        [1354593000000, 74],
        [1354594000000, 23],
        [1354595000000, 79],
        [1354596000000, 88],
        [1354597000000, 36]
    ];
    $(function () {
        $.plot($("#visitors-chart #visitors-container"), [{
            data: data7_1,
            label: "Page View",
            lines: {
                fill: true
            }
        }, {
            data: data7_2,
            label: "Online User",
            points: {
                show: true
            },
            lines: {
                show: true
            },
            yaxis: 2
        }
        ],
        {
            series: {
                lines: {
                    show: true,
                    fill: false
                },
                points: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: "#ffffff",
                    symbol: "circle",
                    radius: 5,
                },
                shadowSize: 0,
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#f9f9f9",
                borderWidth: 1
            },
            colors: ["#b086c3", "#ea701b"],
            tooltip: true,
            tooltipOpts: {
				  shifts: { 
					  x: -100                     //10
				  },
                defaultTheme: false
            },
            xaxis: {
                mode: "time",
                timeformat: "%0m/%0d %0H:%0M"
            },
            yaxes: [{
                /* First y axis */
            }, {
                /* Second y axis */
                position: "right" /* left or right */
            }]
        }
        );
    });
</script>
<script type="text/javascript">
/*===============================================
FLOT PIE CHART
==================================================*/

    $(function () {
        var data = [{
            label: "Page View",
            data: 70
        }, {
            label: "Online User",
            data: 30
        }];
        var options = {
            series: {
                pie: {
                    show: true,
					innerRadius: 0.5,
            show: true
                }
            },
            legend: {
                show: true
            },
            grid: {
                hoverable: true,
                clickable: true
            },
			 colors: ["#b086c3", "#ea701b"],
            tooltip: true,
            tooltipOpts: {
				shifts: { 
					  x: -100                     //10
				  },
                defaultTheme: false
            }
        };
        $.plot($("#pie-chart-donut #pie-donutContainer"), data, options);
    });
</script>

<?php include("includes/funciones-js.php");?>

</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
	
	<div class="main-wrapper">
		<div class="container-fluid">
			
			<?php 
		if(isset($_GET["p"])) {
				if($_GET["p"]==1){
			?>
			<h2>CRM</h2>		
			
			<?php
			$tikets = $conexionBdPrincipal->query("SELECT * FROM cliente_seguimiento
			INNER JOIN clientes ON cli_id=cseg_cliente
			INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
			WHERE cseg_usuario_encargado='".$_SESSION["id"]."' AND cseg_fecha_proximo_contacto!='0000-00-00'
			ORDER BY cseg_fecha_proximo_contacto DESC
			");
			?>
			
			<p>&nbsp;</p>
			
			<div class="row-fluid">
				
				<div class="span5">

						<div class="content-widgets gray">
						<div class="widget-head orange">
							<h3><i class="icon-phone"></i> Mis llamadas</h3>
						</div>
						<div class="widget-container">
							<ul class="sample-noty">
								<?php
								$llamadas = $conexionBdPrincipal->query("SELECT * FROM cliente_seguimiento 
								INNER JOIN clientes ON cli_id=cseg_cliente
								WHERE cseg_usuario_encargado='".$_SESSION["id"]."' AND (cseg_canal_proximo_contacto=2 OR cseg_canal_proximo_contacto=3) AND cseg_realizado IS NULL");
								
								while($llamada = mysqli_fetch_array($llamadas)){
								$consultaClienteSeguimiento=$conexionBdPrincipal->query("SELECT DATEDIFF(cseg_fecha_proximo_contacto,now()) FROM cliente_seguimiento 
								WHERE cseg_id='".$llamada['cseg_id']."'");
								$pahoy = mysqli_fetch_array($consultaClienteSeguimiento, MYSQLI_BOTH);
								
								$consultaContactos=$conexionBdPrincipal->query("SELECT * FROM contactos WHERE cont_id='".$llamada['cseg_contacto']."'");
								$contacto = mysqli_fetch_array($consultaContactos, MYSQLI_BOTH);
								$addContacto = '';
								if($contacto[0]!=""){$addContacto = '. El contacto de esta empresa es <a href="clientes-contactos-editar.php?id='.$contacto['cont_id'].'&cte='.$llamada['cli_id'].'">'.$contacto['cont_nombre'].' ('.$contacto['cont_telefono'].' - '.$contacto['cont_celular'].')</a>';}	
									
								if($pahoy[0]<>0) continue;
								?>
									<li>
										<a href="bd_update/cliente-seguimiento-estado-update.php?get=28&id=<?=$llamada['cseg_id'];?>" title="Completar tarea"><i class="icon-ok-sign"></i></a>
										<a href="clientes-seguimiento-editar.php?id=<?=$llamada['cseg_id'];?>&idTK=<?=$llamada['cseg_tiket'];?>" title="Ver detalles" target="_blank"><i class="icon-reorder"></i></a>
										
										LLamar a <a href="clientes-editar.php?id=<?=$llamada['cli_id'];?>" id="add-regular"><?=$llamada['cli_nombre']." (".$llamada['cli_telefono']." - ".$llamada['cli_celular'].")";?></a> 
										<?=$addContacto;?> para el asunto <strong><?=$llamada['cseg_asunto'];?></strong></li>
								<?php
								}
								?>
							</ul>
						</div>
					</div>
					
						<div class="content-widgets gray">
						<div class="widget-head green">
							<h3><i class="icon-calendar"></i> Mis reuniones</h3>
						</div>
						<div class="widget-container">
							<ul class="sample-noty">
								<?php
								$llamadas = $conexionBdPrincipal->query("SELECT * FROM cliente_seguimiento 
								INNER JOIN clientes ON cli_id=cseg_cliente
								WHERE cseg_usuario_encargado='".$_SESSION["id"]."' AND (cseg_canal_proximo_contacto=4 OR cseg_canal_proximo_contacto=5) AND cseg_realizado IS NULL");
								
								while($llamada = mysqli_fetch_array($llamadas)){
								$consultaClienteSeguimiento=$conexionBdPrincipal->query("SELECT DATEDIFF(cseg_fecha_proximo_contacto,now()) FROM cliente_seguimiento 
								WHERE cseg_id='".$llamada['cseg_id']."'");
								$pahoy = mysqli_fetch_array($consultaClienteSeguimiento, MYSQLI_BOTH);
								
								$consultaContactos=$conexionBdPrincipal->query("SELECT * FROM contactos
								WHERE cont_id='".$llamada['cseg_contacto']."'");
								$contacto = mysqli_fetch_array($consultaContactos, MYSQLI_BOTH);
								$addContacto = '';
								if($contacto[0]!=""){$addContacto = '. El contacto de esta empresa es <a href="clientes-contactos-editar.php?id='.$contacto['cont_id'].'&cte='.$llamada['cli_id'].'">'.$contacto['cont_nombre'].' ('.$contacto['cont_telefono'].' - '.$contacto['cont_celular'].')</a>';}	
									
								if($pahoy[0]<>0) continue;
								?>
									<li>
										<a href="bd_update/cliente-seguimiento-estado-update.php?get=28&id=<?=$llamada['cseg_id'];?>" title="Completar tarea"><i class="icon-ok-sign"></i></a>
										<a href="clientes-seguimiento-editar.php?id=<?=$llamada['cseg_id'];?>&idTK=<?=$llamada['cseg_tiket'];?>" title="Ver detalles" target="_blank"><i class="icon-reorder"></i></a>
										
										Reunión con <a href="clientes-editar.php?id=<?=$llamada['cli_id'];?>" id="add-regular"><?=$llamada['cli_nombre']." (".$llamada['cli_telefono']." - ".$llamada['cli_celular'].")";?></a> 
										<?=$addContacto;?> para el asunto <strong><?=$llamada['cseg_asunto'];?></strong></li>
								<?php
								}
								?>
							</ul>
						</div>
					</div>
					
					<div class="content-widgets gray">
						<div class="widget-head blue">
							<h3><i class="icon-trophy"></i> Oportunidades</h3>
						</div>
						<div class="widget-container">
							<ul class="sample-noty">
								<li>Hay una oportunidad con <a href="#" id="add-regular">Nombre del cliente</a> para el asunto...</li>
							</ul>
						</div>
					</div>
					
					<?php
					$ConsultaNumTikets=$conexionBdPrincipal->query("SELECT * FROM clientes_tikets
					INNER JOIN clientes ON cli_id=tik_cliente
					WHERE tik_usuario_responsable='".$_SESSION["id"]."' AND tik_estado=1 
					ORDER BY tik_tipo_tiket DESC
					");
					$NumtiketsI = $ConsultaNumTikets->num_rows;

					$tiketsI = $conexionBdPrincipal->query("SELECT * FROM clientes_tikets
					INNER JOIN clientes ON cli_id=tik_cliente
					WHERE tik_usuario_responsable='".$_SESSION["id"]."' AND tik_estado=1 
					ORDER BY tik_tipo_tiket DESC
					LIMIT 0,5
					");
					?>
					<div class="content-widgets gray">
						<div class="widget-head" style="background-color: #eb4132;">
							<h3><i class="icon-list"></i> Tickets Abiertos (<?=$NumtiketsI;?>)</h3>
						</div>
							<?php $i=1; while($tkResI = mysqli_fetch_array($tiketsI, MYSQLI_BOTH)){?>
                            <ul class="sample-noty">
								<li><a href="clientes-tikets-editar.php?id=<?=$tkResI['tik_id'];?>" style="color:#000;" target="_blank">
								<?="<b>".$i.")</b> ".$tkResI['tik_asunto_principal']." (<b>".$tkResI['cli_nombre']."</b>)</a><br>
								<span style='color:gray; font-size:10px;'>Creado en: ".$tkResI['tik_fecha_creacion']."</span>";?>
								</li>
								
								<p><a href="bd_update/clientes-tikets-actualizar-estado.php?get=29&id=<?=$tkResI['tik_id'];?>" onClick="if(!confirm('Desea cerrar este ticket?')){return false;}" style="text-decoration: underline;">Cerrar Ticket</a></p>
							</ul>
                            <?php $i++;}?>
                            
                            <?php if($NumtiketsI>5){?><div align="center"><a href="clientes-tikets.php?resp=<?=$_SESSION["id"];?>" class="btn btn-mini btn-danger" style="margin:10px; color:#FFF;">VER TODOS</a></div><?php }?>

					</div>
					
				</div>
				
				<div class="span7">
					<div class="alert alert-info">
						<i class="icon-exclamation-sign"></i>
						<strong>Información!</strong>
						Haga click sobre el botón <b>completar tarea</b> y ésta quedará completada y desaparecerá de esta lista.
					</div>
					
					<div class="tab-widget">
						<ul class="nav nav-tabs" id="myTab1">
							<li class="active"><a href="#user"><i class="icon-tasks"></i> Tareas para hoy</a></li>
							<li><a href="#task"><i class=" icon-tasks"></i> Tareas para mañana</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="user">
								<div class="user_list">
									
									<?php
									while($tkRes = mysqli_fetch_array($tikets, MYSQLI_BOTH)){
										switch($tkRes['cseg_tipo']){
											case 1: $tipoS = 'Comercial'; $etiquetaT='success'; break;
											case 2: $tipoS = 'Soporte'; $etiquetaT='info'; break;
										}
										$consultaClienteSeguimiento=$conexionBdPrincipal->query("SELECT DATEDIFF(cseg_fecha_proximo_contacto,now()), cseg_usuario_encargado FROM cliente_seguimiento 
										WHERE cseg_id='".$tkRes['cseg_id']."'");
										$segHoy = mysqli_fetch_array($consultaClienteSeguimiento, MYSQLI_BOTH);
										if(($segHoy[0]<0 and $tkRes['cseg_realizado']==1) or $segHoy[0]>0) continue;
									?>
                                    <div class="user_block">
										<div class="info_block">
											<div class="widget_thumb">
												<img width="46" height="46" alt="User" src="images/user-thumb1.png">
											</div>
											<ul class="list_info clearfix">
												<?php if($segHoy[0]<0){?><li><span style="color: red;">TAREA VENCIDA (Hace <?=($segHoy[0]*-1);?> días)</span></li><?php }?>
												<li><span>Cliente: <i><a href="clientes-editar.php?id=<?=$tkRes['cli_id'];?>" target="_blank"><?=$tkRes['cli_nombre'];?></a></i></span></li>
                                                <li><span>Asunto: <b><?=$tkRes['cseg_asunto'];?></b></span></li>
												<li><span>Creador del seguimiento: <b><?=$tkRes['usr_nombre'];?></b></span></li>
                                                <li><span>Fecha de contacto anterior: <b><?=$tkRes['cseg_fecha_contacto'];?></b></span></li>
                                                <li><span>Fecha programada: <b><?=$tkRes['cseg_fecha_proximo_contacto'];?></b></span></li>
												<li><span>Tipo de seguimiento: <b><?=$tipoS;?></b></span></li>
											</ul>
										</div>
										<div class="clearfix">
											<div class="btn-group pull-left">
												<a href="clientes-seguimiento-editar.php?id=<?=$tkRes['cseg_id'];?>&idTK=<?=$tkRes['cseg_tiket'];?>" class="btn btn-mini" target="new"><i class=" icon-list-alt"></i> Más detalles</a>
                                                <!--<a href="#" onClick='window.open("clientes-tikets-editar.php?id=<?=$tkRes['tik_id'];?>","EditarTiket","width=1200,height=800,menubar=no")' class="btn "><i class=" icon-edit"></i> Editar</a>-->
											</div>
											<div class="btn-group pull-right">
												<a href="bd_update/cliente-seguimiento-estado-update.php?id=<?=$tkRes['cseg_id'];?>&get=28" class="btn"><i class="icon-ok-circle"></i> Completar tarea</a>
											</div>
										</div>
									</div>
									<?php }?>
                                    
								</div>
							</div>

							<div class="tab-pane" id="task">
								<div class="user_list">
									
									<?php
									$tikets2 = $conexionBdPrincipal->query("SELECT * FROM cliente_seguimiento
									INNER JOIN clientes ON cli_id=cseg_cliente
									INNER JOIN usuarios ON usr_id=cseg_usuario_responsable
									WHERE cseg_usuario_encargado='".$_SESSION["id"]."' AND cseg_fecha_proximo_contacto!='0000-00-00'
									");
									while($tkRes2 = mysqli_fetch_array($tikets2, MYSQLI_BOTH)){
										switch($tkRes2['cseg_tipo']){
											case 1: $tipoS = 'Comercial'; $etiquetaT='success'; break;
											case 2: $tipoS = 'Soporte'; $etiquetaT='info'; break;
										}
										$consultaClienteSeguimiento=$conexionBdPrincipal->query("SELECT DATEDIFF(cseg_fecha_proximo_contacto,now()), cseg_usuario_encargado FROM cliente_seguimiento 
										WHERE cseg_id='".$tkRes2['cseg_id']."'");
										$segHoy2 = mysqli_fetch_array($consultaClienteSeguimiento, MYSQLI_BOTH);
										if($segHoy2[0]!=1) continue;
									?>
                                    <div class="user_block">
										<div class="info_block">
											<div class="widget_thumb">
												<img width="46" height="46" alt="User" src="images/user-thumb1.png">
											</div>
											<ul class="list_info clearfix">
												<li><span>Cliente: <i><a href="#"><?=$tkRes2['cli_nombre'];?></a></i></span></li>
                                                <li><span>Asunto: <b><?=$tkRes2['cseg_asunto'];?></b></span></li>
												<li><span>Creador del seguimiento: <b><?=$tkRes2['usr_nombre'];?></b></span></li>
                                                <li><span>Fecha de contacto anterior: <b><?=$tkRes2['cseg_fecha_contacto'];?></b></span></li>
                                                <li><span>Fecha programada: <b><?=$tkRes2['cseg_fecha_proximo_contacto'];?></b></span></li>
												<li><span>Tipo de seguimiento: <b><?=$tipoS;?></b></span></li>
											</ul>
										</div>
										<div class="clearfix">
											<div class="btn-group pull-left">
												<a href="clientes-seguimiento-editar.php?id=<?=$tkRes2['cseg_id'];?>&idTK=<?=$tkRes2['cseg_tiket'];?>" class="btn btn-mini" target="new"><i class=" icon-list-alt"></i> Más detalles</a>
                                                <!--<a href="#" onClick='window.open("clientes-tikets-editar.php?id=<?=$tkRes['tik_id'];?>","EditarTiket","width=1200,height=800,menubar=no")' class="btn "><i class=" icon-edit"></i> Editar</a>-->
											</div>
											<div class="btn-group pull-right">
												<!--<a href="sql.php?id=<?=$tkRes['tik_id'];?>&get=24" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" class="btn"><i class=" icon-remove-sign"></i> Eliminar</a>-->
											</div>
										</div>
									</div>
									<?php }?>
                                    
								</div>
							</div>
                            
						</div>
					</div>
				</div>

              </div>
			
			
			<?php 
			}
		}
		?>
			
                </div>
			</div>
		</div>

	<?php include("includes/pie.php");?>

</body>
</html>
