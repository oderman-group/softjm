<?php
include("brand-config.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Recuperación de contraseña - Sistema <?= BRAND_NAME ?>">
  <title><?= BRAND_NAME ?> | Recuperar Contraseña</title>
  
  <!-- Favicon -->
  <link rel="shortcut icon" href="assets-login/images/favicon.png" />
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary-color: #667eea;
      --primary-dark: #5568d3;
      --secondary-color: #764ba2;
      --success-color: #48bb78;
      --error-color: #f56565;
      --warning-color: #ed8936;
      --text-primary: #2d3748;
      --text-secondary: #718096;
      --bg-light: #f7fafc;
      --bg-white: #ffffff;
      --border-color: #e2e8f0;
      --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      overflow-x: hidden;
    }

    .particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: 0;
      pointer-events: none;
    }

    .particle {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float 20s infinite ease-in-out;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0) translateX(0); opacity: 0; }
      25% { opacity: 1; }
      50% { transform: translateY(-800px) translateX(400px); opacity: 0.5; }
      75% { opacity: 1; }
    }

    .recovery-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 500px;
      background: var(--bg-white);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: var(--shadow-xl);
      animation: fadeInScale 0.6s ease;
    }

    @keyframes fadeInScale {
      from {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
      }
      to {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }

    .recovery-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 48px 40px;
      text-align: center;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .recovery-header::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
      animation: rotate 30s linear infinite;
    }

    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    .recovery-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 20px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(10px);
      position: relative;
      z-index: 1;
    }

    .recovery-icon i {
      font-size: 36px;
      color: white;
    }

    .recovery-header h1 {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
      position: relative;
      z-index: 1;
    }

    .recovery-header p {
      font-size: 15px;
      opacity: 0.95;
      position: relative;
      z-index: 1;
    }

    .recovery-body {
      padding: 40px;
    }

    .alert {
      padding: 16px 20px;
      border-radius: 12px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 14px;
      font-weight: 500;
      animation: slideInDown 0.4s ease;
      position: relative;
      overflow: hidden;
    }

    .alert::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 4px;
    }

    .alert-success {
      background: #f0fff4;
      color: #276749;
      border: 1px solid #9ae6b4;
    }

    .alert-success::before {
      background: var(--success-color);
    }

    .alert-error {
      background: #fff5f5;
      color: #c53030;
      border: 1px solid #feb2b2;
    }

    .alert-error::before {
      background: var(--error-color);
    }

    .alert i {
      font-size: 20px;
    }

    .alert-close {
      margin-left: auto;
      background: none;
      border: none;
      font-size: 20px;
      color: inherit;
      cursor: pointer;
      opacity: 0.6;
      transition: opacity 0.3s ease;
      padding: 0;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .alert-close:hover {
      opacity: 1;
    }

    .info-box {
      background: linear-gradient(135deg, #edf2f7 0%, #e6f0ff 100%);
      border: 2px solid #bee3f8;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 32px;
    }

    .info-box-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 12px;
    }

    .info-box-header i {
      font-size: 24px;
      color: var(--primary-color);
    }

    .info-box-header h3 {
      font-size: 16px;
      font-weight: 600;
      color: var(--text-primary);
      margin: 0;
    }

    .info-box p {
      font-size: 14px;
      color: var(--text-secondary);
      line-height: 1.6;
      margin: 0;
    }

    .form-group {
      margin-bottom: 24px;
    }

    .form-label {
      display: block;
      font-size: 14px;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 8px;
    }

    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon {
      position: absolute;
      left: 16px;
      color: var(--text-secondary);
      font-size: 18px;
      transition: color 0.3s ease;
      pointer-events: none;
    }

    .form-input {
      width: 100%;
      padding: 14px 16px 14px 48px;
      font-size: 15px;
      border: 2px solid var(--border-color);
      border-radius: 12px;
      background: var(--bg-white);
      color: var(--text-primary);
      transition: all 0.3s ease;
      font-family: 'Inter', sans-serif;
    }

    .form-input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .form-input:focus + .input-icon {
      color: var(--primary-color);
    }

    .form-input.error {
      border-color: var(--error-color);
    }

    .form-input.error:focus {
      box-shadow: 0 0 0 4px rgba(245, 101, 101, 0.1);
    }

    .input-error-message {
      display: none;
      font-size: 13px;
      color: var(--error-color);
      margin-top: 6px;
      animation: slideInDown 0.3s ease;
    }

    .input-error-message.show {
      display: block;
    }

    .btn-primary {
      width: 100%;
      padding: 16px;
      font-size: 16px;
      font-weight: 600;
      color: white;
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      border: none;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      position: relative;
      overflow: hidden;
      margin-bottom: 16px;
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s ease;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .btn-primary:hover::before {
      left: 100%;
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .btn-primary:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .btn-primary.loading {
      pointer-events: none;
    }

    .btn-primary .spinner {
      display: none;
      width: 20px;
      height: 20px;
      border: 3px solid rgba(255, 255, 255, 0.3);
      border-top-color: white;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
      margin: 0 auto;
    }

    .btn-primary.loading .btn-text {
      display: none;
    }

    .btn-primary.loading .spinner {
      display: block;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .btn-secondary {
      width: 100%;
      padding: 16px;
      font-size: 16px;
      font-weight: 600;
      color: var(--text-primary);
      background: var(--bg-white);
      border: 2px solid var(--border-color);
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      text-decoration: none;
    }

    .btn-secondary:hover {
      background: var(--bg-light);
      border-color: var(--text-secondary);
      transform: translateY(-2px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary i {
      font-size: 18px;
    }

    .loading-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(5px);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }

    .loading-overlay.show {
      display: flex;
    }

    .loading-spinner {
      width: 60px;
      height: 60px;
      border: 4px solid rgba(255, 255, 255, 0.2);
      border-top-color: white;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }

    @media (max-width: 576px) {
      body {
        padding: 10px;
      }

      .recovery-header {
        padding: 32px 24px;
      }

      .recovery-header h1 {
        font-size: 24px;
      }

      .recovery-body {
        padding: 30px 24px;
      }

      .form-input {
        font-size: 14px;
        padding: 12px 14px 12px 44px;
      }
    }
  </style>
</head>

<body>
  <!-- Animated Background -->
  <div class="particles" id="particles"></div>

  <!-- Loading Overlay -->
  <div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
  </div>

  <!-- Recovery Container -->
  <div class="recovery-container">
    <!-- Header -->
    <div class="recovery-header">
      <div class="recovery-icon">
        <i class="fas fa-key"></i>
      </div>
      <h1>Recuperar Contraseña</h1>
      <p>Te ayudaremos a recuperar el acceso a tu cuenta</p>
    </div>

    <!-- Body -->
    <div class="recovery-body">
      <!-- Alert Messages -->
      <?php 
      if(!empty($_GET["msg"])){
        if($_GET["msg"]==1){
      ?>
        <div class="alert alert-success" id="alertMessage">
          <i class="fas fa-check-circle"></i>
          <span><strong>¡Éxito!</strong> Tus credenciales han sido enviadas al correo electrónico proporcionado.</span>
          <button type="button" class="alert-close" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times"></i>
          </button>
        </div>
      <?php
        }
        if($_GET["msg"]==2){
      ?>
        <div class="alert alert-error" id="alertMessage">
          <i class="fas fa-exclamation-circle"></i>
          <span><strong>Error:</strong> No fue encontrado un registro con el correo electrónico proporcionado.</span>
          <button type="button" class="alert-close" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times"></i>
          </button>
        </div>
      <?php 
        }
      }
      ?>

      <!-- Info Box -->
      <div class="info-box">
        <div class="info-box-header">
          <i class="fas fa-info-circle"></i>
          <h3>¿Cómo funciona?</h3>
        </div>
        <p>Ingresa tu correo electrónico registrado y te enviaremos tus credenciales de acceso. Asegúrate de revisar también tu carpeta de spam.</p>
      </div>

      <!-- Brand Logo -->
      <div style="text-align: center; margin-bottom: 32px;">
        <img src="<?= BRAND_LOGO_TEMP_URL ?>" alt="Logo <?= BRAND_NAME ?>" style="max-width: 200px; height: auto;">
      </div>

      <!-- Recovery Form -->
      <form id="recoveryForm">
        <div class="form-group">
          <label for="email" class="form-label">Correo Electrónico</label>
          <div class="input-wrapper">
            <input 
              type="email" 
              id="email" 
              name="email" 
              class="form-input" 
              placeholder="correo@ejemplo.com"
              autocomplete="email"
              required
            >
            <i class="fas fa-envelope input-icon"></i>
          </div>
          <div class="input-error-message" id="errorEmail">
            Por favor ingresa un correo electrónico válido
          </div>
        </div>

        <button type="submit" class="btn-primary" id="btnSubmit">
          <span class="btn-text">
            <i class="fas fa-paper-plane"></i>
            Enviar Credenciales
          </span>
          <div class="spinner"></div>
        </button>

        <a href="index.php" class="btn-secondary">
          <i class="fas fa-arrow-left"></i>
          <span>Volver al inicio de sesión</span>
        </a>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    // ===================================
    // ANIMATED BACKGROUND PARTICLES
    // ===================================
    function createParticles() {
      const particlesContainer = document.getElementById('particles');
      const particleCount = 15;

      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        const size = Math.random() * 60 + 20;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${Math.random() * 100}%`;
        particle.style.top = `${Math.random() * 100}%`;
        particle.style.animationDelay = `${Math.random() * 20}s`;
        particle.style.animationDuration = `${Math.random() * 10 + 15}s`;
        
        particlesContainer.appendChild(particle);
      }
    }

    createParticles();

    // ===================================
    // EMAIL VALIDATION
    // ===================================
    const emailInput = document.getElementById('email');
    const errorEmail = document.getElementById('errorEmail');

    function validateEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    }

    function validateField() {
      const email = emailInput.value.trim();
      
      if (!email) {
        emailInput.classList.add('error');
        errorEmail.textContent = 'Por favor ingresa tu correo electrónico';
        errorEmail.classList.add('show');
        return false;
      } else if (!validateEmail(email)) {
        emailInput.classList.add('error');
        errorEmail.textContent = 'Por favor ingresa un correo electrónico válido';
        errorEmail.classList.add('show');
        return false;
      } else {
        emailInput.classList.remove('error');
        errorEmail.classList.remove('show');
        return true;
      }
    }

    emailInput.addEventListener('blur', validateField);
    
    emailInput.addEventListener('input', function() {
      if (this.classList.contains('error')) {
        validateField();
      }
    });

    // ===================================
    // ASYNC FORM SUBMISSION
    // ===================================
    const recoveryForm = document.getElementById('recoveryForm');
    const btnSubmit = document.getElementById('btnSubmit');
    const loadingOverlay = document.getElementById('loadingOverlay');

    recoveryForm.addEventListener('submit', async function(e) {
      e.preventDefault();

      // Validate email
      if (!validateField()) {
        emailInput.focus();
        return;
      }

      // Show loading state
      btnSubmit.classList.add('loading');
      btnSubmit.disabled = true;
      loadingOverlay.classList.add('show');

      try {
        const formData = new FormData(this);
        
        const response = await fetch('recuperar-clave-guardar.php', {
          method: 'POST',
          body: formData
        });

        const result = await response.json();
        
        // Hide loading
        btnSubmit.classList.remove('loading');
        btnSubmit.disabled = false;
        loadingOverlay.classList.remove('show');

        if (result.success) {
          showAlert('success', result.message || 'Credenciales enviadas exitosamente. Revisa tu correo.');
          emailInput.value = '';
        } else {
          showAlert('error', result.message || 'No se encontró un usuario con ese correo electrónico.');
        }
      } catch (error) {
        console.error('Error:', error);
        btnSubmit.classList.remove('loading');
        btnSubmit.disabled = false;
        loadingOverlay.classList.remove('show');
        showAlert('error', 'Error de conexión. Por favor intenta de nuevo.');
      }
    });

    // ===================================
    // ALERT SYSTEM
    // ===================================
    function showAlert(type, message) {
      // Remove existing alerts
      const existingAlerts = document.querySelectorAll('.alert');
      existingAlerts.forEach(alert => alert.remove());

      const alertTypes = {
        'error': { icon: 'fa-exclamation-circle', class: 'alert-error' },
        'success': { icon: 'fa-check-circle', class: 'alert-success' }
      };

      const alertConfig = alertTypes[type] || alertTypes['error'];

      const alert = document.createElement('div');
      alert.className = `alert ${alertConfig.class} animate__animated animate__slideInDown`;
      alert.innerHTML = `
        <i class="fas ${alertConfig.icon}"></i>
        <span>${message}</span>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
          <i class="fas fa-times"></i>
        </button>
      `;

      const recoveryBody = document.querySelector('.recovery-body');
      recoveryBody.insertBefore(alert, recoveryBody.firstChild);

      // Auto-hide after 6 seconds
      setTimeout(() => {
        alert.classList.add('animate__fadeOut');
        setTimeout(() => alert.remove(), 500);
      }, 6000);
    }

    // ===================================
    // AUTO-HIDE ALERT FROM URL
    // ===================================
    const alertMessage = document.getElementById('alertMessage');
    if (alertMessage) {
      setTimeout(() => {
        alertMessage.classList.add('animate__animated', 'animate__fadeOut');
        setTimeout(() => alertMessage.remove(), 500);
      }, 6000);
    }
  </script>
</body>
</html>
