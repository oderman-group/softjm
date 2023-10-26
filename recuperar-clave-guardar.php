<?php
include("conexion.php");
include("usuarios/includes/funciones-para-el-sistema.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'librerias/phpmailer/Exception.php';
require 'librerias/phpmailer/PHPMailer.php';
require 'librerias/phpmailer/SMTP.php';

$consultaUsuario=mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_login='" . $_POST["email"] . "' OR usr_email='" . $_POST["email"] . "'");
$numDatos=mysqli_num_rows($consultaUsuario);
$msg=2;
if ($numDatos>0) {

  $configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1"), MYSQLI_BOTH);

  $datosUsuario = mysqli_fetch_array($consultaUsuario, MYSQLI_BOTH);

  $nuevaClave = generarClaves(8);
  $nuevaClaveSHA1 = sha1($nuevaClave);

  mysqli_query($conexionBdPrincipal,"UPDATE usuarios SET  usr_clave='".$nuevaClaveSHA1."' WHERE usr_id='" . $datosUsuario['usr_id'] . "'");

  mysqli_query($conexionBdAdmin, "INSERT INTO restaurar_clave(resc_id_usuario, resc_fec_solicitud, resc_clave_generada, resc_id_empresa) VALUES('".$datosUsuario['usr_id']."', now(), '".$nuevaClaveSHA1."', '".$datosUsuario['usr_id_empresa']."')");
  

  $fin =  '<html><body style="background-color:#E6E6E6;">';
  $fin .= '
      <center>
        <div style="font-family:arial; background:#FFF; width:600px; color:#000; text-align:justify; padding:15px; border-radius:10px;">
          Hola!<br>
          ' . strtoupper($datosUsuario['usr_nombre']) . ', a continuación sus datos de acceso son los siguientes:<br>
          <b>Usuario:</b> ' . $datosUsuario['usr_login'] . '<br>
          <b>Contraseña:</b> ' . $nuevaClave . '<br>

          Recomendamos cambiar la clave asignada automáticamente por una que usted recuerde más fácilmente.<br><br>
          
          Muchas gracias por utilizar nuestra plataforma!.
          <p align="center" style="color:#399;">
            ¡Que tengas un excelente d&iacute;a!
          </p>
        </div>
      </center>
      <p>&nbsp;</p>
    ';
  $fin .= '';
  $fin .=  '<html><body>';


  // Instantiation and passing `true` enables exceptions
  $mail = new PHPMailer(true);
  echo '<div style="display:none;">';
  try {
    $mail->SMTPDebug = 2;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'mail.orioncrm.com.co';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $configuracion['conf_email'];                     // SMTP username
    $mail->Password   = $configuracion['conf_clave_correo'];                              // SMTP password
    $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom($configuracion['conf_email'], 'JMEQUIPOS');
    $mail->addAddress($datosUsuario['usr_email'], $datosUsuario['usr_nombre']);     // Add a recipient


    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'CRM ORIÓN - Credenciales de acceso';
    $mail->Body = $fin;
    $mail->CharSet = 'UTF-8';

    $mail->send();
  } catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
  }
  echo '</div>';

  $msg=1;
}
echo '<script type="text/javascript">window.location.href="recuperar-clave.php?msg='.$msg.'";</script>';
exit();