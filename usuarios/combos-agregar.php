<?php
include("sesion.php");
include(RUTA_PROYECTO."/usuarios/class/Utilidades.php");

$idPagina = 174;

include("includes/verificar-paginas.php");
include("includes/head.php");

$numPaquetes = Utilidades::getNextIdSequence($conexionBdPrincipal, MAINBD, "combos");
$nombreSugerido = 'P ' . $numPaquetes . ' - ';
?>
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
#product-select + .select2-container { width: 100% !important; max-width: 100%; display: inline-block !important; }
#product-select + .select2-container .select2-selection--multiple { min-height: 38px; }
#product-select + .select2-container .select2-search--inline .select2-search__field {
  width: 12em !important;
  min-width: 12em !important;
  margin-top: 4px;
}
#product-select + .select2-container .select2-selection__choice:has(.combo-prod-ok),
.select2-results__option .combo-prod-ok {
  color: #065f46 !important;
  font-weight: 700;
}
#product-select + .select2-container .select2-selection__choice:has(.combo-prod-ok) {
  background-color: #d1fae5 !important;
  border-color: #6ee7b7 !important;
}
.combo-editar .combo-layout {
  display: grid;
  grid-template-columns: 240px minmax(0, 1fr);
  gap: 1.25rem;
  align-items: start;
}
.combo-editar .file-upload-container { position: relative; }
.combo-editar .file-upload-wrapper {
  border: 2px dashed #cbd5e1;
  border-radius: 14px;
  padding: 1.1rem 1rem;
  text-align: center;
  background: #f8fafc;
  cursor: pointer;
}
.combo-editar .file-upload-wrapper:hover,
.combo-editar .file-upload-wrapper:focus,
.combo-editar .file-upload-wrapper.dragover {
  border-color: #059669;
  background: #ecfdf5;
  outline: none;
}
.combo-editar .file-upload-wrapper.has-file { border-color: #059669; border-style: solid; background: #fff; }
.combo-editar .file-upload-wrapper.has-file .file-upload-placeholder { display: none; }
.combo-editar .file-upload-icon { font-size: 2rem; color: #059669; margin-bottom: 0.4rem; }
.combo-editar .file-upload-text { font-size: 0.8rem; font-weight: 700; color: #0f172a; }
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
.combo-editar .file-upload-preview {
  display: none;
  margin: 0;
  padding: 0;
  border: 0;
  background: transparent;
  text-align: center;
}
.combo-editar .file-upload-preview.is-visible { display: block; }
.combo-editar .file-upload-preview img {
  display: block;
  max-width: 100%;
  max-height: 140px;
  margin: 0 auto 0.45rem;
  border-radius: 10px;
  object-fit: contain;
  background: #fff;
  border: 1px solid #e2e8f0;
}
.combo-editar .file-upload-preview-title { margin: 0 0 0.45rem; font-size: 0.75rem; font-weight: 700; color: #047857; }
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
.combo-editar .combo-campo textarea { height: auto; min-height: 96px; }
.combo-editar .combo-ayuda { display: block; margin-top: 0.35rem; font-size: 0.75rem; color: #64748b; }
.combo-editar .combo-acciones { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.combo-editar .combo-acciones .btn { border-radius: 8px; font-weight: 600; }
@media (max-width: 900px) {
  .combo-editar .combo-layout,
  .combo-editar .combo-campos { grid-template-columns: 1fr; }
}
</style>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<?php include("includes/js-formularios.php"); ?>
</head>
<body class="clientes-page combo-editar">
<div class="layout">
	<?php include("includes/encabezado.php");?>
	<div class="main-wrapper">
		<div class="container-fluid clientes-page-inner">
			<?php
			$migaTema = [
				['texto' => 'Combos', 'url' => 'combos.php'],
				['texto' => $paginaActual['pag_nombre'] ?? 'Agregar combos'],
			];
			include 'includes/miga-tema.php';
			?>

			<section class="clientes-panel">
				<div class="clientes-panel-header">
					<div>
						<h3><?= htmlspecialchars($paginaActual['pag_nombre'] ?? 'Agregar combos'); ?></h3>
						<p>Nombre, imagen, productos habilitados y descuentos</p>
					</div>
				</div>
				<div class="clientes-panel-body">
					<form method="post" action="bd_create/combos-guardar.php" enctype="multipart/form-data">
						<input type="hidden" name="dctoDealer" value="0">
						<div class="combo-layout">
							<div class="combo-imagen-col">
								<label for="comboFoto">Imagen</label>
								<div class="file-upload-container">
									<div class="file-upload-wrapper" id="comboFotoWrapper" role="button" tabindex="0" aria-controls="comboFoto">
										<div class="file-upload-placeholder" id="comboFotoPlaceholder">
											<div class="file-upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
											<div class="file-upload-text">Arrastre la imagen o selecciónela</div>
											<div class="file-upload-subtext">JPG, PNG o GIF</div>
											<button type="button" class="file-upload-button" id="btnSeleccionarComboFoto"><i class="fa-solid fa-plus"></i> Seleccionar imagen</button>
										</div>
										<div class="file-upload-preview" id="comboFotoPreview" aria-live="polite">
											<img id="comboFotoPreviewImg" alt="Vista previa de la imagen">
											<p class="file-upload-preview-name" id="comboFotoNombre"></p>
											<button type="button" class="file-upload-remove" id="btnQuitarComboFoto"><i class="fa-solid fa-xmark"></i> Quitar</button>
										</div>
									</div>
									<input type="file" id="comboFoto" name="foto" class="file-upload-input" accept="image/jpeg,image/png,image/gif">
								</div>
							</div>
							<div class="combo-campos">
								<div class="combo-campo combo-campo-ancho">
									<label for="comboNombre">Nombre</label>
									<input type="text" id="comboNombre" name="nombre" value="<?= htmlspecialchars($nombreSugerido, ENT_QUOTES, 'UTF-8'); ?>">
								</div>
								<div class="combo-campo combo-campo-ancho">
									<label for="comboEstado">Estado</label>
									<select id="comboEstado" name="estado">
										<option value="1" selected>Activo</option>
										<option value="0">Inactivo</option>
									</select>
								</div>
								<div class="combo-campo combo-campo-ancho">
									<label for="comboDescripcion">Descripción</label>
									<textarea id="comboDescripcion" rows="4" name="descripcion"></textarea>
								</div>
								<div class="combo-campo combo-campo-ancho">
									<label for="product-select">Productos</label>
									<select data-placeholder="Escriba para buscar productos..." name="producto[]" multiple id="product-select" data-ajax-url="<?= htmlspecialchars(REDIRECT_ROUTE . '/usuarios/ajax/ajax-buscar-productos.php', ENT_QUOTES, 'UTF-8'); ?>"></select>
									<span class="combo-ayuda">La búsqueda solo muestra productos habilitados.</span>
								</div>
								<div class="combo-campo">
									<label for="comboDcto">Descuento</label>
									<input type="text" id="comboDcto" name="dcto" value="">
								</div>
								<div class="combo-campo">
									<label for="comboDctoMax">Descuento máximo en cotización</label>
									<input type="text" id="comboDctoMax" name="descuentoMax" value="">
								</div>
								<div class="combo-campo combo-campo-ancho combo-acciones">
									<button type="submit" class="btn btn-success"><i class="icon-save"></i> Guardar cambios</button>
									<a href="combos.php" class="btn">Cancelar</a>
								</div>
							</div>
						</div>
					</form>
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
		if (!$el.length || typeof $.fn.select2 !== "function") {
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
			return true;
		}
		function comboEtiquetaProducto(item) {
			if (!item.id) {
				return item.text;
			}
			return $("<span></span>").addClass(comboProductoHabilitado(item) ? "combo-prod-ok" : "combo-prod-off").text(item.text || "");
		}
		$el.select2({
			placeholder: "Escriba para buscar productos...",
			multiple: true,
			width: "100%",
			closeOnSelect: false,
			allowClear: true,
			minimumInputLength: 1,
			templateResult: comboEtiquetaProducto,
			templateSelection: comboEtiquetaProducto,
			language: {
				inputTooShort: function () { return "Escriba al menos 1 caracter para buscar"; },
				noResults: function () { return "Sin resultados"; },
				searching: function () { return "Buscando..."; }
			},
			ajax: {
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
			}
		});
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
		if (!input || !wrapper) return;

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
			if (e.target.closest('.file-upload-button') || e.target.closest('.file-upload-remove')) return;
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
		input.addEventListener('change', function () { mostrar(input.files && input.files[0]); });
		wrapper.addEventListener('dragover', function (e) {
			e.preventDefault();
			wrapper.classList.add('dragover');
		});
		wrapper.addEventListener('dragleave', function () { wrapper.classList.remove('dragover'); });
		wrapper.addEventListener('drop', function (e) {
			e.preventDefault();
			wrapper.classList.remove('dragover');
			if (!e.dataTransfer || !e.dataTransfer.files.length) return;
			input.files = e.dataTransfer.files;
			mostrar(input.files[0]);
		});
		if (btnQuitar) {
			btnQuitar.addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				limpiar();
			});
		}
	})();
	</script>
</div>
</body>
</html>
