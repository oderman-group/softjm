<?php 
include("sesion.php");

$idPagina = 12;
$paginaActual['pag_nombre'] = "Seguimiento de clientes";
include("includes/verificar-paginas.php");
include("includes/head.php");

$nombreCliente="";
if(!empty($_GET["cte"])){
	$consultaDatos=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_id='".$_GET["cte"]."' AND cli_id_empresa='".$idEmpresa."'");
	$cliente = mysqli_fetch_array($consultaDatos, MYSQLI_BOTH);
	$nombreCliente=" de <b>".$cliente['cli_nombre']."</b>";

	$filtroUsuario = " AND (cseg_usuario_responsable='" . $_SESSION["id"] . "' OR cseg_usuario_encargado='" . $_SESSION["id"] . "')";
}

if(!empty($_GET["idTK"])){
	$consultaTikets=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes_tikets WHERE tik_id='" . $_GET["idTK"] . "'");
	$tiket = mysqli_fetch_array($consultaTikets, MYSQLI_BOTH);

	$filtroUsuario = " AND (cseg_usuario_responsable='" . $_SESSION["id"] . "' OR cseg_usuario_encargado='" . $_SESSION["id"] . "')";
}
?>

<!-- Font Awesome para iconos modernos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>

<!-- Estilos Modernos para Seguimiento de Clientes -->
<style>
/* ========== ESTILOS MODERNOS PARA SEGUIMIENTO ========== */

.seguimiento-container {
    background: #f5f7fa;
    min-height: 100vh;
    padding: 20px 0;
}

.seguimiento-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 25px 30px;
    margin-bottom: 25px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    color: white;
}

.seguimiento-header h1 {
    color: white;
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.seguimiento-header .breadcrumb-modern {
    background: rgba(255,255,255,0.2);
    padding: 10px 15px;
    border-radius: 8px;
    margin-top: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.seguimiento-header .breadcrumb-modern a {
    color: white;
    text-decoration: none;
    font-weight: 500;
}

.seguimiento-header .breadcrumb-modern a:hover {
    text-decoration: underline;
}

/* ========== FILTROS MODERNOS ========== */
.filters-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.filters-card h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.filter-group {
    position: relative;
}

.filter-group label {
    display: block;
    font-weight: 600;
    color: #555;
    margin-bottom: 8px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-group input,
.filter-group select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
    color: #333;
    box-sizing: border-box;
}

.filter-group select {
    cursor: pointer;
    height: 45px;
}

.filter-group input:focus,
.filter-group select:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Indicador visual para selects con valor */
.filter-group select.has-value {
    border-color: #667eea;
    background-color: #f8f9ff;
    font-weight: 600;
}

.filter-group input.has-value {
    border-color: #667eea;
    background-color: #f8f9ff;
}

.filter-clear-btn {
    background: transparent;
    border: none;
    color: #f44336;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.filter-clear-btn:hover {
    color: #d32f2f;
    transform: scale(1.1);
}

.filter-actions {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.btn-modern {
    padding: 12px 25px;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-success-modern {
    background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
    color: white;
}

.btn-success-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
}

.btn-secondary-modern {
    background: #e0e0e0;
    color: #666;
}

.btn-secondary-modern:hover {
    background: #d0d0d0;
}

/* ========== BÚSQUEDA GLOBAL ========== */
.search-box-modern {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.search-input-wrapper {
    position: relative;
}

.search-input-modern {
    width: 100%;
    padding: 15px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 16px;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.search-input-modern:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* ========== ESTADÍSTICAS RÁPIDAS ========== */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.stat-card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 15px;
}

.stat-card-icon.blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-card-icon.green {
    background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
    color: white;
}

.stat-card-icon.orange {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    color: white;
}

.stat-card-icon.red {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    color: white;
}

.stat-card-value {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.stat-card-label {
    color: #666;
    font-size: 14px;
    margin: 5px 0 0 0;
}

/* ========== TARJETAS DE SEGUIMIENTO ========== */
.seguimientos-grid {
    display: grid;
    gap: 20px;
}

.seguimiento-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border-left: 5px solid #667eea;
}

.seguimiento-card:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    transform: translateY(-3px);
}

.seguimiento-card.destacado {
    background: linear-gradient(135deg, #fff9e6 0%, #fff3d4 100%);
    border-left-color: #ff9800;
}

.seguimiento-card-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 20px;
    cursor: pointer;
    user-select: none;
}

.seguimiento-card-header:hover .seguimiento-card-title {
    color: #667eea;
}

.seguimiento-card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #333;
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: color 0.3s ease;
}

.collapse-icon {
    font-size: 16px;
    color: #667eea;
    transition: transform 0.3s ease;
}

.seguimiento-card.collapsed .collapse-icon {
    transform: rotate(-90deg);
}

.seguimiento-card-subtitle {
    font-size: 14px;
    color: #666;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.seguimiento-actions {
    display: flex;
    gap: 10px;
    position: relative;
    z-index: 10;
}

/* Badge de estado en el header */
.status-badge-header {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge-header.completado-header {
    background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
}

.status-badge-header.pendiente-header {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(255, 152, 0, 0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

/* Borde lateral del card según estado cuando está colapsado */
.seguimiento-card.collapsed.estado-completado {
    border-left-color: #4caf50;
}

.seguimiento-card.collapsed.estado-pendiente {
    border-left-color: #ff9800;
}

.seguimiento-card-body {
    transition: all 0.3s ease;
    overflow: hidden;
}

.seguimiento-card.collapsed .seguimiento-card-body {
    max-height: 0;
    opacity: 0;
    margin: 0;
    padding: 0;
}

.action-btn {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 16px;
}

.action-btn.edit {
    background: #e3f2fd;
    color: #2196f3;
}

.action-btn.edit:hover {
    background: #2196f3;
    color: white;
}

.action-btn.add {
    background: #e8f5e9;
    color: #4caf50;
}

.action-btn.add:hover {
    background: #4caf50;
    color: white;
}

.seguimiento-card-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.info-block {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
}

.info-block h4 {
    font-size: 14px;
    font-weight: 700;
    color: #667eea;
    margin: 0 0 12px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-item {
    margin-bottom: 8px;
    font-size: 14px;
    display: flex;
    gap: 8px;
}

.info-item strong {
    color: #555;
    min-width: 80px;
}

.info-item span {
    color: #333;
}

.seguimiento-detail {
    background: #f0f4ff;
    border-radius: 10px;
    padding: 15px;
    margin-top: 15px;
    border-left: 3px solid #667eea;
}

.seguimiento-detail h5 {
    font-size: 13px;
    font-weight: 700;
    color: #667eea;
    margin: 0 0 10px 0;
    text-transform: uppercase;
}

.seguimiento-detail p {
    color: #555;
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
}

.seguimiento-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #e0e0e0;
    flex-wrap: wrap;
    gap: 10px;
}

.seguimiento-badges {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.badge-modern {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.badge-modern.success {
    background: #e8f5e9;
    color: #2e7d32;
}

.badge-modern.warning {
    background: #fff3e0;
    color: #e65100;
}

.badge-modern.info {
    background: #e3f2fd;
    color: #1565c0;
}

.badge-modern.danger {
    background: #ffebee;
    color: #c62828;
}

.status-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
}

.status-badge.completado {
    background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
    color: white;
}

.status-badge.pendiente {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    color: white;
}

.status-badge.pendiente:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
}

/* ========== PAGINACIÓN MODERNA ========== */
.pagination-modern {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin: 30px 0 15px 0;
    flex-wrap: wrap;
}

.pagination-modern a,
.pagination-modern span {
    min-width: 40px;
    height: 40px;
    padding: 0 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: white;
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 2px solid transparent;
}

.pagination-modern a:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.pagination-modern span.current {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    border: 2px solid #667eea;
}

/* Botones Anterior/Siguiente */
.pagination-modern .pagination-prev,
.pagination-modern .pagination-next {
    font-size: 14px;
    font-weight: 700;
    padding: 0 15px;
}

.pagination-modern .pagination-prev:hover,
.pagination-modern .pagination-next:hover {
    background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
    color: white;
}

.pagination-modern .pagination-prev.disabled,
.pagination-modern .pagination-next.disabled {
    background: #f5f5f5;
    color: #ccc;
    cursor: not-allowed;
    box-shadow: none;
}

/* Puntos suspensivos */
.pagination-modern .pagination-dots {
    background: transparent;
    color: #999;
    font-weight: 700;
    box-shadow: none;
    cursor: default;
    min-width: 30px;
    letter-spacing: 2px;
}

/* Información de paginación */
.pagination-info {
    text-align: center;
    color: #666;
    font-size: 14px;
    margin-bottom: 30px;
    padding: 12px 20px;
    background: #f8f9fa;
    border-radius: 8px;
    display: inline-block;
    margin-left: 50%;
    transform: translateX(-50%);
}

.pagination-info strong {
    color: #667eea;
    font-weight: 700;
}

/* ========== INFO CARDS ========== */
.info-card {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
    border-radius: 10px;
    padding: 15px 20px;
    margin-bottom: 20px;
    display: flex;
    align-items: start;
    gap: 15px;
}

.info-card-icon {
    font-size: 24px;
    color: #2196f3;
    flex-shrink: 0;
}

.info-card-text {
    flex: 1;
}

.info-card-text strong {
    display: block;
    color: #1565c0;
    margin-bottom: 5px;
}

.info-card-text p {
    color: #555;
    margin: 0;
    line-height: 1.5;
}

/* ========== LEYENDA ========== */
.legend-box {
    background: white;
    border-radius: 10px;
    padding: 15px 20px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    box-shadow: 0 3px 10px rgba(0,0,0,0.06);
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #666;
}

.legend-item strong {
    color: #333;
}

/* ========== LOADING STATE ========== */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-spinner {
    width: 60px;
    height: 60px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* ========== BOTÓN COLAPSAR TODOS ========== */
.collapse-all-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    transition: all 0.3s ease;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
}

.collapse-all-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
}

.collapse-all-btn:active {
    transform: scale(0.95);
}

.collapse-all-btn i {
    transition: transform 0.3s ease;
}

.collapse-all-btn.all-collapsed i {
    transform: rotate(180deg);
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
    .seguimiento-header h1 {
        font-size: 1.4rem;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .seguimiento-card-content {
        grid-template-columns: 1fr;
    }
    
    .seguimiento-footer {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .stats-row {
        grid-template-columns: 1fr;
    }
    
    .collapse-all-btn {
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    /* Paginación responsive */
    .pagination-modern {
        gap: 5px;
    }
    
    .pagination-modern a,
    .pagination-modern span {
        min-width: 36px;
        height: 36px;
        padding: 0 8px;
        font-size: 14px;
    }
    
    .pagination-info {
        font-size: 13px;
        padding: 10px 15px;
        margin-left: 0;
        transform: none;
        width: 100%;
        box-sizing: border-box;
    }
}

/* ========== EMPTY STATE ========== */
.empty-state {
    background: white;
    border-radius: 15px;
    padding: 60px 40px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.empty-state-icon {
    font-size: 80px;
    color: #e0e0e0;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #666;
    margin-bottom: 10px;
}

.empty-state p {
    color: #999;
    margin-bottom: 25px;
}
</style>

<?php include("includes/funciones-js.php"); ?>
</head>

<body>
    <div class="layout">
        <?php include("includes/encabezado.php"); ?>

        <div class="main-wrapper seguimiento-container">
            <div class="container-fluid">
                <!-- Header Principal -->
                <div class="seguimiento-header">
                    <h1>
                        <i class="fa-solid fa-comments"></i>
                        <?=$paginaActual['pag_nombre'].$nombreCliente?>
                    </h1>
                    <div class="breadcrumb-modern">
                        <a href="index.php"><i class="fa-solid fa-home"></i></a>
                        <i class="fa-solid fa-angle-right"></i>
                        <a href="clientes.php">Clientes</a>
                        <i class="fa-solid fa-angle-right"></i>
                        <span><?=$paginaActual['pag_nombre']?></span>
                    </div>
                </div>

                <?php include("includes/notificaciones.php");?>

                <!-- Botones de Acción -->
                <div class="filter-actions" style="margin-bottom: 20px;">
                    <a href="javascript:history.go(-1);" class="btn-modern btn-secondary-modern">
                        <i class="fa-solid fa-arrow-left"></i> Regresar
                    </a>
                    <?php if (Modulos::validarRol([13], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && (isset($_GET["cte"]) || isset($_GET["idTK"])) && (!isset($tiket['tik_estado']) || $tiket['tik_estado'] != 2)) {?>
                    <a href="clientes-seguimiento-agregar.php?idTK=<?= $_GET["idTK"]; ?>&cte=<?= $_GET["cte"]; ?>" class="btn-modern btn-success-modern">
                        <i class="fa-solid fa-plus"></i> Agregar Nuevo
                    </a>
                    <?php } ?>
                </div>

                <!-- Card Informativa -->
                <div class="info-card">
                    <div class="info-card-icon">
                        <i class="fa-solid fa-info-circle"></i>
                    </div>
                    <div class="info-card-text">
                        <strong>Información Importante</strong>
                        <p>Haga clic sobre el estado del seguimiento para cambiar su estado entre pendiente o completado.</p>
                    </div>
                </div>

                <!-- Leyenda -->
                <div class="legend-box">
                    <div class="legend-item">
                        <strong>DT:</strong> <span>Consiguió datos</span>
                    </div>
                    <div class="legend-item">
                        <strong>CZ:</strong> <span>Hubo cotización</span>
                    </div>
                    <div class="legend-item">
                        <strong>VT:</strong> <span>Hubo venta</span>
                    </div>
                </div>

                <?php
                // Construcción de filtros
                $filtro = "";
                $filtroUsuario = "";
                
                if(!empty($_GET["cte"]) || !empty($_GET["idTK"])){
                    $filtroUsuario = " AND (cseg_usuario_responsable='" . $_SESSION["id"] . "' OR cseg_usuario_encargado='" . $_SESSION["id"] . "')";
                }

                if (isset($_GET["busqueda"]) and $_GET["busqueda"] != "") {
                    $filtro .= " AND (cseg_id LIKE '%" . $_GET["busqueda"] . "%' OR cseg_observacion LIKE '%" . $_GET["busqueda"] . "%' OR cseg_asunto LIKE '%" . $_GET["busqueda"] . "%' OR cli_nombre LIKE '%" . $_GET["busqueda"] . "%')";
                }

                if ($_GET["estado"] == 1) {
                    $filtro .= ' AND cseg_realizado=1';
                    $filtroUsuario = '';
                }
                if ($_GET["estado"] == 2) {
                    $filtro .= ' AND cseg_realizado IS NULL';
                    $filtroUsuario = '';
                }
                if (is_numeric($_GET["idTK"])) {
                    $filtro .= " AND cseg_tiket='" . $_GET["idTK"] . "'";
                    $filtroUsuario = '';
                }
                if (is_numeric($_GET["cte"])) {
                    $filtro .= " AND cseg_cliente='" . $_GET["cte"] . "'";
                    $filtroUsuario = '';
                }
                if (is_numeric($_GET["a"])) {
                    $filtro .= " AND YEAR(cseg_fecha_reporte)='" . $_GET["a"] . "'";
                    $filtroUsuario = '';
                }
                if (is_numeric($_GET["m"])) {
                    $filtro .= " AND MONTH(cseg_fecha_reporte)='" . $_GET["m"] . "'";
                    $filtroUsuario = '';
                }
                if (isset($_GET["usuario_resp"]) and $_GET["usuario_resp"] != "") {
                    $filtro .= " AND cseg_usuario_responsable='" . $_GET["usuario_resp"] . "'";
                }
                if (isset($_GET["estado_seg"]) and $_GET["estado_seg"] != "") {
                    if($_GET["estado_seg"] == "1"){
                        $filtro .= " AND cseg_realizado=1";
                    }else{
                        $filtro .= " AND (cseg_realizado IS NULL OR cseg_realizado=0)";
                    }
                }
                if (isset($_GET["fecha_inicio"]) and $_GET["fecha_inicio"] != "") {
                    $filtro .= " AND cseg_fecha_contacto >= '" . $_GET["fecha_inicio"] . " 00:00:00'";
                }
                if (isset($_GET["fecha_fin"]) and $_GET["fecha_fin"] != "") {
                    $filtro .= " AND cseg_fecha_contacto <= '" . $_GET["fecha_fin"] . " 23:59:59'";
                }

                $orden = 'ORDER BY cseg_id DESC';
                if (isset($_GET["seg"]) and $_GET["seg"] != "" and is_numeric($_GET["seg"])) {
                    $orden = 'ORDER BY cseg_id=' . $_GET["seg"] . ' DESC, cseg_id DESC';
                }

                // Variable SQL para paginación (debe incluir JOIN con clientes por el filtro de búsqueda)
                $SQL = "SELECT cseg.cseg_id FROM cliente_seguimiento cseg
                    INNER JOIN clientes cli ON cli.cli_id=cseg.cseg_cliente
                    WHERE cseg.cseg_id=cseg.cseg_id $filtroUsuario $filtro
                ";

                // Consulta optimizada para contar registros
                $consultaTotal = mysqli_query($conexionBdPrincipal, $SQL);
                $totalRegistros = mysqli_num_rows($consultaTotal);

                // Estadísticas rápidas (incluyen JOIN con clientes por si hay búsqueda)
                $statsCompletados = mysqli_query($conexionBdPrincipal,"SELECT COUNT(*) as total FROM cliente_seguimiento cseg
                    INNER JOIN clientes cli ON cli.cli_id=cseg.cseg_cliente
                    WHERE cseg.cseg_realizado=1 AND cseg.cseg_id=cseg.cseg_id $filtroUsuario $filtro
                ");
                $totalCompletados = mysqli_fetch_array($statsCompletados, MYSQLI_BOTH)['total'];

                $statsPendientes = mysqli_query($conexionBdPrincipal,"SELECT COUNT(*) as total FROM cliente_seguimiento cseg
                    INNER JOIN clientes cli ON cli.cli_id=cseg.cseg_cliente
                    WHERE (cseg.cseg_realizado IS NULL OR cseg.cseg_realizado=0) AND cseg.cseg_id=cseg.cseg_id $filtroUsuario $filtro
                ");
                $totalPendientes = mysqli_fetch_array($statsPendientes, MYSQLI_BOTH)['total'];

                $statsVentas = mysqli_query($conexionBdPrincipal,"SELECT COUNT(*) as total FROM cliente_seguimiento cseg
                    INNER JOIN clientes cli ON cli.cli_id=cseg.cseg_cliente
                    WHERE cseg.cseg_vendio=1 AND cseg.cseg_id=cseg.cseg_id $filtroUsuario $filtro
                ");
                $totalVentas = mysqli_fetch_array($statsVentas, MYSQLI_BOTH)['total'];

                $statsCotizaciones = mysqli_query($conexionBdPrincipal,"SELECT COUNT(*) as total FROM cliente_seguimiento cseg
                    INNER JOIN clientes cli ON cli.cli_id=cseg.cseg_cliente
                    WHERE cseg.cseg_cotizo=1 AND cseg.cseg_id=cseg.cseg_id $filtroUsuario $filtro
                ");
                $totalCotizaciones = mysqli_fetch_array($statsCotizaciones, MYSQLI_BOTH)['total'];
                ?>

                <!-- Estadísticas Rápidas -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-card-icon blue">
                            <i class="fa-solid fa-list"></i>
                        </div>
                        <p class="stat-card-value"><?= $totalRegistros; ?></p>
                        <p class="stat-card-label">Total Seguimientos</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-icon green">
                            <i class="fa-solid fa-check-circle"></i>
                        </div>
                        <p class="stat-card-value"><?= $totalCompletados; ?></p>
                        <p class="stat-card-label">Completados</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-icon orange">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <p class="stat-card-value"><?= $totalPendientes; ?></p>
                        <p class="stat-card-label">Pendientes</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-icon red">
                            <i class="fa-solid fa-shopping-cart"></i>
                        </div>
                        <p class="stat-card-value"><?= $totalVentas; ?></p>
                        <p class="stat-card-label">Ventas</p>
                    </div>
                </div>

                <!-- Búsqueda Global -->
                <div class="search-box-modern">
                    <form method="GET" action="" id="searchForm">
                        <?php if(isset($_GET["cte"])) { ?><input type="hidden" name="cte" value="<?=$_GET["cte"];?>"><?php } ?>
                        <?php if(isset($_GET["idTK"])) { ?><input type="hidden" name="idTK" value="<?=$_GET["idTK"];?>"><?php } ?>
                        <?php if(isset($_GET["a"])) { ?><input type="hidden" name="a" value="<?=$_GET["a"];?>"><?php } ?>
                        <?php if(isset($_GET["m"])) { ?><input type="hidden" name="m" value="<?=$_GET["m"];?>"><?php } ?>
                        <?php if(isset($_GET["usuario_resp"])) { ?><input type="hidden" name="usuario_resp" value="<?=$_GET["usuario_resp"];?>"><?php } ?>
                        <?php if(isset($_GET["estado_seg"])) { ?><input type="hidden" name="estado_seg" value="<?=$_GET["estado_seg"];?>"><?php } ?>
                        <?php if(isset($_GET["fecha_inicio"])) { ?><input type="hidden" name="fecha_inicio" value="<?=$_GET["fecha_inicio"];?>"><?php } ?>
                        <?php if(isset($_GET["fecha_fin"])) { ?><input type="hidden" name="fecha_fin" value="<?=$_GET["fecha_fin"];?>"><?php } ?>
                        
                        <div class="search-input-wrapper" style="display: flex; gap: 10px; align-items: center;">
                            <input 
                                type="text" 
                                name="busqueda" 
                                class="search-input-modern" 
                                placeholder="Buscar por ID, cliente, asunto u observación..." 
                                value="<?= isset($_GET["busqueda"]) ? $_GET["busqueda"] : ""; ?>"
                                id="searchInput"
                                style="flex: 1;"
                            >
                            <button type="submit" class="btn-modern btn-primary-modern" style="white-space: nowrap;">
                                <i class="fa-solid fa-search"></i> Buscar
                            </button>
                            <?php if(isset($_GET["busqueda"]) && $_GET["busqueda"] != "") { ?>
                            <a href="?<?= http_build_query(array_diff_key($_GET, ['busqueda' => ''])) ?>" class="btn-modern btn-secondary-modern" style="white-space: nowrap;">
                                <i class="fa-solid fa-times"></i> Limpiar
                            </a>
                            <?php } ?>
                        </div>
                    </form>
                </div>

                <!-- Filtros Avanzados -->
                <div class="filters-card">
                    <h3>
                        <i class="fa-solid fa-filter"></i>
                        Filtros Avanzados
                    </h3>
                    <form method="GET" action="">
                        <?php if(isset($_GET["cte"])) { ?><input type="hidden" name="cte" value="<?=$_GET["cte"];?>"><?php } ?>
                        <?php if(isset($_GET["idTK"])) { ?><input type="hidden" name="idTK" value="<?=$_GET["idTK"];?>"><?php } ?>
                        <?php if(isset($_GET["a"])) { ?><input type="hidden" name="a" value="<?=$_GET["a"];?>"><?php } ?>
                        <?php if(isset($_GET["m"])) { ?><input type="hidden" name="m" value="<?=$_GET["m"];?>"><?php } ?>
                        <?php if(isset($_GET["busqueda"])) { ?><input type="hidden" name="busqueda" value="<?=$_GET["busqueda"];?>"><?php } ?>
                        
                        <div class="filter-row">
                            <div class="filter-group">
                                <label>
                                    <i class="fa-solid fa-user"></i>
                                    Usuario Responsable
                                    <?php if(isset($_GET["usuario_resp"]) && $_GET["usuario_resp"]!="") { ?>
                                        <button type="button" class="filter-clear-btn" onclick="clearFilter('usuario_resp')">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    <?php } ?>
                                </label>
                                <select name="usuario_resp">
                                    <option value="">Todos los usuarios</option>
                                    <?php
                                    $consultaUsuarios = mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id_empresa='".$idEmpresa."' ORDER BY usr_nombre");
                                    while($usuario = mysqli_fetch_array($consultaUsuarios, MYSQLI_BOTH)){
                                        $selected = (isset($_GET["usuario_resp"]) && $_GET["usuario_resp"]==$usuario['usr_id']) ? "selected" : "";
                                        echo '<option value="'.$usuario['usr_id'].'" '.$selected.'>'.$usuario['usr_nombre'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label>
                                    <i class="fa-solid fa-flag"></i>
                                    Estado del Seguimiento
                                    <?php if(isset($_GET["estado_seg"]) && $_GET["estado_seg"]!="") { ?>
                                        <button type="button" class="filter-clear-btn" onclick="clearFilter('estado_seg')">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    <?php } ?>
                                </label>
                                <select name="estado_seg">
                                    <option value="">Todos los estados</option>
                                    <option value="1" <?= (isset($_GET["estado_seg"]) && $_GET["estado_seg"]=="1") ? "selected" : ""; ?>>Completado</option>
                                    <option value="0" <?= (isset($_GET["estado_seg"]) && $_GET["estado_seg"]=="0") ? "selected" : ""; ?>>Pendiente</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label>
                                    <i class="fa-solid fa-calendar"></i>
                                    Fecha Inicio
                                    <?php if(isset($_GET["fecha_inicio"]) && $_GET["fecha_inicio"]!="") { ?>
                                        <button type="button" class="filter-clear-btn" onclick="clearFilter('fecha_inicio')">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    <?php } ?>
                                </label>
                                <input type="date" name="fecha_inicio" value="<?= isset($_GET["fecha_inicio"]) ? $_GET["fecha_inicio"] : ""; ?>">
                            </div>

                            <div class="filter-group">
                                <label>
                                    <i class="fa-solid fa-calendar-check"></i>
                                    Fecha Fin
                                    <?php if(isset($_GET["fecha_fin"]) && $_GET["fecha_fin"]!="") { ?>
                                        <button type="button" class="filter-clear-btn" onclick="clearFilter('fecha_fin')">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    <?php } ?>
                                </label>
                                <input type="date" name="fecha_fin" value="<?= isset($_GET["fecha_fin"]) ? $_GET["fecha_fin"] : ""; ?>">
                            </div>
                        </div>

                        <div class="filter-actions">
                            <button type="submit" class="btn-modern btn-primary-modern">
                                <i class="fa-solid fa-filter"></i>
                                Aplicar Filtros
                            </button>
                            <a href="?<?= http_build_query(array_intersect_key($_GET, array_flip(['cte','idTK','a','m']))) ?>" class="btn-modern btn-secondary-modern">
                                <i class="fa-solid fa-times"></i>
                                Limpiar Filtros
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Grid de Seguimientos -->
                <div class="seguimientos-grid" id="seguimientosContainer">
                    <?php
                    // Paginación personalizada
                    $numTotal = $totalRegistros;
                    $limite = $configuracion['conf_paginacion'];
                    
                    if(!isset($_GET["pagina"]) or !is_numeric($_GET["pagina"]))
                        $pagina = 1;
                    else
                        $pagina = $_GET["pagina"];
                    
                    $inicio = ($pagina - 1) * $limite;
                    
                    if($limite > 0) {
                        $numPaginas = ceil($numTotal/$limite);
                    } else {
                        $numPaginas = 1;
                    }
                    
                    $paginaAnterior = $pagina - 1;
                    $paginaSiguiente = $pagina + 1;

                    // Consulta principal optimizada
                    $consulta = mysqli_query($conexionBdPrincipal,"SELECT 
                        cseg.*,
                        cli.cli_nombre, cli.cli_telefono, cli.cli_celular, cli.cli_email, cli.cli_zona,
                        ciu.ciu_nombre,
                        usr.usr_nombre as responsable_nombre,
                        tik.tik_id, tik.tik_asunto_principal, tik.tik_estado
                        FROM cliente_seguimiento cseg
                        INNER JOIN clientes cli ON cli.cli_id=cseg.cseg_cliente
                        INNER JOIN ".BDADMIN.".localidad_ciudades ciu ON ciu.ciu_id=cli.cli_ciudad
                        INNER JOIN usuarios usr ON usr.usr_id=cseg.cseg_usuario_responsable
                        LEFT JOIN clientes_tikets tik ON tik.tik_id=cseg.cseg_tiket
                        WHERE cseg.cseg_id=cseg.cseg_id $filtroUsuario $filtro
                        $orden
                        LIMIT $inicio, $limite
                    ");

                    $no = 1;
                    $hayResultados = false;

                    while ($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
                        $hayResultados = true;

                        // Obtener encargado
                        $consultaEncargado=mysqli_query($conexionBdPrincipal,"SELECT usr_nombre FROM usuarios WHERE usr_id='" . $res['cseg_usuario_encargado'] . "'");
                        $encargado = mysqli_fetch_array($consultaEncargado, MYSQLI_BOTH);

                        // Obtener contacto
                        $consultaContacto=mysqli_query($conexionBdPrincipal,"SELECT cont.*, sucu.sucu_nombre 
                            FROM contactos cont
                            LEFT JOIN sucursales sucu ON sucu.sucu_id=cont.cont_sucursal
                            WHERE cont.cont_id='" . $res['cseg_contacto'] . "'");
                        $contacto = mysqli_fetch_array($consultaContacto, MYSQLI_BOTH);

                        // Validar zonas si es necesario
                        if (!Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
                            $consultaNumZonas=mysqli_query($conexionBdPrincipal,"SELECT * FROM zonas_usuarios WHERE zpu_usuario='" . $_SESSION["id"] . "' AND zpu_zona='" . $res['cli_zona'] . "'");
                            $numZ = mysqli_num_rows($consultaNumZonas);
                            if ($numZ == 0) continue;
                        }

                        // Determinar si es destacado
                        $destacado = ($res['cseg_id'] == $_GET['seg']) ? 'destacado' : '';

                        // Estado
                        $estadoHtml = '';
                        $estadoClass = '';
                        $estadoBadgeHeader = '';
                        if($res['cseg_realizado'] == 1){
                            $estadoHtml = '<span class="status-badge completado"><i class="fa-solid fa-check-circle"></i> Completado</span>';
                            $estadoClass = 'estado-completado';
                            $estadoBadgeHeader = '<span class="status-badge-header completado-header"><i class="fa-solid fa-check-circle"></i> Completado</span>';
                        } else {
                            $estadoHtml = '<a href="bd_update/cliente-seguimiento-estado-update.php?id=' . $res['cseg_id'] . '&get=28" class="status-badge pendiente"><i class="fa-solid fa-clock"></i> Pendiente</a>';
                            $estadoClass = 'estado-pendiente';
                            $estadoBadgeHeader = '<span class="status-badge-header pendiente-header"><i class="fa-solid fa-clock"></i> Pendiente</span>';
                        }
                    ?>
                        <div class="seguimiento-card <?= $destacado; ?> <?= $estadoClass; ?>" data-card-id="<?= $res['cseg_id']; ?>">
                            <div class="seguimiento-card-header" onclick="toggleCard(<?= $res['cseg_id']; ?>)">
                                <div>
                                    <h3 class="seguimiento-card-title">
                                        <i class="fa-solid fa-chevron-down collapse-icon"></i>
                                        <i class="fa-solid fa-ticket"></i>
                                        Ticket #<?= $res['tik_id']; ?> - <?= $res['tik_asunto_principal']; ?>
                                    </h3>
                                    <p class="seguimiento-card-subtitle">
                                        <span>
                                            <i class="fa-solid fa-hashtag"></i> Seguimiento #<?= $res['cseg_id']; ?>
                                        </span>
                                        <?= $estadoBadgeHeader; ?>
                                    </p>
                                </div>
                                <div class="seguimiento-actions" onclick="event.stopPropagation()">
                                    <?php if (Modulos::validarRol([13], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) && $res['tik_estado'] != 2) {?>
                                    <button class="action-btn add" data-toggle="tooltip" title="Nuevo Seguimiento" onclick="window.location.href='clientes-seguimiento-agregar.php?idTK=<?= $res['cseg_tiket']; ?>'">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                    <?php } ?>
                                    <?php if (Modulos::validarRol([14], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
                                    <button class="action-btn edit" data-toggle="tooltip" title="Editar" onclick="window.location.href='clientes-seguimiento-editar.php?id=<?= $res['cseg_id']; ?>&idTK=<?= $res['cseg_tiket']; ?>'">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="seguimiento-card-body">
                            <div class="seguimiento-card-content">
                                <!-- Información del Cliente -->
                                <div class="info-block">
                                    <h4><i class="fa-solid fa-building"></i> Cliente</h4>
                                    <div class="info-item">
                                        <strong>Nombre:</strong>
                                        <span><?= $res['cli_nombre']; ?></span>
                                    </div>
                                    <?php if($res['cli_telefono']){ ?>
                                    <div class="info-item">
                                        <strong>Teléfono:</strong>
                                        <span><?= $res['cli_telefono']; ?></span>
                                    </div>
                                    <?php } ?>
                                    <?php if($res['cli_celular']){ ?>
                                    <div class="info-item">
                                        <strong>Celular:</strong>
                                        <span><?= $res['cli_celular']; ?></span>
                                    </div>
                                    <?php } ?>
                                    <?php if($res['cli_email']){ ?>
                                    <div class="info-item">
                                        <strong>Email:</strong>
                                        <span><?= $res['cli_email']; ?></span>
                                    </div>
                                    <?php } ?>
                                    <div class="info-item">
                                        <strong>Ciudad:</strong>
                                        <span><?= $res['ciu_nombre']; ?></span>
                                    </div>
                                </div>

                                <!-- Información del Contacto -->
                                <?php if($contacto){ ?>
                                <div class="info-block">
                                    <h4><i class="fa-solid fa-user"></i> Contacto</h4>
                                    <div class="info-item">
                                        <strong>Nombre:</strong>
                                        <span><?= $contacto['cont_nombre']; ?></span>
                                    </div>
                                    <?php if($contacto['cont_telefono']){ ?>
                                    <div class="info-item">
                                        <strong>Teléfono:</strong>
                                        <span><?= $contacto['cont_telefono']; ?></span>
                                    </div>
                                    <?php } ?>
                                    <?php if($contacto['cont_email']){ ?>
                                    <div class="info-item">
                                        <strong>Email:</strong>
                                        <span><?= $contacto['cont_email']; ?></span>
                                    </div>
                                    <?php } ?>
                                    <?php if($contacto['sucu_nombre']){ ?>
                                    <div class="info-item">
                                        <strong>Sucursal:</strong>
                                        <span><?= $contacto['sucu_nombre']; ?></span>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            </div>

                            <!-- Detalles de Gestión -->
                            <div class="seguimiento-detail">
                                <h5><i class="fa-solid fa-clipboard-check"></i> Gestión Actual</h5>
                                <div class="info-item" style="margin-bottom: 8px;">
                                    <strong>Fecha:</strong>
                                    <span><?= $res['cseg_fecha_contacto']; ?></span>
                                </div>
                                <div class="info-item" style="margin-bottom: 12px;">
                                    <strong>Responsable:</strong>
                                    <span><?= $res['responsable_nombre']; ?></span>
                                </div>
                                <?php if($res['cseg_observacion']){ ?>
                                <p><?= nl2br($res['cseg_observacion']); ?></p>
                                <?php } ?>
                            </div>

                            <!-- Próximo Contacto -->
                            <?php if($res['cseg_fecha_proximo_contacto'] != '0000-00-00' && $res['cseg_fecha_proximo_contacto']){ ?>
                            <div class="seguimiento-detail" style="background: #fff3e0; border-left-color: #ff9800;">
                                <h5 style="color: #e65100;"><i class="fa-solid fa-calendar-alt"></i> Próximo Contacto</h5>
                                <div class="info-item" style="margin-bottom: 8px;">
                                    <strong>Fecha:</strong>
                                    <span><?= $res['cseg_fecha_proximo_contacto']; ?></span>
                                </div>
                                <?php if($encargado){ ?>
                                <div class="info-item" style="margin-bottom: 12px;">
                                    <strong>Encargado:</strong>
                                    <span><?= $encargado['usr_nombre']; ?></span>
                                </div>
                                <?php } ?>
                                <?php if($res['cseg_asunto']){ ?>
                                <p><?= nl2br($res['cseg_asunto']); ?></p>
                                <?php } ?>
                            </div>
                            <?php } ?>

                            <!-- Footer con Badges -->
                            <div class="seguimiento-footer">
                                <div class="seguimiento-badges">
                                    <?php if($res['cseg_consiguio_datos'] == 1){ ?>
                                    <span class="badge-modern success">
                                        <i class="fa-solid fa-database"></i> Datos
                                    </span>
                                    <?php } ?>
                                    <?php if($res['cseg_cotizo'] == 1){ ?>
                                    <span class="badge-modern info">
                                        <i class="fa-solid fa-file-invoice"></i> Cotización
                                    </span>
                                    <?php } ?>
                                    <?php if($res['cseg_vendio'] == 1){ ?>
                                    <span class="badge-modern success">
                                        <i class="fa-solid fa-shopping-cart"></i> Venta
                                    </span>
                                    <?php } ?>
                                </div>
                                <div>
                                    <?= $estadoHtml; ?>
                                </div>
                            </div>
                            </div>
                        </div>
                    <?php 
                        $no++;
                    } 
                    
                    if(!$hayResultados){
                    ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fa-solid fa-inbox"></i>
                            </div>
                            <h3>No se encontraron seguimientos</h3>
                            <p>No hay seguimientos que coincidan con los filtros aplicados.</p>
                            <a href="?<?= http_build_query(array_intersect_key($_GET, array_flip(['cte','idTK']))) ?>" class="btn-modern btn-primary-modern">
                                <i class="fa-solid fa-refresh"></i>
                                Ver Todos
                            </a>
                        </div>
                    <?php } ?>
                </div>

                <!-- Paginación -->
                <?php if($hayResultados && $numPaginas > 1){ ?>
                <div class="pagination-modern">
                    <?php 
                    // Generar links de paginación modernos con lógica inteligente
                    $queryParams = $_GET;
                    $rango = 2; // Número de páginas a mostrar antes y después de la actual
                    
                    // Botón Anterior
                    if($paginaAnterior > 0){
                        $queryParams['pagina'] = $paginaAnterior;
                        echo '<a href="?'.http_build_query($queryParams).'" class="pagination-prev" title="Página anterior"><i class="fa-solid fa-chevron-left"></i></a>';
                    } else {
                        echo '<span class="pagination-prev disabled"><i class="fa-solid fa-chevron-left"></i></span>';
                    }
                    
                    // Primera página siempre visible
                    $queryParams['pagina'] = 1;
                    if($pagina == 1){
                        echo '<span class="current">1</span>';
                    } else {
                        echo '<a href="?'.http_build_query($queryParams).'">1</a>';
                    }
                    
                    // Puntos suspensivos si hay un salto
                    if($pagina > ($rango + 2)){
                        echo '<span class="pagination-dots">...</span>';
                    }
                    
                    // Páginas alrededor de la actual
                    $inicio = max(2, $pagina - $rango);
                    $fin = min($numPaginas - 1, $pagina + $rango);
                    
                    for($i = $inicio; $i <= $fin; $i++){
                        $queryParams['pagina'] = $i;
                        if($i == $pagina){
                            echo '<span class="current">'.$i.'</span>';
                        } else {
                            echo '<a href="?'.http_build_query($queryParams).'">'.$i.'</a>';
                        }
                    }
                    
                    // Puntos suspensivos si hay un salto
                    if($pagina < ($numPaginas - $rango - 1)){
                        echo '<span class="pagination-dots">...</span>';
                    }
                    
                    // Última página siempre visible (si hay más de 1 página)
                    if($numPaginas > 1){
                        $queryParams['pagina'] = $numPaginas;
                        if($pagina == $numPaginas){
                            echo '<span class="current">'.$numPaginas.'</span>';
                        } else {
                            echo '<a href="?'.http_build_query($queryParams).'">'.$numPaginas.'</a>';
                        }
                    }
                    
                    // Botón Siguiente
                    if($paginaSiguiente <= $numPaginas){
                        $queryParams['pagina'] = $paginaSiguiente;
                        echo '<a href="?'.http_build_query($queryParams).'" class="pagination-next" title="Página siguiente"><i class="fa-solid fa-chevron-right"></i></a>';
                    } else {
                        echo '<span class="pagination-next disabled"><i class="fa-solid fa-chevron-right"></i></span>';
                    }
                    ?>
                </div>
                
                <!-- Información de paginación -->
                <div class="pagination-info">
                    Mostrando página <strong><?= $pagina; ?></strong> de <strong><?= $numPaginas; ?></strong> 
                    (<?= $totalRegistros; ?> <?= $totalRegistros == 1 ? 'registro' : 'registros'; ?> en total)
                </div>
                <?php } ?>

            </div>
        </div>

        <?php include("includes/pie.php"); ?>
    </div>

    <!-- Botón flotante para colapsar/expandir todos -->
    <button class="collapse-all-btn" id="collapseAllBtn" onclick="toggleAllCards()" data-toggle="tooltip" title="Colapsar todos">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    <!-- JavaScript para funcionalidad moderna -->
    <script>
    $(document).ready(function() {
        // Inicializar tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Forzar la visualización correcta de los selects con valores preseleccionados
        $('select[name="usuario_resp"], select[name="estado_seg"]').each(function() {
            let $select = $(this);
            let currentValue = $select.val();
            let selectName = $select.attr('name');
            
            // Debug: Mostrar qué valor tiene cada select
            console.log('Select ' + selectName + ' tiene valor:', currentValue);
            
            // Si tiene un valor, asegurarse de que esté seleccionado
            if(currentValue && currentValue !== '') {
                // Forzar el valor nuevamente
                $select.val(currentValue);
                
                // Agregar clase visual
                $select.addClass('has-value');
                
                // Log de confirmación
                console.log('Select ' + selectName + ' configurado con valor:', $select.val());
            }
        });

        // Animación suave al cargar
        $('.seguimiento-card').each(function(index) {
            $(this).css({
                'opacity': '0',
                'transform': 'translateY(20px)'
            }).delay(index * 50).animate({
                'opacity': '1'
            }, 300).css({
                'transform': 'translateY(0)'
            });
        });

        // Mejorar feedback visual en filtros con clases
        function updateFieldStyle(field) {
            let $field = $(field);
            if($field.val() && $field.val() !== '') {
                $field.addClass('has-value');
            } else {
                $field.removeClass('has-value');
            }
        }

        // Aplicar al cambiar
        $('select, input[type="date"]').on('change', function() {
            updateFieldStyle(this);
        });

        // Aplicar estilos iniciales a campos con valor
        $('select, input[type="date"]').each(function() {
            updateFieldStyle(this);
        });
    });

    // Función para limpiar filtros individuales
    function clearFilter(filterName) {
        let url = new URL(window.location.href);
        url.searchParams.delete(filterName);
        window.location.href = url.toString();
    }

    // Confirmación antes de cambiar estado
    $('.status-badge.pendiente').on('click', function(e) {
        if(!confirm('¿Desea marcar este seguimiento como completado?')) {
            e.preventDefault();
            return false;
        }
    });

    // ========== FUNCIONES PARA COLAPSAR CARDS ==========
    
    // Colapsar/expandir un card individual
    window.toggleCard = function(cardId) {
        const card = $('[data-card-id="' + cardId + '"]');
        card.toggleClass('collapsed');
        
        // Guardar estado en localStorage
        const collapsedCards = getCollapsedCards();
        if(card.hasClass('collapsed')) {
            collapsedCards.push(cardId);
        } else {
            const index = collapsedCards.indexOf(cardId);
            if(index > -1) {
                collapsedCards.splice(index, 1);
            }
        }
        localStorage.setItem('collapsedCards', JSON.stringify(collapsedCards));
    };

    // Colapsar/expandir todos los cards
    window.toggleAllCards = function() {
        const cards = $('.seguimiento-card');
        const btn = $('#collapseAllBtn');
        const allCollapsed = cards.first().hasClass('collapsed');
        
        if(allCollapsed) {
            // Expandir todos
            cards.removeClass('collapsed');
            btn.removeClass('all-collapsed');
            btn.attr('title', 'Colapsar todos');
            btn.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            localStorage.setItem('collapsedCards', JSON.stringify([]));
        } else {
            // Colapsar todos
            cards.addClass('collapsed');
            btn.addClass('all-collapsed');
            btn.attr('title', 'Expandir todos');
            btn.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            
            // Guardar todos los IDs
            const allIds = [];
            cards.each(function() {
                allIds.push($(this).data('card-id'));
            });
            localStorage.setItem('collapsedCards', JSON.stringify(allIds));
        }
        
        // Actualizar tooltip
        $('[data-toggle="tooltip"]').tooltip('destroy');
        $('[data-toggle="tooltip"]').tooltip();
    };

    // Obtener cards colapsados del localStorage
    function getCollapsedCards() {
        const stored = localStorage.getItem('collapsedCards');
        return stored ? JSON.parse(stored) : [];
    }

    // Restaurar estado de cards colapsados al cargar la página
    function restoreCollapsedState() {
        const collapsedCards = getCollapsedCards();
        collapsedCards.forEach(function(cardId) {
            $('[data-card-id="' + cardId + '"]').addClass('collapsed');
        });
        
        // Actualizar botón si todos están colapsados
        const totalCards = $('.seguimiento-card').length;
        if(collapsedCards.length === totalCards && totalCards > 0) {
            const btn = $('#collapseAllBtn');
            btn.addClass('all-collapsed');
            btn.attr('title', 'Expandir todos');
            btn.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        }
    }

    // Restaurar estado al cargar
    restoreCollapsedState();
    </script>
</body>
</html>
