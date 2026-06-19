<style>
#drawerContactoOverlay {
  position: fixed !important; inset: 0; background: rgba(15, 23, 42, 0.45);
  z-index: 99992; opacity: 0; visibility: hidden; pointer-events: none;
  transition: opacity 0.3s ease, visibility 0.3s ease;
}
#drawerContactoOverlay.is-open { opacity: 1; visibility: visible; pointer-events: auto; }
#drawerContacto {
  position: fixed !important; top: 0; right: 0; bottom: 0;
  width: 50vw; max-width: 50vw; height: 100dvh;
  z-index: 99993; display: flex; flex-direction: column;
  box-shadow: -8px 0 32px rgba(15, 23, 42, 0.15);
  transform: translateX(100%); transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}
#drawerContacto.is-open { transform: translateX(0); }
body.drawer-contacto-open { overflow: hidden !important; }
#drawerContacto .drawer-header {
  display: flex; align-items: center; justify-content: space-between;
  background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
  color: #fff; flex-shrink: 0;
}
@media (max-width: 768px) { #drawerContacto { width: 100vw; max-width: 100vw; } }
</style>

<div id="drawerContactoOverlay" aria-hidden="true"></div>
<aside id="drawerContacto" role="dialog" aria-modal="true">
  <div class="drawer-header">
    <div>
      <h2 class="drawer-header-title" id="drawerContactoTitulo">Contacto</h2>
      <div class="drawer-header-subtitle" id="drawerContactoSubtitulo"></div>
    </div>
    <button type="button" class="drawer-close" id="btnCerrarDrawerContacto" aria-label="Cerrar"><i class="icon-remove"></i></button>
  </div>
  <div class="drawer-body" id="drawerContactoBody"><div class="drawer-cargando">Cargando...</div></div>
  <div class="drawer-footer">
    <button type="button" class="drawer-btn drawer-btn-secondary" id="btnCancelarDrawerContacto">Cerrar</button>
    <div class="drawer-footer-actions">
      <a href="#" class="drawer-btn drawer-btn-link" id="btnContactoPaginaCompleta" target="_blank" rel="noopener" style="display:none;">
        <i class="icon-external-link"></i> Página completa
      </a>
      <button type="submit" form="formContactoDrawer" class="drawer-btn drawer-btn-primary" id="btnGuardarContacto">
        <i class="icon-save"></i> Guardar
      </button>
    </div>
  </div>
</aside>

<script>
(function () {
  var overlay = document.getElementById('drawerContactoOverlay');
  var drawer = document.getElementById('drawerContacto');
  var body = document.getElementById('drawerContactoBody');
  var titulo = document.getElementById('drawerContactoTitulo');
  var subtitulo = document.getElementById('drawerContactoSubtitulo');
  var btnCompleto = document.getElementById('btnContactoPaginaCompleta');
  var btnGuardar = document.getElementById('btnGuardarContacto');
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
    var c = data.contacto || {};
    var sucHtml = '<option value="">Sin sucursal asignada</option>';
    (data.sucursales || []).forEach(function (s) {
      sucHtml += '<option value="' + s.id + '"' + (String(s.id) === String(c.sucursal || '') ? ' selected' : '') + '>' + escaparHtml(s.nombre) + '</option>';
    });

    body.innerHTML =
      '<div class="drawer-alert drawer-alert-error" id="drawerContactoError"></div>' +
      '<p class="drawer-form-intro">Registre la información del contacto. El nombre es obligatorio.</p>' +
      '<div class="drawer-form-card">' +
        '<form id="formContactoDrawer" class="drawer-cliente-form">' +
        '<input type="hidden" name="cte" value="' + escaparHtml(data.cliente.id) + '">' +
        (data.modo === 'editar' ? '<input type="hidden" name="id" value="' + escaparHtml(c.id) + '">' : '') +
        '<div class="drawer-form-section">' +
          '<h3 class="drawer-form-section-title">Asignación</h3>' +
          campo('Sucursal', '<select name="sucursal">' + sucHtml + '</select>') +
        '</div>' +
        '<div class="drawer-form-section">' +
          '<h3 class="drawer-form-section-title">Datos personales</h3>' +
          campo('Nombre <span class="drawer-required">*</span>', '<input type="text" name="nombre" required maxlength="255" placeholder="Nombre completo" value="' + escaparHtml(c.nombre || '') + '">') +
          campo('Email', '<input type="email" name="email" placeholder="correo@empresa.com" value="' + escaparHtml(c.email || '') + '">') +
        '</div>' +
        '<div class="drawer-form-section">' +
          '<h3 class="drawer-form-section-title">Medios de contacto</h3>' +
          '<div class="drawer-form-row drawer-form-row--2">' +
            campo('Teléfono fijo', '<input type="text" name="telefono" placeholder="601 234 5678" value="' + escaparHtml(c.telefono || '') + '">') +
            campo('Celular', '<input type="text" name="celular" maxlength="10" inputmode="numeric" placeholder="3001234567" value="' + escaparHtml(c.celular || '') + '">', { text: '10 dígitos, sin puntos ni espacios', warning: true }) +
          '</div>' +
          campo('Teléfonos complementarios', '<input type="text" name="telefonos" placeholder="Opcional" value="' + escaparHtml(c.telefonos || '') + '">') +
        '</div>' +
        '<div class="drawer-form-section">' +
          '<h3 class="drawer-form-section-title">Información laboral</h3>' +
          '<div class="drawer-form-row drawer-form-row--2">' +
            campo('Área', '<input type="text" name="area" placeholder="Ej: Compras" value="' + escaparHtml(c.area || '') + '">') +
            campo('Cargo', '<input type="text" name="cargo" placeholder="Ej: Gerente" value="' + escaparHtml(c.cargo || '') + '">') +
          '</div>' +
        '</div>' +
        '</form>' +
      '</div>';

    titulo.textContent = data.modo === 'editar' ? 'Editar contacto' : 'Agregar contacto';
    subtitulo.textContent = data.cliente.nombre || '';
    if (data.urls && data.urls.paginaCompleta) {
      btnCompleto.href = data.urls.paginaCompleta;
      btnCompleto.style.display = '';
    } else {
      btnCompleto.style.display = 'none';
    }

    document.getElementById('formContactoDrawer').addEventListener('submit', onSubmit);
  }

  function onSubmit(e) {
    e.preventDefault();
    if (guardando) return;
    var form = e.target;
    var errorEl = document.getElementById('drawerContactoError');
    errorEl.classList.remove('is-visible');

    guardando = true;
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = '<i class="icon-save"></i> Guardando...';

    fetch('ajax/ajax-clientes-contacto-guardar.php', { method: 'POST', body: new FormData(form) })
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
    document.body.classList.remove('drawer-contacto-open');
    body.innerHTML = '<div class="drawer-cargando">Cargando...</div>';
  }

  function abrirDrawerContacto(cliente, contactoId) {
    var clienteId = parseInt(cliente, 10);
    contactoId = contactoId ? parseInt(contactoId, 10) : 0;
    if (!clienteId) return;

    body.innerHTML = '<div class="drawer-cargando">Cargando formulario...</div>';
    overlay.classList.add('is-open');
    drawer.classList.add('is-open');
    document.body.classList.add('drawer-contacto-open');

    var url = 'ajax/ajax-clientes-contacto-formulario.php?cliente=' + encodeURIComponent(clienteId);
    if (contactoId) url += '&contacto=' + encodeURIComponent(contactoId);

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

  document.getElementById('btnCerrarDrawerContacto').addEventListener('click', cerrar);
  document.getElementById('btnCancelarDrawerContacto').addEventListener('click', cerrar);
  overlay.addEventListener('click', cerrar);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer.classList.contains('is-open')) cerrar();
  });
  document.addEventListener('click', function (e) {
    var crear = e.target.closest('.js-abrir-contacto-drawer-crear');
    if (crear) {
      e.preventDefault();
      abrirDrawerContacto(crear.getAttribute('data-cliente-id'), 0);
      return;
    }
    var editar = e.target.closest('.js-abrir-contacto-drawer-editar');
    if (editar) {
      e.preventDefault();
      abrirDrawerContacto(editar.getAttribute('data-cliente-id'), editar.getAttribute('data-contacto-id'));
    }
  });

  window.abrirDrawerContactoCliente = abrirDrawerContacto;
})();
</script>
