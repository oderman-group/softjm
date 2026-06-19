<style>
#drawerTicketSegOverlay {
  position: fixed !important;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  height: 100%;
  background: rgba(15, 23, 42, 0.45);
  z-index: 99996;
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
  transition: opacity 0.3s ease, visibility 0.3s ease;
}

#drawerTicketSegOverlay.is-open {
  opacity: 1;
  visibility: visible;
  pointer-events: auto;
}

#drawerTicketSeg {
  position: fixed !important;
  top: 0;
  right: 0;
  left: auto;
  bottom: 0;
  width: 70vw;
  max-width: 70vw;
  height: 100vh;
  height: 100dvh;
  margin: 0;
  padding: 0;
  border: none;
  background: #f8fafc;
  z-index: 99997;
  display: flex;
  flex-direction: column;
  box-sizing: border-box;
  box-shadow: -8px 0 32px rgba(15, 23, 42, 0.15);
  transform: translateX(100%);
  transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}

#drawerTicketSeg.is-open {
  transform: translateX(0);
}

body.drawer-ticket-seg-open {
  overflow: hidden !important;
}

#drawerTicketSeg .drawer-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.15);
  background: linear-gradient(135deg, #059669 0%, #047857 100%);
  color: #fff;
  flex-shrink: 0;
}

#drawerTicketSeg .drawer-header.is-cerrado {
  background: linear-gradient(135deg, #475569 0%, #334155 100%);
}

#drawerTicketSeg .drawer-header-title {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 1.125rem;
  font-weight: 700;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

#drawerTicketSeg .drawer-header-subtitle {
  font-size: 0.8125rem;
  font-weight: 500;
  opacity: 0.92;
}

#drawerTicketSeg .drawer-header-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 0.35rem;
  margin-top: 0.35rem;
}

#drawerTicketSeg .drawer-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.15rem 0.55rem;
  border-radius: 999px;
  font-size: 0.6875rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  background: rgba(255, 255, 255, 0.18);
  border: 1px solid rgba(255, 255, 255, 0.25);
}

#drawerTicketSeg .drawer-close {
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

#drawerTicketSeg .drawer-body {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 1.25rem 1.5rem 1.5rem;
}

#drawerTicketSeg .drawer-cargando,
#drawerTicketSeg .drawer-vacio,
#drawerTicketSeg .drawer-error {
  text-align: center;
  padding: 2.5rem 1rem;
  color: #64748b;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.9375rem;
}

#drawerTicketSeg .drawer-error { color: #b91c1c; }

#drawerTicketSeg .drawer-spinner {
  display: inline-block;
  width: 28px;
  height: 28px;
  border: 3px solid #e2e8f0;
  border-top-color: #059669;
  border-radius: 50%;
  animation: drawer-ticket-seg-spin 0.6s linear infinite;
  margin-bottom: 0.75rem;
}

@keyframes drawer-ticket-seg-spin {
  to { transform: rotate(360deg); }
}

#drawerTicketSeg .drawer-section {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  margin-bottom: 1rem;
  overflow: hidden;
}

#drawerTicketSeg .drawer-section-title {
  margin: 0;
  padding: 0.85rem 1rem;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  color: #334155;
  background: #f1f5f9;
  border-bottom: 1px solid #e2e8f0;
}

#drawerTicketSeg .drawer-section-body {
  padding: 0.85rem 1rem 1rem;
}

#drawerTicketSeg .drawer-field {
  display: grid;
  grid-template-columns: 120px 1fr;
  gap: 0.35rem 0.75rem;
  padding: 0.4rem 0;
  border-bottom: 1px solid #f1f5f9;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem;
  line-height: 1.45;
}

#drawerTicketSeg .drawer-field:last-child { border-bottom: none; }

#drawerTicketSeg .drawer-field-label {
  font-weight: 600;
  color: #64748b;
}

#drawerTicketSeg .drawer-field-value {
  color: #0f172a;
  word-break: break-word;
}

#drawerTicketSeg .drawer-stats {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

#drawerTicketSeg .drawer-stat {
  flex: 1;
  min-width: 90px;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  padding: 0.65rem 0.75rem;
  text-align: center;
}

#drawerTicketSeg .drawer-stat-num {
  display: block;
  font-size: 1.25rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.2;
}

#drawerTicketSeg .drawer-stat-label {
  display: block;
  font-size: 0.6875rem;
  font-weight: 600;
  text-transform: uppercase;
  color: #64748b;
  letter-spacing: 0.03em;
}

#drawerTicketSeg .drawer-timeline-title {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.875rem;
  font-weight: 700;
  color: #334155;
  margin: 0 0 0.75rem 0;
}

#drawerTicketSeg .drawer-seg-item {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  margin-bottom: 0.65rem;
  overflow: hidden;
}

#drawerTicketSeg .drawer-seg-item.is-pendiente {
  border-left: 4px solid #dc2626;
}

#drawerTicketSeg .drawer-seg-item.is-completado {
  border-left: 4px solid #4169e1;
}

#drawerTicketSeg .drawer-seg-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 0.75rem;
  padding: 0.75rem 0.85rem;
  cursor: pointer;
  user-select: none;
}

#drawerTicketSeg .drawer-seg-header:hover {
  background: #f8fafc;
}

#drawerTicketSeg .drawer-seg-header-main {
  flex: 1;
  min-width: 0;
}

#drawerTicketSeg .drawer-seg-title {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 0.2rem 0;
}

#drawerTicketSeg .drawer-seg-meta {
  font-size: 0.75rem;
  color: #64748b;
  line-height: 1.4;
}

#drawerTicketSeg .drawer-seg-badge {
  flex-shrink: 0;
  padding: 0.15rem 0.5rem;
  border-radius: 999px;
  font-size: 0.6875rem;
  font-weight: 700;
  text-transform: uppercase;
}

#drawerTicketSeg .drawer-seg-badge.is-pendiente {
  background: #fee2e2;
  color: #b91c1c;
}

#drawerTicketSeg .drawer-seg-badge.is-completado {
  background: #dbeafe;
  color: #1d4ed8;
}

#drawerTicketSeg .drawer-seg-preview {
  padding: 0 0.85rem 0.75rem;
  font-size: 0.8125rem;
  color: #475569;
  line-height: 1.45;
  max-height: 3.6em;
  overflow: hidden;
}

#drawerTicketSeg .drawer-seg-body {
  display: none;
  padding: 0 0.85rem 0.85rem;
  border-top: 1px solid #f1f5f9;
}

#drawerTicketSeg .drawer-seg-item.is-expanded .drawer-seg-body {
  display: block;
  padding-top: 0.75rem;
}

#drawerTicketSeg .drawer-seg-item.is-expanded .drawer-seg-preview {
  display: none;
}

#drawerTicketSeg .drawer-seg-obs {
  padding: 0.65rem 0.75rem;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: 0.8125rem;
  line-height: 1.5;
  color: #1e293b;
  margin-bottom: 0.65rem;
}

#drawerTicketSeg .drawer-seg-proximo {
  padding: 0.65rem 0.75rem;
  background: #fff7ed;
  border: 1px solid #fed7aa;
  border-radius: 8px;
  margin-bottom: 0.65rem;
}

#drawerTicketSeg .drawer-seg-proximo-title {
  font-size: 0.75rem;
  font-weight: 700;
  color: #c2410c;
  margin: 0 0 0.35rem 0;
  text-transform: uppercase;
}

#drawerTicketSeg .drawer-seg-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

#drawerTicketSeg .drawer-link-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.35rem 0.65rem;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 600;
  text-decoration: none;
  border: 1px solid #cbd5e1;
  background: #fff;
  color: #334155;
  cursor: pointer;
}

#drawerTicketSeg .drawer-link-btn:hover {
  background: #f1f5f9;
  color: #0f172a;
}

#drawerTicketSeg .drawer-link-btn-primary {
  background: #eff6ff;
  border-color: #93c5fd;
  color: #1d4ed8;
}

#drawerTicketSeg .drawer-checks {
  display: flex;
  flex-wrap: wrap;
  gap: 0.35rem;
  margin-bottom: 0.5rem;
}

#drawerTicketSeg .drawer-check {
  font-size: 0.6875rem;
  font-weight: 600;
  padding: 0.15rem 0.45rem;
  border-radius: 5px;
  background: #f1f5f9;
  color: #64748b;
}

#drawerTicketSeg .drawer-check.is-yes {
  background: #dcfce7;
  color: #166534;
}

#drawerTicketSeg .drawer-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  padding: 1rem 1.5rem;
  border-top: 1px solid #e2e8f0;
  background: #fff;
  flex-shrink: 0;
}

#drawerTicketSeg .drawer-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.35rem;
  padding: 0.55rem 1rem;
  border-radius: 8px;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem;
  font-weight: 600;
  cursor: pointer;
  border: none;
  text-decoration: none;
}

#drawerTicketSeg .drawer-btn-secondary {
  background: #f1f5f9;
  color: #334155;
}

#drawerTicketSeg .drawer-btn-primary {
  background: #059669;
  color: #fff;
}

#drawerTicketSeg .drawer-btn-primary:hover {
  background: #047857;
  color: #fff;
}

@media (max-width: 768px) {
  #drawerTicketSeg {
    width: 100vw;
    max-width: 100vw;
  }

  #drawerTicketSeg .drawer-field {
    grid-template-columns: 1fr;
  }
}
</style>

<div id="drawerTicketSegOverlay" aria-hidden="true"></div>
<aside id="drawerTicketSeg" role="dialog" aria-labelledby="drawerTicketSegTitulo" aria-modal="true">
  <div class="drawer-header" id="drawerTicketSegHeader">
    <div class="drawer-header-title">
      <span id="drawerTicketSegTitulo"><i class="icon-ticket" aria-hidden="true"></i> Ticket</span>
      <span class="drawer-header-subtitle" id="drawerTicketSegSubtitulo"></span>
      <div class="drawer-header-badges" id="drawerTicketSegBadges"></div>
    </div>
    <button type="button" class="drawer-close" id="btnCerrarDrawerTicketSeg" aria-label="Cerrar">
      <i class="icon-remove" aria-hidden="true"></i>
    </button>
  </div>

  <div class="drawer-body" id="drawerTicketSegBody">
    <div class="drawer-cargando">
      <div class="drawer-spinner"></div>
      <div>Cargando seguimientos...</div>
    </div>
  </div>

  <div class="drawer-footer">
    <button type="button" class="drawer-btn drawer-btn-secondary" id="btnCancelarDrawerTicketSeg">Cerrar</button>
    <a href="#" class="drawer-btn drawer-btn-primary" id="btnAbrirTicketSegCompleto" target="_blank" rel="noopener">
      <i class="icon-external-link" aria-hidden="true"></i>
      Abrir página completa
    </a>
  </div>
</aside>

<script>
(function () {
  var overlay     = document.getElementById('drawerTicketSegOverlay');
  var drawer      = document.getElementById('drawerTicketSeg');
  var header      = document.getElementById('drawerTicketSegHeader');
  var body        = document.getElementById('drawerTicketSegBody');
  var subtitulo   = document.getElementById('drawerTicketSegSubtitulo');
  var badges      = document.getElementById('drawerTicketSegBadges');
  var titulo      = document.getElementById('drawerTicketSegTitulo');
  var btnCerrar   = document.getElementById('btnCerrarDrawerTicketSeg');
  var btnCancel   = document.getElementById('btnCancelarDrawerTicketSeg');
  var btnCompleto = document.getElementById('btnAbrirTicketSegCompleto');

  if (!overlay || !drawer || !body) return;

  document.body.appendChild(overlay);
  document.body.appendChild(drawer);

  var cargandoFetch = false;
  var clienteActualId = 0;

  function escaparHtml(texto) {
    if (texto === null || texto === undefined) return '';
    var div = document.createElement('div');
    div.textContent = String(texto);
    return div.innerHTML;
  }

  function stripHtml(html) {
    var div = document.createElement('div');
    div.innerHTML = html || '';
    return (div.textContent || div.innerText || '').trim();
  }

  function truncar(texto, max) {
    var limpio = stripHtml(texto);
    if (limpio.length <= max) return limpio;
    return limpio.substring(0, max) + '…';
  }

  function fila(label, valor) {
    if (!valor && valor !== 0) return '';
    return '<div class="drawer-field"><span class="drawer-field-label">' + escaparHtml(label) +
      '</span><span class="drawer-field-value">' + escaparHtml(valor) + '</span></div>';
  }

  function renderCheck(label, activo) {
    return '<span class="drawer-check ' + (activo ? 'is-yes' : '') + '">' +
      (activo ? '✓' : '○') + ' ' + escaparHtml(label) + '</span>';
  }

  function renderTicketResumen(ticket, urls, permisos) {
    var html = '<section class="drawer-section"><h3 class="drawer-section-title">Información del ticket</h3><div class="drawer-section-body">';
    html += fila('Cliente', ticket.clienteNombre);
    html += fila('Tipo', ticket.tipo);
    html += fila('Asunto', ticket.asunto);
    html += fila('Fecha inicio', ticket.fechaCreacion);
    html += fila('Responsable', ticket.responsable);
    html += fila('Estado', ticket.estadoLabel);
    html += fila('Prioridad', ticket.prioridad);
    if (ticket.tipoId !== 3) {
      html += fila('Valor', ticket.valor || '—');
      html += fila('Etapa', ticket.etapa);
      html += fila('Tipo negocio', ticket.tipoNegocio);
      html += fila('Origen', ticket.origenNegocio);
    }
    if (ticket.cotizacionId && urls.cotizacion) {
      html += '<div class="drawer-field"><span class="drawer-field-label">Cotización</span>' +
        '<span class="drawer-field-value"><a href="' + escaparHtml(urls.cotizacion) + '" target="_blank" rel="noopener">#' +
        escaparHtml(ticket.cotizacionId) + '</a></span></div>';
    }
    if (permisos.puedeEditarTicket && urls.editarTicket) {
      html += '<p style="margin:0.65rem 0 0;"><a href="' + escaparHtml(urls.editarTicket) +
        '" class="drawer-link-btn drawer-link-btn-primary" target="_blank" rel="noopener">Editar ticket</a></p>';
    }
    html += '</div></section>';
    return html;
  }

  function renderStats(stats) {
    return '<div class="drawer-stats">' +
      '<div class="drawer-stat"><span class="drawer-stat-num">' + escaparHtml(stats.total) + '</span><span class="drawer-stat-label">Total</span></div>' +
      '<div class="drawer-stat"><span class="drawer-stat-num">' + escaparHtml(stats.completados) + '</span><span class="drawer-stat-label">Completados</span></div>' +
      '<div class="drawer-stat"><span class="drawer-stat-num">' + escaparHtml(stats.pendientes) + '</span><span class="drawer-stat-label">Pendientes</span></div>' +
      '</div>';
  }

  function renderSeguimientoItem(seg, index, permisos) {
    var cls = seg.estadoClase === 'completado' ? 'is-completado' : 'is-pendiente';
    var expanded = '';
    var preview = truncar(seg.observacion, 120);

    var html = '<article class="drawer-seg-item ' + cls + expanded + '" data-seg-id="' + escaparHtml(seg.id) + '">';
    html += '<div class="drawer-seg-header" role="button" tabindex="0" aria-expanded="false">';
    html += '<div class="drawer-seg-header-main">';
    html += '<p class="drawer-seg-title">Seguimiento #' + escaparHtml(seg.id) + '</p>';
    html += '<div class="drawer-seg-meta">' + escaparHtml(seg.fechaReporte) + ' · ' + escaparHtml(seg.responsable);
    if (seg.fechaContacto) html += '<br>Contacto: ' + escaparHtml(seg.fechaContacto);
    html += '</div></div>';
    html += '<span class="drawer-seg-badge ' + cls + '">' + escaparHtml(seg.estadoLabel) + '</span>';
    html += '</div>';

    if (preview) {
      html += '<div class="drawer-seg-preview">' + escaparHtml(preview) + '</div>';
    }

    html += '<div class="drawer-seg-body">';

    if (seg.contacto && (seg.contacto.nombre || seg.contacto.telefono || seg.contacto.email)) {
      var c = seg.contacto;
      var contactoTxt = c.nombre || '';
      if (c.telefono) contactoTxt += (contactoTxt ? ' · ' : '') + c.telefono;
      if (c.email) contactoTxt += (contactoTxt ? ' · ' : '') + c.email;
      html += fila('Contacto', contactoTxt);
    }

    html += fila('Forma', seg.formaContacto);
    html += fila('Canal', seg.canal);

    html += '<div class="drawer-seg-obs" data-obs-id="' + escaparHtml(seg.id) + '"></div>';

    if (seg.esComercial) {
      html += '<div class="drawer-checks">';
      html += renderCheck('Datos', seg.consiguioDatos);
      html += renderCheck('Cotizó', seg.cotizo);
      html += renderCheck('Vendió', seg.vendio);
      html += renderCheck('Demo', seg.demostracion);
      html += renderCheck('Visita', seg.visita);
      html += '</div>';
      if (seg.cotizacion) html += fila('Cotización', seg.cotizacion);
    }

    if (seg.archivo && seg.archivoUrl) {
      html += '<div class="drawer-field"><span class="drawer-field-label">Archivo</span>' +
        '<span class="drawer-field-value"><a href="' + escaparHtml(seg.archivoUrl) + '" target="_blank" rel="noopener">' +
        escaparHtml(seg.archivo) + '</a></span></div>';
    }

    if (seg.fechaProximo) {
      html += '<div class="drawer-seg-proximo"><p class="drawer-seg-proximo-title">Próximo contacto</p>';
      html += fila('Fecha', seg.fechaProximo);
      if (seg.horaProximo) html += fila('Hora', seg.horaProximo);
      html += fila('Encargado', seg.encargadoProximo);
      html += fila('Medio', seg.canalProximo);
      if (seg.asuntoProximo) html += fila('Asunto', seg.asuntoProximo);
      html += '</div>';
    }

    html += '<div class="drawer-seg-actions">';
    if (permisos.puedeVerDetalleSeguimiento && typeof window.abrirDrawerSeguimientoCliente === 'function') {
      html += '<button type="button" class="drawer-link-btn drawer-link-btn-primary js-ver-seg-drawer" data-seguimiento-id="' +
        escaparHtml(seg.id) + '"><i class="icon-eye-open"></i> Ver en panel</button>';
    }
    if (seg.urls && seg.urls.editarCompleta) {
      html += '<a href="' + escaparHtml(seg.urls.editarCompleta) +
        '" class="drawer-link-btn" target="_blank" rel="noopener"><i class="icon-external-link"></i> Página completa</a>';
    }
    html += '</div></div></article>';

    return html;
  }

  function bindSeguimientoItems(seguimientos) {
    body.querySelectorAll('.drawer-seg-item').forEach(function (item) {
      var headerEl = item.querySelector('.drawer-seg-header');
      if (!headerEl) return;

      function toggle() {
        var expanded = item.classList.toggle('is-expanded');
        headerEl.setAttribute('aria-expanded', expanded ? 'true' : 'false');
      }

      headerEl.addEventListener('click', toggle);
      headerEl.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          toggle();
        }
      });
    });

    seguimientos.forEach(function (seg) {
      var obsEl = body.querySelector('[data-obs-id="' + seg.id + '"]');
      if (obsEl) obsEl.innerHTML = seg.observacion || '—';
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
    [ticket.tipo, ticket.estadoLabel, ticket.prioridad].forEach(function (texto) {
      if (!texto) return;
      var badge = document.createElement('span');
      badge.className = 'drawer-badge';
      badge.textContent = texto;
      badges.appendChild(badge);
    });

    var html = renderTicketResumen(ticket, urls, permisos);
    html += renderStats(stats);
    html += '<h3 class="drawer-timeline-title">Seguimientos <small style="font-weight:500;color:#64748b;">(más recientes primero)</small></h3>';

    if (seguimientos.length === 0) {
      html += '<div class="drawer-vacio">Este ticket aún no tiene seguimientos registrados.</div>';
    } else {
      seguimientos.forEach(function (seg, index) {
        html += renderSeguimientoItem(seg, index, permisos);
      });
    }

    body.innerHTML = html;
    bindSeguimientoItems(seguimientos);

    if (urls.paginaCompleta) {
      btnCompleto.href = urls.paginaCompleta;
      btnCompleto.style.display = '';
    } else {
      btnCompleto.style.display = 'none';
    }
  }

  function mostrarError(mensaje) {
    body.innerHTML = '<div class="drawer-error">' + escaparHtml(mensaje) + '</div>';
    btnCompleto.style.display = 'none';
  }

  function mostrarCargando() {
    body.innerHTML = '<div class="drawer-cargando"><div class="drawer-spinner"></div><div>Cargando seguimientos...</div></div>';
    subtitulo.textContent = '';
    badges.innerHTML = '';
    btnCompleto.style.display = 'none';
  }

  function cerrarDrawer() {
    if (cargandoFetch) return;
    overlay.classList.remove('is-open');
    drawer.classList.remove('is-open');
    document.body.classList.remove('drawer-ticket-seg-open');
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
    document.body.classList.add('drawer-ticket-seg-open');
    overlay.setAttribute('aria-hidden', 'false');

    cargandoFetch = true;

    fetch('ajax/ajax-clientes-ticket-seguimientos.php?ticket=' + encodeURIComponent(ticketId) + '&cliente=' + encodeURIComponent(clienteId))
      .then(function (res) { return res.json().then(function (data) { return { data: data }; }); })
      .then(function (result) {
        if (!result.data.success) {
          throw new Error(result.data.message || 'No se pudo cargar el ticket.');
        }
        renderDetalle(result.data.data);
      })
      .catch(function (err) {
        mostrarError(err.message || 'Error al cargar los seguimientos.');
      })
      .finally(function () {
        cargandoFetch = false;
      });
  }

  btnCerrar.addEventListener('click', cerrarDrawer);
  btnCancel.addEventListener('click', cerrarDrawer);
  overlay.addEventListener('click', cerrarDrawer);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
      cerrarDrawer();
    }
  });

  document.addEventListener('click', function (e) {
    var trigger = e.target.closest('.js-abrir-ticket-seguimientos-drawer');
    if (!trigger) return;
    e.preventDefault();
    abrirDrawer(trigger.getAttribute('data-ticket-id'), trigger.getAttribute('data-cliente-id'));
  });

  window.abrirDrawerTicketSeguimientos = abrirDrawer;
})();
</script>
