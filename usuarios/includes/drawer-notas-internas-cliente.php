<style>
#drawerNotasInternasOverlay {
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

#drawerNotasInternasOverlay.is-open {
  opacity: 1;
  visibility: visible;
  pointer-events: auto;
}

#drawerNotasInternas {
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

#drawerNotasInternas.is-open {
  transform: translateX(0);
}

body.drawer-notas-open {
  overflow: hidden !important;
}

#drawerNotasInternas .drawer-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid #e2e8f0;
  background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
  color: #fff;
  flex-shrink: 0;
}

#drawerNotasInternas .drawer-header-title {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 1.125rem;
  font-weight: 700;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
}

#drawerNotasInternas .drawer-header-subtitle {
  font-size: 0.8125rem;
  font-weight: 500;
  opacity: 0.9;
}

#drawerNotasInternas .drawer-close {
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
  flex-shrink: 0;
}

#drawerNotasInternas .drawer-body {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 1.25rem 1.5rem;
}

#drawerNotasInternas .drawer-intro {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.875rem;
  color: #64748b;
  margin: 0 0 1rem 0;
  line-height: 1.5;
}

#drawerNotasInternas .drawer-nota-form label {
  display: block;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.75rem;
  font-weight: 600;
  color: #334155;
  margin-bottom: 0.35rem;
}

#drawerNotasInternas .drawer-nota-form textarea {
  width: 100%;
  min-height: 100px;
  padding: 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.875rem;
  line-height: 1.5;
  box-sizing: border-box;
  resize: vertical;
}

#drawerNotasInternas .drawer-nota-form textarea:focus {
  outline: none;
  border-color: #7c3aed;
  box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
}

#drawerNotasInternas .drawer-notas-historial-title {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.9375rem;
  font-weight: 700;
  color: #0f172a;
  margin: 1.5rem 0 0.75rem 0;
  padding-top: 1.25rem;
  border-top: 1px solid #e2e8f0;
}

#drawerNotasInternas .drawer-nota-item,
#drawerNotasInternas .cliente-nota-item {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-left: 4px solid #7c3aed;
  border-radius: 8px;
  padding: 0.875rem 1rem;
  margin-bottom: 0.75rem;
}

#drawerNotasInternas .drawer-nota-meta,
#drawerNotasInternas .cliente-nota-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 0.35rem 0.75rem;
  font-size: 0.75rem;
  color: #64748b;
  margin-bottom: 0.5rem;
}

#drawerNotasInternas .drawer-nota-meta strong,
#drawerNotasInternas .cliente-nota-meta strong {
  color: #334155;
}

#drawerNotasInternas .drawer-nota-texto,
#drawerNotasInternas .cliente-nota-texto {
  font-size: 0.875rem;
  color: #0f172a;
  line-height: 1.55;
  white-space: pre-wrap;
  word-break: break-word;
}

#drawerNotasInternas .drawer-notas-vacio,
#drawerNotasInternas .drawer-notas-cargando {
  color: #64748b;
  font-size: 0.875rem;
  font-style: italic;
  padding: 0.5rem 0;
}

#drawerNotasInternas .drawer-footer {
  padding: 0.875rem 1.5rem;
  border-top: 1px solid #e2e8f0;
  background: #f8fafc;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 0.75rem;
  flex-shrink: 0;
}

#drawerNotasInternas .drawer-btn {
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

#drawerNotasInternas .drawer-btn-secondary {
  background: #fff;
  color: #475569;
  border: 1px solid #cbd5e1;
}

#drawerNotasInternas .drawer-btn-primary {
  background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
  color: #fff;
  min-width: 130px;
  justify-content: center;
}

#drawerNotasInternas .drawer-btn-primary:hover:not(:disabled) {
  background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
  color: #fff;
}

#drawerNotasInternas .drawer-btn-primary:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

#drawerNotasInternas .drawer-alert {
  padding: 0.75rem 1rem;
  border-radius: 8px;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem;
  margin-top: 0.75rem;
  display: none;
}

#drawerNotasInternas .drawer-alert.is-visible {
  display: block;
}

#drawerNotasInternas .drawer-alert-success {
  background: #f0fdf4;
  color: #166534;
  border: 1px solid #bbf7d0;
}

#drawerNotasInternas .drawer-alert-error {
  background: #fef2f2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

#drawerNotasInternas .drawer-spinner {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: drawer-notas-spin 0.6s linear infinite;
}

@keyframes drawer-notas-spin {
  to { transform: rotate(360deg); }
}

@media (max-width: 640px) {
  #drawerNotasInternas {
    width: 100vw;
    max-width: 100vw;
  }
}
</style>

<div id="drawerNotasInternasOverlay" aria-hidden="true"></div>
<aside id="drawerNotasInternas" role="dialog" aria-labelledby="drawerNotasInternasTitulo" aria-modal="true">
  <div class="drawer-header">
    <div class="drawer-header-title" id="drawerNotasInternasTitulo">
      <span><i class="icon-comment" aria-hidden="true"></i> Notas internas</span>
      <span class="drawer-header-subtitle" id="drawerNotasClienteNombre"></span>
    </div>
    <button type="button" class="drawer-close" id="btnCerrarDrawerNotas" aria-label="Cerrar">
      <i class="icon-remove" aria-hidden="true"></i>
    </button>
  </div>

  <div class="drawer-body">
    <p class="drawer-intro">Elija el tipo de nota, complete el contenido y guarde con el botón inferior.</p>

    <form class="drawer-nota-form" id="formNotaInternaDrawer">
      <input type="hidden" name="cliente" id="drawerNotasClienteId" value="">
      <?php include __DIR__ . '/cliente-notas-tipo-selector.php'; ?>

      <div class="cliente-notas-panel js-notas-panel-texto" id="drawerPanelNotaTexto">
        <label for="drawerNotaTexto">Contenido de la nota</label>
        <textarea name="nota" id="drawerNotaTexto" placeholder="Escriba una nota interna..." maxlength="5000"></textarea>
      </div>

      <div class="cliente-notas-panel js-notas-panel-voz" id="drawerPanelNotaVoz" hidden>
        <?php $vozRootId = 'notasVozDrawer'; $vozEmbebido = true; include __DIR__ . '/cliente-notas-voz-bloque.php'; ?>
      </div>

      <div class="drawer-alert drawer-alert-success" id="drawerNotaExito"></div>
      <div class="drawer-alert drawer-alert-error" id="drawerNotaError"></div>
    </form>

    <h3 class="drawer-notas-historial-title">Historial <span id="drawerNotasTotal"></span></h3>
    <div id="drawerNotasLista">
      <p class="drawer-notas-cargando">Cargando notas...</p>
    </div>
  </div>

  <div class="drawer-footer">
    <button type="button" class="drawer-btn drawer-btn-secondary" id="btnCancelarDrawerNotas">Cerrar</button>
    <button type="submit" form="formNotaInternaDrawer" class="drawer-btn drawer-btn-primary" id="btnGuardarNotaDrawer">
      <i class="icon-save" aria-hidden="true"></i>
      <span>Guardar nota</span>
    </button>
  </div>
</aside>

<script src="js/cliente-notas-voz.js"></script>
<script>
(function () {
  var overlay   = document.getElementById('drawerNotasInternasOverlay');
  var drawer    = document.getElementById('drawerNotasInternas');
  var form      = document.getElementById('formNotaInternaDrawer');
  var btnCerrar = document.getElementById('btnCerrarDrawerNotas');
  var btnCancel = document.getElementById('btnCancelarDrawerNotas');
  var btnGuardar = document.getElementById('btnGuardarNotaDrawer');
  var inputCliente = document.getElementById('drawerNotasClienteId');
  var labelCliente = document.getElementById('drawerNotasClienteNombre');
  var textarea  = document.getElementById('drawerNotaTexto');
  var lista     = document.getElementById('drawerNotasLista');
  var totalLbl  = document.getElementById('drawerNotasTotal');
  var alertExito = document.getElementById('drawerNotaExito');
  var alertError = document.getElementById('drawerNotaError');

  if (!overlay || !drawer || !form) return;

  document.body.appendChild(overlay);
  document.body.appendChild(drawer);

  var guardando = false;
  var clienteActualId = 0;
  var widgetVoz = null;
  var selectorTipo = null;

  if (typeof ClienteNotasVoz !== 'undefined') {
    widgetVoz = new ClienteNotasVoz({
      rootId: 'notasVozDrawer',
      getClienteId: function () { return clienteActualId; },
      onSaving: function (activo) { setGuardando(activo); },
      onSaved: function (data) {
        alertExito.textContent = data.message;
        alertExito.classList.add('is-visible');
        return cargarNotas(clienteActualId);
      },
      onError: function (msg) {
        alertError.textContent = msg;
        alertError.classList.add('is-visible');
      }
    });
  }

  if (window.ClienteNotasVozUtil && typeof ClienteNotasVozUtil.bindNotasTipoSelector === 'function') {
    selectorTipo = ClienteNotasVozUtil.bindNotasTipoSelector({
      root: form,
      widgetVoz: widgetVoz,
      onModoChange: function (modo) {
        setTimeout(function () {
          if (modo === 'texto') textarea.focus();
        }, 50);
      }
    });
  }

  function escaparHtml(texto) {
    var div = document.createElement('div');
    div.textContent = texto;
    return div.innerHTML;
  }

  function nl2br(texto) {
    return escaparHtml(texto).replace(/\n/g, '<br>');
  }

  function ocultarAlertas() {
    alertExito.classList.remove('is-visible');
    alertError.classList.remove('is-visible');
    alertExito.textContent = '';
    alertError.textContent = '';
  }

  function setGuardando(estado) {
    guardando = estado;
    btnGuardar.disabled = estado;
    btnCerrar.disabled = estado;
    btnCancel.disabled = estado;
    var label = btnGuardar.querySelector('span');
    if (estado) {
      label.textContent = 'Guardando...';
      if (!btnGuardar.querySelector('.drawer-spinner')) {
        var spinner = document.createElement('span');
        spinner.className = 'drawer-spinner';
        btnGuardar.insertBefore(spinner, label);
      }
    } else {
      label.textContent = 'Guardar nota';
      var sp = btnGuardar.querySelector('.drawer-spinner');
      if (sp) sp.remove();
    }
  }

  function renderNotas(notas) {
    if (!notas || notas.length === 0) {
      lista.innerHTML = '<p class="drawer-notas-vacio">Aún no hay notas internas para este cliente.</p>';
      totalLbl.textContent = '(0)';
      return;
    }

    totalLbl.textContent = '(' + notas.length + ')';
    var html = '';
    notas.forEach(function (nota) {
      html += window.ClienteNotasVozUtil.renderHistorialItem(nota);
    });
    lista.innerHTML = html;
  }

  function cargarNotas(clienteId) {
    lista.innerHTML = '<p class="drawer-notas-cargando">Cargando notas...</p>';
    totalLbl.textContent = '';

    return fetch('ajax/ajax-clientes-nota-interna-listar.php?cliente=' + encodeURIComponent(clienteId))
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (!data.success) {
          throw new Error(data.message || 'No se pudieron cargar las notas.');
        }
        renderNotas(data.notas || []);
        if (data.cliente && data.cliente.nombre) {
          labelCliente.textContent = data.cliente.nombre.toUpperCase();
        }
      });
  }

  function cerrarDrawer() {
    if (guardando) return;
    if (widgetVoz && (widgetVoz.estadoGrabacion === 'recording' || widgetVoz.estadoGrabacion === 'paused')) {
      if (!confirm('Hay una grabación en curso. ¿Desea cerrar y descartarla?')) return;
    }
    if (widgetVoz) widgetVoz.reset();
    overlay.classList.remove('is-open');
    drawer.classList.remove('is-open');
    document.body.classList.remove('drawer-notas-open');
    overlay.setAttribute('aria-hidden', 'true');
    clienteActualId = 0;
  }

  function abrirDrawer(clienteId, clienteNombre) {
    clienteActualId = parseInt(clienteId, 10);
    if (!clienteActualId) return;

    ocultarAlertas();
    form.reset();
    if (widgetVoz) widgetVoz.reset();
    if (selectorTipo) selectorTipo.resetModo();
    inputCliente.value = String(clienteActualId);
    labelCliente.textContent = (clienteNombre || '').toUpperCase();
    lista.innerHTML = '<p class="drawer-notas-cargando">Cargando notas...</p>';
    totalLbl.textContent = '';

    overlay.classList.add('is-open');
    drawer.classList.add('is-open');
    document.body.classList.add('drawer-notas-open');
    overlay.setAttribute('aria-hidden', 'false');

    cargarNotas(clienteActualId)
      .catch(function (err) {
        lista.innerHTML = '<p class="drawer-notas-vacio">' + escaparHtml(err.message) + '</p>';
      });

    setTimeout(function () { textarea.focus(); }, 350);
  }

  window.abrirDrawerNotasInternas = abrirDrawer;

  document.addEventListener('click', function (e) {
    var trigger = e.target.closest('.js-notas-internas-cliente');
    if (!trigger) return;
    e.preventDefault();
    abrirDrawer(trigger.getAttribute('data-cliente-id'), trigger.getAttribute('data-cliente-nombre'));
  });

  btnCerrar.addEventListener('click', cerrarDrawer);
  btnCancel.addEventListener('click', cerrarDrawer);
  overlay.addEventListener('click', cerrarDrawer);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
      cerrarDrawer();
    }
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (guardando || !clienteActualId) return;
    if (widgetVoz && widgetVoz.guardando) return;

    ocultarAlertas();

    var modo = selectorTipo ? selectorTipo.getModo() : 'texto';

    if (modo === 'voz') {
      if (widgetVoz && (widgetVoz.estadoGrabacion === 'recording' || widgetVoz.estadoGrabacion === 'paused')) {
        alertError.textContent = 'Finalice la grabación antes de guardar.';
        alertError.classList.add('is-visible');
        return;
      }
      if (!widgetVoz || !widgetVoz.tieneVozPendiente()) {
        alertError.textContent = 'Grabe y finalice una nota de voz antes de guardar.';
        alertError.classList.add('is-visible');
        return;
      }
      widgetVoz.guardar();
      return;
    }

    var textoNota = textarea.value.trim();
    if (!textoNota) {
      alertError.textContent = 'Escriba el contenido de la nota.';
      alertError.classList.add('is-visible');
      textarea.focus();
      return;
    }

    setGuardando(true);

    fetch('ajax/ajax-clientes-nota-interna-guardar.php', {
      method: 'POST',
      body: new FormData(form)
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        setGuardando(false);
        if (data.success) {
          textarea.value = '';
          alertExito.textContent = data.message;
          alertExito.classList.add('is-visible');
          return cargarNotas(clienteActualId);
        }
        alertError.textContent = data.message || 'No se pudo guardar la nota.';
        alertError.classList.add('is-visible');
      })
      .catch(function () {
        setGuardando(false);
        alertError.textContent = 'Error de conexión. Intente nuevamente.';
        alertError.classList.add('is-visible');
      });
  });
})();
</script>
