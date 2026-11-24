<?php
include("sesion.php");
$idPagina = 172;

include("includes/verificar-paginas.php");
include("includes/head.php");

require_once RUTA_PROYECTO.'/usuarios/class/Combo.php';

$idEmpresa = (int) ($_SESSION["dataAdicional"]["id_empresa"] ?? 0);
$busquedaCombo = trim((string) ($_GET['busqueda'] ?? ''));
$estadoFiltro = isset($_GET['estado']) && in_array((string) $_GET['estado'], ['0', '1'], true) ? (string) $_GET['estado'] : '';
$tamanosPaginaCombo = [10, 25, 50, 100, 200];
$porPaginaCombo = (isset($_GET['por']) && in_array((int) $_GET['por'], $tamanosPaginaCombo, true)) ? (int) $_GET['por'] : 10;
$puedeEditarCombo = Modulos::validarRol([175], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$verDescuentos = Modulos::validarRol([390], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);

$whereCombo = "combo_id_empresa = '" . $idEmpresa . "'";
if ($busquedaCombo !== '') {
	$whereCombo .= " AND combo_nombre LIKE '%" . $conexionBdPrincipal->real_escape_string($busquedaCombo) . "%'";
}
if ($estadoFiltro !== '') {
	$whereCombo .= " AND combo_estado = '" . $estadoFiltro . "'";
}

$conteoCombos = $conexionBdPrincipal->query("SELECT COUNT(*) FROM combos WHERE " . $whereCombo);
$filaConteoCombos = $conteoCombos ? mysqli_fetch_row($conteoCombos) : [0];
$numTotalCombos = (int) ($filaConteoCombos[0] ?? 0);
$paginasCombos = max(1, (int) ceil($numTotalCombos / $porPaginaCombo));
$paginaListaCombos = isset($_GET['inicio']) && is_numeric($_GET['inicio']) ? max(1, (int) $_GET['inicio']) : 1;
if ($paginaListaCombos > $paginasCombos) {
	$paginaListaCombos = $paginasCombos;
}
$offsetCombos = ($paginaListaCombos - 1) * $porPaginaCombo;
$desdeCombos = $numTotalCombos === 0 ? 0 : $offsetCombos + 1;
$hastaCombos = min($offsetCombos + $porPaginaCombo, $numTotalCombos);
$paramsPaginaCombo = $_GET;
unset($paramsPaginaCombo['inicio']);
$urlPaginaCombo = static function (int $pagina, int $por) use ($paramsPaginaCombo): string {
	$params = $paramsPaginaCombo;
	$params['por'] = $por;
	if ($pagina > 1) {
		$params['inicio'] = $pagina;
	}
	return 'combos.php?' . http_build_query($params);
};

$consulta = $conexionBdPrincipal->query(
	"SELECT * FROM combos WHERE " . $whereCombo . " ORDER BY combo_nombre LIMIT " . $offsetCombos . ", " . $porPaginaCombo
);
$columnasCombo = $verDescuentos ? 11 : 9;
?>
<link href="css/clientes-listado.css" rel="stylesheet">
<style>
	.clientes-page .clientes-panel { overflow: visible; border-radius: 14px; }
	.clientes-page .clientes-panel-header { border-radius: 13px 13px 0 0; }
	.clientes-page .clientes-panel-body { border-radius: 0 0 13px 13px; }
	.clientes-page .clientes-busqueda-principal { display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; margin: 0 0 0.75rem; }
	.clientes-page .clientes-busqueda-principal input[type="search"],
	.clientes-page .clientes-busqueda-principal select {
		height: 36px; min-height: 36px; border: 1px solid #cbd5e1; border-radius: 8px; padding: 0 0.7rem; margin: 0; font-size: 0.875rem; line-height: 36px;
	}
	.clientes-page .clientes-busqueda-principal input[type="search"] { flex: 1 1 220px; width: auto; min-width: 0; }
	.clientes-page .clientes-busqueda-principal select { flex: 0 0 auto; }
	.clientes-page .clientes-busqueda-btn {
		position: static; top: auto; right: auto; transform: none; flex: 0 0 auto;
		height: 36px; line-height: 36px; border: 0; border-radius: 8px; padding: 0 0.9rem; background: #047857; color: #fff; font-weight: 700;
	}
	.clientes-page .clientes-leyenda { margin: 0 0 0.65rem; text-align: left; }
	.clientes-page .clientes-table-wrap { max-height: calc(100vh - 280px); overflow: auto; }
	.clientes-page .clientes-table { font-size: 0.75rem; }
	.clientes-page .clientes-table thead th { position: sticky; top: 0; z-index: 2; text-align: center; background: #f8fafc; }
	.clientes-page .clientes-table td { text-align: center; vertical-align: middle; }
	.clientes-page .clientes-table td.col-nombre { text-align: left; font-weight: 700; }
	.clientes-page .combo-thumb { width: 48px; height: 48px; object-fit: contain; border-radius: 8px; background: #f8fafc; border: 1px solid #e2e8f0; }
	.clientes-page .combo-estado {
		border: 0; border-radius: 999px; padding: 0.28rem 0.7rem; font-size: 0.68rem; font-weight: 700; cursor: pointer;
	}
	.clientes-page .combo-estado.is-activo { background: #d1fae5; color: #065f46; }
	.clientes-page .combo-estado.is-inactivo { background: #fee2e2; color: #991b1b; }
	.clientes-page .combo-estado:disabled { cursor: default; }
	.clientes-page tr.combo-inactivo td { background: #fff7f7; }
	.clientes-page .col-acciones h4 { display: flex; justify-content: center; gap: 0.45rem; margin: 0; }
	.clientes-page .col-acciones a { color: #047857; }
	.clientes-page .clientes-table-wrap + .productos-paginacion { margin-top: 0.75rem; }
	.productos-paginacion {
		display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.75rem;
		padding: 0.5rem 0.75rem; font-size: 0.8125rem; color: #475569; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
	}
	.productos-paginacion-grupo, .productos-paginacion form { display: flex; align-items: center; gap: 0.4rem; margin: 0; }
	.productos-paginacion select, .productos-paginacion input[type="number"], .productos-paginacion button, .productos-paginacion a, .productos-paginacion .es-actual {
		height: 32px; min-width: 32px; padding: 0 0.55rem; border-radius: 8px; box-sizing: border-box; display: inline-flex; align-items: center; justify-content: center;
	}
	.productos-paginacion select, .productos-paginacion input[type="number"] { border: 1px solid #cbd5e1; background: #fff; color: #0f172a; }
	.productos-paginacion input[type="number"] { width: 4.2rem; text-align: center; }
	.productos-paginacion a { border: 1px solid #e2e8f0; background: #fff; color: #334155; text-decoration: none; }
	.productos-paginacion .es-actual { background: #1d4ed8; border: 1px solid #1d4ed8; color: #fff; }
	.productos-paginacion button { border: 0; background: #1d4ed8; color: #fff; font-weight: 700; cursor: pointer; }
</style>
</head>
<body class="clientes-page">
<div class="layout">
	<?php include("includes/encabezado.php");?>
	<div class="main-wrapper">
		<div class="container-fluid clientes-page-inner">
			<div class="clientes-hero">
				<div>
					<h1 class="clientes-hero-title"><?= htmlspecialchars($paginaActual['pag_nombre'] ?? 'Combos'); ?></h1>
					<p class="clientes-hero-subtitle">Paquetes, precios y estado</p>
				</div>
				<div class="clientes-hero-actions">
					<?php if (Modulos::validarRol([174], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
						<a href="combos-agregar.php" class="btn btn-success"><i class="icon-plus"></i> Agregar nuevo</a>
					<?php } ?>
				</div>
			</div>

			<?php include("includes/notificaciones.php");?>

			<section class="clientes-panel">
				<div class="clientes-panel-header">
					<div>
						<h3>Listado de combos</h3>
						<p>Busque por nombre y cambie el estado desde la tabla</p>
					</div>
				</div>
				<div class="clientes-panel-body">
					<form class="clientes-busqueda-principal" method="get" action="combos.php">
						<input type="search" name="busqueda" value="<?= htmlspecialchars($busquedaCombo, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nombre del combo" autocomplete="off">
						<select name="estado" aria-label="Estado">
							<option value=""<?= $estadoFiltro === '' ? ' selected' : ''; ?>>Todos</option>
							<option value="1"<?= $estadoFiltro === '1' ? ' selected' : ''; ?>>Activos</option>
							<option value="0"<?= $estadoFiltro === '0' ? ' selected' : ''; ?>>Inactivos</option>
						</select>
						<input type="hidden" name="por" value="<?= (int) $porPaginaCombo; ?>">
						<button type="submit" class="clientes-busqueda-btn">Buscar</button>
					</form>
					<p class="clientes-leyenda">Los combos incluidos en cotizaciones, pedidos, remisiones o facturas no se pueden eliminar. Pulse el estado para activarlo o desactivarlo.</p>
					<div class="clientes-table-wrap">
						<table class="clientes-table" id="data-table">
							<thead>
							<tr>
								<th>No</th>
								<th>Imagen</th>
								<th>Nombre</th>
								<th>Precio</th>
								<?php if ($verDescuentos) { ?>
								<th>Dcto.</th>
								<th>Dcto. dealer</th>
								<?php } ?>
								<th>Dcto. máx.</th>
								<th>Precio final</th>
								<th>Precio final dealer</th>
								<th>Estado</th>
								<th></th>
							</tr>
							</thead>
							<tbody>
							<?php
							$no = $offsetCombos + 1;
							if ($consulta && $consulta->num_rows > 0) {
							while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
								$datosCombos = $conexionBdPrincipal->query("SELECT ROUND((SUM(copp_cantidad)*prod_precio),0) FROM combos
								INNER JOIN combos_productos ON copp_combo=combo_id
								INNER JOIN productos ON prod_id=copp_producto
								WHERE combo_id='".$res['combo_id']."'
								GROUP BY copp_producto");
								$precioCombo = 0;
								while ($datosCombos && ($dCombos = mysqli_fetch_array($datosCombos, MYSQLI_BOTH))) {
									$precioCombo += $dCombos[0];
								}
								$dcto = ((float) $res['combo_descuento']) / 100;
								$precioFinal = round($precioCombo - ($precioCombo * $dcto), 0);
								$dctoDealer = ((float) $res['combo_descuento_dealer']) / 100;
								$precioFinalDealer = round($precioCombo - ($precioCombo * $dctoDealer), 0);
								$numeroCotizaciones = Combo::numeroCotizacionesCombo((int) $res['combo_id'], $conexionBdPrincipal);
								$activo = (int) $res['combo_estado'] === 1;
							?>
							<tr class="<?= $activo ? 'combo-activo' : 'combo-inactivo'; ?>">
								<td><?= $no; ?></td>
								<td>
									<?php if ($res['combo_imagen'] != '') { ?>
										<img class="combo-thumb" src="files/combos/<?= htmlspecialchars($res['combo_imagen']); ?>" alt="">
									<?php } ?>
								</td>
								<td class="col-nombre"><?= htmlspecialchars($res['combo_nombre']); ?></td>
								<td>$<?= number_format($precioCombo, 0, ",", "."); ?></td>
								<?php if ($verDescuentos) { ?>
									<td><?= htmlspecialchars((string) $res['combo_descuento']); ?>%</td>
									<td><?= htmlspecialchars((string) $res['combo_descuento_dealer']); ?>%</td>
								<?php } ?>
								<td><?= htmlspecialchars((string) $res['combo_descuento_maximo']); ?>%</td>
								<td>$<?= number_format($precioFinal, 0, ",", "."); ?></td>
								<td>$<?= number_format($precioFinalDealer, 0, ",", "."); ?></td>
								<td>
									<?php if ($puedeEditarCombo) { ?>
										<button type="button" class="combo-estado js-combo-estado <?= $activo ? 'is-activo' : 'is-inactivo'; ?>" data-id="<?= (int) $res['combo_id']; ?>" data-estado="<?= $activo ? '1' : '0'; ?>"><?= $activo ? 'Activo' : 'Inactivo'; ?></button>
									<?php } else { ?>
										<span class="combo-estado <?= $activo ? 'is-activo' : 'is-inactivo'; ?>"><?= $activo ? 'Activo' : 'Inactivo'; ?></span>
									<?php } ?>
								</td>
								<td class="col-acciones"><h4>
									<?php if (Modulos::validarRol([173], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
										<a href="combos-ver.php?id=<?= (int) $res['combo_id']; ?>" data-toggle="tooltip" title="Ver"><i class="icon-eye-open"></i></a>
									<?php } ?>
									<?php if ($puedeEditarCombo) {?>
										<a href="combos-editar.php?id=<?= (int) $res['combo_id']; ?>" data-toggle="tooltip" title="Editar"><i class="icon-edit"></i></a>
									<?php } ?>
									<?php if (Modulos::validarRol([218], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && $numeroCotizaciones == 0) {?>
										<a href="bd_delete/combos-eliminar.php?id=<?= (int) $res['combo_id']; ?>" onClick="if(!confirm('Desea eliminar el registro?')){return false;}" data-toggle="tooltip" title="Eliminar"><i class="icon-remove-sign"></i></a>
									<?php } ?>
								</h4></td>
							</tr>
							<?php $no++; }
							} else { ?>
							<tr><td colspan="<?= (int) $columnasCombo; ?>">No hay combos para esta búsqueda.</td></tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="productos-paginacion" id="combosPaginacion">
						<div class="productos-paginacion-grupo">
							<span><?= (int) $desdeCombos; ?>–<?= (int) $hastaCombos; ?> de <?= (int) $numTotalCombos; ?></span>
							<label for="combosPorPagina">Ver</label>
							<select id="combosPorPagina">
								<?php foreach ($tamanosPaginaCombo as $tamano) { ?>
									<option value="<?= (int) $tamano; ?>"<?= $tamano === $porPaginaCombo ? ' selected' : ''; ?>><?= (int) $tamano; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="productos-paginacion-grupo">
							<?php if ($paginaListaCombos > 1) { ?>
								<a href="<?= htmlspecialchars($urlPaginaCombo($paginaListaCombos - 1, $porPaginaCombo)); ?>">Anterior</a>
							<?php } ?>
							<?php for ($i = 1; $i <= $paginasCombos; $i++) {
								$mostrarNumero = $i === 1 || $i === $paginasCombos || ($i >= $paginaListaCombos - 2 && $i <= $paginaListaCombos + 2);
								$mostrarPuntos = ($i === 2 && $paginaListaCombos > 4) || ($i === $paginasCombos - 1 && $paginaListaCombos < $paginasCombos - 3);
								if ($mostrarNumero && $i === $paginaListaCombos) { ?>
									<span class="es-actual"><?= $i; ?></span>
								<?php } elseif ($mostrarNumero) { ?>
									<a href="<?= htmlspecialchars($urlPaginaCombo($i, $porPaginaCombo)); ?>"><?= $i; ?></a>
								<?php } elseif ($mostrarPuntos) { ?>
									<span>…</span>
								<?php }
							} ?>
							<?php if ($paginaListaCombos < $paginasCombos) { ?>
								<a href="<?= htmlspecialchars($urlPaginaCombo($paginaListaCombos + 1, $porPaginaCombo)); ?>">Siguiente</a>
							<?php } ?>
						</div>
						<form class="productos-paginacion-grupo" id="formIrPaginaCombo">
							<label for="combosIrPagina">Ir a</label>
							<input type="number" id="combosIrPagina" min="1" max="<?= (int) $paginasCombos; ?>" value="<?= (int) $paginaListaCombos; ?>">
							<button type="submit">Ir</button>
						</form>
					</div>
				</div>
			</section>
		</div>
	</div>
	<?php include("includes/pie.php");?>
</div>
<script>
(function () {
	function avisoOffsetY() {
		var nav = document.querySelector('.navbar .navbar-inner') || document.querySelector('.navbar-inner') || document.querySelector('.navbar');
		if (!nav) return 96;
		return Math.max(88, Math.round(nav.getBoundingClientRect().bottom + 12));
	}
	function aviso(texto, tipo) {
		var colores = { success: '#047857', error: '#b91c1c', warning: '#b45309', info: '#334155' };
		var color = colores[tipo] || colores.info;
		if (typeof Toastify === 'function') {
			Toastify({
				text: String(texto || ''),
				duration: 3500,
				gravity: 'top',
				position: 'right',
				close: true,
				stopOnFocus: true,
				backgroundColor: color,
				offset: { x: 16, y: avisoOffsetY() },
				style: {
					background: color,
					color: '#fff',
					borderRadius: '10px',
					boxShadow: '0 12px 30px rgba(15, 23, 42, 0.25)',
					fontFamily: "'Plus Jakarta Sans', -apple-system, sans-serif",
					fontSize: '0.875rem',
					fontWeight: '600',
					maxWidth: '420px',
					padding: '12px 16px',
					zIndex: 10060
				}
			}).showToast();
		}
	}
	var por = document.getElementById('combosPorPagina');
	if (por) {
		por.addEventListener('change', function () {
			var params = new URLSearchParams(window.location.search);
			params.set('por', por.value);
			params.delete('inicio');
			window.location.href = 'combos.php?' + params.toString();
		});
	}
	var ir = document.getElementById('formIrPaginaCombo');
	if (ir) {
		ir.addEventListener('submit', function (e) {
			e.preventDefault();
			var pagina = parseInt(document.getElementById('combosIrPagina').value, 10) || 1;
			var params = new URLSearchParams(window.location.search);
			if (pagina > 1) params.set('inicio', String(pagina));
			else params.delete('inicio');
			window.location.href = 'combos.php?' + params.toString();
		});
	}
	document.addEventListener('click', function (e) {
		var boton = e.target.closest ? e.target.closest('.js-combo-estado') : null;
		if (!boton || boton.disabled) return;
		var actual = boton.getAttribute('data-estado') === '1' ? 1 : 0;
		var nuevo = actual === 1 ? 0 : 1;
		boton.disabled = true;
		fetch('ajax/ajax-combos-estado.php', {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: 'id=' + encodeURIComponent(boton.getAttribute('data-id')) + '&estado=' + nuevo
		}).then(function (r) { return r.json(); }).then(function (data) {
			boton.disabled = false;
			if (!data || !data.success) {
				aviso((data && data.message) || 'No se pudo cambiar el estado.', 'error');
				return;
			}
			var activo = parseInt(data.estado, 10) === 1;
			boton.setAttribute('data-estado', activo ? '1' : '0');
			boton.textContent = activo ? 'Activo' : 'Inactivo';
			boton.classList.toggle('is-activo', activo);
			boton.classList.toggle('is-inactivo', !activo);
			var fila = boton.closest('tr');
			if (fila) {
				fila.classList.toggle('combo-activo', activo);
				fila.classList.toggle('combo-inactivo', !activo);
			}
			aviso(data.message, 'success');
		}).catch(function () {
			boton.disabled = false;
			aviso('Error de red al cambiar el estado.', 'error');
		});
	});
})();
</script>
</body>
</html>
