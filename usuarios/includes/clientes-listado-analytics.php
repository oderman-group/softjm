<?php
/**
 * Bloque de gráficos anuales del listado de clientes.
 * Requiere: $clientesAnalytics
 */
$resumenAnual = $clientesAnalytics['resumen'] ?? [];
$cartera      = $clientesAnalytics['cartera'] ?? [];
$anioAnalytics = intval($clientesAnalytics['anio'] ?? date('Y'));
?>
<section class="clientes-analytics" id="clientesAnalytics">
  <div class="clientes-panel">
    <div class="clientes-panel-header is-neutral">
      <div>
        <h3>Indicadores de cartera <?= $anioAnalytics; ?></h3>
        <p>Resumen del año en curso para gestión comercial y mercadeo</p>
      </div>
    </div>
    <div class="clientes-panel-body">
      <div class="clientes-analytics-kpis">
        <div class="clientes-analytics-kpi">
          <strong><?= intval($cartera['total'] ?? 0); ?></strong>
          <span>Clientes activos en cartera</span>
        </div>
        <div class="clientes-analytics-kpi">
          <strong><?= intval($resumenAnual['registrados_anio'] ?? 0); ?></strong>
          <span>Registrados <?= $anioAnalytics; ?></span>
        </div>
        <div class="clientes-analytics-kpi">
          <strong><?= intval($resumenAnual['nuevos_clientes'] ?? 0); ?></strong>
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
