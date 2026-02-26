<?php
include("sesion.php");
$idPagina = 1;
include("includes/head.php");
?>

<!-- styles -->
<link href="css/jquery.gritter.css" rel="stylesheet">
<link href="css/tablecloth.css" rel="stylesheet">
<link href="css/dashboard-home.css" rel="stylesheet">
<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/jquery.sparkline.js"></script>
<script src="js/bootstrap-fileupload.js"></script>
<script src="js/jquery.metadata.js"></script>
<script src="js/jquery.tablesorter.min.js"></script>
<script src="js/jquery.tablecloth.js"></script>
<script src="js/jquery.flot.js"></script>
<script src="js/jquery.flot.selection.js"></script>
<script src="js/excanvas.js"></script>
<script src="js/jquery.flot.pie.js"></script>
<script src="js/jquery.flot.stack.js"></script>
<script src="js/jquery.flot.time.js"></script>
<script src="js/jquery.flot.tooltip.js"></script>
<script src="js/jquery.flot.resize.js"></script>
<script src="js/jquery.collapsible.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.gritter.js"></script>
<script src="js/tiny_mce/jquery.tinymce.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>

<?php include("includes/funciones-js.php"); ?>

</head>

<body class="dashboard-home">
  <div class="layout">
    <?php include("includes/encabezado.php"); ?>

    <div class="main-wrapper">
      <div class="container-fluid">
        <div class="dashboard-home-inner">
          <div id="cargarAxios"></div>

          <?php
          $nombreParaWelcome = !empty($datosUsuarioActual['usr_nombre']) ? htmlspecialchars($datosUsuarioActual['usr_nombre']) : 'Usuario';
          ?>
          <div class="dashboard-welcome">
            <p class="dashboard-welcome-text">Bienvenido, <strong><?= $nombreParaWelcome ?></strong></p>
          </div>
          <h2 class="dashboard-section-title">Accesos rápidos</h2>
          <div class="dashboard-shortcuts">
            <a href="panel-menu.php?p=1" class="dashboard-shortcut-card" data-accent="crm">
              <span class="shortcut-icon"><i class="fa fa-phone" aria-hidden="true"></i></span>
              <p class="shortcut-label">CRM</p>
            </a>

            <?php if ($datosUsuarioActual[3] == 1 || $datosUsuarioActual[3] == 9 || $datosUsuarioActual[3] == 10 || $datosUsuarioActual[3] == 15 || $datosUsuarioActual[3] == 14) { ?>
              <a href="remisiones.php" class="dashboard-shortcut-card" data-accent="servicio">
                <span class="shortcut-icon"><i class="fa fa-cogs" aria-hidden="true"></i></span>
                <p class="shortcut-label">Servicio técnico</p>
              </a>
            <?php } ?>

            <?php if (Modulos::validarRol([78], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
              <a href="cotizaciones-agregar.php" class="dashboard-shortcut-card" data-accent="cotizacion">
                <span class="shortcut-icon"><i class="fa fa-file-alt" aria-hidden="true"></i></span>
                <p class="shortcut-label">Crear cotización</p>
              </a>
            <?php } ?>

            <?php if (Modulos::validarRol([36], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
              <a href="productos.php" class="dashboard-shortcut-card" data-accent="productos">
                <span class="shortcut-icon"><i class="fa fa-th-large" aria-hidden="true"></i></span>
                <p class="shortcut-label">Ver productos</p>
              </a>
            <?php } ?>

            <?php if (Modulos::validarRol([170], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
              <a href="ventas.php" class="dashboard-shortcut-card" data-accent="ventas">
                <span class="shortcut-icon"><i class="fa fa-folder" aria-hidden="true"></i></span>
                <p class="shortcut-label">Ventas</p>
              </a>
            <?php } ?>

            <?php if (Modulos::validarRol([88], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
              <a href="clientes-tikets.php" class="dashboard-shortcut-card" data-accent="tickets">
                <span class="shortcut-icon"><i class="fa fa-folder-open" aria-hidden="true"></i></span>
                <p class="shortcut-label">Tickets</p>
              </a>
            <?php } ?>

            <?php if (Modulos::validarRol([12], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
              <a href="clientes-seguimiento.php" class="dashboard-shortcut-card" data-accent="seguimientos">
                <span class="shortcut-icon"><i class="fa fa-list" aria-hidden="true"></i></span>
                <p class="shortcut-label">Seguimientos</p>
              </a>
            <?php } ?>

            <?php if (Modulos::validarRol([10], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
              <a href="clientes-agregar.php" class="dashboard-shortcut-card" data-accent="cliente">
                <span class="shortcut-icon"><i class="fa fa-user-plus" aria-hidden="true"></i></span>
                <p class="shortcut-label">Crear cliente</p>
              </a>
            <?php } ?>

            <?php if (Modulos::validarRol([416], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
              <a href="kpi.php" class="dashboard-shortcut-card" data-accent="kpi">
                <span class="shortcut-icon"><i class="fa fa-chart-bar" aria-hidden="true"></i></span>
                <p class="shortcut-label">KPI</p>
              </a>
            <?php } ?>

            <?php if (Modulos::validarRol([419], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) { ?>
              <a href="listado-prospeccion.php" target="_blank" class="dashboard-shortcut-card" data-accent="prospeccion">
                <span class="shortcut-icon"><i class="fa fa-phone-volume" aria-hidden="true"></i></span>
                <p class="shortcut-label">Listado de prospección</p>
              </a>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>

    <?php include("includes/pie.php"); ?>
  </div>
</body>

</html>
