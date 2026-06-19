<?php
include("conexion.php");

$msjError = '';
if (isset($_GET['error'])) {
  switch ($_GET['error']) {
    case 1: $msjError = 'El usuario no existe.'; break;
    case 2: $msjError = 'La clave no es correcta.'; break;
    case 3: $msjError = 'Los intentos fallidos de acceso superan el límite.'; break;
    case 4: $msjError = 'Su usuario se encuentra bloqueado.'; break;
    default: $msjError = 'Error de acceso.'; break;
  }
}

$idSeguimiento = isset($_GET['idseg']) && is_numeric($_GET['idseg']) ? $_GET['idseg'] : '';
$redirectTo = !empty($_GET['redirect_to']) && is_string($_GET['redirect_to']) ? trim($_GET['redirect_to']) : '';

$showCaptcha = isset($_GET['error']) && (int)$_GET['error'] === 3;
if ($showCaptcha) {
  $numA1 = rand(1, 10);
  $numA2 = rand(1, 10);
  $resultadoA = $numA1 + $numA2;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Iniciar sesión · ORION CRM</title>
  <link rel="shortcut icon" href="assets-login/images/favicon.png">
  <link rel="stylesheet" href="assets-login/css/crm-auth.css">
  <script src="https://kit.fontawesome.com/e84fa1cf78.js" crossorigin="anonymous"></script>
</head>
<body class="crm-auth-page">
  <div class="crm-auth-wrapper">
    <main class="crm-auth-form-panel">
      <div class="crm-auth-card">
        <img src="usuarios/files/orion-600.png" alt="ORION" class="crm-auth-logo">

        <h1 class="crm-auth-title">Bienvenido a ORION</h1>
        <p class="crm-auth-subtitle">Ingresa tu usuario y contraseña para acceder al CRM.</p>

        <?php if ($msjError !== ''): ?>
          <div class="crm-alert crm-alert-error" role="alert">
            <span class="crm-alert-icon" aria-hidden="true"><i class="fa-solid fa-circle-exclamation"></i></span>
            <span><?= htmlspecialchars($msjError) ?></span>
          </div>
        <?php endif; ?>

        <form class="crm-auth-form" action="autentico.php" method="post" id="demo-form">
          <input type="hidden" name="idseg" value="<?= htmlspecialchars($idSeguimiento) ?>">
          <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($redirectTo) ?>">
          <input type="hidden" name="bd" value="<?= htmlspecialchars(MAINBD ?? '') ?>">

          <div class="crm-field">
            <label class="crm-label" for="crm-user">Usuario</label>
            <div class="crm-input-wrap">
              <span class="crm-input-icon" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
              <input type="text" id="crm-user" name="Usuario" placeholder="Usuario" autocomplete="username" autofocus>
            </div>
          </div>

          <div class="crm-field">
            <label class="crm-label" for="crm-password">Contraseña</label>
            <div class="crm-input-wrap">
              <span class="crm-input-icon" aria-hidden="true"><i class="fa-solid fa-lock"></i></span>
              <input type="password" id="crm-password" name="Clave" placeholder="Contraseña" autocomplete="current-password">
              <button type="button" class="crm-toggle-password" onclick="crmTogglePassword()" aria-label="Mostrar u ocultar contraseña">
                <i class="fa-solid fa-eye" id="crm-ico-ver" aria-hidden="true"></i>
              </button>
            </div>
          </div>

          <?php if ($showCaptcha): ?>
            <div class="crm-captcha-box">
              <p>Valida que no eres un robot</p>
              <p style="font-weight: 400; color: inherit;">Escribe el resultado de: <strong><?= $numA1 ?> + <?= $numA2 ?></strong></p>
              <input type="hidden" name="sumaReal" value="<?= md5($resultadoA) ?>">
              <input type="text" name="suma" placeholder="Resultado" required autocomplete="off" style="margin-top: 0.5rem;">
            </div>
          <?php endif; ?>

          <div class="crm-auth-links">
            <a href="recuperar-clave.php" class="crm-auth-link">¿Olvidaste tu clave?</a>
          </div>

          <button type="submit" class="crm-btn-primary">Entrar</button>
        </form>
      </div>
    </main>

    <aside class="crm-auth-brand-panel">
      <div class="crm-auth-brand-content">
        <p class="crm-auth-brand-quote">Gestiona clientes, pedidos e inventario en un solo lugar.</p>
        <p class="crm-auth-brand-desc">Plataforma CRM profesional para equipos que buscan eficiencia y control.</p>
      </div>
      <footer class="crm-auth-footer">
        &copy; <?= date('Y') ?> ORION CRM. Todos los derechos reservados.
      </footer>
    </aside>
  </div>

  <script>
    function crmTogglePassword() {
      var campo = document.getElementById('crm-password');
      var icono = document.querySelector('#crm-ico-ver');
      if (campo.type === 'password') {
        campo.type = 'text';
        icono.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        campo.type = 'password';
        icono.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }
  </script>
</body>
</html>
