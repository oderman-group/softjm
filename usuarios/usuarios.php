<?php 
include("sesion.php");

$idPagina = 2;

include("includes/verificar-paginas.php");
include("includes/head.php");
?>
<!-- styles -->
<link href="css/tablecloth.css" rel="stylesheet">
<!--============javascript===========-->
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
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
			"oTableTools": {
			"aButtons": [
				"copy",
				"print",
				{
					"sExtends":    "collection",
					"sButtonText": 'Save <span class="caret" />',
					"aButtons":    [ "csv", "xls", "pdf" ]
				}
			]
			}
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

<script type="text/javascript">	
	function usuarios(enviada) {
		var valorActual = enviada.value;
		var idUsuario = enviada.id;
		var proceso = 1;
		$('#resp').empty().hide().html("Esperando...").show(1);
		datos = "idUsuario=" + (idUsuario) + "&proceso=" + (proceso) + "&valorActual=" + (valorActual);
		$.ajax({
			type: "POST",
			url: "ajax/ajax-usuarios.php",
			data: datos,
			success: function(data) {
				$('#resp').empty().hide().html(data).show(1);
			}
		});
	}

</script>
<?php include("includes/funciones-js.php");?>
</head>
<body>
	<div class="layout">
		<?php include("includes/encabezado.php");?>		
		<div class="main-wrapper">
			<div class="container-fluid">
				<div class="row-fluid">
					<?php include("includes/notificaciones.php");?>
					<span id="resp"></span>
					<p>
						<?php
							if (Modulos::validarRol([3], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
								echo '<a href="usuarios-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a> ';
							}
							if (Modulos::validarRol([2], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
								echo '<a href="usuarios.php?bloq=1" class="btn btn-warning"><i class="icon-lock"></i> Usuarios bloqueados</a>';
							}
						?>	 					
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
												<th>Datos</th>
												<th>Tipo usuario</th>
												<th>Área</th>
												<th>Usuario de acceso</th>
												<th>Bloq.</th>
												<th>Sesión</th>
												<th>Último ingreso</th>
												<th>Meta</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php
												$filtro = " AND usr_bloqueado='0'";
												if(isset($_GET['bloq']) AND $_GET['bloq']==1){$filtro =" AND usr_bloqueado='1'";}

												$consulta = $conexionBdPrincipal->query("SELECT u.*, GROUP_CONCAT(r.utipo_id) AS roles_id,
												GROUP_CONCAT(r.utipo_nombre) AS roles_nombre
												FROM usuarios AS u
												LEFT JOIN ".BDADMIN.".usuarios_roles AS ru ON u.usr_id = ru.upr_id_usuario
												LEFT JOIN usuarios_tipos AS r ON ru.upr_id_rol = r.utipo_id
												LEFT JOIN areas ON ar_id = u.usr_area
												WHERE u.usr_id != '" . $_SESSION["id"] . "' $filtro AND
												usr_id_empresa =  '".$_SESSION["dataAdicional"]["id_empresa"]."'
												GROUP BY u.usr_id");
												$no = 1;
												while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
													$estadoSesion = 'gris.jpg';
													if(empty($res['usr_sesion']) && $res['usr_sesion']==1){$estadoSesion = 'verde.jpg';}
											?>
											<tr>
												<td><?=$no;?></td>
												<td><img src="files/fotos/<?=$res['usr_foto'];?>" width="70"></td>
												<td>
													<?=$res['usr_nombre'];?><br>
													<span style="text-decoration: underline; color:blue; font-style: italic;"><?=$res['usr_email'];?></span>
												</td>
												<td>   
													<?php
															if (!empty($res['roles_id'])) {
																	$roles_id = explode(',', $res['roles_id']); 
																	$roles_nombre = explode(',', $res['roles_nombre']);
																	for ($i = 0; $i < count($roles_id); $i++) {
																		if (Modulos::validarRol([7], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
																			echo '<a href="roles-editar.php?id=' . $roles_id[$i] . '">' . $roles_nombre[$i] . '</a><br>';
																		}	else {
																			echo '<span>'.$roles_nombre[$i].'</span><br>';
																		}
																}
															} else {
																	echo 'Sin roles asignados';
															}
															?>
												</td>
												<td><a href="#areas-editar.php?id=<?=$res['usr_area'];?>"><?=$res['ar_nombre'];?></a></td>
												<td><?=$res['usr_login'];?></td>
												<td><?=$opcionesSINO[$res['usr_bloqueado']];?></td>
												<td><img src="files/<?=$estadoSesion;?>" width="20"></td>
												<td><?=$res['usr_ultimo_ingreso'];?></td>

												<td>
													<input id="<?= $res['usr_id']; ?>" type="text"  value="<?= $res['usr_meta_ventas']; ?>" style="width: 80px; text-align: center" onChange="usuarios(this)">
													<span style="display: none;"><?php if(!empty($res['usr_meta_ventas']) && is_numeric($res['usr_meta_ventas'])) echo number_format($res['usr_meta_ventas'],0,".",".");?></span>
													
												</td>

												<td>
													<h4>
														<?php
														  if(!isset($_SESSION['admin']) && Modulos::validarRol([344], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
																echo '<a href="auto-login.php?user='.$res['usr_id'].'" data-toggle="tooltip" title="Auto Login"><i class="icon-retweet"></i></a> ';
														  }
															if (Modulos::validarRol([4], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
																echo '<a href="usuarios-editar.php?id='.$res['usr_id'].'" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a> ';
															}
															if (Modulos::validarRol([53], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
															?>
																<a href="bd_delete/usuarios-eliminar.php?id=<?=$res['usr_id'];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
															<?php
															}
															if (Modulos::validarRol([104], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
																echo '<a href="calendario.php?id='.$res['usr_id'].'" data-toggle="tooltip" title="Calendario"><i class="icon-calendar"></i></a> ';
															}
															if (Modulos::validarRol([345], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
																echo '<a href="graficos/4.php?usuario='.$res['usr_id'].'" data-toggle="tooltip" title="Gestión comercial" target="_blank"><i class="icon-list"></i></a> ';
															}
															if (Modulos::validarRol([126], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
																echo '<a href="facturas.php?usuario='.$res['usr_id'].'" data-toggle="tooltip" title="Facturación" target="_blank"><i class="icon-money"></i></a> ';
															}
													?>		
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
