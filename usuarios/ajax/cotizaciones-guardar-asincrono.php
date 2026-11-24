<?php
include("../sesion.php");
require_once RUTA_PROYECTO.'/usuarios/class/Tickets.php';

$idPagina = 79;

header('Content-Type: application/json');

try {
    // Validar que sea POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Validar campos obligatorios
    if (empty($_POST['id']) || !is_numeric($_POST['id'])) {
        throw new Exception('ID de cotización inválido');
    }

    $cotizacionId = intval($_POST['id']);

    // Verificar que la cotización existe y pertenece a la empresa
    $verificarCotiz = $conexionBdPrincipal->query("
        SELECT cotiz_id, cotiz_vendida, cotiz_cliente, cotiz_sucursal, cotiz_contacto, cotiz_ticket
        FROM cotizacion 
        WHERE cotiz_id = '".$cotizacionId."' AND cotiz_id_empresa = '".$idEmpresa."'
    ");
    
    if ($verificarCotiz->num_rows == 0) {
        throw new Exception('Cotización no encontrada');
    }

    $cotizacion = mysqli_fetch_array($verificarCotiz, MYSQLI_BOTH);

    // Una cotización vendida solo admite completar sucursal y contacto si todavía no los tiene.
    if ($cotizacion['cotiz_vendida'] == 1 && !empty($cotizacion['cotiz_contacto'])) {
        throw new Exception('No es posible editar una cotización que ya generó pedido');
    }

    if ($cotizacion['cotiz_vendida'] == 1 && empty($cotizacion['cotiz_contacto'])) {
        $contacto = intval($_POST['contacto'] ?? 0);
        $sucursal = intval($_POST['sucursal'] ?? 0);
        $clienteCotizacion = intval($cotizacion['cotiz_cliente']);
        if ($contacto <= 0 || $sucursal <= 0) {
            throw new Exception('Seleccione la sucursal y el contacto.');
        }
        $contactoValido = $conexionBdPrincipal->query("SELECT cont_id FROM contactos WHERE cont_id='".$contacto."' AND cont_cliente_principal='".$clienteCotizacion."' LIMIT 1");
        $sucursalValida = $conexionBdPrincipal->query("SELECT sucu_id FROM sucursales WHERE sucu_id='".$sucursal."' AND sucu_cliente_principal='".$clienteCotizacion."' LIMIT 1");
        if (!$contactoValido || $contactoValido->num_rows === 0 || !$sucursalValida || $sucursalValida->num_rows === 0) {
            throw new Exception('La sucursal o el contacto no pertenecen al cliente de la cotización.');
        }
        if (!$conexionBdPrincipal->query("UPDATE cotizacion SET cotiz_sucursal='".$sucursal."', cotiz_contacto='".$contacto."', cotiz_usuario_modificacion='".intval($_SESSION['id'] ?? 0)."', cotiz_ultima_modificacion=NOW() WHERE cotiz_id='".$cotizacionId."' AND cotiz_id_empresa='".$idEmpresa."'")) {
            throw new Exception('Error al guardar el contacto: ' . $conexionBdPrincipal->error);
        }
        $consultaFecha = $conexionBdPrincipal->query("SELECT cotiz_ultima_modificacion FROM cotizacion WHERE cotiz_id='".$cotizacionId."'");
        $fecha = mysqli_fetch_array($consultaFecha, MYSQLI_BOTH);
        echo json_encode([
            'success' => true,
            'message' => 'Contacto guardado.',
            'affected_rows' => $conexionBdPrincipal->affected_rows,
            'ultima_modificacion' => $fecha['cotiz_ultima_modificacion'] ?? ''
        ]);
        exit;
    }

    // Construir la consulta de actualización dinámicamente
    $updates = [];
    $clienteNuevo = null;
    $sucursalNueva = null;
    $ticketAsociadoId = null;

    // Proveedor (opcional)
    if (isset($_POST['proveedor'])) {
        $proveedor = mysqli_real_escape_string($conexionBdPrincipal, $_POST['proveedor']);
        $updates[] = "cotiz_proveedor = '".$proveedor."'";
    }

    // Cliente (obligatorio)
    if (isset($_POST['cliente']) && !empty($_POST['cliente'])) {
        $clienteNuevo = intval($_POST['cliente']);
        $updates[] = "cotiz_cliente = '".$clienteNuevo."'";
    }

    // Sucursal (obligatorio)
    if (isset($_POST['sucursal']) && !empty($_POST['sucursal'])) {
        $sucursalNueva = intval($_POST['sucursal']);
        $updates[] = "cotiz_sucursal = '".$sucursalNueva."'";
    }

    // Contacto (obligatorio)
    if (isset($_POST['contacto']) && !empty($_POST['contacto'])) {
        $contacto = intval($_POST['contacto']);
        $updates[] = "cotiz_contacto = '".$contacto."'";
    }

    // Usuario influyente/vendedor (opcional)
    if (isset($_POST['influyente'])) {
        $influyente = mysqli_real_escape_string($conexionBdPrincipal, $_POST['influyente']);
        $updates[] = "cotiz_vendedor = '".$influyente."'";
    }

    // Fecha propuesta (obligatorio)
    if (isset($_POST['fechaPropuesta']) && !empty($_POST['fechaPropuesta'])) {
        $fechaPropuesta = mysqli_real_escape_string($conexionBdPrincipal, $_POST['fechaPropuesta']);
        $updates[] = "cotiz_fecha_propuesta = '".$fechaPropuesta."'";
    }

    // Fecha vencimiento (obligatorio)
    if (isset($_POST['fechaVencimiento']) && !empty($_POST['fechaVencimiento'])) {
        $fechaVencimiento = mysqli_real_escape_string($conexionBdPrincipal, $_POST['fechaVencimiento']);
        $updates[] = "cotiz_fecha_vencimiento = '".$fechaVencimiento."'";
    }

    // Forma de pago
    if (isset($_POST['formaPago'])) {
        $formaPago = mysqli_real_escape_string($conexionBdPrincipal, $_POST['formaPago']);
        $updates[] = "cotiz_forma_pago = '".$formaPago."'";
    }

    // Moneda
    if (isset($_POST['moneda'])) {
        $moneda = intval($_POST['moneda']);
        $updates[] = "cotiz_moneda = '".$moneda."'";
    }

    // Observaciones
    if (isset($_POST['notas'])) {
        $notas = mysqli_real_escape_string($conexionBdPrincipal, $_POST['notas']);
        $updates[] = "cotiz_observaciones = '".$notas."'";
    }

    // Costo de envío
    if (isset($_POST['envio'])) {
        $envio = mysqli_real_escape_string($conexionBdPrincipal, $_POST['envio']);
        $updates[] = "cotiz_envio = '".$envio."'";
    }

    // Ocultar descuento de combos
    if (isset($_POST['dctoCombos'])) {
        $dctoCombos = intval($_POST['dctoCombos']);
        $updates[] = "cotiz_ocultar_descuento_combo = '".$dctoCombos."'";
    }

    // Requiere descuento especial
    if (isset($_POST['dctoEspecial'])) {
        $dctoEspecial = intval($_POST['dctoEspecial']);
        $updates[] = "cotiz_descuentos_especiales = '".$dctoEspecial."'";
    }

    // Ticket asociado
    if (isset($_POST['ticket']) && $_POST['ticket'] !== 'NO_TICKET') {
        if ($_POST['ticket'] === 'TICKET_AUTO') {
            // La creación automática de ticket no está implementada en el guardado asíncrono
            $updates[] = "cotiz_ticket = NULL";
        } else {
            $ticketAsociadoId = intval($_POST['ticket']);
            $updates[] = "cotiz_ticket = '".$ticketAsociadoId."'";
        }
    }

    // Fecha de última modificación
    $updates[] = "cotiz_ultima_modificacion = NOW()";

    // Validar que haya al menos un campo para actualizar
    if (empty($updates)) {
        throw new Exception('No hay datos para actualizar');
    }

    // Construir y ejecutar la consulta
    $sql = "UPDATE cotizacion SET " . implode(", ", $updates) . " WHERE cotiz_id = '".$cotizacionId."' AND cotiz_id_empresa = '".$idEmpresa."'";
    
    if (!$conexionBdPrincipal->query($sql)) {
        throw new Exception('Error al actualizar: ' . $conexionBdPrincipal->error);
    }

    $filasCotizacion = $conexionBdPrincipal->affected_rows;

    // Si se asoció un ticket existente, alinear también tik_id_cotizacion y cliente
    if ($ticketAsociadoId !== null && $ticketAsociadoId > 0) {
        $clienteTicket = $clienteNuevo !== null ? $clienteNuevo : intval($cotizacion['cotiz_cliente']);
        $sucursalTicket = $sucursalNueva !== null ? $sucursalNueva : intval($cotizacion['cotiz_sucursal']);

        $setSucursal = $sucursalTicket > 0 ? ", tik_sucursal = '".$sucursalTicket."'" : "";
        $conexionBdPrincipal->query("
            UPDATE clientes_tikets
            SET tik_id_cotizacion = '".$cotizacionId."',
                tik_cliente = '".$clienteTicket."'
                ".$setSucursal."
            WHERE tik_id = '".$ticketAsociadoId."'
        ");
    }

    // Si cambió el cliente de la cotización, sincronizar el ticket vinculado
    if ($clienteNuevo !== null && $clienteNuevo > 0) {
        Ticket::sincronizarClienteDesdeCotizacion(
            $cotizacionId,
            $clienteNuevo,
            $conexionBdPrincipal,
            $sucursalNueva
        );
    }

    // Verificar si se actualizó algo
    if ($filasCotizacion === 0 && $ticketAsociadoId === null && $clienteNuevo === null) {
        echo json_encode([
            'success' => true,
            'message' => 'No se detectaron cambios',
            'affected_rows' => 0
        ]);
        exit;
    }

    // Obtener la fecha de última modificación para mostrarla
    $consultaFecha = $conexionBdPrincipal->query("
        SELECT cotiz_ultima_modificacion 
        FROM cotizacion 
        WHERE cotiz_id = '".$cotizacionId."'
    ");
    $fecha = mysqli_fetch_array($consultaFecha, MYSQLI_BOTH);

    echo json_encode([
        'success' => true,
        'message' => 'Cambios guardados exitosamente',
        'affected_rows' => $filasCotizacion,
        'ultima_modificacion' => $fecha['cotiz_ultima_modificacion']
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
