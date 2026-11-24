<?php
/**
 * Miga tema.
 * Breadcrumb del tema de listados (fondo blanco, borde redondeado, dentro del contenido).
 * El inicio se agrega solo. El último ítem es la página actual.
 *
 * $migaTema = [
 *     ['texto' => 'Combos', 'url' => 'combos.php'],
 *     ['texto' => 'Editar combos'],
 * ];
 * include 'includes/miga-tema.php';
 *
 * Acciones a la derecha, opcional:
 * $migaTemaAcciones = '<a class="btn" href="...">Texto</a>';
 */
$migaTemaItems = (isset($migaTema) && is_array($migaTema)) ? array_values($migaTema) : [];
$migaTemaTotal = count($migaTemaItems);
?>
<nav class="miga-tema" aria-label="Miga de pan">
	<ul class="breadcrumb">
		<li><a href="index.php" class="icon-home"></a><span class="divider"><i class="icon-angle-right"></i></span></li>
		<?php foreach ($migaTemaItems as $migaTemaIndice => $migaTemaItem) {
			$migaTemaTexto = htmlspecialchars((string) ($migaTemaItem['texto'] ?? ''), ENT_QUOTES, 'UTF-8');
			$migaTemaUrl = isset($migaTemaItem['url']) ? (string) $migaTemaItem['url'] : '';
			$migaTemaActual = $migaTemaIndice === $migaTemaTotal - 1 || $migaTemaUrl === '';
			if ($migaTemaActual) { ?>
				<li class="active"><?= $migaTemaTexto; ?></li>
			<?php } else { ?>
				<li><a href="<?= htmlspecialchars($migaTemaUrl, ENT_QUOTES, 'UTF-8'); ?>"><?= $migaTemaTexto; ?></a><span class="divider"><i class="icon-angle-right"></i></span></li>
			<?php }
		} ?>
		<?php if (!empty($migaTemaAcciones)) { ?>
			<li class="miga-tema-acciones"><?= $migaTemaAcciones; ?></li>
		<?php } ?>
	</ul>
</nav>
