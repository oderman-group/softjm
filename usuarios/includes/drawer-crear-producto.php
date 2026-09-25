<?php
require_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';
$ofimaActivaProducto = ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa);
$exigeProveedor = !empty($configuracion['conf_proveedor_cotizacion']) && (int) $configuracion['conf_proveedor_cotizacion'] === 1;

$grupos1Drawer = [];
$qG1 = $conexionBdPrincipal->query("SELECT catp_id, catp_nombre FROM productos_categorias WHERE catp_grupo=1 AND catp_habilitada=1 AND catp_id_empresa='" . (int) $idEmpresa . "' ORDER BY catp_nombre");
while ($qG1 && ($r = mysqli_fetch_array($qG1, MYSQLI_ASSOC))) {
    $grupos1Drawer[] = $r;
}

$grupos2Drawer = [];
$qG2 = $conexionBdPrincipal->query("SELECT catp_id, catp_nombre FROM productos_categorias WHERE catp_grupo=2 AND catp_habilitada=1 AND catp_id_empresa='" . (int) $idEmpresa . "' ORDER BY catp_nombre");
while ($qG2 && ($r = mysqli_fetch_array($qG2, MYSQLI_ASSOC))) {
    $grupos2Drawer[] = $r;
}

$grupos3Drawer = [];
$qG3 = $conexionBdPrincipal->query("SELECT catp_id, catp_nombre FROM productos_categorias WHERE catp_grupo=3 AND catp_habilitada=1 AND catp_id_empresa='" . (int) $idEmpresa . "' ORDER BY catp_nombre");
while ($qG3 && ($r = mysqli_fetch_array($qG3, MYSQLI_ASSOC))) {
    $grupos3Drawer[] = $r;
}

$marcasDrawer = [];
$qMar = $conexionBdPrincipal->query("SELECT mar_id, mar_nombre FROM marcas WHERE mar_habilitada=1 AND mar_id_empresa='" . (int) $idEmpresa . "' ORDER BY mar_nombre");
while ($qMar && ($r = mysqli_fetch_array($qMar, MYSQLI_ASSOC))) {
    $marcasDrawer[] = $r;
}

$proveedoresDrawer = [];
if ($exigeProveedor) {
    $qProv = $conexionBdPrincipal->query("SELECT prov_id, prov_nombre FROM proveedores WHERE prov_id_empresa='" . (int) $idEmpresa . "' ORDER BY prov_nombre");
    while ($qProv && ($r = mysqli_fetch_array($qProv, MYSQLI_ASSOC))) {
        $proveedoresDrawer[] = $r;
    }
}
?>
<style>
#drawerCrearProductoOverlay {
  position: fixed !important;
  top: 0; left: 0; right: 0; bottom: 0;
  width: 100%; height: 100%;
  background: rgba(15, 23, 42, 0.45);
  z-index: 99998;
  opacity: 0; visibility: hidden; pointer-events: none;
  transition: opacity 0.3s ease, visibility 0.3s ease;
}
#drawerCrearProductoOverlay.is-open { opacity: 1; visibility: visible; pointer-events: auto; }
#drawerCrearProducto {
  position: fixed !important;
  top: 0; right: 0; left: auto; bottom: 0;
  width: 50vw; max-width: 50vw;
  height: 100vh; height: 100dvh;
  margin: 0; padding: 0; border: none; background: #fff;
  z-index: 99999;
  display: flex; flex-direction: column; box-sizing: border-box;
  box-shadow: -8px 0 32px rgba(15, 23, 42, 0.15);
  transform: translateX(100%);
  transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}
#drawerCrearProducto.is-open { transform: translateX(0); }
body.drawer-producto-open { overflow: hidden !important; }
#drawerCrearProducto .drawer-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0;
  background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%);
  color: #fff; flex-shrink: 0;
}
#drawerCrearProducto .drawer-header-title {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 1.25rem; font-weight: 700; margin: 0;
}
#drawerCrearProducto .drawer-close {
  background: rgba(255,255,255,0.15); border: none; color: #fff;
  width: 36px; height: 36px; border-radius: 8px; cursor: pointer; font-size: 1.25rem;
}
#drawerCrearProducto .drawer-body {
  flex: 1; overflow-y: auto; padding: 1.25rem 1.5rem;
}
#drawerCrearProducto .drawer-intro {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.875rem; color: #64748b; margin: 0 0 1.25rem 0; line-height: 1.5;
}
#drawerCrearProducto .drawer-form { max-width: 720px; }
#drawerCrearProducto .form-group { margin-bottom: 1rem; }
#drawerCrearProducto .form-group label {
  display: block; font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.75rem; font-weight: 600; color: #334155; margin-bottom: 0.3rem;
}
#drawerCrearProducto .form-group label .required { color: #dc2626; }
#drawerCrearProducto .drawer-form input,
#drawerCrearProducto .drawer-form select {
  width: 100%; max-width: 100%; margin-bottom: 0 !important;
  min-height: 38px; padding: 0.45rem 0.75rem; box-sizing: border-box;
  border: 1px solid #cbd5e1; border-radius: 8px;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; font-size: 0.875rem;
  background: #fff; color: #0f172a;
}
#drawerCrearProducto .drawer-form input.is-invalid,
#drawerCrearProducto .drawer-form select.is-invalid {
  border-color: #dc2626; background: #fef2f2;
}
#drawerCrearProducto .drawer-select-wrap { position: relative; }
#drawerCrearProducto .drawer-select-icon {
  position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%);
  color: #64748b; pointer-events: none; font-size: 0.75rem;
}
#drawerCrearProducto .drawer-select-wrap select { appearance: none; -webkit-appearance: none; padding-right: 2rem; }
#drawerCrearProducto select.js-select-buscar {
  position: absolute; width: 1px; height: 1px; padding: 0; margin: 0;
  overflow: hidden; clip: rect(0,0,0,0); border: 0; opacity: 0; pointer-events: none;
}
#drawerCrearProducto .js-select-buscar ~ .drawer-select-icon { display: none; }
#drawerCrearProducto .drawer-buscar { position: relative; }
#drawerCrearProducto .drawer-buscar-lista {
  display: none; position: absolute; z-index: 5; left: 0; right: 0; top: calc(100% + 4px);
  max-height: 220px; overflow-y: auto; margin: 0; padding: 0.25rem 0; list-style: none;
  background: #fff; border: 1px solid #cbd5e1; border-radius: 8px;
  box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
}
#drawerCrearProducto .drawer-buscar-lista.is-open { display: block; }
#drawerCrearProducto .drawer-buscar-lista li {
  padding: 0.45rem 0.75rem; cursor: pointer; font-size: 0.875rem; color: #0f172a;
}
#drawerCrearProducto .drawer-buscar-lista li:hover,
#drawerCrearProducto .drawer-buscar-lista li.is-elegida { background: #f1f5f9; }
#drawerCrearProducto .drawer-buscar-lista li.is-vacio { color: #94a3b8; cursor: default; }
#drawerCrearProducto .field-error {
  display: none; font-size: 0.75rem; color: #b91c1c; margin-top: 0.25rem;
}
#drawerCrearProducto .field-error.is-visible { display: block; }
#drawerCrearProducto .field-status { display: block; font-size: 0.75rem; margin-top: 0.25rem; }
#drawerCrearProducto .field-status.is-ok { color: #166534; }
#drawerCrearProducto .field-status.is-error { color: #b91c1c; }
#drawerCrearProducto .drawer-footer {
  display: flex; gap: 0.75rem; justify-content: flex-end;
  padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0; flex-shrink: 0; background: #f8fafc;
}
#drawerCrearProducto .drawer-btn {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.875rem; font-weight: 600; border-radius: 8px; padding: 0.55rem 1.1rem;
  border: 1px solid transparent; cursor: pointer;
}
#drawerCrearProducto .drawer-btn-secondary { background: #fff; border-color: #cbd5e1; color: #334155; }
#drawerCrearProducto .drawer-btn-primary { background: #0f766e; color: #fff; }
#drawerCrearProducto .drawer-btn-primary:disabled { opacity: 0.65; cursor: not-allowed; }
#drawerCrearProducto .drawer-alert {
  padding: 0.875rem 1rem; border-radius: 8px; font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.875rem; margin-bottom: 1.25rem; display: none;
}
#drawerCrearProducto .drawer-alert.is-visible { display: block; }
#drawerCrearProducto .drawer-alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
#drawerCrearProducto .drawer-alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
#drawerCrearProducto .drawer-alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
#drawerCrearProducto .drawer-alert-ofima { margin-top: 0.75rem; }
#drawerCrearProducto .drawer-success-actions { display: flex; gap: 0.75rem; margin-top: 0.75rem; }
#drawerCrearProducto .drawer-success-actions a,
#drawerCrearProducto .drawer-success-actions button {
  font-size: 0.8125rem; font-weight: 600; background: transparent; border: none;
  color: #0f766e; cursor: pointer; padding: 0; text-decoration: underline;
}
#drawerCrearProducto .drawer-form.is-hidden,
#drawerCrearProducto .drawer-intro.is-hidden { display: none; }
#drawerCrearProducto .drawer-spinner {
  display: inline-block; width: 14px; height: 14px; margin-right: 0.4rem;
  border: 2px solid rgba(255,255,255,0.35); border-top-color: #fff;
  border-radius: 50%; animation: drawerProdSpin 0.7s linear infinite; vertical-align: -2px;
}
@keyframes drawerProdSpin { to { transform: rotate(360deg); } }
@media (max-width: 900px) {
  #drawerCrearProducto { width: 100vw; max-width: 100vw; }
}
</style>

<div id="drawerCrearProductoOverlay" aria-hidden="true"></div>
<aside id="drawerCrearProducto" role="dialog" aria-modal="true" aria-labelledby="drawerProductoTitulo" aria-hidden="true">
  <div class="drawer-header">
    <h2 class="drawer-header-title" id="drawerProductoTitulo">Crear producto</h2>
    <button type="button" class="drawer-close" id="btnCerrarDrawerProducto" aria-label="Cerrar">&times;</button>
  </div>
  <div class="drawer-body">
    <p class="drawer-intro">Complete los datos del producto. <?= $ofimaActivaProducto ? 'El código, el nombre, la línea, la sublínea, la clasificación y el grupo son obligatorios. Al guardar se enviará a Ofima (/Integracion/Orion/Productos/Crear).' : 'El nombre, los grupos y la marca son obligatorios.'; ?></p>
    <div class="drawer-alert drawer-alert-success" id="drawerProductoExito" role="status"></div>
    <div class="drawer-alert drawer-alert-error" id="drawerProductoError" role="alert"></div>
    <div class="drawer-alert drawer-alert-ofima" id="drawerProductoOfima" role="status"></div>

    <form class="drawer-form" id="formCrearProductoRapido" novalidate>
      <?php if ($ofimaActivaProducto) { ?>
      <div class="form-group">
        <label for="prodRapidoReferencia">Código <span class="required">*</span></label>
        <input type="text" id="prodRapidoReferencia" name="referencia" autocomplete="off" required>
        <span class="field-status" id="statusReferenciaProducto" aria-live="polite"></span>
        <span class="field-error" id="errorReferenciaProducto">El código es obligatorio.</span>
      </div>
      <?php } ?>

      <div class="form-group">
        <label for="prodRapidoNombre">Nombre <span class="required">*</span></label>
        <input type="text" id="prodRapidoNombre" name="nombre" required>
        <span class="field-error" id="errorNombreProducto">El nombre es obligatorio.</span>
      </div>

      <?php if ($exigeProveedor) { ?>
      <div class="form-group">
        <label for="prodRapidoProveedor">Proveedor <span class="required">*</span></label>
        <div class="drawer-select-wrap">
          <select id="prodRapidoProveedor" name="proveedor" required>
            <option value="">Seleccione...</option>
            <?php foreach ($proveedoresDrawer as $prov) { ?>
              <option value="<?= (int) $prov['prov_id'] ?>"><?= htmlspecialchars($prov['prov_nombre']) ?></option>
            <?php } ?>
          </select>
          <i class="fa fa-chevron-down drawer-select-icon" aria-hidden="true"></i>
        </div>
        <span class="field-error" id="errorProveedorProducto">Debe seleccionar un proveedor.</span>
      </div>
      <?php } else { ?>
        <input type="hidden" name="proveedor" value="0">
      <?php } ?>

      <div class="form-group">
        <label for="prodRapidoGrupo1"><?= $ofimaActivaProducto ? 'Línea' : 'Grupo 1'; ?> <span class="required">*</span></label>
        <div class="drawer-select-wrap">
          <select id="prodRapidoGrupo1" class="js-select-buscar" name="grupo1" required>
            <option value="">Seleccione...</option>
            <?php foreach ($grupos1Drawer as $g) { ?>
              <option value="<?= (int) $g['catp_id'] ?>"><?= htmlspecialchars($g['catp_nombre']) ?></option>
            <?php } ?>
          </select>
          <i class="fa fa-chevron-down drawer-select-icon" aria-hidden="true"></i>
        </div>
        <span class="field-error" id="errorGrupo1Producto">Debe seleccionar <?= $ofimaActivaProducto ? 'la línea' : 'el Grupo 1'; ?>.</span>
      </div>

      <div class="form-group">
        <label for="prodRapidoCategoria"><?= $ofimaActivaProducto ? 'Sublínea' : 'Grupo 2'; ?> <span class="required">*</span></label>
        <div class="drawer-select-wrap">
          <select id="prodRapidoCategoria" class="js-select-buscar" name="categoria" required>
            <option value="">Seleccione...</option>
            <?php foreach ($grupos2Drawer as $g) { ?>
              <option value="<?= (int) $g['catp_id'] ?>"><?= htmlspecialchars($g['catp_nombre']) ?></option>
            <?php } ?>
          </select>
          <i class="fa fa-chevron-down drawer-select-icon" aria-hidden="true"></i>
        </div>
        <span class="field-error" id="errorCategoriaProducto">Debe seleccionar <?= $ofimaActivaProducto ? 'la sublínea' : 'el Grupo 2'; ?>.</span>
      </div>

      <div class="form-group">
        <label for="prodRapidoGrupo3"><?= $ofimaActivaProducto ? 'Clasificación 1' : 'Grupo 3'; ?> <span class="required">*</span></label>
        <div class="drawer-select-wrap">
          <select id="prodRapidoGrupo3" class="js-select-buscar" name="grupo3" required>
            <option value="">Seleccione...</option>
            <?php foreach ($grupos3Drawer as $g) { ?>
              <option value="<?= (int) $g['catp_id'] ?>"><?= htmlspecialchars($g['catp_nombre']) ?></option>
            <?php } ?>
          </select>
          <i class="fa fa-chevron-down drawer-select-icon" aria-hidden="true"></i>
        </div>
        <span class="field-error" id="errorGrupo3Producto">Debe seleccionar <?= $ofimaActivaProducto ? 'la clasificación' : 'el Grupo 3'; ?>.</span>
      </div>

      <div class="form-group">
        <label for="prodRapidoMarca"><?= $ofimaActivaProducto ? 'Grupo' : 'Marca'; ?> <span class="required">*</span></label>
        <div class="drawer-select-wrap">
          <select id="prodRapidoMarca" class="js-select-buscar" name="marca" required>
            <option value="">Seleccione...</option>
            <?php foreach ($marcasDrawer as $m) { ?>
              <option value="<?= (int) $m['mar_id'] ?>"><?= htmlspecialchars($m['mar_nombre']) ?></option>
            <?php } ?>
          </select>
          <i class="fa fa-chevron-down drawer-select-icon" aria-hidden="true"></i>
        </div>
        <span class="field-error" id="errorMarcaProducto">Debe seleccionar <?= $ofimaActivaProducto ? 'el grupo' : 'la marca'; ?>.</span>
      </div>

      <div class="form-group">
        <label for="prodRapidoReplicar">Replicar en soporte operativo?</label>
        <div class="drawer-select-wrap">
          <select id="prodRapidoReplicar" name="replicar">
            <option value="0" selected>NO</option>
            <option value="1">SI</option>
          </select>
          <i class="fa fa-chevron-down drawer-select-icon" aria-hidden="true"></i>
        </div>
      </div>
    </form>
  </div>
  <div class="drawer-footer">
    <button type="button" class="drawer-btn drawer-btn-secondary" id="btnCancelarDrawerProducto">Cancelar</button>
    <button type="button" class="drawer-btn drawer-btn-primary" id="btnGuardarProductoRapido">
      <span class="drawer-btn-label">Guardar</span>
    </button>
  </div>
</aside>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var overlay = document.getElementById('drawerCrearProductoOverlay');
  var drawer = document.getElementById('drawerCrearProducto');
  var form = document.getElementById('formCrearProductoRapido');
  if (!overlay || !drawer || !form) return;

  var btnCerrar = document.getElementById('btnCerrarDrawerProducto');
  var btnCancel = document.getElementById('btnCancelarDrawerProducto');
  var btnGuardar = document.getElementById('btnGuardarProductoRapido');
  var alertExito = document.getElementById('drawerProductoExito');
  var alertError = document.getElementById('drawerProductoError');
  var alertOfima = document.getElementById('drawerProductoOfima');
  var drawerIntro = drawer.querySelector('.drawer-intro');
  var refInput = document.getElementById('prodRapidoReferencia');
  var nombreInput = document.getElementById('prodRapidoNombre');
  var statusRef = document.getElementById('statusReferenciaProducto');
  var exigeProveedor = <?= $exigeProveedor ? 'true' : 'false' ?>;
  var guardando = false;
  var refEstado = 'empty';
  var refVerificada = '';
  var refTimer = null;
  var refRequestId = 0;

  function marcarSelectInvalido(id) {
    var sel = document.getElementById(id);
    if (!sel) return;
    sel.classList.add('is-invalid');
    var vis = sel.parentNode.querySelector('.drawer-buscar-input');
    if (vis) vis.classList.add('is-invalid');
  }

  function activarBusquedaSelect(select) {
    var wrap = select.parentNode;
    var box = document.createElement('div');
    box.className = 'drawer-buscar';
    var input = document.createElement('input');
    input.type = 'text';
    input.className = 'drawer-buscar-input';
    input.placeholder = 'Buscar...';
    input.autocomplete = 'off';
    var lista = document.createElement('ul');
    lista.className = 'drawer-buscar-lista';
    box.appendChild(input);
    box.appendChild(lista);
    wrap.appendChild(box);

    function opciones() {
      return Array.prototype.filter.call(select.options, function (o) { return o.value !== ''; });
    }
    function textoElegido() {
      var elegida = opciones().filter(function (o) { return o.value === select.value; })[0];
      return elegida ? elegida.text : '';
    }
    function pintar(filtro) {
      var q = (filtro || '').toLowerCase();
      lista.innerHTML = '';
      opciones().forEach(function (o) {
        if (q && o.text.toLowerCase().indexOf(q) === -1) return;
        var li = document.createElement('li');
        li.textContent = o.text;
        if (o.value === select.value) li.className = 'is-elegida';
        li.addEventListener('mousedown', function (e) {
          e.preventDefault();
          select.value = o.value;
          input.value = o.text;
          input.classList.remove('is-invalid');
          lista.classList.remove('is-open');
        });
        lista.appendChild(li);
      });
      if (!lista.children.length) {
        var vacio = document.createElement('li');
        vacio.className = 'is-vacio';
        vacio.textContent = 'Sin resultados';
        lista.appendChild(vacio);
      }
    }
    input.addEventListener('focus', function () {
      pintar('');
      lista.classList.add('is-open');
    });
    input.addEventListener('input', function () {
      select.value = '';
      pintar(input.value);
      lista.classList.add('is-open');
    });
    input.addEventListener('blur', function () {
      setTimeout(function () { lista.classList.remove('is-open'); }, 120);
      input.value = textoElegido();
    });
    select._syncBuscar = function () { input.value = textoElegido(); };
  }

  form.querySelectorAll('select.js-select-buscar').forEach(activarBusquedaSelect);
  form.addEventListener('reset', function () {
    setTimeout(function () {
      form.querySelectorAll('select.js-select-buscar').forEach(function (sel) {
        if (sel._syncBuscar) sel._syncBuscar();
      });
    }, 0);
  });

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

  function ocultarAlertas() {
    alertExito.classList.remove('is-visible');
    alertError.classList.remove('is-visible');
    if (alertOfima) {
      alertOfima.classList.remove('is-visible', 'drawer-alert-success', 'drawer-alert-error', 'drawer-alert-warning');
      alertOfima.innerHTML = '';
    }
    alertExito.innerHTML = '';
    alertError.textContent = '';
  }

  function limpiarErrores() {
    form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
    form.querySelectorAll('.field-error.is-visible').forEach(function (el) { el.classList.remove('is-visible'); });
  }

  function resetReferenciaEstado() {
    refEstado = 'empty';
    refVerificada = '';
    if (refTimer) { clearTimeout(refTimer); refTimer = null; }
    if (statusRef) { statusRef.textContent = ''; statusRef.className = 'field-status'; }
  }

  function mostrarEstadoRef(tipo, mensaje) {
    if (!statusRef) return;
    statusRef.textContent = mensaje || '';
    statusRef.className = 'field-status' + (tipo === 'ok' ? ' is-ok' : (tipo === 'error' ? ' is-error' : ''));
  }

  function verificarReferencia(valor, forzar) {
    var referencia = (valor || '').trim();
    if (!referencia) {
      refEstado = 'empty';
      mostrarEstadoRef('', '');
      return Promise.resolve(false);
    }
    if (!forzar && referencia === refVerificada && refEstado === 'available') {
      return Promise.resolve(true);
    }
    refEstado = 'checking';
    mostrarEstadoRef('', 'Verificando código...');
    var requestId = ++refRequestId;
    var body = new FormData();
    body.append('idUnico', referencia);
    body.append('opcion', '3');
    body.append('format', 'json');
    return fetch('ajax/ajax-clientes-verificar.php', { method: 'POST', body: body, credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (requestId !== refRequestId) return refEstado === 'available';
        if (data && data.available) {
          refEstado = 'available';
          refVerificada = referencia;
          if (refInput) refInput.classList.remove('is-invalid');
          mostrarEstadoRef('ok', 'Código disponible.');
          return true;
        }
        refEstado = 'duplicate';
        refVerificada = referencia;
        if (refInput) refInput.classList.add('is-invalid');
        mostrarEstadoRef('error', (data && data.message) || 'Ya existe un producto con este código.');
        return false;
      })
      .catch(function () {
        if (requestId !== refRequestId) return false;
        refEstado = 'error';
        mostrarEstadoRef('error', 'No se pudo verificar el código.');
        return false;
      });
  }

  function abrirDrawer() {
    ocultarAlertas();
    limpiarErrores();
    form.reset();
    resetReferenciaEstado();
    mostrarFormulario(true);
    setGuardando(false);
    overlay.classList.add('is-open');
    drawer.classList.add('is-open');
    overlay.setAttribute('aria-hidden', 'false');
    drawer.setAttribute('aria-hidden', 'false');
    document.body.classList.add('drawer-producto-open');
    if (refInput) refInput.focus();
  }

  function cerrarDrawer() {
    if (guardando) return;
    overlay.classList.remove('is-open');
    drawer.classList.remove('is-open');
    overlay.setAttribute('aria-hidden', 'true');
    drawer.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('drawer-producto-open');
  }

  window.abrirDrawerCrearProducto = abrirDrawer;

  function validarFormulario() {
    limpiarErrores();
    var valido = true;
    var referencia = refInput ? refInput.value.trim() : '';
    var nombre = nombreInput ? nombreInput.value.trim() : '';
    var grupo1 = document.getElementById('prodRapidoGrupo1').value;
    var categoria = document.getElementById('prodRapidoCategoria').value;
    var grupo3 = document.getElementById('prodRapidoGrupo3').value;
    var marca = document.getElementById('prodRapidoMarca').value;
    var proveedorEl = document.getElementById('prodRapidoProveedor');

    if (refInput && !referencia) {
      refInput.classList.add('is-invalid');
      document.getElementById('errorReferenciaProducto').classList.add('is-visible');
      valido = false;
    } else if (refInput && refEstado === 'duplicate') {
      if (refInput) refInput.classList.add('is-invalid');
      valido = false;
    } else if (refInput && (refEstado !== 'available' || referencia !== refVerificada)) {
      if (refInput) refInput.classList.add('is-invalid');
      mostrarEstadoRef('error', 'Espere la verificación del código o corrija el valor.');
      valido = false;
    }

    if (!nombre) {
      if (nombreInput) nombreInput.classList.add('is-invalid');
      document.getElementById('errorNombreProducto').classList.add('is-visible');
      valido = false;
    }
    if (!grupo1) {
      marcarSelectInvalido('prodRapidoGrupo1');
      document.getElementById('errorGrupo1Producto').classList.add('is-visible');
      valido = false;
    }
    if (!categoria) {
      marcarSelectInvalido('prodRapidoCategoria');
      document.getElementById('errorCategoriaProducto').classList.add('is-visible');
      valido = false;
    }
    if (!grupo3) {
      marcarSelectInvalido('prodRapidoGrupo3');
      document.getElementById('errorGrupo3Producto').classList.add('is-visible');
      valido = false;
    }
    if (!marca) {
      marcarSelectInvalido('prodRapidoMarca');
      document.getElementById('errorMarcaProducto').classList.add('is-visible');
      valido = false;
    }
    if (exigeProveedor && proveedorEl && !proveedorEl.value) {
      proveedorEl.classList.add('is-invalid');
      document.getElementById('errorProveedorProducto').classList.add('is-visible');
      valido = false;
    }
    return valido;
  }

  function mostrarExito(data) {
    setGuardando(false);
    limpiarErrores();
    form.reset();
    resetReferenciaEstado();
    mostrarFormulario(false);
    alertError.classList.remove('is-visible');
    alertExito.classList.add('is-visible');
    alertExito.innerHTML =
      '<strong>' + data.message + '</strong>' +
      '<div class="drawer-success-actions">' +
        '<a href="' + data.editUrl + '">Ver y completar producto</a>' +
        '<button type="button" id="btnCrearOtroProducto">Crear otro</button>' +
      '</div>';

    if (alertOfima) {
      alertOfima.classList.remove('is-visible', 'drawer-alert-success', 'drawer-alert-error', 'drawer-alert-warning');
      alertOfima.innerHTML = '';
      if (data.ofima && data.ofima.mensaje) {
        var tipoOfima = data.ofima.tipo || (data.ofima.sincronizado ? 'success' : 'error');
        var claseOfima = 'drawer-alert-error';
        if (tipoOfima === 'success') claseOfima = 'drawer-alert-success';
        else if (tipoOfima === 'warning') claseOfima = 'drawer-alert-warning';
        alertOfima.classList.add(claseOfima, 'is-visible');
        alertOfima.innerHTML = '<strong>Ofima!</strong> ' + data.ofima.mensaje;
      }
    }

    document.getElementById('btnCrearOtroProducto').addEventListener('click', function () {
      ocultarAlertas();
      mostrarFormulario(true);
      setGuardando(false);
      form.reset();
      resetReferenciaEstado();
      if (refInput) refInput.focus();
    });
  }

  if (refInput) {
    refInput.addEventListener('input', function () {
      var valor = refInput.value.trim();
      if (valor !== refVerificada) refEstado = 'pending';
      if (refTimer) clearTimeout(refTimer);
      refTimer = setTimeout(function () { verificarReferencia(valor, false); }, 450);
    });
    refInput.addEventListener('blur', function () {
      if (refTimer) { clearTimeout(refTimer); refTimer = null; }
      verificarReferencia(refInput.value, true);
    });
  }

  document.addEventListener('click', function (e) {
    var trigger = e.target.closest ? e.target.closest('.js-abrir-crear-producto') : null;
    if (!trigger) return;
    e.preventDefault();
    abrirDrawer();
  });

  if (btnCerrar) btnCerrar.addEventListener('click', cerrarDrawer);
  if (btnCancel) btnCancel.addEventListener('click', cerrarDrawer);
  if (overlay) overlay.addEventListener('click', cerrarDrawer);
  if (btnGuardar) {
    btnGuardar.addEventListener('click', function () {
      if (guardando) return;
      ocultarAlertas();
      var referencia = refInput ? refInput.value.trim() : '';
      var continuar = function () {
        if (!validarFormulario()) return;
        setGuardando(true);
        fetch('ajax/ajax-productos-crear-rapido.php', {
          method: 'POST',
          body: new FormData(form),
          credentials: 'same-origin'
        })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            if (!data || !data.success) {
              setGuardando(false);
              alertError.textContent = (data && data.message) || 'No se pudo guardar el producto.';
              alertError.classList.add('is-visible');
              return;
            }
            mostrarExito(data);
          })
          .catch(function () {
            setGuardando(false);
            alertError.textContent = 'Error de conexión. Verifique su red e intente nuevamente.';
            alertError.classList.add('is-visible');
          });
      };

      if (referencia && (refEstado !== 'available' || referencia !== refVerificada)) {
        verificarReferencia(referencia, true).then(function (ok) {
          if (!ok) {
            if (refInput) refInput.classList.add('is-invalid');
            return;
          }
          continuar();
        });
        return;
      }
      continuar();
    });
  }
});
</script>
<?php
$puedeEditarCodigo = Modulos::validarRol([398], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$puedeEditarPrecios = Modulos::validarRol([399], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
?>
<style>
#drawerEditarProductoOverlay {
  position: fixed; inset: 0; background: rgba(15,23,42,.45); z-index: 99998;
  opacity: 0; visibility: hidden; pointer-events: none; transition: opacity .3s ease;
}
#drawerEditarProductoOverlay.is-open { opacity: 1; visibility: visible; pointer-events: auto; }
#drawerEditarProducto {
  position: fixed; top: 0; right: 0; width: 50vw; max-width: 50vw; height: 100vh;
  background: #fff; z-index: 99999; display: flex; flex-direction: column;
  transform: translateX(100%); transition: transform .35s ease; box-shadow: -8px 0 32px rgba(15,23,42,.15);
}
#drawerEditarProducto.is-open { transform: translateX(0); }
#drawerEditarProducto .drawer-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1.25rem 1.5rem; color: #fff; background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
}
#drawerEditarProducto .drawer-header-title { margin: 0; font-size: 1.25rem; font-weight: 700; }
#drawerEditarProducto .drawer-close {
  background: rgba(255,255,255,.15); border: 0; color: #fff; width: 36px; height: 36px; border-radius: 8px; cursor: pointer;
}
#drawerEditarProducto .drawer-body { flex: 1; overflow-y: auto; padding: 1.25rem 1.5rem; }
#drawerEditarProducto .form-group { margin-bottom: 1rem; }
#drawerEditarProducto label { display: block; font-size: .75rem; font-weight: 600; color: #334155; margin-bottom: .3rem; }
#drawerEditarProducto input, #drawerEditarProducto select, #drawerEditarProducto textarea {
  width: 100%; box-sizing: border-box; min-height: 38px; padding: .45rem .75rem;
  border: 1px solid #cbd5e1; border-radius: 8px; font-size: .875rem;
}
#drawerEditarProducto textarea { min-height: 90px; }
#drawerEditarProducto .drawer-footer {
  display: flex; gap: .75rem; justify-content: flex-end; padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0; background: #f8fafc;
}
#drawerEditarProducto .drawer-buscar { position: relative; }
#drawerEditarProducto .drawer-buscar-lista {
  display: none; position: absolute; z-index: 5; left: 0; right: 0; top: calc(100% + 4px);
  max-height: 220px; overflow-y: auto; margin: 0; padding: .25rem 0; list-style: none;
  background: #fff; border: 1px solid #cbd5e1; border-radius: 8px; box-shadow: 0 8px 24px rgba(15,23,42,.12);
}
#drawerEditarProducto .drawer-buscar-lista.is-open { display: block; }
#drawerEditarProducto .drawer-buscar-lista li { padding: .45rem .75rem; cursor: pointer; }
#drawerEditarProducto .drawer-buscar-lista li:hover { background: #f1f5f9; }
#drawerEditarProducto .drawer-error { color: #b91c1c; font-size: .8rem; margin: 0 0 .75rem; }
#drawerEditarProducto .drawer-aviso {
  display: none; margin: 0 0 1rem; padding: .75rem .9rem; border-radius: 8px; font-size: .875rem; line-height: 1.4;
}
#drawerEditarProducto .drawer-aviso.is-visible { display: block; }
#drawerEditarProducto .drawer-aviso.is-ok { background: #ecfdf5; color: #166534; border: 1px solid #86efac; }
#drawerEditarProducto .drawer-aviso.is-error { background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }
#drawerEditarProducto .drawer-btn { border: 0; border-radius: 8px; padding: .55rem 1rem; cursor: pointer; font-weight: 600; }
#drawerEditarProducto .drawer-btn-secondary { background: #e2e8f0; color: #0f172a; }
#drawerEditarProducto .drawer-btn-primary { background: #1d4ed8; color: #fff; }
#drawerEditarProducto select.js-select-buscar { position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none; }
</style>
<div id="drawerEditarProductoOverlay"></div>
<aside id="drawerEditarProducto" role="dialog" aria-modal="true" aria-hidden="true">
  <div class="drawer-header">
    <h2 class="drawer-header-title">Editar producto</h2>
    <button type="button" class="drawer-close" id="btnCerrarDrawerEditar" aria-label="Cerrar">&times;</button>
  </div>
  <div class="drawer-body">
    <p class="drawer-error" id="drawerEditarError"></p>
    <div class="drawer-aviso" id="drawerEditarAviso" role="status"></div>
    <form id="formEditarProducto" enctype="multipart/form-data">
      <input type="hidden" name="ajax" value="1">
      <input type="hidden" name="id" id="editProdId" value="">
      <?php if ($puedeEditarCodigo) { ?>
      <div class="form-group">
        <label for="editProdReferencia">Código</label>
        <input type="text" name="referencia" id="editProdReferencia">
      </div>
      <?php } else { ?>
      <input type="hidden" name="referencia" id="editProdReferencia">
      <?php } ?>
      <div class="form-group">
        <label>Existencias</label>
        <input type="text" id="editProdExistencias" readonly>
      </div>
      <div class="form-group">
        <label for="editProdNombre">Nombre <span style="color:#dc2626">*</span></label>
        <input type="text" name="nombre" id="editProdNombre" required>
      </div>
      <div class="form-group">
        <label for="editProdFoto">Foto</label>
        <input type="file" name="foto" id="editProdFoto">
      </div>
      <div class="form-group">
        <label for="editProdDescripcion">Descripción corta</label>
        <textarea name="descripcion" id="editProdDescripcion"></textarea>
      </div>
      <div class="form-group">
        <label for="editProdDescripcionLarga">Descripción larga</label>
        <textarea name="descripcionLarga" id="editProdDescripcionLarga"></textarea>
      </div>
      <?php if ($exigeProveedor) { ?>
      <div class="form-group">
        <label>Proveedor</label>
        <select name="proveedor" id="editProdProveedor" class="js-select-buscar">
          <option value="">Seleccione...</option>
          <?php foreach ($proveedoresDrawer as $prov) { ?>
            <option value="<?= (int) $prov['prov_id'] ?>"><?= htmlspecialchars($prov['prov_nombre']) ?></option>
          <?php } ?>
        </select>
      </div>
      <?php } else { ?>
      <input type="hidden" name="proveedor" value="0">
      <?php } ?>
      <div class="form-group">
        <label><?= $ofimaActivaProducto ? 'Línea' : 'Grupo 1'; ?></label>
        <select name="grupo1" id="editProdGrupo1" class="js-select-buscar" required>
          <option value="">Seleccione...</option>
          <?php foreach ($grupos1Drawer as $g) { ?>
            <option value="<?= (int) $g['catp_id'] ?>"><?= htmlspecialchars($g['catp_nombre']) ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label><?= $ofimaActivaProducto ? 'Sublínea' : 'Grupo 2'; ?></label>
        <select name="categoria" id="editProdCategoria" class="js-select-buscar" required>
          <option value="">Seleccione...</option>
          <?php foreach ($grupos2Drawer as $g) { ?>
            <option value="<?= (int) $g['catp_id'] ?>"><?= htmlspecialchars($g['catp_nombre']) ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label><?= $ofimaActivaProducto ? 'Clasificación 1' : 'Grupo 3'; ?></label>
        <select name="grupo3" id="editProdGrupo3" class="js-select-buscar">
          <option value="">Seleccione...</option>
          <?php foreach ($grupos3Drawer as $g) { ?>
            <option value="<?= (int) $g['catp_id'] ?>"><?= htmlspecialchars($g['catp_nombre']) ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label><?= $ofimaActivaProducto ? 'Grupo' : 'Marca'; ?></label>
        <select name="marca" id="editProdMarca" class="js-select-buscar" required>
          <option value="">Seleccione...</option>
          <?php foreach ($marcasDrawer as $m) { ?>
            <option value="<?= (int) $m['mar_id'] ?>"><?= htmlspecialchars($m['mar_nombre']) ?></option>
          <?php } ?>
        </select>
      </div>
      <?php if ($puedeEditarPrecios) { ?>
      <div class="form-group"><label>Costo COP ($)</label><input type="text" name="costo" id="editProdCosto"></div>
      <div class="form-group"><label>Costo USD ($)</label><input type="text" name="costoDolar" id="editProdCostoDolar"></div>
      <div class="form-group"><label>Utilidad (%)</label><input type="text" name="utilidad" id="editProdUtilidad"></div>
      <div class="form-group"><label>Precio lista ($)</label><input type="text" id="editProdPrecio" readonly></div>
      <div class="form-group"><label>Precio lista (USD)</label><input type="text" id="editProdPrecioUsd" readonly></div>
      <div class="form-group"><label>Dcto. máximo (%)</label><input type="text" name="dcto1" id="editProdDcto"></div>
      <div class="form-group"><label>Comisión venta (%)</label><input type="text" name="comision" id="editProdComision"></div>
      <?php } else { ?>
      <input type="hidden" name="costo" id="editProdCosto">
      <input type="hidden" name="costoDolar" id="editProdCostoDolar">
      <input type="hidden" name="utilidad" id="editProdUtilidad">
      <input type="hidden" name="dcto1" id="editProdDcto">
      <input type="hidden" name="comision" id="editProdComision">
      <?php } ?>
    </form>
  </div>
  <div class="drawer-footer">
    <button type="button" class="drawer-btn drawer-btn-secondary" id="btnCancelarDrawerEditar">Cancelar</button>
    <button type="button" class="drawer-btn drawer-btn-primary" id="btnGuardarDrawerEditar">Guardar cambios</button>
  </div>
</aside>
<script>
(function () {
  var drawer = document.getElementById('drawerEditarProducto');
  var overlay = document.getElementById('drawerEditarProductoOverlay');
  var form = document.getElementById('formEditarProducto');
  if (!drawer || !form) return;

  function activar(select) {
    var box = document.createElement('div');
    box.className = 'drawer-buscar';
    var input = document.createElement('input');
    input.type = 'text';
    input.className = 'drawer-buscar-input';
    input.placeholder = 'Buscar...';
    input.autocomplete = 'off';
    var lista = document.createElement('ul');
    lista.className = 'drawer-buscar-lista';
    box.appendChild(input);
    box.appendChild(lista);
    select.parentNode.appendChild(box);
    function opciones() {
      return Array.prototype.filter.call(select.options, function (o) { return o.value !== ''; });
    }
    function pintar(filtro) {
      var q = (filtro || '').toLowerCase();
      lista.innerHTML = '';
      opciones().forEach(function (o) {
        if (q && o.text.toLowerCase().indexOf(q) === -1) return;
        var li = document.createElement('li');
        li.textContent = o.text;
        li.addEventListener('mousedown', function (e) {
          e.preventDefault();
          select.value = o.value;
          input.value = o.text;
          lista.classList.remove('is-open');
        });
        lista.appendChild(li);
      });
    }
    input.addEventListener('focus', function () { pintar(''); lista.classList.add('is-open'); });
    input.addEventListener('input', function () { select.value = ''; pintar(input.value); lista.classList.add('is-open'); });
    input.addEventListener('blur', function () {
      setTimeout(function () { lista.classList.remove('is-open'); }, 120);
      var elegida = opciones().filter(function (o) { return o.value === select.value; })[0];
      input.value = elegida ? elegida.text : '';
    });
    select._syncBuscar = function () {
      var elegida = opciones().filter(function (o) { return o.value === select.value; })[0];
      input.value = elegida ? elegida.text : '';
    };
  }
  form.querySelectorAll('select.js-select-buscar').forEach(activar);

  function asegurarOpcion(select, valor) {
    if (!select || !valor) return;
    var existe = Array.prototype.some.call(select.options, function (o) { return o.value === String(valor); });
    if (!existe) {
      var op = document.createElement('option');
      op.value = String(valor);
      op.textContent = 'Actual (' + valor + ')';
      select.appendChild(op);
    }
    select.value = String(valor);
    if (select._syncBuscar) select._syncBuscar();
  }
  function abrir() {
    overlay.classList.add('is-open');
    drawer.classList.add('is-open');
    document.body.classList.add('drawer-producto-open');
  }
  function cerrar() {
    overlay.classList.remove('is-open');
    drawer.classList.remove('is-open');
    document.body.classList.remove('drawer-producto-open');
  }
  document.getElementById('btnCerrarDrawerEditar').addEventListener('click', cerrar);
  document.getElementById('btnCancelarDrawerEditar').addEventListener('click', cerrar);
  overlay.addEventListener('click', cerrar);

  document.addEventListener('click', function (e) {
    var link = e.target.closest ? e.target.closest('.js-editar-producto') : null;
    if (!link) return;
    e.preventDefault();
    var id = link.getAttribute('data-id');
    document.getElementById('drawerEditarError').textContent = 'Cargando...';
    abrir();
    fetch('ajax/ajax-producto-cargar.php?id=' + encodeURIComponent(id), { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data || !data.success) {
          document.getElementById('drawerEditarError').textContent = (data && data.message) || 'No se pudo cargar.';
          return;
        }
        var p = data.producto;
        document.getElementById('drawerEditarError').textContent = '';
        document.getElementById('editProdId').value = p.id;
        document.getElementById('editProdReferencia').value = p.referencia;
        document.getElementById('editProdExistencias').value = p.existencias;
        document.getElementById('editProdNombre').value = p.nombre;
        document.getElementById('editProdDescripcion').value = p.descripcion;
        document.getElementById('editProdDescripcionLarga').value = p.descripcion_larga;
        asegurarOpcion(document.getElementById('editProdProveedor'), p.proveedor);
        asegurarOpcion(document.getElementById('editProdGrupo1'), p.grupo1);
        asegurarOpcion(document.getElementById('editProdCategoria'), p.categoria);
        asegurarOpcion(document.getElementById('editProdGrupo3'), p.grupo3);
        asegurarOpcion(document.getElementById('editProdMarca'), p.marca);
        document.getElementById('editProdCosto').value = p.costo;
        document.getElementById('editProdCostoDolar').value = p.costo_dolar;
        document.getElementById('editProdUtilidad').value = p.utilidad;
        var precio = document.getElementById('editProdPrecio');
        var precioUsd = document.getElementById('editProdPrecioUsd');
        if (precio) precio.value = p.precio;
        if (precioUsd) precioUsd.value = p.precio_usd;
        document.getElementById('editProdDcto').value = p.dcto1;
        document.getElementById('editProdComision').value = p.comision;
      })
      .catch(function () {
        document.getElementById('drawerEditarError').textContent = 'No se pudo cargar el producto.';
      });
  });

  function mostrarAviso(tipo, texto) {
    var aviso = document.getElementById('drawerEditarAviso');
    aviso.className = 'drawer-aviso is-visible ' + (tipo === 'ok' ? 'is-ok' : 'is-error');
    aviso.textContent = texto;
    aviso.scrollIntoView({ block: 'nearest' });
  }
  document.getElementById('btnGuardarDrawerEditar').addEventListener('click', function () {
    var error = document.getElementById('drawerEditarError');
    var boton = document.getElementById('btnGuardarDrawerEditar');
    error.textContent = '';
    document.getElementById('drawerEditarAviso').className = 'drawer-aviso';
    boton.disabled = true;
    fetch('bd_update/productos-actualizar.php', { method: 'POST', body: new FormData(form), credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        boton.disabled = false;
        if (!data || !data.success) {
          mostrarAviso('error', (data && data.message) || 'No se pudieron guardar los cambios.');
          return;
        }
        var texto = data.message || 'Los cambios se guardaron correctamente.';
        var ofimaFallo = data.ofima && data.ofima.tipo === 'error';
        if (ofimaFallo && data.ofima.mensaje) {
          texto += ' ' + data.ofima.mensaje;
        } else if (data.ofima && data.ofima.sincronizado && data.ofima.mensaje) {
          texto += ' ' + data.ofima.mensaje;
        }
        mostrarAviso(ofimaFallo ? 'error' : 'ok', texto);
      })
      .catch(function () {
        boton.disabled = false;
        mostrarAviso('error', 'No se pudieron guardar los cambios. Revise la conexión e intente de nuevo.');
      });
  });
})();
</script>
