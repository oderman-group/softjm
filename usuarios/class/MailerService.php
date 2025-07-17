<?php

// Asegúrate de que las rutas a PHPMailer sean correctas para tu estructura de directorios
require '../../librerias/phpmailer/Exception.php';
require '../../librerias/phpmailer/PHPMailer.php';
require '../../librerias/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// --- Configuración de Credenciales (¡IMPORTANTE: Esto debe venir de un lugar seguro!) ---
// Para DEMO, puedes ponerlo aquí. Para PRODUCCIÓN, usa variables de entorno, etc.
// Simular la obtención de configuración (en tu caso, vendría de $configuracion)
function obtenerConfiguracionCorreo() {
    // Aquí es donde cargarías tus credenciales.
    // Podrían venir de una BD, de un archivo .env, o de constantes.
    return [
        'server'           => EMAIL_SERVER,
        'email'            => EMAIL_USER, // Ejemplo: $configuracion['conf_email']
        'clave'            => EMAIL_PASSWORD,      // Ejemplo: $configuracion['conf_clave_correo']
        'nombre_remitente' => NAME_SENDER,     // Nombre descriptivo para el remitente
    ];
}

class MailerService
{
    private PHPMailer $mailer;
    private array $emailConfig;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true); // Habilita las excepciones
        $this->emailConfig = obtenerConfiguracionCorreo(); // Carga las credenciales

        // Configuración del servidor SMTP (basado en tu código)
        // $this->mailer->SMTPDebug = 0; // Desactivar en producción (0 = off, 1 = client, 2 = client and server)
        $this->mailer->isSMTP();
        $this->mailer->Host       = $this->emailConfig['server']; // Tu host SMTP actual
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $this->emailConfig['email'];
        $this->mailer->Password   = $this->emailConfig['clave'];
        // PHPMailer.ENCRYPTION_SMTPS para puerto 465, PHPMailer::ENCRYPTION_STARTTLS para puerto 587
        $this->mailer->SMTPSecure = 'ssl';
        $this->mailer->Port       = 465;

        // Configuración del remitente por defecto
        $this->mailer->setFrom($this->emailConfig['email'], $this->emailConfig['nombre_remitente']);
        $this->mailer->CharSet    = 'UTF-8';
    }

    /**
     * Envía un correo electrónico.
     *
     * @param string $toEmail   Correo electrónico del destinatario.
     * @param string $toName    Nombre del destinatario.
     * @param string $subject   Asunto del correo.
     * @param string $bodyHtml  Contenido del correo en formato HTML.
     * @param string $bodyText  Contenido del correo en formato de texto plano (fallback).
     * @param array  $attachments Array de rutas a archivos adjuntos.
     * @param array  $ccEmails  Array de correos para CC (ej. ['cc1@example.com', 'cc2@example.com']).
     * @param array  $bccEmails Array de correos para BCC (ej. ['bcc1@example.com', 'bcc2@example.com']).
     * @return bool True si el correo se envió con éxito, False en caso contrario.
     */
    public function sendEmail(
        string $toEmail,
        string $toName,
        string $subject,
        string $bodyHtml,
        string $bodyText,
        array $attachments = [],
        array $ccEmails = [],
        array $bccEmails = []
    ): bool {
        try {
            // Limpia los destinatarios y adjuntos de envíos anteriores
            $this->mailer->clearAllRecipients(); // Limpia To, Cc, Bcc
            $this->mailer->clearAttachments();

            // Añadir destinatario principal
            $this->mailer->addAddress($toEmail, $toName);

            // Añadir destinatarios CC
            foreach ($ccEmails as $ccEmail) {
                $this->mailer->addCC($ccEmail);
            }

            // Añadir destinatarios BCC
            foreach ($bccEmails as $bccEmail) {
                $this->mailer->addBCC($bccEmail);
            }

            // Contenido del correo
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $bodyHtml;
            $this->mailer->AltBody = $bodyText;

            // Añadir adjuntos
            foreach ($attachments as $filePath) {
                if (file_exists($filePath)) {
                    $this->mailer->addAttachment($filePath);
                } else {
                    error_log("Advertencia: Archivo adjunto no encontrado: " . $filePath);
                }
            }

            // Enviar el correo
            $this->mailer->send();
            error_log("Correo enviado con éxito a: {$toEmail} - Asunto: {$subject}");
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo a {$toEmail} - Asunto: {$subject}: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
}


/**
 * Renders an email template by replacing placeholders with provided data.
 *
 * This function reads an HTML template file, replaces placeholders like `{{ variable }}`
 * and simple conditionals like `{% if variable %}`...`{% endif %}`, and returns the
 * resulting HTML string. It has special handling for the 'content' key to allow raw HTML injection.
 *
 * @param string $templatePath The file path to the HTML email template.
 * @param array  $data         An associative array where keys are placeholder names (without braces)
 *                             and values are the data to be injected.
 * @return string              The rendered HTML content as a string.
 * @throws Exception           If the template file cannot be read.
 */
function renderEmailTemplate(string $templatePath, array $data): string
{
    $template = file_get_contents($templatePath);
    if ($template === false) {
        throw new Exception("No se pudo cargar la plantilla de correo: " . $templatePath);
    }

    foreach ($data as $key => $value) {
        // CONDICIONAL AÑADIDO: No aplicar htmlspecialchars a la clave 'content'
        if ($key === 'content') {
            $template = str_replace('{{ ' . $key . ' }}', $value, $template);
        } else {
            // Para todas las demás claves, sí aplicar htmlspecialchars por seguridad
            $template = str_replace('{{ ' . $key . ' }}', htmlspecialchars($value), $template);
        }

        // Manejo de condicionales simples {% if ... %}
        // Esto solo funciona para un solo marcador de posición dentro del if.
        // Si necesitas lógica más compleja, considera un motor de plantillas de verdad.
        $if_pattern = '/{% if ' . preg_quote($key) . '.*?%}(.*?){% endif %}/s';
        if (preg_match($if_pattern, $template)) {
            $replacement = !empty($value) ? '$1' : '';
            $template = preg_replace($if_pattern, $replacement, $template);
        }
    }
    // Después de reemplazar todas las variables, eliminar cualquier otro condicional {% if %} que no se haya resuelto
    $template = preg_replace('/{% if .*?%}(.*?){% endif %}/s', '', $template);


    // Reemplazar el año actual (esto no es una variable del array $data)
    $template = str_replace('{{ current_year }}', date('Y'), $template);

    return $template;
}