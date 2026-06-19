<div class="cliente-resumen">

  <div class="cliente-resumen-header">

    <h1 class="cliente-resumen-title"><?= htmlspecialchars(strtoupper($resultadoD['cli_nombre'])) ?></h1>

    <p class="cliente-resumen-subtitle">

      Cliente #<?= $clienteId ?> · Registrado <?= htmlspecialchars($resultadoD['cli_fecha_registro']) ?>

    </p>

  </div>



  <div class="cliente-resumen-meta">

    <div class="cliente-resumen-meta-block cliente-resumen-meta-etiquetas">

      <div class="cliente-resumen-meta-head">

        <span class="cliente-resumen-meta-label">Etiquetas comerciales</span>

        <span class="cliente-resumen-meta-hint">Asignadas manualmente</span>

      </div>

      <div class="cliente-resumen-meta-content">

        <?php if (!empty($etiquetasCliente)) { ?>

          <?= Etiqueta::renderBadges($etiquetasCliente, 'crm-etiquetas--resumen') ?>

        <?php } else { ?>

          <span class="cliente-resumen-sin-etiquetas">Sin etiquetas asignadas</span>

        <?php } ?>

        <a href="#cliente-etiquetas-section" class="cliente-resumen-meta-link" onclick="var tab = document.querySelector('.cliente-editar-tabs [href=\'#user\']'); if (tab) { tab.click(); } setTimeout(function(){ var el = document.getElementById('cliente-etiquetas-section'); if (el) { el.scrollIntoView({behavior:'smooth', block:'start'}); } }, 120); return false;">Gestionar</a>

      </div>

    </div>



    <div class="cliente-resumen-meta-block cliente-resumen-meta-estado">

      <div class="cliente-resumen-meta-head">

        <span class="cliente-resumen-meta-label">Estado CRM</span>

        <span class="cliente-resumen-meta-hint">Calculado automáticamente</span>

      </div>

      <div class="cliente-resumen-meta-content">

        <div class="cliente-resumen-badges cliente-resumen-badges-sistema">

          <span class="cliente-badge cliente-badge-sistema cliente-badge-categoria"><?= htmlspecialchars($categoriaActualLabel) ?></span>

          <span class="cliente-badge cliente-badge-sistema cliente-badge-nivel"><?= htmlspecialchars($nivelActualLabel) ?></span>

          <?php if ($resultadoD['cli_credito'] == 1) { ?>

            <span class="cliente-badge cliente-badge-sistema cliente-badge-credito">Con crédito</span>

          <?php } else { ?>

            <span class="cliente-badge cliente-badge-sistema cliente-badge-sincredito">Sin crédito</span>

          <?php } ?>

        </div>

      </div>

    </div>

  </div>



  <div class="cliente-kpi-grid">

    <div class="cliente-kpi-card">

      <div class="cliente-kpi-label">Teléfono / Celular</div>

      <div class="cliente-kpi-value">

        <?= htmlspecialchars(trim(($resultadoD['cli_telefono'] ?: '-') . ' / ' . ($resultadoD['cli_celular'] ?: '-'))) ?>

      </div>

    </div>

    <div class="cliente-kpi-card">

      <div class="cliente-kpi-label">Email</div>

      <div class="cliente-kpi-value"><?= htmlspecialchars($resultadoD['cli_email'] ?: '—') ?></div>

    </div>

    <div class="cliente-kpi-card">

      <div class="cliente-kpi-label">Asesor</div>

      <div class="cliente-kpi-value"><?= htmlspecialchars($nombreAsesorActual) ?></div>

    </div>

    <div class="cliente-kpi-card">

      <div class="cliente-kpi-label">Referencia</div>

      <div class="cliente-kpi-value"><?= htmlspecialchars($referenciaActualLabel) ?></div>

    </div>

    <div class="cliente-kpi-card">

      <div class="cliente-kpi-label">Saldo</div>

      <div class="cliente-kpi-value">$<?= htmlspecialchars($resultadoD['cli_saldo'] ?: '0') ?></div>

    </div>

    <div class="cliente-kpi-card">

      <div class="cliente-kpi-label">Tickets abiertos</div>

      <div class="cliente-kpi-value">

        <a href="#tickets" onclick="document.querySelector('[href=\'#tickets\']').click(); return false;">

          <?= intval($contadoresCliente['tickets_abiertos'] ?? 0) ?> / <?= intval($contadoresCliente['tickets'] ?? 0) ?>

        </a>

      </div>

    </div>

  </div>



  <h4 class="cliente-resumen-timeline-title">Evolución comercial</h4>

  <div class="cliente-timeline">

    <div class="cliente-timeline-step registro">

      <div class="circle">1</div>

      <div class="title">Registro</div>

      <div class="date"><?= htmlspecialchars($resultadoD['cli_fecha_registro']) ?></div>

    </div>

    <div class="cliente-timeline-step">

      <div class="circle">2</div>

      <div class="title">Primera cotización</div>

      <div class="date"><?= htmlspecialchars($fechaPrimeraCotizacion ?: '—') ?></div>

    </div>

    <div class="cliente-timeline-step compra">

      <div class="circle">3</div>

      <div class="title">Primera compra</div>

      <div class="date"><?= htmlspecialchars($fechaPrimeraCompra ?: '—') ?></div>

    </div>

    <div class="cliente-timeline-step ultima">

      <div class="circle">4</div>

      <div class="title">Última compra</div>

      <div class="date"><?= htmlspecialchars($fechaUltimaCompra ?: '—') ?></div>

    </div>

  </div>

</div>


