<?php include("sesion.php");

$idPagina = 6;

include("includes/verificar-paginas.php");
include("includes/head.php");
?>
<link href="css/tablecloth.css" rel="stylesheet">

<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-fileupload.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.tagsinput.js"></script>
<script src="js/chosen.jquery.js"></script>
<script src="js/bootstrap-colorpicker.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script src="js/date.js"></script>
<script src="js/daterangepicker.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script src="js/jquery.tablecloth.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/dataTables.bootstrap.js"></script>
<script src="js/TableTools.js"></script>
<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>

<?php include("includes/funciones-js.php");?>
</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
	<div class="main-wrapper">
		<div class="container-fluid">
			<div class="row-fluid ">
				<div class="span12">
					<div class="primary-head">
						<h3 class="page-header"><?=$paginaActual['pag_nombre'];?></h3>
					</div>
					<ul class="breadcrumb">
						<li><a href="index.php" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
						<li><a href="roles.php">Roles</a><span class="divider"><i class="icon-angle-right"></i></span></li>
						<li class="active"><?=$paginaActual['pag_nombre'];?></li>
					</ul>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="content-widgets gray">
						<div class="widget-head bondi-blue">
							<h3> <?=$paginaActual['pag_nombre'];?></h3>
						</div>
						<div class="widget-container">

							<ul class="nav nav-tabs" id="myTab1">
								<?php
								$queryModulos = "SELECT * FROM modulos 
								INNER JOIN modulos_empresa ON mxe_id_modulo=mod_id AND mxe_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' 
								WHERE mod_padre IS NULL";
								$resultModulos = $conexionBdAdmin->query($queryModulos);
								$conta = 1;
								while ($rowModulos = $resultModulos->fetch_assoc()) {
								?>
									<li <?php if($conta == 1) echo 'class="active"';?> id="mod<?=$rowModulos['mod_id'];?>"><a onclick="mostrarPaginas(<?=$rowModulos['mod_id'];?>, <?=$_GET['id'];?>)"><i class="icon-tasks"></i> <?=$rowModulos['mod_nombre'];?></a></li>
								<?php
									$conta ++;
								}
								?>
							</ul>

							<form class="form-horizontal" method="post" action="bd_create/roles-guardar.php">
								<div class="tab-content">
									<div class="control-group">
										<label class="control-label">Nombre</label>
										<div class="controls">
											<input type="text" class="span4" name="nombre" id="nombre" value="<?=$resultadoD['utipo_nombre'];?>" required>
										</div>
									</div> 
									
									<div id="divTablePaginas"></div>
								</div>
											
								<select id="paginasSeleccionadas"  name="paginasP[]" multiple  style="display: none;"></select>                 

								<div class="form-actions">
									<a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	<?php include("includes/pie.php");?>
</div>
</body>
<script src="js/Roles.js" ></script>
<script type="text/javascript">
	$(document).ready(mostrarPaginas(1, <?=$_GET['id'];?>));
</script>
<script type="text/javascript">
	// let dataTable = $('#data-table').DataTable()
	//     function actualizarPaginasSeleccionadas(dataTable) {
    //     let paginasSeleccionadas = [];
	// 			dataTable.$('.selectCheckbox').each(function() {
    //         if ($(this).prop('checked')) {
    //             paginasSeleccionadas.push($(this).val());
    //         }
    //     });

	// 			$('#selectAll').change(function() {
	// 			let isChecked = $(this).prop('checked');	
	// 			dataTable.$('.selectCheckbox').each(function() {
	// 							$(this).prop('checked',isChecked)
    //             paginasSeleccionadas.push($(this).val());
    //     });
	// 		});
			
	// 			let selectElement = $('#paginasSeleccionadas');
    //     selectElement.find('option').remove(); 
	// 			for (let i = 0; i < paginasSeleccionadas.length; i++) {
	// 					selectElement.append($('<option>', {
	// 							value: paginasSeleccionadas[i]
	// 					}));
	// 			}
	// 			selectElement.val(paginasSeleccionadas);

    // }

    // $(document).ready(function() {
    //     actualizarPaginasSeleccionadas(dataTable);

    //     $(document).on('change', 'input[type="checkbox"]', function() {
    //         actualizarPaginasSeleccionadas(dataTable);
    //     });
    // });
		
</script>
</html>