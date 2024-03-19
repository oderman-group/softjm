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
									<h4 align="center">-Busqueda general y paginación-</h4>
									<p>
									<form class="form-horizontal" style="text-align: right;" action="<?=$_SERVER['PHP_SELF'];?>" method="get">
										<div class="search-box">
											<div class="input-append input-icon">
												<input placeholder="Buscar..." id="btn_buscar" type="text" name="busqueda" value="<?php if(isset($_GET["buscar"])) echo $_GET["buscar"]; ?>">
												<i class=" icon-search"></i>
												<input class="btn" type="button" value="Buscar">
											</div>
											<?php if(isset($_GET["busqueda"]) and $_GET["busqueda"]!=""){?> <a href="<?=$_SERVER['PHP_SELF'];?>" class="btn btn-warning"><i class="icon-minus"></i> Quitar Filtro</a> <?php } ?>
										</div>
									</form>
									<p style="margin: 10px;"><?php include("includes/paginacion.php");?></p>
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
											<th>Sesión<br>Último ingreso</th>
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
			btn_buscar.addEventListener('keyup',function(event){buscar()});
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