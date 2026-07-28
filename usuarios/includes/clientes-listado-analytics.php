<?php
/**
 * Bloque de gráficos anuales del listado de clientes.
 * Requiere: $clientesAnalytics
 */
$resumenAnual = $clientesAnalytics['resumen'] ?? [];
$cartera      = $clientesAnalytics['cartera'] ?? [];
$anioAnalytics = intval($clientesAnalytics['anio'] ?? date('Y'));
$analyticsTotal = intval($cartera['total'] ?? 0);
$analyticsRegistrados = intval($resumenAnual['registrados_anio'] ?? 0);
$analyticsNuevos = intval($resumenAnual['nuevos_clientes'] ?? 0);
?>
<section class="clientes-analytics clientes-collapsible is-collapsed" id="clientesAnalytics" data-collapsible="analytics">
  <div class="clientes-panel">
    <button type="button" class="clientes-collapsible-toggle clientes-panel-header is-neutral" id="clientes-analytics-toggle" aria-expanded="false" aria-controls="clientes-analytics-body">
      <span class="clientes-collapsible-heading">
        <span class="clientes-collapsible-title">Indicadores de cartera <?= $anioAnalytics; ?></span>
        <span class="clientes-collapsible-summary">
          <?= $analyticsTotal; ?> activos · <?= $analyticsRegistrados; ?> registrados · <?= $analyticsNuevos; ?> nuevos clientes
        </span>
      </span>
      <i class="icon-chevron-down clientes-collapsible-icon" aria-hidden="true"></i>
    </button>
    <div class="clientes-collapsible-body clientes-panel-body" id="clientes-analytics-body" hidden>
      <div class="clientes-analytics-kpis">
        <div class="clientes-analytics-kpi">
          <strong><?= $analyticsTotal; ?></strong>
          <span>Clientes activos en cartera</span>
        </div>
        <div class="clientes-analytics-kpi">
          <strong><?= $analyticsRegistrados; ?></strong>
          <span>Registrados <?= $anioAnalytics; ?></span>
        </div>
        <div class="clientes-analytics-kpi">
          <strong><?= $analyticsNuevos; ?></strong>
          <span>Nuevos clientes (ingreso)</span>
        </div>
        <div class="clientes-analytics-kpi">
          <strong><?= intval($resumenAnual['prospectos_anio'] ?? 0); ?></strong>
          <span>Prospectos registrados</span>
        </div>
        <div class="clientes-analytics-kpi">
          <strong><?= intval($resumenAnual['clientes_anio'] ?? 0); ?></strong>
          <span>Categoría cliente</span>
        </div>
        <div class="clientes-analytics-kpi">
          <strong><?= intval($resumenAnual['dealers_anio'] ?? 0); ?></strong>
          <span>Dealers registrados</span>
        </div>
      </div>

      <div class="clientes-charts-grid">
        <div class="clientes-chart-card is-wide">
          <h4>Registros por mes</h4>
          <div class="clientes-chart-canvas-wrap">
            <canvas id="chartClientesRegistroMes"></canvas>
          </div>
        </div>

        <div class="clientes-chart-card is-wide">
          <h4>Nuevos clientes por mes (fecha ingreso)</h4>
          <div class="clientes-chart-canvas-wrap">
            <canvas id="chartClientesIngresoMes"></canvas>
          </div>
        </div>

        <div class="clientes-chart-card">
          <h4>Distribución por categoría</h4>
          <div class="clientes-chart-canvas-wrap">
            <canvas id="chartClientesCategoria"></canvas>
          </div>
        </div>

        <div class="clientes-chart-card">
          <h4>Tipo de documento</h4>
          <div class="clientes-chart-canvas-wrap">
            <canvas id="chartClientesTipoDoc"></canvas>
          </div>
        </div>

        <div class="clientes-chart-card">
          <h4>Estado de mercadeo</h4>
          <div class="clientes-chart-canvas-wrap">
            <canvas id="chartClientesEstadoMercadeo"></canvas>
          </div>
        </div>

        <div class="clientes-chart-card">
          <h4>Top departamentos (registros <?= $anioAnalytics; ?>)</h4>
          <div class="clientes-chart-canvas-wrap">
            <canvas id="chartClientesDepartamento"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="application/json" id="clientesAnalyticsData"><?=
  json_encode($clientesAnalytics, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?></script>
