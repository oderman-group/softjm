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
							<form class="form-horizontal" method="post" action="bd_create/roles-guardar.php">
                                
                                <div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" class="span6" name="nombre">
									</div>
								</div>
                                
								<table class="table table-striped table-bordered" id="data-table">
										<thead>
												<tr>
														<th>ID</th>
														<th>Nombre</th>
														<th>Seleccionar <input type="checkbox" id="selectAll"></th>
												</tr>
										</thead>
										<tbody>
												<?php
												$dataBase = $_SESSION["bd"];
												$query = "SELECT p.pag_id, p.pag_nombre, pp.pper_id 
														FROM paginas p 
														LEFT JOIN $dataBase.paginas_perfiles pp 
														ON p.pag_id = pp.pper_pagina 
														AND pp.pper_tipo_usuario = '" . $resultadoD['utipo_id'] . "'";
												$result = $conexionBdAdmin->query($query);
												$no=1;
												while ($row = $result->fetch_assoc()) {
														?>
														<tr>
																<td><?= $row['pag_id'];?></td>
																<td><?= $row['pag_nombre']; ?></td>
																<td>
																		<input class="selectCheckbox" type="checkbox" value="<?= $row['pag_id']; ?>">
																		<span style="display: none;"> <?= $isChecked; ?> </span>
																</td>
														</tr>
												<?php
												$no++;
												}
												?>
										</tbody>
								</table>
                                <!--
                                
                                <span style="color:#F00;">Utiliza la siguiente opción (<b>Acciones NO permitidas</b>) sólo si NO utilizaste la opción anterior (<b>Paginas NO permitidas</b>) y viceversa.</span>
                                
                                <div class="control-group">
									<label class="control-label">Acciones NO permitidas</label>
									<div class="controls">
										<select data-placeholder="Escoja una opción" class="chzn-select span6" multiple tabindex="4" name="accionesNP[]">
											<option value=""></option>
                                            <option value="2">Agregar</option>
                                            <option value="3">Editar</option>
                                            <option value="3">Eliminar</option>
										</select>
									</div>
								</div>

                            -->
								<select id="paginasSeleccionadas"  name="paginasP[]" multiple  style="display: none;"></select>                               
								<div class="form-actions">
									<button type="submit" class="btn btn-info"><i class="icon-save"></i> Guardar cambios</button>
									<button type="button" class="btn btn-danger">Cancelar</button>
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
<script type="text/javascript">
	let dataTable = $('#data-table').DataTable()
	    function actualizarPaginasSeleccionadas(dataTable) {
        let paginasSeleccionadas = [];
				dataTable.$('.selectCheckbox').each(function() {
            if ($(this).prop('checked')) {
                paginasSeleccionadas.push($(this).val());
            }
        });

				$('#selectAll').change(function() {
				let isChecked = $(this).prop('checked');	
				dataTable.$('.selectCheckbox').each(function() {
								$(this).prop('checked',isChecked)
                paginasSeleccionadas.push($(this).val());
        });
			});
			
				let selectElement = $('#paginasSeleccionadas');
        selectElement.find('option').remove(); 
				for (let i = 0; i < paginasSeleccionadas.length; i++) {
						selectElement.append($('<option>', {
								value: paginasSeleccionadas[i]
						}));
				}
				selectElement.val(paginasSeleccionadas);

    }

    $(document).ready(function() {
        actualizarPaginasSeleccionadas(dataTable);

        $(document).on('change', 'input[type="checkbox"]', function() {
            actualizarPaginasSeleccionadas(dataTable);
        });
    });
		
</script>
</html>