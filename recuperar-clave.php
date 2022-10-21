<?php
//include("conexion.php");
//$configuracion = mysql_fetch_array(mysql_query("SELECT * FROM configuracion WHERE conf_id=1",$conexion));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ORIÓN - <?= $configuracion['conf_empresa']; ?></title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="assets-login/vendors/iconfonts/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="assets-login/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="assets-login/vendors/css/vendor.bundle.addons.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="assets-login/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="assets-login/images/favicon.png" />


  <script src="https://www.google.com/recaptcha/api.js" async defer></script>


</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">

        
          <div class="col-lg-6 d-flex align-items-center justify-content-center">

          

            <div class="auth-form-transparent text-left p-3">

            <?php if($_GET["msg"]==1){?>
	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<i class="icon-ok"></i><strong>Exito!</strong> Tus credenciales de acceso han sido enviadas al correo electrónico proporcionado. Verifica por favor.
	</div>
<?php }?>
	
	<?php if($_GET["msg"]==2){?>
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<i class="icon-exclamation-sign"></i><strong>Error!</strong> No fue encontrado un registro de usuario con el correo electrónico proporcionado.
	</div>
<?php }?>
              <div class="brand-logo">
                <img src="usuarios/files/<?= $configuracion['conf_logo']; ?>" alt="<?= $configuracion['conf_empresa']; ?>">
              </div>
              <h4>Recuperar contraseña</h4>
              <h6 class="font-weight-light">Ingresa tu email registrado</h6>
              <form class="pt-3" action="sql.php" method="post" id="demo-form">
                <input type="hidden" name="idSql" value="2">
                <input type="hidden" name="bd" value="odermancom_jm_crm">

                <div class="form-group">
                  <label for="exampleInputEmail">Email</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="fa fa-user text-primary"></i>
                      </span>
                    </div>
                    <input type="email" class="form-control form-control-lg border-left-0" placeholder="Email" name="email">
                  </div>
                </div>

                

                

                  
                <div class="my-2 d-flex justify-content-between align-items-center">
                  
                  <a href="index.php" class="auth-link text-black">Volver al inicio</a>
                </div>
				
                <!--
        <div class="g-recaptcha" data-sitekey="6LcAsMUZAAAAAPoMH8oNDtCdYkF35qG5PZu8Xy2T"></div>
        <br>-->
                <div class="my-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">RECUPERAR CLAVE</button>
                  <!--<a class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" href="../../index-2.html">ENTRAR</a>-->
                </div>

                <!-- 
                <div class="mb-2 d-flex">
                  <button type="button" class="btn btn-facebook auth-form-btn flex-grow mr-1">
                    <i class="fab fa-facebook-f mr-2"></i>Facebook
                  </button>
                  <button type="button" class="btn btn-google auth-form-btn flex-grow ml-1">
                    <i class="fab fa-google mr-2"></i>Google
                  </button>
                </div>
				  
                <div class="text-center mt-4 font-weight-light">
                  Don't have an account? <a href="register-2.html" class="text-primary">Create</a>
                </div>
				-->

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