<?php
/**
 * Paginación del listado de cotizaciones. Requiere las variables de cotizaciones-listado-consulta.php.
 */
?>
<div class="productos-paginacion" id="cotizacionesPaginacionContenedor">
	<div class="productos-paginacion-grupo">
		<span><?= $numTotalCotizaciones > 0 ? (int) ($offsetCotizaciones + 1) : 0; ?>–<?= (int) min($offsetCotizaciones + $porPaginaCotizacion, max(0, $numTotalCotizaciones)); ?> de <?= (int) $numTotalCotizaciones; ?></span>
		<label for="cotizacionesPorPagina">Ver</label>
		<select id="cotizacionesPorPagina" class="js-cotizaciones-por-pagina">
			<?php foreach ($tamanosPaginaCotizacion as $tamano) { ?>
				<option value="<?= (int) $tamano; ?>"<?= $tamano === $porPaginaCotizacion ? ' selected' : ''; ?>><?= (int) $tamano; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="productos-paginacion-grupo">
		<?php if ($paginaListaCotizaciones > 1) { ?>
			<a href="<?= htmlspecialchars($urlPaginaCotizacion($paginaListaCotizaciones - 1, $porPaginaCotizacion)); ?>" class="js-cotizaciones-pagina" data-pagina="<?= (int) ($paginaListaCotizaciones - 1); ?>">Anterior</a>
		<?php } ?>
		<?php
		$puntosIzquierda = false;
		$puntosDerecha = false;
		for ($i = 1; $i <= $paginasCotizaciones; $i++) {
			$mostrarNumero = $i === 1 || $i === $paginasCotizaciones || ($i >= $paginaListaCotizaciones - 2 && $i <= $paginaListaCotizaciones + 2);
			if ($mostrarNumero && $i === $paginaListaCotizaciones) { ?>
				<span class="es-actual"><?= $i; ?></span>
			<?php } elseif ($mostrarNumero) { ?>
				<a href="<?= htmlspecialchars($urlPaginaCotizacion($i, $porPaginaCotizacion)); ?>" class="js-cotizaciones-pagina" data-pagina="<?= (int) $i; ?>"><?= $i; ?></a>
			<?php } elseif ($i < $paginaListaCotizaciones && !$puntosIzquierda) {
				$puntosIzquierda = true; ?>
				<span class="es-puntos">…</span>
			<?php } elseif ($i > $paginaListaCotizaciones && !$puntosDerecha) {
				$puntosDerecha = true; ?>
				<span class="es-puntos">…</span>
			<?php }
		} ?>
		<?php if ($paginaListaCotizaciones < $paginasCotizaciones) { ?>
			<a href="<?= htmlspecialchars($urlPaginaCotizacion($paginaListaCotizaciones + 1, $porPaginaCotizacion)); ?>" class="js-cotizaciones-pagina" data-pagina="<?= (int) ($paginaListaCotizaciones + 1); ?>">Siguiente</a>
		<?php } ?>
	</div>
	<form class="productos-paginacion-grupo" id="formIrPaginaCotizacion">
		<label for="cotizacionesIrPagina">Ir a</label>
		<input type="number" id="cotizacionesIrPagina" min="1" max="<?= (int) max(1, $paginasCotizaciones); ?>" value="<?= (int) $paginaListaCotizaciones; ?>">
		<button type="submit">Ir</button>
	</form>
</div>
