<?php include("sesion.php");?>
<?php include("sesion-documentos.php");?>
<?php
$tituloPagina = "Mis Documentos";
?>
<?php include("head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<!-- styles -->

<!--[if IE 7]>
            <link rel="stylesheet" href="css/font-awesome-ie7.min.css">
        <![endif]-->
<link href="css/styles.css" rel="stylesheet">
<link href="css/theme-wooden.css" rel="stylesheet">

<!--[if IE 7]>
            <link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
        <![endif]-->
<!--[if IE 8]>
            <link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
        <![endif]-->
<!--[if IE 9]>
            <link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
        <![endif]-->
<link href="css/tablecloth.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
<!--fav and touch icons -->
<link rel="shortcut icon" href="ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
<!--============j avascript===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.tablecloth.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/ZeroClipboard.js"></script>
<script src="js/dataTables.bootstrap.js"></script>
<script src="js/TableTools.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script type="text/javascript">
            /*$( function () {
		  // Set the classes that TableTools uses to something suitable for Bootstrap
		  $.extend( true, $.fn.DataTable.TableTools.classes, {
			  "container": "btn-group",
			  "buttons": {
				  "normal": "btn",
				  "disabled": "btn disabled"
			  },
			  "collection": {
				  "container": "DTTT_dropdown dropdown-menu",
				  "buttons": {
					  "normal": "",
					  "disabled": "disabled"
				  }
			  }
		  } );
		  // Have the collection use a bootstrap compatible dropdown
		  $.extend( true, $.fn.DataTable.TableTools.DEFAULTS.oTags, {
			  "collection": {
				  "container": "ul",
				  "button": "li",
				  "liner": "a"
			  }
		  } );
		  });
		  */
            $(function () {
                $('#data-table').dataTable({
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
                    /*"oTableTools": {
			"aButtons": [
				"copy",
				"print",
				{
					"sExtends":    "collection",
					"sButtonText": 'Save <span class="caret" />',
					"aButtons":    [ "csv", "xls", "pdf" ]
				}
			]
		}*/
                });
            });
            $(function () {
                $('.tbl-simple').dataTable({
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
                });
            });
			
			$(function () {
			$(".tbl-paper-theme").tablecloth({
          theme: "paper"
		   });
			});
			
		$(function () {
			$(".tbl-dark-theme").tablecloth({
          theme: "dark"
		   });
		});
			$(function () {
                $('.tbl-paper-theme,.tbl-dark-theme').dataTable({
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
                });
	

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
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$tituloPagina;?></li>
					</ul>
				</div>
			</div>
            <?php include("notificaciones.php");?>
			
			<p><a href="sql.php?get=1" class="btn btn-warning"> Salir de documentos</a></p>
			
			
			
			<div class="row-fluid">
				
				<div class="span3">
					<div class="board-widgets brown small-widget">
						<a href="historial-equipos.php"><span class="widget-icon icon-calendar"></span><span class="widget-label">Historial equipos</span></a>
					</div>
				</div>
				
				<div class="span3">
					<div class="board-widgets green small-widget">
						<a href="cupones.php"><span class="widget-icon icon-money"></span><span class="widget-label">Mis cupones</span></a>
					</div>
				</div>
				
			</div>
			
			
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3>Cotizaciones</h3>
						</div>
						
						<div class="widget-container">
							<p></p>
							
							
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>ID</th>
                                <th>Fecha Propuesta</th>
								<th>Fecha Vencimiento</th>
								<th>Productos</th>
								<th>Responsable</th>
								<th>Vendedor</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php	
								
							
							$consulta = mysql_query("SELECT * FROM cotizacion
							INNER JOIN clientes ON cli_id=cotiz_cliente
							INNER JOIN usuarios ON usr_id=cotiz_creador
							WHERE cotiz_cliente='".$_SESSION["id"]."'
							",$conexion);
							
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								
								$vendedor = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='".$res['cotiz_vendedor']."'",$conexion));
								
								$fondoCotiz = '';
								if($res['cotiz_vendida']==1){
									$fondoCotiz = 'aquamarine';
								}
								
								$vencimiento = mysql_fetch_array(mysql_query("SELECT DATEDIFF(cotiz_fecha_vencimiento, now()) FROM cotizacion WHERE cotiz_id='".$res['cotiz_id']."'",$conexion));
								
								if($vencimiento[0]<0){continue;}
							?>
							<tr>
								<td style="background-color: <?=$fondoCotiz;?>;"><?=$res['cotiz_id'];?></td>
                                <td><?=$res['cotiz_fecha_propuesta'];?></td>
                                <td><?=$res['cotiz_fecha_vencimiento'];?></td>
								<td>
									<?php
										$productos = mysql_query("SELECT * FROM cotizacion_productos
										INNER JOIN productos ON prod_id=czpp_producto
										WHERE czpp_cotizacion='".$res['cotiz_id']."'
										",$conexion);
										$i = 1;
										while($prod = mysql_fetch_array($productos)){
											echo "<b>".$i.".</b> ".$prod['prod_nombre'].", ";
											$i++;
										}
									?>
								</td>
								<td><?=strtoupper($res['usr_nombre']);?></td>
								<td><?=strtoupper($vendedor['usr_nombre']);?></td>
                                <td>
									<div class="btn-group">
										<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											
											<li><a href="https://softjm.com/usuarios/reportes/formato-cotizacion-1.php?id=<?=$res['cotiz_id'];?>" target="_blank">Imprimir</a></li>
											
											
										</ul>
									</div>
								</td>
							</tr>
                            <?php $no++;}?>
							</tbody>
							</table>
							
							
						</div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head blue">
							<h3>CERTIFICADOS</h3>
						</div>
						
						<div class="widget-container">
							<p></p>
							
                            <?php
							$consultaC = mysql_query("SELECT * FROM remisiones 
							WHERE rem_cliente='".$_SESSION["id"]."' AND rem_fecha_certificado!=''
							ORDER BY rem_id DESC
							",$conexion);
							$no = 1;
							$meses = array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");	
							$estadosCertificados = array("","Vigente","Vencido","Provisional");
							$labCert = array("","success","important","warning");
							while($resC = mysql_fetch_array($consultaC)){
								$camposRemision = mysql_fetch_array(mysql_query("SELECT 
								DAY(rem_fecha), MONTH(rem_fecha), YEAR(rem_fecha),
								DAY(DATE_ADD(rem_fecha, INTERVAL 6 MONTH)), MONTH(DATE_ADD(rem_fecha, INTERVAL 6 MONTH)), YEAR(DATE_ADD(rem_fecha, INTERVAL 6 MONTH))
								FROM remisiones 
								WHERE rem_id='".$resC['rem_id']."'",$conexion));
							?>
							<h5 style="font-weight: bold;">
								<?=$no;?>. C<?=$resC['rem_id'];?>
								<span class="label label-<?=$labCert[$resC['rem_estado_certificado']];?>"><?=$estadosCertificados[$resC['rem_estado_certificado']];?></span>
							</h5>
							
								<b>Fecha:</b> <?=$resC['rem_fecha_certificado'];?><br>
								<b>Vencimiento:</b> <?=$camposRemision[3]." DE ".$meses[$camposRemision[4]]." DE ".$camposRemision[5];?><br>
                                <b>Equipo:</b> <?=$resC['rem_equipo'];?><br>
								<b>Serial:</b> <?=$resC['rem_serial'];?><br>
									
									<?php if($resC['rem_estado_certificado']!=2){?>
										<!--
										<a href="http://softjm.com/v2.0/usuarios/empresa/lab-certificado-imprimir.php?id=<?=$resC['rem_id'];?>" target="_blank">Imprimir</a>
										-->
									
										

										<?php if($configu['conf_cliente_imprimir_certificado']==1){?>
											<a href="https://softjm.com/v2.0/usuarios/empresa/lab-certificado-imprimir.php?id=<?=$resC['rem_id'];?>&cp=1" class="btn btn-danger" target="_blank">Imprimir certificado</a><br>
										<?php }else{?>
											<a href="solicitud.php?id=<?=$resC['rem_id'];?>" class="btn btn-info">Solicitar copia</a>
										<?php }?>

										<?php if($resC['rem_foto_certificado']!=""){?>
											<a href="../../usuarios/files/adjuntos/<?=$resC['rem_foto_certificado'];?>" class="btn btn-warning" target="_blank">Imprimir copia</a>
										<?php }?>
										
										
									
									<?php }else{?>
										<a href="certificado-renovar.php?id=<?=$resC['rem_id'];?>" class="btn btn-success">Renovar certificado</a>
									<?php }?>
							<hr>
							
                            <?php $no++;}?>
							
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