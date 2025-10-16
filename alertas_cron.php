<?php
// Script para alertas de tickets y seguimientos vencidos
// Ejecutar via cron job

require_once("constantes.php");

$conexion = new mysqli(SERVER, USER, PASS, MAINBD);

$configuracion = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM configuracion WHERE conf_id=1"));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'librerias/phpmailer/Exception.php';
require 'librerias/phpmailer/PHPMailer.php';
require 'librerias/phpmailer/SMTP.php';

// Función para enviar email
function enviarEmail($asunto, $mensaje, $destinatarios) {
    global $configuracion;

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'mail.orioncrm.com.co';
        $mail->SMTPAuth = true;
        $mail->Username = $configuracion['conf_email'];
        $mail->Password = $configuracion['conf_clave_correo'];
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom($configuracion['conf_email'], 'JMEQUIPOS - Alertas');
        foreach ($destinatarios as $email => $nombre) {
            $mail->addAddress($email, $nombre);
        }

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;
        $mail->CharSet = 'UTF-8';

        $mail->send();
        echo "Email enviado: $asunto\n";
        return true;
    } catch (Exception $e) {
        echo "Error enviando email: {$mail->ErrorInfo}\n";
        return false;
    }
}

// Función para insertar notificación interna
function insertarNotificacion($titulo, $mensaje, $usuario) {
    global $conexion;
    mysqli_query($conexion, "INSERT INTO notificaciones (not_asunto, not_usuario, not_visto, not_estado, not_fecha) VALUES ('$titulo - $mensaje', '$usuario', 0, 1, now())");
}

// 1. Alertas para tickets sin respuesta
$horasTicket = $configuracion['conf_tiempo_ticket_sin_respuesta'];
$emailsEnviados = 0;
if ($horasTicket > 0) {
    $consultaTickets = mysqli_query($conexion, "
        SELECT tik.*, usr.usr_email, usr.usr_nombre,
               MAX(cseg.cseg_fecha_contacto) as ultima_respuesta
        FROM clientes_tikets tik
        INNER JOIN usuarios usr ON usr.usr_id = tik.tik_usuario_responsable
        LEFT JOIN cliente_seguimiento cseg ON cseg.cseg_tiket = tik.tik_id
        WHERE tik.tik_estado = 1 AND tik.tik_notificado = 0
        GROUP BY tik.tik_id
        HAVING TIMESTAMPDIFF(HOUR, COALESCE(ultima_respuesta, tik.tik_fecha_creacion), NOW()) > $horasTicket
        LIMIT 10
    ");

    while ($ticket = mysqli_fetch_array($consultaTickets) && $emailsEnviados < 10) {
        $titulo = "Ticket sin respuesta - ID: " . $ticket['tik_id'];
        $mensaje = "
        <div style='background-color:{$configuracion["conf_fondo_boletin"]};'>
            <center>
                <p align='center'><img src='{$configuracion["conf_url_encuestas"]}/usuarios/files/{$configuracion["conf_logo"]}' width='350'></p>
                <div style='font-family:arial; background:{$configuracion["conf_fondo_mensaje"]}; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;'>
                    <p style='color:{$configuracion["conf_color_letra"]};'>
                        El ticket <b>{$ticket['tik_id']}</b> - {$ticket['tik_asunto_principal']} lleva más de {$horasTicket} horas sin respuesta.<br>
                        Responsable: {$ticket['usr_nombre']}<br>
                        Fecha de creación: {$ticket['tik_fecha_creacion']}
                    </p>
                </div>
            </center>
        </div>
        ";

        // Notificación interna
        insertarNotificacion($titulo, "El ticket {$ticket['tik_id']} lleva más de {$horasTicket} horas sin respuesta.", $ticket['tik_usuario_responsable']);

        // Email al responsable
        if (enviarEmail($titulo, $mensaje, [$ticket['usr_email'] => $ticket['usr_nombre']])) {
            $emailsEnviados++;
        }

        // Marcar como notificado
        mysqli_query($conexion, "UPDATE clientes_tikets SET tik_notificado = 1 WHERE tik_id = '{$ticket['tik_id']}'");
    }
}

// 2. Alertas para seguimientos vencidos
$horasSeguimiento = $configuracion['conf_tiempo_tareas_vencidas'];
if ($horasSeguimiento > 0) {
    $consultaSeguimientos = mysqli_query($conexion, "
        SELECT cseg.*, usr.usr_email, usr.usr_nombre, cli.cli_nombre, tik.tik_asunto_principal
        FROM cliente_seguimiento cseg
        INNER JOIN usuarios usr ON usr.usr_id = cseg.cseg_usuario_encargado
        INNER JOIN clientes cli ON cli.cli_id = cseg.cseg_cliente
        INNER JOIN clientes_tikets tik ON tik.tik_id = cseg.cseg_tiket
        WHERE (cseg.cseg_realizado IS NULL OR cseg.cseg_realizado = 0) AND cseg.cseg_notificado = 0
        AND TIMESTAMPDIFF(HOUR, cseg.cseg_fecha_proximo_contacto, NOW()) > $horasSeguimiento
        LIMIT 10
    ");

    while ($seguimiento = mysqli_fetch_array($consultaSeguimientos) && $emailsEnviados < 10) {
        $titulo = "Seguimiento vencido - ID: " . $seguimiento['cseg_id'];
        $mensaje = "
        <div style='background-color:{$configuracion["conf_fondo_boletin"]};'>
            <center>
                <p align='center'><img src='{$configuracion["conf_url_encuestas"]}/usuarios/files/{$configuracion["conf_logo"]}' width='350'></p>
                <div style='font-family:arial; background:{$configuracion["conf_fondo_mensaje"]}; width:800px; color:#000; text-align:justify; padding:15px; border-radius:5px;'>
                    <p style='color:{$configuracion["conf_color_letra"]};'>
                        El seguimiento <b>{$seguimiento['cseg_id']}</b> para el cliente {$seguimiento['cli_nombre']} lleva más de {$horasSeguimiento} horas vencido.<br>
                        Ticket: {$seguimiento['tik_asunto_principal']}<br>
                        Fecha próximo contacto: {$seguimiento['cseg_fecha_proximo_contacto']}<br>
                        Encargado: {$seguimiento['usr_nombre']}
                    </p>
                </div>
            </center>
        </div>
        ";

        // Notificación interna
        insertarNotificacion($titulo, "El seguimiento {$seguimiento['cseg_id']} para {$seguimiento['cli_nombre']} lleva más de {$horasSeguimiento} horas vencido.", $seguimiento['cseg_usuario_encargado']);

        // Email al encargado
        if (enviarEmail($titulo, $mensaje, [$seguimiento['usr_email'] => $seguimiento['usr_nombre']])) {
            $emailsEnviados++;
        }

        // Marcar como notificado
        mysqli_query($conexion, "UPDATE cliente_seguimiento SET cseg_notificado = 1 WHERE cseg_id = '{$seguimiento['cseg_id']}'");
    }
}

echo "Proceso completado.\n";
?>