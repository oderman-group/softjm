<div class="cliente-direccion-nomenclatura">
  <div class="cliente-direccion-grid">
    <div class="cliente-direccion-campo cliente-direccion-campo-via">
      <span class="cliente-direccion-campo-label">Tipo vía</span>
      <select data-placeholder="Seleccione..." class="chzn-select" tabindex="2" name="op1">
        <option value=""></option>
        <?php foreach ($tiposViaDireccion as $tipoVia) { ?>
          <option value="<?= htmlspecialchars($tipoVia) ?>" <?php if ($direccionPartes['op1'] === $tipoVia) echo 'selected'; ?>><?= htmlspecialchars($tipoVia) ?></option>
        <?php } ?>
      </select>
    </div>

    <div class="cliente-direccion-campo cliente-direccion-campo-num">
      <span class="cliente-direccion-campo-label">Número</span>
      <input type="text" name="op2" style="text-transform:uppercase;" placeholder="Ej. 72" value="<?= htmlspecialchars($direccionPartes['op2']) ?>">
    </div>

    <div class="cliente-direccion-campo cliente-direccion-campo-cardinal">
      <span class="cliente-direccion-campo-label">Cardinal</span>
      <select data-placeholder="Seleccione..." class="chzn-select" tabindex="2" name="op3">
        <option value=""></option>
        <?php foreach ($puntosCardinalesDireccion as $puntoCardinal) { ?>
          <option value="<?= htmlspecialchars($puntoCardinal) ?>" <?php if ($direccionPartes['op3'] === $puntoCardinal) echo 'selected'; ?>><?= htmlspecialchars($puntoCardinal) ?></option>
        <?php } ?>
      </select>
    </div>

    <div class="cliente-direccion-campo cliente-direccion-campo-num">
      <span class="cliente-direccion-campo-label">Número (#)</span>
      <input type="text" name="op4" style="text-transform:uppercase;" placeholder="Ej. 15" value="<?= htmlspecialchars($direccionPartes['op4']) ?>">
    </div>

    <div class="cliente-direccion-campo cliente-direccion-campo-cardinal">
      <span class="cliente-direccion-campo-label">Cardinal</span>
      <select data-placeholder="Seleccione..." class="chzn-select" tabindex="2" name="op5">
        <option value=""></option>
        <?php foreach ($puntosCardinalesDireccion as $puntoCardinal) { ?>
          <option value="<?= htmlspecialchars($puntoCardinal) ?>" <?php if ($direccionPartes['op5'] === $puntoCardinal) echo 'selected'; ?>><?= htmlspecialchars($puntoCardinal) ?></option>
        <?php } ?>
      </select>
    </div>

    <div class="cliente-direccion-campo cliente-direccion-campo-num">
      <span class="cliente-direccion-campo-label">Número (-)</span>
      <input type="text" name="op6" style="text-transform:uppercase;" placeholder="Ej. 20" value="<?= htmlspecialchars($direccionPartes['op6']) ?>">
    </div>

    <div class="cliente-direccion-campo cliente-direccion-campo-complemento">
      <span class="cliente-direccion-campo-label">Complemento</span>
      <input type="text" name="op7" style="text-transform:uppercase;" placeholder="Oficina, apto, torre..." value="<?= htmlspecialchars($direccionPartes['op7']) ?>">
    </div>
  </div>
</div>
