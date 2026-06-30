(function () {
  var dataEl = document.getElementById('clientesAnalyticsData');
  if (!dataEl || typeof Chart === 'undefined') return;

  var data;
  try {
    data = JSON.parse(dataEl.textContent || '{}');
  } catch (e) {
    return;
  }

  var meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
  var palette = ['#2563eb', '#059669', '#d97706', '#7c3aed', '#dc2626', '#0891b2', '#db2777', '#65a30d', '#ea580c', '#4f46e5'];
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

  function createBarChart(canvasId, labels, values, horizontal, color) {
    var canvas = document.getElementById(canvasId);
    if (!canvas) return;

    new Chart(canvas, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          data: values,
          backgroundColor: color || palette.slice(0, labels.length),
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

  createBarChart('chartClientesRegistroMes', meses, data.por_mes_registro || [], false, '#2563eb');
  createBarChart('chartClientesIngresoMes', meses, data.por_mes_ingreso || [], false, '#059669');

  createDoughnutChart('chartClientesCategoria', data.por_categoria);
  createDoughnutChart('chartClientesTipoDoc', data.por_tipo_documento);

  if ((data.por_estado_mercadeo || []).length) {
    createDoughnutChart('chartClientesEstadoMercadeo', data.por_estado_mercadeo);
  }

  var depto = mapChartData(data.por_departamento);
  if (depto.labels.length) {
    createBarChart('chartClientesDepartamento', depto.labels, depto.values, true);
  }
})();
