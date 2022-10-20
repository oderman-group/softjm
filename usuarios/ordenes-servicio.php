<?php include("sesion.php");?>
<?php
$idPagina = 73;
$paginaActual['pag_nombre'] = "Ordenes de servicio";
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
            <p>
            <a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
            <a href="ordenes-servicio-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
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
                                <th>Canal</th>
                                <th>F. Solicitud</th>
                                <th>F. Ideal</th>
                                <th>F. Entrega</th>
                                <th>Faltan</th>
                                <th>Cliente (Contacto)</th> 
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							if(isset($_GET["cte"]) and $_GET["cte"]!=""){
								$consulta = mysql_query("SELECT * FROM ordenes_servicio
								INNER JOIN contactos ON cont_id=ord_contacto_cliente
								INNER JOIN clientes ON cli_id=cont_cliente_principal AND cli_id='".$_GET["cte"]."'
								",$conexion);
							}else{
								$consulta = mysql_query("SELECT * FROM ordenes_servicio
								INNER JOIN contactos ON cont_id=ord_contacto_cliente
								INNER JOIN clientes ON cli_id=cont_cliente_principal
								",$conexion);
							}
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								$diasV = mysql_fetch_array(mysql_query("SELECT DATEDIFF(ord_fecha_entrega,now()) FROM ordenes_servicio WHERE ord_id='".$res['ord_id']."'",$conexion));
								switch($res['ord_canal']){
									case 1: $canal = 'Facebook'; break;
									case 2: $canal = 'WhatsApp'; break;
									case 3: $canal = 'Fijo'; break;
									case 4: $canal = 'Celular'; break;
									case 5: $canal = 'Personal'; break;
									case 6: $canal = 'Skype'; break;
									case 7: $canal = 'Otro'; break;
								}
								
								switch($res['ord_estado']){
									case 1: $estado = 'En espera'; $etiquetaE='important'; break;
									case 2: $estado = 'En proceso'; $etiquetaE='info'; break;
									case 3: $estado = 'Completado'; $etiquetaE='success'; break;
								}
								
								switch($res['ord_prioridad']){
									case 1: $prioridad = 'Normal'; $etiquetaP='success'; break;
									case 2: $prioridad = 'Urgente'; $etiquetaP='warning'; break;
									case 3: $prioridad = 'Muy Urgente'; $etiquetaP='important'; break;
								}
								if($datosUsuarioActual[3]!=1){
									$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$res['cli_zona']."'",$conexion));
									if($numZ==0) continue;
								}
							?>
							<tr>
								<td><?=$no;?></td>
                                <td><span class="label label-info"><?php echo $canal;?></span></td>
                                <td><?=$res['ord_fecha_solicitud'];?></td>
                                <td><?=$res['ord_fecha_entrega'];?></td>
                                <td><?=$res['ord_fecha_fin'];?></td>
                                <td><?php if($res['ord_estado']!=3){?><span class="label label-info"><?=$diasV[0];?> días</span><?php }else echo "-";?></td>
                                <td><?=$res['cli_nombre']." (".$res['cont_nombre'].")";?></td>
                                <td><span class="label label-<?=$etiquetaE;?>"><?=$estado;?></span></td>
                                <td><span class="label label-<?=$etiquetaP;?>"><?=$prioridad;?></span></td>
                                <td><h4>
                                	<a href="ordenes-servicio-editar.php?id=<?=$res['ord_id'];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
                                    <a href="sql.php?id=<?=$res['ord_id'];?>&get=21" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
                                </h4></td>
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