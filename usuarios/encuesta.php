<?php include("sesion.php");?>
<?php
$idPagina = 50;
$paginaActual['pag_nombre'] = "Encuestas";
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
                bootbox.confirm("Desea enviar esta encuesta?", function (result) {
                    var id = e.id;
					var cont = e.name;
					if(result == true){
						window.location.href="sql.php?get=18&id="+id+"&cont="+cont;
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
            	<a href="encuesta-agregar.php?cte=<?=$_GET["cte"];?>" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
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
								<th>Cliente (Contacto)</th>
                                <th>Atención</th>
                                <th>Producto</th>
                                <th>Prom</th>
                                <th></th>
							</tr>
							</thead>
							<tbody>
                            <?php
							if(isset($_GET["cte"]) and $_GET["cte"]!=""){
								$consulta = mysql_query("SELECT * FROM encuesta_satisfaccion
								INNER JOIN clientes ON cli_id=encs_cliente
								INNER JOIN usuarios ON usr_id=encs_atendido
								INNER JOIN contactos ON cont_id=encs_contacto
								WHERE encs_cliente='".$_GET["cte"]."'
								",$conexion);
							}else{
								$consulta = mysql_query("SELECT * FROM encuesta_satisfaccion
								INNER JOIN clientes ON cli_id=encs_cliente
								INNER JOIN usuarios ON usr_id=encs_atendido
								INNER JOIN contactos ON cont_id=encs_contacto
								",$conexion);
							}
							$no = 1;
							while($res = mysql_fetch_array($consulta)){
								if($datosUsuarioActual[3]!=1){
									$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$res['cli_zona']."'",$conexion));
									if($numZ==0) continue;
								}
								$producto = mysql_fetch_array(mysql_query("SELECT * FROM productos WHERE prod_id='".$res['encs_producto']."'",$conexion));
								$promedio='-';
								if($res['encs_p1']!=""){
									$promedio = ($res['encs_p1']+$res['encs_p2']+$res['encs_p3']+$res['encs_p4']+$res['encs_p5'])/5;
								}
							?>
							<tr>
								<td><?=$no;?></td>
                                <td><?=$res['encs_fecha'];?></td>
                                <td><?=$res['cli_nombre']." (<b>".$res['cont_nombre']."</b>)";?></td>
                                <td><?=$res['usr_nombre'];?></td>
                                <td><?=$producto['prod_nombre'];?></td>
                                <td><?=$promedio;?></td>
                                <td><h4>
                                    <a href="encuesta-editar.php?id=<?=$res[0];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
                                    <a href="sql.php?id=<?=$res[0];?>&get=15" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
                                    <a href="../formato-encuesta.php?id=<?=$res[0];?>" target="_blank" data-toggle="tooltip" title="Ver encuesta"><i class="icon-file"></i></a>
                                    <a href="#" data-toggle="tooltip" title="Enviar encuesta" id="<?=$res[0];?>" name="<?=$res['encs_contacto'];?>" class="confirm" onClick="confirmar(this)"><i class="icon-envelope"></i></a>
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
	<?php include("includes/pie.php");?>
</div>
</body>
</html>