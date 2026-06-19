<?php
require_once RUTA_PROYECTO . '/usuarios/class/Etiqueta.php';
$listaEtiquetasCliente = Etiqueta::listarPorModulo(Etiqueta::MODULO_CLIENTE, $idEmpresa, $conexionBdPrincipal);
$etiquetasClienteSeleccionadas = [];
?>
<style>
#drawerCrearClienteOverlay {
  position: fixed !important;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  height: 100%;
  background: rgba(15, 23, 42, 0.45);
  z-index: 99998;
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
  transition: opacity 0.3s ease, visibility 0.3s ease;
}

#drawerCrearClienteOverlay.is-open {
  opacity: 1;
  visibility: visible;
  pointer-events: auto;
}

#drawerCrearCliente {
  position: fixed !important;
  top: 0;
  right: 0;
  left: auto;
  bottom: 0;
  width: 50vw;
  max-width: 50vw;
  height: 100vh;
  height: 100dvh;
  margin: 0;
  padding: 0;
  border: none;
  background: #fff;
  z-index: 99999;
  display: flex;
  flex-direction: column;
  box-sizing: border-box;
  box-shadow: -8px 0 32px rgba(15, 23, 42, 0.15);
  transform: translateX(100%);
  transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}

#drawerCrearCliente.is-open {
  transform: translateX(0);
}

body.drawer-open {
  overflow: hidden !important;
}

#drawerCrearCliente .drawer-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid #e2e8f0;
  background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
  color: #fff;
  flex-shrink: 0;
}

#drawerCrearCliente .drawer-header-title {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 1.25rem;
  font-weight: 700;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.625rem;
}

#drawerCrearCliente .drawer-close {
  background: rgba(255, 255, 255, 0.15);
  border: none;
  color: #fff;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.125rem;
}

#drawerCrearCliente .drawer-body {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 1.25rem 1.5rem;
}

#drawerCrearCliente .drawer-intro {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.875rem;
  color: #64748b;
  margin: 0 0 1.25rem 0;
  line-height: 1.5;
  max-width: 720px;
}

#drawerCrearCliente .drawer-form {
  max-width: 720px;
}

#drawerCrearCliente .drawer-form .form-group {
  margin-bottom: 1rem;
}

#drawerCrearCliente .drawer-form label {
  display: block;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.75rem;
  font-weight: 600;
  color: #334155;
  margin-bottom: 0.3rem;
  letter-spacing: 0.01em;
}

#drawerCrearCliente .drawer-form label .required {
  color: #dc2626;
}

#drawerCrearCliente .drawer-form input,
#drawerCrearCliente .drawer-form select,
#drawerCrearCliente .drawer-form textarea {
  width: 100%;
  max-width: 100%;
  margin-bottom: 0 !important;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.875rem !important;
  line-height: 1.4 !important;
  border: 1px solid #cbd5e1;
  border-radius: 6px;
  color: #0f172a;
  background-color: #fff;
  box-sizing: border-box;
  vertical-align: middle;
}

#drawerCrearCliente .drawer-form input,
#drawerCrearCliente .drawer-form select {
  height: 38px !important;
  min-height: 38px;
  padding: 0 0.75rem !important;
}

#drawerCrearCliente .drawer-form textarea {
  min-height: 96px;
  padding: 0.625rem 0.75rem !important;
  resize: vertical;
}

#drawerCrearCliente .drawer-form .drawer-select-wrap {
  position: relative;
  display: block;
  width: 100%;
}

#drawerCrearCliente .drawer-form .drawer-select-wrap select {
  padding-right: 2.25rem !important;
  cursor: pointer;
  -webkit-appearance: none !important;
  -moz-appearance: none !important;
  appearance: none !important;
  background-color: #fff !important;
  background-image: none !important;
}

#drawerCrearCliente .drawer-form .drawer-select-wrap .drawer-select-icon {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
  color: #64748b;
  font-size: 0.75rem;
  line-height: 1;
}

#drawerCrearCliente .drawer-form select::-ms-expand {
  display: none;
}

#drawerCrearCliente .drawer-form select option {
  font-size: 0.875rem;
  line-height: 1.4;
  padding: 0.25rem;
}

#drawerCrearCliente .drawer-form .drawer-select-wrap select:focus + .drawer-select-icon {
  color: #7c3aed;
}

#drawerCrearCliente .drawer-form input:focus,
#drawerCrearCliente .drawer-form select:focus,
#drawerCrearCliente .drawer-form textarea:focus {
  outline: none;
  border-color: #7c3aed;
  box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
}

#drawerCrearCliente .drawer-form input.is-invalid,
#drawerCrearCliente .drawer-form select.is-invalid,
#drawerCrearCliente .drawer-form textarea.is-invalid {
  border-color: #dc2626;
}

#drawerCrearCliente .drawer-form .field-hint {
  font-size: 0.75rem;
  color: #94a3b8;
  margin-top: 0.25rem;
}

#drawerCrearCliente .drawer-form .field-error {
  font-size: 0.75rem;
  color: #dc2626;
  margin-top: 0.25rem;
  display: none;
}

#drawerCrearCliente .drawer-form .field-error.is-visible {
  display: block;
}

#drawerCrearCliente .drawer-form .form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.875rem;
}

#drawerCrearCliente .drawer-form .drawer-etiquetas-group {
  margin-bottom: 1rem;
}

#drawerCrearCliente .drawer-form .drawer-etiquetas-group .cliente-etiquetas-selector {
  margin-top: 0.35rem;
}

#drawerCrearCliente .drawer-form .drawer-etiquetas-group .cliente-etiquetas-leyenda {
  margin-bottom: 0.35rem;
}

#drawerCrearCliente .drawer-nota-interna-group .cliente-notas-tipo {
  margin-top: 0.65rem;
}

#drawerCrearCliente .drawer-nota-interna-group .cliente-notas-panel textarea {
  margin-top: 0.5rem;
}

#drawerCrearCliente .drawer-footer {
  padding: 0.875rem 1.5rem;
  border-top: 1px solid #e2e8f0;
  background: #f8fafc;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 0.75rem;
  flex-shrink: 0;
}

#drawerCrearCliente .drawer-btn {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.875rem;
  font-weight: 600;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

#drawerCrearCliente .drawer-btn-secondary {
  background: #fff;
  color: #475569;
  border: 1px solid #cbd5e1;
}

#drawerCrearCliente .drawer-btn-primary {
  background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
  color: #fff;
  min-width: 140px;
  justify-content: center;
}

#drawerCrearCliente .drawer-btn-primary:hover:not(:disabled) {
  background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
  color: #fff;
}

#drawerCrearCliente .drawer-btn-primary:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

#drawerCrearCliente .drawer-alert {
  padding: 0.875rem 1rem;
  border-radius: 8px;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.875rem;
  margin-bottom: 1.25rem;
  display: none;
}

#drawerCrearCliente .drawer-alert.is-visible {
  display: block;
}

#drawerCrearCliente .drawer-alert-success {
  background: #f0fdf4;
  color: #166534;
  border: 1px solid #bbf7d0;
}

#drawerCrearCliente .drawer-alert-error {
  background: #fef2f2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

#drawerCrearCliente .drawer-success-actions {
  display: flex;
  gap: 0.75rem;
  margin-top: 0.75rem;
}

#drawerCrearCliente .drawer-success-actions a,
#drawerCrearCliente .drawer-success-actions button {
  font-size: 0.8125rem;
  font-weight: 600;
  color: #166534;
  background: none;
  border: none;
  cursor: pointer;
  text-decoration: underline;
  padding: 0;
}

#drawerCrearCliente .drawer-spinner {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: drawer-spin 0.6s linear infinite;
}

@keyframes drawer-spin {
  to { transform: rotate(360deg); }
}

#drawerCrearCliente .drawer-form.is-hidden,
#drawerCrearCliente .drawer-intro.is-hidden {
  display: none;
}

@media (max-width: 640px) {
  #drawerCrearCliente {
    width: 100vw;
    max-width: 100vw;
  }

  #drawerCrearCliente .drawer-form .form-row {
    grid-template-columns: 1fr;
  }
}
</style>

<div id="drawerCrearClienteOverlay" aria-hidden="true"></div>
<aside id="drawerCrearCliente" role="dialog" aria-labelledby="drawerCrearClienteTitulo" aria-modal="true">
  <div class="drawer-header">
    <h2 class="drawer-header-title" id="drawerCrearClienteTitulo">
      <i class="fa fa-user-plus" aria-hidden="true"></i>
      Crear cliente
    </h2>
    <button type="button" class="drawer-close" id="btnCerrarDrawerCliente" aria-label="Cerrar">
      <i class="fa fa-times" aria-hidden="true"></i>
    </button>
  </div>

  <div class="drawer-body">
    <p class="drawer-intro">Complete los datos básicos para registrar un cliente de forma rápida. Podrá completar el resto de la información después.</p>

    <div class="drawer-alert drawer-alert-success" id="drawerClienteExito" role="status"></div>
    <div class="drawer-alert drawer-alert-error" id="drawerClienteError" role="alert"></div>

    <form class="drawer-form" id="formCrearClienteRapido" novalidate>
      <div class="form-group">
        <label for="cliRapidoNombre">Nombre <span class="required">*</span></label>
        <input type="text" id="cliRapidoNombre" name="nombre" autocomplete="organization" style="text-transform:uppercase;" required>
        <span class="field-error" id="errorNombre">El nombre es obligatorio.</span>
      </div>

      <div class="form-group">
        <label for="cliRapidoEmail">Email</label>
        <input type="email" id="cliRapidoEmail" name="email" autocomplete="email" style="text-transform:lowercase;">
        <span class="field-error" id="errorEmail">Ingrese un email válido.</span>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="cliRapidoTelefono">Teléfono</label>
          <input type="tel" id="cliRapidoTelefono" name="telefono" autocomplete="tel">
        </div>
        <div class="form-group">
          <label for="cliRapidoCelular">Celular</label>
          <input type="tel" id="cliRapidoCelular" name="celular" maxlength="10" inputmode="numeric" pattern="[0-9]{10}">
          <span class="field-hint">10 dígitos sin espacios ni puntos.</span>
          <span class="field-error" id="errorCelular">El celular debe tener 10 dígitos.</span>
        </div>
      </div>

      <div class="form-group">
        <label for="cliRapidoReferencia">Referencia de llegada</label>
        <div class="drawer-select-wrap">
          <select id="cliRapidoReferencia" name="referencia">
            <option value="">Seleccione una opción...</option>
            <?php for ($i = 1; $i <= 12; $i++) { ?>
              <option value="<?= $i ?>"><?= htmlspecialchars($referenciaLlegada[$i]) ?></option>
            <?php } ?>
          </select>
          <i class="fa fa-chevron-down drawer-select-icon" aria-hidden="true"></i>
        </div>
      </div>

      <div class="form-group" id="grupoNombreEvento" style="display:none;">
        <label for="cliRapidoNombreEvento">Nombre del evento</label>
        <input type="text" id="cliRapidoNombreEvento" name="nombreEvento">
      </div>

      <div class="form-group">
        <label for="cliRapidoAsesor">Asesor asociado</label>
        <div class="drawer-select-wrap">
          <select id="cliRapidoAsesor" name="asesor">
            <option value="">Seleccione un asesor...</option>
            <?php
            $consultaAsesores = $conexionBdPrincipal->query(
              "SELECT usr_id, usr_nombre FROM usuarios
               WHERE usr_bloqueado != 1 AND usr_id_empresa = '" . $idEmpresa . "'
               ORDER BY usr_nombre"
            );
            while ($asesor = mysqli_fetch_array($consultaAsesores, MYSQLI_BOTH)) {
              $selected = ($asesor['usr_id'] == $_SESSION['id']) ? 'selected' : '';
            ?>
              <option value="<?= $asesor['usr_id'] ?>" <?= $selected ?>><?= htmlspecialchars(strtoupper($asesor['usr_nombre'])) ?></option>
            <?php } ?>
          </select>
          <i class="fa fa-chevron-down drawer-select-icon" aria-hidden="true"></i>
        </div>
        <span class="field-hint">Por defecto se asigna el usuario actual.</span>
      </div>

      <div class="form-group drawer-etiquetas-group">
        <label>Etiquetas comerciales <span class="field-hint" style="display:inline; margin-left:0.25rem;">(opcional)</span></label>
        <span class="field-hint">Clasifique al cliente con una o más etiquetas visibles en todo el CRM.</span>
        <?php include __DIR__ . '/cliente-etiquetas-selector.php'; ?>
      </div>

      <div class="form-group drawer-nota-interna-group">
        <label>Nota interna <span class="field-hint" style="display:inline; margin-left:0.25rem;">(opcional)</span></label>
        <span class="field-hint">Quedará registrada en el historial del cliente.</span>
        <?php include __DIR__ . '/cliente-notas-tipo-selector.php'; ?>

        <div class="cliente-notas-panel js-notas-panel-texto">
          <textarea id="cliRapidoNotaInterna" name="notaInterna" rows="4" maxlength="5000" placeholder="Observaciones internas sobre el cliente..."></textarea>
        </div>

        <div class="cliente-notas-panel js-notas-panel-voz" hidden>
          <?php $vozRootId = 'notasVozCrearCliente'; $vozEmbebido = true; include __DIR__ . '/cliente-notas-voz-bloque.php'; ?>
        </div>
      </div>
    </form>
  </div>

  <div class="drawer-footer">
    <button type="button" class="drawer-btn drawer-btn-secondary" id="btnCancelarDrawerCliente">Cancelar</button>
    <button type="submit" form="formCrearClienteRapido" class="drawer-btn drawer-btn-primary" id="btnGuardarClienteRapido">
      <i class="fa fa-save" aria-hidden="true"></i>
      <span class="drawer-btn-label">Guardar</span>
    </button>
  </div>
</aside>

<script src="js/cliente-notas-voz.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var overlay    = document.getElementById('drawerCrearClienteOverlay');
  var drawer     = document.getElementById('drawerCrearCliente');
  var form       = document.getElementById('formCrearClienteRapido');
  var btnAbrir   = document.getElementById('btnCrearClienteRapido');
  var btnCerrar  = document.getElementById('btnCerrarDrawerCliente');
  var btnCancel  = document.getElementById('btnCancelarDrawerCliente');
  var btnGuardar = document.getElementById('btnGuardarClienteRapido');
  var alertExito = document.getElementById('drawerClienteExito');
  var alertError = document.getElementById('drawerClienteError');
  var drawerIntro = drawer ? drawer.querySelector('.drawer-intro') : null;
  var referencia = document.getElementById('cliRapidoReferencia');
  var grupoEvento = document.getElementById('grupoNombreEvento');
  var nombreInput = document.getElementById('cliRapidoNombre');
  var notaTextarea = document.getElementById('cliRapidoNotaInterna');
  var guardando = false;
  var widgetVozCrear = null;
  var selectorTipoCrear = null;

  function setGuardando(estado) {
    guardando = estado;
    if (btnGuardar) btnGuardar.disabled = estado;
    if (btnCerrar) btnCerrar.disabled = estado;
    if (btnCancel) btnCancel.disabled = estado;
    if (!btnGuardar) return;
    var label = btnGuardar.querySelector('.drawer-btn-label');
    if (!label) return;
    var spinner = btnGuardar.querySelector('.drawer-spinner');
    if (estado) {
      label.textContent = 'Guardando...';
      if (!spinner) {
        spinner = document.createElement('span');
        spinner.className = 'drawer-spinner';
        spinner.setAttribute('aria-hidden', 'true');
        btnGuardar.insertBefore(spinner, label);
      }
    } else {
      label.textContent = 'Guardar';
      if (spinner) spinner.remove();
    }
  }

  function mostrarFormulario(estado) {
    if (form) form.classList.toggle('is-hidden', !estado);
    if (drawerIntro) drawerIntro.classList.toggle('is-hidden', !estado);
    if (btnGuardar) btnGuardar.style.display = estado ? '' : 'none';
  }

  if (typeof ClienteNotasVoz !== 'undefined') {
    widgetVozCrear = new ClienteNotasVoz({
      rootId: 'notasVozCrearCliente',
      getClienteId: function () { return 0; },
      onSaved: function () {},
      onError: function (msg) {
        alertError.textContent = msg;
        alertError.classList.add('is-visible');
      }
    });
  }

  if (window.ClienteNotasVozUtil && typeof ClienteNotasVozUtil.bindNotasTipoSelector === 'function') {
    selectorTipoCrear = ClienteNotasVozUtil.bindNotasTipoSelector({
      root: form,
      widgetVoz: widgetVozCrear
    });
  }

  if (!overlay || !drawer || !btnAbrir) return;

  document.body.appendChild(overlay);
  document.body.appendChild(drawer);

  function ocultarAlertas() {
    alertExito.classList.remove('is-visible');
    alertError.classList.remove('is-visible');
    alertExito.innerHTML = '';
    alertError.textContent = '';
  }

  function limpiarErrores() {
    form.querySelectorAll('.is-invalid').forEach(function (el) {
      el.classList.remove('is-invalid');
    });
    form.querySelectorAll('.field-error.is-visible').forEach(function (el) {
      el.classList.remove('is-visible');
    });
  }

  function resetEtiquetasVisuales() {
    form.querySelectorAll('.cliente-etiqueta-option input[type="checkbox"]').forEach(function (input) {
      input.checked = false;
      var option = input.closest('.cliente-etiqueta-option');
      if (option) option.classList.remove('is-active');
    });
  }

  function resetNotaInterna() {
    if (widgetVozCrear) widgetVozCrear.reset();
    if (selectorTipoCrear) selectorTipoCrear.resetModo();
    if (notaTextarea) notaTextarea.value = '';
  }

  function abrirDrawer() {
    ocultarAlertas();
    limpiarErrores();
    setGuardando(false);
    mostrarFormulario(true);
    form.reset();
    resetEtiquetasVisuales();
    resetNotaInterna();
    grupoEvento.style.display = 'none';
    overlay.classList.add('is-open');
    drawer.classList.add('is-open');
    document.body.classList.add('drawer-open');
    overlay.setAttribute('aria-hidden', 'false');
    setTimeout(function () { nombreInput.focus(); }, 350);
  }

  function cerrarDrawer() {
    if (guardando) return;
    if (widgetVozCrear && (widgetVozCrear.estadoGrabacion === 'recording' || widgetVozCrear.estadoGrabacion === 'paused')) {
      if (!window.confirm('Hay una grabación en curso. ¿Desea cerrar y descartarla?')) return;
    }
    if (widgetVozCrear) widgetVozCrear.reset();
    setGuardando(false);
    mostrarFormulario(true);
    ocultarAlertas();
    overlay.classList.remove('is-open');
    drawer.classList.remove('is-open');
    document.body.classList.remove('drawer-open');
    overlay.setAttribute('aria-hidden', 'true');
    btnAbrir.focus();
  }

  function validarFormulario() {
    limpiarErrores();
    var valido = true;
    var nombre = nombreInput.value.trim();
    var email = document.getElementById('cliRapidoEmail').value.trim();
    var celular = document.getElementById('cliRapidoCelular').value.trim();

    if (!nombre) {
      nombreInput.classList.add('is-invalid');
      document.getElementById('errorNombre').classList.add('is-visible');
      valido = false;
    }

    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      document.getElementById('cliRapidoEmail').classList.add('is-invalid');
      document.getElementById('errorEmail').classList.add('is-visible');
      valido = false;
    }

    if (celular && !/^\d{10}$/.test(celular)) {
      document.getElementById('cliRapidoCelular').classList.add('is-invalid');
      document.getElementById('errorCelular').classList.add('is-visible');
      valido = false;
    }

    return valido;
  }

  function mostrarExito(data) {
    setGuardando(false);
    limpiarErrores();
    form.reset();
    resetEtiquetasVisuales();
    resetNotaInterna();
    grupoEvento.style.display = 'none';
    mostrarFormulario(false);

    alertError.classList.remove('is-visible');
    alertExito.classList.add('is-visible');
    alertExito.innerHTML =
      '<strong>' + data.message + '</strong>' +
      '<div class="drawer-success-actions">' +
        '<a href="' + data.editUrl + '">Ver y completar cliente</a>' +
        '<button type="button" id="btnCrearOtroCliente">Crear otro</button>' +
      '</div>';
    document.getElementById('btnCrearOtroCliente').addEventListener('click', function () {
      ocultarAlertas();
      mostrarFormulario(true);
      setGuardando(false);
      form.reset();
      resetEtiquetasVisuales();
      resetNotaInterna();
      grupoEvento.style.display = 'none';
      nombreInput.focus();
    });
  }

  btnAbrir.addEventListener('click', function (e) {
    e.preventDefault();
    abrirDrawer();
  });

  btnCerrar.addEventListener('click', cerrarDrawer);
  btnCancel.addEventListener('click', cerrarDrawer);
  overlay.addEventListener('click', cerrarDrawer);

  referencia.addEventListener('change', function () {
    grupoEvento.style.display = this.value === '4' ? 'block' : 'none';
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
      cerrarDrawer();
    }
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (guardando || !validarFormulario()) return;

    ocultarAlertas();

    var modoNota = selectorTipoCrear ? selectorTipoCrear.getModo() : 'texto';

    if (modoNota === 'voz') {
      if (widgetVozCrear && (widgetVozCrear.estadoGrabacion === 'recording' || widgetVozCrear.estadoGrabacion === 'paused')) {
        alertError.textContent = 'Finalice la grabación de la nota de voz antes de guardar el cliente.';
        alertError.classList.add('is-visible');
        return;
      }
    }

    setGuardando(true);

    var formData = new FormData(form);
    if (modoNota === 'voz') {
      formData.delete('notaInterna');
    }

    var guardarNotaVoz = modoNota === 'voz' &&
      widgetVozCrear &&
      typeof widgetVozCrear.tieneVozPendiente === 'function' &&
      widgetVozCrear.tieneVozPendiente();

    fetch('ajax/ajax-clientes-crear-rapido.php', {
      method: 'POST',
      body: formData
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (!data.success) {
          alertError.textContent = data.message || 'No se pudo guardar el cliente.';
          alertError.classList.add('is-visible');
          return null;
        }

        if (guardarNotaVoz) {
          return widgetVozCrear.guardar(data.clienteId).then(function (notaOk) {
            if (!notaOk) {
              data.message += ' La nota de voz no pudo guardarse.';
              data.notaGuardada = false;
            } else {
              data.notaGuardada = true;
            }
            return data;
          });
        }

        return data;
      })
      .then(function (data) {
        if (!data) return;
        mostrarExito(data);
      })
      .catch(function () {
        alertError.textContent = 'Error de conexión. Verifique su red e intente nuevamente.';
        alertError.classList.add('is-visible');
      })
      .finally(function () {
        setGuardando(false);
      });
  });
});
</script>
