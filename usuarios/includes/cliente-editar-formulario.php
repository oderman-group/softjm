<form class="cliente-form-sections" method="post" action="bd_update/clientes-actualizar.php">
  <input type="hidden" name="id" value="<?= $clienteId ?>">
  <input type="hidden" value="<?= $resultadoD['cli_categoria'] ?>" name="categoriaActual">

  <section class="cliente-form-section">
    <h3 class="cliente-form-section-title">Contacto y empresa</h3>
    <p class="cliente-form-section-desc">Datos que el equipo comercial consulta con más frecuencia.</p>
    <div class="cliente-form-grid cliente-form-grid-3">
      <div class="cliente-field cliente-field-full">
        <label class="cliente-field-label">Nombre (*)</label>
        <input type="text" class="cliente-field-input" name="nombre" value="<?= htmlspecialchars($resultadoD['cli_nombre']) ?>" style="text-transform:uppercase;" required>
      </div>
      <div class="cliente-field cliente-field-full">
        <label class="cliente-field-label">Email</label>
        <input type="email" class="cliente-field-input" name="email" value="<?= htmlspecialchars($resultadoD['cli_email']) ?>" style="text-transform:lowercase;">
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">Teléfono</label>
        <input type="text" class="cliente-field-input" name="telefono" value="<?= htmlspecialchars($resultadoD['cli_telefono']) ?>">
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">Celular</label>
        <input type="text" class="cliente-field-input" name="celular" value="<?= htmlspecialchars($resultadoD['cli_celular']) ?>" maxlength="10">
        <span class="cliente-field-hint">10 dígitos sin espacios.</span>
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">Sigla (nombre corto)</label>
        <input type="text" class="cliente-field-input" name="sigla" style="text-transform:uppercase;" value="<?= htmlspecialchars($resultadoD['cli_sigla']) ?>">
      </div>
      <div class="cliente-field cliente-field-full">
        <label class="cliente-field-label">Teléfonos complementarios</label>
        <input type="text" class="cliente-field-input" name="telefonos" value="<?= htmlspecialchars($resultadoD['cli_telefonos']) ?>">
      </div>
    </div>
  </section>

  <section class="cliente-form-section">
    <h3 class="cliente-form-section-title">Acceso al sistema y documentación</h3>
    <div class="cliente-form-grid cliente-form-grid-2">
      <div class="cliente-field">
        <label class="cliente-field-label">Tipo de documento</label>
        <select data-placeholder="Escoja una opción..." class="chzn-select cliente-field-input" tabindex="2" name="tipoDocumento">
          <option value="1"></option>
          <option value="2" <?php if ($resultadoD['cli_tipo_documento'] == 2) echo 'selected'; ?>>NIT</option>
          <option value="3" <?php if ($resultadoD['cli_tipo_documento'] == 3) echo 'selected'; ?>>Cédula</option>
        </select>
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">Documento</label>
        <input type="text" class="cliente-field-input" name="usuarioCliente" value="<?= htmlspecialchars($resultadoD['cli_usuario']) ?>" autocomplete="off" placeholder="Documento">
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">Usuario de acceso</label>
        <input type="text" class="cliente-field-input" value="<?= htmlspecialchars($resultadoD['cli_usuario_acceso']) ?>" <?= $soloLecturaUsuarioAcceso ?> name="usuarioAcceso" autocomplete="off">
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">Contraseña</label>
        <input type="<?= $campoC ?>" class="cliente-field-input" name="claveCliente" value="<?= htmlspecialchars($resultadoD['cli_clave']) ?>" autocomplete="off">
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">Clave documentos (*)</label>
        <input type="<?= $campoC ?>" class="cliente-field-input" name="claveDocumentos" value="<?= htmlspecialchars($resultadoD['cli_clave_documentos']) ?>" autocomplete="off" required>
      </div>
    </div>
  </section>

  <section class="cliente-form-section">
    <h3 class="cliente-form-section-title">Ubicación</h3>
    <div class="cliente-form-grid cliente-form-grid-3">
      <div class="cliente-field">
        <label class="cliente-field-label">País</label>
        <select data-placeholder="Escoja una opción..." class="chzn-select cliente-field-input" tabindex="2" name="pais" onChange="mostrar(this)">
          <option value=""></option>
          <?php foreach ($listaPaises as $nombrePais) { ?>
            <option value="<?= htmlspecialchars($nombrePais) ?>" <?php if ($resultadoD['cli_pais'] == $nombrePais) echo 'selected'; ?>><?= htmlspecialchars($nombrePais) ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">Zona</label>
        <select data-placeholder="Escoja una opción..." class="chzn-select cliente-field-input" tabindex="2" name="zona" disabled>
          <option value=""></option>
          <?php foreach ($listaZonas as $zonaItem) { ?>
            <option value="<?= $zonaItem['zon_id'] ?>" <?php if ($resultadoD['cli_zona'] == $zonaItem['zon_id']) echo 'selected'; ?>><?= htmlspecialchars($zonaItem['zon_nombre']) ?></option>
          <?php } ?>
        </select>
      </div>
      <div id="local" class="cliente-field" style="<?= $displayCol ?>">
        <label class="cliente-field-label">Ciudad</label>
        <select data-placeholder="Escoja una opción..." class="chzn-select cliente-field-input" tabindex="2" name="ciudad">
          <option value=""></option>
          <?php foreach ($listaCiudades as $ciudadItem) { ?>
            <option value="<?= $ciudadItem['ciu_id'] ?>" <?php if ($resultadoD['cli_ciudad'] == $ciudadItem['ciu_id']) echo 'selected'; ?>>
              <?= htmlspecialchars($ciudadItem['ciu_nombre'] . ', ' . $ciudadItem['dep_nombre']) ?>
            </option>
          <?php } ?>
        </select>
      </div>
      <div id="extrangero" class="cliente-field cliente-field-full" style="<?= $displayExtr ?>">
        <label class="cliente-field-label">Ciudad (extranjero)</label>
        <input type="text" class="cliente-field-input" name="ciuExtra" value="<?= htmlspecialchars($resultadoD['cli_ciudad_extranjera']) ?>">
      </div>
      <div class="cliente-field cliente-field-full">
        <label class="cliente-field-label">Dirección</label>
        <?php include __DIR__ . '/cliente-direccion-nomenclatura-campos.php'; ?>
      </div>
    </div>
  </section>

  <section class="cliente-form-section">
    <h3 class="cliente-form-section-title">Clasificación comercial</h3>
    <div class="cliente-form-grid cliente-form-grid-2">
      <div class="cliente-field">
        <label class="cliente-field-label">Estado</label>
        <select data-placeholder="Escoja una opción..." class="chzn-select cliente-field-input" tabindex="2" name="categoria" disabled>
          <option value=""></option>
          <option value="<?= CLI_CATEGORIA_PROSPECTO ?>" <?php if ($resultadoD['cli_categoria'] == CLI_CATEGORIA_PROSPECTO) echo 'selected'; ?>>Prospecto</option>
          <option value="<?= CLI_CATEGORIA_CLIENTE ?>" <?php if ($resultadoD['cli_categoria'] == CLI_CATEGORIA_CLIENTE) echo 'selected'; ?>>Cliente</option>
          <option value="<?= CLI_CATEGORIA_DEALER ?>" <?php if ($resultadoD['cli_categoria'] == CLI_CATEGORIA_DEALER) echo 'selected'; ?>>Dealer</option>
        </select>
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">Nivel</label>
        <select data-placeholder="Escoja una opción..." class="chzn-select cliente-field-input" tabindex="2" name="nivel" disabled>
          <option value=""></option>
          <?php foreach ($nivelesCliente as $nivelId => $nivelNombre) { ?>
            <option value="<?= $nivelId ?>" <?php if ($resultadoD['cli_nivel'] == $nivelId) echo 'selected'; ?>><?= htmlspecialchars($nivelNombre) ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">¿Tiene crédito?</label>
        <select data-placeholder="Escoja una opción..." class="chzn-select cliente-field-input" tabindex="2" name="credito">
          <option value="0">--</option>
          <option value="1" <?php if ($resultadoD['cli_credito'] == 1) echo 'selected'; ?>>SI</option>
          <option value="0" <?php if ($resultadoD['cli_credito'] == '0') echo 'selected'; ?>>NO</option>
        </select>
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">Saldo disponible</label>
        <input type="text" class="cliente-field-input" name="saldo" value="<?= htmlspecialchars($resultadoD['cli_saldo'] ?: '0') ?>" maxlength="10" readonly>
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">Referencia de llegada</label>
        <select data-placeholder="Escoja una opción..." class="chzn-select cliente-field-input" tabindex="2" name="referencia" onchange="mostrarNombreEvento(this)">
          <option value=""></option>
          <?php for ($i = 1; $i <= 12; $i++) { ?>
            <option value="<?= $i ?>" <?php if ($resultadoD['cli_referencia'] == $i) echo 'selected'; ?>><?= htmlspecialchars($referenciaLlegada[$i]) ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="cliente-field" id="eventoNombre" style="display: <?= $diplayNombreEvento ?>;">
        <label class="cliente-field-label">Nombre del evento</label>
        <input type="text" class="cliente-field-input" name="nombreEvento" value="<?= htmlspecialchars($resultadoD['cli_nombre_evento']) ?>">
      </div>
      <div class="cliente-field cliente-field-full">
        <label class="cliente-field-label">Grupos (*)</label>
        <select data-placeholder="Escoja una opción..." class="chzn-select cliente-field-input" multiple tabindex="2" name="grupos[]" required>
          <option value=""></option>
          <?php foreach ($listaDealers as $dealerItem) { ?>
            <option value="<?= $dealerItem['deal_id'] ?>" <?php if (in_array(intval($dealerItem['deal_id']), $gruposSeleccionados, true)) echo 'selected'; ?>>
              <?= htmlspecialchars($dealerItem['deal_nombre']) ?>
            </option>
          <?php } ?>
        </select>
      </div>
      <div class="cliente-field cliente-field-full cliente-field-checkbox">
        <label class="cliente-checkbox">
          <input type="checkbox" value="1" name="clienteInstitucional" <?php if ($resultadoD['cli_institucional'] == 1) echo 'checked'; ?>>
          Cliente institucional
        </label>
      </div>
    </div>
  </section>

  <section class="cliente-form-section" id="cliente-etiquetas-section">
    <h3 class="cliente-form-section-title">Etiquetas</h3>
    <p class="cliente-form-section-desc">Clasifique al cliente con una o más etiquetas visibles en todo el CRM.</p>
    <div class="cliente-form-grid cliente-form-grid-2">
      <div class="cliente-field cliente-field-full">
        <?php include __DIR__ . '/cliente-etiquetas-selector.php'; ?>
      </div>
    </div>
  </section>

  <section class="cliente-form-section">
    <h3 class="cliente-form-section-title">Asesor asociado</h3>
    <div class="cliente-form-grid cliente-form-grid-2">
      <div class="cliente-field cliente-field-full">
        <label class="cliente-field-label">Asesor</label>
        <select data-placeholder="Escoja una opción..." class="chzn-select cliente-field-input" tabindex="2" name="asesor">
          <option value=""></option>
          <?php foreach ($listaAsesores as $asesorItem) { ?>
            <option value="<?= $asesorItem['usr_id'] ?>" <?php if ($asesorSeleccionado && intval($asesorItem['usr_id']) === $asesorSeleccionado) echo 'selected'; ?>>
              <?= htmlspecialchars(strtoupper($asesorItem['usr_nombre'])) ?>
            </option>
          <?php } ?>
        </select>
      </div>
    </div>
  </section>

  <div class="cliente-form-actions">
    <a href="javascript:history.go(-1);" class="btn btn-primary"><i class="icon-arrow-left"></i> Regresar</a>
    <button type="submit" class="btn btn-info" id="btnGuardarCliente"><i class="icon-save"></i> <span class="btn-text">Guardar cambios</span></button>
  </div>
</form>

<script>
(function () {
  var form = document.querySelector('.cliente-form-sections');
  var btn = document.getElementById('btnGuardarCliente');
  if (!form || !btn) return;

  var enviando = false;

  form.addEventListener('submit', function (e) {
    if (enviando) {
      e.preventDefault();
      return;
    }

    if (!form.checkValidity()) {
      return;
    }

    enviando = true;
    btn.disabled = true;
    btn.innerHTML = '<i class="icon-spinner icon-spin"></i> <span class="btn-text">Guardando...</span>';
  });
})();
</script>
