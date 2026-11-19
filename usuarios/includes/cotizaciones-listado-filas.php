<?php
$numeroFilaCotizacion = $offsetCotizaciones + 1;
foreach ($cotizacionesFilas as $res) {
	$pedido = is_array($res['pedido']) ? $res['pedido'] : array();
	$idPedido = isset($pedido['pedid_id']) ? (string) $pedido['pedid_id'] : '';
	$infoPedido = '';
	if ($idPedido !== '') {
		$infoPedido = 'Esta cotización ya generó el pedido con ID: ' . $idPedido . '. En la fecha: ' . ($pedido['pedid_fecha_creacion'] ?? '');
	}
	$claseFila = ((int) $res['cotiz_vendida'] === 1) ? 'is-vendida' : '';
	$tipoCotizacion = ((int) $res['cotiz_es_precotizacion'] === 1)
		? '<span class="clientes-pill is-warning">Pre-cotización</span>'
		: '<span class="clientes-pill is-info">Cotización</span>';
	$puedeGenerar = empty($ofimaPedidosBloqueados)
		&& $idPedido === ''
		&& !empty($puedeGenerarPedidoCotiz)
		&& !empty($res['cotiz_ticket'])
		&& (int) $res['cotiz_es_precotizacion'] !== 1;
	$estadoOfima = isset($pedido['pedid_ofima_estado']) ? (int) $pedido['pedid_ofima_estado'] : 0;
	$respuestaOfima = isset($pedido['pedid_ofima_respuesta']) ? (string) $pedido['pedid_ofima_respuesta'] : '';
	?>
	<tr class="<?= $claseFila; ?>">
		<td><?= $numeroFilaCotizacion; ?></td>
		<td title="<?= htmlspecialchars($infoPedido, ENT_QUOTES, 'UTF-8'); ?>"><a href="cotizaciones-editar.php?id=<?= (int) $res['cotiz_id']; ?>"><?= (int) $res['cotiz_id']; ?></a></td>
		<td><?= $tipoCotizacion; ?></td>
		<td><?= htmlspecialchars((string) $res['cotiz_fecha_propuesta'], ENT_QUOTES, 'UTF-8'); ?></td>
		<td><a href="clientes-editar.php?id=<?= (int) $res['cli_id']; ?>"><?= htmlspecialchars(strtoupper((string) $res['cli_nombre']), ENT_QUOTES, 'UTF-8'); ?></a></td>
		<td class="col-items"><?php
			$gruposItems = array(
				'Productos' => $res['productos'],
				'Combos' => $res['combos'],
				'Servicios' => $res['servicios'],
			);
			$primero = true;
			foreach ($gruposItems as $tituloGrupo => $nombresGrupo) {
				if (!$nombresGrupo) {
					continue;
				}
				if (!$primero) {
					echo '<br>';
				}
				$primero = false;
				echo '<b>' . $tituloGrupo . ':</b><br>';
				$indiceItem = 1;
				foreach ($nombresGrupo as $nombreItem) {
					echo '<b>' . $indiceItem . '.</b> ' . htmlspecialchars($nombreItem, ENT_QUOTES, 'UTF-8') . ', ';
					$indiceItem++;
				}
			}
		?></td>
		<td><?= !empty($res['responsable_nombre']) ? htmlspecialchars(strtoupper($res['responsable_nombre']), ENT_QUOTES, 'UTF-8') : ''; ?></td>
		<td><?= !empty($res['vendedor_nombre']) ? htmlspecialchars(strtoupper($res['vendedor_nombre']), ENT_QUOTES, 'UTF-8') : ''; ?></td>
		<td class="cotiz-acciones">
			<?php
			$hayMasAcciones = !empty($puedeEditarCotiz) || !empty($puedePdfCotiz) || !empty($puedeReplicarCotiz) || $puedeGenerar;
			?>
			<div class="btn-group">
				<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span></button>
				<ul class="dropdown-menu pull-right">
					<?php if (!empty($ofimaBotonEnvio) && $idPedido !== '') {
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
						<li><a href="bd_create/pedidos-enviar-ofima.php?id=<?= (int) $idPedido; ?>" class="<?= $claseOfima; ?>" onClick="return confirm('<?= htmlspecialchars($confirmaOfima, ENT_QUOTES, 'UTF-8'); ?>');"><i class="<?= $iconoOfima; ?>"></i><?= $textoOfima; ?></a></li>
						<?php if ($respuestaOfima !== '') { ?>
							<li><a href="#" class="ofima-msg js-ofima-mensaje" data-estado="<?= (int) $estadoOfima; ?>" data-mensaje="<?= htmlspecialchars($respuestaOfima, ENT_QUOTES, 'UTF-8'); ?>"><i class="icon-comment"></i>Ver mensaje de Ofima</a></li>
						<?php } ?>
						<?php if ($hayMasAcciones) { ?><li class="divider"></li><?php } ?>
					<?php } ?>
					<?php if (!empty($puedeEditarCotiz)) { ?>
						<li><a href="cotizaciones-editar.php?id=<?= (int) $res['cotiz_id']; ?>#productos" class="accion-editar"><i class="icon-pencil"></i>Editar</a></li>
					<?php } ?>
					<?php if (!empty($puedePdfCotiz)) { ?>
						<li><a href="reportes/formato-cotizacion-1_pdf.php?id=<?= (int) $res['cotiz_id']; ?>" class="accion-pdf" target="_blank"><i class="icon-file-alt"></i>Generar PDF (Formato 1)</a></li>
						<li><a href="reportes/formato-cotizacion-3_pdf.php?id=<?= (int) $res['cotiz_id']; ?>" class="accion-pdf" target="_blank"><i class="icon-file-alt"></i>Generar PDF (Formato 2)</a></li>
					<?php } ?>
					<?php if (!empty($puedeReplicarCotiz)) { ?>
						<li><a href="bd_create/cotizaciones-replicar.php?id=<?= (int) $res['cotiz_id']; ?>" class="accion-replicar" onClick="if(!confirm('Desea replicar este registro?')){return false;}"><i class="icon-copy"></i>Replicar</a></li>
					<?php } ?>
					<?php if ($puedeGenerar) { ?>
						<li><a href="bd_create/cotizaciones-generar-pedido.php?id=<?= (int) $res['cotiz_id']; ?>" class="accion-pedido" onClick="if(!confirm('Desea generar pedido de esta cotización?')){return false;}"><i class="icon-shopping-cart"></i>Generar pedido</a></li>
					<?php } ?>
				</ul>
			</div>
		</td>
	</tr>
	<?php
	$numeroFilaCotizacion++;
}
