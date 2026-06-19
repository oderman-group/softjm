<style>
#drawerTicketOverlay {
  position: fixed !important;
  top: 0; left: 0; right: 0; bottom: 0;
  width: 100%; height: 100%;
  background: rgba(15, 23, 42, 0.45);
  z-index: 99994;
  opacity: 0; visibility: hidden; pointer-events: none;
  transition: opacity 0.3s ease, visibility 0.3s ease;
}
#drawerTicketOverlay.is-open { opacity: 1; visibility: visible; pointer-events: auto; }

#drawerTicket {
  position: fixed !important;
  top: 0; right: 0; left: auto; bottom: 0;
  width: 70vw; max-width: 70vw;
  height: 100vh; height: 100dvh;
  margin: 0; padding: 0; border: none;
  background: #f8fafc;
  z-index: 99995;
  display: flex; flex-direction: column;
  box-sizing: border-box;
  box-shadow: -8px 0 32px rgba(15, 23, 42, 0.15);
  transform: translateX(100%);
  transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}
#drawerTicket.is-open { transform: translateX(0); }
body.drawer-ticket-open { overflow: hidden !important; }

#drawerTicket .drawer-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid rgba(255,255,255,0.15);
  background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
  color: #fff; flex-shrink: 0;
}
#drawerTicket .drawer-header.is-cerrado {
  background: linear-gradient(135deg, #475569 0%, #334155 100%);
}
#drawerTicket .drawer-header-title {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 1.125rem; font-weight: 700; margin: 0;
  display: flex; flex-direction: column; gap: 0.25rem;
}
#drawerTicket .drawer-header-subtitle { font-size: 0.8125rem; font-weight: 500; opacity: 0.92; }
#drawerTicket .drawer-header-badges { display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.35rem; }
#drawerTicket .drawer-badge {
  display: inline-flex; align-items: center;
  padding: 0.15rem 0.55rem; border-radius: 999px;
  font-size: 0.6875rem; font-weight: 700; text-transform: uppercase;
  background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.25);
}
#drawerTicket .drawer-close {
  background: rgba(255,255,255,0.15); border: none; color: #fff;
  width: 36px; height: 36px; border-radius: 8px; cursor: pointer;
  display: flex; align-items: center; justify-content: center; font-size: 1.125rem;
}
#drawerTicket .drawer-body { flex: 1; overflow-y: auto; padding: 1.25rem 1.5rem 1.5rem; }
#drawerTicket .drawer-cargando, #drawerTicket .drawer-vacio, #drawerTicket .drawer-error {
  text-align: center; padding: 2.5rem 1rem; color: #64748b;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif; font-size: 0.9375rem;
}
#drawerTicket .drawer-error { color: #b91c1c; }
#drawerTicket .drawer-spinner {
  display: inline-block; width: 28px; height: 28px;
  border: 3px solid #e2e8f0; border-top-color: #2563eb;
  border-radius: 50%; animation: drawer-ticket-spin 0.6s linear infinite; margin-bottom: 0.75rem;
}
@keyframes drawer-ticket-spin { to { transform: rotate(360deg); } }

#drawerTicket .drawer-section {
  background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;
  margin-bottom: 1rem; overflow: hidden;
}
#drawerTicket .drawer-section-title {
  margin: 0; padding: 0.85rem 1rem;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.04em; color: #334155;
  background: #f1f5f9; border-bottom: 1px solid #e2e8f0;
}
#drawerTicket .drawer-section-body { padding: 0.85rem 1rem 1rem; }
#drawerTicket .drawer-field {
  display: grid; grid-template-columns: 130px 1fr; gap: 0.35rem 0.75rem;
  padding: 0.4rem 0; border-bottom: 1px solid #f1f5f9;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem; line-height: 1.45;
}
#drawerTicket .drawer-field:last-child { border-bottom: none; }
#drawerTicket .drawer-field-label { font-weight: 600; color: #64748b; }
#drawerTicket .drawer-field-value { color: #0f172a; word-break: break-word; }
#drawerTicket .drawer-field-value a { color: #2563eb; text-decoration: none; }
#drawerTicket .drawer-observaciones {
  margin-top: 0.5rem; padding: 0.75rem; background: #f8fafc;
  border: 1px solid #e2e8f0; border-radius: 8px;
  font-size: 0.875rem; line-height: 1.55; color: #1e293b;
}
#drawerTicket .drawer-alert-info {
  margin-bottom: 1rem; padding: 0.75rem 0.9rem; border-radius: 8px;
  background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af;
  font-size: 0.8125rem; line-height: 1.45;
}
#drawerTicket .drawer-stats { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem; }
#drawerTicket .drawer-stat {
  flex: 1; min-width: 90px; background: #fff; border: 1px solid #e2e8f0;
  border-radius: 10px; padding: 0.65rem 0.75rem; text-align: center;
}
#drawerTicket .drawer-stat-num { display: block; font-size: 1.25rem; font-weight: 800; color: #0f172a; }
#drawerTicket .drawer-stat-label {
  display: block; font-size: 0.6875rem; font-weight: 600;
  text-transform: uppercase; color: #64748b;
}
#drawerTicket .drawer-timeline-title {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.875rem; font-weight: 700; color: #334155; margin: 0 0 0.75rem 0;
}
#drawerTicket .drawer-seg-item {
  background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
  margin-bottom: 0.65rem; overflow: hidden;
}
#drawerTicket .drawer-seg-item.is-pendiente { border-left: 4px solid #dc2626; }
#drawerTicket .drawer-seg-item.is-completado { border-left: 4px solid #4169e1; }
#drawerTicket .drawer-seg-header {
  display: flex; align-items: flex-start; justify-content: space-between;
  gap: 0.75rem; padding: 0.75rem 0.85rem; cursor: pointer;
}
#drawerTicket .drawer-seg-header:hover { background: #f8fafc; }
#drawerTicket .drawer-seg-title {
  font-size: 0.8125rem; font-weight: 700; color: #0f172a; margin: 0 0 0.2rem 0;
}
#drawerTicket .drawer-seg-meta { font-size: 0.75rem; color: #64748b; }
#drawerTicket .drawer-seg-badge {
  flex-shrink: 0; padding: 0.15rem 0.5rem; border-radius: 999px;
  font-size: 0.6875rem; font-weight: 700; text-transform: uppercase;
}
#drawerTicket .drawer-seg-badge.is-pendiente { background: #fee2e2; color: #b91c1c; }
#drawerTicket .drawer-seg-badge.is-completado { background: #dbeafe; color: #1d4ed8; }
#drawerTicket .drawer-seg-preview {
  padding: 0 0.85rem 0.75rem; font-size: 0.8125rem; color: #475569;
  max-height: 3.6em; overflow: hidden;
}
#drawerTicket .drawer-seg-body { display: none; padding: 0 0.85rem 0.85rem; border-top: 1px solid #f1f5f9; }
#drawerTicket .drawer-seg-item.is-expanded .drawer-seg-body { display: block; padding-top: 0.75rem; }
#drawerTicket .drawer-seg-item.is-expanded .drawer-seg-preview { display: none; }
#drawerTicket .drawer-seg-obs {
  padding: 0.65rem 0.75rem; background: #f8fafc; border: 1px solid #e2e8f0;
  border-radius: 8px; font-size: 0.8125rem; line-height: 1.5; margin-bottom: 0.65rem;
}
#drawerTicket .drawer-link-btn {
  display: inline-flex; align-items: center; gap: 0.25rem;
  padding: 0.35rem 0.65rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600;
  text-decoration: none; border: 1px solid #cbd5e1; background: #fff; color: #334155; cursor: pointer;
}
#drawerTicket .drawer-link-btn-primary { background: #eff6ff; border-color: #93c5fd; color: #1d4ed8; }
#drawerTicket .drawer-footer {
  display: flex; align-items: center; justify-content: space-between;
  gap: 0.75rem; padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0; background: #fff;
}
#drawerTicket .drawer-btn {
  display: inline-flex; align-items: center; gap: 0.35rem;
  padding: 0.55rem 1rem; border-radius: 8px;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem; font-weight: 600; cursor: pointer; border: none; text-decoration: none;
}
#drawerTicket .drawer-btn-secondary { background: #f1f5f9; color: #334155; }
#drawerTicket .drawer-btn-primary { background: #2563eb; color: #fff; }
#drawerTicket .drawer-btn-primary:hover { background: #1d4ed8; color: #fff; }

@media (max-width: 768px) {
  #drawerTicket { width: 100vw; max-width: 100vw; }
  #drawerTicket .drawer-field { grid-template-columns: 1fr; }
}
</style>

<div id="drawerTicketOverlay" aria-hidden="true"></div>
<aside id="drawerTicket" role="dialog" aria-labelledby="drawerTicketTitulo" aria-modal="true">
  <div class="drawer-header" id="drawerTicketHeader">
    <div class="drawer-header-title">
      <span id="drawerTicketTitulo"><i class="icon-ticket" aria-hidden="true"></i> Ticket</span>
      <span class="drawer-header-subtitle" id="drawerTicketSubtitulo"></span>
      <div class="drawer-header-badges" id="drawerTicketBadges"></div>
    </div>
    <button type="button" class="drawer-close" id="btnCerrarDrawerTicket" aria-label="Cerrar">
      <i class="icon-remove" aria-hidden="true"></i>
    </button>
  </div>
  <div class="drawer-body" id="drawerTicketBody">
    <div class="drawer-cargando"><div class="drawer-spinner"></div><div>Cargando ticket...</div></div>
  </div>
  <div class="drawer-footer">
    <button type="button" class="drawer-btn drawer-btn-secondary" id="btnCancelarDrawerTicket">Cerrar</button>
    <a href="#" class="drawer-btn drawer-btn-primary" id="btnAbrirTicketCompleto" target="_blank" rel="noopener">
      <i class="icon-external-link" aria-hidden="true"></i> Abrir página completa
    </a>
  </div>
</aside>

<script>
(function () {
  var overlay = document.getElementById('drawerTicketOverlay');
  var drawer = document.getElementById('drawerTicket');
  var header = document.getElementById('drawerTicketHeader');
  var body = document.getElementById('drawerTicketBody');
  var subtitulo = document.getElementById('drawerTicketSubtitulo');
  var badges = document.getElementById('drawerTicketBadges');
  var titulo = document.getElementById('drawerTicketTitulo');
  var btnCerrar = document.getElementById('btnCerrarDrawerTicket');
  var btnCancel = document.getElementById('btnCancelarDrawerTicket');
  var btnCompleto = document.getElementById('btnAbrirTicketCompleto');
  if (!overlay || !drawer || !body) return;

  document.body.appendChild(overlay);
  document.body.appendChild(drawer);

  var cargandoFetch = false;
  var clienteActualId = 0;

  function escaparHtml(t) {
    if (t === null || t === undefined) return '';
    var d = document.createElement('div');
    d.textContent = String(t);
    return d.innerHTML;
  }

  function stripHtml(h) {
    var d = document.createElement('div');
    d.innerHTML = h || '';
    return (d.textContent || d.innerText || '').trim();
  }

  function truncar(t, max) {
    var l = stripHtml(t);
    return l.length <= max ? l : l.substring(0, max) + '…';
  }

  function fila(label, valor, enlace) {
    if (!valor && valor !== 0) return '';
    var val = enlace
      ? '<span class="drawer-field-value"><a href="' + escaparHtml(enlace) + '" target="_blank" rel="noopener">' + escaparHtml(valor) + '</a></span>'
      : '<span class="drawer-field-value">' + escaparHtml(valor) + '</span>';
    return '<div class="drawer-field"><span class="drawer-field-label">' + escaparHtml(label) + '</span>' + val + '</div>';
  }

  function renderTicket(ticket, urls, permisos) {
    var html = '';
    if (ticket.estadoClase === 'cerrado') {
      html += '<div class="drawer-alert-info"><strong>Ticket cerrado.</strong> No es posible hacer cambios. Use la página completa para consultar el historial.</div>';
    } else if (!permisos.puedeEditarTicket) {
      html += '<div class="drawer-alert-info">Este ticket no admite edición en este momento.</div>';
    }

    html += '<section class="drawer-section"><h3 class="drawer-section-title">Datos del ticket</h3><div class="drawer-section-body">';
    html += fila('Cliente', ticket.clienteNombre, urls.cliente);
    html += fila('Asunto', ticket.asunto);
    html += fila('Fecha inicio', ticket.fechaCreacion);
    html += fila('Tipo', ticket.tipo);
    html += fila('Estado', ticket.estadoLabel);
    html += fila('Prioridad', ticket.prioridad);
    html += fila('Responsable', ticket.responsable);
    html += fila('Canal', ticket.canal || '—');

    if (ticket.tipoId !== 3) {
      html += '<div style="margin:0.5rem 0 0.25rem;font-weight:700;font-size:0.75rem;color:#334155;text-transform:uppercase;">Negociación</div>';
      html += fila('Valor', ticket.valor || '—');
      html += fila('Etapa', ticket.etapa);
      html += fila('Tipo negocio', ticket.tipoNegocio);
      html += fila('Origen', ticket.origenNegocio);
      if (ticket.razonGanado) html += fila('Razón ganado', ticket.razonGanado);
      if (ticket.razonPerdido) html += fila('Razón perdido', ticket.razonPerdido);
    }

    if (ticket.cotizacionId && urls.cotizacion) {
      html += fila('Cotización', '#' + ticket.cotizacionId, urls.cotizacion);
    }

    if (ticket.observaciones) {
      html += '<div class="drawer-field" style="display:block;border-bottom:none;margin-top:0.5rem;">';
      html += '<span class="drawer-field-label" style="display:block;margin-bottom:0.35rem;">Observaciones</span>';
      html += '<div class="drawer-observaciones" id="drawerTicketObservaciones"></div></div>';
    }

    html += '</div></section>';
    return html;
  }

  function renderStats(stats) {
    return '<div class="drawer-stats">' +
      '<div class="drawer-stat"><span class="drawer-stat-num">' + escaparHtml(stats.total) + '</span><span class="drawer-stat-label">Seguimientos</span></div>' +
      '<div class="drawer-stat"><span class="drawer-stat-num">' + escaparHtml(stats.completados) + '</span><span class="drawer-stat-label">Completados</span></div>' +
      '<div class="drawer-stat"><span class="drawer-stat-num">' + escaparHtml(stats.pendientes) + '</span><span class="drawer-stat-label">Pendientes</span></div>' +
      '</div>';
  }

  function renderSegItem(seg, index, permisos) {
    var cls = seg.estadoClase === 'completado' ? 'is-completado' : 'is-pendiente';
    var expanded = '';
    var preview = truncar(seg.observacion, 100);
    var html = '<article class="drawer-seg-item ' + cls + expanded + '">';
    html += '<div class="drawer-seg-header" role="button" tabindex="0" aria-expanded="false">';
    html += '<div><p class="drawer-seg-title">Seguimiento #' + escaparHtml(seg.id) + '</p>';
    html += '<div class="drawer-seg-meta">' + escaparHtml(seg.fechaReporte) + ' · ' + escaparHtml(seg.responsable);
    if (seg.fechaContacto) html += '<br>Contacto: ' + escaparHtml(seg.fechaContacto);
    html += '</div></div>';
    html += '<span class="drawer-seg-badge ' + cls + '">' + escaparHtml(seg.estadoLabel) + '</span></div>';
    if (preview) html += '<div class="drawer-seg-preview">' + escaparHtml(preview) + '</div>';
    html += '<div class="drawer-seg-body"><div class="drawer-seg-obs" data-obs-id="' + escaparHtml(seg.id) + '"></div>';
    html += '<div style="display:flex;flex-wrap:wrap;gap:0.5rem;">';
    if (permisos.puedeVerDetalleSeguimiento && typeof window.abrirDrawerSeguimientoCliente === 'function') {
      html += '<button type="button" class="drawer-link-btn drawer-link-btn-primary js-ver-seg-drawer" data-seguimiento-id="' + escaparHtml(seg.id) + '"><i class="icon-eye-open"></i> Ver detalle</button>';
    }
    if (seg.urls && seg.urls.editarCompleta) {
      html += '<a href="' + escaparHtml(seg.urls.editarCompleta) + '" class="drawer-link-btn" target="_blank" rel="noopener"><i class="icon-external-link"></i> Página completa</a>';
    }
    html += '</div></div></article>';
    return html;
  }

  function bindItems(seguimientos) {
    body.querySelectorAll('.drawer-seg-item').forEach(function (item) {
      var h = item.querySelector('.drawer-seg-header');
      if (!h) return;
      function toggle() {
        var expanded = item.classList.toggle('is-expanded');
        h.setAttribute('aria-expanded', expanded ? 'true' : 'false');
      }
      h.addEventListener('click', toggle);
      h.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); }
      });
    });
    seguimientos.forEach(function (seg) {
      var el = body.querySelector('[data-obs-id="' + seg.id + '"]');
      if (el) el.innerHTML = seg.observacion || '—';
    });
    body.querySelectorAll('.js-ver-seg-drawer').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        if (typeof window.abrirDrawerSeguimientoCliente === 'function') {
          window.abrirDrawerSeguimientoCliente(btn.getAttribute('data-seguimiento-id'), clienteActualId);
        }
      });
    });
  }

  function renderDetalle(data) {
    var ticket = data.ticket;
    var stats = data.estadisticas || { total: 0, completados: 0, pendientes: 0 };
    var seguimientos = data.seguimientos || [];
    var urls = data.urls || {};
    var permisos = data.permisos || {};

    header.classList.toggle('is-cerrado', ticket.estadoClase === 'cerrado');
    titulo.innerHTML = '<i class="icon-ticket" aria-hidden="true"></i> Ticket #' + escaparHtml(ticket.id);
    subtitulo.textContent = ticket.asunto || '';
    badges.innerHTML = '';
    [ticket.tipo, ticket.estadoLabel, ticket.prioridad].forEach(function (txt) {
      if (!txt) return;
      var b = document.createElement('span');
      b.className = 'drawer-badge';
      b.textContent = txt;
      badges.appendChild(b);
    });

    var html = renderTicket(ticket, urls, permisos);
    html += renderStats(stats);
    html += '<h3 class="drawer-timeline-title">Seguimientos <small style="font-weight:500;color:#64748b;">(más recientes primero)</small></h3>';

    if (seguimientos.length === 0) {
      html += '<div class="drawer-vacio">Este ticket aún no tiene seguimientos.</div>';
      if (permisos.puedeAgregarSeguimiento) {
        html += '<p style="margin-top:0.75rem;"><a href="clientes-seguimiento-agregar.php?idTK=' + escaparHtml(ticket.id) + '&cte=' + escaparHtml(clienteActualId) + '" class="drawer-link-btn drawer-link-btn-primary" target="_blank" rel="noopener"><i class="icon-plus"></i> Agregar seguimiento</a></p>';
      }
    } else {
      seguimientos.forEach(function (seg, i) { html += renderSegItem(seg, i, permisos); });
    }

    body.innerHTML = html;

    var obsEl = document.getElementById('drawerTicketObservaciones');
    if (obsEl) obsEl.innerHTML = ticket.observaciones || '';

    bindItems(seguimientos);

    if (urls.paginaCompleta) {
      btnCompleto.href = urls.paginaCompleta;
      btnCompleto.style.display = '';
    } else {
      btnCompleto.style.display = 'none';
    }
  }

  function mostrarError(msg) {
    body.innerHTML = '<div class="drawer-error">' + escaparHtml(msg) + '</div>';
    btnCompleto.style.display = 'none';
  }

  function mostrarCargando() {
    body.innerHTML = '<div class="drawer-cargando"><div class="drawer-spinner"></div><div>Cargando ticket...</div></div>';
    subtitulo.textContent = '';
    badges.innerHTML = '';
    btnCompleto.style.display = 'none';
  }

  function cerrarDrawer() {
    if (cargandoFetch) return;
    overlay.classList.remove('is-open');
    drawer.classList.remove('is-open');
    document.body.classList.remove('drawer-ticket-open');
    overlay.setAttribute('aria-hidden', 'true');
    clienteActualId = 0;
    mostrarCargando();
  }

  function abrirDrawer(ticketId, clienteId) {
    ticketId = parseInt(ticketId, 10);
    clienteId = parseInt(clienteId, 10);
    if (!ticketId || !clienteId) return;

    clienteActualId = clienteId;
    mostrarCargando();
    overlay.classList.add('is-open');
    drawer.classList.add('is-open');
    document.body.classList.add('drawer-ticket-open');
    overlay.setAttribute('aria-hidden', 'false');
    cargandoFetch = true;

    fetch('ajax/ajax-clientes-ticket-detalle.php?ticket=' + encodeURIComponent(ticketId) + '&cliente=' + encodeURIComponent(clienteId))
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data.success) throw new Error(data.message || 'No se pudo cargar el ticket.');
        renderDetalle(data.data);
      })
      .catch(function (err) { mostrarError(err.message || 'Error al cargar el ticket.'); })
      .finally(function () { cargandoFetch = false; });
  }

  btnCerrar.addEventListener('click', cerrarDrawer);
  btnCancel.addEventListener('click', cerrarDrawer);
  overlay.addEventListener('click', cerrarDrawer);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer.classList.contains('is-open')) cerrarDrawer();
  });
  document.addEventListener('click', function (e) {
    var t = e.target.closest('.js-abrir-ticket-drawer');
    if (!t) return;
    e.preventDefault();
    abrirDrawer(t.getAttribute('data-ticket-id'), t.getAttribute('data-cliente-id'));
  });

  window.abrirDrawerTicketCliente = abrirDrawer;
})();
</script>
