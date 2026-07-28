<style>
#drawerCotizacionOverlay {
  position: fixed !important; inset: 0;
  background: rgba(15, 23, 42, 0.45);
  z-index: 99990; opacity: 0; visibility: hidden; pointer-events: none;
  transition: opacity 0.3s ease, visibility 0.3s ease;
}
#drawerCotizacionOverlay.is-open { opacity: 1; visibility: visible; pointer-events: auto; }

#drawerCotizacion {
  position: fixed !important; top: 0; right: 0; bottom: 0;
  width: 70vw; max-width: 70vw; height: 100dvh;
  z-index: 99991; display: flex; flex-direction: column;
  background: #f8fafc;
  box-shadow: -8px 0 32px rgba(15, 23, 42, 0.15);
  transform: translateX(100%);
  transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}
#drawerCotizacion.is-open { transform: translateX(0); }
body.drawer-cotizacion-open { overflow: hidden !important; }

#drawerCotizacion .drawer-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1.25rem 1.5rem; flex-shrink: 0;
  background: linear-gradient(135deg, #059669 0%, #047857 100%);
  color: #fff;
}
#drawerCotizacion .drawer-header.is-vendida {
  background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
}
#drawerCotizacion .drawer-header-title {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 1.125rem; font-weight: 700; margin: 0;
  display: flex; flex-direction: column; gap: 0.25rem;
}
#drawerCotizacion .drawer-header-subtitle { font-size: 0.8125rem; font-weight: 500; opacity: 0.92; }
#drawerCotizacion .drawer-header-badges { display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.35rem; }
#drawerCotizacion .drawer-badge {
  display: inline-flex; align-items: center;
  padding: 0.15rem 0.55rem; border-radius: 999px;
  font-size: 0.6875rem; font-weight: 700; text-transform: uppercase;
  background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.25);
}
#drawerCotizacion .drawer-close {
  background: rgba(255,255,255,0.15); border: none; color: #fff;
  width: 36px; height: 36px; border-radius: 8px; cursor: pointer;
  display: flex; align-items: center; justify-content: center; font-size: 1.125rem;
}
#drawerCotizacion .drawer-body { flex: 1; overflow-y: auto; padding: 1.25rem 1.5rem 1.5rem; }
#drawerCotizacion .drawer-cargando, #drawerCotizacion .drawer-error {
  text-align: center; padding: 2.5rem 1rem; color: #64748b;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; font-size: 0.9375rem;
}
#drawerCotizacion .drawer-error { color: #b91c1c; }
#drawerCotizacion .drawer-spinner {
  display: inline-block; width: 28px; height: 28px;
  border: 3px solid #e2e8f0; border-top-color: #059669;
  border-radius: 50%; animation: drawer-cotiz-spin 0.6s linear infinite; margin-bottom: 0.75rem;
}
@keyframes drawer-cotiz-spin { to { transform: rotate(360deg); } }

#drawerCotizacion .drawer-stats { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem; }
#drawerCotizacion .drawer-stat {
  flex: 1; min-width: 100px; background: #fff; border: 1px solid #e2e8f0;
  border-radius: 10px; padding: 0.65rem 0.75rem; text-align: center;
}
#drawerCotizacion .drawer-stat-num { display: block; font-size: 1.05rem; font-weight: 800; color: #0f172a; }
#drawerCotizacion .drawer-stat-label {
  display: block; font-size: 0.6875rem; font-weight: 600;
  text-transform: uppercase; color: #64748b;
}
#drawerCotizacion .drawer-section {
  background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;
  margin-bottom: 1rem; overflow: hidden;
}
#drawerCotizacion .drawer-section-title {
  margin: 0; padding: 0.85rem 1rem;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.04em; color: #334155;
  background: #f1f5f9; border-bottom: 1px solid #e2e8f0;
}
#drawerCotizacion .drawer-section-body { padding: 0.85rem 1rem 1rem; }
#drawerCotizacion .drawer-field {
  display: grid; grid-template-columns: 130px 1fr; gap: 0.35rem 0.75rem;
  padding: 0.4rem 0; border-bottom: 1px solid #f1f5f9;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem; line-height: 1.45;
}
#drawerCotizacion .drawer-field:last-child { border-bottom: none; }
#drawerCotizacion .drawer-field-label { font-weight: 600; color: #64748b; }
#drawerCotizacion .drawer-field-value { color: #0f172a; word-break: break-word; }
#drawerCotizacion .drawer-field-value a { color: #059669; text-decoration: none; font-weight: 600; }
#drawerCotizacion .drawer-item-list { list-style: none; margin: 0; padding: 0; }
#drawerCotizacion .drawer-item-list li {
  padding: 0.45rem 0; border-bottom: 1px solid #f1f5f9;
  font-size: 0.8125rem; color: #334155; line-height: 1.45;
}
#drawerCotizacion .drawer-item-list li:last-child { border-bottom: none; }
#drawerCotizacion .drawer-item-qty {
  display: inline-block; min-width: 1.5rem; font-weight: 700; color: #059669; margin-right: 0.35rem;
}
#drawerCotizacion .drawer-observaciones {
  margin-top: 0.25rem; padding: 0.75rem; background: #f8fafc;
  border: 1px solid #e2e8f0; border-radius: 8px;
  font-size: 0.875rem; line-height: 1.55; color: #1e293b; white-space: pre-wrap;
}
#drawerCotizacion .drawer-footer {
  display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;
  padding: 0.875rem 1.5rem; border-top: 1px solid #e2e8f0; background: #fff; flex-shrink: 0;
}
#drawerCotizacion .drawer-footer-actions { display: flex; flex-wrap: wrap; gap: 0.55rem; }
#drawerCotizacion .drawer-btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;
  min-height: 40px; padding: 0.55rem 1rem; border-radius: 8px;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem; font-weight: 600; cursor: pointer; text-decoration: none; border: none;
}
#drawerCotizacion .drawer-btn-secondary { background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; }
#drawerCotizacion .drawer-btn-link { background: #fff; color: #059669; border: 1px solid #a7f3d0; }
#drawerCotizacion .drawer-btn-primary { background: #059669; color: #fff; }

@media (max-width: 768px) {
  #drawerCotizacion { width: 100vw; max-width: 100vw; }
  #drawerCotizacion .drawer-field { grid-template-columns: 1fr; }
}
</style>

<div id="drawerCotizacionOverlay" aria-hidden="true"></div>
<aside id="drawerCotizacion" role="dialog" aria-modal="true">
  <div class="drawer-header" id="drawerCotizacionHeader">
    <div>
      <h2 class="drawer-header-title" id="drawerCotizacionTitulo">Cotización</h2>
      <div class="drawer-header-subtitle" id="drawerCotizacionSubtitulo"></div>
      <div class="drawer-header-badges" id="drawerCotizacionBadges"></div>
    </div>
    <button type="button" class="drawer-close" id="btnCerrarDrawerCotizacion" aria-label="Cerrar"><i class="icon-remove"></i></button>
  </div>
  <div class="drawer-body" id="drawerCotizacionBody">
    <div class="drawer-cargando"><div class="drawer-spinner"></div><div>Cargando cotización...</div></div>
  </div>
  <div class="drawer-footer">
    <button type="button" class="drawer-btn drawer-btn-secondary" id="btnCancelarDrawerCotizacion">Cerrar</button>
    <div class="drawer-footer-actions">
      <a href="#" class="drawer-btn drawer-btn-link" id="btnCotizacionPaginaCompleta" target="_blank" rel="noopener" style="display:none;">
        <i class="icon-external-link"></i> Página completa
      </a>
      <a href="#" class="drawer-btn drawer-btn-primary" id="btnCotizacionEditar" target="_blank" rel="noopener" style="display:none;">
        <i class="icon-edit"></i> Editar cotización
      </a>
    </div>
  </div>
</aside>

<script>
(function () {
  var overlay = document.getElementById('drawerCotizacionOverlay');
  var drawer = document.getElementById('drawerCotizacion');
  var body = document.getElementById('drawerCotizacionBody');
  var header = document.getElementById('drawerCotizacionHeader');
  var titulo = document.getElementById('drawerCotizacionTitulo');
  var subtitulo = document.getElementById('drawerCotizacionSubtitulo');
  var badges = document.getElementById('drawerCotizacionBadges');
  var btnCompleto = document.getElementById('btnCotizacionPaginaCompleta');
  var btnEditar = document.getElementById('btnCotizacionEditar');
  if (!overlay || !drawer || !body) return;

  document.body.appendChild(overlay);
  document.body.appendChild(drawer);

  var cargandoFetch = false;
  var clienteActualId = 0;

  function escaparHtml(t) {
    var d = document.createElement('div');
    d.textContent = t == null ? '' : String(t);
    return d.innerHTML;
  }

  function formatearMoneda(simbolo, valor) {
    return escaparHtml(simbolo) + Number(valor || 0).toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
  }

  function renderListaItems(items, tituloSeccion) {
    if (!items || !items.length) return '';
    var html = '<div class="drawer-section"><h3 class="drawer-section-title">' + escaparHtml(tituloSeccion) + '</h3><div class="drawer-section-body"><ul class="drawer-item-list">';
    items.forEach(function (item) {
      html += '<li><span class="drawer-item-qty">' + escaparHtml(item.cantidad) + '×</span>' + escaparHtml(item.nombre) + '</li>';
    });
    html += '</ul></div></div>';
    return html;
  }

  function renderDetalle(data) {
    var c = data.cotizacion || {};
    var simbolo = c.monedaSimbolo || '$';
    var tot = data.totales || {};

    titulo.textContent = c.tipoLabel + ' #' + c.id;
    subtitulo.textContent = (data.cliente && data.cliente.nombre) ? data.cliente.nombre.toUpperCase() : '';
    header.classList.toggle('is-vendida', !!c.vendida);

    badges.innerHTML = '';
    badges.innerHTML += '<span class="drawer-badge">' + escaparHtml(c.vendidaLabel) + '</span>';
    if (c.descuentosEspeciales) badges.innerHTML += '<span class="drawer-badge">Desc. especial</span>';
    if (data.pedido && data.pedido.id) badges.innerHTML += '<span class="drawer-badge">Pedido #' + data.pedido.id + '</span>';

    var html = '<div class="drawer-stats">';
    html += '<div class="drawer-stat"><span class="drawer-stat-num">' + formatearMoneda(simbolo, tot.total) + '</span><span class="drawer-stat-label">Total</span></div>';
    html += '<div class="drawer-stat"><span class="drawer-stat-num">' + escaparHtml((data.items && data.items.totalLineas) || 0) + '</span><span class="drawer-stat-label">Ítems</span></div>';
    html += '<div class="drawer-stat"><span class="drawer-stat-num">' + escaparHtml(c.fechaPropuesta || '—') + '</span><span class="drawer-stat-label">F. propuesta</span></div>';
    html += '</div>';

    html += '<div class="drawer-section"><h3 class="drawer-section-title">Información general</h3><div class="drawer-section-body">';
    html += '<div class="drawer-field"><span class="drawer-field-label">Cliente</span><span class="drawer-field-value">' + escaparHtml(data.cliente && data.cliente.nombre) + '</span></div>';
    html += '<div class="drawer-field"><span class="drawer-field-label">Sucursal</span><span class="drawer-field-value">' + escaparHtml(data.sucursal && data.sucursal.nombre || '—') + '</span></div>';
    html += '<div class="drawer-field"><span class="drawer-field-label">Contacto</span><span class="drawer-field-value">' + escaparHtml(data.contacto && data.contacto.nombre || '—') + '</span></div>';
    html += '<div class="drawer-field"><span class="drawer-field-label">Vendedor</span><span class="drawer-field-value">' + escaparHtml(data.vendedor && data.vendedor.nombre || '—') + '</span></div>';
    html += '<div class="drawer-field"><span class="drawer-field-label">Creador</span><span class="drawer-field-value">' + escaparHtml(data.creador && data.creador.nombre || '—') + '</span></div>';
    html += '<div class="drawer-field"><span class="drawer-field-label">Vencimiento</span><span class="drawer-field-value">' + escaparHtml(c.fechaVencimiento || '—') + '</span></div>';
    if (c.ticketId) {
      html += '<div class="drawer-field"><span class="drawer-field-label">Ticket</span><span class="drawer-field-value">#' + escaparHtml(c.ticketId) + '</span></div>';
    }
    html += '</div></div>';

    html += '<div class="drawer-section"><h3 class="drawer-section-title">Totales</h3><div class="drawer-section-body">';
    html += '<div class="drawer-field"><span class="drawer-field-label">Subtotal</span><span class="drawer-field-value">' + formatearMoneda(simbolo, tot.subtotal) + '</span></div>';
    html += '<div class="drawer-field"><span class="drawer-field-label">Descuento</span><span class="drawer-field-value">' + formatearMoneda(simbolo, tot.descuento) + '</span></div>';
    html += '<div class="drawer-field"><span class="drawer-field-label">IVA</span><span class="drawer-field-value">' + formatearMoneda(simbolo, tot.iva) + '</span></div>';
    html += '<div class="drawer-field"><span class="drawer-field-label">Envío</span><span class="drawer-field-value">' + formatearMoneda(simbolo, tot.envio) + '</span></div>';
    html += '<div class="drawer-field"><span class="drawer-field-label">Total</span><span class="drawer-field-value"><strong>' + formatearMoneda(simbolo, tot.total) + '</strong></span></div>';
    html += '</div></div>';

    if (data.items) {
      html += renderListaItems(data.items.productos, 'Productos');
      html += renderListaItems(data.items.combos, 'Combos');
      html += renderListaItems(data.items.servicios, 'Servicios');
    }

    if (c.observaciones) {
      html += '<div class="drawer-section"><h3 class="drawer-section-title">Observaciones</h3><div class="drawer-section-body"><div class="drawer-observaciones">' + escaparHtml(c.observaciones) + '</div></div></div>';
    }

    body.innerHTML = html;

    if (data.urls && data.urls.paginaCompleta) {
      btnCompleto.href = data.urls.paginaCompleta;
      btnCompleto.style.display = '';
    } else {
      btnCompleto.style.display = 'none';
    }

    if (data.permisos && data.permisos.puedeEditar && data.urls && data.urls.paginaCompleta) {
      btnEditar.href = data.urls.paginaCompleta + '#productos';
      btnEditar.style.display = '';
    } else {
      btnEditar.style.display = 'none';
    }
  }

  function mostrarCargando() {
    body.innerHTML = '<div class="drawer-cargando"><div class="drawer-spinner"></div><div>Cargando cotización...</div></div>';
    titulo.textContent = 'Cotización';
    subtitulo.textContent = '';
    badges.innerHTML = '';
    btnCompleto.style.display = 'none';
    btnEditar.style.display = 'none';
  }

  function mostrarError(msg) {
    body.innerHTML = '<div class="drawer-error">' + escaparHtml(msg) + '</div>';
  }

  function cerrarDrawer() {
    if (cargandoFetch) return;
    overlay.classList.remove('is-open');
    drawer.classList.remove('is-open');
    document.body.classList.remove('drawer-cotizacion-open');
    overlay.setAttribute('aria-hidden', 'true');
    clienteActualId = 0;
    mostrarCargando();
  }

  function abrirDrawer(cotizacionId, clienteId) {
    cotizacionId = parseInt(cotizacionId, 10);
    clienteId = parseInt(clienteId, 10);
    if (!cotizacionId) return;

    clienteActualId = clienteId;
    mostrarCargando();
    overlay.classList.add('is-open');
    drawer.classList.add('is-open');
    document.body.classList.add('drawer-cotizacion-open');
    overlay.setAttribute('aria-hidden', 'false');
    cargandoFetch = true;

    var url = 'ajax/ajax-cotizaciones-resumen-drawer.php?cotizacion=' + encodeURIComponent(cotizacionId);
    if (clienteId) url += '&cliente=' + encodeURIComponent(clienteId);

    fetch(url)
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data.success) throw new Error(data.message || 'No se pudo cargar la cotización.');
        renderDetalle(data.data);
      })
      .catch(function (err) {
        mostrarError(err.message || 'Error al cargar la cotización.');
      })
      .finally(function () {
        cargandoFetch = false;
      });
  }

  document.getElementById('btnCerrarDrawerCotizacion').addEventListener('click', cerrarDrawer);
  document.getElementById('btnCancelarDrawerCotizacion').addEventListener('click', cerrarDrawer);
  overlay.addEventListener('click', cerrarDrawer);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer.classList.contains('is-open')) cerrarDrawer();
  });
  document.addEventListener('click', function (e) {
    var trigger = e.target.closest('.js-abrir-cotizacion-drawer');
    if (!trigger) return;
    e.preventDefault();
    abrirDrawer(trigger.getAttribute('data-cotizacion-id'), trigger.getAttribute('data-cliente-id'));
  });

  window.abrirDrawerCotizacionCliente = abrirDrawer;
})();
</script>
