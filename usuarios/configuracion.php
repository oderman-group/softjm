<?php 
include("sesion.php");
$idPagina = 42;

include("includes/verificar-paginas.php");
include("includes/head.php");

$consulta = $conexionBdPrincipal->query("SELECT * FROM configuracion WHERE conf_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");
$resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);
?>

<!-- Font Awesome para iconos modernos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!--============ javascript ===========-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-fileupload.js"></script>
<script src="js/accordion.nav.js"></script>
<script src="js/chosen.jquery.js"></script>
<script src="js/custom.js"></script>
<script src="js/respond.min.js"></script>
<script src="js/ios-orientationchange-fix.js"></script>

<!-- Estilos modernos para configuración -->
<style>
/* ========== ESTILOS MODERNOS PARA CONFIGURACIÓN ========== */

.config-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 25px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    text-align: center;
    color: white;
}

.config-header h1 {
    color: white;
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.config-header p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1rem;
    margin: 10px 0 0 0;
}

/* ========== TABS MODERNOS ========== */
.config-tabs {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
}

.tabs-nav {
    display: flex;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    flex-wrap: wrap;
}

.tab-button {
    flex: 1;
    padding: 20px 25px;
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    color: #666;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-width: 150px;
}

.tab-button:hover {
    background: #e9ecef;
    color: #333;
}

.tab-button.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.tab-content {
    display: none;
    padding: 40px;
    animation: fadeIn 0.3s ease;
}

.tab-content.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ========== FORMULARIOS MODERNOS ========== */
.form-section {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 25px;
    border: 1px solid #e9ecef;
}

.form-section h3 {
    color: #333;
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0 0 25px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 15px;
    border-bottom: 2px solid #667eea;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 15px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: white;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    transform: scale(1.02);
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

/* ========== UPLOAD DE ARCHIVOS MODERNO ========== */
.file-upload-container {
    position: relative;
    margin-bottom: 20px;
}

.file-upload-wrapper {
    border: 3px dashed #e0e0e0;
    border-radius: 15px;
    padding: 30px;
    text-align: center;
    transition: all 0.3s ease;
    background: #fafafa;
    cursor: pointer;
}

.file-upload-wrapper:hover {
    border-color: #667eea;
    background: #f0f4ff;
    transform: scale(1.02);
}

.file-upload-wrapper.dragover {
    border-color: #667eea;
    background: #e3f2fd;
    transform: scale(1.05);
}

.file-upload-icon {
    font-size: 3rem;
    color: #667eea;
    margin-bottom: 15px;
}

.file-upload-text {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.file-upload-subtext {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
}

.file-upload-input {
    display: none;
}

.file-upload-button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-upload-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

/* ========== VISTA PREVIA DE IMÁGENES ========== */
.image-preview-container {
    margin-top: 20px;
    text-align: center;
}

.image-preview {
    max-width: 300px;
    max-height: 200px;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.image-preview:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.current-image {
    margin-top: 15px;
    padding: 15px;
    background: #e8f5e8;
    border-radius: 10px;
    border-left: 4px solid #4caf50;
}

.current-image h4 {
    color: #2e7d32;
    margin: 0 0 10px 0;
    font-size: 14px;
    font-weight: 600;
}

.current-image img {
    max-width: 200px;
    max-height: 150px;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

/* ========== COLOR PICKER MODERNO ========== */
.color-picker-container {
    display: flex;
    align-items: center;
    gap: 15px;
}

.color-picker-input {
    width: 60px !important;
    height: 50px;
    border-radius: 10px;
    border: 2px solid #e0e0e0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.color-picker-input:hover {
    transform: scale(1.1);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.color-picker-text {
    flex: 1;
}

/* ========== BOTONES MODERNOS ========== */
.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #e9ecef;
}

.btn-modern {
    padding: 15px 30px;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 150px;
    justify-content: center;
}

.btn-save {
    background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
    color: white;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
}

.btn-cancel {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    color: white;
}

.btn-cancel:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(244, 67, 54, 0.3);
}

/* ========== NOTIFICACIONES ========== */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 25px;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    z-index: 1000;
    transform: translateX(400px);
    transition: all 0.3s ease;
}

.notification.show {
    transform: translateX(0);
}

.notification.success {
    background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
    color: white;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.notification.error {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
    color: white !important;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5) !important;
    font-weight: 700 !important;
    border: 2px solid #fff !important;
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3) !important;
}

.notification.info {
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
    color: white;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

/* ========== LOADING STATES ========== */
.loading {
    opacity: 0.6;
    pointer-events: none;
    position: relative;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 30px;
    height: 30px;
    margin: -15px 0 0 -15px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
    .config-header {
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .config-header h1 {
        font-size: 1.8rem;
        flex-direction: column;
        gap: 10px;
    }
    
    .tabs-nav {
        flex-direction: column;
    }
    
    .tab-button {
        min-width: auto;
    }
    
    .tab-content {
        padding: 20px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-modern {
        width: 100%;
    }
}

/* ========== VALIDACIONES VISUALES ========== */
.form-group.error input,
.form-group.error select,
.form-group.error textarea {
    border-color: #f44336;
    box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.1);
}

.form-group.success input,
.form-group.success select,
.form-group.success textarea {
    border-color: #4caf50;
    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
}

.error-message {
    color: #f44336;
    font-size: 12px;
    margin-top: 5px;
    font-weight: 500;
}

.success-message {
    color: #4caf50;
    font-size: 12px;
    margin-top: 5px;
    font-weight: 500;
}

/* ========== TOOLTIPS MODERNOS ========== */
.tooltip-icon {
    color: #667eea;
    cursor: help;
    font-size: 14px;
    margin-left: 5px;
}

.tooltip-icon:hover {
    color: #5a67d8;
}

/* ========== HELP TEXT ========== */
.help-text {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
    font-style: italic;
}

.help-text.warning {
    color: #f44336;
    font-weight: 600;
}

.help-text.success {
    color: #4caf50;
    font-weight: 600;
}
</style>

</head>

<body>
    <div class="layout">
        <?php include("includes/encabezado.php"); ?>

        <div class="main-wrapper">
            <div class="container-fluid">
                <?php include("includes/notificaciones.php"); ?>
                <div class="row-fluid">
                    <div class="span12">
                        <!-- Header Principal -->
                        <div class="config-header">
                            <h1>
                                <i class="fa-solid fa-cogs"></i>
                                <?= $paginaActual['pag_nombre']; ?>
                            </h1>
                            <p>Gestiona la configuración general de tu empresa y personaliza el sistema</p>
                        </div>

        <!-- Tabs de Configuración -->
        <div class="config-tabs">
            <div class="tabs-nav">
                <button class="tab-button active" data-tab="empresa">
                    <i class="fa-solid fa-building"></i>
                    Empresa
                </button>
                <button class="tab-button" data-tab="financiero">
                    <i class="fa-solid fa-dollar-sign"></i>
                    Financiero
                </button>
                <button class="tab-button" data-tab="documentos">
                    <i class="fa-solid fa-file-image"></i>
                    Documentos
                </button>
                <button class="tab-button" data-tab="email">
                    <i class="fa-solid fa-envelope"></i>
                    Email
                </button>
                <button class="tab-button" data-tab="sistema">
                    <i class="fa-solid fa-sliders-h"></i>
                    Sistema
                </button>
            </div>

            <!-- Tab: Información de la Empresa -->
            <div class="tab-content active" id="empresa">
                <div class="form-section">
                    <h3><i class="fa-solid fa-building"></i> Información Básica</h3>
                    
                    <div class="form-group">
                        <label for="nombre">Nombre de la empresa <span class="help-text">(Requerido)</span></label>
                        <input type="text" id="nombre" name="nombre" value="<?= $resultadoD['conf_empresa']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="nit">NIT de la empresa</label>
                        <input type="text" id="nit" name="nit" value="<?= $resultadoD['conf_nit']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email de la empresa <span class="help-text">(Requerido)</span></label>
                        <input type="email" id="email" name="email" value="<?= $resultadoD['conf_email']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="claveEmail">Clave del Email <i class="fa-solid fa-circle-question tooltip-icon" title="Clave asignada al correo para iniciar sesión"></i></label>
                        <input type="password" id="claveEmail" name="claveEmail" value="<?= $resultadoD['conf_clave_correo']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono de la empresa</label>
                        <input type="tel" id="telefono" name="telefono" value="<?= $resultadoD['conf_telefono']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="web">Sitio Web de la empresa</label>
                        <input type="url" id="web" name="web" value="<?= $resultadoD['conf_web']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="urlEncuestas">URL del CRM <i class="fa-solid fa-circle-question tooltip-icon" title="Dirección web donde se encuentra alojado el sistema CRM"></i></label>
                        <input type="url" id="urlEncuestas" name="urlEncuestas" value="<?= $resultadoD['conf_url_encuestas']; ?>" required>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fa-solid fa-image"></i> Logo de la Empresa</h3>
                    
                    <div class="file-upload-container">
                        <div class="file-upload-wrapper" onclick="document.getElementById('logo').click()">
                            <div class="file-upload-icon">
                                <i class="fa-solid fa-cloud-upload-alt"></i>
                            </div>
                            <div class="file-upload-text">Subir Logo de la Empresa</div>
                            <div class="file-upload-subtext">Formatos: JPG, PNG, GIF (Máx: 5MB)</div>
                            <button type="button" class="file-upload-button">
                                <i class="fa-solid fa-plus"></i> Seleccionar Archivo
                            </button>
                        </div>
                        <input type="file" id="logo" name="logo" class="file-upload-input" accept="image/*">
                        
                        <div class="image-preview-container" id="logoPreview"></div>
                        
                        <?php if($resultadoD['conf_logo']): ?>
                        <div class="current-image">
                            <h4><i class="fa-solid fa-check-circle"></i> Logo Actual</h4>
                            <img src="files/<?= $resultadoD['conf_logo']; ?>" alt="Logo actual">
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="anchoLogo">Ancho del Logo en Login (PX)</label>
                        <input type="number" id="anchoLogo" name="anchoLogo" value="<?= $resultadoD['conf_ancho_logo']; ?>" min="50" max="500">
                        <div class="help-text warning">Coloque sólo el número</div>
                    </div>

                    <div class="form-group">
                        <label for="altoLogo">Alto del Logo en Login (PX)</label>
                        <input type="number" id="altoLogo" name="altoLogo" value="<?= $resultadoD['conf_alto_logo']; ?>" min="50" max="500">
                        <div class="help-text warning">Coloque sólo el número</div>
                    </div>
                </div>
            </div>

            <!-- Tab: Configuración Financiera -->
            <div class="tab-content" id="financiero">
                <div class="form-section">
                    <h3><i class="fa-solid fa-dollar-sign"></i> Configuración Monetaria</h3>
                    
                    <div class="form-group">
                        <label for="agnoInicio">Año de inicio</label>
                        <input type="number" id="agnoInicio" name="agnoInicio" value="<?= $resultadoD['conf_agno_inicio']; ?>" min="2000" max="2030" required>
                        <div class="help-text">Desde cuando empezó a usar este CRM</div>
                    </div>

                    <div class="form-group">
                        <label for="dolarCompra">Dólar compra <i class="fa-solid fa-circle-question tooltip-icon" title="Precio del dólar actual para compras"></i></label>
                        <input type="number" id="dolarCompra" name="dolarCompra" value="<?= $resultadoD['conf_trm_compra']; ?>" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="dolarVenta">Dólar venta <i class="fa-solid fa-circle-question tooltip-icon" title="Precio del dólar actual para ventas"></i></label>
                        <input type="number" id="dolarVenta" name="dolarVenta" value="<?= $resultadoD['conf_trm_venta']; ?>" step="0.01" min="0" required>
                        <div class="help-text warning">Este es el valor por el cual se multiplicará el dólar para cambiar a pesos</div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fa-solid fa-percentage"></i> Porcentajes y Comisiones</h3>
                    
                    <div class="form-group">
                        <label for="porcentajeClientes">Porcentaje para clientes (Compras) <i class="fa-solid fa-circle-question tooltip-icon" title="Descuentos a los clientes por las compras o paquetes"></i></label>
                        <input type="number" id="porcentajeClientes" name="porcentajeClientes" value="<?= $resultadoD['conf_porcentaje_clientes']; ?>" step="0.01" min="0" max="100">
                        <div class="help-text warning">Lo que se le dará al cliente por cada compra</div>
                    </div>

                    <div class="form-group">
                        <label for="comisionVendedores">Porcentaje comisión ventas <i class="fa-solid fa-circle-question tooltip-icon" title="Comisión para los vendedores"></i></label>
                        <input type="number" id="comisionVendedores" name="comisionVendedores" value="<?= $resultadoD['conf_comision_vendedores']; ?>" step="0.01" min="0" max="100">
                        <div class="help-text warning">Lo que se le dará al vendedor por cada venta</div>
                    </div>

                    <div class="form-group">
                        <label for="correoPuntos">Saldo para enviar correo <i class="fa-solid fa-circle-question tooltip-icon" title="Monto pendiente a cancelar"></i></label>
                        <input type="number" id="correoPuntos" name="correoPuntos" value="<?= $resultadoD['conf_coreo_puntos']; ?>" step="0.01" min="0">
                        <div class="help-text warning">El sistema enviará un correo automático cuando los clientes acumulen este saldo</div>
                    </div>

                    <div class="form-group">
                        <label for="fechaVencimientoSaldo">Próximo vencimiento <i class="fa-solid fa-circle-question tooltip-icon" title="Fecha de vencimiento del bono del cliente"></i></label>
                        <input type="date" id="fechaVencimientoSaldo" name="fechaVencimientoSaldo" value="<?= $resultadoD['conf_vencimiento_puntos']; ?>">
                        <div class="help-text warning">La fecha límite próxima para que los clientes rediman su saldo acumulado</div>
                    </div>
                </div>
            </div>

            <!-- Tab: Documentos e Imágenes -->
            <div class="tab-content" id="documentos">
                <div class="form-section">
                    <h3><i class="fa-solid fa-file-contract"></i> Encabezados de Cotización</h3>
                    
                    <div class="file-upload-container">
                        <div class="file-upload-wrapper" onclick="document.getElementById('encabezadoCotizacion').click()">
                            <div class="file-upload-icon">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <div class="file-upload-text">Encabezado de Cotización</div>
                            <div class="file-upload-subtext">Dimensiones recomendadas: 1920 x 343 px</div>
                            <button type="button" class="file-upload-button">
                                <i class="fa-solid fa-plus"></i> Seleccionar Archivo
                            </button>
                        </div>
                        <input type="file" id="encabezadoCotizacion" name="encabezadoCotizacion" class="file-upload-input" accept="image/*">
                        
                        <div class="image-preview-container" id="encabezadoCotizacionPreview"></div>
                        
                        <?php if($resultadoD['conf_encabezado_cotizacion']): ?>
                        <div class="current-image">
                            <h4><i class="fa-solid fa-check-circle"></i> Encabezado Actual</h4>
                            <img src="images/<?= $resultadoD['conf_encabezado_cotizacion']; ?>" alt="Encabezado actual">
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="file-upload-container">
                        <div class="file-upload-wrapper" onclick="document.getElementById('encabezadoCotizacion2').click()">
                            <div class="file-upload-icon">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <div class="file-upload-text">Encabezado 2 de Cotización</div>
                            <div class="file-upload-subtext">Dimensiones recomendadas: 1920 x 224 px</div>
                            <button type="button" class="file-upload-button">
                                <i class="fa-solid fa-plus"></i> Seleccionar Archivo
                            </button>
                        </div>
                        <input type="file" id="encabezadoCotizacion2" name="encabezadoCotizacion2" class="file-upload-input" accept="image/*">
                        
                        <div class="image-preview-container" id="encabezadoCotizacion2Preview"></div>
                        
                        <?php if($resultadoD['conf_encabezado2_cotizacion']): ?>
                        <div class="current-image">
                            <h4><i class="fa-solid fa-check-circle"></i> Encabezado 2 Actual</h4>
                            <img src="images/<?= $resultadoD['conf_encabezado2_cotizacion']; ?>" alt="Encabezado 2 actual">
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="file-upload-container">
                        <div class="file-upload-wrapper" onclick="document.getElementById('pieCotizacion').click()">
                            <div class="file-upload-icon">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <div class="file-upload-text">Pie de Cotización</div>
                            <div class="file-upload-subtext">Dimensiones recomendadas: 1920 x 533 px</div>
                            <button type="button" class="file-upload-button">
                                <i class="fa-solid fa-plus"></i> Seleccionar Archivo
                            </button>
                        </div>
                        <input type="file" id="pieCotizacion" name="pieCotizacion" class="file-upload-input" accept="image/*">
                        
                        <div class="image-preview-container" id="pieCotizacionPreview"></div>
                        
                        <?php if($resultadoD['conf_pie_cotizacion']): ?>
                        <div class="current-image">
                            <h4><i class="fa-solid fa-check-circle"></i> Pie Actual</h4>
                            <img src="images/<?= $resultadoD['conf_pie_cotizacion']; ?>" alt="Pie actual">
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fa-solid fa-shopping-cart"></i> Encabezados de Pedidos</h3>
                    
                    <div class="file-upload-container">
                        <div class="file-upload-wrapper" onclick="document.getElementById('encabezadoPedido').click()">
                            <div class="file-upload-icon">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <div class="file-upload-text">Encabezado de Pedido</div>
                            <div class="file-upload-subtext">Dimensiones recomendadas: 1920 x 343 px</div>
                            <button type="button" class="file-upload-button">
                                <i class="fa-solid fa-plus"></i> Seleccionar Archivo
                            </button>
                        </div>
                        <input type="file" id="encabezadoPedido" name="encabezadoPedido" class="file-upload-input" accept="image/*">
                        
                        <div class="image-preview-container" id="encabezadoPedidoPreview"></div>
                        
                        <?php if($resultadoD['conf_encabezado_pedido']): ?>
                        <div class="current-image">
                            <h4><i class="fa-solid fa-check-circle"></i> Encabezado Actual</h4>
                            <img src="images/<?= $resultadoD['conf_encabezado_pedido']; ?>" alt="Encabezado actual">
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="file-upload-container">
                        <div class="file-upload-wrapper" onclick="document.getElementById('encabezadoPedido2').click()">
                            <div class="file-upload-icon">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <div class="file-upload-text">Encabezado 2 de Pedido</div>
                            <div class="file-upload-subtext">Dimensiones recomendadas: 1920 x 224 px</div>
                            <button type="button" class="file-upload-button">
                                <i class="fa-solid fa-plus"></i> Seleccionar Archivo
                            </button>
                        </div>
                        <input type="file" id="encabezadoPedido2" name="encabezadoPedido2" class="file-upload-input" accept="image/*">
                        
                        <div class="image-preview-container" id="encabezadoPedido2Preview"></div>
                        
                        <?php if($resultadoD['conf_encabezado2_pedido']): ?>
                        <div class="current-image">
                            <h4><i class="fa-solid fa-check-circle"></i> Encabezado 2 Actual</h4>
                            <img src="images/<?= $resultadoD['conf_encabezado2_pedido']; ?>" alt="Encabezado 2 actual">
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="file-upload-container">
                        <div class="file-upload-wrapper" onclick="document.getElementById('piePedido').click()">
                            <div class="file-upload-icon">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <div class="file-upload-text">Pie de Pedido</div>
                            <div class="file-upload-subtext">Dimensiones recomendadas: 1920 x 533 px</div>
                            <button type="button" class="file-upload-button">
                                <i class="fa-solid fa-plus"></i> Seleccionar Archivo
                            </button>
                        </div>
                        <input type="file" id="piePedido" name="piePedido" class="file-upload-input" accept="image/*">
                        
                        <div class="image-preview-container" id="piePedidoPreview"></div>
                        
                        <?php if($resultadoD['conf_pie_pedido']): ?>
                        <div class="current-image">
                            <h4><i class="fa-solid fa-check-circle"></i> Pie Actual</h4>
                            <img src="images/<?= $resultadoD['conf_pie_pedido']; ?>" alt="Pie actual">
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tab: Configuración de Email -->
            <div class="tab-content" id="email">
                <div class="form-section">
                    <h3><i class="fa-solid fa-palette"></i> Colores del Email</h3>
                    
                    <div class="form-group">
                        <label for="fondoBoletin">Fondo del Email</label>
                        <div class="color-picker-container">
                            <input type="color" id="fondoBoletin" name="fondoBoletin" value="<?= $resultadoD['conf_fondo_boletin']; ?>" class="color-picker-input">
                            <input type="text" value="<?= $resultadoD['conf_fondo_boletin']; ?>" class="color-picker-text" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fondoMensaje">Fondo del Mensaje</label>
                        <div class="color-picker-container">
                            <input type="color" id="fondoMensaje" name="fondoMensaje" value="<?= $resultadoD['conf_fondo_mensaje']; ?>" class="color-picker-input">
                            <input type="text" value="<?= $resultadoD['conf_fondo_mensaje']; ?>" class="color-picker-text" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="colorLetra">Color de la letra</label>
                        <div class="color-picker-container">
                            <input type="color" id="colorLetra" name="colorLetra" value="<?= $resultadoD['conf_color_letra']; ?>" class="color-picker-input">
                            <input type="text" value="<?= $resultadoD['conf_color_letra']; ?>" class="color-picker-text" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="colorLink">Color de los links</label>
                        <div class="color-picker-container">
                            <input type="color" id="colorLink" name="colorLink" value="<?= $resultadoD['conf_color_link']; ?>" class="color-picker-input">
                            <input type="text" value="<?= $resultadoD['conf_color_link']; ?>" class="color-picker-text" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fa-solid fa-envelope-open"></i> Contenido del Email</h3>
                    
                    <div class="form-group">
                        <label for="botonNombre">Nombre del Botón (Llamado a la acción)</label>
                        <input type="text" id="botonNombre" name="botonNombre" value="<?= $resultadoD['conf_nombre_boton']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="botonUrl">URL del Botón (URL donde irá)</label>
                        <input type="url" id="botonUrl" name="botonUrl" value="<?= $resultadoD['conf_url_boton']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="mensajePie">Mensaje final corto (Pie del email)</label>
                        <textarea id="mensajePie" name="mensajePie" rows="3"><?= $resultadoD['conf_mensaje_pie']; ?></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fa-solid fa-cog"></i> Configuración Avanzada de Email</h3>
                    <p style="color: #666; margin-bottom: 20px; font-style: italic;">
                        <i class="fa-solid fa-info-circle"></i>
                        Configuraciones avanzadas para el diseño de emails y boletines
                    </p>
                    
                    <button type="button" class="btn-modern" onclick="toggleAdvancedEmail()" style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%); margin-bottom: 20px;">
                        <i class="fa-solid fa-eye" id="advancedEmailIcon"></i>
                        <span id="advancedEmailText">Mostrar Configuración Avanzada</span>
                    </button>
                    
                    <div id="advancedEmailConfig" style="display: none;">
                        <div class="form-group">
                            <label for="fondoBoletin">Fondo del Email</label>
                            <div class="color-picker-container">
                                <input type="color" id="fondoBoletin" name="fondoBoletin" value="<?= $resultadoD['conf_fondo_boletin']; ?>" class="color-picker-input">
                                <input type="text" value="<?= $resultadoD['conf_fondo_boletin']; ?>" class="color-picker-text" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fondoMensaje">Fondo del Mensaje</label>
                            <div class="color-picker-container">
                                <input type="color" id="fondoMensaje" name="fondoMensaje" value="<?= $resultadoD['conf_fondo_mensaje']; ?>" class="color-picker-input">
                                <input type="text" value="<?= $resultadoD['conf_fondo_mensaje']; ?>" class="color-picker-text" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="colorLetra">Color de la letra</label>
                            <div class="color-picker-container">
                                <input type="color" id="colorLetra" name="colorLetra" value="<?= $resultadoD['conf_color_letra']; ?>" class="color-picker-input">
                                <input type="text" value="<?= $resultadoD['conf_color_letra']; ?>" class="color-picker-text" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="colorLink">Color de los links</label>
                            <div class="color-picker-container">
                                <input type="color" id="colorLink" name="colorLink" value="<?= $resultadoD['conf_color_link']; ?>" class="color-picker-input">
                                <input type="text" value="<?= $resultadoD['conf_color_link']; ?>" class="color-picker-text" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Configuración del Sistema -->
            <div class="tab-content" id="sistema">
                <div class="form-section">
                    <h3><i class="fa-solid fa-cogs"></i> Configuración General</h3>
                    
                    <div class="form-group">
                        <label for="proveedorCotizacion">Asociar proveedores en cotización <i class="fa-solid fa-circle-question tooltip-icon" title="Elija los proveedores que desea hacer la cotización"></i></label>
                        <select id="proveedorCotizacion" name="proveedorCotizacion">
                            <option value="1" <?php if($resultadoD['conf_proveedor_cotizacion'] == 1){echo "selected";}?>>SÍ</option>
                            <option value="0" <?php if($resultadoD['conf_proveedor_cotizacion'] == '0'){echo "selected";}?>>NO</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="paginacion">Paginación <i class="fa-solid fa-circle-question tooltip-icon" title="Escoja el número de páginas que desea ver"></i></label>
                        <input type="number" id="paginacion" name="paginacion" value="<?= $resultadoD['conf_paginacion']; ?>" min="5" max="100">
                        <div class="help-text warning">Son los registros que mostrará por cada página</div>
                    </div>

                    <div class="form-group">
                        <label for="clientesImprimir">Permitir a clientes imprimir certificados</label>
                        <select id="clientesImprimir" name="clientesImprimir">
                            <option value="1" <?php if($resultadoD['conf_cliente_imprimir_certificado'] == 1){echo "selected";}?>>SÍ</option>
                            <option value="0" <?php if($resultadoD['conf_cliente_imprimir_certificado'] == '0'){echo "selected";}?>>NO</option>
                        </select>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fa-solid fa-file-contract"></i> Términos y Condiciones</h3>
                    
                    <div class="form-group">
                        <label for="terminos">Términos y condiciones</label>
                        <textarea id="terminos" name="terminos" rows="10"><?= $resultadoD['conf_terminos_condiciones']; ?></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fa-solid fa-clock"></i> Configuración de Alertas</h3>
                    
                    <div class="form-group">
                        <label for="conf_tiempo_ticket_sin_respuesta">Tiempo para alerta de ticket sin respuesta (Horas) <i class="fa-solid fa-circle-question tooltip-icon" title="Establezca el tiempo en horas para que el sistema dispare una notificación al encargado cuando un ticket no tenga respuesta"></i></label>
                        <input type="number" id="conf_tiempo_ticket_sin_respuesta" name="conf_tiempo_ticket_sin_respuesta" value="<?= $resultadoD['conf_tiempo_ticket_sin_respuesta']; ?>" min="1" max="168">
                    </div>

                    <div class="form-group">
                        <label for="conf_tiempo_tareas_vencidas">Tiempo para alerta de tareas vencidas (Horas) <i class="fa-solid fa-circle-question tooltip-icon" title="Establezca la frecuencia en horas para que el sistema dispare una notificación al encargado por tareas vencidas"></i></label>
                        <input type="number" id="conf_tiempo_tareas_vencidas" name="conf_tiempo_tareas_vencidas" value="<?= $resultadoD['conf_tiempo_tareas_vencidas']; ?>" min="1" max="168">
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="action-buttons">
                <?php if(Modulos::validarRol([265], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)): ?>
                <button type="button" class="btn-modern btn-save" onclick="guardarConfiguracion()">
                    <i class="fa-solid fa-save"></i>
                    Guardar Cambios
                </button>
                <?php endif; ?>
                <button type="button" class="btn-modern btn-cancel" onclick="cancelarCambios()">
                    <i class="fa-solid fa-times"></i>
                    Cancelar
                </button>
                    </div>
                </div>
            </div>
        </div>
        <?php include("includes/pie.php"); ?>
    </div>

    <!-- JavaScript para funcionalidad moderna -->
    <script>
    // ========== VARIABLES GLOBALES ==========
    let configuracionOriginal = {};
    let cambiosPendientes = false;

    // ========== INICIALIZACIÓN ==========
    document.addEventListener('DOMContentLoaded', function() {
        inicializarTabs();
        inicializarFileUploads();
        inicializarColorPickers();
        inicializarValidaciones();
        guardarConfiguracionOriginal();
        
        // Detectar cambios en tiempo real
        document.querySelectorAll('input, select, textarea').forEach(elemento => {
            elemento.addEventListener('input', marcarCambios);
            elemento.addEventListener('change', marcarCambios);
        });
    });

    // ========== GESTIÓN DE TABS ==========
    function inicializarTabs() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                
                // Remover clase active de todos
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Agregar clase active al seleccionado
                button.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });
    }

    // ========== UPLOAD DE ARCHIVOS CON VISTA PREVIA ==========
    function inicializarFileUploads() {
        const fileInputs = document.querySelectorAll('.file-upload-input');
        
        fileInputs.forEach(input => {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    mostrarVistaPrevia(file, input.id + 'Preview');
                    validarArchivo(file, input);
                }
            });
        });

        // Drag and drop
        const uploadWrappers = document.querySelectorAll('.file-upload-wrapper');
        uploadWrappers.forEach(wrapper => {
            wrapper.addEventListener('dragover', function(e) {
                e.preventDefault();
                wrapper.classList.add('dragover');
            });

            wrapper.addEventListener('dragleave', function(e) {
                e.preventDefault();
                wrapper.classList.remove('dragover');
            });

            wrapper.addEventListener('drop', function(e) {
                e.preventDefault();
                wrapper.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const input = wrapper.nextElementSibling;
                    input.files = files;
                    input.dispatchEvent(new Event('change'));
                }
            });
        });
    }

    function mostrarVistaPrevia(file, previewId) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewContainer = document.getElementById(previewId);
            previewContainer.innerHTML = `
                <div style="margin-top: 15px; padding: 15px; background: #e8f5e8; border-radius: 10px; border-left: 4px solid #4caf50;">
                    <h4 style="color: #2e7d32; margin: 0 0 10px 0; font-size: 14px; font-weight: 600;">
                        <i class="fa-solid fa-eye"></i> Vista Previa
                    </h4>
                    <img src="${e.target.result}" class="image-preview" alt="Vista previa">
                    <div style="margin-top: 10px; font-size: 12px; color: #666;">
                        <strong>Archivo:</strong> ${file.name}<br>
                        <strong>Tamaño:</strong> ${(file.size / 1024 / 1024).toFixed(2)} MB
                    </div>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }

    function validarArchivo(file, input) {
        const maxSize = 5 * 1024 * 1024; // 5MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
        if (file.size > maxSize) {
            mostrarNotificacion('El archivo es demasiado grande. Máximo 5MB.', 'error');
            input.value = '';
            return false;
        }
        
        if (!allowedTypes.includes(file.type)) {
            mostrarNotificacion('Tipo de archivo no válido. Solo JPG, PNG, GIF.', 'error');
            input.value = '';
            return false;
        }
        
        mostrarNotificacion('Archivo válido y listo para subir', 'success');
        return true;
    }

    // ========== COLOR PICKERS ==========
    function inicializarColorPickers() {
        const colorInputs = document.querySelectorAll('.color-picker-input');
        
        colorInputs.forEach(input => {
            input.addEventListener('change', function() {
                const textInput = this.nextElementSibling;
                textInput.value = this.value;
                marcarCambios();
            });
        });
    }

    // ========== VALIDACIONES ==========
    function inicializarValidaciones() {
        // Validación de email
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                validarEmail(this);
            });
        }

        // Validación de URL
        const urlInputs = document.querySelectorAll('input[type="url"]');
        urlInputs.forEach(input => {
            input.addEventListener('blur', function() {
                validarURL(this);
            });
        });

        // Validación de números
        const numberInputs = document.querySelectorAll('input[type="number"]');
        numberInputs.forEach(input => {
            input.addEventListener('blur', function() {
                validarNumero(this);
            });
        });
    }

    function validarEmail(input) {
        const email = input.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            mostrarError(input, 'Formato de email inválido');
            return false;
        } else {
            mostrarExito(input);
            return true;
        }
    }

    function validarURL(input) {
        const url = input.value;
        const urlRegex = /^https?:\/\/.+/;
        
        if (url && !urlRegex.test(url)) {
            mostrarError(input, 'URL debe comenzar con http:// o https://');
            return false;
        } else {
            mostrarExito(input);
            return true;
        }
    }

    function validarNumero(input) {
        const valor = parseFloat(input.value);
        const min = parseFloat(input.getAttribute('min'));
        const max = parseFloat(input.getAttribute('max'));
        
        if (input.value && (isNaN(valor) || (min && valor < min) || (max && valor > max))) {
            mostrarError(input, `Valor debe estar entre ${min || 0} y ${max || '∞'}`);
            return false;
        } else {
            mostrarExito(input);
            return true;
        }
    }

    function mostrarError(input, mensaje) {
        const formGroup = input.closest('.form-group');
        formGroup.classList.remove('success');
        formGroup.classList.add('error');
        
        let errorDiv = formGroup.querySelector('.error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            formGroup.appendChild(errorDiv);
        }
        errorDiv.textContent = mensaje;
    }

    function mostrarExito(input) {
        const formGroup = input.closest('.form-group');
        formGroup.classList.remove('error');
        formGroup.classList.add('success');
        
        const errorDiv = formGroup.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    // ========== GESTIÓN DE CAMBIOS ==========
    function guardarConfiguracionOriginal() {
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            configuracionOriginal[input.name] = input.value;
        });
    }

    function marcarCambios() {
        cambiosPendientes = true;
        const saveButton = document.querySelector('.btn-save');
        if (saveButton) {
            saveButton.innerHTML = '<i class="fa-solid fa-save"></i> Guardar Cambios *';
            saveButton.style.background = 'linear-gradient(135deg, #ff9800 0%, #f57c00 100%)';
        }
    }

    // ========== GUARDAR CONFIGURACIÓN ==========
    function guardarConfiguracion() {
        if (!validarFormulario()) {
            mostrarNotificacion('Por favor corrige los errores antes de guardar', 'error');
            return;
        }

        const saveButton = document.querySelector('.btn-save');
        if (saveButton) {
            saveButton.classList.add('loading');
            saveButton.disabled = true;
        }

        const formData = new FormData();
        
        // Agregar todos los campos del formulario
        document.querySelectorAll('input, select, textarea').forEach(input => {
            if (input.type === 'file') {
                if (input.files.length > 0) {
                    formData.append(input.name, input.files[0]);
                }
            } else {
                formData.append(input.name, input.value);
            }
        });

        fetch('bd_update/configuracion-actualizar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes('msg=2')) {
                mostrarNotificacion('Configuración guardada exitosamente', 'success');
                cambiosPendientes = false;
                guardarConfiguracionOriginal();
                
                // Resetear botón
                if (saveButton) {
                    saveButton.innerHTML = '<i class="fa-solid fa-save"></i> Guardar Cambios';
                    saveButton.style.background = 'linear-gradient(135deg, #4caf50 0%, #45a049 100%)';
                }
            } else {
                mostrarNotificacion('Error al guardar la configuración', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error de conexión', 'error');
        })
        .finally(() => {
            if (saveButton) {
                saveButton.classList.remove('loading');
                saveButton.disabled = false;
            }
        });
    }

    function validarFormulario() {
        let esValido = true;
        
        // Validar campos requeridos
        const camposRequeridos = document.querySelectorAll('[required]');
        camposRequeridos.forEach(campo => {
            if (!campo.value.trim()) {
                mostrarError(campo, 'Este campo es requerido');
                esValido = false;
            }
        });

        // Validar emails
        const emails = document.querySelectorAll('input[type="email"]');
        emails.forEach(email => {
            if (!validarEmail(email)) {
                esValido = false;
            }
        });

        // Validar URLs
        const urls = document.querySelectorAll('input[type="url"]');
        urls.forEach(url => {
            if (!validarURL(url)) {
                esValido = false;
            }
        });

        // Validar números
        const numeros = document.querySelectorAll('input[type="number"]');
        numeros.forEach(numero => {
            if (!validarNumero(numero)) {
                esValido = false;
            }
        });

        return esValido;
    }

    function cancelarCambios() {
        if (cambiosPendientes) {
            if (confirm('¿Estás seguro de cancelar los cambios? Se perderán las modificaciones no guardadas.')) {
                location.reload();
            }
        } else {
            location.reload();
        }
    }

    // ========== NOTIFICACIONES ==========
    function mostrarNotificacion(mensaje, tipo = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${tipo}`;
        notification.innerHTML = `
            <i class="fa-solid fa-${tipo === 'success' ? 'check-circle' : tipo === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${mensaje}
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 6000); // Aumentado de 3 a 6 segundos
    }

    // ========== PREVENIR SALIDA ACCIDENTAL ==========
    window.addEventListener('beforeunload', function(e) {
        if (cambiosPendientes) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // ========== CONFIGURACIÓN AVANZADA DE EMAIL ==========
    function toggleAdvancedEmail() {
        const configDiv = document.getElementById('advancedEmailConfig');
        const icon = document.getElementById('advancedEmailIcon');
        const text = document.getElementById('advancedEmailText');
        
        if (configDiv.style.display === 'none') {
            configDiv.style.display = 'block';
            icon.className = 'fa-solid fa-eye-slash';
            text.textContent = 'Ocultar Configuración Avanzada';
            configDiv.style.animation = 'fadeIn 0.3s ease';
        } else {
            configDiv.style.display = 'none';
            icon.className = 'fa-solid fa-eye';
            text.textContent = 'Mostrar Configuración Avanzada';
        }
    }
    </script>

</body>
</html>