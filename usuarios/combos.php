<?php include("sesion.php");?>
<?php
$idPagina = 172;
$paginaActual['pag_nombre'] = "Combos";
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
	<?php include("includes/encabezado.php");?>
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
            <?php include("includes/notificaciones.php");?>
   
            
            <p>
            	<a href="combos-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
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
								<th>Imagen</th>
                                <th>Nombre</th>
								<th>Precio</th>
								<?php if($datosUsuarioActual['usr_tipo']==1){?>
								<th>Dcto.</th>
								<th>Dcto. Dealer</th>
								<?php }?>
								<th>Dcto. Max.<br> Permitido</th>
								<th>Precio final</th>

								<th>Precio final Dealer</th>
								<th>Estado</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$consulta = mysql_query("SELECT * FROM combos",$conexion);
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								
								$datosCombos = mysql_query("SELECT ROUND((SUM(copp_cantidad)*prod_precio),0) FROM combos
								INNER JOIN combos_productos ON copp_combo=combo_id
								INNER JOIN productos ON prod_id=copp_producto
								WHERE combo_id='".$res['combo_id']."'
								GROUP BY copp_producto
								",$conexion);
								
								$precioCombo = 0;
								while($dCombos = mysql_fetch_array($datosCombos)){
									$precioCombo += $dCombos[0];
								}
								
								$dcto = $res['combo_descuento']/100;
								$precioFinal = round($precioCombo - ($precioCombo*$dcto),0);

								$dctoDealer = $res['combo_descuento_dealer']/100;
								$precioFinalDealer = round($precioCombo - ($precioCombo*$dctoDealer),0);
							?>
							<tr>
								<td><?=$no;?></td>
								<td>
									<?php if($res['combo_imagen']!=""){?>		
										<img src="files/combos/<?=$res['combo_imagen'];?>" width="80">
									<?php }?>
								</td>
                                <td><?=$res['combo_nombre'];?></td>
								<td>$<?=number_format($precioCombo,0,".",".");?></td>
								<?php if($datosUsuarioActual['usr_tipo']==1){?>
									<td><?=$res['combo_descuento'];?>%</td>
									<td><?=$res['combo_descuento_dealer'];?>%</td>
								<?php }?>
								<td><?=$res['combo_descuento_maximo'];?>%</td>
								<td>$<?=number_format($precioFinal,0,".",".");?></td>
								<td>$<?=number_format($precioFinalDealer,0,".",".");?></td>
								<td><?=$estadoRegistros[$res['combo_estado']];?></td>
								
                                <td><h4>
									
										<a href="combos-ver.php?id=<?=$res[0];?>" data-toggle="tooltip" title="Ver"><i class="icon-eye-open"></i></a>
										<a href="combos-editar.php?id=<?=$res[0];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
									
									<?php if($_SESSION["id"]==7 or $_SESSION["id"]==15 or $_SESSION["id"]==17){?>
										<a href="sql.php?id=<?=$res[0];?>&get=53" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
									<?php }?>
                                </h4>
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
	<?php include("includes/pie.php");?>
</div>
</body>
</html>