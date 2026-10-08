<?php
/**
 * Paginación del listado de productos (compatible con AJAX).
 * Requiere: $offsetProductos, $porPaginaProducto, $numTotalProductos,
 *           $paginaListaProductos, $paginasProductos, $tamanosPaginaProducto, $urlPaginaProducto
 */
?>
<div class="productos-paginacion" id="productosPaginacionContenedor">
	<div class="productos-paginacion-grupo">
		<span><?= (int) ($offsetProductos + 1); ?>–<?= (int) min($offsetProductos + $porPaginaProducto, max(0, $numTotalProductos)); ?> de <?= (int) $numTotalProductos; ?></span>
		<label for="productosPorPagina">Ver</label>
		<select id="productosPorPagina" class="js-productos-por-pagina">
			<?php foreach ($tamanosPaginaProducto as $tamano) { ?>
				<option value="<?= (int) $tamano; ?>"<?= $tamano === $porPaginaProducto ? ' selected' : ''; ?>><?= (int) $tamano; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="productos-paginacion-grupo">
		<?php if ($paginaListaProductos > 1) { ?>
			<a href="<?= htmlspecialchars($urlPaginaProducto($paginaListaProductos - 1, $porPaginaProducto)); ?>" class="js-productos-pagina" data-pagina="<?= (int) ($paginaListaProductos - 1); ?>">Anterior</a>
		<?php } ?>
		<?php for ($i = 1; $i <= $paginasProductos; $i++) {
			$mostrarNumero = $i === 1 || $i === $paginasProductos || ($i >= $paginaListaProductos - 2 && $i <= $paginaListaProductos + 2);
			$mostrarPuntos = ($i === 2 && $paginaListaProductos > 4) || ($i === $paginasProductos - 1 && $paginaListaProductos < $paginasProductos - 3);
			if ($mostrarNumero && $i === $paginaListaProductos) { ?>
				<span class="es-actual"><?= $i; ?></span>
			<?php } elseif ($mostrarNumero) { ?>
				<a href="<?= htmlspecialchars($urlPaginaProducto($i, $porPaginaProducto)); ?>" class="js-productos-pagina" data-pagina="<?= (int) $i; ?>"><?= $i; ?></a>
			<?php } elseif ($mostrarPuntos) { ?>
				<span class="es-puntos">…</span>
			<?php }
		} ?>
		<?php if ($paginaListaProductos < $paginasProductos) { ?>
			<a href="<?= htmlspecialchars($urlPaginaProducto($paginaListaProductos + 1, $porPaginaProducto)); ?>" class="js-productos-pagina" data-pagina="<?= (int) ($paginaListaProductos + 1); ?>">Siguiente</a>
		<?php } ?>
	</div>
	<form class="productos-paginacion-grupo" id="formIrPaginaProducto">
		<label for="productosIrPagina">Ir a</label>
		<input type="number" id="productosIrPagina" min="1" max="<?= (int) max(1, $paginasProductos); ?>" value="<?= (int) $paginaListaProductos; ?>">
		<button type="submit">Ir</button>
	</form>
</div>
