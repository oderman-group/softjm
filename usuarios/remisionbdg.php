<?php include("sesion.php");?>
<?php
$idPagina = 148;
$paginaActual['pag_nombre'] = "Remisiones";
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
<script src="js/bootbox.js"></script>

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
			
			function confirmar (e) {
                bootbox.confirm("Desea enviar esta cotización?", function (result) {
                    var id = e.id;
					var cont = e.name;
					if(result == true){
						window.location.href="sql.php?get=44&id="+id+"&cont="+cont;
					}
            })
			};
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
            <a href="#remisionbdg-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
            </p>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">
							<p></p>
							
							<div style="border:thin; border-style:solid; height:150px; margin:10px;">
                            	<h4 align="center">-Búsqueda por ID-</h4>
                                <p> 
                                    <form class="form-horizontal" action="<?=$_SERVER['PHP_SELF'];?>" method="get">
                                        <div class="search-box">
                                            <div class="input-append input-icon">
                                                <input class="search-input" placeholder="ID..." type="text" name="q" value="<?=$_GET["q"];?>">
                                                <i class=" icon-search"></i>
                                                <input class="btn" type="submit" name="buscar" value="Buscar">
                                            </div>
                                            <?php if($_GET["q"]!=""){?> <a href="<?=$_SERVER['PHP_SELF'];?>" class="btn btn-warning"><i class="icon-minus"></i> Quitar Filtro</a> <?php } ?>
                                        </div>
                                    </form> 
                                </p>
                            </div>
						
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
							<th>No.</th>	
							<th>ID</th>
                                <th>Fecha Documento</th>
                                <th>Cliente</th>
								<th>Responsable</th>
								<th>Vendedor</th>
								<th>Estado</th>
								<th>#Pedido</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro = '';
							if($_GET["q"]!=""){$filtro .= " AND remi_id='".$_GET["q"]."'";}	
								
							if(isset($_GET["cte"]) and $_GET["cte"]!=""){
								$consulta = mysql_query("SELECT * FROM remisionbdg
								INNER JOIN clientes ON cli_id=remi_cliente AND cli_id='".$_GET["cte"]."'
								ORDER BY remi_id DESC
								",$conexion);
							}else{
								$consulta = mysql_query("SELECT * FROM remisionbdg
								INNER JOIN clientes ON cli_id=remi_cliente
								INNER JOIN usuarios ON usr_id=remi_creador
								WHERE remi_id=remi_id $filtro
								ORDER BY remi_id DESC
								",$conexion);
							}
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								if($datosUsuarioActual[3]!=1){
									$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$res['cli_zona']."'",$conexion));
									if($numZ==0) continue;
								}
								
								$vendedor = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='".$res['remi_vendedor']."'",$conexion));


								$generoFactura = mysql_fetch_array(mysql_query("SELECT * FROM facturas WHERE factura_remision='" . $res['remi_id'] . "'", $conexion));

											$infoFac = '';
											$fondoRemision = '';
											if($generoFactura[0]!=""){
												$infoFac = 'Esta remisión ya generó la factura con ID: '.$generoFactura[0].". En la fecha: ".$generoFactura['factura_fecha_creacion'];
												$fondoRemision = 'aquamarine';
											}
							?>
							<tr>
							<td><?= $no; ?></td>	
							<td style="background-color: <?= $fondoRemision; ?>;" title="<?=$infoFac;?>"><?=$res['remi_id'];?></td>
                                <td><?=$res['remi_fecha_propuesta'];?></td>
                                <td><?=strtoupper($res['cli_nombre']);?></td>
								<td><?=strtoupper($res['usr_nombre']);?></td>
								<td><?=strtoupper($vendedor['usr_nombre']);?></td>
								<td><?=$res['remi_estado'];?></td>
								<td><?=$res['remi_pedido'];?></td>
                                <td>
									<div class="btn-group">
										<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<?php if($_SESSION["id"]==$res['remi_creador'] or $_SESSION["id"]==$res['remi_vendedor'] or $datosUsuarioActual[3]==13){?>
											<li><a href="remisionbdg-editar.php?id=<?=$res[0];?>#productos"> Editar</a></li>
											<?php }?>
											
											<?php if($_SESSION["id"]==$res['remi_creador'] or $_SESSION["id"]==$res['remi_vendedor']){?>
											<li><a href="sql.php?id=<?=$res[0];?>&get=50" onClick="if(!confirm('Desea eliminar el registro?')){return false;}">Eliminar</a></li>
											<?php }?>
											
											<li><a href="reportes/formato-remision-1.php?id=<?=$res[0];?>" target="_blank">Imprimir</a></li>

											<?php if($generoFactura[0]==""){?>
											
											<li><a href="sql.php?get=65&id=<?=$res[0];?>" onClick="if(!confirm('Desea generar factura de esta remisión?')){return false;}">Generar Factura</a></li>

											<?php }?>
											
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
	
		
			</div>
		</div>
	</div>
	<?php include("pie.php");?>
</div>
</body>
</html>