<?php
/**
 * Bloque de gráficos anuales de tickets.
 * Requiere: $ticketsAnalytics
 */
$resumenAnual = $ticketsAnalytics['resumen'] ?? [];
$anioAnalytics = intval($ticketsAnalytics['anio'] ?? date('Y'));
$analyticsTotal = intval($resumenAnual['total'] ?? 0);
$analyticsAbiertos = intval($resumenAnual['abiertos'] ?? 0);
$analyticsTasa = floatval($resumenAnual['tasa_ganados'] ?? 0);
?>
<section class="tickets-analytics tickets-collapsible is-collapsed" id="ticketsAnalytics" data-collapsible="analytics">
  <div class="tickets-panel">
    <button type="button" class="tickets-collapsible-toggle tickets-panel-header is-neutral" id="tickets-analytics-toggle" aria-expanded="false" aria-controls="tickets-analytics-body">
      <span class="tickets-collapsible-heading">
        <span class="tickets-collapsible-title">Indicadores comerciales <?= $anioAnalytics; ?></span>
        <span class="tickets-collapsible-summary">
          <?= $analyticsTotal; ?> tickets · <?= $analyticsAbiertos; ?> abiertos · <?= $analyticsTasa; ?>% tasa ganados
        </span>
      </span>
      <i class="icon-chevron-down tickets-collapsible-icon" aria-hidden="true"></i>
    </button>
    <div class="tickets-collapsible-body tickets-panel-body" id="tickets-analytics-body" hidden>
      <div class="tickets-analytics-kpis">
        <div class="tickets-analytics-kpi">
          <strong><?= $analyticsTotal; ?></strong>
          <span>Tickets creados</span>
        </div>
        <div class="tickets-analytics-kpi">
          <strong><?= $analyticsAbiertos; ?></strong>
          <span>Abiertos</span>
        </div>
        <div class="tickets-analytics-kpi">
          <strong><?= intval($resumenAnual['cerrados'] ?? 0); ?></strong>
          <span>Cerrados</span>
        </div>
        <div class="tickets-analytics-kpi">
          <strong><?= intval($resumenAnual['con_cotizacion'] ?? 0); ?></strong>
          <span>Con cotización</span>
        </div>
        <div class="tickets-analytics-kpi">
          <strong><?= intval($resumenAnual['ganados'] ?? 0); ?></strong>
          <span>Cerrados ganados</span>
        </div>
        <div class="tickets-analytics-kpi">
          <strong><?= $analyticsTasa; ?>%</strong>
          <span>Tasa ganados / cerrados</span>
        </div>
      </div>

      <div class="tickets-charts-grid">
        <div class="tickets-chart-card is-wide">
          <h4>Tickets creados por mes</h4>
          <div class="tickets-chart-canvas-wrap">
            <canvas id="chartTicketsMes"></canvas>
          </div>
        </div>

        <div class="tickets-chart-card">
          <h4>Distribución por etapa</h4>
          <div class="tickets-chart-canvas-wrap">
            <canvas id="chartTicketsEtapa"></canvas>
          </div>
        </div>

        <div class="tickets-chart-card">
          <h4>Estado actual</h4>
          <div class="tickets-chart-canvas-wrap">
            <canvas id="chartTicketsEstado"></canvas>
          </div>
        </div>

        <div class="tickets-chart-card">
          <h4>Tipo de ticket</h4>
          <div class="tickets-chart-canvas-wrap">
            <canvas id="chartTicketsTipo"></canvas>
          </div>
        </div>

        <div class="tickets-chart-card">
          <h4>Prioridad</h4>
          <div class="tickets-chart-canvas-wrap">
            <canvas id="chartTicketsPrioridad"></canvas>
          </div>
        </div>

        <div class="tickets-chart-card">
          <h4>Tickets por responsable</h4>
          <div class="tickets-chart-canvas-wrap">
            <canvas id="chartTicketsResponsable"></canvas>
          </div>
        </div>

        <?php if (empty($ticketsAnalytics['es_vista_cliente'])) { ?>
        <div class="tickets-chart-card">
          <h4>Top clientes con más tickets</h4>
          <div class="tickets-chart-canvas-wrap">
            <canvas id="chartTicketsClientes"></canvas>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</section>

<script type="application/json" id="ticketsAnalyticsData"><?=
  json_encode($ticketsAnalytics, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?></script>
