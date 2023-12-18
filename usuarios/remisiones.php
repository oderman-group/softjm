<?php
include("sesion.php");

$idPagina = 148;

include("includes/verificar-paginas.php");
include("includes/head.php");
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
                <a href="remisiones-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
                <a href="remisiones-informes-todos.php" class="btn btn-info"><i class="icon-filter"></i> Informe certificados</a>
                <a href="remisiones-escoger.php" class="btn btn-warning"><i class="icon-print"></i> Imprimir varias remisiones</a>
            </p>
            
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?></h3>
						</div>      
						<div style="margin: 10px; padding: 10px; background-color: darkgray;">
							<h4 style="color: white;">Buscar por</h4>
							
							<form class="m-t-25" action="<?=$_SERVER["PHP_SELF"];?>" method="get">
								<div class="form-group">
									<select class="select2 form-control" style="width: 100%; height:36px;" name="campo">
										<option value="rem_id" <?php if($_GET["campo"]=="rem_id") echo "selected"; ?> >ID</option>
										<option value="rem_serial" <?php if($_GET["campo"]=="rem_serial") echo "selected"; ?>>Serial</option>
									</select>
								</div>	
								
								<div class="form-group">
									<input type="text" class="form-control" style="width: 99%;" name="busqueda" value="<?=$_GET["busqueda"];?>" placeholder="Buscar...">
								</div>
								
								<div class="action-form">
									<div class="form-group m-b-0 text-left">
										<button type="submit" class="btn btn-info">Aplicar filtros</button>
										<a href="<?=$_SERVER["PHP_SELF"];?>" class="btn btn-danger">Quitar filtros</a>
									</div>
								</div>
							</form>
						</div>
		
						<div class="widget-container">	
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
								while($m<=12){
									$consultaRemisiones=mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones 
									INNER JOIN clientes ON cli_id=rem_cliente
									WHERE rem_estado=1 AND MONTH(rem_fecha_registro)='".$m."' AND rem_id_empresa='".$idEmpresa."'");
									$numRem = mysqli_num_rows($consultaRemisiones);
									if($m==$_GET["m"])
										echo '<a style="font-weight:bold;">'.$mesesAbre[$m].'('.$numRem.')</a>&nbsp;&nbsp;&nbsp;';
									else
										echo '<a href="remisiones.php?m='.$m.'&a='.$_GET["a"].'&u='.$_GET["u"].'">'.$mesesAbre[$m].'('.$numRem.')</a>&nbsp;&nbsp;&nbsp;';	
									$m++;
								}
								?>
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
											
											$filtro = '';
											if(is_numeric($_GET["idRem"])){$filtro .= " AND rem_id='".$_GET["idRem"]."'";}
											if(is_numeric($_GET["cliente"])){$filtro .= " AND rem_cliente='".$_GET["cliente"]."'";}
											
											if($_GET["busqueda"]!=""){$filtro .= " AND ".$_GET["campo"]."='".$_GET["busqueda"]."'";}
											
											if(is_numeric($_GET["a"])){$filtro .= " AND YEAR(rem_fecha_registro)='".$_GET["a"]."'";}
											if(is_numeric($_GET["m"])){$filtro .= " AND MONTH(rem_fecha_registro)='".$_GET["m"]."'";}
											
											if($datosUsuarioActual[3] == 14){

												$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones
												LEFT JOIN clientes ON cli_id=rem_cliente
												LEFT JOIN usuarios ON usr_id=rem_asesor
												LEFT JOIN sucursales_propias ON sucp_id=usr_sucursal
												WHERE rem_id_empresa='".$idEmpresa."' $filtro
												ORDER BY rem_id DESC
												");
												
											}else{

												$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones
												LEFT JOIN clientes ON cli_id=rem_cliente
												LEFT JOIN usuarios ON usr_id=rem_asesor
												LEFT JOIN sucursales_propias ON sucp_id=usr_sucursal
												WHERE rem_id_empresa='".$idEmpresa."' $filtro
												ORDER BY rem_id DESC
												LIMIT 0, 100
												");
											}


											$conRegistros = 1;
											while($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
												
												//Solo Vendedores externos
												if($datosUsuarioActual[3] == 14){
													$numZ = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$resultado['cli_zona']."'"));
													if($numZ==0) continue;
												}

												$remisionesEnt = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT DATEDIFF(now(), rem_fecha_registro), rem_id FROM remisiones 
												WHERE rem_id='".$resultado['rem_id']."' AND rem_id_empresa='".$idEmpresa."'"), MYSQLI_BOTH);
												
												$remisionesSeg = mysqli_num_rows(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones_seguimiento 
												WHERE remseg_id_remisiones='".$resultado['rem_id']."'"));
												
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
												
												if($resultado['rem_estado_certificado']==REM_ESTADO_CERTIFICADO_VENCIDO){
													$certificado = '<span class="label label-danger">Vencido</span>';
												}
												//Para obtener la fecha
												$camposRemision = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT 
												DAY(rem_fecha), MONTH(rem_fecha), YEAR(rem_fecha),
												DAY(DATE_ADD(rem_fecha, INTERVAL '".$resultado['rem_tiempo_certificado']."' MONTH)), MONTH(DATE_ADD(rem_fecha, INTERVAL '".$resultado['rem_tiempo_certificado']."' MONTH)), YEAR(DATE_ADD(rem_fecha, INTERVAL '".$resultado['rem_tiempo_certificado']."' MONTH))
												FROM remisiones 
												WHERE rem_id='".$resultado['rem_id']."' AND rem_id_empresa='".$idEmpresa."'"), MYSQLI_BOTH);
											?>
							<tr>
								<td><?=$conRegistros;?></td>
												
												<td>
													<div class="btn-group">
							<button class="btn btn-danger">Opciones</button>
							<button data-toggle="dropdown" class="btn btn-danger dropdown-toggle"><span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a href="remisiones-editar.php?id=<?=$resultado['rem_id'];?>">Ver detalles</a></li>
								
								<li><a href="#" onClick="if(!confirm('Desea eliminar este registro?')){return false;}">Eliminar</a></li>
								
								<hr>
								<li><a href="enviar_correos/enviar-remision-actual-al-cliente.php?id=<?=$resultado['rem_id'];?>&cte=<?=$resultado['rem_cliente'];?>&contacto=<?=$resultado['rem_contacto'];?>">Enviar remisión actual</a></li>
								
								<?php if($resultado['rem_generar_certificado']==1 and $resultado['rem_estado']==1){?>
									<hr>
									<li><a href="bd_update/salida-remision-actualizar.php?id=<?=$resultado['rem_id'];?>" onClick="if(!confirm('Desea generar salida a este equipo?')){return false;}" target="_blank">Generar salida</a></li>
								<?php } if($resultado['rem_generar_certificado']!=1){?>
									<hr>
									<li><a href="bd_update/generar-certificado.php?id=<?=$resultado['rem_id'];?>&cte=<?=$resultado['rem_cliente'];?>" onClick="if(!confirm('Desea generar certificado a este equipo?')){return false;}">Generar certificado</a></li>
								<?php }?>
								
								<hr>
								<li><a href="reportes/remisiones-imprimir.php?id=<?=$resultado['rem_id'];?>&estado=1" target="_blank">Remisión Entrada</a></li>
								
								<?php if($resultado['rem_estado']==2){?>
								<li><a href="reportes/remisiones-imprimir.php?id=<?=$resultado['rem_id'];?>&estado=2" target="_blank">Remisión Salida</a></li>
								<?php }?>
								
								<?php if($resultado['rem_generar_certificado']==1){?>
								<li><a href="reportes/certificado-imprimir.php?id=<?=$resultado['rem_id'];?>" target="_blank">Certificado</a></li>
								<?php }?>
								
								<?php if(!empty($resultado['rem_archivo'])){?>
									<hr>
									<li><a href="files/adjuntos/<?=$resultado['rem_archivo'];?>" target="_blank">Ver imagen</a></li>
								<?php }?>
							</ul>
						</div>
												</td>
												
												<td><a href="remisiones-editar.php?id=<?=$resultado['rem_id'];?>"><?=$resultado['rem_id'];?></a></td>
												
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