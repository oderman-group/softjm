<?php
	include("../conexion.php");
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ORIÓN - Clientes</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets-login/vendors/iconfonts/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="../assets-login/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets-login/vendors/css/vendor.bundle.addons.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets-login/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../assets-login/images/favicon.png" />

  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script src="https://kit.fontawesome.com/e84fa1cf78.js" crossorigin="anonymous"></script>
</head>
<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
          <div class="col-lg-6 d-flex align-items-center justify-content-center">
          
            <div class="auth-form-transparent text-left p-3">
              <div class="brand-logo">
                <img src="../usuarios/files/orion-600.png" alt="Logo Orion">
              </div>
              <h4>Bienvenido a ORION</h4>
              <h6 class="font-weight-light">Ingreso al Área de Clientes</h6>
              <form class="pt-3" action="autentico.php" method="post" id="demo-form">

                <input type="hidden" name="idseg" value="<?= $idSeguimiento; ?>">

                <input type="hidden" name="bd" value="<?=MAINBD;?>">

                <div class="form-group">
                  <label for="exampleInputEmail">Usuario</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="fa fa-user text-primary"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control form-control-lg border-left-0" placeholder="Usuario" name="Usuario">
                  </div>
                </div>

                <div class="form-group">
                  <label for="exampleInputPassword">Contraseña</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="fa fa-lock text-primary"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control form-control-lg border-left-0" placeholder="Contraseña" name="Clave" id="passwordInput">
                    <div class="input-group-prepend bg-transparent" onclick="mostrarClave()">
                      <span class="input-group-text bg-transparent border-left-0">
                      <i class="fa-solid fa-eye" id="icoVer"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <script>
                    function mostrarClave() {
                        var campo = document.getElementById("passwordInput");
                        var icoVer = document.getElementById("icoVer");

                        if (campo.type === "password") {
                            campo.type = "text";
                            icoVer.classList.remove("fa-eye");
                            icoVer.classList.add("fa-eye-slash");
                        } else {
                            campo.type = "password";
                            icoVer.classList.remove("fa-eye-slash");
                            icoVer.classList.add("fa-eye");
                        }
                    }
                </script>
                <div class="my-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">ENTRAR</button>
                </div>
              </form>
            </div>
          </div>
          <div class="col-lg-6 login-half-bg d-flex flex-row">
            <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; 2019 Todos los derechos reservados.</p>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="assets-login/vendors/js/vendor.bundle.base.js"></script>
  <script src="assets-login/vendors/js/vendor.bundle.addons.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="assets-login/js/off-canvas.js"></script>
  <script src="assets-login/js/hoverable-collapse.js"></script>
  <script src="assets-login/js/misc.js"></script>
  <script src="assets-login/js/settings.js"></script>
  <script src="assets-login/js/todolist.js"></script>
  <!-- endinject -->
</body>
</html>