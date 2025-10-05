<?php
include("sesion.php");
header('Content-Type: application/json; charset=utf-8');

require_once RUTA_PROYECTO.'/usuarios/class/MailerService.php';
require_once RUTA_PROYECTO.'/usuarios/class/Utilidades.php';

try {
    $consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_materiales 
    WHERE ppmt_producto='".$_POST["idProducto"]."'");

    $contadorRecursos = 1;
    $recursos = '';

    while ($materiales = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
        switch ($materiales[2]) {
            case 1: 
                $tipoMaterial = 'Documento';
                $linkMaterial = '<a href="'.REDIRECT_ROUTE.'/usuarios/files/materiales/'.$materiales[1].'" target="_blank">DESCARGAR</a>'; 
            break;
            case 2:
                $tipoMaterial = 'Vídeo'; 
                $linkMaterial = '<a href="https://www.youtube.com/watch?v='.$materiales[1].'" target="_blank">VER</a>'; 
                break;
            case 3:
                $tipoMaterial = 'Software'; 
                $linkMaterial = '<a href="'.REDIRECT_ROUTE.'/usuarios/files/materiales/'.$materiales[1].'" target="_blank">DESCARGAR</a>'; 
                break;
        }

        $recursos .= '<tr>
        <td>' . $contadorRecursos . '</td>
        <td>' . htmlspecialchars($materiales['ppmt_nombre']) . '</td>
        <td>' . $linkMaterial . '</td>
        <td>' . htmlspecialchars($tipoMaterial) . '</td>
        </tr>';

        $contadorRecursos++;
    }

    $contadorRecursos--;

    //Envíar email al encargado
    $fin = '<h1>Recursos para el cliente</h1>
        <p>
        Hola, el usuario <b>'.$datosUsuarioActual['usr_nombre'].'</b> te ha compartido unos recursos <b>('.$contadorRecursos.')</b> relacionados al producto <b>'.$_POST["nombreProducto"].'</b>. Para verlos puedes dar click sobre el nombre de estos.
        </p>

        <table border="1" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nombre</th>
                    <th>Material</th>
                    <th>Tipo</th>
                </tr>
            </thead>
            <tbody>
                ' . $recursos . '
            </tbody>
        </table>

        <p>Recuerda que contamos con una plataforma CRM donde puedes acceder y estar al día sobre tus compras, certificados, promociones de la empresa y mucho más.</p>'; // Simular el cuerpo HTML

    

    // 1. Instanciar el servicio
    $mailer = new MailerService();

    // 2. Preparar el contenido del correo usando la plantilla
    $subject =  $contadorRecursos." Recursos del producto ".$_POST["nombreProducto"]."";

    $emailData = [
        'subject'       => $subject,
        'app_name'      => $_SESSION["dataAdicional"]["nombre_empresa"], // Reutiliza el nombre de la empresa
        'content'       => $fin, // Tu contenido HTML aquí
        'button_link'   => REDIRECT_ROUTE.'/clientes/index.php', // Si no necesitas botón, dejar vacío
        'button_text'   => 'Ir al sistema', // Si no necesitas botón, dejar vacío
        'support_email' => 'soporte@jmequipos.com',
    ];

    $htmlBody = renderEmailTemplate(RUTA_PROYECTO.'/usuarios/plantillas_email/notificaciones.html', $emailData);
    $textBody = strip_tags($htmlBody); // Siempre genera una versión de texto plano

    $asesorDatos = [
        'usr_email' => 'jhooderman@gmail.com'
    ];

    // 3. Definir destinatarios adicionales (asesor en CC)
    $ccRecipients = [];
    if (!empty($asesorDatos['usr_email'])) {
        $ccRecipients[] = $asesorDatos['usr_email'];
    }

    // 4. Enviar el correo usando el método sendEmail
    if ($mailer->sendEmail(
        $_POST["emailCliente"],
        'Cliente de JM',
        $subject,
        $htmlBody,
        $textBody,
        [], // No hay adjuntos en este ejemplo
        $ccRecipients // El asesor en CC
    )) {
        Utilidades::redirect('productos-sop.php', 16, 'msg');
    } else {
        throw new Exception('Falló el envío de Email');
    }
} catch (Exception $e) {
    Utilidades::redirect('productos.php', 3, 'error', $e->getMessage());
    exit();
}
