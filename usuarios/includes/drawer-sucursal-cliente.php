<style>
#drawerSucursalOverlay {
  position: fixed !important; inset: 0; background: rgba(15, 23, 42, 0.45);
  z-index: 99992; opacity: 0; visibility: hidden; pointer-events: none;
  transition: opacity 0.3s ease, visibility 0.3s ease;
}
#drawerSucursalOverlay.is-open { opacity: 1; visibility: visible; pointer-events: auto; }
#drawerSucursal {
  position: fixed !important; top: 0; right: 0; bottom: 0;
  width: 50vw; max-width: 50vw; height: 100dvh;
  z-index: 99993; display: flex; flex-direction: column;
  box-shadow: -8px 0 32px rgba(15, 23, 42, 0.15);
  transform: translateX(100%); transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}
#drawerSucursal.is-open { transform: translateX(0); }
body.drawer-sucursal-open { overflow: hidden !important; }
#drawerSucursal .drawer-header {
  display: flex; align-items: center; justify-content: space-between;
  background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
  color: #fff; flex-shrink: 0;
}
@media (max-width: 768px) { #drawerSucursal { width: 100vw; max-width: 100vw; } }
</style>

<div id="drawerSucursalOverlay" aria-hidden="true"></div>
<aside id="drawerSucursal" role="dialog" aria-modal="true">
  <div class="drawer-header">
    <div>
      <h2 class="drawer-header-title" id="drawerSucursalTitulo">Sucursal</h2>
      <div class="drawer-header-subtitle" id="drawerSucursalSubtitulo"></div>
    </div>
    <button type="button" class="drawer-close" id="btnCerrarDrawerSucursal" aria-label="Cerrar"><i class="icon-remove"></i></button>
  </div>
  <div class="drawer-body" id="drawerSucursalBody"><div class="drawer-cargando">Cargando...</div></div>
  <div class="drawer-footer">
    <button type="button" class="drawer-btn drawer-btn-secondary" id="btnCancelarDrawerSucursal">Cerrar</button>
    <div class="drawer-footer-actions">
      <a href="#" class="drawer-btn drawer-btn-link" id="btnSucursalPaginaCompleta" target="_blank" rel="noopener" style="display:none;">
        <i class="icon-external-link"></i> Página completa
      </a>
      <button type="submit" form="formSucursalDrawer" class="drawer-btn drawer-btn-primary" id="btnGuardarSucursal">
        <i class="icon-save"></i> Guardar
      </button>
    </div>
  </div>
</aside>

<script>
(function () {
  var overlay = document.getElementById('drawerSucursalOverlay');
  var drawer = document.getElementById('drawerSucursal');
  var body = document.getElementById('drawerSucursalBody');
  var titulo = document.getElementById('drawerSucursalTitulo');
  var subtitulo = document.getElementById('drawerSucursalSubtitulo');
  var btnCompleto = document.getElementById('btnSucursalPaginaCompleta');
  var btnGuardar = document.getElementById('btnGuardarSucursal');
  if (!overlay || !drawer || !body) return;

  document.body.appendChild(overlay);
  document.body.appendChild(drawer);

  var guardando = false;

  function escaparHtml(t) {
    var d = document.createElement('div');
    d.textContent = t == null ? '' : String(t);
    return d.innerHTML;
  }

  function campo(label, inputHtml, hint) {
    var html = '<div class="drawer-field"><label>' + label + '</label>' + inputHtml;
    if (hint) html += '<div class="drawer-hint' + (hint.warning ? ' is-warning' : '') + '">' + escaparHtml(hint.text) + '</div>';
    html += '</div>';
    return html;
  }

  function renderForm(data) {
    var s = data.sucursal || {};
    var ciudadesHtml = '<option value="">Seleccione una ciudad</option>';
    (data.ciudades || []).forEach(function (c) {
      ciudadesHtml += '<option value="' + c.id + '"' + (String(c.id) === String(s.ciudad || '') ? ' selected' : '') + '>' + escaparHtml(c.label) + '</option>';
    });

    body.innerHTML =
      '<div class="drawer-alert drawer-alert-success" id="drawerSucursalExito"></div>' +
      '<div class="drawer-alert drawer-alert-error" id="drawerSucursalError"></div>' +
      '<p class="drawer-form-intro">Registre la información de la sucursal. Los campos marcados con <span class="drawer-required">*</span> son obligatorios.</p>' +
      '<div class="drawer-form-card">' +
        '<form id="formSucursalDrawer" class="drawer-cliente-form">' +
        '<input type="hidden" name="cte" value="' + escaparHtml(data.cliente.id) + '">' +
        (data.modo === 'editar' ? '<input type="hidden" name="id" value="' + escaparHtml(s.id) + '">' : '') +
        '<div class="drawer-form-section">' +
          '<h3 class="drawer-form-section-title">Identificación</h3>' +
          campo('Nombre sucursal <span class="drawer-required">*</span>', '<input type="text" name="nombre" required maxlength="255" placeholder="Ej: Sede principal" value="' + escaparHtml(s.nombre || '') + '">') +
        '</div>' +
        '<div class="drawer-form-section">' +
          '<h3 class="drawer-form-section-title">Contacto</h3>' +
          '<div class="drawer-form-row drawer-form-row--2">' +
            campo('Teléfono fijo', '<input type="text" name="telefono" placeholder="601 234 5678" value="' + escaparHtml(s.telefono || '') + '">') +
            campo('Celular', '<input type="text" name="celular" maxlength="10" inputmode="numeric" placeholder="3001234567" value="' + escaparHtml(s.celular || '') + '">', { text: '10 dígitos, sin puntos ni espacios', warning: true }) +
          '</div>' +
          campo('Teléfonos complementarios', '<input type="text" name="telefonos" placeholder="Opcional" value="' + escaparHtml(s.telefonos || '') + '">') +
        '</div>' +
        '<div class="drawer-form-section">' +
          '<h3 class="drawer-form-section-title">Ubicación</h3>' +
          campo('Dirección', '<input type="text" name="direccion" placeholder="Calle, número, barrio" value="' + escaparHtml(s.direccion || '') + '">') +
          campo('Ciudad', '<select name="ciudad">' + ciudadesHtml + '</select>') +
        '</div>' +
        '</form>' +
      '</div>';

    titulo.textContent = data.modo === 'editar' ? 'Editar sucursal' : 'Agregar sucursal';
    subtitulo.textContent = data.cliente.nombre || '';
    if (data.urls && data.urls.paginaCompleta) {
      btnCompleto.href = data.urls.paginaCompleta;
      btnCompleto.style.display = '';
    } else {
      btnCompleto.style.display = 'none';
    }

    document.getElementById('formSucursalDrawer').addEventListener('submit', onSubmit);
  }

  function onSubmit(e) {
    e.preventDefault();
    if (guardando) return;
    var form = e.target;
    var errorEl = document.getElementById('drawerSucursalError');
    errorEl.classList.remove('is-visible');

    guardando = true;
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = '<i class="icon-save"></i> Guardando...';

    fetch('ajax/ajax-clientes-sucursal-guardar.php', { method: 'POST', body: new FormData(form) })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data.success) throw new Error(data.message || 'No se pudo guardar.');
        window.location.reload();
      })
      .catch(function (err) {
        errorEl.textContent = err.message;
        errorEl.classList.add('is-visible');
      })
      .finally(function () {
        guardando = false;
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = '<i class="icon-save"></i> Guardar';
      });
  }

  function cerrar() {
    if (guardando) return;
    overlay.classList.remove('is-open');
    drawer.classList.remove('is-open');
    document.body.classList.remove('drawer-sucursal-open');
    body.innerHTML = '<div class="drawer-cargando">Cargando...</div>';
  }

  function abrirDrawerSucursal(cliente, sucursalId) {
    var clienteId = parseInt(cliente, 10);
    sucursalId = sucursalId ? parseInt(sucursalId, 10) : 0;
    if (!clienteId) return;

    body.innerHTML = '<div class="drawer-cargando">Cargando formulario...</div>';
    overlay.classList.add('is-open');
    drawer.classList.add('is-open');
    document.body.classList.add('drawer-sucursal-open');

    var url = 'ajax/ajax-clientes-sucursal-formulario.php?cliente=' + encodeURIComponent(clienteId);
    if (sucursalId) url += '&sucursal=' + encodeURIComponent(sucursalId);

    fetch(url)
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data.success) throw new Error(data.message || 'Error al cargar.');
        renderForm(data);
      })
      .catch(function (err) {
        body.innerHTML = '<div class="drawer-error">' + escaparHtml(err.message) + '</div>';
      });
  }

  document.getElementById('btnCerrarDrawerSucursal').addEventListener('click', cerrar);
  document.getElementById('btnCancelarDrawerSucursal').addEventListener('click', cerrar);
  overlay.addEventListener('click', cerrar);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer.classList.contains('is-open')) cerrar();
  });
  document.addEventListener('click', function (e) {
    var crear = e.target.closest('.js-abrir-sucursal-drawer-crear');
    if (crear) {
      e.preventDefault();
      abrirDrawerSucursal(crear.getAttribute('data-cliente-id'), 0);
      return;
    }
    var editar = e.target.closest('.js-abrir-sucursal-drawer-editar');
    if (editar) {
      e.preventDefault();
      abrirDrawerSucursal(editar.getAttribute('data-cliente-id'), editar.getAttribute('data-sucursal-id'));
    }
  });

  window.abrirDrawerSucursalCliente = abrirDrawerSucursal;
})();
</script>
