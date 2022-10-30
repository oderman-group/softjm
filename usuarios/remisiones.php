<?php include("sesion.php");?>
<?php
$idPagina = 9;
$paginaActual['pag_nombre'] = "Remisiones";
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
            
            $(function () {
                $('#data-table').dataTable({
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"

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

<link rel="stylesheet" href="css/modal/jquery-ui.css">
<!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
  <script>
  $( function() {
    
	var dialog, form,
 
      // From http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#e-mail-state-%28type=email%29
      emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
      name = $( "#name" ),
      email = $( "#email" ),
      password = $( "#password" ),
      allFields = $( [] ).add( name ).add( email ).add( password ),
      tips = $( ".validateTips" );
	  

 
    function addUser() {
      
      if ( valid ) {
        $( "#users tbody" ).append( "<tr>" +
          "<td>" + name.val() + "</td>" +
          "<td>" + email.val() + "</td>" +
          "<td>" + password.val() + "</td>" +
        "</tr>" );
        dialog.dialog( "close" );
      }
      return valid;
    }
 
    dialog = $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 650,
      width: 450,
      modal: true,
      buttons: {
        Cancel: function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });
 
    form = dialog.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
      addUser();
    });
 
    $( ".create-user" ).button().on( "click", function() {
      dialog.dialog( "open" );
    });
  } );
  </script>
  
<?php include("includes/funciones-js.php");?>
</head>
<body>



<div class="layout">
	<?php include("includes/encabezado.php");?>
    
    
	<div class="main-wrapper">
		<div class="container-fluid">
            <?php include("includes/notificaciones.php");?>
            <p>
                <a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
                <a href="../v2.0/usuarios/empresa/lab-remisiones-agregar.php" class="btn btn-danger" target="_blank"><i class="icon-plus"></i> Agregar nuevo</a>
                <a href="certificados-filtros.php" class="btn btn-info"><i class="icon-filter"></i> Informe certificados</a>
                <a href="lab-remisiones-escoger.php" class="btn btn-warning"><i class="icon-print"></i> Imprimir varias remisiones</a>
            </p>

            <div align="center" style="margin:10px; font-size:20px;">
								<?php
								$a = $configuracion['conf_agno_inicio'];
								while($a<=date("Y")){
									if($a==$_GET["a"])
										echo '<a style="font-weight:bold;">'.$a.'</a>&nbsp;&nbsp;&nbsp;';
									else
										echo '<a href="remisiones.php?a='.$a.'&m='.$_GET["m"].'&u='.$_GET["u"].'">'.$a.'</a>&nbsp;&nbsp;&nbsp;';	
									$a++;
								}
								?>
								</div>
								
								<div align="center" style="margin:10px; font-size:18px;">
								<?php
								$m = 1;
								$meses = array("","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
								while($m<=12){
									$numRem = mysql_num_rows(mysql_query("SELECT * FROM remisiones 
									INNER JOIN clientes ON cli_id=rem_cliente
									WHERE rem_estado=1 AND MONTH(rem_fecha_registro)='".$m."'",$conexion));
									if($m==$_GET["m"])
										echo '<a style="font-weight:bold;">'.$meses[$m].'('.$numRem.')</a>&nbsp;&nbsp;&nbsp;';
									else
										echo '<a href="remisiones.php?m='.$m.'&a='.$_GET["a"].'&u='.$_GET["u"].'">'.$meses[$m].'('.$numRem.')</a>&nbsp;&nbsp;&nbsp;';	
									$m++;
								}
								?>
								</div>
            
			<div class="row-fluid">
				
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?></h3>
						</div>
                        <?php
						if(isset($_GET["busqueda"]) and $_GET["busqueda"]!=""){
							$filtro = "AND (cli_usuario LIKE '%".$_GET["busqueda"]."%' OR cli_nombre LIKE '%".$_GET["busqueda"]."%')";
						}else{
							$filtro = "";
						}
						?>
                        
		
						<div class="widget-container">
							<div style="border:thin; border-style:solid; height:150px; margin:10px;">
                            	<h4 align="center">-Busqueda general y paginación-</h4>
                                <p> 
                                    <form class="form-horizontal" action="<?=$_SERVER['PHP_SELF'];?>" method="get">
                                        <div class="search-box">
                                            <div class="input-append input-icon">
                                                <input class="search-input" placeholder="Buscar..." type="text" name="busqueda" value="<?=$_GET["busqueda"];?>">
                                                <i class=" icon-search"></i>
                                                <input class="btn" type="submit" name="buscar" value="Buscar">
                                            </div>
                                            <?php if(isset($_GET["busqueda"]) and $_GET["busqueda"]!=""){?> <a href="<?=$_SERVER['PHP_SELF'];?>" class="btn btn-warning"><i class="icon-minus"></i> Quitar Filtro</a> <?php } ?>
                                        </div>
                                    </form>
                                    <?php //include("includes/paginacion.php");?> 
                                </p>
                            </div>
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>#</th>
												<th></th>
												<th>ID</th>
                                                <th>Fecha</th>
                                                <th>Cliente</th>
												<th>Equipo</th>
												<th>REF</th>
												<th>Serial</th>
												<th>Sucursal</th>
												<th>Estado</th>
												<th>Certificado</th>
												<th>Vencimiento</th>
							</tr>
							</thead>
							<tbody>
                            <?php
											$meses = array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
											
											$filtro = '';
											if(is_numeric($_GET["idRem"])){$filtro = " AND rem_id='".$_GET["idRem"]."'";}
											if(is_numeric($_GET["cliente"])){$filtro = " AND rem_cliente='".$_GET["cliente"]."'";}
											
											if($_GET["busqueda"]!=""){$filtro = " AND ".$_GET["campo"]."='".$_GET["busqueda"]."'";}
											
											if(is_numeric($_GET["a"])){$filtro .= " AND YEAR(rem_fecha_registro)='".$_GET["a"]."'";}
											if(is_numeric($_GET["m"])){$filtro .= " AND MONTH(rem_fecha_registro)='".$_GET["m"]."'";}
											
											if($datosUsuarioActual[3] == 14){

												$consulta = mysql_query("SELECT * FROM remisiones
												LEFT JOIN clientes ON cli_id=rem_cliente
												LEFT JOIN usuarios ON usr_id=rem_asesor
												LEFT JOIN sucursales_propias ON sucp_id=usr_sucursal
												WHERE rem_id=rem_id $filtro
												ORDER BY rem_id DESC
												",$conexion);
												
											}else{

												$consulta = mysql_query("SELECT * FROM remisiones
												LEFT JOIN clientes ON cli_id=rem_cliente
												LEFT JOIN usuarios ON usr_id=rem_asesor
												LEFT JOIN sucursales_propias ON sucp_id=usr_sucursal
												WHERE rem_id=rem_id $filtro
												ORDER BY rem_id DESC
												LIMIT 0, 100
												",$conexion);
											}


											$conRegistros = 1;
											while($resultado = mysql_fetch_array($consulta)){
												
												//Solo Vendedores externos
												if($datosUsuarioActual[3] == 14){
													$numZ = mysql_num_rows(mysql_query("SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$resultado['cli_zona']."'",$conexion));
													if($numZ==0) continue;
												}

												$remisionesEnt = mysql_fetch_array(mysql_query("SELECT DATEDIFF(now(), rem_fecha_registro), rem_id FROM remisiones 
												WHERE rem_id='".$resultado['rem_id']."'",$conexion));
												
												$remisionesSeg = mysql_num_rows(mysql_query("SELECT * FROM remisiones_seguimiento 
												WHERE remseg_id_remisiones='".$resultado['rem_id']."'",$conexion));
												
												$colorEntrada = 'info';
												
												if($resultado['rem_estado']==1){
													if($remisionesEnt[0]>=3 and $remisionesSeg>0){
														$colorEntrada = 'warning';
													}elseif($remisionesEnt[0]>=3 and $remisionesSeg==0){
														$colorEntrada = 'danger';
													}
												}

												$html = '<span class="label label-warning">Entrada</a>';
												
												if($resultado['rem_estado']==2){
													$html = '<span class="label label-success">Salida</span>';
												}

												$certificado = '<span class="label label-success">Vigente</a>';
												
												if($resultado['rem_estado_certificado']==2){
													$certificado = '<span class="label label-danger">Vencido</span>';
												}
												//Para obtener la fecha
												$camposRemision = mysql_fetch_array(mysql_query("SELECT 
												DAY(rem_fecha), MONTH(rem_fecha), YEAR(rem_fecha),
												DAY(DATE_ADD(rem_fecha, INTERVAL '".$resultado['rem_tiempo_certificado']."' MONTH)), MONTH(DATE_ADD(rem_fecha, INTERVAL '".$resultado['rem_tiempo_certificado']."' MONTH)), YEAR(DATE_ADD(rem_fecha, INTERVAL '".$resultado['rem_tiempo_certificado']."' MONTH))
												FROM remisiones 
												WHERE rem_id='".$resultado['rem_id']."'",$conexion));
											?>
							<tr>
								<td><?=$conRegistros;?></td>
												
												<td>
													<div class="btn-group">
							<button class="btn btn-danger">Opciones</button>
							<button data-toggle="dropdown" class="btn btn-danger dropdown-toggle"><span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a href="../v2.0/usuarios/empresa/lab-remisiones-editar.php?id=<?=$resultado['rem_id'];?>" target="_blank">Ver detalles</a></li>

								<li><a href="../v2.0/usuarios/empresa/lab-remisiones-imprimir.php?estado=1&id=<?=$resultado['rem_id'];?>" target="_blank">Remisión de entrada</a></li>

								<li><a href="../v2.0/usuarios/empresa/lab-remisiones-imprimir.php?estado=2&id=<?=$resultado['rem_id'];?>" target="_blank">Remisión de salida</a></li>

								<li><a href="../v2.0/usuarios/empresa/lab-certificado-imprimir.php?id=<?=$resultado['rem_id'];?>" target="_blank">Ver certificado</a></li>
								
							</ul>
						</div>
												</td>
												
												<td><a href="#"><?=$resultado['rem_id'];?></a></td>
												
												<td><?=$resultado['rem_fecha'];?></td>
                                                
												<td><a href="clientes-editar.php?id=<?php echo $resultado['cli_id'];?>" target="_blank"><?php echo $resultado['cli_nombre'];?></a></td>
												
												<td><?=$resultado['rem_equipo'];?></td>
												<td><?=$resultado['rem_referencia'];?></td>
												<td><?=$resultado['rem_serial'];?></td>
												<td><?=$resultado['sucp_nombre'];?></td>
												<td><?=$html;?></td>
												<td><?=$certificado;?></td>
												<td><?=$camposRemision[3]." DE ".$meses[$camposRemision[4]]." DE ".$camposRemision[5];?></td>
							</tr>
                            <?php $conRegistros++;}?>
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