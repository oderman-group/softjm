<?php
require_once("../sesion.php");

require_once RUTA_PROYECTO.'/usuarios/class/Cotizacion.php';

if (Cotizacion::esCotizacionVendida($_POST["id"], $idEmpresa)) {
    echo '<script type="text/javascript">window.location.href="../cotizaciones-editar.php?id=' . $_POST["id"] . '&warning=4";</script>';
    exit();
}

$idPagina = 25;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$consultaCli=$conexionBdPrincipal->query("SELECT * FROM clientes WHERE cli_id='".$_POST["cliente"]."'");
$datosCliente = mysqli_fetch_array($consultaCli, MYSQLI_BOTH);

try {
    mysqli_query($conexionBdPrincipal, "START TRANSACTION");

    if (isset($_POST["ticket"])) {
        $ticketId = "NULL";

        if ($_POST["ticket"] == 'TICKET_AUTO') {
            //Creamos un ticket automáticamente
            mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_tikets(tik_asunto_principal, tik_tipo_tiket, tik_fecha_creacion, tik_usuario_responsable, tik_estado, tik_cliente, tik_prioridad, tik_sucursal, tik_etapa, tik_tipo_negocio, tik_id_cotizacion)VALUES('COTIZACIÓN Nro. ".$_POST["id"]." (Ticket creado de forma automática)', 1,'" . $_POST["fechaPropuesta"] . "','" . $_SESSION["id"] . "', 1,'" . $_POST["cliente"] . "', 2,'" . $_POST["sucursal"] . "', 3, 1, ".$_POST["id"].")");

            $ticketId = mysqli_insert_id($conexionBdPrincipal);
        } else if ($_POST["ticket"] != 'NO_TICKET') {
            $ticketId = $_POST["ticket"];
            mysqli_query($conexionBdPrincipal,"UPDATE clientes_tikets 
            SET tik_id_cotizacion = ".$idInsert.",
            WHERE tik_id=".$ticketId);
        }
    }

    $conexionBdPrincipal->query("UPDATE cotizacion SET 
    cotiz_fecha_propuesta='" . $_POST["fechaPropuesta"] . "', 
    cotiz_cliente='" . $_POST["cliente"] . "', 
    cotiz_sucursal='" . $_POST["sucursal"] . "', 
    cotiz_contacto='" . $_POST["contacto"] . "', 
    cotiz_fecha_vencimiento='" . $_POST["fechaVencimiento"] . "', 
    cotiz_vendedor='" . $_POST["influyente"] . "', 
    cotiz_forma_pago='" . $_POST["formaPago"] . "', 
    cotiz_moneda='" . $_POST["moneda"] . "', 
    cotiz_usuario_modificacion='" . $_SESSION["id"] . "', 
    cotiz_observaciones='" . $conexionBdPrincipal->real_escape_string($_POST["notas"]) . "', 
    cotiz_envio='" . $_POST["envio"] . "', 
    cotiz_ocultar_descuento_combo='" . $_POST["dctoCombos"] . "', 
    cotiz_descuentos_especiales='" . $_POST["dctoEspecial"] . "', 
    cotiz_ticket=" . $ticketId . "
    WHERE cotiz_id='" . $_POST["id"] . "' 
    AND cotiz_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'
    ");

    $filasAfectadas = $conexionBdPrincipal->affected_rows;

    if ($filasAfectadas > 0) {
        $conexionBdPrincipal->query("UPDATE cotizacion SET 
        cotiz_ultima_modificacion=now(), 
        cotiz_version=cotiz_version+1
        WHERE cotiz_id='" . $_POST["id"] . "' 
        AND cotiz_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'
        ");

        $parmetros = '&msg=2';
    } else {
        $parmetros = '&msg=17&msgContent=No hubo cambios para guardar.';
    }

    if ($_POST["monedaActual"] != $_POST["moneda"]) {
        require('actualizar-productos-cotizacion-2.php');
        require('actualizar-combos-cotizacion-2.php');
    }

    mysqli_query($conexionBdPrincipal, "COMMIT");

} catch (Exception $e) {
    mysqli_query($conexionBdPrincipal, "ROLLBACK");
    $parmetros = '&error=3&message=Hubo problemas para actualizar esta cotización!';
}

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../cotizaciones-editar.php?id=' . $_POST["id"] . $parmetros.'";</script>';
exit();
