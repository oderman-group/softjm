<?php include("sesion.php");?>
<?php
$idPagina = 141;
$paginaActual['pag_nombre'] = "Pedidos Store";
?>
<?php include("includes/verificar-paginas.php");?>
<?php include("includes/head.php");?>
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
	<?php include("includes/encabezado.php");?>
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
            <?php include("includes/notificaciones.php");?>
            <p>
            <a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
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
								<th>NO.</th>
								<th>ID</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
								<th>Productos</th>
								<th>Total</th>
								<th>Estado</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtro = '';
							if($_GET["q"]!=""){$filtro .= " AND ped_id='".$_GET["q"]."'";}	

							$consulta = mysql_query("SELECT * FROM store_pedidos
							WHERE ped_id=ped_id $filtro
							ORDER BY ped_id DESC
							",$conexion);
							
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								$estadoColor = 'crimson';
								switch($res['ped_estado']){
									case 4: $estadoPedido = 'APROBADA'; $estadoColor = 'darkgreen'; break;
										
									case 6: $estadoPedido = 'RECHAZADA'; break;	
										
									case 7: $estadoPedido = 'PENDIENTE'; break;
										
									case 104: $estadoPedido = 'ERROR'; break;	
										
									default: $estadoPedido = 'DESCONOCIDO'; break;	
								}
							?>
							<tr>
								<td><?=$no;?></td>
								<td><?=$res['ped_id'];?></td>
                                <td><?=$res['ped_fecha_pedido'];?></td>
                                <td>
									<b>DNI:</b> <?=$res['ped_documento'];?><br>
									<?=strtoupper($res['ped_nombre']);?><br>
									<?=$res['ped_email'];?><br>
									<?=$res['ped_telefono'];?><br>
									<?=$res['ped_ciudad'];?><br>
									<?=$res['ped_direccion'];?>
								</td>
								<td>
									<?php
										$productos = mysql_query("SELECT * FROM store_pedidos_items
										INNER JOIN productos ON prod_id=pedit_producto
										WHERE pedit_pedido='".$res['ped_id']."'
										",$conexion);
										$i = 1;
										while($prod = mysql_fetch_array($productos)){
											echo "<b>".$i.".</b> ".$prod['prod_nombre']." - [<b>Cant:</b> ".$prod['pedit_cantidad']."] - $".number_format($prod['pedit_valor_base'],0,",",".")."<br> ";
											$i++;
										}
									?>
								</td>
								<td>$<?=number_format($res['ped_total_pagar'],0,",",".");?></td>
								<td><span style="color: <?=$estadoColor;?>; font-weight:bold;"><?=$estadoPedido." [".$res['ped_estado']."]";?></span></td>
								
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
	<?php include("includes/pie.php");?>
</div>
</body>
</html>