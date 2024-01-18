<?php 
include("sesion.php");
$idPagina = 9; 
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
								<?php if (Modulos::validarRol([10], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="clientes-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
								<?php } ?>
								<?php if (Modulos::validarRol([252], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="clientes-importar.php" class="btn btn-success"><i class="icon-upload"></i> Cargar masivamente</a>
								<?php } ?>
                	
				<div class="btn-group">
							<button class="btn btn-primary">Acciones</button>
							<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<?php if (Modulos::validarRol([103], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<li><a href="clientes-filtro.php">Imprimir informe</a></li>
								<?php } ?>
								<?php if (Modulos::validarRol([264], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<li><a href="excel_exportar/clientes-exportar.php?dpto=<?php if(isset($_GET["dpto"])) echo $_GET["dpto"];?>" target="_blank">Exportar a Excel</a></li>
								<?php } ?>
								<?php if (Modulos::validarRol([57], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<li><a href="bd_update/clientes-actualizar-claves.php" onClick="if(!confirm('Desea ejecutar esta accion?')){return false;}">Cambiar todas las claves</a></li>
								<?php } ?>
								<?php if (Modulos::validarRol([2], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<li><a href="clientes.php?pap=1">Ver clientes en papelera</a></li>
								<?php } ?>
								
							</ul>
						</div>
            </p>
        
            
            
			<div class="row-fluid">
				<div class="span2">
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h5 align="center" style="color:white;">DEPARTAMENTOS</h5>
						</div>

					<div class="widget-container">
                <a href="clientes.php" style="margin-bottom:10px;">TODOS</a><br>
                <?php
                if(Modulos::validarRol([387], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
					$departamentos = $conexionBdAdmin->query("SELECT * FROM localidad_departamentos ORDER BY dep_nombre");
				}else{
					$departamentos = $conexionBdAdmin->query("SELECT * FROM ".BDADMIN.".localidad_departamentos
					INNER JOIN ".MAINBD.".zonas_usuarios ON zpu_usuario='".$_SESSION["id"]."' AND zpu_zona=dep_id
					ORDER BY dep_nombre");
				}
                while($deptos = mysqli_fetch_array($departamentos, MYSQLI_BOTH)){
                    
					$color = 'blue';
					if(isset($_GET["dpto"])){
						if($deptos[0]==$_GET["dpto"]) $color = 'green' ;
					}

					$contarClientes = contarClientesPorDepto($deptos[0]);
                ?>
                    	
					<a href="clientes.php?dpto=<?=$deptos[0];?>" style="margin-bottom:10px; color:<?=$color;?>"><?=$deptos[1]." (".$contarClientes.")";?></a><br>
					

                <?php }?>
					</div>
					</div>
				</div>
				
				<div class="span10">
					<p style="font-size: 11px;">
								TK = Tickets | SG = Seguimientos | SC = Sucursales | CT = Contactos | FC = Facturas | RM = Remisiones
							</p>
					
					<p>
						<div class="btn-group">
							<button class="btn btn-primary">Grupos</button>
							<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a href="clientes.php">Todos</a></li>
								<?php
								$grupos = $conexionBdPrincipal->query("SELECT * FROM dealer WHERE deal_id_empresa='".$idEmpresa."'");
								while($grupo = mysqli_fetch_array($grupos, MYSQLI_BOTH)){
									
									$color = 'white';
									if(isset($_GET["grupo"])){
										if($grupo[0]==$_GET["grupo"]) $color = 'black' ;
									}
					
									$consultaContarClientes = $conexionBdPrincipal->query("SELECT COUNT(*) FROM clientes_categorias
									INNER JOIN clientes ON cli_id=cpcat_cliente AND (cli_papelera=0 OR  cli_papelera IS NULL)
									WHERE cpcat_categoria='".$grupo[0]."' AND cli_id_empresa='".$idEmpresa."'
									");
									$contarClientes = mysqli_fetch_array($consultaContarClientes, MYSQLI_BOTH);
								?>
								<li><a href="clientes.php?grupo=<?=$grupo[0];?>" style="color:<?=$color;?>"><?=$grupo['deal_nombre']." (".$contarClientes[0].")";?></a></li>
								<?php }?>
							</ul>
						</div>
					
						<div class="btn-group">
							<button class="btn btn-primary">Tipo Documento</button>
							<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a href="clientes.php">Todos</a></li>
								<li><a href="clientes.php?tipoDoc=2&grupo=<?php if(isset($_GET["grupo"])) echo $_GET["grupo"];?>">NIT</a></li>
								<li><a href="clientes.php?tipoDoc=3&grupo=<?php if(isset($_GET["grupo"])) echo $_GET["grupo"];?>">Cédula</a></li>
							</ul>
						</div>
					</p>
					
					<div class="content-widgets light-gray">
						<div class="widget-head green">
							<h3><?=$paginaActual['pag_nombre'];?></h3>
						</div>
                        <?php
						$filtro = "";
						if(isset($_GET["busqueda"]) and $_GET["busqueda"]!=""){
							$filtro .= " AND (cli_usuario LIKE '%".$_GET["busqueda"]."%' OR cli_nombre LIKE '%".$_GET["busqueda"]."%')";
						}
						if(isset($_GET["pap"]) and $_GET["pap"]==1){ $filtro .= " AND cli_papelera=1";}
						if(isset($_GET["tipoDoc"]) and is_numeric($_GET["tipoDoc"])){ $filtro .= " AND cli_tipo_documento='".$_GET["tipoDoc"]."'";}
						if(Modulos::validarRol([385], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
							$filtro.=' AND cli_ciudad!="1122"';
						}
						?>
                        
						<?php
						if(isset($_GET["dpto"]) and $_GET["dpto"]!=""){
								$SQL = "SELECT * FROM ".MAINBD.".clientes
								LEFT JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento AND dep_id='".$_GET["dpto"]."'
								WHERE cli_id=cli_id ".$filtro."
								";
							}else{
								$SQL = "SELECT * FROM ".MAINBD.".clientes
								LEFT JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
								WHERE cli_id=cli_id ".$filtro."
								";
							}
						?>
						
						<div class="widget-container">
							<div style="border:thin; border-style:solid; height:150px; margin:10px; padding:10px;">
                            	<h4 align="center">-Busqueda general y paginación-</h4>
                                <p> 
                                    <form class="form-horizontal" style="text-align: right;" action="<?=$_SERVER['PHP_SELF'];?>" method="get">
                                        <div class="search-box">
                                            <div class="input-append input-icon">
                                                <input placeholder="Buscar..." type="text" name="busqueda" value="<?php if(isset($_GET["busqueda"])) echo $_GET["busqueda"]; ?>">
                                                <i class=" icon-search"></i>
                                                <input class="btn" type="submit" value="Buscar">
                                            </div>
                                            <?php if(isset($_GET["busqueda"]) and $_GET["busqueda"]!=""){?> <a href="<?=$_SERVER['PHP_SELF'];?>" class="btn btn-warning"><i class="icon-minus"></i> Quitar Filtro</a> <?php } ?>
                                        </div>
                                    </form>
								<p style="margin: 10px;"><?php include("includes/paginacion.php");?></p> 
                                </p>
                            </div>
							<table class="table table-striped table-bordered" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>Ciudad, Departamento(Ind.)</th>
                                <th>Información</th>
								<th>TK</th>
								<th>SG</th>
								<th>SC</th>
								<th>CT</th>
								<th>FC</th>
								<th>RM</th>
								<th>Sesión<br>Último ingreso</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$filtroGrupos = '';
							if(isset($_GET["grupo"]) and is_numeric($_GET["grupo"])){ $filtroGrupos .="LEFT JOIN clientes_categorias ON cpcat_cliente=cli_id AND cpcat_categoria='".$_GET["grupo"]."'";}
								
							if(isset($_GET["dpto"]) and $_GET["dpto"]!=""){
								$consulta = $conexionBdPrincipal->query("SELECT * FROM ".MAINBD.".clientes
								LEFT JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento AND dep_id='".$_GET["dpto"]."'
								WHERE cli_id=cli_id ".$filtro." AND cli_id_empresa='".$idEmpresa."'
								LIMIT $inicio, $limite
								");
							}else{
								$consulta = $conexionBdPrincipal->query("SELECT * FROM ".MAINBD.".clientes
								LEFT JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento
								$filtroGrupos
								WHERE cli_id=cli_id ".$filtro." AND cli_id_empresa='".$idEmpresa."'
								LIMIT $inicio, $limite
								");
							}
							$no = 1;
							while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
								
								$estadoSesion = 'gris.jpg';
								if($res['cli_sesion']==1){$estadoSesion = 'verde.jpg';}
								
								if(isset($_GET["pap"]) and $res['cli_papelera']==1 and $_GET["pap"]!=1){continue;}

								$fondoPapelera = 'none';
								$titleEstado = '';
								if($res['cli_estado_mercadeo']==6 or $res['cli_papelera']==1){
									$fondoPapelera = 'tomato';
									$titleEstado = 'Papelera - '.$res['cli_estado_mercadeo_fecha'];
								}

								if($res['cli_estado_mercadeo']==2){
									$fondoPapelera = 'goldenrod';
									$titleEstado = 'Número equivocado - '.$res['cli_estado_mercadeo_fecha'];
								}

								if($res['cli_estado_mercadeo']==5){
									$fondoPapelera = 'aqua';
									$titleEstado = 'Actualizado - '.$res['cli_estado_mercadeo_fecha'];
								}
								
								if(!Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
									$consultaNumZ = $conexionBdPrincipal->query("SELECT * FROM zonas_usuarios 
									WHERE zpu_usuario='".$_SESSION["id"]."' AND zpu_zona='".$res['cli_zona']."'");
									$numZ = $consultaNumZ->num_rows;
									
									$consultaNumCliente = $conexionBdPrincipal->query("SELECT * FROM clientes_usuarios 
									WHERE cliu_usuario='".$_SESSION["id"]."' AND cliu_cliente='".$res['cli_id']."'");
									$numCliente = $consultaNumCliente->num_rows;
									
									if($numZ == 0 and $numCliente == 0) continue;
								}
								
								switch($res['cli_categoria']){
									case 1: $categ = 'Prospecto'; $etiquetaC='warning'; $fondoColorCat=''; break;
									case 2: $categ = 'Cliente'; $etiquetaC='info'; $fondoColorCat=''; break;
									case 3: $categ = 'Dealer'; $etiquetaC='info'; $fondoColorCat='aquamarine'; break;
								}
								
								$consultaNumeros = $conexionBdPrincipal->query("
								SELECT
								(SELECT count(tik_id) FROM clientes_tikets WHERE tik_cliente='".$res['cli_id']."'),
								(SELECT count(cseg_id) FROM cliente_seguimiento 
								INNER JOIN clientes_tikets ON tik_id=cseg_tiket 
								WHERE cseg_cliente='".$res['cli_id']."'),
								(SELECT count(sucu_id) FROM sucursales WHERE sucu_cliente_principal='".$res['cli_id']."'),
								(SELECT count(cont_id) FROM contactos WHERE cont_cliente_principal='".$res['cli_id']."'),
								(SELECT count(fact_id) FROM facturacion WHERE fact_cliente='".$res['cli_id']."'),
								(SELECT count(rem_id) FROM remisiones WHERE rem_cliente='".$res['cli_id']."')
								");

								$numeros = mysqli_fetch_array($consultaNumeros, MYSQLI_BOTH);
								
								$color1='#FFF';	$color2='#FFF';	$color3='#FFF';	$color4='#FFF';	$color5='#FFF';	$color6='#FFF';
								if($numeros[0]==0){$color1='#FFF090';}
								if($numeros[1]==0){$color2='#FFF090';}
								if($numeros[2]==0){$color3='#FFF090';}
								if($numeros[3]==0){$color4='#FFF090';}
								if($numeros[4]==0){$color5='#FFF090';}
								if($numeros[5]==0){$color6='#FFF090';}
								
							?>
							<tr title="<?=$titleEstado;?>">
								<td style="background-color: <?=$fondoPapelera;?>;"><?php if($res['cli_retirado']==1) {echo '<span style="color:red;"><strike>(R) '.$no.'</strike></span>';} else {echo $no;}?></td>
                                <td style="background-color: <?=$fondoPapelera;?>;"><?=$res['ciu_nombre'].", ".$res['dep_nombre']." (03".$res['dep_indicativo'].")";?></td>
								
								
								
                                <td style="background-color: <?=$fondoColorCat;?>;">
									<?php echo "<b>Tipo</b>:". $tipoDocumento[$res['cli_tipo_documento']]." | ";?> 
									<?php echo "<b>Documento</b>:". $res['cli_usuario'];?> | <?php echo "<b>Categoría:</b> ". $categ;?><br>
									<?php echo '<span style="font-size:16px;">'.$res['cli_nombre'];?></span> 
                                    <?php if($res['cli_telefono']!="") echo "<br><b>Tel:</b> ". $res['cli_telefono'];?>
                                    <?php if($res['cli_celular']!="") echo "<br><b>Cel:</b> ". $res['cli_celular'];?>
                                    <?php if($res['cli_email']!="") echo " | <b>Email:</b> ". $res['cli_email'];?>
									
                                    <h4 style="margin-top:5px;">
																			<?php if (Modulos::validarRol([11], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																				<a href="clientes-editar.php?id=<?=$res[0];?>" data-toggle="tooltip" title="Editar" target="_blank"><i class="icon-edit"></i></a>&nbsp;
																			<?php } ?>
																			<?php if (Modulos::validarRol([55], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																				<a href="bd_delete/clientes-eliminar.php?id=<?=$res[0];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>&nbsp;
																			<?php } ?>
																			<?php if (Modulos::validarRol([83], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																				<a href="clientes-sucursales.php?cte=<?=$res[0];?>&emg=1" data-toggle="tooltip" title="Sucursales" target="new"><i class="icon-home"></i></a>&nbsp;
																			<?php } ?>
																			<?php if (Modulos::validarRol([44], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																				<a href="clientes-contactos.php?cte=<?=$res[0];?>&emg=1" data-toggle="tooltip" title="Contactos" target="new"><i class="icon-group"></i></a>&nbsp;
																			<?php } ?>
																			<?php if (Modulos::validarRol([88], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																				<a href="clientes-tikets.php?cte=<?=$res[0];?>&emg=1" data-toggle="tooltip" title="Tikets de seguimiento" target="new"><i class="icon-list-ol"></i></a>&nbsp;
																			<?php } ?>
																			<?php if (Modulos::validarRol([12], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																				<a href="clientes-seguimiento.php?cte=<?=$res[0];?>&emg=1" data-toggle="tooltip" title="Seguimiento de clientes" target="new"><i class="icon-list-alt"></i></a>&nbsp;
																			<?php } ?>
																			<?php if (Modulos::validarRol([259], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																				<a href="facturacion.php?cte=<?=$res[0];?>&emg=1" data-toggle="tooltip" title="Facturación" target="new"><i class="icon-money"></i></a>&nbsp;
																			<?php } ?>
																			<?php if (Modulos::validarRol([110], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
																				<a href="enviar-portafolios.php?cte=<?=$res[0];?>" data-toggle="tooltip" title="Enviar portafolios" target="_blank"><i class="icon-list-ul"></i></a>&nbsp;
																			<?php } ?>									
                                	</h4>
                                </td>
								<?php
									$valoresClientes = [
										["url" => "clientes-tikets.php?cte=" . $res['cli_id'], "id" => 88, "color" => $color1, "numero" => $numeros[0]],
										["url" => "clientes-seguimiento.php?cte=" . $res['cli_id'], "id" => 88, "color" => $color2, "numero" => $numeros[1]],
										["url" => "clientes-sucursales.php?cte=" . $res['cli_id'], "id" => 88, "color" => $color3, "numero" => $numeros[2]],
										["url" => "clientes-contactos.php?cte=" . $res['cli_id'], "id" => 88, "color" => $color4, "numero" => $numeros[3]],
										["url" => "facturacion.php?cte=" . $res['cli_id'], "id" => 88, "color" => $color5, "numero" => $numeros[4]],
										["url" => "../v2.0/usuarios/empresa/lab-remisiones.php?cte=" . $res['cli_id'], "id" => 88, "color" => $color6, "numero" => $numeros[5]]
								];
								?>
								<?php foreach ($valoresClientes as $pagina) { ?>
									<?php if (Modulos::validarRol([$pagina['id']], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
											<td align="center" style="background:<?=$pagina['color']?>;"><a href="<?= $pagina['url'] ?>" target="_blank"><?=$pagina['numero']?></a></td>
									<?php } ?>
								<?php } ?>	
								<td><img src="files/<?=$estadoSesion;?>"><br><?=$res['cli_ultimo_ingreso'];?></td>
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