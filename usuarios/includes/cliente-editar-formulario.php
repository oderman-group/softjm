<form class="cliente-form-sections" method="post" action="bd_update/clientes-actualizar.php" novalidate data-ofima-activa="<?= $ofimaActiva ? '1' : '0' ?>">
  <input type="hidden" name="id" value="<?= $clienteId ?>">
  <input type="hidden" value="<?= $resultadoD['cli_categoria'] ?>" name="categoriaActual">

  <div class="cliente-form-alert-requeridos" id="clienteAlertRequeridos" role="alert" aria-live="polite"></div>

  <section class="cliente-form-section">
    <h3 class="cliente-form-section-title">Contacto y empresa</h3>
    <p class="cliente-form-section-desc">Datos que el equipo comercial consulta con más frecuencia.<?= $ofimaActiva ? ' Con Ofima activo, los campos marcados con (*) son obligatorios.' : '' ?></p>
    <div class="cliente-form-grid cliente-form-grid-3">
      <div class="cliente-field cliente-field-full" data-required-field="nombre">
        <label class="cliente-field-label">Nombre (*)</label>
        <input type="text" class="cliente-field-input" name="nombre" value="<?= htmlspecialchars($resultadoD['cli_nombre']) ?>" style="text-transform:uppercase;" required data-label="Nombre">
        <span class="cliente-field-error">El nombre es obligatorio.</span>
      </div>
      <div class="cliente-field cliente-field-full" data-required-field="<?= $ofimaActiva ? 'email' : '' ?>">
        <label class="cliente-field-label">Email<?= $reqOfima ?></label>
        <input type="email" class="cliente-field-input" name="email" value="<?= htmlspecialchars($resultadoD['cli_email']) ?>" style="text-transform:lowercase;"<?= $ofimaActiva ? ' required' : '' ?> data-label="Email">
        <span class="cliente-field-error">El email es obligatorio.</span>
      </div>
      <div class="cliente-field" data-required-field="<?= $ofimaActiva ? 'telefono' : '' ?>">
        <label class="cliente-field-label">Teléfono<?= $reqOfima ?></label>
        <input type="text" class="cliente-field-input" name="telefono" value="<?= htmlspecialchars($resultadoD['cli_telefono']) ?>"<?= $ofimaActiva ? ' required' : '' ?> data-label="Teléfono">
        <span class="cliente-field-error">El teléfono es obligatorio.</span>
      </div>
      <div class="cliente-field" data-required-field="<?= $ofimaActiva ? 'celular' : '' ?>">
        <label class="cliente-field-label">Celular<?= $reqOfima ?></label>
        <input type="text" class="cliente-field-input" name="celular" value="<?= htmlspecialchars($resultadoD['cli_celular']) ?>" maxlength="10"<?= $ofimaActiva ? ' required' : '' ?> data-label="Celular">
        <span class="cliente-field-hint">10 dígitos sin espacios.</span>
        <span class="cliente-field-error">El celular es obligatorio (10 dígitos).</span>
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
      <div class="cliente-field" data-required-field="<?= $ofimaActiva ? 'tipoDocumento' : '' ?>">
        <label class="cliente-field-label">Tipo de documento<?= $reqOfima ?></label>
        <select data-placeholder="Escoja una opción..." class="chzn-select cliente-field-input" tabindex="2" name="tipoDocumento"<?= $ofimaActiva ? ' required' : '' ?> data-label="Tipo de documento">
          <option value=""></option>
          <option value="2" <?php if ($resultadoD['cli_tipo_documento'] == 2) echo 'selected'; ?>>NIT</option>
          <option value="3" <?php if ($resultadoD['cli_tipo_documento'] == 3) echo 'selected'; ?>>Cédula</option>
        </select>
        <span class="cliente-field-error">Seleccione NIT o Cédula.</span>
      </div>
      <div class="cliente-field" data-required-field="<?= $ofimaActiva ? 'usuarioCliente' : '' ?>">
        <label class="cliente-field-label">Documento<?= $reqOfima ?></label>
        <input type="text" class="cliente-field-input" name="usuarioCliente" value="<?= htmlspecialchars($resultadoD['cli_usuario']) ?>" autocomplete="off" placeholder="Documento"<?= $ofimaActiva ? ' required' : '' ?> data-label="Documento">
        <span class="cliente-field-error">El documento es obligatorio.</span>
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">Usuario de acceso</label>
        <input type="text" class="cliente-field-input" value="<?= htmlspecialchars($resultadoD['cli_usuario_acceso']) ?>" <?= $soloLecturaUsuarioAcceso ?> name="usuarioAcceso" autocomplete="off">
      </div>
      <div class="cliente-field">
        <label class="cliente-field-label">Contraseña</label>
        <input type="<?= $campoC ?>" class="cliente-field-input" name="claveCliente" value="<?= htmlspecialchars($resultadoD['cli_clave']) ?>" autocomplete="off">
      </div>
      <div class="cliente-field" data-required-field="claveDocumentos">
        <label class="cliente-field-label">Clave documentos (*)</label>
        <input type="<?= $campoC ?>" class="cliente-field-input" name="claveDocumentos" value="<?= htmlspecialchars($resultadoD['cli_clave_documentos']) ?>" autocomplete="off" required data-label="Clave documentos">
        <span class="cliente-field-error">La clave de documentos es obligatoria.</span>
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
      <div id="local" class="cliente-field" style="<?= $displayCol ?>" data-required-field="<?= $ofimaActiva ? 'ciudad' : '' ?>">
        <label class="cliente-field-label">Ciudad<?= $reqOfima ?></label>
        <select data-placeholder="Escoja una opción..." class="chzn-select cliente-field-input" tabindex="2" name="ciudad"<?= $ofimaActiva ? ' required' : '' ?> data-label="Ciudad">
          <option value=""></option>
          <?php foreach ($listaCiudades as $ciudadItem) { ?>
            <option value="<?= $ciudadItem['ciu_id'] ?>" <?php if ($resultadoD['cli_ciudad'] == $ciudadItem['ciu_id']) echo 'selected'; ?>>
              <?= htmlspecialchars($ciudadItem['ciu_nombre'] . ', ' . $ciudadItem['dep_nombre']) ?>
            </option>
          <?php } ?>
        </select>
        <span class="cliente-field-error">Debe seleccionar una ciudad.</span>
      </div>
      <div id="extrangero" class="cliente-field cliente-field-full" style="<?= $displayExtr ?>">
        <label class="cliente-field-label">Ciudad (extranjero)</label>
        <input type="text" class="cliente-field-input" name="ciuExtra" value="<?= htmlspecialchars($resultadoD['cli_ciudad_extranjera']) ?>">
      </div>
      <div class="cliente-field cliente-field-full" data-required-field="<?= $ofimaActiva ? 'direccion' : '' ?>" id="campoDireccionCliente">
        <label class="cliente-field-label">Dirección<?= $reqOfima ?></label>
        <?php include __DIR__ . '/cliente-direccion-nomenclatura-campos.php'; ?>
        <span class="cliente-field-error">Complete la dirección (al menos tipo de vía y número).</span>
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
  var alertBox = document.getElementById('clienteAlertRequeridos');
  if (!form || !btn) return;

  var ofimaActiva = form.getAttribute('data-ofima-activa') === '1';
  var enviando = false;

  function valorCampo(el) {
    if (!el) return '';
    return String(el.value || '').trim();
  }

  function marcarInvalido(fieldEl, mensaje) {
    if (!fieldEl) return;
    fieldEl.classList.add('is-invalid');
    var err = fieldEl.querySelector('.cliente-field-error');
    if (err && mensaje) err.textContent = mensaje;
    var nomenclatura = fieldEl.querySelector('.cliente-direccion-nomenclatura');
    if (nomenclatura) nomenclatura.classList.add('is-invalid');
  }

  function limpiarInvalidos() {
    form.querySelectorAll('.cliente-field.is-invalid').forEach(function (el) {
      el.classList.remove('is-invalid');
    });
    form.querySelectorAll('.cliente-direccion-nomenclatura.is-invalid').forEach(function (el) {
      el.classList.remove('is-invalid');
    });
    if (alertBox) {
      alertBox.classList.remove('is-visible');
      alertBox.innerHTML = '';
    }
  }

  function focoYScroll(fieldEl) {
    if (!fieldEl) return;
    fieldEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    var input = fieldEl.querySelector('input:not([disabled]), select:not([disabled]), textarea:not([disabled])');
    if (!input) return;
    setTimeout(function () {
      try {
        if (window.jQuery && jQuery(input).hasClass('chzn-select')) {
          jQuery(input).trigger('chosen:open');
          var chosenSearch = jQuery(input).next('.chzn-container').find('input').get(0);
          if (chosenSearch) chosenSearch.focus();
          else input.focus();
        } else {
          input.focus();
        }
      } catch (err) {
        input.focus();
      }
    }, 280);
  }

  function direccionCompleta() {
    var op1 = valorCampo(form.querySelector('[name="op1"]'));
    var op2 = valorCampo(form.querySelector('[name="op2"]'));
    return op1 !== '' && op2 !== '';
  }

  function validarFormulario() {
    limpiarInvalidos();
    var faltantes = [];
    var primerInvalido = null;

    function registrar(fieldSelector, ok, mensaje, etiqueta) {
      if (ok) return;
      var fieldEl = typeof fieldSelector === 'string'
        ? form.querySelector(fieldSelector)
        : fieldSelector;
      marcarInvalido(fieldEl, mensaje);
      faltantes.push(etiqueta || mensaje);
      if (!primerInvalido) primerInvalido = fieldEl;
    }

    registrar('[data-required-field="nombre"]', valorCampo(form.nombre) !== '', 'El nombre es obligatorio.', 'Nombre');
    registrar('[data-required-field="claveDocumentos"]', valorCampo(form.claveDocumentos) !== '', 'La clave de documentos es obligatoria.', 'Clave documentos');

    if (ofimaActiva) {
      var tipoDoc = valorCampo(form.tipoDocumento);
      registrar('[data-required-field="tipoDocumento"]', tipoDoc === '2' || tipoDoc === '3', 'Seleccione NIT o Cédula.', 'Tipo de documento');
      registrar('[data-required-field="usuarioCliente"]', valorCampo(form.usuarioCliente) !== '', 'El documento es obligatorio.', 'Documento');
      registrar('[data-required-field="email"]', valorCampo(form.email) !== '', 'El email es obligatorio.', 'Email');
      if (valorCampo(form.email) !== '' && form.email && typeof form.email.checkValidity === 'function' && !form.email.checkValidity()) {
        registrar('[data-required-field="email"]', false, 'Ingrese un email válido.', 'Email válido');
      }
      registrar('[data-required-field="telefono"]', valorCampo(form.telefono) !== '', 'El teléfono es obligatorio.', 'Teléfono');
      var celular = valorCampo(form.celular);
      registrar('[data-required-field="celular"]', /^\d{10}$/.test(celular), 'El celular es obligatorio (10 dígitos).', 'Celular');
      registrar('[data-required-field="ciudad"]', valorCampo(form.ciudad) !== '', 'Debe seleccionar una ciudad.', 'Ciudad');
      registrar('[data-required-field="direccion"]', direccionCompleta(), 'Complete la dirección (tipo de vía y número).', 'Dirección');
    } else if (valorCampo(form.email) !== '' && form.email && typeof form.email.checkValidity === 'function' && !form.email.checkValidity()) {
      var emailWrap = form.email.closest ? form.email.closest('.cliente-field') : null;
      marcarInvalido(emailWrap, 'Ingrese un email válido.');
      faltantes.push('Email válido');
      if (!primerInvalido) primerInvalido = emailWrap;
    }

    if (faltantes.length) {
      if (alertBox) {
        alertBox.innerHTML =
          '<strong>Faltan campos obligatorios</strong>' +
          'Complete o corrija lo siguiente y vuelva a guardar:' +
          '<ul>' + faltantes.map(function (f) { return '<li>' + f + '</li>'; }).join('') + '</ul>';
        alertBox.classList.add('is-visible');
        alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      setTimeout(function () { focoYScroll(primerInvalido); }, 200);
      return false;
    }
    return true;
  }

  form.addEventListener('submit', function (e) {
    if (enviando) {
      e.preventDefault();
      return;
    }
    if (!validarFormulario()) {
      e.preventDefault();
      return;
    }
    enviando = true;
    btn.disabled = true;
    btn.innerHTML = '<i class="icon-spinner icon-spin"></i> <span class="btn-text">Guardando...</span>';
  });

  form.addEventListener('input', function (e) {
    var field = e.target && e.target.closest ? e.target.closest('.cliente-field') : null;
    if (field) field.classList.remove('is-invalid');
  });
  form.addEventListener('change', function (e) {
    var field = e.target && e.target.closest ? e.target.closest('.cliente-field') : null;
    if (field) field.classList.remove('is-invalid');
    if (e.target && (e.target.name === 'op1' || e.target.name === 'op2')) {
      var dir = document.getElementById('campoDireccionCliente');
      if (dir) dir.classList.remove('is-invalid');
    }
  });
})();
</script>
