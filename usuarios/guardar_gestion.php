<?php
include("sesion.php");
header('Content-Type: application/json; charset=utf-8');

require_once RUTA_PROYECTO.'/usuarios/class/MailerService.php';

try {
    // Obtener datos enviados por fetch (JSON)
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['id_cliente'], $data['clasificacion'])) {
        echo json_encode(["error" => true, "mensaje" => "Datos incompletos."]);
        exit;
    }

    $idCliente     = intval($data['id_cliente']);
    $clasificacion = $conexionBdPrincipal->real_escape_string($data['clasificacion']);
    $agente        = !empty($data['agente']) ? intval($data['agente']) : "NULL";
    $notas         = $conexionBdPrincipal->real_escape_string($data['notas']);

    // Guardar en la tabla de prospectos
    $conexionBdPrincipal->query("
        UPDATE prospectos_importacion_detalles SET
            pid_estado = '$clasificacion',
            pid_notas = CONCAT(IFNULL(pid_notas,''), '\n[".date('Y-m-d H:i')."] ', '$notas'),
            pid_ultima_modificacion = NOW(),
            pid_responsable_ultima_modificacion = ".$_SESSION["id"].",
            pid_cantidad_actualizaciones = pid_cantidad_actualizaciones + 1
        WHERE pid_id = $idCliente
    ");

    if ($clasificacion == 'VALIDO') {
        //Crear al cliente
        mysqli_query($conexionBdPrincipal,"INSERT INTO clientes(cli_nombre, cli_categoria, cli_email, cli_telefono, cli_fecha_registro, cli_nivel, cli_responsable, cli_id_empresa, cli_ciudad, cli_referencia, cli_forma_creacion)VALUES('" . $data["nombre_cliente"] . "', ".CLI_CATEGORIA_PROSPECTO.",'" . $data["email"] . "','" . $data["telefono"] . "', now(), 3, '" . $_SESSION["id"] . "', '" . $idEmpresa . "', ".CIUDAD_DESCONOCIDA.", '" . $data["fuente_id"] . "', 'EJECUTIVO_PROSPECCION')");
        $idCliente = mysqli_insert_id($conexionBdPrincipal);

        //Crear automáticamente la sucursal
        mysqli_query($conexionBdPrincipal,"INSERT INTO sucursales(sucu_cliente_principal, sucu_telefono, sucu_nombre)VALUES('" . $idCliente . "', '" . $data["telefono"] . "','Sede principal')");

        //Crear el contacto
        mysqli_query($conexionBdPrincipal,"INSERT INTO contactos(cont_nombre, cont_telefono, cont_email, cont_cliente_principal)VALUES('" . $data["nombre_cliente"] . "', '" . $data["telefono"] . "', '" . $data["email"] . "', '" . $idCliente . "')");
        $idContacto = mysqli_insert_id($conexionBdPrincipal);

        //Crear ticket
        mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_tikets(tik_asunto_principal, tik_tipo_tiket, tik_fecha_creacion, tik_usuario_responsable, tik_estado, tik_cliente, tik_prioridad, tik_canal)
        VALUES('NUEVO PROSPECTO VÁLIDO - (".$data["nombre_cliente"].")', ".TICKET_COMERCIAL.", now(), ".$_SESSION["id"].", ".TIK_ESTADO_ABIERTO.", ".$idCliente.", ".TICKET_PRIORIDAD_URGENTE.", 7)");
        
        $tiketID = mysqli_insert_id($conexionBdPrincipal);
        
        //Crear seguimiento
        mysqli_query($conexionBdPrincipal,"INSERT INTO cliente_seguimiento(cseg_cliente, cseg_fecha_reporte, cseg_observacion, cseg_usuario_responsable, cseg_fecha_proximo_contacto, cseg_asunto, cseg_usuario_encargado, cseg_fecha_contacto, cseg_tipo, cseg_contacto, cseg_tiket, cseg_canal, cseg_canal_proximo_contacto)VALUES(".$idCliente.", now(), 'Asignado desde ejecutivo de prospección', ".$_SESSION["id"].", now(), '".$notas."', ".$agente.", now(), ".SEGUIMIENTO_COMERCIAL.", '".$idContacto."', '".$tiketID."', 7, 8)");

        $idSeguimiento = mysqli_insert_id($conexionBdPrincipal);

        //Crear notificación interna
        mysqli_query($conexionBdPrincipal,"INSERT INTO notificaciones(not_asunto, not_cliente, not_usuario, not_visto, not_estado, not_fecha, not_seguimiento)VALUES('Nuevo prospecto asignado (".$data["nombre_cliente"].")', '".$idCliente."', ".$agente.", 0, 1, now(), '".$idSeguimiento."')");

        //Envíar email al encargado
        $fin = "
        <p>
        Hola, te informamos que <b>".$datosUsuarioActual['usr_nombre']."</b> te ha asignado un nuevo ticket y seguimiento relacionado a un nuevo prospecto llamado <b>".$data["nombre_cliente"]."</b> con la siguiente nota: <br>
			<i>".$notas."</i>.
        </p>
        <p>Recuerda que para entrar al link del seguimiento debes estar logueado en el sistema.</p>
        "; // Simular el cuerpo HTML

        // 1. Instanciar el servicio
        $mailer = new MailerService();

        // 2. Preparar el contenido del correo usando la plantilla
        $subject =  "Nuevo prospecto asignado (".$data["nombre_cliente"].")";

        $emailData = [
            'subject'       => $subject,
            'app_name'      => $_SESSION["dataAdicional"]["nombre_empresa"], // Reutiliza el nombre de la empresa
            'content'       => $fin, // Tu contenido HTML aquí
            'button_link'   => REDIRECT_ROUTE.'/usuarios/clientes-seguimiento.php?idTK='.$tiketID.'&seg='.$idSeguimiento, // Si no necesitas botón, dejar vacío
            'button_text'   => 'Ver el seguimiento', // Si no necesitas botón, dejar vacío
            'support_email' => 'soporte@jmequipos.com',
        ];

        $htmlBody = renderEmailTemplate(RUTA_PROYECTO.'/usuarios/plantillas_email/notificaciones.html', $emailData);
        $textBody = strip_tags($htmlBody); // Siempre genera una versión de texto plano

        $asesor = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM usuarios WHERE usr_id='" . $agente . "'"));

        $asesorDatos = [];

        // 3. Definir destinatarios adicionales (asesor en CC)
        $ccRecipients = [];
        if (!empty($asesorDatos['usr_email'])) {
            $ccRecipients[] = $asesorDatos['usr_email'];
        }

        // 4. Enviar el correo usando el método sendEmail
        if ($mailer->sendEmail(
            $asesor['usr_email'],
            $asesor['usr_nombre'],
            $subject,
            $htmlBody,
            $textBody,
            [], // No hay adjuntos en este ejemplo
            $ccRecipients // El asesor en CC
        )) {} else {
            throw new Exception('Falló el envío de Email');
        }
    }

    $clientes = [];
    
    $consultaProspectos = $conexionBdPrincipal->query("SELECT *
    FROM prospectos_importacion_detalles
    INNER JOIN prospectos_importacion ON pi_id=pid_id_archivo
    ");

    while ($prospectos = mysqli_fetch_assoc($consultaProspectos)) {
        $clientes[] = [
            'id'             => $prospectos['pid_id'],
            'nombre_cliente' => $prospectos['pid_nombres'],
            'telefono'       => $prospectos['pid_telefono'],
            'email'          => $prospectos['pid_email'],
            'gestion_estado' => $prospectos['pid_estado'],
            'notas_previas'  => $prospectos['pid_notas'],
            'fuente'         => $referenciaLlegada[$prospectos['pi_fuente']],
            'ciudad'         => $prospectos['pi_ciudad_evento'],
        ];
    }

    echo json_encode(
        [
            "error"   => false, 
            "mensaje" => "Gestión guardada con éxito.",
            "datos"   => $clientes
        ]
    );
} catch (Exception $e) {
    echo json_encode(["error" => true, "mensaje" => "Error: ".$e->getMessage()]);
}
