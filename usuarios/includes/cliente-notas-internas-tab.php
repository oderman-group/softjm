<?php
require_once RUTA_PROYECTO . '/usuarios/class/ClienteNotaInterna.php';

$clienteIdNotas = intval($_GET['id']);
$notasInternas  = ClienteNotaInterna::listarPorCliente($clienteIdNotas, $idEmpresa, $conexionBdPrincipal);
?>
<style>
.cliente-notas-internas .nota-form textarea {
  width: 100%;
  min-height: 110px;
  padding: 0.75rem;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 0.9375rem;
  line-height: 1.5;
  box-sizing: border-box;
  resize: vertical;
}

.cliente-notas-internas .nota-form textarea:focus {
  outline: none;
  border-color: #7c3aed;
  box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
}

.cliente-notas-internas .notas-lista {
  margin-top: 1.5rem;
}

.cliente-notas-internas .nota-item,
.cliente-notas-internas .cliente-nota-item {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-left: 4px solid #7c3aed;
  border-radius: 8px;
  padding: 1rem 1.125rem;
  margin-bottom: 0.875rem;
}

.cliente-notas-internas .nota-meta,
.cliente-notas-internas .cliente-nota-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem 1rem;
  font-size: 0.8125rem;
  color: #64748b;
  margin-bottom: 0.625rem;
}

.cliente-notas-internas .nota-meta strong,
.cliente-notas-internas .cliente-nota-meta strong {
  color: #334155;
}

.cliente-notas-internas .nota-texto,
.cliente-notas-internas .cliente-nota-texto {
  font-size: 0.9375rem;
  color: #0f172a;
  line-height: 1.55;
  white-space: pre-wrap;
  word-break: break-word;
}

.cliente-notas-internas .notas-vacio {
  color: #64748b;
  font-style: italic;
  padding: 1rem 0;
}

.cliente-notas-internas .nota-alert {
  display: none;
  margin-top: 0.75rem;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  font-size: 0.875rem;
}

.cliente-notas-internas .nota-alert.is-visible {
  display: block;
}

.cliente-notas-internas .nota-alert-success {
  background: #f0fdf4;
  color: #166534;
  border: 1px solid #bbf7d0;
}

.cliente-notas-internas .nota-alert-error {
  background: #fef2f2;
  color: #991b1b;
  border: 1px solid #fecaca;
}
</style>

<div class="row-fluid cliente-notas-internas">
  <div class="span12">
    <div class="content-widgets light-gray">
      <div class="widget-head green">
        <h3>Notas internas</h3>
      </div>
      <div class="widget-container">
        <p>Elija el tipo de nota, complete el contenido y guarde con el botón inferior.</p>

        <form class="nota-form" id="formNotaInternaCliente">
          <input type="hidden" name="cliente" value="<?= $clienteIdNotas ?>">
          <?php include __DIR__ . '/cliente-notas-tipo-selector.php'; ?>

          <div class="cliente-notas-panel js-notas-panel-texto">
            <div class="control-group">
              <label class="control-label">Contenido de la nota</label>
              <div class="controls">
                <textarea name="nota" id="notaInternaCliente" placeholder="Escriba una nota interna..." maxlength="5000"></textarea>
              </div>
            </div>
          </div>

          <div class="cliente-notas-panel js-notas-panel-voz" hidden>
            <?php $vozRootId = 'notasVozTab'; $vozEmbebido = true; include __DIR__ . '/cliente-notas-voz-bloque.php'; ?>
          </div>

          <div class="form-actions" style="padding-left: 0;">
            <button type="submit" class="btn btn-info" id="btnGuardarNotaInterna">
              <i class="icon-save"></i> Guardar nota
            </button>
          </div>
          <div class="nota-alert nota-alert-success" id="notaInternaExito"></div>
          <div class="nota-alert nota-alert-error" id="notaInternaError"></div>
        </form>

        <div class="notas-lista">
          <h4 style="margin-bottom: 0.75rem;">Historial</h4>
          <div id="listaNotasInternas">
            <?php if (empty($notasInternas)) { ?>
              <p class="notas-vacio" id="notasInternasVacio">Aún no hay notas internas para este cliente.</p>
            <?php } else { ?>
              <?php foreach ($notasInternas as $notaItem) {
                  $notaApi = ClienteNotaInterna::formatearNotaParaApi($notaItem);
                  if (!empty($notaApi['audio_url'])) { ?>
                <article class="nota-item cliente-nota-item--voz">
                  <div class="nota-meta">
                    <span><strong><?= htmlspecialchars($notaApi['usuario']) ?></strong></span>
                    <span><?= htmlspecialchars($notaApi['fecha']) ?></span>
                  </div>
                  <div class="cliente-nota-voz-badge">
                    <i class="icon-volume-up"></i> Nota de voz
                    <?php if (!empty($notaApi['duracion_segundos'])) {
                        $m = floor($notaApi['duracion_segundos'] / 60);
                        $s = $notaApi['duracion_segundos'] % 60;
                        echo ' · ' . $m . ':' . str_pad((string) $s, 2, '0', STR_PAD_LEFT);
                    } ?>
                  </div>
                  <audio class="cliente-nota-audio" controls preload="none" src="<?= htmlspecialchars($notaApi['audio_url']) ?>"></audio>
                </article>
              <?php } else { ?>
                <article class="nota-item">
                  <div class="nota-meta">
                    <span><strong><?= htmlspecialchars($notaApi['usuario']) ?></strong></span>
                    <span><?= htmlspecialchars($notaApi['fecha']) ?></span>
                  </div>
                  <div class="nota-texto"><?= nl2br(htmlspecialchars($notaApi['nota'])) ?></div>
                </article>
              <?php }
              } ?>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="js/cliente-notas-voz.js"></script>
<script>
(function () {
  var form = document.getElementById('formNotaInternaCliente');
  if (!form) return;

  var btnGuardar = document.getElementById('btnGuardarNotaInterna');
  var textarea = document.getElementById('notaInternaCliente');
  var lista = document.getElementById('listaNotasInternas');
  var alertExito = document.getElementById('notaInternaExito');
  var alertError = document.getElementById('notaInternaError');
  var guardando = false;
  var clienteIdNotas = <?= $clienteIdNotas ?>;
  var widgetVozTab = null;
  var selectorTipoTab = null;

  function setGuardandoTab(estado) {
    guardando = estado;
    btnGuardar.disabled = estado;
  }

  function ocultarAlertas() {
    alertExito.classList.remove('is-visible');
    alertError.classList.remove('is-visible');
    alertExito.textContent = '';
    alertError.textContent = '';
  }

  function incrementarContadorTab() {
    var tabCount = document.getElementById('tabCountNotasInternas');
    if (tabCount) {
      tabCount.textContent = String(parseInt(tabCount.textContent, 10) + 1);
    }
  }

  function agregarNotaAlHistorial(nota) {
    var vacio = document.getElementById('notasInternasVacio');
    if (vacio) vacio.remove();

    var wrapper = document.createElement('div');
    wrapper.innerHTML = window.ClienteNotasVozUtil.renderHistorialItem(nota);
    var article = wrapper.firstElementChild;
    if (article) {
      article.classList.add('nota-item');
      lista.insertBefore(article, lista.firstChild);
    }
    incrementarContadorTab();
  }

  if (typeof ClienteNotasVoz !== 'undefined') {
    widgetVozTab = new ClienteNotasVoz({
      rootId: 'notasVozTab',
      getClienteId: function () { return clienteIdNotas; },
      onSaving: function (activo) { setGuardandoTab(activo); },
      onSaved: function (data) {
        agregarNotaAlHistorial(data.nota);
        alertExito.textContent = data.message;
        alertExito.classList.add('is-visible');
      },
      onError: function (msg) {
        alertError.textContent = msg;
        alertError.classList.add('is-visible');
      }
    });
  }

  if (window.ClienteNotasVozUtil && typeof ClienteNotasVozUtil.bindNotasTipoSelector === 'function') {
    selectorTipoTab = ClienteNotasVozUtil.bindNotasTipoSelector({
      root: form,
      widgetVoz: widgetVozTab,
      onModoChange: function (modo) {
        if (modo === 'texto') textarea.focus();
      }
    });
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (guardando) return;
    if (widgetVozTab && widgetVozTab.guardando) return;

    ocultarAlertas();

    var modo = selectorTipoTab ? selectorTipoTab.getModo() : 'texto';

    if (modo === 'voz') {
      if (widgetVozTab && (widgetVozTab.estadoGrabacion === 'recording' || widgetVozTab.estadoGrabacion === 'paused')) {
        alertError.textContent = 'Finalice la grabación antes de guardar.';
        alertError.classList.add('is-visible');
        return;
      }
      if (!widgetVozTab || !widgetVozTab.tieneVozPendiente()) {
        alertError.textContent = 'Grabe y finalice una nota de voz antes de guardar.';
        alertError.classList.add('is-visible');
        return;
      }
      widgetVozTab.guardar();
      return;
    }

    var textoNota = textarea.value.trim();
    if (!textoNota) {
      alertError.textContent = 'Escriba el contenido de la nota.';
      alertError.classList.add('is-visible');
      textarea.focus();
      return;
    }

    setGuardandoTab(true);

    fetch('ajax/ajax-clientes-nota-interna-guardar.php', {
      method: 'POST',
      body: new FormData(form)
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        setGuardandoTab(false);

        if (data.success) {
          agregarNotaAlHistorial(data.nota);
          textarea.value = '';
          alertExito.textContent = data.message;
          alertExito.classList.add('is-visible');
        } else {
          alertError.textContent = data.message || 'No se pudo guardar la nota.';
          alertError.classList.add('is-visible');
        }
      })
      .catch(function () {
        setGuardandoTab(false);
        alertError.textContent = 'Error de conexión. Intente nuevamente.';
        alertError.classList.add('is-visible');
      });
  });
})();
</script>
