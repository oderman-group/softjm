<?php
/**
 * Selector de etiquetas para clientes (multi-selección estilo WhatsApp).
 * Requiere: $listaEtiquetasCliente, $etiquetasClienteSeleccionadas
 */
?>
<div class="cliente-etiquetas-selector" role="group" aria-label="Etiquetas del cliente">
  <p class="cliente-etiquetas-leyenda">
    <span class="cliente-etiquetas-leyenda-item cliente-etiquetas-leyenda-item--activa">■ Activa</span>
    <span class="cliente-etiquetas-leyenda-item cliente-etiquetas-leyenda-item--inactiva">○ Disponible</span>
  </p>
  <?php foreach ($listaEtiquetasCliente as $etiquetaItem) {
      $etiqId = intval($etiquetaItem['etiq_id']);
      $checked = in_array($etiqId, $etiquetasClienteSeleccionadas, true);
      $color = htmlspecialchars($etiquetaItem['etiq_color']);
      $fondo = htmlspecialchars($etiquetaItem['etiq_color_fondo']);
  ?>
    <label class="cliente-etiqueta-option<?= $checked ? ' is-active' : '' ?>">
      <input type="checkbox" name="etiquetas[]" value="<?= $etiqId ?>" <?= $checked ? 'checked' : '' ?>>
      <span
        class="cliente-etiqueta-pill"
        style="--etiq-color: <?= $color ?>; --etiq-fondo: <?= $fondo ?>;"
      >
        <span class="cliente-etiqueta-check" aria-hidden="true"></span>
        <span class="cliente-etiqueta-text"><?= htmlspecialchars($etiquetaItem['etiq_nombre']) ?></span>
      </span>
    </label>
  <?php } ?>
</div>
<script>
(function () {
  document.querySelectorAll('.cliente-etiqueta-option input[type="checkbox"]').forEach(function (input) {
    input.addEventListener('change', function () {
      this.closest('.cliente-etiqueta-option').classList.toggle('is-active', this.checked);
    });
  });
})();
</script>
