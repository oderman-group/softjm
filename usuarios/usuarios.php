<?php 
include("sesion.php");

$idPagina = 2;

include("verificar-paginas.php");
include("head.php");
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
			"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>"
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
<?php include("funciones-js.php");?>
</head>
<body>
	<div class="layout">
		<?php include("encabezado.php");?>		
		<div class="main-wrapper">
			<div class="container-fluid">
				<div class="row-fluid">
					<?php include("notificaciones.php");?>
					<span id="resp"></span>
					<p>
						<a href="usuarios-agregar.php" class="btn btn-danger"><i class="icon-plus"></i> Agregar nuevo</a>
						<a href="usuarios.php?bloq=1" class="btn btn-warning"><i class="icon-lock"></i> Usuarios bloqueados</a>
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

												$consulta = $conexionBdPrincipal->query("SELECT * FROM usuarios
												INNER JOIN usuarios_tipos ON utipo_id=usr_tipo
												INNER JOIN areas ON ar_id=usr_area
												WHERE usr_id!='".$_SESSION["id"]."' $filtro
												");
												$no = 1;
												$bloq = array("NO","SI");	
												while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
													$estadoSesion = 'gris.jpg';
													if($res['usr_sesion']==1){$estadoSesion = 'verde.jpg';}
											?>
											<tr>
												<td><?=$no;?></td>
												<td><img src="files/fotos/<?=$res['usr_foto'];?>" width="100"></td>
												<td>
													<?=$res['usr_nombre'];?><br>
													<span style="text-decoration: underline; color:blue; font-style: italic;"><?=$res['usr_email'];?></span>
												</td>
												<td><a href="roles-editar.php?id=<?=$res['usr_tipo'];?>"><?=$res['utipo_nombre'];?></a></td>
												<td><a href="#areas-editar.php?id=<?=$res['usr_area'];?>"><?=$res['ar_nombre'];?></a></td>
												<td><?=$res['usr_login'];?></td>
												<td><?=$bloq[$res['usr_bloqueado']];?></td>
												<td><img src="files/<?=$estadoSesion;?>"></td>
												<td><?=$res['usr_ultimo_ingreso'];?></td>

												<td>
													<input id="<?= $res['usr_id']; ?>" type="text"  value="<?= $res['usr_meta_ventas']; ?>" style="width: 80px; text-align: center" onChange="usuarios(this)">
													<span style="display: none;"><?=number_format($res['usr_meta_ventas'],0,".",".");?></span>
													
												</td>

												<td>
													<h4>
														<a href="usuarios-editar.php?id=<?=$res['usr_id'];?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
														
														<a href="bd_delete/usuarios-eliminar.php?id=<?=$res['usr_id'];?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
														
														<a href="calendario.php?id=<?=$res['usr_id'];?>" data-toggle="tooltip" title="Calendario"><i class="icon-calendar"></i></a>
														
														<a href="graficos/4.php?usuario=<?=$res['usr_id'];?>" data-toggle="tooltip" title="Gestión comercial" target="_blank"><i class="icon-list"></i></a>

														<a href="facturas.php?usuario=<?=$res['usr_id'];?>" data-toggle="tooltip" title="Facturación" target="_blank"><i class="icon-money"></i></a>
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
		<?php include("pie.php");?>
	</div>
</body>
</html>