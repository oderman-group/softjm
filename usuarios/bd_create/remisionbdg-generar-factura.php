<?php
require_once("../sesion.php");
$idPagina = 377;

require_once RUTA_PROYECTO.'/usuarios/class/Producto.php';
require_once RUTA_PROYECTO.'/usuarios/class/Factura.php';
require_once RUTA_PROYECTO.'/usuarios/class/Cliente.php';
require_once RUTA_PROYECTO.'/usuarios/class/MailerService.php';

//Verificamos que la remisión actual no haya generado ya otra factura
$generoFactura = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM facturas 
WHERE factura_remision='" . $_GET["id"] . "'"));

if ($generoFactura[0]!="") {
    echo "<span style='font-family:arial; text-align:center; color:red;'>Esta Remisión ya generó la factura con ID: ".$generoFactura[0].". En la fecha: ".$generoFactura['factura_fecha_creacion']."</div>";
    exit();
}

$valorProductos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT sum(czpp_valor*czpp_cantidad) + sum(czpp_valor*czpp_cantidad)*(czpp_impuesto/100) FROM cotizacion_productos 
WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_REM."'"));

try {
    mysqli_query($conexionBdPrincipal, "START TRANSACTION");

    mysqli_query($conexionBdPrincipal,"INSERT INTO facturas(factura_fecha_propuesta, factura_observaciones, factura_cliente, factura_fecha_vencimiento, factura_vendedor, factura_creador, factura_sucursal, factura_contacto, factura_forma_pago, factura_fecha_creacion, factura_moneda, factura_estado, factura_tipo, factura_concepto, factura_extranjera, factura_remision)
    SELECT now(), remi_observaciones, remi_cliente, remi_fecha_vencimiento, remi_vendedor, '" . $_SESSION["id"] . "', remi_sucursal, remi_contacto, remi_forma_pago, now(), remi_moneda, 1, 1, 'Traída de remisión #".$_GET["id"]."', 0, remi_id 
    FROM remisionbdg 
    WHERE remi_id='" . $_GET["id"] . "'");

    $idInsert = mysqli_insert_id($conexionBdPrincipal);

    $datosRemision = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM remisionbdg WHERE remi_id='" . $_GET["id"] . "'"));

    Cliente::convertirACliente($datosRemision['remi_cliente'], $idEmpresa);


    $productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
    WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_REM."'");

    $trazabilidadFactura = Factura::trazabilidadFactura($idInsert, $conexionBdPrincipal);

    if (!empty($trazabilidadFactura)) {
        mysqli_query($conexionBdPrincipal,"UPDATE clientes_tikets SET 
        tik_estado='".TIK_ESTADO_CERRADO."', 
        tik_fecha_cierre=NOW(), 
        tik_etapa=5
        WHERE tik_id='" . $trazabilidadFactura['tik_id'] . "'");
    }

    while ($prod = mysqli_fetch_array($productos)) {
        if ($prod['czpp_orden'] == "") $prod['czpp_orden'] = 1;
        if ($prod['czpp_cantidad'] == "") $prod['czpp_cantidad'] = 1;

        mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_descuento, czpp_observacion, czpp_servicio, czpp_combo, czpp_bodega, czpp_productos_en_combo, czpp_nombre_original)VALUES('" . $idInsert . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', '" . $prod['czpp_orden'] . "', '" . $prod['czpp_cantidad'] . "', '" . $prod['czpp_impuesto'] . "', ".CZPP_TIPO_FACT.", '" . $prod['czpp_descuento'] . "', '" . $prod['czpp_observacion'] . "', '" . $prod['czpp_servicio'] . "', '" . $prod['czpp_combo'] . "', '" . $prod['czpp_bodega'] . "', '" . $prod['czpp_productos_en_combo'] . "', '" . $prod['czpp_nombre_original'] . "')");

        $contador++;
    }

    mysqli_query($conexionBdPrincipal, "COMMIT");

} catch (Exception $e) {
    mysqli_query($conexionBdPrincipal, "ROLLBACK");
    $parmetros = '&error=3&message=Hubo problemas para generar esta factura!';
    echo '<script type="text/javascript">window.location.href="../remisionbdg.php?busqueda=' . $_GET["id"] . $parametros .'";</script>';
    exit();
}


// Disparar notificación de nueva factura creada
$contactoCliente = mysqli_fetch_array(Cliente::Select([
    'cli_id'         => $_GET["cliente"],
    'cli_id_empresa' => $idEmpresa
]), MYSQLI_BOTH);

$asesorDatos = [
    'usr_email' => $datosUsuarioActual['usr_email'],
    'usr_nombre' => $datosUsuarioActual['usr_nombre']
];

$_SESSION["dataAdicional"]["nombre_empresa"] = "JM EQUIPOS S.A.S.";

$fin = "
<p>
Hola, informamos que <b>".$datosUsuarioActual['usr_nombre']."</b> ha generado una nueva factura, relacionada al cliente <b>".$contactoCliente['cli_nombre']."</b>, con el siguiente número de factura: <br>
<h1>".$idInsert."</h1>
</p>
<p>Recuerda que para entrar al link de la factura debes estar logueado en el sistema.</p>
"; // Simular el cuerpo HTML

// 1. Instanciar el servicio
$mailer = new MailerService();

// 2. Preparar el contenido del correo usando la plantilla
$subject =  " Nueva factura generada #".$idInsert;

$emailData = [
    'subject'       => $subject,
    'app_name'      => $_SESSION["dataAdicional"]["nombre_empresa"], // Reutiliza el nombre de la empresa
    'content'       => $fin, // Tu contenido HTML aquí
    'button_link'   => REDIRECT_ROUTE.'/usuarios/facturas.php?busqueda='.$idInsert, // Si no necesitas botón, dejar vacío
    'button_text'   => 'Ver factura', // Si no necesitas botón, dejar vacío
    'support_email' => 'soporte@jmequipos.com',
];

$htmlBody = renderEmailTemplate(RUTA_PROYECTO.'/usuarios/plantillas_email/notificaciones.html', $emailData);
$textBody = strip_tags($htmlBody); // Siempre genera una versión de texto plano

// 3. Definir destinatarios adicionales (asesor en CC)
$ccRecipients = [];
if (!empty($asesorDatos['usr_email'])) {
    $ccRecipients[] = $asesorDatos['usr_email'];
}

// 4. Enviar el correo usando el método sendEmail
if ($mailer->sendEmail(
    'jmendoza@jmequipos.com',
    'Jaime Mendoza',
    $subject,
    $htmlBody,
    $textBody,
    [], // No hay adjuntos en este ejemplo
    $ccRecipients // El asesor en CC
)) {
    echo "Correo de factura enviado con éxito.<br>";
} else {
    echo "Fallo el envío del correo de factura.<br>";
}

echo '<script type="text/javascript">window.location.href="../facturas.php?busqueda=' . $idInsert . '";</script>';
exit();