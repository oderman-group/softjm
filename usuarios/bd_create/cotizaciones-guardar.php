<?php
require_once("../sesion.php");

$idPagina = 29;

if (isset($_POST["envio"])) {
    $envio = $_POST["envio"];
} else {
    $envio = 0;
}

$ticketId = "NULL";

if ($_POST["ticket"] == 'TICKET_AUTO') {
    //Creamos un ticket automáticamente
    mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_tikets(tik_asunto_principal, tik_tipo_tiket, tik_fecha_creacion, tik_usuario_responsable, tik_estado, tik_cliente, tik_prioridad, tik_sucursal, tik_etapa, tik_tipo_negocio)VALUES('COTIZACIÓN (Ticket creado de forma automática)', 1,'" . $_POST["fechaPropuesta"] . "','" . $_SESSION["id"] . "', 1,'" . $_POST["cliente"] . "', 2,'" . $_POST["sucursal"] . "', 3, 1)");

    $ticketId = mysqli_insert_id($conexionBdPrincipal);
} else if ($_POST["ticket"] != 'NO_TICKET') {
    $ticketId = $_POST["ticket"];
}

$precotizacion = 0;
if (isset($_POST["precotizacion"]) && $_POST["precotizacion"] == 1) {
    $precotizacion = 1;
}

$conexionBdPrincipal->query("INSERT INTO cotizacion(cotiz_fecha_propuesta, cotiz_cliente, cotiz_fecha_vencimiento, cotiz_vendedor, cotiz_creador, cotiz_sucursal, cotiz_contacto, cotiz_forma_pago, cotiz_fecha_creacion, cotiz_moneda, cotiz_observaciones, cotiz_envio, cotiz_proveedor, cotiz_id_empresa, cotiz_ticket, cotiz_es_precotizacion)VALUES('" . $_POST["fechaPropuesta"] . "','" . $_POST["cliente"] . "','" . $_POST["fechaVencimiento"] . "','" . $_POST["influyente"] . "','" . $_SESSION["id"] . "','" . $_POST["sucursal"] . "','" . $_POST["contacto"] . "','" . $_POST["formaPago"] . "',now(),'" . $_POST["moneda"] . "','" . $_POST["notas"] . "','" . $envio . "','" . $_POST["proveedor"] . "','" . $idEmpresa . "', ".$ticketId.", ".$precotizacion.")");
$idInsert = mysqli_insert_id($conexionBdPrincipal);

if ($_POST["ticket"] != 'NO_TICKET') {
    mysqli_query($conexionBdPrincipal,"UPDATE clientes_tikets 
    SET tik_id_cotizacion = ".$idInsert.",
    tik_asunto_principal = 'COTIZACIÓN Nro. ".$idInsert." (Ticket creado de forma automática)' 
    WHERE tik_id=".$ticketId);
}

require("guardar-productos-cotizacion.php");

require("guardar-combos-cotizacion.php");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../cotizaciones-editar.php?id=' . $idInsert . '&msg=1";</script>';
exit();