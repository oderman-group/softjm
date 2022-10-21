<?php include("sesion.php");?>
<?php
$idPagina = 151;
$paginaActual['pag_nombre'] = "Pedidos";
?>
<?php include("verificar-paginas.php");?>
<?php include("head.php");?>
<?php
mysql_query("INSERT INTO historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_pagina_anterior)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPagina."', now(),'".$_SERVER['HTTP_REFERER']."')",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
?>
<!-- styles -->


<link href="css/tablecloth.css" rel="stylesheet">

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
            <?php include("notificaciones.php");?>
            <p>
            <a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
				<!--
            <a href="#pedidos-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>-->
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
								<th>#Cotización</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro = '';
							if($_GET["q"]!=""){$filtro .= " AND pedid_id='".$_GET["q"]."'";}	
								
							if(isset($_GET["cte"]) and $_GET["cte"]!=""){
								$consulta = mysql_query("SELECT * FROM pedidos
								INNER JOIN clientes ON cli_id=pedid_cliente AND cli_id='".$_GET["cte"]."'
								ORDER BY pedid_id DESC
								",$conexion);
							}else{
								$consulta = mysql_query("SELECT * FROM pedidos
								INNER JOIN clientes ON cli_id=pedid_cliente
								INNER JOIN usuarios ON usr_id=pedid_creador
								WHERE pedid_id=pedid_id $filtro
								ORDER BY pedid_id DESC
								",$conexion);
							}
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								if($datosUsuarioActual[3]!=1){
									$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$res['cli_zona']."'",$conexion));
									if($numZ==0) continue;
								}
								
								$vendedor = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usr_id='".$res['pedid_vendedor']."'",$conexion));

								$generoRemision = mysql_fetch_array(mysql_query("SELECT * FROM remisionbdg WHERE remi_pedido='" . $res['pedid_id'] . "'", $conexion));


											$infoRem = '';
											$fondoPedido = '';
											if($generoRemision[0]!=""){
												$infoRem = 'Este pedido ya generó la remisión con ID: '.$generoRemision[0].". En la fecha: ".$generoRemision['remi_fecha_creacion'];
												$fondoPedido = 'aquamarine';
											}
							?>
							<tr>
							<td><?= $no; ?></td>	
							<td style="background-color: <?= $fondoPedido; ?>;" title="<?=$infoRem;?>"><?=$res['pedid_id'];?></td>
                                <td><?=$res['pedid_fecha_propuesta'];?></td>
                                <td><?=strtoupper($res['cli_nombre']);?></td>
								<td><?=strtoupper($res['usr_nombre']);?></td>
								<td><?=strtoupper($vendedor['usr_nombre']);?></td>
								<td><a href="pedidos-timeline.php?id=<?=$res['pedid_id'];?>" target="_blank">En camino</a></td>
								<td><?=$res['pedid_cotizacion'];?></td>
                                <td>
									<div class="btn-group">
										<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<?php if($_SESSION["id"]==$res['pedid_creador'] or $_SESSION["id"]==$res['pedid_vendedor']){?>
											<!--
											<li><a href="#pedidos-editar.php?id=<?=$res[0];?>#productos"> Editar</a></li>
											
											<li><a href="sql.php?id=<?=$res[0];?>&get=51" onClick="if(!confirm('Desea anular el registro?')){return false;}">Anular</a></li>-->
											
											<li><a href="sql.php?id=<?=$res[0];?>&get=50" onClick="if(!confirm('Desea eliminar el registro?')){return false;}">Eliminar</a></li>
											<?php }?>
											
											<li><a href="reportes/formato-pedido-1.php?id=<?=$res[0];?>" target="_blank">Imprimir</a></li>

											<?php if($generoRemision[0]==""){?>
											
												<li><a href="sql.php?get=52&id=<?=$res[0];?>" onClick="if(!confirm('Desea generar remisión de este pedido?')){return false;}">Generar remisión</a></li>

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