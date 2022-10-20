<?php include("sesion.php");?>
<?php
$idPagina = 43;
$paginaActual['pag_nombre'] = "Notificaciones";
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

<?php include("funciones-js.php");?>
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
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
            <?php include("notificaciones.php");?>
            
            <div class="alert alert-warning">
                <i class="icon-remove-sign"></i>
                <strong>Recomendación!</strong>
                Te recomendamos eliminar las notificaciones de las tareas que ya hayas realizado para que puedas tener este espacio limpio.
            </div>
            
            <div class="alert alert-info">
                <i class="icon-exclamation-sign"></i>
                <strong>Información!</strong>
                Haga click sobre el estado de la notificación para cambiar su estado a pendiente o completado.
            </div>
			<p>
            	<a href="notificaciones-lista.php?estNot=3" class="btn btn-info btn-large">Ver Todas</a>
                <a href="notificaciones-lista.php?estNot=1" class="btn btn-danger btn-large">Ver Pendientes</a>
                <a href="notificaciones-lista.php?estNot=2" class="btn btn-success btn-large">Ver Completadas</a>
            </p>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<p></p>
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>Fecha</th>
                                <th>Asunto</th>
								<th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Estado</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							if(!isset($_GET["estNot"]) or !is_numeric($_GET["estNot"])){$estadoNot = 'AND not_estado=1';}
							else{
								switch($_GET["estNot"]){
									case 1: $estadoNot = 'AND not_estado=1'; break;
									case 2: $estadoNot = 'AND not_estado=2'; break;
									case 3: $estadoNot = 'AND (not_estado=1 OR not_estado=2)'; break;
								}
							}
							mysql_query("UPDATE notificaciones SET not_visto=1 WHERE not_usuario='".$_SESSION["id"]."' AND not_varios IS NULL",$conexion);
								
							if(is_numeric($_GET["idNot"])){
								mysql_query("UPDATE notificaciones SET not_visto=1 WHERE not_id='".$_GET["idNot"]."'",$conexion);
								if(mysql_errno()!=0){echo mysql_error(); exit();}
								mysql_query("DELETE FROM notificaciones WHERE not_seguimiento='".$_GET["idSeg"]."' AND not_id!='".$_GET["idNot"]."'",$conexion);
								if(mysql_errno()!=0){echo mysql_error(); exit();}
								mysql_query("UPDATE cliente_seguimiento SET cseg_usuario_encargado='".$_SESSION["id"]."' WHERE cseg_id='".$_GET["idSeg"]."'",$conexion);
								if(mysql_errno()!=0){echo mysql_error(); exit();}
							}	
								
							$consulta = mysql_query("SELECT * FROM notificaciones 
							INNER JOIN clientes ON cli_id=not_cliente 
							WHERE not_usuario='".$_SESSION["id"]."' $estadoNot
							ORDER BY not_id DESC",$conexion);
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								switch($res['not_estado']){
									case 1: $estado = 'Pendiente'; $etiquetaE='important'; break;
									case 2: $estado = 'Completado'; $etiquetaE='success'; break;
								}
							?>
							<tr>
								<td><?=$no;?></td>
                                <td><?=$res['not_fecha'];?></td>
								<?php if($res['not_visto']==0 and $res['not_varios']==1){?>
								
								<td><a href="notificaciones-lista.php?idNot=<?=$res['not_id']?>&idSeg=<?=$res['not_seguimiento']?>">VER DETALLES</a></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								
								<?php }else{?>
								
                                <td><?=$res['not_asunto'];?></td>
                                <td><?=$res['cli_nombre'];?></td>
                                <td><?=$res['cli_telefono'];?></td>
                                <td><a href="sql.php?get=20&id=<?=$res['not_id'];?>&seg=<?=$res['not_seguimiento'];?>" data-toggle="tooltip" title="Cambiar de estado"><span class="label label-<?=$etiquetaE;?>"><?=$estado;?></span></a></td>
                                <td>	
								<h4>
                                	<a href="clientes-seguimiento.php?cte=<?=$res['cli_id'];?>&seg=<?=$res['not_seguimiento'];?>" data-toggle="tooltip" title="Seguimiento del cliente" target="new"><i class="icon-list-ol"></i></a>
                                    <a href="clientes-contactos.php?cte=<?=$res['cli_id'];?>&emg=1" data-toggle="tooltip" title="Contactos del cliente" target="new"><i class="icon-group"></i></a>
                                    <a href="sql.php?id=<?=$res['not_id'];?>&get=16" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar notificación"><i class="icon-remove-sign"></i></a>
                                </h4>	
								</td>
								
								<?php }?>
							</tr>
                            <?php $no++;}?>
							</tbody>
							</table>
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