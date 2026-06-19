<?php
$msgSuccess = isset($_GET['msg']) && (int)$_GET['msg'] === 1;
$msgError   = isset($_GET['msg']) && (int)$_GET['msg'] === 2;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Recuperar contraseña · ORION CRM</title>
  <link rel="shortcut icon" href="assets-login/images/favicon.png">
  <link rel="stylesheet" href="assets-login/css/crm-auth.css">
  <script src="https://kit.fontawesome.com/e84fa1cf78.js" crossorigin="anonymous"></script>
</head>
<body class="crm-auth-page">
  <div class="crm-auth-wrapper">
    <main class="crm-auth-form-panel">
      <div class="crm-auth-card">
        <img src="usuarios/files/orion-600.png" alt="ORION" class="crm-auth-logo">

        <h1 class="crm-auth-title">Recuperar contraseña</h1>
        <p class="crm-auth-subtitle">Ingresa tu usuario o correo registrado y te enviaremos las instrucciones.</p>

        <?php if ($msgSuccess): ?>
          <div class="crm-alert crm-alert-success" role="alert">
            <span class="crm-alert-icon" aria-hidden="true"><i class="fa-solid fa-circle-check"></i></span>
            <span><strong>Éxito.</strong> Tus credenciales han sido enviadas al correo proporcionado. Revisa tu bandeja de entrada.</span>
          </div>
        <?php endif; ?>

        <?php if ($msgError): ?>
          <div class="crm-alert crm-alert-error" role="alert">
            <span class="crm-alert-icon" aria-hidden="true"><i class="fa-solid fa-circle-exclamation"></i></span>
            <span>No se encontró un usuario con el correo electrónico proporcionado.</span>
          </div>
        <?php endif; ?>

        <form class="crm-auth-form" action="recuperar-clave-guardar.php" method="post" id="demo-form">
          <div class="crm-field">
            <label class="crm-label" for="crm-email">Usuario o correo electrónico</label>
            <div class="crm-input-wrap">
              <span class="crm-input-icon" aria-hidden="true"><i class="fa-solid fa-envelope"></i></span>
              <input type="text" id="crm-email" name="email" placeholder="Usuario o correo electrónico" autocomplete="email" autofocus>
            </div>
          </div>

          <div class="crm-auth-links">
            <a href="index.php" class="crm-auth-link">Volver al inicio de sesión</a>
          </div>

          <button type="submit" class="crm-btn-primary">Recuperar clave</button>
        </form>
      </div>
    </main>

    <aside class="crm-auth-brand-panel">
      <div class="crm-auth-brand-content">
        <p class="crm-auth-brand-quote">Recupera el acceso a tu cuenta de forma segura.</p>
        <p class="crm-auth-brand-desc">Te enviaremos un correo con las instrucciones para restablecer tu contraseña.</p>
      </div>
      <footer class="crm-auth-footer">
        &copy; <?= date('Y') ?> ORION CRM. Todos los derechos reservados.
      </footer>
    </aside>
  </div>
</body>
</html>
