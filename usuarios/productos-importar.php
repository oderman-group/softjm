<?php
include("sesion.php");

$idPagina = 21;
include("includes/verificar-paginas.php");
include("includes/head.php");

include_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';
include_once RUTA_PROYECTO . '/usuarios/includes/productos-importar-helpers.php';
productosImportLimpiarJobsUsuario((int) ($_SESSION['id'] ?? 0), (int) $idEmpresa);
$ofimaProductosActiva = ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa);

$habilitadoActual = (isset($_GET['habilitado']) && (string) $_GET['habilitado'] === '0') ? '0' : '1';
$vistaActual = '';
foreach (['web', 'pdt', 'stock', 'utilidad'] as $claveVista) {
	if (!empty($_GET[$claveVista]) || (isset($_GET['vista']) && $_GET['vista'] === $claveVista)) {
		$vistaActual = $claveVista;
	}
}
$grupo1Actual = isset($_GET['grupo1']) ? (string) $_GET['grupo1'] : '';
$grupo2Actual = isset($_GET['grupo2']) ? (string) $_GET['grupo2'] : '';
$marcaActual = isset($_GET['marca']) ? (string) $_GET['marca'] : '';
$grupo3Actual = isset($_GET['grupo3']) ? (string) $_GET['grupo3'] : '';
?>
<link href="css/clientes-listado.css" rel="stylesheet">
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<?php include("includes/funciones-js.php"); ?>
<style>
	.importar-page .clientes-panel { overflow: visible; border-radius: 14px; }
	.importar-page .clientes-panel-header { border-radius: 13px 13px 0 0; align-items: flex-start; }
	.importar-page .clientes-panel-body { border-radius: 0 0 13px 13px; }
	.importar-page .productos-filtros-buscar {
		display: flex;
		flex: 1 1 720px;
		flex-wrap: wrap;
		justify-content: flex-end;
		align-items: flex-end;
		gap: 0.4rem;
		margin: 0 0 0 auto;
		min-width: min(100%, 720px);
		max-width: 100%;
	}
	.importar-page .productos-filtro {
		position: relative;
		flex: 1 1 108px;
		min-width: 96px;
		max-width: 158px;
	}
	.importar-page .productos-filtro label {
		display: block;
		color: rgba(255, 255, 255, 0.85);
		margin-bottom: 0.15rem;
		font-size: 0.6rem;
		font-weight: 600;
		letter-spacing: 0.02em;
		text-transform: uppercase;
	}
	.importar-page .productos-filtro select.js-filtro-buscar {
		position: absolute;
		opacity: 0;
		pointer-events: none;
		width: 1px;
		height: 1px;
	}
	.importar-page .productos-filtro-input {
		display: block;
		width: 100%;
		height: 33px;
		min-height: 33px;
		padding: 0.2rem 0.45rem;
		font-size: 0.75rem;
		border: 1px solid #cbd5e1;
		border-radius: 8px;
		color: #0f172a;
		background: #fff;
		box-sizing: border-box;
	}
	.importar-page .productos-filtro-input:focus {
		outline: none;
		border-color: #059669;
		box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.2);
	}
	.importar-page .productos-filtro-lista {
		display: none;
		position: absolute;
		z-index: 40;
		left: 0;
		right: 0;
		top: calc(100% + 2px);
		max-height: 220px;
		overflow: auto;
		margin: 0;
		padding: 0.25rem 0;
		list-style: none;
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 8px;
		box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
		color: #0f172a;
	}
	.importar-page .productos-filtro-lista.is-open { display: block; }
	.importar-page .productos-filtro-lista li { padding: 0.45rem 0.75rem; cursor: pointer; font-size: 0.875rem; }
	.importar-page .productos-filtro-lista li:hover,
	.importar-page .productos-filtro-lista li.is-elegida { background: #e2e8f0; }
	.importar-page .productos-filtro-lista li.is-vacio { color: #94a3b8; cursor: default; }
	.importar-acciones {
		display: flex;
		flex-wrap: wrap;
		gap: 0.5rem;
		margin-top: 1rem;
	}
	.importar-page .importar-acciones .btn {
		border-radius: 8px;
		font-weight: 600;
	}
	.importar-info {
		margin: 0 0 1.25rem;
		padding: 1rem 1.1rem;
		border-radius: 12px;
		border: 1px solid #fde68a;
		background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
		color: #78350f;
	}
	.importar-info h4 {
		margin: 0 0 0.55rem;
		font-size: 0.95rem;
		font-weight: 700;
		color: #92400e;
	}
	.importar-info ol {
		margin: 0;
		padding-left: 1.2rem;
	}
	.importar-info li { margin-bottom: 0.35rem; line-height: 1.4; }
	.importar-info a { color: #047857; font-weight: 700; }
	.importar-aviso {
		margin: 0 0 1.25rem;
		padding: 0.9rem 1rem;
		border-radius: 12px;
		border: 1px solid #bfdbfe;
		background: #eff6ff;
		color: #1e3a8a;
	}
	.importar-aviso h4 {
		margin: 0 0 0.35rem;
		font-size: 0.9rem;
		font-weight: 700;
	}
	.importar-aviso p { margin: 0; line-height: 1.4; }
	.importar-campo {
		margin-bottom: 1rem;
	}
	.importar-campo label {
		display: block;
		margin-bottom: 0.35rem;
		font-size: 0.75rem;
		font-weight: 700;
		color: #334155;
		text-transform: uppercase;
		letter-spacing: 0.03em;
	}
	.importar-campo .help {
		display: block;
		margin-top: 0.35rem;
		font-size: 0.75rem;
		color: #64748b;
	}
	.importar-campo input[type="number"] {
		width: 100%;
		max-width: 220px;
		height: 40px;
		padding: 0 0.75rem;
		border: 1px solid #cbd5e1;
		border-radius: 10px;
		box-sizing: border-box;
	}
	.file-upload-container { position: relative; margin-bottom: 0.35rem; }
	.file-upload-wrapper {
		border: 2px dashed #cbd5e1;
		border-radius: 14px;
		padding: 1.5rem 1.25rem;
		text-align: center;
		transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
		background: #f8fafc;
		cursor: pointer;
	}
	.file-upload-wrapper:hover {
		border-color: #059669;
		background: #ecfdf5;
		box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.12);
	}
	.file-upload-wrapper.dragover {
		border-color: #047857;
		background: #d1fae5;
		box-shadow: 0 0 0 3px rgba(4, 120, 87, 0.18);
	}
	.file-upload-wrapper.has-file {
		border-color: #059669;
		border-style: solid;
		background: #ecfdf5;
	}
	.file-upload-icon {
		font-size: 2.25rem;
		color: #059669;
		margin-bottom: 0.65rem;
	}
	.file-upload-text {
		font-size: 0.95rem;
		font-weight: 700;
		color: #0f172a;
		margin-bottom: 0.35rem;
	}
	.file-upload-subtext {
		font-size: 0.8125rem;
		color: #64748b;
		margin-bottom: 0.85rem;
	}
	.file-upload-input { display: none; }
	.file-upload-button {
		display: inline-flex;
		align-items: center;
		gap: 0.35rem;
		background: linear-gradient(135deg, #059669 0%, #047857 100%);
		color: #fff;
		border: none;
		padding: 0.55rem 1.1rem;
		border-radius: 999px;
		font-weight: 700;
		font-size: 0.8125rem;
		cursor: pointer;
		transition: transform 0.15s ease, box-shadow 0.15s ease;
	}
	.file-upload-button:hover {
		transform: translateY(-1px);
		box-shadow: 0 6px 16px rgba(5, 150, 105, 0.28);
	}
	.file-upload-preview {
		display: none;
		margin-top: 0.85rem;
		padding: 0.85rem 1rem;
		border-radius: 12px;
		border: 1px solid #a7f3d0;
		background: #fff;
		text-align: left;
	}
	.file-upload-preview.is-visible { display: block; }
	.file-upload-preview-title {
		margin: 0 0 0.35rem;
		font-size: 0.75rem;
		font-weight: 700;
		color: #047857;
		text-transform: uppercase;
		letter-spacing: 0.03em;
	}
	.file-upload-preview-name {
		margin: 0;
		font-size: 0.875rem;
		font-weight: 600;
		color: #0f172a;
		word-break: break-all;
	}
	.file-upload-preview-meta {
		margin: 0.25rem 0 0;
		font-size: 0.75rem;
		color: #64748b;
	}
	.file-upload-remove {
		margin-top: 0.65rem;
		display: inline-flex;
		align-items: center;
		gap: 0.3rem;
		padding: 0.35rem 0.7rem;
		border: 1px solid #fecaca;
		border-radius: 8px;
		background: #fff;
		color: #b91c1c;
		font-size: 0.75rem;
		font-weight: 600;
		cursor: pointer;
	}
	.file-upload-remove:hover { background: #fef2f2; }
	.export-conteo {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 0.5rem 0.85rem;
		margin: 0 0 0.9rem;
		padding: 0.75rem 0.95rem;
		border-radius: 12px;
		border: 1px solid #dbeafe;
		background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
		color: #334155;
		font-size: 0.875rem;
	}
	.export-conteo strong {
		font-size: 1.15rem;
		color: #047857;
		font-variant-numeric: tabular-nums;
	}
	.export-conteo.is-loading strong { color: #64748b; }
	.export-conteo-hint { color: #64748b; font-size: 0.8125rem; }
	.import-resultado {
		margin: 0.85rem 0 1.25rem;
		padding: 1rem 1.15rem;
		border-radius: 14px;
		border: 1px solid #a7f3d0;
		background: linear-gradient(135deg, #ecfdf5 0%, #f8fafc 100%);
	}
	.import-resultado h3 {
		margin: 0 0 0.75rem;
		font-size: 1rem;
		color: #065f46;
	}
	.import-resultado-tabs {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(132px, 1fr));
		gap: 0.55rem;
		margin-bottom: 0.7rem;
	}
	.import-resultado-tab {
		appearance: none;
		-webkit-appearance: none;
		text-align: left;
		font-family: inherit;
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 10px;
		padding: 0.65rem 0.75rem;
		cursor: pointer;
	}
	.import-resultado-tab span {
		display: block;
		font-size: 0.7rem;
		color: #64748b;
		text-transform: uppercase;
		letter-spacing: 0.03em;
	}
	.import-resultado-tab strong {
		font-size: 1.25rem;
		color: #0f172a;
		font-variant-numeric: tabular-nums;
	}
	.import-resultado-tab.is-ok strong { color: #047857; }
	.import-resultado-tab.is-warn strong { color: #b45309; }
	.import-resultado-tab.is-err strong { color: #b91c1c; }
	.import-resultado-tab.is-active {
		border-color: #059669;
		box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.18);
	}
	.import-resultado-tab.is-warn.is-active {
		border-color: #b45309;
		box-shadow: 0 0 0 2px rgba(180, 83, 9, 0.16);
	}
	.import-resultado-tab.is-err.is-active {
		border-color: #b91c1c;
		box-shadow: 0 0 0 2px rgba(185, 28, 28, 0.16);
	}
	.import-resultado-panel {
		display: none;
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 10px;
		padding: 0.35rem 0.85rem 0.55rem;
	}
	.import-resultado-panel.is-active { display: block; }
	.import-resultado-lista {
		margin: 0;
		padding: 0;
		list-style: none;
		max-height: 240px;
		overflow: auto;
	}
	.import-resultado-lista li {
		padding: 0.35rem 0;
		border-top: 1px solid #f1f5f9;
		font-size: 0.8125rem;
		color: #475569;
	}
	.import-resultado-lista li:first-child { border-top: 0; }
	.import-resultado-lista code {
		font-weight: 700;
		color: #0f172a;
	}
	.import-progreso {
		display: none;
		margin: 1rem 0 0;
		padding: 1rem 1.1rem;
		border-radius: 12px;
		border: 1px solid #bfdbfe;
		background: #eff6ff;
	}
	.import-progreso.is-visible { display: block; }
	.import-progreso-titulo {
		margin: 0 0 0.45rem;
		font-size: 0.9rem;
		font-weight: 700;
		color: #1e3a8a;
	}
	.import-progreso-meta {
		margin: 0 0 0.65rem;
		font-size: 0.8125rem;
		color: #475569;
	}
	.import-progreso-barra {
		height: 12px;
		border-radius: 999px;
		background: #e2e8f0;
		overflow: hidden;
	}
	.import-progreso-fill {
		height: 100%;
		width: 0%;
		border-radius: 999px;
		background: linear-gradient(90deg, #059669 0%, #047857 100%);
		transition: width 0.25s ease;
	}
	.import-progreso-conteos {
		margin-top: 0.65rem;
		font-size: 0.75rem;
		color: #64748b;
	}
	.import-progreso-timer {
		margin: 0 0 0.55rem;
		font-size: 1.05rem;
		font-weight: 700;
		font-variant-numeric: tabular-nums;
		color: #1d4ed8;
		letter-spacing: 0.02em;
	}
	.import-progreso-timer.is-done { color: #047857; }
</style>
</head>
<body class="clientes-page importar-page">
<div class="layout">
	<?php include("includes/encabezado.php"); ?>
	<div class="main-wrapper">
		<div class="container-fluid clientes-page-inner">
			<?php
			$migaTema = [
				['texto' => 'Productos', 'url' => 'productos.php'],
				['texto' => $paginaActual['pag_nombre'] ?? 'Importar productos'],
			];
			include 'includes/miga-tema.php';
			?>

			<?php include("includes/notificaciones.php"); ?>

			<?php
			$resultadoImport = null;
			if (!empty($_SESSION['import_productos_resultado']) && is_array($_SESSION['import_productos_resultado'])) {
				$resultadoImport = $_SESSION['import_productos_resultado'];
				unset($_SESSION['import_productos_resultado']);
			}
			$fmtListaImport = static function (array $items): string {
				if (!$items) {
					return '<li>Ninguno</li>';
				}
				$html = '';
				foreach ($items as $item) {
					$id = (int) ($item['id'] ?? 0);
					$ref = htmlspecialchars((string) ($item['referencia'] ?? ''), ENT_QUOTES, 'UTF-8');
					$nom = htmlspecialchars((string) ($item['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
					$extra = '';
					if (!empty($item['mensaje'])) {
						$extra = ' — <em>' . htmlspecialchars((string) $item['mensaje'], ENT_QUOTES, 'UTF-8') . '</em>';
					}
					if (!empty($item['operacion'])) {
						$extra = ' [' . htmlspecialchars((string) $item['operacion'], ENT_QUOTES, 'UTF-8') . ']' . $extra;
					}
					$prefijo = $id > 0 ? '<code>#' . $id . '</code> ' : '';
					$html .= '<li>' . $prefijo . $ref . ' — ' . $nom . $extra . '</li>';
				}
				return $html;
			};
			?>
			<?php if ($resultadoImport) {
				$gruposResultado = [
					['id' => 'creados', 'titulo' => 'Creados Orion', 'clase' => 'is-ok', 'items' => $resultadoImport['creados'] ?? []],
					['id' => 'actualizados', 'titulo' => 'Actualizados Orion', 'clase' => 'is-ok', 'items' => $resultadoImport['actualizados'] ?? []],
					['id' => 'sin_cambios', 'titulo' => 'Sin cambios', 'clase' => '', 'items' => $resultadoImport['sin_cambios'] ?? []],
					['id' => 'duplicados', 'titulo' => 'Duplicados', 'clase' => 'is-warn', 'items' => $resultadoImport['duplicados'] ?? []],
					['id' => 'ofima_creados', 'titulo' => 'Creados Ofima', 'clase' => 'is-ok', 'items' => $resultadoImport['ofima_creados'] ?? []],
					['id' => 'ofima_actualizados', 'titulo' => 'Actualizados Ofima', 'clase' => 'is-ok', 'items' => $resultadoImport['ofima_actualizados'] ?? []],
					['id' => 'ofima_omitidos', 'titulo' => 'Ofima omitidos (NO)', 'clase' => 'is-warn', 'items' => $resultadoImport['ofima_omitidos'] ?? []],
					['id' => 'ofima_errores', 'titulo' => 'Errores Ofima', 'clase' => 'is-err', 'items' => $resultadoImport['ofima_errores'] ?? []],
					['id' => 'invalidos', 'titulo' => 'Filas inválidas', 'clase' => 'is-err', 'items' => $resultadoImport['invalidos'] ?? []],
				];
				$tabActivo = $gruposResultado[0]['id'];
				foreach ($gruposResultado as $grupoResultado) {
					if (count($grupoResultado['items']) > 0) {
						$tabActivo = $grupoResultado['id'];
						break;
					}
				}
				$duracionSeg = max(0, (int) ($resultadoImport['duracion_segundos'] ?? 0));
				if (isset($_GET['duracion']) && preg_match('/^\d+$/', (string) $_GET['duracion'])) {
					$duracionSeg = (int) $_GET['duracion'];
				}
				$duracionTexto = sprintf(
					'%02d h %02d min %02d s',
					intdiv($duracionSeg, 3600),
					intdiv($duracionSeg % 3600, 60),
					$duracionSeg % 60
				);
				$nCreadosImp = count($resultadoImport['creados'] ?? []);
				$nActualizadosImp = count($resultadoImport['actualizados'] ?? []);
				$nSinCambiosImp = count($resultadoImport['sin_cambios'] ?? []);
				$nDuplicadosImp = count($resultadoImport['duplicados'] ?? []);
				$nOfimaCreadosImp = count($resultadoImport['ofima_creados'] ?? []);
				$nOfimaActualizadosImp = count($resultadoImport['ofima_actualizados'] ?? []);
				$nOfimaErroresImp = count($resultadoImport['ofima_errores'] ?? []);
				$nInvalidosImp = count($resultadoImport['invalidos'] ?? []);
				$totalHechosImp = (int) ($resultadoImport['filas_con_datos'] ?? ($nCreadosImp + $nActualizadosImp + $nSinCambiosImp + $nDuplicadosImp + $nInvalidosImp));
				$textoConteoImp = 'Orion: ' . $nCreadosImp . ' creados, ' . $nActualizadosImp . ' actualizados, ' . $nSinCambiosImp . ' sin cambios'
					. ($nDuplicadosImp ? (', ' . $nDuplicadosImp . ' duplicados') : '')
					. ' · Ofima: '
					. $nOfimaCreadosImp . ' creados, ' . $nOfimaActualizadosImp . ' actualizados'
					. ($nOfimaErroresImp ? (', ' . $nOfimaErroresImp . ' errores') : '')
					. ($nInvalidosImp ? (', ' . $nInvalidosImp . ' inválidos') : '');
			} ?>

			<section class="clientes-panel">
				<div class="clientes-panel-header">
					<div>
						<h3>Exportar productos</h3>
						<p>Use los mismos filtros del listado (por defecto: habilitados)</p>
					</div>
					<div class="productos-filtros-buscar">
						<div class="productos-filtro">
							<label for="filtroHabilitadoExport">Habilitado</label>
							<select id="filtroHabilitadoExport" class="js-filtro-buscar" name="habilitado" form="formExportarProductos">
								<option value="1"<?= $habilitadoActual === '1' ? ' selected' : ''; ?>>Habilitados</option>
								<option value="0"<?= $habilitadoActual === '0' ? ' selected' : ''; ?>>No habilitados</option>
							</select>
						</div>
						<?php if (Modulos::validarRol([401], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
						<div class="productos-filtro">
							<label for="filtroVistaExport">Filtro</label>
							<select id="filtroVistaExport" class="js-filtro-buscar" name="vista" form="formExportarProductos">
								<option value="">Todos</option>
								<option value="web"<?= $vistaActual === 'web' ? ' selected' : ''; ?>>Visible web</option>
								<option value="pdt"<?= $vistaActual === 'pdt' ? ' selected' : ''; ?>>Predeterminados</option>
								<option value="stock"<?= $vistaActual === 'stock' ? ' selected' : ''; ?>>Sin existencias</option>
								<option value="utilidad"<?= $vistaActual === 'utilidad' ? ' selected' : ''; ?>>Sin utilidad</option>
							</select>
						</div>
						<?php } ?>
						<div class="productos-filtro">
							<label for="filtroGrupo1Export"><?= $ofimaProductosActiva ? 'Línea' : 'Grupo 1'; ?></label>
							<select id="filtroGrupo1Export" class="js-filtro-buscar" name="grupo1" form="formExportarProductos">
								<option value="">Todos</option>
								<?php
								$grupos1 = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=1 AND catp_habilitada=1 AND catp_id_empresa='" . (int) $idEmpresa . "'");
								while ($grupo1 = mysqli_fetch_array($grupos1, MYSQLI_BOTH)) {
									$sel = $grupo1Actual === (string) $grupo1[0] ? ' selected' : '';
								?>
									<option value="<?= (int) $grupo1[0]; ?>"<?= $sel; ?>><?= htmlspecialchars($grupo1['catp_nombre']); ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="productos-filtro">
							<label for="filtroGrupo2Export"><?= $ofimaProductosActiva ? 'Sublínea' : 'Grupo 2'; ?></label>
							<select id="filtroGrupo2Export" class="js-filtro-buscar" name="grupo2" form="formExportarProductos">
								<option value="">Todos</option>
								<?php
								$grupos2 = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=2 AND catp_habilitada=1 AND catp_id_empresa='" . (int) $idEmpresa . "'");
								while ($grupo2 = mysqli_fetch_array($grupos2, MYSQLI_BOTH)) {
									$sel = $grupo2Actual === (string) $grupo2[0] ? ' selected' : '';
								?>
									<option value="<?= (int) $grupo2[0]; ?>"<?= $sel; ?>><?= htmlspecialchars($grupo2['catp_nombre']); ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="productos-filtro">
							<label for="filtroMarcaExport"><?= $ofimaProductosActiva ? 'Grupo' : 'Marca'; ?></label>
							<select id="filtroMarcaExport" class="js-filtro-buscar" name="marca" form="formExportarProductos">
								<option value="">Todas</option>
								<?php
								$marcas = $conexionBdPrincipal->query("SELECT * FROM marcas WHERE mar_id_empresa='" . (int) $idEmpresa . "' AND mar_habilitada=1");
								while ($marca = mysqli_fetch_array($marcas, MYSQLI_BOTH)) {
									$sel = $marcaActual === (string) $marca[0] ? ' selected' : '';
								?>
									<option value="<?= (int) $marca[0]; ?>"<?= $sel; ?>><?= htmlspecialchars($marca[1]); ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="productos-filtro">
							<label for="filtroGrupo3Export"><?= $ofimaProductosActiva ? 'Clasificación 1' : 'Grupo 3'; ?></label>
							<select id="filtroGrupo3Export" class="js-filtro-buscar" name="grupo3" form="formExportarProductos">
								<option value="">Todos</option>
								<?php
								$grupos3 = $conexionBdPrincipal->query("SELECT * FROM productos_categorias WHERE catp_grupo=3 AND catp_habilitada=1 AND catp_id_empresa='" . (int) $idEmpresa . "'");
								while ($grupo3 = mysqli_fetch_array($grupos3, MYSQLI_BOTH)) {
									$sel = $grupo3Actual === (string) $grupo3[0] ? ' selected' : '';
								?>
									<option value="<?= (int) $grupo3[0]; ?>"<?= $sel; ?>><?= htmlspecialchars($grupo3['catp_nombre']); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="clientes-panel-body">
					<form id="formExportarProductos" class="importar-export-form" method="get" action="productos-exportar.php">
						<div class="export-conteo is-loading" id="exportConteoBox" aria-live="polite">
							<span>Productos a exportar:</span>
							<strong id="exportConteoNumero">…</strong>
							<span class="export-conteo-hint" id="exportConteoHint">Calculando con los filtros actuales…</span>
						</div>
						<div class="importar-acciones">
							<button type="submit" id="btnExportar" class="btn btn-info"><i class="icon-download"></i> Exportar Excel</button>
						</div>
					</form>
				</div>
			</section>

			<section class="clientes-panel">
				<div class="clientes-panel-header is-neutral">
					<div>
						<h3><?= htmlspecialchars($paginaActual['pag_nombre'] ?? 'Importar productos'); ?></h3>
						<p>Suba la plantilla Excel con los productos a cargar o actualizar</p>
					</div>
				</div>
				<div class="clientes-panel-body">
					<div class="importar-info">
						<h4>Información importante</h4>
						<ol>
							<li>Descargue la plantilla Excel con los filtros de arriba.</li>
							<li>Complete la información y guárdela en formato <strong>Excel xlsx</strong>.</li>
							<li>Suba la plantilla: el sistema lee solo las filas con datos. Encabezados en <strong style="color:#b91c1c;">rojo</strong> son obligatorios (Código, Nombre, Precio lista, Integrar Ofima SI/NO, Habilitado SI/NO). Filas no habilitadas salen en gris.</li>
							<li>La clave es el <strong>Código</strong>: si no existe se crea. Si ya existe, solo se actualiza cuando cambia el nombre, la línea, la sublínea, el grupo, el precio de lista, Integrar Ofima o Habilitado. Si esos datos vienen iguales, el producto no se toca. Si el mismo código se repite en el archivo, solo se toma la primera fila; las demás quedan como duplicadas y no se actualizan.</li>
							<li>Si <em>Integrar Ofima</em> = SI y Ofima está activo, el producto se crea/actualiza también en Ofima. Si el producto ya está integrado en Ofima, la importación no cambia ese campo; el resto de datos sí se puede actualizar.</li>
						</ol>
					</div>

					<div class="importar-aviso">
						<h4>Existencias y precios</h4>
						<p>Las existencias ya no se toman desde este archivo (se manejan por bodegas). En productos existentes no se tocan costo ni utilidad. Solo se guardan nombre, línea, sublínea, grupo, precio de lista, Integrar Ofima y Habilitado cuando alguno de esos valores cambió. Si Ofima está activo y el producto sí cambió, también se envía allá.</p>
					</div>

					<form id="formImportar" method="post" action="productos-importar-excel.php" enctype="multipart/form-data">
						<div class="importar-campo">
							<label for="planillaExcel">Plantilla Excel</label>
							<div class="file-upload-container">
								<div class="file-upload-wrapper" id="planillaUploadWrapper" role="button" tabindex="0" aria-controls="planillaExcel">
									<div class="file-upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
									<div class="file-upload-text">Arrastre aquí la plantilla o selecciónela</div>
									<div class="file-upload-subtext">Solo Excel (.xlsx)</div>
									<button type="button" class="file-upload-button" id="btnSeleccionarPlanilla">
										<i class="fa-solid fa-plus"></i> Seleccionar archivo
									</button>
								</div>
								<input type="file" id="planillaExcel" name="planilla" class="file-upload-input" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
								<div class="file-upload-preview" id="planillaPreview" aria-live="polite">
									<p class="file-upload-preview-title"><i class="fa-solid fa-file-excel"></i> Archivo listo</p>
									<p class="file-upload-preview-name" id="planillaNombre"></p>
									<p class="file-upload-preview-meta" id="planillaMeta"></p>
									<button type="button" class="file-upload-remove" id="btnQuitarPlanilla"><i class="fa-solid fa-xmark"></i> Quitar archivo</button>
								</div>
							</div>
						</div>

						<div class="import-progreso<?= $resultadoImport ? ' is-visible' : ''; ?>" id="importProgreso" aria-live="polite">
							<p class="import-progreso-titulo" id="importProgresoTitulo"><?= $resultadoImport ? 'Importación finalizada' : 'Procesando importación…'; ?></p>
							<p class="import-progreso-timer<?= $resultadoImport ? ' is-done' : ''; ?>" id="importProgresoTimer">Tiempo: <?= $resultadoImport ? htmlspecialchars($duracionTexto, ENT_QUOTES, 'UTF-8') : '00 h 00 min 00 s'; ?></p>
							<p class="import-progreso-meta" id="importProgresoMeta"><?= $resultadoImport ? ('Completado: ' . (int) $totalHechosImp . ' / ' . (int) $totalHechosImp . ' productos') : 'Preparando archivo'; ?></p>
							<div class="import-progreso-barra"><div class="import-progreso-fill" id="importProgresoFill"<?= $resultadoImport ? ' style="width:100%"' : ''; ?>></div></div>
							<p class="import-progreso-conteos" id="importProgresoConteos"><?= $resultadoImport ? htmlspecialchars($textoConteoImp, ENT_QUOTES, 'UTF-8') : ''; ?></p>
						</div>

						<?php if ($resultadoImport) { ?>
						<section class="import-resultado" id="importResultado">
							<h3><i class="fa-solid fa-circle-check"></i> Resultado de la importación<?= !empty($resultadoImport['fecha']) ? ' · ' . htmlspecialchars($resultadoImport['fecha']) : ''; ?> · Duración: <?= htmlspecialchars($duracionTexto, ENT_QUOTES, 'UTF-8'); ?></h3>
							<div class="import-resultado-tabs" role="tablist">
								<?php foreach ($gruposResultado as $grupoResultado) {
									$activo = $grupoResultado['id'] === $tabActivo;
								?>
								<button type="button" class="import-resultado-tab <?= htmlspecialchars(trim($grupoResultado['clase'] . ($activo ? ' is-active' : '')), ENT_QUOTES, 'UTF-8'); ?>" role="tab" id="tab-<?= htmlspecialchars($grupoResultado['id'], ENT_QUOTES, 'UTF-8'); ?>" data-tab="<?= htmlspecialchars($grupoResultado['id'], ENT_QUOTES, 'UTF-8'); ?>" aria-selected="<?= $activo ? 'true' : 'false'; ?>" aria-controls="panel-<?= htmlspecialchars($grupoResultado['id'], ENT_QUOTES, 'UTF-8'); ?>">
									<span><?= htmlspecialchars($grupoResultado['titulo'], ENT_QUOTES, 'UTF-8'); ?></span>
									<strong><?= count($grupoResultado['items']); ?></strong>
								</button>
								<?php } ?>
							</div>
							<?php foreach ($gruposResultado as $grupoResultado) {
								$activo = $grupoResultado['id'] === $tabActivo;
							?>
							<div class="import-resultado-panel<?= $activo ? ' is-active' : ''; ?>" role="tabpanel" id="panel-<?= htmlspecialchars($grupoResultado['id'], ENT_QUOTES, 'UTF-8'); ?>" data-panel="<?= htmlspecialchars($grupoResultado['id'], ENT_QUOTES, 'UTF-8'); ?>"<?= $activo ? '' : ' hidden'; ?>>
								<ul class="import-resultado-lista"><?= $fmtListaImport($grupoResultado['items']); ?></ul>
							</div>
							<?php } ?>
						</section>
						<?php } ?>

						<div class="importar-acciones">
							<a href="productos.php" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
							<button id="btnImportar" type="submit" class="btn btn-info"><i class="icon-upload"></i> Iniciar importación</button>
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
	<?php include("includes/pie.php"); ?>
</div>
<script>
(function () {
	var formExport = document.getElementById('formExportarProductos');
	var conteoBox = document.getElementById('exportConteoBox');
	var conteoNumero = document.getElementById('exportConteoNumero');
	var conteoHint = document.getElementById('exportConteoHint');
	var conteoTimer = null;
	var conteoRequest = 0;

	function paramsFiltrosExport() {
		var params = new URLSearchParams();
		document.querySelectorAll('select.js-filtro-buscar[form="formExportarProductos"]').forEach(function (sel) {
			if (!sel.name) return;
			params.set(sel.name, sel.value == null ? '' : String(sel.value));
		});
		if (!params.has('habilitado')) params.set('habilitado', '1');
		return params;
	}

	function actualizarConteoExport() {
		if (!conteoNumero) return;
		var id = ++conteoRequest;
		if (conteoBox) conteoBox.classList.add('is-loading');
		conteoNumero.textContent = '…';
		if (conteoHint) conteoHint.textContent = 'Calculando con los filtros actuales…';
		fetch('ajax/ajax-productos-export-conteo.php?' + paramsFiltrosExport().toString(), { credentials: 'same-origin' })
			.then(function (r) { return r.json(); })
			.then(function (data) {
				if (id !== conteoRequest) return;
				if (conteoBox) conteoBox.classList.remove('is-loading');
				if (!data || !data.success) {
					conteoNumero.textContent = '—';
					if (conteoHint) conteoHint.textContent = 'No se pudo calcular el conteo.';
					return;
				}
				var total = Number(data.total) || 0;
				conteoNumero.textContent = total.toLocaleString('es-CO');
				if (conteoHint) {
					conteoHint.textContent = total === 1
						? 'producto coincide con los filtros'
						: 'productos coinciden con los filtros';
				}
			})
			.catch(function () {
				if (id !== conteoRequest) return;
				if (conteoBox) conteoBox.classList.remove('is-loading');
				conteoNumero.textContent = '—';
				if (conteoHint) conteoHint.textContent = 'Error de red al calcular el conteo.';
			});
	}

	function programarConteoExport() {
		clearTimeout(conteoTimer);
		conteoTimer = setTimeout(actualizarConteoExport, 220);
	}

	if (formExport) {
		formExport.addEventListener('submit', function () {
			var btn = document.getElementById('btnExportar');
			if (!btn) return;
			btn.disabled = true;
			btn.innerHTML = '<i class="icon-refresh"></i> Exportando...';
			setTimeout(function () {
				btn.disabled = false;
				btn.innerHTML = '<i class="icon-download"></i> Exportar Excel';
			}, 3000);
		});
	}

	function pad2(n) {
		n = Math.max(0, parseInt(n, 10) || 0);
		return n < 10 ? '0' + n : String(n);
	}

	function formatearHms(segundos) {
		segundos = Math.max(0, parseInt(segundos, 10) || 0);
		var h = Math.floor(segundos / 3600);
		var m = Math.floor((segundos % 3600) / 60);
		var s = segundos % 60;
		return pad2(h) + ' h ' + pad2(m) + ' min ' + pad2(s) + ' s';
	}

	function formatearEta(segundos) {
		segundos = Math.max(0, parseInt(segundos, 10) || 0);
		if (segundos < 60) return segundos + ' s';
		var m = Math.floor(segundos / 60);
		var s = segundos % 60;
		return m + ' min ' + s + ' s';
	}

	var formImportar = document.getElementById('formImportar');
	var cajaProgreso = document.getElementById('importProgreso');
	var progTitulo = document.getElementById('importProgresoTitulo');
	var progMeta = document.getElementById('importProgresoMeta');
	var progFill = document.getElementById('importProgresoFill');
	var progConteos = document.getElementById('importProgresoConteos');
	var progTimer = document.getElementById('importProgresoTimer');
	var importando = false;
	var timerInicio = 0;
	var timerInterval = null;
	var timerSegundosFinal = 0;

	function pintarTimer(segundos) {
		if (!progTimer) return;
		progTimer.textContent = 'Tiempo: ' + formatearHms(segundos);
	}

	function iniciarContadorTiempo() {
		detenerContadorTiempo();
		timerInicio = Date.now();
		timerSegundosFinal = 0;
		if (progTimer) progTimer.classList.remove('is-done');
		pintarTimer(0);
		timerInterval = setInterval(function () {
			var seg = Math.floor((Date.now() - timerInicio) / 1000);
			pintarTimer(seg);
		}, 250);
	}

	function detenerContadorTiempo() {
		if (timerInterval) {
			clearInterval(timerInterval);
			timerInterval = null;
		}
		if (timerInicio) {
			timerSegundosFinal = Math.floor((Date.now() - timerInicio) / 1000);
			pintarTimer(timerSegundosFinal);
		}
		if (progTimer) progTimer.classList.add('is-done');
	}

	function mostrarProgreso(visible) {
		if (!cajaProgreso) return;
		cajaProgreso.classList.toggle('is-visible', !!visible);
	}

	function actualizarBarra(data) {
		var total = Number(data.total) || 0;
		var hechos = Number(data.procesados) || 0;
		var pct = Number(data.porcentaje);
		if (isNaN(pct)) pct = total > 0 ? Math.floor((hechos / total) * 100) : 0;
		if (progFill) progFill.style.width = Math.min(100, pct) + '%';
		if (progTitulo) progTitulo.textContent = data.done ? 'Importación finalizada' : 'Importando en segundo plano…';
		if (progMeta) {
			progMeta.textContent = data.done
				? ('Completado: ' + hechos + ' / ' + total + ' productos')
				: ('Procesados ' + hechos + ' de ' + total + ' · ETA ~ ' + formatearEta(data.eta_segundos));
		}
		if (progConteos && data.conteos) {
			var c = data.conteos;
			progConteos.textContent = 'Orion: ' + (c.creados || 0) + ' creados, ' + (c.actualizados || 0) + ' actualizados, ' + (c.sin_cambios || 0) + ' sin cambios'
				+ ((c.duplicados || 0) ? (', ' + c.duplicados + ' duplicados') : '')
				+ ' · Ofima: '
				+ (c.ofima_creados || 0) + ' creados, ' + (c.ofima_actualizados || 0) + ' actualizados'
				+ ((c.ofima_errores || 0) ? (', ' + c.ofima_errores + ' errores') : '')
				+ ((c.invalidos || 0) ? (', ' + c.invalidos + ' inválidos') : '');
		}
	}

	function procesarLote(jobId) {
		var body = new URLSearchParams();
		body.set('job_id', jobId);
		body.set('lote', '5');
		return fetch('ajax/ajax-productos-importar-lote.php', {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			body: body.toString()
		}).then(function (r) { return r.json(); });
	}

	function correrLotes(jobId) {
		return procesarLote(jobId).then(function (data) {
			if (!data || !data.success) {
				throw new Error((data && data.message) ? data.message : 'Error al procesar el lote');
			}
			actualizarBarra(data);
			if (data.done) {
				return data;
			}
			return correrLotes(jobId);
		});
	}

	if (formImportar) {
		formImportar.addEventListener('submit', function (e) {
			e.preventDefault();
			if (importando) return;
			var input = document.getElementById('planillaExcel');
			var btn = document.getElementById('btnImportar');
			if (!input || !input.files || !input.files.length) {
				alert('Seleccione la plantilla Excel (.xlsx) antes de importar.');
				return;
			}
			importando = true;
			if (btn) {
				btn.disabled = true;
				btn.innerHTML = '<i class="icon-refresh"></i> Importando...';
			}
			mostrarProgreso(true);
			iniciarContadorTiempo();
			if (progTitulo) progTitulo.textContent = 'Subiendo y preparando Excel…';
			if (progMeta) progMeta.textContent = 'Esto puede tomar unos segundos según el tamaño del archivo';
			if (progFill) progFill.style.width = '2%';
			if (progConteos) progConteos.textContent = '';

			var fd = new FormData();
			fd.append('planilla', input.files[0]);

			fetch('ajax/ajax-productos-importar-iniciar.php', {
				method: 'POST',
				credentials: 'same-origin',
				body: fd
			})
				.then(function (r) { return r.json(); })
				.then(function (data) {
					if (!data || !data.success || !data.job_id) {
						throw new Error((data && data.message) ? data.message : 'No se pudo iniciar la importación');
					}
					actualizarBarra({ total: data.total || 0, procesados: 0, porcentaje: 1, eta_segundos: 0, conteos: {} });
					return correrLotes(data.job_id);
				})
				.then(function () {
					detenerContadorTiempo();
					if (progTitulo) progTitulo.textContent = 'Importación finalizada';
					window.location.href = 'productos-importar.php?import=ok&duracion=' + timerSegundosFinal;
				})
				.catch(function (err) {
					importando = false;
					detenerContadorTiempo();
					if (btn) {
						btn.disabled = false;
						btn.innerHTML = '<i class="icon-upload"></i> Iniciar importación';
					}
					if (progTitulo) progTitulo.textContent = 'Error en la importación';
					if (progMeta) progMeta.textContent = (err && err.message) ? err.message : 'Error desconocido';
					alert((err && err.message) ? err.message : 'Error al importar');
				});
		});
	}

	(function initUploadPlanilla() {
		var input = document.getElementById('planillaExcel');
		var wrapper = document.getElementById('planillaUploadWrapper');
		var btnSel = document.getElementById('btnSeleccionarPlanilla');
		var preview = document.getElementById('planillaPreview');
		var nombre = document.getElementById('planillaNombre');
		var meta = document.getElementById('planillaMeta');
		var btnQuitar = document.getElementById('btnQuitarPlanilla');
		if (!input || !wrapper) return;

		function esXlsx(file) {
			if (!file) return false;
			var n = (file.name || '').toLowerCase();
			return n.slice(-5) === '.xlsx' || file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		}
		function formatearTamano(bytes) {
			if (bytes < 1024) return bytes + ' B';
			if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
			return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
		}
		function limpiar() {
			input.value = '';
			wrapper.classList.remove('has-file');
			preview.classList.remove('is-visible');
			nombre.textContent = '';
			meta.textContent = '';
		}
		function mostrarArchivo(file) {
			if (!esXlsx(file)) {
				alert('Solo se admite Excel (.xlsx).');
				limpiar();
				return;
			}
			nombre.textContent = file.name;
			meta.textContent = 'Tamaño: ' + formatearTamano(file.size);
			preview.classList.add('is-visible');
			wrapper.classList.add('has-file');
		}
		function abrirSelector() {
			input.click();
		}

		wrapper.addEventListener('click', function (e) {
			if (e.target.closest('#btnQuitarPlanilla')) return;
			abrirSelector();
		});
		wrapper.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				abrirSelector();
			}
		});
		if (btnSel) {
			btnSel.addEventListener('click', function (e) {
				e.stopPropagation();
				abrirSelector();
			});
		}
		input.addEventListener('change', function () {
			if (input.files && input.files[0]) mostrarArchivo(input.files[0]);
			else limpiar();
		});
		wrapper.addEventListener('dragover', function (e) {
			e.preventDefault();
			wrapper.classList.add('dragover');
		});
		wrapper.addEventListener('dragleave', function (e) {
			e.preventDefault();
			wrapper.classList.remove('dragover');
		});
		wrapper.addEventListener('drop', function (e) {
			e.preventDefault();
			wrapper.classList.remove('dragover');
			var files = e.dataTransfer && e.dataTransfer.files;
			if (!files || !files.length) return;
			try {
				input.files = files;
			} catch (err) {
				// Algunos navegadores no permiten asignar FileList; se usa DataTransfer.
				var dt = new DataTransfer();
				dt.items.add(files[0]);
				input.files = dt.files;
			}
			mostrarArchivo(input.files[0]);
		});
		if (btnQuitar) {
			btnQuitar.addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				limpiar();
			});
		}
	})();

	document.querySelectorAll('select.js-filtro-buscar').forEach(function (select) {
		var wrap = select.parentNode;
		var input = document.createElement('input');
		input.type = 'text';
		input.className = 'productos-filtro-input';
		input.placeholder = 'Buscar...';
		input.autocomplete = 'off';
		input.setAttribute('aria-label', select.id);
		var lista = document.createElement('ul');
		lista.className = 'productos-filtro-lista';
		wrap.appendChild(input);
		wrap.appendChild(lista);

		function textoElegido() {
			var elegida = Array.prototype.filter.call(select.options, function (o) { return o.value === select.value; })[0];
			return elegida ? elegida.text : '';
		}
		function pintar(filtro) {
			var q = (filtro || '').toLowerCase();
			lista.innerHTML = '';
			Array.prototype.forEach.call(select.options, function (o) {
				if (q && o.text.toLowerCase().indexOf(q) === -1) return;
				var li = document.createElement('li');
				li.textContent = o.text;
				if (o.value === select.value) li.className = 'is-elegida';
				li.addEventListener('mousedown', function (e) {
					e.preventDefault();
					select.value = o.value;
					input.value = o.text;
					lista.classList.remove('is-open');
					select.dispatchEvent(new Event('change', { bubbles: true }));
				});
				lista.appendChild(li);
			});
			if (!lista.children.length) {
				var vacio = document.createElement('li');
				vacio.className = 'is-vacio';
				vacio.textContent = 'Sin resultados';
				lista.appendChild(vacio);
			}
		}
		input.addEventListener('focus', function () {
			pintar('');
			lista.classList.add('is-open');
		});
		input.addEventListener('input', function () {
			pintar(input.value);
			lista.classList.add('is-open');
		});
		input.addEventListener('keydown', function (e) {
			if (e.key !== 'Enter') return;
			e.preventDefault();
			var primera = lista.querySelector('li:not(.is-vacio)');
			if (primera) primera.dispatchEvent(new MouseEvent('mousedown', { bubbles: true }));
		});
		input.addEventListener('blur', function () {
			setTimeout(function () { lista.classList.remove('is-open'); }, 150);
			input.value = textoElegido();
		});
		select.addEventListener('change', programarConteoExport);
		input.value = textoElegido();
	});

	actualizarConteoExport();

	var cajaResultado = document.getElementById('importResultado');
	if (cajaResultado) {
		cajaResultado.querySelectorAll('.import-resultado-tab').forEach(function (boton) {
			boton.addEventListener('click', function () {
				var id = boton.getAttribute('data-tab');
				cajaResultado.querySelectorAll('.import-resultado-tab').forEach(function (otro) {
					var activo = otro === boton;
					otro.classList.toggle('is-active', activo);
					otro.setAttribute('aria-selected', activo ? 'true' : 'false');
				});
				cajaResultado.querySelectorAll('.import-resultado-panel').forEach(function (panel) {
					var activo = panel.getAttribute('data-panel') === id;
					panel.classList.toggle('is-active', activo);
					if (activo) panel.removeAttribute('hidden');
					else panel.setAttribute('hidden', '');
				});
			});
		});
		cajaResultado.scrollIntoView({ behavior: 'smooth', block: 'start' });
	}
})();
</script>
</body>
</html>
