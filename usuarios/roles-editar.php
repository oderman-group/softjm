<?php include("sesion.php");

$idPagina = 7;
include("includes/verificar-paginas.php");
include("includes/head.php");

$consulta=$conexionBdPrincipal->query("SELECT * FROM usuarios_tipos WHERE utipo_id='".$_GET["id"]."' AND utipo_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'");
$resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);
if(empty($resultadoD)) {
	echo '<script type="text/javascript">window.location.href="index.php?error=Unauthorized";</script>';
	exit();
}
?>
<!-- styles -->
<link href="css/tablecloth.css" rel="stylesheet">
<!-- Font Awesome para iconos modernos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-fileupload.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/jquery.tagsinput.js"></script>
<script src="js/chosen.jquery.js"></script>
<script src="js/bootstrap-colorpicker.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script src="js/date.js"></script>
<script src="js/daterangepicker.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>
<script src="js/jquery.tablecloth.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/dataTables.bootstrap.js"></script>
<script src="js/TableTools.js"></script>
<?php 
//Son todas las funciones javascript para que los campos del formulario funcionen bien.
include("includes/js-formularios.php");
?>

<?php include("includes/funciones-js.php");?>

<style>
/* ========== ESTILOS MODERNOS PARA ROLES-EDITAR ========== */
.roles-modern-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 20px 0;
}

.roles-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.roles-header h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 600;
}

.role-name-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 25px;
    transition: all 0.3s ease;
}

.role-name-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.role-name-card label {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    display: block;
}

.role-name-card input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s ease;
}

.role-name-card input:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Buscador General - FORZAR ESTILOS */
.search-container {
    background: white !important;
    padding: 40px !important;
    border-radius: 20px !important;
    box-shadow: 0 6px 30px rgba(0,0,0,0.1) !important;
    margin-bottom: 40px !important;
    border: 1px solid #f0f0f0 !important;
    width: 100% !important;
    max-width: 100% !important;
}

.search-container h4 {
    margin: 0 0 30px 0 !important;
    color: #333 !important;
    font-size: 24px !important;
    font-weight: 700 !important;
    display: flex !important;
    align-items: center !important;
    gap: 15px !important;
    text-align: center !important;
    justify-content: center !important;
}

.search-wrapper {
    position: relative !important;
    width: 100% !important;
    max-width: 800px !important;
    margin: 0 auto !important;
    display: block !important;
}

.search-input {
    width: 100% !important;
    padding: 25px 80px 25px 80px !important;
    border: 4px solid #e0e0e0 !important;
    border-radius: 60px !important;
    font-size: 18px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
    box-sizing: border-box !important;
    background: #fafafa !important;
    color: #333 !important;
    display: block !important;
    margin: 0 !important;
}

.search-input:focus {
    border-color: #667eea !important;
    outline: none !important;
    box-shadow: 0 0 0 5px rgba(102, 126, 234, 0.15) !important;
    background: white !important;
    transform: scale(1.02) !important;
}

.search-input::placeholder {
    color: #999 !important;
    font-weight: 400 !important;
}

.search-icon {
    position: absolute !important;
    left: 30px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    color: #667eea !important;
    font-size: 24px !important;
    pointer-events: none !important;
    z-index: 2 !important;
}

.clear-search {
    position: absolute !important;
    right: 30px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%) !important;
    color: white !important;
    border: none !important;
    border-radius: 50% !important;
    width: 45px !important;
    height: 45px !important;
    cursor: pointer !important;
    display: none !important;
    transition: all 0.3s ease !important;
    font-size: 18px !important;
    box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3) !important;
}

.clear-search:hover {
    background: #d32f2f;
    transform: translateY(-50%) scale(1.1);
}

.clear-search.active {
    display: block;
}

.search-stats {
    margin-top: 15px;
    padding: 10px 15px;
    background: #e3f2fd;
    border-radius: 8px;
    color: #1976d2;
    font-size: 14px;
    display: none;
}

.search-stats.active {
    display: block;
}

/* Tabs de Módulos */
.modules-tabs {
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 25px;
}

.nav-tabs-modern {
    border: none;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.nav-tabs-modern > li {
    margin: 0;
    flex: 0 1 auto;
}

.nav-tabs-modern > li > a {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 12px 20px;
    color: #666;
    font-weight: 500;
    transition: all 0.3s ease;
    background: #fafafa;
}

.nav-tabs-modern > li > a:hover {
    background: #f0f0f0;
    border-color: #667eea;
    color: #667eea;
}

.nav-tabs-modern > li.active > a,
.nav-tabs-modern > li.active > a:hover,
.nav-tabs-modern > li.active > a:focus {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
}

/* Tabla de Páginas Mejorada */
.pages-table-container {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 25px;
}

.table-modern {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.table-modern thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.table-modern thead th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table-modern thead th:first-child {
    border-top-left-radius: 8px;
}

.table-modern thead th:last-child {
    border-top-right-radius: 8px;
}

.table-modern tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.table-modern tbody tr:hover {
    background: #f8f9ff;
    transform: scale(1.01);
}

.table-modern tbody td {
    padding: 15px;
    vertical-align: middle;
}

.module-badge {
    display: inline-block;
    padding: 5px 12px;
    background: #e3f2fd;
    color: #1976d2;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.page-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.page-name {
    font-weight: 600;
    color: #333;
    font-size: 15px;
}

.page-description {
    font-size: 13px;
    color: #666;
    line-height: 1.5;
    margin-top: 5px;
    display: block;
}

.page-description i {
    color: #667eea;
    margin-right: 5px;
}

.page-route {
    font-size: 12px;
    color: #999;
    font-style: italic;
    margin-top: 3px;
    display: block;
}

.page-route i {
    margin-right: 5px;
}

/* Dropdown cambiar módulo por página */
.page-module-select {
    font-size: 12px;
    padding: 5px 8px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    min-width: 120px;
    max-width: 100%;
}
.page-module-select:disabled {
    opacity: 0.7;
    cursor: wait;
}

/* Checkbox personalizado */
.custom-checkbox {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 26px;
}

.custom-checkbox input {
    opacity: 0;
    width: 0;
    height: 0;
}

.checkbox-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.4s;
    border-radius: 34px;
}

.checkbox-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

.custom-checkbox input:checked + .checkbox-slider {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.custom-checkbox input:checked + .checkbox-slider:before {
    transform: translateX(24px);
}

/* Contador de permisos - fijo arriba a la derecha para no tapar los botones inferiores */
.permissions-counter {
    position: fixed;
    top: 140px;
    right: 30px;
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    z-index: 1000;
    min-width: 200px;
}

.permissions-counter h4 {
    margin: 0 0 10px 0;
    font-size: 16px;
    color: #333;
}

.counter-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.counter-item:last-child {
    border-bottom: none;
}

.counter-label {
    color: #666;
    font-size: 14px;
}

.counter-value {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 16px;
}

/* Botones de acción */
.action-buttons {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    position: sticky;
    bottom: 20px;
    z-index: 999;
}

.btn-modern {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-modern-primary {
    background: #6c757d;
    color: white;
}

.btn-modern-primary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-modern-success {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-modern-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

/* Vista de Módulos Expandible */
.module-view {
    background: white;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    overflow: hidden;
}

.module-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.module-header:hover {
    opacity: 0.9;
}

.module-title {
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.module-stats {
    display: flex;
    gap: 20px;
    font-size: 14px;
}

.module-content {
    padding: 20px;
    display: none;
}

.module-content.active {
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .nav-tabs-modern {
        flex-direction: column;
    }
    
    .permissions-counter {
        top: 120px;
        right: 15px;
        min-width: 150px;
        padding: 15px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-modern {
        width: 100%;
        justify-content: center;
    }
}

/* Animaciones */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.5s ease;
}

/* Loading Spinner */
.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255,255,255,.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Tooltips personalizados */
.tooltip-custom {
    position: relative;
    display: inline-block;
}

.tooltip-custom .tooltiptext {
    visibility: hidden;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 8px 12px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 12px;
    white-space: nowrap;
}

.tooltip-custom:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}

/* Badge de "Nuevo" */
.new-badge {
    background: #f44336;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    margin-left: 8px;
}

/* Sección de Usuarios del Rol */
.users-section {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 25px;
}

.users-section h4 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.users-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.user-card {
    background: #f8f9ff;
    padding: 15px;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.user-card:hover {
    border-color: #667eea;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.2);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 16px;
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.user-email {
    font-size: 12px;
    color: #999;
}

.btn-remove-user {
    background: #f44336;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
}

.btn-remove-user:hover {
    background: #d32f2f;
    transform: scale(1.1);
}

.add-user-section {
    background: #f0f0f0;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
}

.add-user-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    align-items: stretch;
}

.auto-add-info {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    background: #e3f2fd;
    border-radius: 8px;
    color: #1976d2;
    font-size: 14px;
    font-weight: 500;
    border-left: 4px solid #2196f3;
}

.auto-add-info i {
    font-size: 16px;
}

.user-select {
    flex: 1 !important;
    padding: 15px 20px !important;
    border: 3px solid #e0e0e0 !important;
    border-radius: 12px !important;
    font-size: 16px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
    background: #fafafa !important;
    color: #333 !important;
    cursor: pointer !important;
    appearance: none !important;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23667eea' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 15px center !important;
    background-size: 20px !important;
    padding-right: 50px !important;
}

.user-select:focus {
    border-color: #667eea !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
    background-color: white !important;
    transform: scale(1.02) !important;
}

.user-select option {
    padding: 10px !important;
    background: white !important;
    color: #333 !important;
    font-weight: 500 !important;
}

.user-select option:checked {
    background: #667eea !important;
    color: white !important;
    font-weight: 700 !important;
}

.btn-add-user {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.btn-add-user:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-add-user:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Selector de Roles - FORZAR ESTILOS */
.role-switcher {
    background: white !important;
    padding: 25px !important;
    border-radius: 15px !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
    margin-bottom: 30px !important;
    border: 1px solid #f0f0f0 !important;
}

.role-switcher label {
    display: block !important;
    font-weight: 700 !important;
    color: #333 !important;
    margin-bottom: 15px !important;
    font-size: 16px !important;
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
}

.role-switcher select {
    width: 100% !important;
    padding: 15px 20px !important;
    border: 3px solid #e0e0e0 !important;
    border-radius: 12px !important;
    font-size: 16px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
    background: #fafafa !important;
    color: #333 !important;
    cursor: pointer !important;
    appearance: none !important;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23667eea' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 15px center !important;
    background-size: 20px !important;
    padding-right: 50px !important;
}

.role-switcher select:focus {
    border-color: #667eea !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
    background-color: white !important;
    transform: scale(1.02) !important;
}

.role-switcher select option {
    padding: 10px !important;
    background: white !important;
    color: #333 !important;
    font-weight: 500 !important;
}

.role-switcher select option:checked {
    background: #667eea !important;
    color: white !important;
    font-weight: 700 !important;
}

.empty-state {
    text-align: center;
    padding: 30px;
    color: #999;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

/* Esquema jerárquico de permisos */
.permisos-esquema {
    background: linear-gradient(135deg, #f0f4ff 0%, #e8eeff 100%);
    border: 1px solid #c5d4f7;
    border-radius: 12px;
    padding: 20px 24px;
    margin-bottom: 25px;
}
.permisos-esquema .esquema-titulo {
    font-size: 15px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.permisos-esquema .esquema-titulo i { color: #667eea; }
.permisos-esquema .esquema-diagrama {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px 4px;
    font-size: 13px;
}
.permisos-esquema .esquema-nodo {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #fff;
    border-radius: 8px;
    border: 1px solid #c5d4f7;
    color: #334155;
    font-weight: 500;
}
.permisos-esquema .esquema-nodo i { color: #667eea; font-size: 14px; }
.permisos-esquema .esquema-flecha {
    color: #94a3b8;
    font-size: 12px;
}
.permisos-esquema .esquema-leyenda {
    margin-top: 12px;
    font-size: 12px;
    color: #64748b;
    line-height: 1.5;
}

/* Loading spinner para acciones */
.btn-loading {
    position: relative;
    pointer-events: none;
    opacity: 0.7;
}

.btn-loading::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 0.6s linear infinite;
}
</style>

</head>
<body>
<div class="layout">
	<?php include("includes/encabezado.php");?>
    
	<div class="main-wrapper roles-modern-container">
		<div class="container-fluid">
			<!-- Encabezado mejorado -->
			<div class="roles-header">
				<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
					<div>
						<h3><i class="fa-solid fa-user-shield"></i> <?=$paginaActual['pag_nombre'];?></h3>
						<p style="margin: 10px 0 0 0; opacity: 0.9;">Gestiona los permisos y accesos del rol: <strong><?=$resultadoD['utipo_nombre'];?></strong></p>
					</div>
					<?php
						if (Modulos::validarRol([6], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
							echo '<a href="roles-agregar.php" class="btn-modern btn-modern-success" style="margin-top: 10px;"><i class="fa-solid fa-plus"></i> Agregar nuevo rol</a>';
						}
					?>	
				</div>
			</div>
            
            <?php include("includes/notificaciones.php");?>
            
            <form class="form-horizontal" method="post" action="bd_update/actualizar-roles.php" id="formRoles">
				<input type="hidden" name="id" value="<?=$_GET["id"];?>">
				
				<!-- Esquema jerárquico: cómo funcionan los permisos -->
				<div class="permisos-esquema fade-in">
					<div class="esquema-titulo"><i class="fa-solid fa-sitemap"></i> Estructura de permisos</div>
					<div class="esquema-diagrama">
						<span class="esquema-nodo"><i class="fa-solid fa-building"></i> Empresa</span>
						<span class="esquema-flecha">→</span>
						<span class="esquema-nodo"><i class="fa-solid fa-user-shield"></i> Roles</span>
						<span class="esquema-flecha">→</span>
						<span class="esquema-nodo"><i class="fa-solid fa-folder"></i> Módulos</span>
						<span class="esquema-flecha">→</span>
						<span class="esquema-nodo"><i class="fa-solid fa-file-lines"></i> Páginas</span>
						<span class="esquema-flecha" style="margin-left:8px;">|</span>
						<span class="esquema-nodo"><i class="fa-solid fa-users"></i> Usuarios</span>
						<span style="color:#94a3b8;font-size:12px;margin-left:4px;">(asignados al rol)</span>
					</div>
					<div class="esquema-leyenda">
						<strong>Resumen:</strong> Cada <strong>rol</strong> agrupa permisos por <strong>páginas</strong> (organizadas en <strong>módulos</strong>). Los <strong>usuarios</strong> se asignan a un rol y heredan acceso solo a las páginas que el rol tiene marcadas. Para dar acceso a una pantalla concreta, marque el permiso de esa página en la lista inferior.
					</div>
				</div>
				
				<!-- Card del nombre del rol -->
				<div class="role-name-card fade-in">
					<label><i class="fa-solid fa-tag"></i> Nombre del Rol</label>
					<input type="text" name="nombre" id="nombre" value="<?=$resultadoD['utipo_nombre'];?>" placeholder="Ej: Administrador, Vendedor, Supervisor..." required>
				</div>
				
				<!-- Selector rápido de roles -->
				<div class="role-switcher fade-in">
					<label><i class="fa-solid fa-exchange-alt"></i> Cambiar a otro rol</label>
					<select id="roleSwitcher" onchange="cambiarRol(this.value)">
						<option value="">Selecciona un rol para editar...</option>
						<?php
						$consultaRoles = $conexionBdPrincipal->query("SELECT * FROM usuarios_tipos WHERE utipo_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' ORDER BY utipo_nombre ASC");
						while($rol = mysqli_fetch_array($consultaRoles, MYSQLI_BOTH)){
							$selected = ($rol['utipo_id'] == $_GET["id"]) ? 'selected' : '';
							echo '<option value="'.$rol['utipo_id'].'" '.$selected.'>'.$rol['utipo_nombre'].'</option>';
						}
						?>
					</select>
				</div>
				
				<!-- Sección de usuarios con este rol -->
				<div class="users-section fade-in">
					<h4>
						<i class="fa-solid fa-users"></i> 
						Usuarios con este rol 
						<span id="usersCount" style="background: #667eea; color: white; padding: 4px 12px; border-radius: 20px; font-size: 14px; margin-left: 10px;">0</span>
					</h4>
					
					<div id="usersGrid" class="users-grid">
						<!-- Los usuarios se cargarán dinámicamente aquí -->
					</div>
					
					<div class="add-user-section">
						<div class="add-user-form">
							<select id="userToAdd" class="user-select">
								<option value="">Selecciona un usuario para agregar automáticamente...</option>
								<?php
								// Obtener todos los usuarios activos (ya filtrados por empresa en sesión)
								$consultaUsuarios = $conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_bloqueado!=1 AND usr_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' ORDER BY usr_nombre ASC");
								if($consultaUsuarios && mysqli_num_rows($consultaUsuarios) > 0){
									while($usuario = mysqli_fetch_array($consultaUsuarios, MYSQLI_BOTH)){
										$emailUsuario = isset($usuario['usr_email']) && !empty($usuario['usr_email']) ? $usuario['usr_email'] : $usuario['usr_login'];
										echo '<option value="'.$usuario['usr_id'].'">'.$usuario['usr_nombre'].' ('.$emailUsuario.')</option>';
									}
								}
								?>
							</select>
							<div class="auto-add-info">
								<i class="fa-solid fa-info-circle"></i>
								<span>El usuario se agregará automáticamente al seleccionarlo</span>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Buscador y filtros -->
				<div class="search-container fade-in">
					<h4><i class="fa-solid fa-magnifying-glass"></i> Búsqueda General de Páginas</h4>
					<div class="search-wrapper">
						<i class="fa-solid fa-search search-icon"></i>
						<input type="text" id="searchGlobal" class="search-input" placeholder="Buscar por nombre de página, nombre del archivo (ej: listar-usuarios), módulo o descripción...">
						<button type="button" class="clear-search" id="clearSearch"><i class="fa-solid fa-times"></i></button>
					</div>
					<div class="search-stats" id="searchStats"></div>
				</div>
				
				<!-- Vista por Módulos -->
				<div id="modulesView" class="fade-in">
					<!-- Aquí se cargarán dinámicamente todos los módulos con sus páginas -->
				</div>
				
				<!-- Select oculto para enviar los datos -->
				<select id="paginasSeleccionadas" name="paginasP[]" multiple style="display: none;">
					<?php
					$consultaPagina = $conexionBdPrincipal->query("SELECT * FROM paginas_perfiles WHERE pper_tipo_usuario= '".$_GET["id"]."'");
					while ($page = $consultaPagina->fetch_assoc()) {
						echo '<option value="' . $page["pper_pagina"] . '" id="pag-' . $page["pper_pagina"] . '" selected >' . $page["pper_pagina"] . '</option>';
					}
					?>
				</select>
				
				<!-- Contador flotante de permisos -->
				<div class="permissions-counter">
					<h4><i class="fa-solid fa-chart-simple"></i> Permisos Asignados</h4>
					<div class="counter-item">
						<span class="counter-label">Total Páginas:</span>
						<span class="counter-value" id="totalPages">0</span>
					</div>
					<div class="counter-item">
						<span class="counter-label">Seleccionadas:</span>
						<span class="counter-value" id="selectedPages">0</span>
					</div>
				</div>
				
				<!-- Botones de acción -->
				<div class="action-buttons">
					<a href="javascript:history.go(-1);" class="btn-modern btn-modern-primary">
						<i class="fa-solid fa-arrow-left"></i> Regresar
					</a>
					<button type="submit" class="btn-modern btn-modern-success">
						<i class="fa-solid fa-save"></i> Guardar cambios
					</button>
				</div>
			</form>
			
		</div>
	</div>
	<?php include("includes/pie.php");?>
</div>
</body>
<script src="js/Roles.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		const rolId = <?= (int)$_GET['id']; ?>;
		cargarTodosLosModulos(rolId);
		cargarUsuariosDelRol(rolId);
		initSearchFunctionality();
		mejorarSelects(); // Mejorar visualización de selects
		
		// Actualizar usuarios disponibles después de cargar
		setTimeout(function() {
			actualizarUsuariosDisponibles();
		}, 1000);
		
		// Forzar estilos de selects después de cargar
		setTimeout(function() {
			const roleSelect = document.getElementById('roleSwitcher');
			const userSelect = document.getElementById('userToAdd');
			
			if (roleSelect) {
				roleSelect.style.color = '#333';
				roleSelect.style.fontWeight = '700';
				roleSelect.style.background = 'white';
			}
			
			if (userSelect) {
				userSelect.style.color = '#999';
				userSelect.style.fontWeight = '400';
				userSelect.style.background = '#fafafa';
			}
		}, 500);
	});
</script>
</html>