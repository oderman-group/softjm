<?php
include("sesion.php");
$idPagina = 9;
include("includes/verificar-paginas.php");
include("../brand-config.php");
include(RUTA_PROYECTO."/usuarios/class/Cliente.php");

// Obtener estadísticas
$clienteConMasVenta = Cliente::obtenerDatosClienteConMasComprasAgnoActual($idEmpresa, $conexionBdPrincipal);
$clientesNuevosEsteMes = Cliente::clientesNuevosEstesMes($idEmpresa, $conexionBdPrincipal);

// Configuración de la página
$pageTitle = "Clientes";
$breadcrumbs = [
  ['name' => 'Inicio', 'url' => 'index.php'],
  ['name' => 'Clientes']
];

$footerJS = [
  'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js',
  'https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js'
];

$additionalCSS = [
  'https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css'
];

include("includes/head-modern.php");
include("includes/sidebar-modern.php");

// Incluir funciones del sistema si no están incluidas
if(!function_exists('contarClientesPorDepto')) {
  include("includes/funciones-para-el-sistema.php");
}

// Lógica de filtros (mantener exactamente igual)
$filtro = "";
if (isset($_GET["pap"]) and $_GET["pap"] == 1) {
  $filtro .= " AND cli_papelera=1";
} else {
  $filtro .= " AND (cli_papelera=0 OR cli_papelera IS NULL)";
}

$filtroGrupos = '';
if (isset($_GET["grupo"]) and is_numeric($_GET["grupo"])) {
  $filtroGrupos .= "LEFT JOIN clientes_categorias ON cpcat_cliente=cli_id AND cpcat_categoria='" . $_GET["grupo"] . "'";
}

if (isset($_GET["tipoDoc"]) and is_numeric($_GET["tipoDoc"])) {
  $filtro .= " AND cli_tipo_documento='" . $_GET["tipoDoc"] . "'";
}

if(Modulos::validarRol([385], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
  $filtro.=' AND cli_ciudad!="1122"';
}

if (isset($_GET["clientesNuevos"])) {
  $filtro .= " AND year(cli_fecha_ingreso)=".date("Y")." AND month(cli_fecha_ingreso)=".date("m");
}

if (isset($_GET["categoria"]) && is_numeric($_GET["categoria"])) {
  $filtro .= " AND cli_categoria=".$_GET["categoria"];
}

if (isset($_GET["fecha_registro_inicio"]) and $_GET["fecha_registro_inicio"] != "") {
  $filtro .= " AND cli_fecha_registro >= '" . $_GET["fecha_registro_inicio"] . " 00:00:00'";
}

if (isset($_GET["fecha_registro_fin"]) and $_GET["fecha_registro_fin"] != "") {
  $filtro .= " AND cli_fecha_registro <= '" . $_GET["fecha_registro_fin"] . " 23:59:59'";
}

if (isset($_GET["fecha_ingreso_inicio"]) and $_GET["fecha_ingreso_inicio"] != "") {
  $filtro .= " AND cli_fecha_ingreso >= '" . $_GET["fecha_ingreso_inicio"] . " 00:00:00' AND cli_categoria = 2";
}

if (isset($_GET["fecha_ingreso_fin"]) and $_GET["fecha_ingreso_fin"] != "") {
  $filtro .= " AND cli_fecha_ingreso <= '" . $_GET["fecha_ingreso_fin"] . " 23:59:59' AND cli_categoria = 2";
}

// SQL para consulta
$dpto = "";
if (isset($_GET["dpto"]) and $_GET["dpto"]!="") {
  $SQL = "SELECT * FROM ".MAINBD.".clientes
    LEFT JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
    INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento AND dep_id='".$_GET["dpto"]."'
    $filtroGrupos
    WHERE cli_id=cli_id ".$filtro."";
  $dpto=$_GET["dpto"];
} else {
  $SQL = "SELECT * FROM ".MAINBD.".clientes
    LEFT JOIN ".BDADMIN.".localidad_ciudades ON ciu_id=cli_ciudad
    INNER JOIN ".BDADMIN.".localidad_departamentos ON dep_id=ciu_departamento 
    $filtroGrupos
    WHERE cli_id=cli_id ".$filtro."";					
}

// Estadísticas rápidas
$totalClientes = $conexionBdPrincipal->query("SELECT COUNT(*) as total FROM clientes WHERE cli_id_empresa='".$idEmpresa."' AND (cli_papelera=0 OR cli_papelera IS NULL)");
$totalClientesData = mysqli_fetch_array($totalClientes, MYSQLI_BOTH);

$clientesActivos = $conexionBdPrincipal->query("SELECT COUNT(*) as total FROM clientes WHERE cli_id_empresa='".$idEmpresa."' AND cli_categoria=2 AND (cli_papelera=0 OR cli_papelera IS NULL)");
$clientesActivosData = mysqli_fetch_array($clientesActivos, MYSQLI_BOTH);

$prospectosTotal = $conexionBdPrincipal->query("SELECT COUNT(*) as total FROM clientes WHERE cli_id_empresa='".$idEmpresa."' AND cli_categoria=1 AND (cli_papelera=0 OR cli_papelera IS NULL)");
$prospectosTotalData = mysqli_fetch_array($prospectosTotal, MYSQLI_BOTH);

// Ventas de clientes del mes (calculadas desde cotizaciones vendidas)
$ventasClientes = $conexionBdPrincipal->query("
  SELECT SUM(czpp_valor * czpp_cantidad) as total 
  FROM cotizacion_productos cp
  INNER JOIN cotizacion c ON c.cotiz_id = cp.czpp_cotizacion
  WHERE c.cotiz_vendida = 1 
  AND MONTH(c.cotiz_fecha_vendida) = '".date('m')."' 
  AND YEAR(c.cotiz_fecha_vendida) = '".date('Y')."' 
  AND c.cotiz_id_empresa = '".$idEmpresa."'
");
$ventasClientesData = mysqli_fetch_array($ventasClientes, MYSQLI_BOTH);
?>

<!-- Contenido Principal -->
<div class="modern-main-content">
  
  <?php include("includes/topbar-modern.php"); ?>

  <div class="page-container">
    
    <!-- Header de la Página -->
    <div class="page-header">
      <div class="page-header-top">
        <div>
          <h1 class="page-title">
            <i class="fas fa-users" style="color: var(--primary-color);"></i>
            Gestión de Clientes
          </h1>
          <p class="page-description">
            Administra tu cartera de clientes y prospectos
          </p>
        </div>
        <div class="page-actions">
          <?php if (Modulos::validarRol([10], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)): ?>
            <a href="clientes-agregar.php" class="btn-modern btn-primary">
              <i class="fas fa-plus"></i>
              Agregar Cliente
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-4" style="margin-bottom: var(--spacing-xl);">
      
      <!-- Total Clientes -->
      <div class="card">
        <div class="stat-card">
          <div class="stat-icon primary">
            <i class="fas fa-users"></i>
          </div>
          <div class="stat-content">
            <div class="stat-label">Total Clientes</div>
            <div class="stat-value"><?= number_format($totalClientesData['total']) ?></div>
            <div class="stat-change">
              <i class="fas fa-database"></i>
              <span>En el sistema</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Clientes Activos -->
      <div class="card">
        <div class="stat-card">
          <div class="stat-icon success">
            <i class="fas fa-user-check"></i>
          </div>
          <div class="stat-content">
            <div class="stat-label">Clientes Activos</div>
            <div class="stat-value"><?= number_format($clientesActivosData['total']) ?></div>
            <div class="stat-change positive">
              <i class="fas fa-check-circle"></i>
              <span>Categoría cliente</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Prospectos -->
      <div class="card">
        <div class="stat-card">
          <div class="stat-icon warning">
            <i class="fas fa-user-clock"></i>
          </div>
          <div class="stat-content">
            <div class="stat-label">Prospectos</div>
            <div class="stat-value"><?= number_format($prospectosTotalData['total']) ?></div>
            <div class="stat-change">
              <i class="fas fa-hourglass-half"></i>
              <span>Por convertir</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Ventas del Mes -->
      <div class="card">
        <div class="stat-card">
          <div class="stat-icon info">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <div class="stat-content">
            <div class="stat-label">Ventas del Mes</div>
            <div class="stat-value">$<?= number_format($ventasClientesData['total'] ?? 0, 0, ',', '.') ?></div>
            <div class="stat-change positive">
              <i class="fas fa-arrow-up"></i>
              <span>Total ventas</span>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Destacados del Mes -->
    <?php if($clientesNuevosEsteMes > 0 || !empty($clienteConMasVenta)): ?>
    <div class="grid grid-cols-2" style="margin-bottom: var(--spacing-xl);">
      
      <?php if($clientesNuevosEsteMes > 0): ?>
      <div class="card" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; border: none;">
        <div style="display: flex; align-items: center; gap: 20px;">
          <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
            <i class="fas fa-user-plus" style="font-size: 32px;"></i>
          </div>
          <div>
            <div style="font-size: 40px; font-weight: 800; margin-bottom: 4px;"><?= $clientesNuevosEsteMes ?></div>
            <div style="font-size: 15px; opacity: 0.95;">Clientes nuevos este mes</div>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <?php if(!empty($clienteConMasVenta) && isset($clienteConMasVenta['total_compras'])): ?>
      <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none;">
        <div style="display: flex; align-items: center; gap: 20px;">
          <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
            <i class="fas fa-trophy" style="font-size: 32px;"></i>
          </div>
          <div style="flex: 1;">
            <div style="font-size: 15px; opacity: 0.95; margin-bottom: 4px;">Cliente Top del Año</div>
            <div style="font-size: 18px; font-weight: 700; margin-bottom: 4px;"><?= $clienteConMasVenta['cli_nombre'] ?? 'N/A' ?></div>
            <div style="font-size: 14px; opacity: 0.9;">$<?= number_format($clienteConMasVenta['total_compras'] ?? 0, 0, ',', '.') ?> en ventas</div>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </div>
    <?php endif; ?>

    <!-- Filtros y Búsqueda -->
    <div class="card" style="margin-bottom: var(--spacing-xl);">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-filter"></i>
          Filtros y Búsqueda
        </h3>
        <button class="btn-modern btn-outline" style="padding: 8px 16px; font-size: 13px;" onclick="location.href='clientes.php'">
          <i class="fas fa-times"></i>
          Limpiar filtros
        </button>
      </div>
      <div class="card-body">
        <form method="get" id="formFiltros">
          <div class="grid grid-cols-4" style="gap: 16px;">
            
            <!-- Búsqueda -->
            <div style="grid-column: 1 / -1;">
              <label style="font-size: 14px; font-weight: 600; color: var(--text-primary); display: block; margin-bottom: 8px;">
                <i class="fas fa-search"></i> Búsqueda General
              </label>
              <div style="display: flex; gap: 8px;">
                <input 
                  type="text" 
                  name="busqueda" 
                  id="busquedaInput"
                  placeholder="Buscar por nombre, NIT, teléfono, email..."
                  value="<?= $_GET['busqueda'] ?? '' ?>"
                  style="flex: 1; padding: 12px 16px; border: 2px solid var(--border-color); border-radius: 10px; font-size: 14px; transition: all 0.3s ease;"
                  onfocus="this.style.borderColor='var(--primary-color)'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                  onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'"
                >
                <button type="button" class="btn-modern btn-primary" onclick="buscarClientes()">
                  <i class="fas fa-search"></i>
                  Buscar
                </button>
              </div>
            </div>

            <!-- Departamento -->
            <div>
              <label style="font-size: 14px; font-weight: 600; color: var(--text-primary); display: block; margin-bottom: 8px;">
                Departamento
              </label>
              <select name="dpto" style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px; font-size: 14px;" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php
                if(Modulos::validarRol([387], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)){
                  $departamentos = $conexionBdAdmin->query("SELECT * FROM localidad_departamentos ORDER BY dep_nombre");
                }else{
                  $departamentos = $conexionBdAdmin->query("SELECT * FROM ".BDADMIN.".localidad_departamentos
                  INNER JOIN ".MAINBD.".zonas_usuarios ON zpu_usuario='".$_SESSION["id"]."' AND zpu_zona=dep_id
                  ORDER BY dep_nombre");
                }
                while($deptos = mysqli_fetch_array($departamentos, MYSQLI_BOTH)){
                  $selected = (isset($_GET["dpto"]) && $_GET["dpto"]==$deptos[0]) ? 'selected' : '';
                  $count = contarClientesPorDepto($deptos[0]);
                  echo "<option value='".$deptos[0]."' $selected>".$deptos[1]." ($count)</option>";
                }
                ?>
              </select>
            </div>

            <!-- Tipo Documento -->
            <div>
              <label style="font-size: 14px; font-weight: 600; color: var(--text-primary); display: block; margin-bottom: 8px;">
                Tipo Documento
              </label>
              <select name="tipoDoc" style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px; font-size: 14px;" onchange="this.form.submit()">
                <option value="">Todos</option>
                <option value="2" <?= (isset($_GET["tipoDoc"]) && $_GET["tipoDoc"]=='2') ? 'selected' : '' ?>>NIT</option>
                <option value="3" <?= (isset($_GET["tipoDoc"]) && $_GET["tipoDoc"]=='3') ? 'selected' : '' ?>>Cédula</option>
              </select>
            </div>

            <!-- Categoría -->
            <div>
              <label style="font-size: 14px; font-weight: 600; color: var(--text-primary); display: block; margin-bottom: 8px;">
                Categoría
              </label>
              <select name="categoria" style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px; font-size: 14px;" onchange="this.form.submit()">
                <option value="">Todas</option>
                <option value="1" <?= (isset($_GET["categoria"]) && $_GET["categoria"]=='1') ? 'selected' : '' ?>>Prospecto</option>
                <option value="2" <?= (isset($_GET["categoria"]) && $_GET["categoria"]=='2') ? 'selected' : '' ?>>Cliente</option>
                <option value="3" <?= (isset($_GET["categoria"]) && $_GET["categoria"]=='3') ? 'selected' : '' ?>>Dealer</option>
              </select>
            </div>

            <!-- Grupo/Dealer -->
            <div>
              <label style="font-size: 14px; font-weight: 600; color: var(--text-primary); display: block; margin-bottom: 8px;">
                Grupo
              </label>
              <select name="grupo" style="width: 100%; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px; font-size: 14px;" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php
                $grupos = $conexionBdPrincipal->query("SELECT * FROM dealer WHERE deal_id_empresa='".$idEmpresa."'");
                while($grupo = mysqli_fetch_array($grupos, MYSQLI_BOTH)){
                  $selected = (isset($_GET["grupo"]) && $_GET["grupo"]==$grupo[0]) ? 'selected' : '';
                  
                  $consultaContarClientes = $conexionBdPrincipal->query("SELECT COUNT(*) FROM clientes_categorias
                  INNER JOIN clientes ON cli_id=cpcat_cliente AND (cli_papelera=0 OR  cli_papelera IS NULL)
                  WHERE cpcat_categoria='".$grupo[0]."' AND cli_id_empresa='".$idEmpresa."'");
                  $contarClientes = mysqli_fetch_array($consultaContarClientes, MYSQLI_BOTH);
                  
                  echo "<option value='".$grupo[0]."' $selected>".$grupo['deal_nombre']." (".$contarClientes[0].")</option>";
                }
                ?>
              </select>
            </div>

          </div>

          <!-- Botones de Acción Adicionales -->
          <div style="display: flex; gap: 12px; margin-top: 20px; flex-wrap: wrap;">
            <?php if (Modulos::validarRol([252], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)): ?>
              <a href="clientes-importar.php" class="btn-modern btn-outline">
                <i class="fas fa-upload"></i>
                Importar Clientes
              </a>
            <?php endif; ?>
            
            <?php if (Modulos::validarRol([103], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)): ?>
              <a href="clientes-filtro.php" class="btn-modern btn-outline" target="_blank">
                <i class="fas fa-print"></i>
                Imprimir Informe
              </a>
            <?php endif; ?>
            
            <?php if (Modulos::validarRol([264], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)): ?>
              <a href="excel_exportar/clientes-exportar.php?dpto=<?= $_GET['dpto'] ?? '' ?>" class="btn-modern btn-success" target="_blank">
                <i class="fas fa-file-excel"></i>
                Exportar a Excel
              </a>
            <?php endif; ?>
            
            <?php if (Modulos::validarRol([2], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)): ?>
              <a href="clientes.php?pap=1" class="btn-modern btn-outline" style="border-color: var(--error-color); color: var(--error-color);">
                <i class="fas fa-trash"></i>
                Ver Papelera
              </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabla de Clientes -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-list"></i>
          Listado de Clientes
        </h3>
        <div style="display: flex; gap: 8px; font-size: 11px; color: var(--text-secondary); align-items: center;">
          <span><strong>TK</strong>=Tickets</span>
          <span><strong>SG</strong>=Seguimientos</span>
          <span><strong>SC</strong>=Sucursales</span>
          <span><strong>CT</strong>=Contactos</span>
        </div>
      </div>
      <div class="card-body" style="padding: 0; overflow-x: auto;">
        
        <!-- Paginación superior -->
        <div style="padding: 20px 24px; background: var(--gray-50); border-bottom: 1px solid var(--border-color);">
          <?php 
          // Incluir lógica de paginación
          $inicio = 0;
          $limite = 50;
          
          if(isset($_GET["inicio"]) && is_numeric($_GET["inicio"])){
            $inicio = $_GET["inicio"];
          }
          
          $consulta = $conexionBdPrincipal->query($SQL);
          $numReg = mysqli_num_rows($consulta);
          ?>
          <p style="margin: 0; color: var(--text-secondary); font-size: 14px;">
            <i class="fas fa-info-circle"></i>
            Mostrando <?= ($inicio + 1) ?> - <?= min($inicio + $limite, $numReg) ?> de <?= number_format($numReg) ?> clientes
          </p>
        </div>

        <table class="table-modern" id="tablaClientes">
          <thead>
            <tr>
              <th style="width: 50px;">No</th>
              <th>Ciudad, Dpto</th>
              <th>Información del Cliente</th>
              <th style="width: 50px; text-align: center;">TK</th>
              <th style="width: 50px; text-align: center;">SG</th>
              <th style="width: 50px; text-align: center;">SC</th>
              <th style="width: 50px; text-align: center;">CT</th>
              <th style="width: 120px; text-align: center;">Acciones</th>
            </tr>
          </thead>
          <tbody id="clientes_buscar">
            <?php
            // Mantener la lógica de fetch-buscar-clientes.php inline
            $consulta = $conexionBdPrincipal->query($SQL." LIMIT $inicio, $limite");
            $no = $inicio + 1;
            
            while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
              
              // Contadores
              $numTickets = $conexionBdPrincipal->query("SELECT COUNT(*) FROM clientes_tikets WHERE tik_cliente='".$res['cli_id']."'");
              $numTickets = mysqli_fetch_array($numTickets, MYSQLI_BOTH);
              
              $numSeguimientos = $conexionBdPrincipal->query("SELECT COUNT(*) FROM cliente_seguimiento WHERE cseg_cliente='".$res['cli_id']."'");
              $numSeguimientos = mysqli_fetch_array($numSeguimientos, MYSQLI_BOTH);
              
              $numSucursales = $conexionBdPrincipal->query("SELECT COUNT(*) FROM sucursales WHERE sucu_cliente_principal='".$res['cli_id']."'");
              $numSucursales = mysqli_fetch_array($numSucursales, MYSQLI_BOTH);
              
              $numContactos = $conexionBdPrincipal->query("SELECT COUNT(*) FROM contactos WHERE cont_cliente_principal='".$res['cli_id']."'");
              $numContactos = mysqli_fetch_array($numContactos, MYSQLI_BOTH);

              // Categoría badge
              $categoriaBadge = '';
              $categoriaColor = '';
              switch($res['cli_categoria']) {
                case 1:
                  $categoriaBadge = 'Prospecto';
                  $categoriaColor = 'background: #fed7aa; color: #9a3412;';
                  break;
                case 2:
                  $categoriaBadge = 'Cliente';
                  $categoriaColor = 'background: #bbf7d0; color: #166534;';
                  break;
                case 3:
                  $categoriaBadge = 'Dealer';
                  $categoriaColor = 'background: #ddd6fe; color: #5b21b6;';
                  break;
              }
            ?>
            <tr>
              <td style="font-weight: 600; color: var(--text-secondary);"><?= $no ?></td>
              <td>
                <div style="font-size: 13px; color: var(--text-primary);">
                  <i class="fas fa-map-marker-alt" style="color: var(--primary-color); margin-right: 4px;"></i>
                  <?= $res['ciu_nombre'] ?>
                </div>
                <div style="font-size: 12px; color: var(--text-secondary);">
                  <?= $res['dep_nombre'] ?>
                  <?php if($res['cli_indicativo']): ?>
                    <span style="padding: 2px 6px; background: var(--gray-200); border-radius: 4px; margin-left: 4px;">
                      Ind. <?= $res['cli_indicativo'] ?>
                    </span>
                  <?php endif; ?>
                </div>
              </td>
              <td>
                <div style="margin-bottom: 8px;">
                  <div style="font-weight: 600; font-size: 15px; color: var(--text-primary); margin-bottom: 4px;">
                    <a href="clientes-editar.php?id=<?= $res['cli_id'] ?>" style="color: var(--primary-color); text-decoration: none;">
                      <?= $res['cli_nombre'] ?>
                    </a>
                  </div>
                  <span style="padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; <?= $categoriaColor ?>">
                    <?= $categoriaBadge ?>
                  </span>
                </div>
                <div style="display: grid; grid-template-columns: auto 1fr; gap: 8px 12px; font-size: 13px; color: var(--text-secondary);">
                  <span><i class="fas fa-id-card" style="width: 14px;"></i></span>
                  <span><?= $res['cli_usuario'] ?></span>
                  
                  <?php if($res['cli_telefono']): ?>
                  <span><i class="fas fa-phone" style="width: 14px;"></i></span>
                  <span><?= $res['cli_telefono'] ?></span>
                  <?php endif; ?>
                  
                  <?php if($res['cli_email']): ?>
                  <span><i class="fas fa-envelope" style="width: 14px;"></i></span>
                  <span><?= $res['cli_email'] ?></span>
                  <?php endif; ?>
                </div>
              </td>
              <td style="text-align: center;">
                <a href="clientes-tikets.php?cliente=<?= $res['cli_id'] ?>" style="text-decoration: none;">
                  <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: <?= $numTickets[0] > 0 ? '#fef3c7' : 'var(--gray-100)' ?>; color: <?= $numTickets[0] > 0 ? '#92400e' : 'var(--text-secondary)' ?>; border-radius: 8px; font-weight: 600; font-size: 13px;">
                    <?= $numTickets[0] ?>
                  </span>
                </a>
              </td>
              <td style="text-align: center;">
                <a href="clientes-seguimiento.php?cliente=<?= $res['cli_id'] ?>" style="text-decoration: none;">
                  <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: <?= $numSeguimientos[0] > 0 ? '#dbeafe' : 'var(--gray-100)' ?>; color: <?= $numSeguimientos[0] > 0 ? '#1e40af' : 'var(--text-secondary)' ?>; border-radius: 8px; font-weight: 600; font-size: 13px;">
                    <?= $numSeguimientos[0] ?>
                  </span>
                </a>
              </td>
              <td style="text-align: center;">
                <a href="sucursales.php?cliente=<?= $res['cli_id'] ?>" style="text-decoration: none;">
                  <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: <?= $numSucursales[0] > 0 ? '#e0e7ff' : 'var(--gray-100)' ?>; color: <?= $numSucursales[0] > 0 ? '#4338ca' : 'var(--text-secondary)' ?>; border-radius: 8px; font-weight: 600; font-size: 13px;">
                    <?= $numSucursales[0] ?>
                  </span>
                </a>
              </td>
              <td style="text-align: center;">
                <a href="contactos.php?cliente=<?= $res['cli_id'] ?>" style="text-decoration: none;">
                  <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: <?= $numContactos[0] > 0 ? '#dcfce7' : 'var(--gray-100)' ?>; color: <?= $numContactos[0] > 0 ? '#166534' : 'var(--text-secondary)' ?>; border-radius: 8px; font-weight: 600; font-size: 13px;">
                    <?= $numContactos[0] ?>
                  </span>
                </a>
              </td>
              <td style="text-align: center;">
                <div style="display: flex; gap: 6px; justify-content: center;">
                  <a href="clientes-editar.php?id=<?= $res['cli_id'] ?>" class="btn-icon" data-tooltip="Editar cliente" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background: var(--primary-color); color: white; border-radius: 8px; text-decoration: none; transition: all 0.2s ease;">
                    <i class="fas fa-edit"></i>
                  </a>
                  
                  <?php if (Modulos::validarRol([11], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)): ?>
                    <a href="clientes-eliminar.php?id=<?= $res['cli_id'] ?>" class="btn-icon" data-tooltip="Eliminar cliente" onclick="return confirm('¿Estás seguro de eliminar este cliente?')" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background: var(--error-color); color: white; border-radius: 8px; text-decoration: none; transition: all 0.2s ease;">
                      <i class="fas fa-trash"></i>
                    </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
            <?php 
              $no++;
            } 
            ?>
          </tbody>
        </table>

        <!-- Paginación inferior -->
        <div style="padding: 20px 24px; background: var(--gray-50); border-top: 1px solid var(--border-color);">
          <?php
          // Paginación moderna
          $totalPaginas = ceil($numReg / $limite);
          $paginaActualNum = floor($inicio / $limite) + 1;
          ?>
          
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="color: var(--text-secondary); font-size: 14px;">
              Página <?= $paginaActualNum ?> de <?= $totalPaginas ?>
            </div>
            
            <div style="display: flex; gap: 4px;">
              
              <?php if($paginaActualNum > 1): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['inicio' => 0])) ?>" style="padding: 8px 12px; background: white; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-primary); font-size: 13px; font-weight: 500; transition: all 0.2s ease;" onmouseover="this.style.background='var(--primary-color)'; this.style.color='white'; this.style.borderColor='var(--primary-color)'" onmouseout="this.style.background='white'; this.style.color='var(--text-primary)'; this.style.borderColor='var(--border-color)'">
                  <i class="fas fa-angle-double-left"></i>
                </a>
                <a href="?<?= http_build_query(array_merge($_GET, ['inicio' => max(0, $inicio - $limite)])) ?>" style="padding: 8px 12px; background: white; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-primary); font-size: 13px; font-weight: 500; transition: all 0.2s ease;" onmouseover="this.style.background='var(--primary-color)'; this.style.color='white'; this.style.borderColor='var(--primary-color)'" onmouseout="this.style.background='white'; this.style.color='var(--text-primary)'; this.style.borderColor='var(--border-color)'">
                  <i class="fas fa-angle-left"></i> Anterior
                </a>
              <?php endif; ?>

              <?php
              // Mostrar números de página
              $rango = 2;
              $inicioRango = max(1, $paginaActualNum - $rango);
              $finRango = min($totalPaginas, $paginaActualNum + $rango);

              if($inicioRango > 1): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['inicio' => 0])) ?>" style="padding: 8px 12px; background: white; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-primary); font-size: 13px; font-weight: 500;">1</a>
                <?php if($inicioRango > 2): ?>
                  <span style="padding: 8px 12px; color: var(--text-secondary);">...</span>
                <?php endif; ?>
              <?php endif; ?>

              <?php for($i = $inicioRango; $i <= $finRango; $i++): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['inicio' => ($i - 1) * $limite])) ?>" style="padding: 8px 12px; background: <?= ($i == $paginaActualNum) ? 'var(--primary-color)' : 'white' ?>; border: 1px solid <?= ($i == $paginaActualNum) ? 'var(--primary-color)' : 'var(--border-color)' ?>; border-radius: 8px; text-decoration: none; color: <?= ($i == $paginaActualNum) ? 'white' : 'var(--text-primary)' ?>; font-size: 13px; font-weight: <?= ($i == $paginaActualNum) ? '700' : '500' ?>; transition: all 0.2s ease;" <?= ($i != $paginaActualNum) ? "onmouseover=\"this.style.background='var(--primary-color)'; this.style.color='white'; this.style.borderColor='var(--primary-color)'\" onmouseout=\"this.style.background='white'; this.style.color='var(--text-primary)'; this.style.borderColor='var(--border-color)'\"" : '' ?>>
                  <?= $i ?>
                </a>
              <?php endfor; ?>

              <?php if($finRango < $totalPaginas): ?>
                <?php if($finRango < $totalPaginas - 1): ?>
                  <span style="padding: 8px 12px; color: var(--text-secondary);">...</span>
                <?php endif; ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['inicio' => ($totalPaginas - 1) * $limite])) ?>" style="padding: 8px 12px; background: white; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-primary); font-size: 13px; font-weight: 500;"><?= $totalPaginas ?></a>
              <?php endif; ?>

              <?php if($paginaActualNum < $totalPaginas): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['inicio' => $inicio + $limite])) ?>" style="padding: 8px 12px; background: white; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-primary); font-size: 13px; font-weight: 500; transition: all 0.2s ease;" onmouseover="this.style.background='var(--primary-color)'; this.style.color='white'; this.style.borderColor='var(--primary-color)'" onmouseout="this.style.background='white'; this.style.color='var(--text-primary)'; this.style.borderColor='var(--border-color)'">
                  Siguiente <i class="fas fa-angle-right"></i>
                </a>
                <a href="?<?= http_build_query(array_merge($_GET, ['inicio' => ($totalPaginas - 1) * $limite])) ?>" style="padding: 8px 12px; background: white; border: 1px solid var(--border-color); border-radius: 8px; text-decoration: none; color: var(--text-primary); font-size: 13px; font-weight: 500; transition: all 0.2s ease;" onmouseover="this.style.background='var(--primary-color)'; this.style.color='white'; this.style.borderColor='var(--primary-color)'" onmouseout="this.style.background='white'; this.style.color='var(--text-primary)'; this.style.borderColor='var(--border-color)'">
                  <i class="fas fa-angle-double-right"></i>
                </a>
              <?php endif; ?>

            </div>
          </div>
        </div>

      </div>
    </div>

  </div>

</div>

<?php
$inlineScript = "
// Búsqueda asíncrona de clientes
function buscarClientes() {
  const busqueda = document.getElementById('busquedaInput').value;
  const tbody = document.getElementById('clientes_buscar');
  
  if(!busqueda.trim()) {
    location.reload();
    return;
  }
  
  showLoading();
  
  const params = new URLSearchParams(window.location.search);
  params.set('busqueda', busqueda);
  
  fetch('fetch-buscar-clientes.php?' + params.toString())
    .then(response => response.text())
    .then(data => {
      tbody.innerHTML = data;
      hideLoading();
      showNotification('Búsqueda completada', 'success', 2000);
    })
    .catch(error => {
      console.error('Error:', error);
      hideLoading();
      showNotification('Error al buscar clientes', 'error');
    });
}

// Buscar con Enter
document.getElementById('busquedaInput').addEventListener('keypress', function(e) {
  if(e.key === 'Enter') {
    e.preventDefault();
    buscarClientes();
  }
});

// Hover effects para botones de acción
document.querySelectorAll('.btn-icon').forEach(btn => {
  btn.addEventListener('mouseenter', function() {
    this.style.transform = 'scale(1.1)';
    this.style.boxShadow = 'var(--shadow-md)';
  });
  btn.addEventListener('mouseleave', function() {
    this.style.transform = 'scale(1)';
    this.style.boxShadow = 'none';
  });
});
";

include("includes/footer-modern.php");
?>

<style>
/* Estilos adicionales para tabla de clientes */
.table-modern tbody tr {
  transition: all 0.2s ease;
}

.table-modern tbody tr:hover {
  background: #f8fafc !important;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* Animación para cards de estadísticas */
@media (hover: hover) {
  .card:hover {
    transform: translateY(-2px);
  }
}
</style>

