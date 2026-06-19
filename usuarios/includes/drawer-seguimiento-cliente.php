<style>
#drawerSeguimientoOverlay {
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

#drawerSeguimientoOverlay.is-open {
  opacity: 1;
  visibility: visible;
  pointer-events: auto;
}

#drawerSeguimientoOverlay.is-stacked.is-open {
  width: calc(100% - 70vw);
  right: auto;
}

#drawerSeguimiento {
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
  background: #f8fafc;
  z-index: 99999;
  display: flex;
  flex-direction: column;
  box-sizing: border-box;
  box-shadow: -8px 0 32px rgba(15, 23, 42, 0.15);
  transform: translateX(100%);
  transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}

#drawerSeguimiento.is-open {
  transform: translateX(0);
}

#drawerSeguimiento.is-stacked.is-open {
  box-shadow: -12px 0 40px rgba(15, 23, 42, 0.28);
}

body.drawer-seguimiento-open {
  overflow: hidden !important;
}

#drawerSeguimiento .drawer-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.15);
  color: #fff;
  flex-shrink: 0;
  transition: background 0.25s ease;
}

#drawerSeguimiento .drawer-header.is-pendiente {
  background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
}

#drawerSeguimiento .drawer-header.is-completado {
  background: linear-gradient(135deg, #6495ed 0%, #4169e1 100%);
}

#drawerSeguimiento .drawer-header-title {
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 1.125rem;
  font-weight: 700;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

#drawerSeguimiento .drawer-header-subtitle {
  font-size: 0.8125rem;
  font-weight: 500;
  opacity: 0.92;
}

#drawerSeguimiento .drawer-header-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 0.35rem;
  margin-top: 0.35rem;
}

#drawerSeguimiento .drawer-badge {
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

#drawerSeguimiento .drawer-close {
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

#drawerSeguimiento .drawer-body {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 1.25rem 1.5rem 1.5rem;
}

#drawerSeguimiento .drawer-cargando,
#drawerSeguimiento .drawer-error {
  text-align: center;
  padding: 2.5rem 1rem;
  color: #64748b;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.9375rem;
}

#drawerSeguimiento .drawer-error {
  color: #b91c1c;
}

#drawerSeguimiento .drawer-spinner {
  display: inline-block;
  width: 28px;
  height: 28px;
  border: 3px solid #e2e8f0;
  border-top-color: #dc2626;
  border-radius: 50%;
  animation: drawer-seg-spin 0.6s linear infinite;
  margin-bottom: 0.75rem;
}

@keyframes drawer-seg-spin {
  to { transform: rotate(360deg); }
}

#drawerSeguimiento .drawer-section {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  margin-bottom: 1rem;
  overflow: hidden;
}

#drawerSeguimiento .drawer-section-title {
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

#drawerSeguimiento .drawer-section-body {
  padding: 0.85rem 1rem 1rem;
}

#drawerSeguimiento .drawer-field {
  display: grid;
  grid-template-columns: 130px 1fr;
  gap: 0.35rem 0.75rem;
  padding: 0.45rem 0;
  border-bottom: 1px solid #f1f5f9;
  font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
  font-size: 0.8125rem;
  line-height: 1.45;
}

#drawerSeguimiento .drawer-field:last-child {
  border-bottom: none;
}

#drawerSeguimiento .drawer-field-label {
  font-weight: 600;
  color: #64748b;
}

#drawerSeguimiento .drawer-field-value {
  color: #0f172a;
  word-break: break-word;
}

#drawerSeguimiento .drawer-field-value a {
  color: #2563eb;
  text-decoration: none;
}

#drawerSeguimiento .drawer-field-value a:hover {
  text-decoration: underline;
}

#drawerSeguimiento .drawer-observacion {
  margin-top: 0.5rem;
  padding: 0.75rem;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: 0.875rem;
  line-height: 1.55;
  color: #1e293b;
}

#drawerSeguimiento .drawer-checks {
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
}

#drawerSeguimiento .drawer-check {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.2rem 0.55rem;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 600;
  background: #f1f5f9;
  color: #64748b;
}

#drawerSeguimiento .drawer-check.is-yes {
  background: #dcfce7;
  color: #166534;
}

#drawerSeguimiento .drawer-alert-info {
  margin-bottom: 1rem;
  padding: 0.75rem 0.9rem;
  border-radius: 8px;
  background: #eff6ff;
  border: 1px solid #bfdbfe;
  color: #1e40af;
  font-size: 0.8125rem;
  line-height: 1.45;
}

#drawerSeguimiento .drawer-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
  padding: 1rem 1.5rem;
  border-top: 1px solid #e2e8f0;
  background: #fff;
  flex-shrink: 0;
}

#drawerSeguimiento .drawer-btn {
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

#drawerSeguimiento .drawer-btn-secondary {
  background: #f1f5f9;
  color: #334155;
}

#drawerSeguimiento .drawer-btn-primary {
  background: #2563eb;
  color: #fff;
}

#drawerSeguimiento .drawer-btn-primary:hover {
  background: #1d4ed8;
  color: #fff;
}

@media (max-width: 768px) {
  #drawerSeguimiento {
    width: 100vw;
    max-width: 100vw;
  }

  #drawerSeguimientoOverlay.is-stacked.is-open {
    width: 100%;
  }

  #drawerSeguimiento .drawer-field {
    grid-template-columns: 1fr;
    gap: 0.15rem;
  }
}
</style>

<div id="drawerSeguimientoOverlay" aria-hidden="true"></div>
<aside id="drawerSeguimiento" role="dialog" aria-labelledby="drawerSeguimientoTitulo" aria-modal="true">
  <div class="drawer-header is-pendiente" id="drawerSeguimientoHeader">
    <div class="drawer-header-title">
      <span id="drawerSeguimientoTitulo"><i class="icon-list-ol" aria-hidden="true"></i> Seguimiento</span>
      <span class="drawer-header-subtitle" id="drawerSeguimientoSubtitulo"></span>
      <div class="drawer-header-badges" id="drawerSeguimientoBadges"></div>
    </div>
    <button type="button" class="drawer-close" id="btnCerrarDrawerSeguimiento" aria-label="Cerrar">
      <i class="icon-remove" aria-hidden="true"></i>
    </button>
  </div>

  <div class="drawer-body" id="drawerSeguimientoBody">
    <div class="drawer-cargando" id="drawerSeguimientoCargando">
      <div class="drawer-spinner"></div>
      <div>Cargando seguimiento...</div>
    </div>
  </div>

  <div class="drawer-footer">
    <button type="button" class="drawer-btn drawer-btn-secondary" id="btnCancelarDrawerSeguimiento">Cerrar</button>
    <a href="#" class="drawer-btn drawer-btn-primary" id="btnAbrirSeguimientoCompleto" target="_blank" rel="noopener">
      <i class="icon-external-link" aria-hidden="true"></i>
      Abrir página completa
    </a>
  </div>
</aside>

<script>
(function () {
  var overlay    = document.getElementById('drawerSeguimientoOverlay');
  var drawer     = document.getElementById('drawerSeguimiento');
  var header     = document.getElementById('drawerSeguimientoHeader');
  var body       = document.getElementById('drawerSeguimientoBody');
  var cargando   = document.getElementById('drawerSeguimientoCargando');
  var subtitulo  = document.getElementById('drawerSeguimientoSubtitulo');
  var badges     = document.getElementById('drawerSeguimientoBadges');
  var btnCerrar  = document.getElementById('btnCerrarDrawerSeguimiento');
  var btnCancel  = document.getElementById('btnCancelarDrawerSeguimiento');
  var btnCompleto = document.getElementById('btnAbrirSeguimientoCompleto');

  if (!overlay || !drawer || !body) return;

  document.body.appendChild(overlay);
  document.body.appendChild(drawer);

  var cargandoFetch = false;

  function escaparHtml(texto) {
    if (texto === null || texto === undefined) return '';
    var div = document.createElement('div');
    div.textContent = String(texto);
    return div.innerHTML;
  }

  function valorCampo(valor, enlace) {
    if (!valor && valor !== 0) return '<span class="drawer-field-value">—</span>';
    if (enlace) {
      return '<span class="drawer-field-value"><a href="' + escaparHtml(enlace) + '" target="_blank" rel="noopener">' + escaparHtml(valor) + '</a></span>';
    }
    return '<span class="drawer-field-value">' + escaparHtml(valor) + '</span>';
  }

  function fila(label, valor, enlace) {
    return '<div class="drawer-field"><span class="drawer-field-label">' + escaparHtml(label) + '</span>' + valorCampo(valor, enlace) + '</div>';
  }

  function renderCheck(label, activo) {
    return '<span class="drawer-check ' + (activo ? 'is-yes' : '') + '">' +
      (activo ? '✓' : '○') + ' ' + escaparHtml(label) + '</span>';
  }

  function renderTicket(ticket, urls, permisos) {
    if (!ticket) {
      return '<section class="drawer-section"><h3 class="drawer-section-title">Ticket asociado</h3><div class="drawer-section-body"><p style="margin:0;color:#64748b;font-size:0.8125rem;">Este seguimiento no tiene ticket asociado.</p></div></section>';
    }

    var html = '<section class="drawer-section"><h3 class="drawer-section-title">Ticket #' + escaparHtml(ticket.id) + '</h3><div class="drawer-section-body">';
    html += fila('Cliente', ticket.clienteNombre, urls.cliente);
    html += fila('Tipo', ticket.tipo);
    html += fila('Asunto', ticket.asunto);
    html += fila('Fecha inicio', ticket.fechaCreacion);
    if (ticket.tipoId !== 3) {
      html += fila('Valor', ticket.valor || '—');
      html += fila('Etapa', ticket.etapa);
      html += fila('Tipo negocio', ticket.tipoNegocio);
      html += fila('Origen', ticket.origenNegocio);
    }
    html += fila('Responsable', ticket.responsable);
    html += fila('Estado ticket', ticket.estadoLabel);
    if (ticket.cotizacionId) {
      html += fila('Cotización', '#' + ticket.cotizacionId, urls.cotizacion);
    }
    if (permisos.puedeEditarTicket && urls.editarTicket) {
      html += '<p style="margin:0.75rem 0 0;"><a href="' + escaparHtml(urls.editarTicket) + '" class="drawer-btn drawer-btn-primary" target="_blank" rel="noopener" style="display:inline-flex;">Editar ticket</a></p>';
    }
    html += '</div></section>';
    return html;
  }

  function renderSeguimiento(seg, ticket, urls, permisos) {
    var html = '<section class="drawer-section"><h3 class="drawer-section-title">Detalle del seguimiento</h3><div class="drawer-section-body">';
    html += fila('Fecha reporte', seg.fechaReporte);
    html += fila('Fecha contacto', seg.fechaContacto);
    html += fila('Responsable', seg.responsable);
    html += fila('Tipo', seg.tipoSeguimiento);

    if (seg.contacto && (seg.contacto.nombre || seg.contacto.telefono || seg.contacto.email)) {
      var contactoTxt = seg.contacto.nombre || '';
      if (seg.contacto.telefono) contactoTxt += (contactoTxt ? ' · ' : '') + seg.contacto.telefono;
      if (seg.contacto.email) contactoTxt += (contactoTxt ? ' · ' : '') + seg.contacto.email;
      html += fila('Contacto', contactoTxt);
    }

    html += fila('Forma de contacto', seg.formaContacto);
    html += fila('Canal', seg.canal);

    html += '<div class="drawer-field" style="display:block;border-bottom:none;padding-bottom:0;">';
    html += '<span class="drawer-field-label" style="display:block;margin-bottom:0.35rem;">Observaciones</span>';
    html += '<div class="drawer-observacion" id="drawerSegObservacion"></div></div>';

    var esSoporteOperativo = ticket && ticket.tipoId === 3;
    if (!esSoporteOperativo) {
      html += '<div class="drawer-field" style="display:block;border-bottom:none;"><span class="drawer-field-label" style="display:block;margin-bottom:0.35rem;">Indicadores</span><div class="drawer-checks">';
      html += renderCheck('Consiguió datos', seg.consiguioDatos);
      html += renderCheck('Hubo cotización', seg.cotizo);
      html += renderCheck('Hubo venta', seg.vendio);
      html += renderCheck('Demostración', seg.demostracion);
      html += renderCheck('Visita', seg.visita);
      html += '</div></div>';
      if (seg.cotizacion) html += fila('# Cotización', seg.cotizacion);
    }

    if (seg.archivo && seg.archivoUrl) {
      html += fila('Archivo', seg.archivo, seg.archivoUrl);
    }

    html += '<div style="margin:0.75rem 0 0.35rem;font-weight:700;font-size:0.8125rem;color:#334155;">Próximo contacto</div>';
    html += fila('Fecha', seg.fechaProximo || '—');
    html += fila('Hora', seg.horaProximo || '—');
    if (seg.minutosRecordar) html += fila('Recordar (min)', seg.minutosRecordar);
    html += fila('Asunto', seg.asuntoProximo || '—');
    html += fila('Encargado', seg.encargadoProximo);
    html += fila('Medio', seg.canalProximo);

    if (seg.bloqueadoVarios) {
      html += '<div class="drawer-alert-info">Los encargados aún no han revisado este pendiente. Por ahora no es posible hacer cambios.</div>';
    } else if (!permisos.puedeEditarSeguimiento) {
      html += '<div class="drawer-alert-info"><strong>Ticket cerrado o seguimiento completado.</strong> No es posible editar este seguimiento.</div>';
    }

    html += '</div></section>';
    return html;
  }

  function renderDetalle(data) {
    var seg = data.seguimiento;
    var ticket = data.ticket;
    var urls = data.urls || {};
    var permisos = data.permisos || {};

    header.classList.remove('is-pendiente', 'is-completado');
    header.classList.add(seg.estadoClase === 'completado' ? 'is-completado' : 'is-pendiente');

    subtitulo.textContent = (seg.fechaReporte || '') + ' · ' + (seg.responsable || '');

    badges.innerHTML = '';
    var badgeSeg = document.createElement('span');
    badgeSeg.className = 'drawer-badge';
    badgeSeg.textContent = seg.estadoLabel || 'Seguimiento';
    badges.appendChild(badgeSeg);

    if (ticket) {
      var badgeTik = document.createElement('span');
      badgeTik.className = 'drawer-badge';
      badgeTik.textContent = 'Ticket ' + (ticket.estadoLabel || '');
      badges.appendChild(badgeTik);
    }

    body.innerHTML = renderTicket(ticket, urls, permisos) + renderSeguimiento(seg, ticket, urls, permisos);

    var obsEl = document.getElementById('drawerSegObservacion');
    if (obsEl) obsEl.innerHTML = seg.observacion || '—';

    if (urls.editarCompleta) {
      btnCompleto.href = urls.editarCompleta;
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
    body.innerHTML = '<div class="drawer-cargando"><div class="drawer-spinner"></div><div>Cargando seguimiento...</div></div>';
    subtitulo.textContent = '';
    badges.innerHTML = '';
    btnCompleto.style.display = 'none';
  }

  function cerrarDrawer() {
    if (cargandoFetch) return;
    overlay.classList.remove('is-open', 'is-stacked');
    drawer.classList.remove('is-open', 'is-stacked');
    document.body.classList.remove('drawer-seguimiento-open');
    overlay.setAttribute('aria-hidden', 'true');
    mostrarCargando();
  }

  function abrirDrawer(seguimientoId, clienteId) {
    seguimientoId = parseInt(seguimientoId, 10);
    clienteId = parseInt(clienteId, 10);
    if (!seguimientoId || !clienteId) return;

    var hayTicketAbierto = document.body.classList.contains('drawer-ticket-open')
      || document.body.classList.contains('drawer-ticket-seg-open');

    mostrarCargando();
    overlay.classList.toggle('is-stacked', hayTicketAbierto);
    drawer.classList.toggle('is-stacked', hayTicketAbierto);
    overlay.classList.add('is-open');
    drawer.classList.add('is-open');
    document.body.classList.add('drawer-seguimiento-open');
    overlay.setAttribute('aria-hidden', 'false');

    cargandoFetch = true;

    fetch('ajax/ajax-clientes-seguimiento-detalle.php?seguimiento=' + encodeURIComponent(seguimientoId) + '&cliente=' + encodeURIComponent(clienteId))
      .then(function (res) { return res.json().then(function (data) { return { ok: res.ok, data: data }; }); })
      .then(function (result) {
        if (!result.data.success) {
          throw new Error(result.data.message || 'No se pudo cargar el seguimiento.');
        }
        renderDetalle(result.data.data);
      })
      .catch(function (err) {
        mostrarError(err.message || 'Error al cargar el seguimiento.');
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
    var trigger = e.target.closest('.js-abrir-seguimiento-drawer');
    if (!trigger) return;
    e.preventDefault();
    abrirDrawer(trigger.getAttribute('data-seguimiento-id'), trigger.getAttribute('data-cliente-id'));
  });

  window.abrirDrawerSeguimientoCliente = abrirDrawer;
})();
</script>
