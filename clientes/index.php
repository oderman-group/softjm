<?php
include("../conexion.php");

$idSeguimiento = '';
if(isset($_GET["idseg"]) and is_numeric($_GET["idseg"])){
    $idSeguimiento = $_GET["idseg"];
}

$errorCode = isset($_GET['error']) ? $_GET['error'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Portal de Clientes ORION - Acceso al área de clientes">
  <title>ORION | Portal de Clientes</title>
  
  <link rel="shortcut icon" href="../assets-login/images/favicon.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary-color: #48bb78;
      --primary-dark: #38a169;
      --primary-light: #68d391;
      --secondary-color: #38b2ac;
      --success-color: #48bb78;
      --error-color: #f56565;
      --warning-color: #ed8936;
      --text-primary: #2d3748;
      --text-secondary: #718096;
      --bg-white: #ffffff;
      --border-color: #e2e8f0;
      --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #48bb78 0%, #38b2ac 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
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

    .login-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 1100px;
      display: grid;
      grid-template-columns: 1fr 1fr;
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

    .login-form-panel {
      padding: 60px 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .brand-logo {
      margin-bottom: 40px;
      text-align: center;
    }

    .brand-logo img {
      max-width: 180px;
      height: auto;
      filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
    }

    .client-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: linear-gradient(135deg, #48bb78 0%, #38b2ac 100%);
      color: white;
      padding: 8px 20px;
      border-radius: 24px;
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 24px;
      box-shadow: 0 4px 6px -1px rgba(72, 187, 120, 0.3);
    }

    .client-badge i {
      font-size: 16px;
    }

    .welcome-text h1 {
      font-size: 32px;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 8px;
      background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .welcome-text p {
      font-size: 16px;
      color: var(--text-secondary);
      font-weight: 400;
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
    }

    .alert-close:hover {
      opacity: 1;
    }

    .login-form {
      margin-top: 32px;
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
      box-shadow: 0 0 0 4px rgba(72, 187, 120, 0.1);
    }

    .form-input:focus + .input-icon {
      color: var(--primary-color);
    }

    .form-input.error {
      border-color: var(--error-color);
    }

    .input-error-message {
      display: none;
      font-size: 13px;
      color: var(--error-color);
      margin-top: 6px;
    }

    .input-error-message.show {
      display: block;
    }

    .password-toggle {
      position: absolute;
      right: 16px;
      background: none;
      border: none;
      color: var(--text-secondary);
      font-size: 18px;
      cursor: pointer;
      padding: 8px;
      transition: color 0.3s ease;
    }

    .password-toggle:hover {
      color: var(--primary-color);
    }

    .form-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }

    .remember-me {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      color: var(--text-secondary);
      cursor: pointer;
    }

    .remember-me input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: var(--primary-color);
    }

    .forgot-password {
      font-size: 14px;
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .forgot-password:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .btn-login {
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
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .btn-login:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .btn-login.loading .btn-text {
      display: none;
    }

    .btn-login .spinner {
      display: none;
      width: 20px;
      height: 20px;
      border: 3px solid rgba(255, 255, 255, 0.3);
      border-top-color: white;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
      margin: 0 auto;
    }

    .btn-login.loading .spinner {
      display: block;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 16px;
      margin: 32px 0;
      color: var(--text-secondary);
      font-size: 14px;
      font-weight: 500;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border-color);
    }

    .social-login {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .btn-social {
      padding: 14px 20px;
      border: 2px solid var(--border-color);
      border-radius: 12px;
      background: var(--bg-white);
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      position: relative;
    }

    .btn-social i {
      font-size: 20px;
    }

    .btn-social.google {
      color: #4285f4;
    }

    .btn-social.google:hover {
      background: #4285f4;
      color: white;
      border-color: #4285f4;
      transform: translateY(-2px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .btn-social.facebook {
      color: #1877f2;
    }

    .btn-social.facebook:hover {
      background: #1877f2;
      color: white;
      border-color: #1877f2;
      transform: translateY(-2px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .btn-social .badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: var(--warning-color);
      color: white;
      font-size: 10px;
      padding: 3px 8px;
      border-radius: 12px;
      font-weight: 700;
    }

    .login-info-panel {
      background: linear-gradient(135deg, #48bb78 0%, #38b2ac 100%);
      padding: 60px 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .login-info-panel::before {
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

    .info-content {
      position: relative;
      z-index: 1;
      text-align: center;
    }

    .info-icon {
      width: 120px;
      height: 120px;
      margin: 0 auto 32px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      backdrop-filter: blur(10px);
    }

    .info-icon i {
      font-size: 56px;
      color: white;
    }

    .info-content h2 {
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 16px;
      line-height: 1.2;
    }

    .info-content p {
      font-size: 16px;
      line-height: 1.6;
      opacity: 0.95;
      margin-bottom: 24px;
    }

    .features {
      display: grid;
      gap: 16px;
      margin-top: 32px;
      text-align: left;
    }

    .feature-item {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(255, 255, 255, 0.1);
      padding: 16px 20px;
      border-radius: 12px;
      backdrop-filter: blur(10px);
      transition: transform 0.3s ease;
    }

    .feature-item:hover {
      transform: translateX(8px);
    }

    .feature-item i {
      font-size: 24px;
      color: white;
    }

    .feature-item span {
      font-size: 15px;
      font-weight: 500;
    }

    .info-footer {
      margin-top: 48px;
      padding-top: 32px;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
      text-align: center;
      font-size: 14px;
      opacity: 0.9;
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

    @media (max-width: 992px) {
      .login-container {
        grid-template-columns: 1fr;
        max-width: 500px;
      }
      .login-info-panel {
        display: none;
      }
      .login-form-panel {
        padding: 40px 30px;
      }
    }

    @media (max-width: 576px) {
      body {
        padding: 10px;
      }
      .login-form-panel {
        padding: 30px 20px;
      }
      .brand-logo img {
        max-width: 150px;
      }
      .welcome-text h1 {
        font-size: 26px;
      }
      .social-login {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <div class="particles" id="particles"></div>
  <div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
  </div>

  <div class="login-container">
    <div class="login-form-panel">
      <div class="brand-logo">
        <img src="../usuarios/files/orion-600.png" alt="Logo ORION">
      </div>

      <div class="client-badge">
        <i class="fas fa-users"></i>
        <span>Portal de Clientes</span>
      </div>

      <?php if($errorCode): ?>
        <div class="alert alert-error" id="alertMessage">
          <i class="fas fa-exclamation-circle"></i>
          <span>
            <?php
            switch ($errorCode) {
              case 1:
                echo 'El usuario no existe en nuestro sistema';
                break;
              case 2:
                echo 'La contraseña ingresada no es correcta';
                break;
              case 3:
                echo 'Has superado el límite de intentos fallidos';
                break;
              case 4:
                echo 'Tu usuario se encuentra bloqueado. Contacta al administrador';
                break;
              default:
                echo 'Ha ocurrido un error. Por favor intenta de nuevo';
                break;
            }
            ?>
          </span>
          <button type="button" class="alert-close" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times"></i>
          </button>
        </div>
      <?php endif; ?>

      <div class="welcome-text">
        <h1>¡Bienvenido!</h1>
        <p>Accede a tu área de cliente</p>
      </div>

      <form class="login-form" id="loginForm">
        <input type="hidden" name="idseg" value="<?= $idSeguimiento; ?>">
        <input type="hidden" name="bd" value="<?=MAINBD;?>">

        <div class="form-group">
          <label for="usuario" class="form-label">Usuario</label>
          <div class="input-wrapper">
            <input 
              type="text" 
              id="usuario" 
              name="Usuario" 
              class="form-input" 
              placeholder="Ingresa tu usuario"
              autocomplete="username"
              required
            >
            <i class="fas fa-user input-icon"></i>
          </div>
          <div class="input-error-message" id="errorUsuario">
            Por favor ingresa tu usuario
          </div>
        </div>

        <div class="form-group">
          <label for="password" class="form-label">Contraseña</label>
          <div class="input-wrapper">
            <input 
              type="password" 
              id="password" 
              name="Clave" 
              class="form-input" 
              placeholder="Ingresa tu contraseña"
              autocomplete="current-password"
              required
            >
            <i class="fas fa-lock input-icon"></i>
            <button type="button" class="password-toggle" id="togglePassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
          <div class="input-error-message" id="errorPassword">
            Por favor ingresa tu contraseña
          </div>
        </div>

        <div class="form-footer">
          <label class="remember-me">
            <input type="checkbox" name="remember" id="remember">
            <span>Recordarme</span>
          </label>
          <a href="recuperar-clave.php" class="forgot-password">¿Olvidaste tu clave?</a>
        </div>

        <button type="submit" class="btn-login" id="btnLogin">
          <span class="btn-text">Iniciar Sesión</span>
          <div class="spinner"></div>
        </button>

        <div class="divider">
          <span>O continúa con</span>
        </div>

        <div class="social-login">
          <button type="button" class="btn-social google" id="btnGoogle">
            <i class="fab fa-google"></i>
            <span>Google</span>
            <span class="badge">Próximamente</span>
          </button>
          <button type="button" class="btn-social facebook" id="btnFacebook">
            <i class="fab fa-facebook-f"></i>
            <span>Facebook</span>
            <span class="badge">Próximamente</span>
          </button>
        </div>
      </form>
    </div>

    <div class="login-info-panel">
      <div class="info-content">
        <div class="info-icon">
          <i class="fas fa-shield-alt"></i>
        </div>
        <h2>Portal Seguro</h2>
        <p>Accede de forma segura a tu información, cotizaciones, pedidos y más</p>
        
        <div class="features">
          <div class="feature-item">
            <i class="fas fa-file-invoice"></i>
            <span>Consulta tus cotizaciones</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-shopping-cart"></i>
            <span>Revisa tus pedidos</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-headset"></i>
            <span>Soporte personalizado</span>
          </div>
          <div class="feature-item">
            <i class="fas fa-download"></i>
            <span>Descarga documentos</span>
          </div>
        </div>

        <div class="info-footer">
          <p>Copyright &copy; <?= date('Y'); ?> ORION. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
  </div>

  <script>
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

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      
      const icon = this.querySelector('i');
      icon.classList.toggle('fa-eye');
      icon.classList.toggle('fa-eye-slash');
    });

    function validateField(input, errorElement) {
      if (!input.value.trim()) {
        input.classList.add('error');
        errorElement.classList.add('show');
        return false;
      } else {
        input.classList.remove('error');
        errorElement.classList.remove('show');
        return true;
      }
    }

    const usuarioInput = document.getElementById('usuario');
    const errorUsuario = document.getElementById('errorUsuario');
    const errorPassword = document.getElementById('errorPassword');

    usuarioInput.addEventListener('blur', function() {
      validateField(this, errorUsuario);
    });

    passwordInput.addEventListener('blur', function() {
      validateField(this, errorPassword);
    });

    const loginForm = document.getElementById('loginForm');
    const btnLogin = document.getElementById('btnLogin');
    const loadingOverlay = document.getElementById('loadingOverlay');

    loginForm.addEventListener('submit', async function(e) {
      e.preventDefault();

      const isUsuarioValid = validateField(usuarioInput, errorUsuario);
      const isPasswordValid = validateField(passwordInput, errorPassword);

      if (!isUsuarioValid || !isPasswordValid) {
        return;
      }

      btnLogin.classList.add('loading');
      btnLogin.disabled = true;
      loadingOverlay.classList.add('show');

      try {
        const formData = new FormData(this);
        
        const response = await fetch('autentico.php', {
          method: 'POST',
          body: formData
        });

        const contentType = response.headers.get('content-type');
        
        if (contentType && contentType.includes('application/json')) {
          const result = await response.json();
          
          if (result.success) {
            window.location.href = result.redirect || 'registrados/index.php';
          } else {
            showAlert('error', result.message || 'Error al iniciar sesión');
            btnLogin.classList.remove('loading');
            btnLogin.disabled = false;
            loadingOverlay.classList.remove('show');
          }
        } else {
          window.location.href = response.url;
        }
      } catch (error) {
        console.error('Error:', error);
        showAlert('error', 'Error de conexión. Por favor intenta de nuevo.');
        btnLogin.classList.remove('loading');
        btnLogin.disabled = false;
        loadingOverlay.classList.remove('show');
      }
    });

    function showAlert(type, message) {
      const existingAlerts = document.querySelectorAll('.alert');
      existingAlerts.forEach(alert => alert.remove());

      const alert = document.createElement('div');
      alert.className = `alert alert-${type} animate__animated animate__slideInDown`;
      alert.innerHTML = `
        <i class="fas fa-exclamation-circle"></i>
        <span>${message}</span>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
          <i class="fas fa-times"></i>
        </button>
      `;

      const welcomeText = document.querySelector('.welcome-text');
      welcomeText.insertAdjacentElement('beforebegin', alert);

      setTimeout(() => {
        alert.classList.add('animate__fadeOut');
        setTimeout(() => alert.remove(), 500);
      }, 5000);
    }

    document.getElementById('btnGoogle').addEventListener('click', function() {
      showAlert('warning', 'La autenticación con Google estará disponible próximamente');
    });

    document.getElementById('btnFacebook').addEventListener('click', function() {
      showAlert('warning', 'La autenticación con Facebook estará disponible próximamente');
    });

    const rememberCheckbox = document.getElementById('remember');
    const savedUsername = localStorage.getItem('rememberedUsernameCliente');

    if (savedUsername) {
      usuarioInput.value = savedUsername;
      rememberCheckbox.checked = true;
    }

    loginForm.addEventListener('submit', function() {
      if (rememberCheckbox.checked) {
        localStorage.setItem('rememberedUsernameCliente', usuarioInput.value);
      } else {
        localStorage.removeItem('rememberedUsernameCliente');
      }
    });
  </script>
</body>
</html>
