<?php
/**
 * Paginación del listado de pedidos. Requiere las variables de pedidos-listado-consulta.php.
 */
?>
<div class="productos-paginacion" id="pedidosPaginacionContenedor">
	<div class="productos-paginacion-grupo">
		<span><?= $numTotalPedidos > 0 ? (int) ($offsetPedidos + 1) : 0; ?>–<?= (int) min($offsetPedidos + $porPaginaPedido, max(0, $numTotalPedidos)); ?> de <?= (int) $numTotalPedidos; ?></span>
		<label for="pedidosPorPagina">Ver</label>
		<select id="pedidosPorPagina" class="js-pedidos-por-pagina">
			<?php foreach ($tamanosPaginaPedido as $tamano) { ?>
				<option value="<?= (int) $tamano; ?>"<?= $tamano === $porPaginaPedido ? ' selected' : ''; ?>><?= (int) $tamano; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="productos-paginacion-grupo">
		<?php if ($paginaListaPedidos > 1) { ?>
			<a href="<?= htmlspecialchars($urlPaginaPedido($paginaListaPedidos - 1, $porPaginaPedido)); ?>" class="js-pedidos-pagina" data-pagina="<?= (int) ($paginaListaPedidos - 1); ?>">Anterior</a>
		<?php } ?>
		<?php
		$puntosIzquierda = false;
		$puntosDerecha = false;
		for ($i = 1; $i <= $paginasPedidos; $i++) {
			$mostrarNumero = $i === 1 || $i === $paginasPedidos || ($i >= $paginaListaPedidos - 2 && $i <= $paginaListaPedidos + 2);
			if ($mostrarNumero && $i === $paginaListaPedidos) { ?>
				<span class="es-actual"><?= $i; ?></span>
			<?php } elseif ($mostrarNumero) { ?>
				<a href="<?= htmlspecialchars($urlPaginaPedido($i, $porPaginaPedido)); ?>" class="js-pedidos-pagina" data-pagina="<?= (int) $i; ?>"><?= $i; ?></a>
			<?php } elseif ($i < $paginaListaPedidos && !$puntosIzquierda) {
				$puntosIzquierda = true; ?>
				<span class="es-puntos">…</span>
			<?php } elseif ($i > $paginaListaPedidos && !$puntosDerecha) {
				$puntosDerecha = true; ?>
				<span class="es-puntos">…</span>
			<?php }
		} ?>
		<?php if ($paginaListaPedidos < $paginasPedidos) { ?>
			<a href="<?= htmlspecialchars($urlPaginaPedido($paginaListaPedidos + 1, $porPaginaPedido)); ?>" class="js-pedidos-pagina" data-pagina="<?= (int) ($paginaListaPedidos + 1); ?>">Siguiente</a>
		<?php } ?>
	</div>
	<form class="productos-paginacion-grupo" id="formIrPaginaPedido">
		<label for="pedidosIrPagina">Ir a</label>
		<input type="number" id="pedidosIrPagina" min="1" max="<?= (int) max(1, $paginasPedidos); ?>" value="<?= (int) $paginaListaPedidos; ?>">
		<button type="submit">Ir</button>
	</form>
</div>
