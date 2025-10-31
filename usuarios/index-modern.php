<?php
include("sesion.php");
$idPagina = 1;

// Incluir configuración de marca
include("../brand-config.php");

// Configuración de la página
$pageTitle = "Dashboard";
$breadcrumbs = [
  ['name' => 'Inicio']
];

// CSS y JS adicionales para esta página
$additionalCSS = [
  'https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css',
  'https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css'
];

$footerJS = [
  'https://cdn.anychart.com/releases/v8/js/anychart-base.min.js',
  'https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js',
  'https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js',
  'https://cdn.anychart.com/releases/v8/js/anychart-pyramid-funnel.min.js'
];

// Incluir head moderno
include("includes/head-modern.php");

// Incluir sidebar
include("includes/sidebar-modern.php");
?>

<!-- Contenido Principal -->
<div class="modern-main-content">
  
  <?php include("includes/topbar-modern.php"); ?>

  <div class="page-container">
    
    <!-- Header de la Página -->
    <div class="page-header">
      <div class="page-header-top">
        <div>
          <h1 class="page-title" style="font-size: 32px; margin-bottom: 8px;">
            👋 Bienvenido, <?= explode(' ', $_SESSION["dataAdicional"]["nombre_completo"])[0] ?>
          </h1>
          <p class="page-description">
            Aquí tienes un resumen de tu sistema
          </p>
        </div>
        <div class="page-actions">
          <button class="btn-modern btn-outline" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i>
            Actualizar
          </button>
        </div>
      </div>
    </div>

    <!-- Accesos Rápidos -->
    <div style="margin-bottom: var(--spacing-xl);">
      <h2 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: var(--spacing-md);">
        <i class="fas fa-bolt" style="color: var(--warning-color);"></i>
        Accesos Rápidos
      </h2>
      
      <div class="grid grid-cols-4">
        
        <!-- CRM -->
        <a href="panel-menu.php?p=1" style="text-decoration: none;">
          <div class="card" style="cursor: pointer; background: linear-gradient(135deg, #fbbd01 0%, #f59e0b 100%); color: white; border: none; transition: transform 200ms ease;">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                <i class="fas fa-phone" style="font-size: 24px;"></i>
              </div>
              <div style="flex: 1;">
                <div style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">CRM</div>
                <div style="font-size: 13px; opacity: 0.9;">Gestión de clientes</div>
              </div>
              <i class="fas fa-arrow-right" style="font-size: 18px; opacity: 0.7;"></i>
            </div>
          </div>
        </a>

        <?php if ($datosUsuarioActual[3] == 1 || $datosUsuarioActual[3] == 9 || $datosUsuarioActual[3] == 10 || $datosUsuarioActual[3] == 15 || $datosUsuarioActual[3] == 14): ?>
        <!-- Servicio Técnico -->
        <a href="remisiones.php" style="text-decoration: none;">
          <div class="card" style="cursor: pointer; background: linear-gradient(135deg, #eb4132 0%, #dc2626 100%); color: white; border: none;">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-cogs" style="font-size: 24px;"></i>
              </div>
              <div style="flex: 1;">
                <div style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Servicio Técnico</div>
                <div style="font-size: 13px; opacity: 0.9;">Remisiones y reparaciones</div>
              </div>
              <i class="fas fa-arrow-right" style="font-size: 18px; opacity: 0.7;"></i>
            </div>
          </div>
        </a>
        <?php endif; ?>

        <?php if (Modulos::validarRol([78], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)): ?>
        <!-- Crear Cotización -->
        <a href="cotizaciones-agregar.php" style="text-decoration: none;">
          <div class="card" style="cursor: pointer; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-file-alt" style="font-size: 24px;"></i>
              </div>
              <div style="flex: 1;">
                <div style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Nueva Cotización</div>
                <div style="font-size: 13px; opacity: 0.9;">Crear cotización</div>
              </div>
              <i class="fas fa-arrow-right" style="font-size: 18px; opacity: 0.7;"></i>
            </div>
          </div>
        </a>
        <?php endif; ?>

        <?php if (Modulos::validarRol([36], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)): ?>
        <!-- Productos -->
        <a href="productos.php" style="text-decoration: none;">
          <div class="card" style="cursor: pointer; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border: none;">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-th" style="font-size: 24px;"></i>
              </div>
              <div style="flex: 1;">
                <div style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Productos</div>
                <div style="font-size: 13px; opacity: 0.9;">Ver inventario</div>
              </div>
              <i class="fas fa-arrow-right" style="font-size: 18px; opacity: 0.7;"></i>
            </div>
          </div>
        </a>
        <?php endif; ?>

        <?php if (Modulos::validarRol([170], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)): ?>
        <!-- Ventas -->
        <a href="ventas.php" style="text-decoration: none;">
          <div class="card" style="cursor: pointer; background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; border: none;">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-chart-line" style="font-size: 24px;"></i>
              </div>
              <div style="flex: 1;">
                <div style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Ventas</div>
                <div style="font-size: 13px; opacity: 0.9;">Gestión de ventas</div>
              </div>
              <i class="fas fa-arrow-right" style="font-size: 18px; opacity: 0.7;"></i>
            </div>
          </div>
        </a>
        <?php endif; ?>

        <?php if (Modulos::validarRol([88], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)): ?>
        <!-- Tickets -->
        <a href="clientes-tikets.php" style="text-decoration: none;">
          <div class="card" style="cursor: pointer; background: linear-gradient(135deg, #deb887 0%, #d4a574 100%); color: white; border: none;">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-ticket-alt" style="font-size: 24px;"></i>
              </div>
              <div style="flex: 1;">
                <div style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Tickets</div>
                <div style="font-size: 13px; opacity: 0.9;">Soporte al cliente</div>
              </div>
              <i class="fas fa-arrow-right" style="font-size: 18px; opacity: 0.7;"></i>
            </div>
          </div>
        </a>
        <?php endif; ?>

        <?php if (Modulos::validarRol([12], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)): ?>
        <!-- Seguimientos -->
        <a href="clientes-seguimiento.php" style="text-decoration: none;">
          <div class="card" style="cursor: pointer; background: linear-gradient(135deg, #5f9ea0 0%, #4a7d7f 100%); color: white; border: none;">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-list-check" style="font-size: 24px;"></i>
              </div>
              <div style="flex: 1;">
                <div style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Seguimientos</div>
                <div style="font-size: 13px; opacity: 0.9;">Seguimiento de clientes</div>
              </div>
              <i class="fas fa-arrow-right" style="font-size: 18px; opacity: 0.7;"></i>
            </div>
          </div>
        </a>
        <?php endif; ?>

        <?php if (Modulos::validarRol([52], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)): ?>
        <!-- Pedidos -->
        <a href="pedidos.php" style="text-decoration: none;">
          <div class="card" style="cursor: pointer; background: linear-gradient(135deg, #e67e22 0%, #d35400 100%); color: white; border: none;">
            <div style="display: flex; align-items: center; gap: 16px;">
              <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-shopping-cart" style="font-size: 24px;"></i>
              </div>
              <div style="flex: 1;">
                <div style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Pedidos</div>
                <div style="font-size: 13px; opacity: 0.9;">Gestión de pedidos</div>
              </div>
              <i class="fas fa-arrow-right" style="font-size: 18px; opacity: 0.7;"></i>
            </div>
          </div>
        </a>
        <?php endif; ?>

      </div>
    </div>

    <?php
    // Incluir la lógica de estadísticas del dashboard original
    // Cotizaciones en el mes actual
    $consultaCotizacionesMes = $conexionBdPrincipal->query("SELECT COUNT(*) as total FROM cotizacion WHERE cotiz_fecha_propuesta BETWEEN '" . date('Y-m') . "-01' AND '" . date('Y-m-t') . "' AND cotiz_id_empresa='" . $idEmpresa . "'");
    $cotizacionesMes = mysqli_fetch_array($consultaCotizacionesMes, MYSQLI_BOTH);

    // Ventas del mes
    $consultaVentasMes = $conexionBdPrincipal->query("SELECT SUM(ven_valor) as total FROM ventas WHERE MONTH(ven_fecha)='" . date('m') . "' AND YEAR(ven_fecha)='" . date('Y') . "' AND ven_id_empresa='" . $idEmpresa . "'");
    $ventasMes = mysqli_fetch_array($consultaVentasMes, MYSQLI_BOTH);

    // Clientes activos
    $consultaClientes = $conexionBdPrincipal->query("SELECT COUNT(*) as total FROM clientes WHERE cli_id_empresa='" . $idEmpresa . "'");
    $clientesTotal = mysqli_fetch_array($consultaClientes, MYSQLI_BOTH);

    // Productos en inventario
    $consultaProductos = $conexionBdPrincipal->query("SELECT COUNT(*) as total FROM productos WHERE prod_id_empresa='" . $idEmpresa . "'");
    $productosTotal = mysqli_fetch_array($consultaProductos, MYSQLI_BOTH);
    ?>

    <!-- Estadísticas Principales -->
    <div style="margin-bottom: var(--spacing-xl);">
      <h2 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: var(--spacing-md);">
        <i class="fas fa-chart-pie" style="color: var(--primary-color);"></i>
        Estadísticas del Mes
      </h2>
      
      <div class="grid grid-cols-4">
        
        <!-- Cotizaciones -->
        <div class="card">
          <div class="stat-card">
            <div class="stat-icon primary">
              <i class="fas fa-file-invoice"></i>
            </div>
            <div class="stat-content">
              <div class="stat-label">Cotizaciones</div>
              <div class="stat-value"><?= number_format($cotizacionesMes['total'] ?? 0) ?></div>
              <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>Este mes</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Ventas -->
        <div class="card">
          <div class="stat-card">
            <div class="stat-icon success">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
              <div class="stat-label">Ventas del Mes</div>
              <div class="stat-value">$<?= number_format($ventasMes['total'] ?? 0, 0, ',', '.') ?></div>
              <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>En ingresos</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Clientes -->
        <div class="card">
          <div class="stat-card">
            <div class="stat-icon info">
              <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
              <div class="stat-label">Clientes Activos</div>
              <div class="stat-value"><?= number_format($clientesTotal['total'] ?? 0) ?></div>
              <div class="stat-change">
                <i class="fas fa-minus"></i>
                <span>Total</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Productos -->
        <div class="card">
          <div class="stat-card">
            <div class="stat-icon warning">
              <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
              <div class="stat-label">Productos</div>
              <div class="stat-value"><?= number_format($productosTotal['total'] ?? 0) ?></div>
              <div class="stat-change">
                <i class="fas fa-minus"></i>
                <span>En inventario</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <?php
    // Obtener datos para el gráfico de productos más vendidos
    $consultaProductosVendidos = $conexionBdPrincipal->query("
      SELECT p.prod_nombre, SUM(czpp_cantidad) as cantidad, SUM(czpp_valor * czpp_cantidad) as valor
      FROM cotizacion_productos cp
      INNER JOIN productos p ON p.prod_id = cp.czpp_producto
      INNER JOIN cotizacion c ON c.cotiz_id = cp.czpp_cotizacion
      WHERE c.cotiz_id_empresa = '" . $idEmpresa . "'
      AND YEAR(c.cotiz_fecha_propuesta) = YEAR(CURDATE())
      GROUP BY p.prod_id
      ORDER BY valor DESC
      LIMIT 10
    ");

    $cotizacionesResultados = '';
    while ($producto = mysqli_fetch_array($consultaProductosVendidos, MYSQLI_BOTH)) {
      $cotizacionesResultados .= "['" . addslashes($producto['prod_nombre']) . "', " . $producto['valor'] . "],";
    }
    $cotizacionesResultados = rtrim($cotizacionesResultados, ',');
    ?>

    <!-- Gráficos -->
    <div class="grid grid-cols-1" style="margin-bottom: var(--spacing-xl);">
      
      <!-- Gráfico de Productos Más Vendidos -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-chart-bar" style="color: var(--primary-color); margin-right: 8px;"></i>
            Top 10 Productos Más Vendidos
          </h3>
          <button class="btn-modern btn-outline" style="padding: 8px 16px; font-size: 13px;" onclick="location.href='productos.php'">
            Ver todos
            <i class="fas fa-arrow-right"></i>
          </button>
        </div>
        <div class="card-body">
          <div id="productos" style="width: 100%; height: 400px;"></div>
        </div>
      </div>

    </div>

    <!-- Actividad Reciente -->
    <div style="margin-bottom: var(--spacing-xl);">
      <h2 style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: var(--spacing-md);">
        <i class="fas fa-clock" style="color: var(--info-color);"></i>
        Actividad Reciente
      </h2>
      
      <div class="card">
        <div class="card-body">
          <p style="color: var(--text-secondary); text-align: center; padding: 40px 0;">
            <i class="fas fa-info-circle" style="font-size: 48px; margin-bottom: 16px; display: block; color: var(--gray-400);"></i>
            La actividad reciente se mostrará aquí próximamente
          </p>
        </div>
      </div>
    </div>

  </div>

</div>

<?php
// Script inline para el gráfico
$inlineScript = "
anychart.onDocumentReady(function() {
  var chart = anychart.column();
  chart.animation(true);
  chart.title('10 productos más vendidos del año');
  
  var series = chart.column([
    " . $cotizacionesResultados . "
  ]);
  
  series.tooltip().titleFormat('{%X}');
  series.tooltip()
    .position('center-top')
    .anchor('center-bottom')
    .offsetX(0)
    .offsetY(5)
    .format('{%Value}{groupsSeparator: }');
  
  chart.yScale().minimum(0);
  chart.yAxis().labels().format('{%Value}{groupsSeparator: }');
  chart.tooltip().positionMode('point');
  chart.interactivity().hoverMode('by-x');
  chart.xAxis().title('Productos');
  chart.yAxis().title('Valor');
  chart.container('productos');
  chart.draw();
});
";

include("includes/footer-modern.php");
?>

<style>
/* Animación hover para las cards de acceso rápido */
.card:hover {
  transform: translateY(-4px);
}

/* Responsive para accesos rápidos */
@media (max-width: 992px) {
  .grid-cols-4 {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 576px) {
  .grid-cols-4 {
    grid-template-columns: repeat(1, 1fr);
  }
}
</style>

