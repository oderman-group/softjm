<?php include("sesion.php");?>
<?php
$idPagina = 20;
$paginaActual['pag_nombre'] = "Productos a clientes";
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
            <?php include("notificaciones.php");?>
            <p>
            <a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
            <a href="facturacion-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
				<!--
            <div class="btn-group">
							<button class="btn btn-primary">Acciones</button>
							<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
                            	<li><a href="facturacion-filtros.php">Imprimir informes</a></li>
							</ul>
						</div>
-->
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
                                <th>Cliente</th>
                                <th>Productos</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							if(isset($_GET["cte"]) and $_GET["cte"]!=""){
								$consulta = mysql_query("SELECT * FROM facturacion
								INNER JOIN clientes ON cli_id=fact_cliente
								INNER JOIN localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN localidad_departamentos ON dep_id=ciu_departamento
								WHERE fact_cliente='".$_GET["cte"]."'",$conexion);
							}else{
								$consulta = mysql_query("SELECT * FROM facturacion
								INNER JOIN clientes ON cli_id=fact_cliente
								INNER JOIN localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN localidad_departamentos ON dep_id=ciu_departamento
								",$conexion);
							}
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								$Cpd = mysql_query("SELECT * FROM facturacion_productos
								INNER JOIN productos_soptec ON prod_id=fpp_producto
								WHERE fpp_factura='".$res['fact_id']."'",$conexion);
								
								$abonos = mysql_fetch_array(mysql_query("SELECT sum(fpab_valor) FROM facturacion_abonos WHERE fpab_factura='".$res['fact_id']."'",$conexion));
								
								
								$impuestos = $res['fact_valor'] * $res['fact_impuestos']/100;
								$retencion = $res['fact_valor'] * $res['fact_retencion']/100;
								$descuento = $res['fact_valor'] * $res['fact_descuento']/100;
								
								$valorReal = ($res['fact_valor'] + $impuestos) - ($retencion + $descuento);
								
								$saldoFinal = $valorReal - $abonos[0];

								if($datosUsuarioActual[3]!=1){
									$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$res['cli_zona']."'",$conexion));
									if($numZ==0) continue;
								}
							?>
							<tr>
								<td><?=$no;?></td>

                                <td><?=$res['fact_fecha_real'];?></td>
                                <td>
                                	<?php echo "<b>Nit</b>: ". $res['cli_usuario']."<br>";?>
									<?php echo "<b>Nombre</b>: ". $res['cli_nombre']."<br>";?>
                                    <?php echo "<b>Ciudad</b>: ". $res['ciu_nombre'].", ".$res['dep_nombre']." (<b>03".$res['dep_indicativo']."</b>)";?>
                                    <h4 style="margin-top:10px;">
                                	<a href="facturacion-editar.php?id=<?=$res['fact_id'];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>&nbsp;
                                    <a href="sql.php?id=<?=$res['fact_id'];?>&get=6" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
                                </h4>
                                </td>

                                
                                <td><?php 
								while($pds = mysql_fetch_array($Cpd)){
									echo '<a href="productos-materiales.php?pdto='.$pds['prod_id'].'" title="Ver materiales">'.$pds['prod_nombre']."</a><br>";
								}
								?></td>
                                
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