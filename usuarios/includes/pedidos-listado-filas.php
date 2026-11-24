<?php
$numeroFilaPedido = $offsetPedidos + 1;
foreach ($pedidosFilas as $res) {
	$remision = is_array($res['remision']) ? $res['remision'] : array();
	$idRemision = isset($remision['remi_id']) ? (string) $remision['remi_id'] : '';
	$infoRem = '';
	if ($idRemision !== '') {
		$infoRem = 'Este pedido ya generó la remisión con ID: ' . $idRemision . '. En la fecha: ' . ($remision['remi_fecha_creacion'] ?? '');
	}
	$estadoOfima = isset($res['pedid_ofima_estado']) ? (int) $res['pedid_ofima_estado'] : 0;
	$respuestaOfima = isset($res['pedid_ofima_respuesta']) ? (string) $res['pedid_ofima_respuesta'] : '';
	$enOfima = (int) ($res['pedid_ofima_integrado'] ?? 0) === 1 || $estadoOfima === 1;
	$estadoTexto = isset(Pedido::ESTADO_PEDIDO[$res['pedid_estado']]) ? Pedido::ESTADO_PEDIDO[$res['pedid_estado']] : '';
	$esDueno = (string) $_SESSION['id'] === (string) $res['pedid_creador'] || (string) $_SESSION['id'] === (string) $res['pedid_vendedor'];
	$puedeEliminarEstaFila = $esDueno && empty($ofimaPedidosBloqueados) && !empty($puedeEliminarPedido) && !$enOfima;
	$hayMasAcciones = $puedeEliminarEstaFila
		|| !empty($puedePdfPedido)
		|| (empty($ofimaPedidosBloqueados) && $idRemision === '' && !empty($puedeRemisionPedido));
	?>
	<tr class="<?= $idRemision !== '' ? 'is-remision' : ''; ?>">
		<td><?= $numeroFilaPedido; ?></td>
		<td title="<?= htmlspecialchars($infoRem, ENT_QUOTES, 'UTF-8'); ?>"><?= (int) $res['pedid_id']; ?></td>
		<td><?= htmlspecialchars((string) $res['pedid_fecha_propuesta'], ENT_QUOTES, 'UTF-8'); ?></td>
		<td><?= htmlspecialchars(strtoupper((string) ($res['cli_nombre'] ?? '')), ENT_QUOTES, 'UTF-8'); ?></td>
		<td><?= htmlspecialchars(strtoupper((string) ($res['responsable_nombre'] ?? '')), ENT_QUOTES, 'UTF-8'); ?></td>
		<td><?= htmlspecialchars(strtoupper((string) ($res['vendedor_nombre'] ?? '')), ENT_QUOTES, 'UTF-8'); ?></td>
		<td>
			<?php if (!empty($puedeTimelinePedido)) { ?>
				<a href="pedidos-timeline.php?id=<?= (int) $res['pedid_id']; ?>" target="_blank"><?= htmlspecialchars($estadoTexto, ENT_QUOTES, 'UTF-8'); ?></a>
			<?php } else { ?>
				<span><?= htmlspecialchars($estadoTexto, ENT_QUOTES, 'UTF-8'); ?></span>
			<?php } ?>
		</td>
		<td><a href="cotizaciones-editar.php?id=<?= (int) $res['pedid_cotizacion']; ?>"><?= (int) $res['pedid_cotizacion']; ?></a></td>
		<?php if (!empty($ofimaIntegracionActiva)) {
			if ($enOfima && $estadoOfima === 2) {
				$claseOfimaIcono = 'is-ok';
				$iconoOfimaEstado = 'icon-ok-sign';
				$tituloOfima = 'Este pedido ya está en Ofima. El último envío no se actualizó.';
			} elseif ($enOfima) {
				$claseOfimaIcono = 'is-ok';
				$iconoOfimaEstado = 'icon-ok-sign';
				$tituloOfima = 'Este pedido ya está en Ofima';
			} elseif ($estadoOfima === 2) {
				$claseOfimaIcono = 'is-error';
				$iconoOfimaEstado = 'icon-exclamation-sign';
				$tituloOfima = 'No está en Ofima. El envío falló.';
			} else {
				$claseOfimaIcono = 'is-off';
				$iconoOfimaEstado = 'icon-check-empty';
				$tituloOfima = 'Pendiente de enviar a Ofima';
			}
		?>
		<td class="col-ofima"><?php if ($respuestaOfima !== '') { ?>
			<a href="#" class="pedidos-icono-ofima <?= $claseOfimaIcono; ?> js-ofima-mensaje" data-estado="<?= (int) $estadoOfima; ?>" data-mensaje="<?= htmlspecialchars($respuestaOfima, ENT_QUOTES, 'UTF-8'); ?>" title="Ver mensaje de Ofima" aria-label="Ver mensaje de Ofima"><i class="<?= $iconoOfimaEstado; ?>"></i></a>
		<?php } else { ?>
			<span class="pedidos-icono-ofima <?= $claseOfimaIcono; ?>" title="<?= htmlspecialchars($tituloOfima, ENT_QUOTES, 'UTF-8'); ?>"><i class="<?= $iconoOfimaEstado; ?>"></i></span>
		<?php } ?></td>
		<?php } ?>
		<td class="cotiz-acciones">
			<div class="btn-group">
				<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span></button>
				<ul class="dropdown-menu pull-right">
					<?php if (!empty($ofimaBotonEnvio)) {
						if ($estadoOfima === 1) {
							$claseOfima = 'ofima-ok';
							$iconoOfima = 'icon-refresh';
							$textoOfima = 'Actualizar en Ofima';
							$confirmaOfima = '¿Actualizar este pedido en Ofima?';
						} elseif ($estadoOfima === 2) {
							$claseOfima = 'ofima-error';
							$iconoOfima = 'icon-repeat';
							$textoOfima = 'Reenviar a Ofima';
							$confirmaOfima = '¿Reenviar este pedido a Ofima?';
						} else {
							$claseOfima = 'ofima-pendiente';
							$iconoOfima = 'icon-share';
							$textoOfima = 'Enviar pedido a Ofima';
							$confirmaOfima = '¿Enviar este pedido a Ofima?';
						}
					?>
						<li><a href="bd_create/pedidos-enviar-ofima.php?id=<?= (int) $res['pedid_id']; ?>" class="<?= $claseOfima; ?>" onClick="return confirm('<?= htmlspecialchars($confirmaOfima, ENT_QUOTES, 'UTF-8'); ?>');"><i class="<?= $iconoOfima; ?>"></i><?= $textoOfima; ?></a></li>
						<?php if ($respuestaOfima !== '') { ?>
							<li><a href="#" class="ofima-msg js-ofima-mensaje" data-estado="<?= (int) $estadoOfima; ?>" data-mensaje="<?= htmlspecialchars($respuestaOfima, ENT_QUOTES, 'UTF-8'); ?>"><i class="icon-comment"></i>Ver mensaje de Ofima</a></li>
						<?php } ?>
						<?php if ($hayMasAcciones) { ?><li class="divider"></li><?php } ?>
					<?php } ?>
					<?php if ($puedeEliminarEstaFila) { ?>
						<li><a href="bd_delete/pedidos-eliminar.php?id=<?= (int) $res['pedid_id']; ?>" class="accion-eliminar" onClick="if(!confirm('Desea eliminar el registro?')){return false;}"><i class="icon-trash"></i>Eliminar</a></li>
					<?php } ?>
					<?php if (!empty($puedePdfPedido)) { ?>
						<li><a href="reportes/formato-pedido-1_pdf.php?id=<?= (int) $res['pedid_id']; ?>" class="accion-pdf" target="_blank"><i class="icon-file-alt"></i>Generar PDF</a></li>
					<?php } ?>
					<?php if (empty($ofimaPedidosBloqueados) && $idRemision === '' && !empty($puedeRemisionPedido)) {
						$confirmaRemision = !empty($ofimaBotonEnvio)
							? 'La integración con Ofima está activa. Se generará la remisión sin descontar existencias. ¿Desea continuar?'
							: 'Esta acción descontará existencias de los productos asociados. Desea generar remisión de este pedido?';
					?>
						<li><a href="bd_create/pedidos-generar-remision-v2.php?id=<?= (int) $res['pedid_id']; ?>" class="accion-remision" onClick="if(!confirm('<?= htmlspecialchars($confirmaRemision, ENT_QUOTES, 'UTF-8'); ?>')){return false;}"><i class="icon-truck"></i>Generar remisión</a></li>
					<?php } ?>
				</ul>
			</div>
		</td>
	</tr>
	<?php
	$numeroFilaPedido++;
}
