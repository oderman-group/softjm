<?php
include("../conexion.php");
include("../usuarios/includes/funciones-para-el-sistema.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../librerias/phpmailer/Exception.php';
require '../librerias/phpmailer/PHPMailer.php';
require '../librerias/phpmailer/SMTP.php';

$consultaCliente=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_usuario='" . $_POST["email"] . "' OR cli_email='" . $_POST["email"] . "'");
$numDatos=mysqli_num_rows($consultaCliente);
$msg=2;
if ($numDatos>0) {

  $datosCliente = mysqli_fetch_array($consultaCliente, MYSQLI_BOTH);

  $nuevaClave = generarClaves(8);

  mysqli_query($conexionBdPrincipal,"UPDATE clientes SET  cli_clave='".$nuevaClave."' WHERE cli_id='" . $datosCliente['cli_id'] . "'");

  mysqli_query($conexionBdAdmin, "INSERT INTO restaurar_clave(resc_id_usuario, resc_fec_solicitud, resc_clave_generada, resc_id_empresa) VALUES('".$datosCliente['cli_id']."', now(), '".$nuevaClave."', '".$datosCliente['cli_id_empresa']."')");
  

  $fin =  '<html><body style="background-color:#E6E6E6;">';
  $fin .= '
      <center>
        <div style="font-family:arial; background:#FFF; width:600px; color:#000; text-align:justify; padding:15px; border-radius:10px;">
          Hola!<br>
          ' . strtoupper($datosCliente['cli_nombre']) . ', a continuación sus datos de acceso son los siguientes:<br>
          <b>Usuario:</b> ' . $datosCliente['cli_usuario'] . '<br>
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
  try {
    $mail->SMTPDebug = 0;                                                           // Enable verbose debug output
    $mail->isSMTP();                                                                // Set mailer to use SMTP
    $mail->Host       = EMAIL_SERVER;                                               // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                                       // Enable SMTP authentication
    $mail->Username   = EMAIL_USER;                                                 // SMTP username
    $mail->Password   = EMAIL_PASSWORD;                                             // SMTP password
    $mail->SMTPSecure = 'ssl';                                                      // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 465;                                                        // TCP port to connect to

    //Recipients
    $mail->setFrom(EMAIL_SENDER, NAME_SENDER);
    $mail->addAddress($datosCliente['cli_email'], $datosCliente['cli_nombre']);     // Add a recipient


    // Content
    $mail->isHTML(true);                                                            // Set email format to HTML
    $mail->Subject = 'CRM ORIÓN - Credenciales de acceso';
    $mail->Body = $fin;
    $mail->CharSet = 'UTF-8';

    $mail->send();
  } catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
  }

  $msg=1;
}
echo '<script type="text/javascript">window.location.href="recuperar-clave.php?msg='.$msg.'";</script>';
exit();