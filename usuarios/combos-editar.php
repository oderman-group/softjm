<?php include("sesion.php");

$idPagina = 175;

include("includes/verificar-paginas.php");
include("includes/head.php");

require_once RUTA_PROYECTO.'/usuarios/class/Combo.php';

$consultaCombos=$conexionBdPrincipal->query("SELECT * FROM combos WHERE combo_id='".$_GET["id"]."'");
$resultadoD = mysqli_fetch_array($consultaCombos, MYSQLI_BOTH);

$disabled="disabled";
if(Modulos::validarRol([388], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
	$disabled="";
}
?>
<!-- styles -->
<link href="css/chosen.css" rel="stylesheet">
<link href="css/clientes-listado.css" rel="stylesheet">
<link href="../assets-login/plugins/select2/css/select2.css" rel="stylesheet" />
<style>
select#product-select.select2-hidden-accessible {
  position: absolute !important;
  width: 1px !important;
  height: 1px !important;
  padding: 0 !important;
  margin: -1px !important;
  overflow: hidden !important;
  clip: rect(0, 0, 0, 0) !important;
  border: 0 !important;
}
#product-select + .select2-container {
  width: 100% !important;
  max-width: 100%;
  display: inline-block !important;
}
#product-select + .select2-container .select2-selection--multiple {
  min-height: 38px;
}
#product-select + .select2-container .select2-search--inline .select2-search__field {
  width: 12em !important;
  min-width: 12em !important;
  margin-top: 4px;
}
#product-select + .select2-container .select2-selection__choice:has(.combo-prod-off),
.select2-results__option .combo-prod-off {
  background-color: #fee2e2 !important;
  border-color: #fca5a5 !important;
  color: #991b1b !important;
  font-weight: 700;
}
#product-select + .select2-container .select2-selection__choice:has(.combo-prod-ok) {
  background-color: #d1fae5 !important;
  border-color: #6ee7b7 !important;
  color: #065f46 !important;
  font-weight: 700;
}
.select2-results__option .combo-prod-ok {
  color: #065f46 !important;
  font-weight: 700;
}
.combo-editar .clientes-table tbody tr.combo-fila-no-habilitado > td {
  background-color: #fee2e2 !important;
}
.combo-editar .clientes-table tbody tr.combo-fila-habilitado > td {
  background-color: #ecfdf5 !important;
}
.combo-editar .clientes-table tbody tr.combo-fila-no-habilitado a {
  color: #991b1b;
  font-weight: 700;
}
.combo-editar .clientes-table tbody tr.combo-fila-habilitado a {
  color: #047857;
  font-weight: 700;
}
.combo-editar .combo-aviso {
  margin: 0 0 1rem;
  padding: 0.85rem 1rem;
  border-radius: 12px;
  background: #fef3c7;
  border: 1px solid #fcd34d;
  color: #78350f;
  font-weight: 600;
}
.combo-editar .combo-layout {
  display: grid;
  grid-template-columns: 240px minmax(0, 1fr);
  gap: 1.25rem;
  align-items: start;
}
.combo-editar .combo-imagen-col .file-upload-text { font-size: 0.8rem; }
.combo-editar .combo-imagen-col .combo-imagen-actual,
.combo-editar .combo-imagen-col .file-upload-preview img {
  max-width: 100%;
}
.combo-editar .file-upload-container { position: relative; }
.combo-editar .file-upload-wrapper {
  border: 2px dashed #cbd5e1;
  border-radius: 14px;
  padding: 1.1rem 1rem;
  text-align: center;
  background: #f8fafc;
  cursor: pointer;
  transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
}
.combo-editar .file-upload-wrapper:hover,
.combo-editar .file-upload-wrapper:focus {
  border-color: #059669;
  background: #ecfdf5;
  box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
  outline: none;
}
.combo-editar .file-upload-wrapper.dragover {
  border-color: #047857;
  background: #d1fae5;
}
.combo-editar .file-upload-wrapper.has-file {
  border-color: #059669;
  border-style: solid;
  background: #ecfdf5;
}
.combo-editar .file-upload-wrapper.is-locked {
  cursor: default;
  pointer-events: none;
  opacity: 0.75;
}
.combo-editar .file-upload-icon { font-size: 2rem; color: #059669; margin-bottom: 0.4rem; }
.combo-editar .file-upload-text { font-size: 0.9rem; font-weight: 700; color: #0f172a; }
.combo-editar .file-upload-subtext { margin: 0.25rem 0 0.7rem; font-size: 0.75rem; color: #64748b; }
.combo-editar .file-upload-input { display: none; }
.combo-editar .file-upload-button {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  background: linear-gradient(135deg, #059669 0%, #047857 100%);
  color: #fff;
  border: none;
  padding: 0.45rem 0.95rem;
  border-radius: 999px;
  font-weight: 700;
  font-size: 0.8125rem;
  cursor: pointer;
}
.combo-editar .combo-imagen-actual,
.combo-editar .file-upload-preview img {
  display: block;
  max-width: 220px;
  max-height: 140px;
  margin: 0 auto 0.65rem;
  border-radius: 10px;
  object-fit: contain;
  background: #fff;
  border: 1px solid #e2e8f0;
}
.combo-editar .file-upload-preview {
  display: none;
  margin-top: 0.75rem;
  padding: 0.85rem 1rem;
  border-radius: 12px;
  border: 1px solid #a7f3d0;
  background: #fff;
  text-align: center;
}
.combo-editar .file-upload-preview.is-visible { display: block; }
.combo-editar .file-upload-preview-title {
  margin: 0 0 0.45rem;
  font-size: 0.75rem;
  font-weight: 700;
  color: #047857;
}
.combo-editar .file-upload-preview-name { margin: 0.35rem 0 0; font-size: 0.8125rem; color: #334155; }
.combo-editar .file-upload-remove {
  margin-top: 0.55rem;
  border: 1px solid #fecaca;
  background: #fff;
  color: #991b1b;
  border-radius: 999px;
  padding: 0.3rem 0.7rem;
  font-size: 0.75rem;
  font-weight: 700;
  cursor: pointer;
}
.combo-editar .combo-campos { display: grid; grid-template-columns: 1fr 1fr; gap: 0.9rem 1rem; }
.combo-editar .combo-campo-ancho { grid-column: 1 / -1; }
.combo-editar .combo-imagen-col > label,
.combo-editar .combo-campo label {
  display: block;
  margin-bottom: 0.3rem;
  font-size: 0.75rem;
  font-weight: 700;
  color: #334155;
}
.combo-editar .combo-campo input[type="text"],
.combo-editar .combo-campo input[type="number"],
.combo-editar .combo-campo select,
.combo-editar .combo-campo textarea {
  width: 100%;
  box-sizing: border-box;
  height: 40px;
  min-height: 40px;
  margin: 0;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  padding: 0.45rem 0.75rem;
  font-size: 0.9375rem;
  line-height: 1.4;
  color: #0f172a;
  background: #fff;
}
.combo-editar .combo-campo textarea {
  height: auto;
  min-height: 96px;
}
.combo-editar .combo-ayuda { display: block; margin-top: 0.35rem; font-size: 0.75rem; color: #64748b; }
.combo-editar .combo-ayuda.is-error { color: #991b1b; }
.combo-editar .combo-acciones { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.combo-editar .combo-acciones .btn { border-radius: 8px; font-weight: 600; }
.combo-editar .clientes-table-wrap { max-height: calc(100vh - 220px); overflow: auto; }
.combo-editar .clientes-table { font-size: 0.75rem; }
.combo-editar .clientes-table thead th { position: sticky; top: 0; z-index: 2; text-align: center; }
.combo-editar .clientes-table td,
.combo-editar .clientes-table tfoot td { text-align: center; vertical-align: middle; }
.combo-editar .clientes-table td.col-producto { text-align: left; }
.combo-editar .clientes-table tfoot td {
  background: #f8fafc;
  font-weight: 700;
  border-top: 1px solid #e2e8f0;
}
.combo-editar .col-dealer { color: #1e40af; }
.combo-editar .combo-corte { box-shadow: inset 2px 0 0 #cbd5e1; }
.combo-editar .combo-cant {
  width: 64px;
  text-align: center;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  padding: 0.2rem 0.3rem;
}
@media (max-width: 900px) {
  .combo-editar .combo-layout,
  .combo-editar .combo-campos { grid-template-columns: 1fr; }
}
.combo-estado-prod {
  display: inline-block;
  margin-left: 6px;
  padding: 1px 6px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 700;
  vertical-align: middle;
}
.combo-fila-no-habilitado .combo-estado-prod {
  background: #991b1b;
  color: #fff;
}
.combo-fila-habilitado .combo-estado-prod {
  background: #047857;
  color: #fff;
}
</style><!--============ javascript ===========-->
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
<script src="../assets-login/plugins/select2/js/select2.js"></script>
<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>
<?php include("includes/funciones-js.php");?>

<?php include("includes/texto-editor.php");?>

<script type="text/javascript">
  function productos(enviada){
  	  var campo = enviada.title;
	  var producto = enviada.name;
	  var proceso = 7;
	  var valor = enviada.value;
	  $('#resp').empty().hide().html("Esperando...").show(1);
		datos = "producto="+(producto)+"&proceso="+(proceso)+"&valor="+(valor)+"&campo="+(campo);
			   $.ajax({
				   type: "POST",
				   url: "ajax/ajax-productos.php",
				   data: datos,
				   success: function(data){
				   $('#resp').empty().hide().html(data).show(1);
				   }
			   });
}	
</script>
</head>
<body class="clientes-page combo-editar">
<div class="layout">
	<?php include("includes/encabezado.php");?>

	<div class="main-wrapper">
		<div class="container-fluid clientes-page-inner">
			<?php
			$combosCotizacion = Combo::numeroCotizacionesCombo($_GET["id"], $conexionBdPrincipal);
			$migaTema = [
				['texto' => 'Combos', 'url' => 'combos.php'],
				['texto' => $paginaActual['pag_nombre'] ?? 'Editar combos'],
			];
			include 'includes/miga-tema.php';
			?>
			<?php if ($combosCotizacion > 0) { ?>
				<p class="combo-aviso">Este combo se encuentra incluído en <b><?= (int) $combosCotizacion; ?></b> documentos.</p>
			<?php } ?>

			<section class="clientes-panel">
				<div class="clientes-panel-header">
					<div>
						<h3><?= htmlspecialchars($paginaActual['pag_nombre'] ?? 'Editar combos'); ?></h3>
						<p><?= htmlspecialchars($resultadoD['combo_nombre'] ?? ''); ?></p>
					</div>
				</div>
				<div class="clientes-panel-body">
					<form method="post" action="bd_update/combos-actualizar.php" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?= (int) $_GET["id"]; ?>">
						<input type="hidden" name="dctoDealer" value="<?= htmlspecialchars((string) $resultadoD['combo_descuento_dealer'], ENT_QUOTES, 'UTF-8'); ?>">
						<div class="combo-layout">
							<div class="combo-imagen-col">
								<label for="comboFoto">Imagen</label>
								<div class="file-upload-container">
										<div class="file-upload-wrapper<?= $disabled !== '' ? ' is-locked' : ''; ?>" id="comboFotoWrapper" role="button" tabindex="0" aria-controls="comboFoto">
											<?php if ($resultadoD['combo_imagen'] != '') { ?>
												<img class="combo-imagen-actual" src="files/combos/<?= htmlspecialchars($resultadoD['combo_imagen']); ?>" alt="Imagen actual del combo">
											<?php } else { ?>
												<div class="file-upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
											<?php } ?>
											<div class="file-upload-text"><?= $resultadoD['combo_imagen'] != '' ? 'Imagen actual. Arrastre otra para reemplazarla' : 'Arrastre la imagen o selecciónela'; ?></div>
											<div class="file-upload-subtext">JPG, PNG o GIF</div>
											<?php if ($disabled === '') { ?>
											<button type="button" class="file-upload-button" id="btnSeleccionarComboFoto"><i class="fa-solid fa-plus"></i> Seleccionar imagen</button>
											<?php } ?>
										</div>
										<input type="file" id="comboFoto" name="foto" class="file-upload-input" accept="image/jpeg,image/png,image/gif" <?= $disabled; ?>>
										<div class="file-upload-preview" id="comboFotoPreview" aria-live="polite">
											<p class="file-upload-preview-title"><i class="fa-solid fa-eye"></i> Nueva imagen</p>
											<img id="comboFotoPreviewImg" alt="Vista previa de la nueva imagen">
											<p class="file-upload-preview-name" id="comboFotoNombre"></p>
											<button type="button" class="file-upload-remove" id="btnQuitarComboFoto"><i class="fa-solid fa-xmark"></i> Quitar</button>
										</div>
								</div>
							</div>
							<div class="combo-campos">
								<div class="combo-campo combo-campo-ancho">
									<label for="comboNombre">Nombre</label>
									<input type="text" id="comboNombre" name="nombre" value="<?= htmlspecialchars($resultadoD['combo_nombre']); ?>" <?= $disabled; ?>>
								</div>
								<div class="combo-campo combo-campo-ancho">
									<label for="comboEstado">Estado</label>
									<select id="comboEstado" name="estado" <?= $disabled; ?>>
										<option value="1" <?php if ($resultadoD['combo_estado'] == 1) echo "selected"; ?>>Activo</option>
										<option value="0" <?php if ($resultadoD['combo_estado'] == 0) echo "selected"; ?>>Inactivo</option>
									</select>
								</div>
								<div class="combo-campo combo-campo-ancho">
									<label for="comboDescripcion">Descripción</label>
									<textarea id="comboDescripcion" rows="4" name="descripcion" <?= $disabled; ?>><?= htmlspecialchars($resultadoD['combo_descripcion']); ?></textarea>
								</div>
								<div class="combo-campo combo-campo-ancho">
									<label for="product-select">Productos</label>
									<select data-placeholder="Escriba para buscar productos..." tabindex="2" name="producto[]" multiple id="product-select" data-ajax-url="<?= htmlspecialchars(REDIRECT_ROUTE . '/usuarios/ajax/ajax-buscar-productos.php', ENT_QUOTES, 'UTF-8'); ?>" <?= $disabled; ?>>
												<option value=""></option>
												<?php
            									$consultaProductos = $conexionBdPrincipal->query("SELECT * FROM combos_productos 
												INNER JOIN productos ON prod_id=copp_producto AND prod_id_empresa='".$idEmpresa."'
												WHERE copp_combo='".$resultadoD['combo_id']."' ORDER BY prod_nombre");

												while ($resProducto = mysqli_fetch_array($consultaProductos, MYSQLI_BOTH)) {
													$productoHabilitado = ((int) ($resProducto['prod_habilitado'] ?? 0) === 1) ? 1 : 0;
												?>
													<option selected value="<?= $resProducto['prod_id']; ?>" data-habilitado="<?= $productoHabilitado; ?>"><?= $resProducto['prod_id'] . ". " . strtoupper($resProducto['prod_nombre']) . " - [HAY " . $resProducto['prod_existencias'] . "]"; ?></option>
												<?php
													}
												?>
											</select>
											<?php if ($disabled === '') { ?>
											<span class="combo-ayuda">La búsqueda solo muestra productos habilitados. Los que ya están en el combo se conservan aunque no estén habilitados. Para quitar uno, use la X y luego pulse Guardar cambios.</span>
											<?php } else { ?>
											<span class="combo-ayuda is-error">No tiene permiso para editar productos del combo.</span>
											<?php } ?>
								</div>
								<div class="combo-campo">
									<label for="comboDcto">Descuento</label>
									<input type="text" id="comboDcto" name="dcto" value="<?= htmlspecialchars((string) $resultadoD['combo_descuento'], ENT_QUOTES, 'UTF-8'); ?>" <?= $disabled; ?>>
								</div>
								<div class="combo-campo">
									<label for="comboDctoMax">Descuento máximo en cotización</label>
									<input type="text" id="comboDctoMax" name="descuentoMax" value="<?= htmlspecialchars((string) $resultadoD['combo_descuento_maximo'], ENT_QUOTES, 'UTF-8'); ?>" <?= $disabled; ?>>
								</div>
								<div class="combo-campo combo-campo-ancho combo-acciones">
									<?php if (Modulos::validarRol([217], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
									<button type="submit" class="btn btn-success"><i class="icon-save"></i> Guardar cambios</button>
									<?php } ?>
									<a href="combos.php" class="btn">Cancelar</a>
								</div>
							</div>
						</div>
					</form>
				</div>
			</section>

			<section class="clientes-panel" id="productos">
				<div class="clientes-panel-header">
					<div>
						<h3>Productos del combo</h3>
						<p>El valor original es el del momento en que se armó el combo. El ícono de sincronización actualiza ese producto con el precio de lista vigente.</p>
					</div>
				</div>
				<div class="clientes-panel-body">
					<span id="resp"></span>
					<div class="clientes-table-wrap">
							<table class="clientes-table" id="data-table">
							<thead>
							<tr>
								<th>No</th>
                                <th>Producto</th>
                                <th>Cant.</th>
                                <th>Precio original</th>
								<th>Subtotal original</th>
                                <th class="combo-corte">Precio lista</th>
                                <th>Subtotal actual</th>
                                <th class="combo-corte col-dealer">Precio dealer</th>
                                <th class="col-dealer">Subtotal dealer</th>
							</tr>
							</thead>
							<tbody>
                            <?php
							$no = 1;
							$totalDealer=0;
							$total=0;
							$totalCantidad=0;
							$totalOriginal = 0;
							$subtotalOriginal = 0;
							$productos = $conexionBdPrincipal->query("SELECT * FROM productos 
							INNER JOIN combos_productos ON copp_producto=prod_id AND copp_combo='".$_GET["id"]."'
							LEFT JOIN productos_categorias ON catp_id=prod_categoria
							ORDER BY copp_id");
							while($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)){

								$prodCosto = (float) $prod['prod_costo'];
								$prodPrecio = (float) $prod['prod_precio'];
								$coppPrecio = (float) $prod['copp_precio'];
								$coppCantidad = (float) $prod['copp_cantidad'];
								$utilidadDealer = ((float) $prod['prod_descuento2']) / 100;
								$precioDealer = $prodCosto + ($prodCosto * $utilidadDealer);
								$subtotalDealer = $precioDealer * $coppCantidad;
								$totalDealer += $subtotalDealer;

									
								$subtotal = $prodPrecio * $coppCantidad;
								$subtotalOriginal = $coppPrecio * $coppCantidad;
								$total += $subtotal;
								$totalOriginal += $subtotalOriginal;
								
								$totalCantidad += $coppCantidad;

								
							?>
							<?php $habilitadoFila = ((int) ($prod['prod_habilitado'] ?? 0) === 1); ?>
							<tr class="<?= $habilitadoFila ? 'combo-fila-habilitado' : 'combo-fila-no-habilitado'; ?>">
								<td><?=$no;?></td>
                                <td class="col-producto">
									<?php if (Modulos::validarRol([305], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
										<a href="bd_delete/combo-productos-eliminar.php?get=55&idItem=<?=$prod['copp_id'];?>" onClick="if(!confirm('Desea eliminar este registro?')){return false;}"><i class="icon-trash"></i></a>
									<?php } ?>
									<?php if (Modulos::validarRol([418], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && $coppPrecio <> $prodPrecio) {?>
										<a href="bd_update/combo-productos-sincronizar.php?idItem=<?=$prod['copp_id'];?>&nuevoPrecio=<?=$prod['prod_precio'];?>" onClick="if(!confirm('Desea sincronizar este producto con los valores actuales?')){return false;}"><i class="icon-refresh "></i></a>
									<?php } ?>
									<?php if (Modulos::validarRol([38], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
									<a href="productos-editar.php?id=<?=$prod['prod_id'];?>" target="_blank"><?=$prod['prod_id']." - ".$prod['prod_nombre']." <b>(Quedan ".$prod['prod_existencias']." unds.)</b>";?></a>
									<?php } else {?>
									<span><?=$prod['prod_id']." - ".$prod['prod_nombre'];?></span>
									<?php } ?>
									<span class="combo-estado-prod"><?= $habilitadoFila ? 'Habilitado' : 'No habilitado'; ?></span>
								</td>
                                <td><input type="number" class="combo-cant" title="copp_cantidad" name="<?=$prod['copp_id'];?>" value="<?=$prod['copp_cantidad'];?>" onChange="productos(this)" <?=$disabled;?> translate="no"></td>
                                <td>$<?=number_format($coppPrecio,0,",",".");?></td>
								<td>$<?=number_format($subtotalOriginal,0,",",".");?></td>
                                <td class="combo-corte">$<?=number_format($prodPrecio,0,",",".");?></td>
								<td>$<?=number_format($subtotal,0,",",".");?></td>
								<td class="combo-corte col-dealer">$<?=number_format($precioDealer,0,",",".");?></td>
								<td class="col-dealer">$<?=number_format($subtotalDealer,0,",",".");?></td>
							</tr>
							<?php 
								$no ++;
							}
							$descuentoCombo = (float) $resultadoD['combo_descuento'];
							$descuento = $total * ($descuentoCombo / 100);
							$totalNeto = $total - $descuento;

							$descuentoDealer = $totalDealer * ($descuentoCombo / 100);
							$totalNetoDealer = $totalDealer - $descuentoDealer;

							//$descuentoDealer = ($total * ($resultadoD['combo_descuento_dealer']/100));
							//$totalNetoDealer = ($total - $descuentoDealer);	
							?>
	
							</tbody>
							<tfoot>
								<tr>
									<td colspan="4">Subtotal original</td>
									<td>$<?=number_format($totalOriginal,0,",",".");?></td>
									<td class="combo-corte">Subtotal actual</td>
									<td>$<?=number_format($total,0,",",".");?></td>
									<td class="combo-corte col-dealer">Subtotal dealer</td>
									<td class="col-dealer">$<?=number_format($totalDealer,0,",",".");?></td>
								</tr>
								<tr>
									<td colspan="6">Descuento</td>
									<td>$<?=number_format($descuento,0,",",".");?></td>
									<td class="combo-corte col-dealer">Descuento dealer</td>
									<td class="col-dealer">$<?=number_format($descuentoDealer,0,",",".");?></td>
								</tr>
								<tr>
									<td colspan="6">Total neto</td>
									<td>$<?=number_format($totalNeto,0,",",".");?></td>
									<td class="combo-corte col-dealer">Total neto dealer</td>
									<td class="col-dealer">$<?=number_format($totalNetoDealer,0,",",".");?></td>
								</tr>
							</tfoot>
							</table>
					</div>
				</div>
			</section>

		</div>
	</div>
	<?php include("includes/pie.php");?>
	<script>
	window.__defineBak = window.define;
	window.define = undefined;
	</script>
	<script src="../assets-login/plugins/select2/js/select2.js"></script>
	<script>
	window.define = window.__defineBak;
	(function ($) {
		var $el = $("#product-select");
		if (!$el.length) {
			return;
		}
		if (typeof $.fn.select2 !== "function") {
			console.error("Select2 no disponible en combos-editar");
			return;
		}

		if ($el.hasClass("select2-hidden-accessible")) {
			$el.select2("destroy");
		}

		var ajaxUrl = $el.attr("data-ajax-url") || "ajax/ajax-buscar-productos.php";
		function comboProductoHabilitado(item) {
			if (item.habilitado !== undefined && item.habilitado !== null && item.habilitado !== "") {
				return parseInt(item.habilitado, 10) === 1;
			}
			if (item.element) {
				return parseInt($(item.element).attr("data-habilitado"), 10) === 1;
			}
			return true;
		}

		function comboEtiquetaProducto(item) {
			if (!item.id) {
				return item.text;
			}
			var clase = comboProductoHabilitado(item) ? "combo-prod-ok" : "combo-prod-off";
			return $("<span></span>").addClass(clase).text(item.text || "");
		}

		var opts = {
			placeholder: "Escriba para buscar productos...",
			multiple: true,
			width: "100%",
			closeOnSelect: false,
			allowClear: true,
			templateResult: comboEtiquetaProducto,
			templateSelection: comboEtiquetaProducto
		};

		if ($el.prop("disabled")) {
			opts.disabled = true;
			$el.select2(opts);
			return;
		}

		opts.minimumInputLength = 1;
		opts.language = {
			inputTooShort: function () { return "Escriba al menos 1 caracter para buscar"; },
			noResults: function () { return "Sin resultados"; },
			searching: function () { return "Buscando..."; }
		};
		opts.ajax = {
			url: ajaxUrl,
			dataType: "json",
			delay: 250,
			data: function (params) {
				return { term: params.term || "", habilitado: 1 };
			},
			processResults: function (data) {
				if (!data || !$.isArray(data)) {
					return { results: [] };
				}
				return {
					results: $.map(data, function (item) {
						return {
							id: String(item.id),
							text: item.text,
							habilitado: parseInt(item.habilitado, 10) === 1 ? 1 : 0
						};
					})
				};
			}
		};

		$el.select2(opts);

		$el.on("select2:open", function () {
			setTimeout(function () {
				$(".select2-container--open .select2-search__field")
					.css({ width: "200px", minWidth: "200px" })
					.trigger("focus");
			}, 0);
		});
	})(jQuery);
	</script>
	<script>
	(function () {
		var input = document.getElementById('comboFoto');
		var wrapper = document.getElementById('comboFotoWrapper');
		var btnSel = document.getElementById('btnSeleccionarComboFoto');
		var preview = document.getElementById('comboFotoPreview');
		var previewImg = document.getElementById('comboFotoPreviewImg');
		var nombre = document.getElementById('comboFotoNombre');
		var btnQuitar = document.getElementById('btnQuitarComboFoto');
		if (!input || !wrapper || input.disabled) return;

		var actual = wrapper.querySelector('.combo-imagen-actual');
		if (actual) {
			var quitarRota = function () {
				var icono = document.createElement('div');
				icono.className = 'file-upload-icon';
				icono.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i>';
				actual.replaceWith(icono);
				var texto = wrapper.querySelector('.file-upload-text');
				if (texto) texto.textContent = 'Arrastre la imagen o selecciónela';
			};
			if (actual.complete && actual.naturalWidth === 0) quitarRota();
			else actual.addEventListener('error', quitarRota);
		}

		function abrir() { input.click(); }
		function esImagen(file) {
			return file && ['image/jpeg', 'image/png', 'image/gif'].indexOf(file.type) !== -1;
		}
		function limpiar() {
			input.value = '';
			wrapper.classList.remove('has-file');
			preview.classList.remove('is-visible');
			previewImg.removeAttribute('src');
			nombre.textContent = '';
		}
		function mostrar(file) {
			if (!esImagen(file)) {
				limpiar();
				return;
			}
			var lector = new FileReader();
			lector.onload = function () {
				previewImg.src = lector.result;
				nombre.textContent = file.name;
				preview.classList.add('is-visible');
				wrapper.classList.add('has-file');
			};
			lector.readAsDataURL(file);
		}

		wrapper.addEventListener('click', function (e) {
			if (e.target.closest('.file-upload-button')) return;
			abrir();
		});
		wrapper.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				abrir();
			}
		});
		if (btnSel) {
			btnSel.addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				abrir();
			});
		}
		input.addEventListener('change', function () {
			mostrar(input.files && input.files[0]);
		});
		wrapper.addEventListener('dragover', function (e) {
			e.preventDefault();
			wrapper.classList.add('dragover');
		});
		wrapper.addEventListener('dragleave', function () {
			wrapper.classList.remove('dragover');
		});
		wrapper.addEventListener('drop', function (e) {
			e.preventDefault();
			wrapper.classList.remove('dragover');
			if (!e.dataTransfer || !e.dataTransfer.files.length) return;
			input.files = e.dataTransfer.files;
			mostrar(input.files[0]);
		});
		if (btnQuitar) {
			btnQuitar.addEventListener('click', function () { limpiar(); });
		}
	})();
	</script>
</div>
</body>
</html>
