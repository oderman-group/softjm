(function () {
  var dataEl = document.getElementById('ticketsAnalyticsData');
  if (!dataEl || typeof Chart === 'undefined') return;

  var data;
  try {
    data = JSON.parse(dataEl.textContent || '{}');
  } catch (e) {
    return;
  }

  var chartsReady = false;
  var loadingAnalytics = false;
  var meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
  var palette = ['#2563eb', '#059669', '#d97706', '#7c3aed', '#dc2626', '#0891b2', '#db2777', '#65a30d'];
  var chartDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        labels: {
          font: { family: 'Plus Jakarta Sans', size: 11 },
          boxWidth: 12
        }
      }
    }
  };

  function mapChartData(items) {
    return {
      labels: (items || []).map(function (i) { return i.label; }),
      values: (items || []).map(function (i) { return i.total; })
    };
  }

  function createBarChart(canvasId, labels, values, horizontal) {
    var canvas = document.getElementById(canvasId);
    if (!canvas) return;

    new Chart(canvas, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          data: values,
          backgroundColor: palette.slice(0, labels.length),
          borderRadius: 6,
          maxBarThickness: horizontal ? 22 : 36
        }]
      },
      options: Object.assign({}, chartDefaults, {
        indexAxis: horizontal ? 'y' : 'x',
        scales: {
          x: {
            beginAtZero: true,
            ticks: { precision: 0, font: { size: 10 } },
            grid: { color: '#f1f5f9' }
          },
          y: {
            beginAtZero: true,
            ticks: { font: { size: 10 } },
            grid: { display: !horizontal, color: '#f1f5f9' }
          }
        },
        plugins: Object.assign({}, chartDefaults.plugins, { legend: { display: false } })
      })
    });
  }

  function createDoughnutChart(canvasId, items) {
    var canvas = document.getElementById(canvasId);
    if (!canvas) return;
    var mapped = mapChartData(items);

    new Chart(canvas, {
      type: 'doughnut',
      data: {
        labels: mapped.labels,
        datasets: [{
          data: mapped.values,
          backgroundColor: palette,
          borderWidth: 0
        }]
      },
      options: Object.assign({}, chartDefaults, {
        cutout: '62%'
      })
    });
  }

  function resizeAnalyticsCharts() {
    var section = document.getElementById('ticketsAnalytics');
    if (!section) return;
    section.querySelectorAll('canvas').forEach(function (canvas) {
      var chart = Chart.getChart(canvas);
      if (chart) chart.resize();
    });
  }

  function renderChartsFromData() {
    if (chartsReady) {
      resizeAnalyticsCharts();
      return;
    }
    chartsReady = true;

    createBarChart('chartTicketsMes', meses, data.por_mes || [], false);

    var etapa = mapChartData(data.por_etapa);
    createBarChart('chartTicketsEtapa', etapa.labels, etapa.values, true);

    createDoughnutChart('chartTicketsEstado', data.por_estado);
    createDoughnutChart('chartTicketsTipo', data.por_tipo);
    createDoughnutChart('chartTicketsPrioridad', data.por_prioridad);

    var resp = mapChartData(data.por_responsable);
    createBarChart('chartTicketsResponsable', resp.labels, resp.values, true);

    if (!data.es_vista_cliente && (data.top_clientes || []).length) {
      var clientes = mapChartData(data.top_clientes);
      createBarChart('chartTicketsClientes', clientes.labels, clientes.values, true);
    }
  }

  function buildAnalyticsUrl() {
    var params = new URLSearchParams();
    params.set('anio', String(data.anio || new Date().getFullYear()));
    var cteMatch = window.location.search.match(/[?&]cte=(\d+)/);
    if (cteMatch) {
      params.set('cte', cteMatch[1]);
    }
    return 'ajax/ajax-clientes-tikets-analytics.php?' + params.toString();
  }

  function setAnalyticsLoading(isLoading) {
    var section = document.getElementById('ticketsAnalytics');
    if (!section) return;
    var body = section.querySelector('.tickets-collapsible-body');
    if (!body) return;
    var existing = body.querySelector('.tickets-analytics-loading');
    if (isLoading && !existing) {
      var tip = document.createElement('p');
      tip.className = 'tickets-analytics-loading';
      tip.style.cssText = 'margin:0 0 12px;color:#64748b;font-size:13px;';
      tip.textContent = 'Cargando indicadores…';
      body.insertBefore(tip, body.firstChild);
    } else if (!isLoading && existing) {
      existing.remove();
    }
  }

  function initTicketsAnalyticsCharts() {
    if (chartsReady) {
      resizeAnalyticsCharts();
      return;
    }

    if (!data.lazy) {
      renderChartsFromData();
      return;
    }

    if (loadingAnalytics) {
      return;
    }

    loadingAnalytics = true;
    setAnalyticsLoading(true);

    fetch(buildAnalyticsUrl(), { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (payload) {
        if (!payload || !payload.success || !payload.analytics) {
          throw new Error((payload && payload.message) || 'No se pudieron cargar los indicadores');
        }
        data = payload.analytics;
        dataEl.textContent = JSON.stringify(data);
        renderChartsFromData();
      })
      .catch(function () {
        setAnalyticsLoading(false);
        var section = document.getElementById('ticketsAnalytics');
        var body = section ? section.querySelector('.tickets-collapsible-body') : null;
        if (body && !body.querySelector('.tickets-analytics-error')) {
          var err = document.createElement('p');
          err.className = 'tickets-analytics-error';
          err.style.cssText = 'margin:0 0 12px;color:#b91c1c;font-size:13px;';
          err.textContent = 'No se pudieron cargar los gráficos. Intenta de nuevo.';
          body.insertBefore(err, body.firstChild);
        }
      })
      .finally(function () {
        loadingAnalytics = false;
        setAnalyticsLoading(false);
      });
  }

  window.initTicketsAnalyticsCharts = initTicketsAnalyticsCharts;

  var section = document.getElementById('ticketsAnalytics');
  if (section && !section.classList.contains('is-collapsed')) {
    initTicketsAnalyticsCharts();
  }
})();
