<?php
/**
 * Bloque de gráficos anuales de tickets.
 * Requiere: $ticketsAnalytics
 */
$resumenAnual = $ticketsAnalytics['resumen'] ?? [];
$anioAnalytics = intval($ticketsAnalytics['anio'] ?? date('Y'));
?>
<section class="tickets-analytics" id="ticketsAnalytics">
  <div class="tickets-panel">
    <div class="tickets-panel-header is-neutral">
      <div>
        <h3>Indicadores comerciales <?= $anioAnalytics; ?></h3>
        <p>Resumen del año en curso para seguimiento gerencial y comercial</p>
      </div>
    </div>
    <div class="tickets-panel-body">
      <div class="tickets-analytics-kpis">
        <div class="tickets-analytics-kpi">
          <strong><?= intval($resumenAnual['total'] ?? 0); ?></strong>
          <span>Tickets creados</span>
        </div>
        <div class="tickets-analytics-kpi">
          <strong><?= intval($resumenAnual['abiertos'] ?? 0); ?></strong>
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
          <strong><?= floatval($resumenAnual['tasa_ganados'] ?? 0); ?>%</strong>
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
