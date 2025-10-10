<?php
include("sesion.php");
$idPagina = 9;
include("includes/verificar-paginas.php");
include("includes/head.php");

include(RUTA_PROYECTO."/usuarios/class/Cliente.php");

$clienteConMasVenta = Cliente::obtenerDatosClienteConMasComprasAgnoActual($idEmpresa, $conexionBdPrincipal);
$clientesNuevosEsteMes = Cliente::clientesNuevosEstesMes($idEmpresa, $conexionBdPrincipal);
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

				<div class="row-fluid">
					<div class="span3">
						<div class="board-widgets magenta">
							<div class="board-widgets-head clearfix">
								<h4 class="pull-left"><i class="icon-user"></i> Cliente con más compras este año </h4>
							</div>
							<div class="board-widgets-content">
								<span class="n-counter"><?=$clienteConMasVenta['cantidad'];?></span><span class="n-sources">Compras</span>
							</div>
							<div class="board-widgets-botttom">
								<a href="clientes-editar.php?id=<?=$clienteConMasVenta['factura_cliente'];?>" target="_blank"><?=$clienteConMasVenta['nombreCliente'];?><i class="icon-double-angle-right"></i></a>
							</div>
						</div>
					</div>

					<div class="span3">
						<div class="board-widgets green">
							<div class="board-widgets-head clearfix">
								<h4 class="pull-left"><i class="icon-star"></i> Cliente nuevos este mes </h4>
							</div>
							<div class="board-widgets-content">
								<span class="n-counter"><?=$clientesNuevosEsteMes;?></span><span class="n-sources">Nuevos</span>
							</div>
							<div class="board-widgets-botttom">
								<a href="clientes.php?clientesNuevos=1">Ver clientes recientes</a>
							</div>
						</div>
					</div>
				</div>

				<!-- Filtros de Fechas -->
				<div class="row-fluid" style="margin-top: 20px;">
					<div class="span12">
						<div class="content-widgets light-gray">
							<div class="widget-head green" style="background: linear-gradient(135deg, #007bff, #0056b3); color: white; border-radius: 10px; cursor: pointer;" data-toggle="collapse" data-target="#filtersCollapse">
								<h3 style="text-align: center; margin: 0; padding: 10px;"><i class="icon-filter"></i> Filtros de Fechas <i class="icon-chevron-down pull-right"></i></h3>
							</div>
							<div class="widget-container">
								<div id="filtersCollapse" class="in collapse" style="padding: 20px; background: #f8f9fa; border: 2px solid #007bff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-top: none; border-top-left-radius: 0; border-top-right-radius: 0;">
								<form method="GET" action="" class="form-horizontal">
									<?php if(isset($_GET["dpto"])) { ?><input type="hidden" name="dpto" value="<?=$_GET["dpto"];?>"><?php } ?>
									<?php if(isset($_GET["tipoDoc"])) { ?><input type="hidden" name="tipoDoc" value="<?=$_GET["tipoDoc"];?>"><?php } ?>
									<?php if(isset($_GET["categoria"])) { ?><input type="hidden" name="categoria" value="<?=$_GET["categoria"];?>"><?php } ?>
									<?php if(isset($_GET["grupo"])) { ?><input type="hidden" name="grupo" value="<?=$_GET["grupo"];?>"><?php } ?>
									<div class="control-group">
										<div class="span3">
											<label class="control-label" style="font-weight: bold;">Fecha Creación Inicio: <?php if(isset($_GET["fecha_registro_inicio"]) && $_GET["fecha_registro_inicio"]!="") { ?><a href="?<?= http_build_query(array_diff_key($_GET, ['fecha_registro_inicio' => ''])) ?>" style="color:red; font-weight: bold;">x</a><?php } ?></label>
											<div class="controls">
												<input type="date" name="fecha_registro_inicio" value="<?= isset($_GET["fecha_registro_inicio"]) ? $_GET["fecha_registro_inicio"] : ""; ?>" class="form-control" style="width: 100%;">
											</div>
										</div>
										<div class="span3">
											<label class="control-label" style="font-weight: bold;">Fecha Creación Fin: <?php if(isset($_GET["fecha_registro_fin"]) && $_GET["fecha_registro_fin"]!="") { ?><a href="?<?= http_build_query(array_diff_key($_GET, ['fecha_registro_fin' => ''])) ?>" style="color:red; font-weight: bold;">x</a><?php } ?></label>
											<div class="controls">
												<input type="date" name="fecha_registro_fin" value="<?= isset($_GET["fecha_registro_fin"]) ? $_GET["fecha_registro_fin"] : ""; ?>" class="form-control" style="width: 100%;">
											</div>
										</div>
										<div class="span3">
											<label class="control-label" style="font-weight: bold;">Fecha Cliente Inicio: <?php if(isset($_GET["fecha_ingreso_inicio"]) && $_GET["fecha_ingreso_inicio"]!="") { ?><a href="?<?= http_build_query(array_diff_key($_GET, ['fecha_ingreso_inicio' => ''])) ?>" style="color:red; font-weight: bold;">x</a><?php } ?></label>
											<div class="controls">
												<input type="date" name="fecha_ingreso_inicio" value="<?= isset($_GET["fecha_ingreso_inicio"]) ? $_GET["fecha_ingreso_inicio"] : ""; ?>" class="form-control" style="width: 100%;">
											</div>
										</div>
										<div class="span3">
											<label class="control-label" style="font-weight: bold;">Fecha Cliente Fin: <?php if(isset($_GET["fecha_ingreso_fin"]) && $_GET["fecha_ingreso_fin"]!="") { ?><a href="?<?= http_build_query(array_diff_key($_GET, ['fecha_ingreso_fin' => ''])) ?>" style="color:red; font-weight: bold;">x</a><?php } ?></label>
											<div class="controls">
												<input type="date" name="fecha_ingreso_fin" value="<?= isset($_GET["fecha_ingreso_fin"]) ? $_GET["fecha_ingreso_fin"] : ""; ?>" class="form-control" style="width: 100%;">
											</div>
										</div>
									</div>
									<div class="form-actions" style="text-align: center; margin-top: 20px;">
										<button type="submit" class="btn btn-success btn-large"><i class="icon-search"></i> Filtrar</button>
										<a href="?<?= http_build_query(array_intersect_key($_GET, array_flip(['dpto','tipoDoc','categoria','grupo']))) ?>" class="btn btn-warning btn-large"><i class="icon-refresh"></i> Limpiar Todos</a>
									</div>
								</form>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row-fluid">
					<div class="span12">
						<div class="navbar">
							<div class="navbar-inner">
								<div class="container">
									<div class="nav-collapse collapse navbar-responsive-collapse">
										<ul class="nav">
											<li><a href="clientes.php"><i class="icon-group"></i> Todos los clientes</a></li>
											<li><a href="javascript:history.go(-1);"><i class="icon-arrow-left"></i> Regresar</a></li>
											<li>
												<?php if (Modulos::validarRol([10], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
													<a href="clientes-agregar.php"><i class="icon-plus"></i> Agregar nuevo</a>
												<?php } ?>
											</li>
											<li>
												<?php if (Modulos::validarRol([252], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
													<a href="clientes-importar.php"><i class="icon-upload"></i> Cargar masivamente</a>
												<?php } ?>
											</li>
											<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#">Más opciones <b class="caret"></b></a>
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
											</li>
										</ul>

										<form action="#<?=$_SERVER['PHP_SELF'];?>" method="get" class="navbar-search pull-left">
											<div class="input-append input-icon">	
												<input type="text" name="busqueda" placeholder="Buscar..." id="btn_buscar" class="search-query span12" value="<?php if(isset($_GET["buscar"])) echo $_GET["buscar"]; ?>">
												<input class="btn" id="btnSubmitBuscar" type="button" value="Buscar">
											</div>
										</form>

										<ul class="nav pull-right">
											<li class="divider-vertical"></li>
											<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#">Grupos <b class="caret"></b></a>
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
											</li>
											<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#">Tipo documento <b class="caret"></b></a>
												<ul class="dropdown-menu">
													<li><a href="clientes.php">Todos</a></li>
													<li><a href="clientes.php?tipoDoc=2&grupo=<?php if(isset($_GET["grupo"])) echo $_GET["grupo"];?>">NIT</a></li>
													<li><a href="clientes.php?tipoDoc=3&grupo=<?php if(isset($_GET["grupo"])) echo $_GET["grupo"];?>">Cédula</a></li>
												</ul>
											</li>
											<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#">Categoría <b class="caret"></b></a>
												<ul class="dropdown-menu">
													<li><a href="clientes.php">Todos</a></li>
													<li><a href="clientes.php?categoria=1&grupo=<?php if(isset($_GET["grupo"])) echo $_GET["grupo"];?>">Prospecto</a></li>
													<li><a href="clientes.php?categoria=2&grupo=<?php if(isset($_GET["grupo"])) echo $_GET["grupo"];?>">Cliente</a></li>
													<li><a href="clientes.php?categoria=3&grupo=<?php if(isset($_GET["grupo"])) echo $_GET["grupo"];?>">Dealer</a></li>
												</ul>
											</li>
										</ul>
									</div>
									<!-- /.nav-collapse -->
								</div>
							</div>
							<!-- /navbar-inner -->
						</div>
					</div>
				</div>

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
						<div class="content-widgets light-gray">
							<div class="widget-head green">
								<h3><?=$paginaActual['pag_nombre'];?></h3>
							</div>
							<?php
							$filtro = "";
							if (isset($_GET["pap"]) and $_GET["pap"] == 1) {
								$filtro .= " AND cli_papelera=1";
							}

							$filtroGrupos = '';
							if (isset($_GET["grupo"]) and is_numeric($_GET["grupo"])) {
								$filtroGrupos .= "LEFT JOIN clientes_categorias ON cpcat_cliente=cli_id AND cpcat_categoria='" . $_GET["grupo"] . "'";
							}

							$tipoDoc="";
							if (isset($_GET["tipoDoc"]) and is_numeric($_GET["tipoDoc"])) {
								$filtro .= " AND cli_tipo_documento='" . $_GET["tipoDoc"] . "'";
								$tipoDoc=$_GET["tipoDoc"];
							}

							if(Modulos::validarRol([385], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
								$filtro.=' AND cli_ciudad!="1122"';
							}

							if (isset($_GET["clientesNuevos"])) {
								$filtro .= " AND year(cli_fecha_ingreso)=".date("Y")." AND month(cli_fecha_ingreso)=".date("m");
							}

							if (isset($_GET["categoria"]) && is_numeric($_GET["categoria"])) {
								$filtro .= " AND cli_categoria=".$_GET["categoria"];
							}
							if (isset($_GET["fecha_registro_inicio"]) and $_GET["fecha_registro_inicio"] != "") {
								$filtro .= " AND cli_fecha_registro >= '" . $_GET["fecha_registro_inicio"] . " 00:00:00'";
							}
							if (isset($_GET["fecha_registro_fin"]) and $_GET["fecha_registro_fin"] != "") {
								$filtro .= " AND cli_fecha_registro <= '" . $_GET["fecha_registro_fin"] . " 23:59:59'";
							}
							if (isset($_GET["fecha_ingreso_inicio"]) and $_GET["fecha_ingreso_inicio"] != "") {
								$filtro .= " AND cli_fecha_ingreso >= '" . $_GET["fecha_ingreso_inicio"] . " 00:00:00' AND cli_categoria = 2";
							}
							if (isset($_GET["fecha_ingreso_fin"]) and $_GET["fecha_ingreso_fin"] != "") {
								$filtro .= " AND cli_fecha_ingreso <= '" . $_GET["fecha_ingreso_fin"] . " 23:59:59' AND cli_categoria = 2";
							}
							?>

							<?php
							$dpto="";
							if (isset($_GET["dpto"]) and $_GET["dpto"]!="") {
								$SQL = "SELECT * FROM ".MAINBD.".clientes
								LEFT JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento AND dep_id='".$_GET["dpto"]."'
								$filtroGrupos
								WHERE cli_id=cli_id ".$filtro."";
								$dpto=$_GET["dpto"];
							}else{
								$SQL = "SELECT * FROM ".MAINBD.".clientes
								LEFT JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
								INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento 
								$filtroGrupos
								WHERE cli_id=cli_id ".$filtro."
								";					
							}
							?>

							<div class="widget-container">
								<div style="border:thin; border-style:solid; height:150px; margin:10px; padding:10px;">
									<p style="margin: 10px;"><?php include("includes/paginacion.php");?></p>
									<p style="font-size: 11px; text-align:center; margin-top:40px;">
										TK = Tickets | SG = Seguimientos | SC = Sucursales | CT = Contactos | FC = Facturas | RM = Remisiones
									</p>
								</div>
								<table class="table table-striped table-bordered">
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
										</tr>
									</thead>
									<tbody id="clientes_buscar">	
									<?php include("fetch-buscar-clientes.php"); ?>					

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			btnSubmitBuscar.addEventListener('click',function(event){buscar()});
			function buscar(){
				var valor = document.getElementById('btn_buscar').value;
				var tbody = document.getElementById('clientes_buscar');
				tbody.innerHTML='';
    
				fetch('fetch-buscar-clientes.php?buscar='+valor+'&inicio=<?=$inicio?>&limite=<?=$limite?>&tipoDoc=<?=$tipoDoc?>&dpto=<?=$dpto?>&filtroGrupos=<?=$filtroGrupos?>', {
					method: 'GET'
				})
				.then(response => response.text())
				.then(data => {
					tbody.innerHTML=data;
				})
				.catch(error => {
					console.error('Error:', error);
				});						
			}
		</script>
	</div>

	<?php include("includes/pie.php"); ?>
	</div>

</body>
</html>