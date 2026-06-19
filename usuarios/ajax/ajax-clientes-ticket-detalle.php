<?php
include('../sesion.php');

header('Content-Type: application/json; charset=utf-8');

require_once RUTA_PROYECTO . '/usuarios/class/ClienteSeguimiento.php';
require_once RUTA_PROYECTO . '/usuarios/class/Modulos.php';
require_once RUTA_PROYECTO . '/usuarios/class/Tickets.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

if (!Modulos::validarRol([90], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tiene permisos para ver este ticket.']);
    exit;
}

$ticketId  = !empty($_GET['ticket']) && is_numeric($_GET['ticket']) ? intval($_GET['ticket']) : 0;
$clienteId = !empty($_GET['cliente']) && is_numeric($_GET['cliente']) ? intval($_GET['cliente']) : 0;

if ($ticketId <= 0 || $clienteId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Parámetros inválidos.']);
    exit;
}

$zonasUsuarioPermitidas = null;
if (!Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    $zonasUsuarioPermitidas = [];
    $conZonasUsuario = $conexionBdPrincipal->query("
        SELECT zpu_zona
        FROM zonas_usuarios
        WHERE zpu_usuario = '" . intval($_SESSION['id']) . "'
    ");
    while ($zonaUsuario = mysqli_fetch_array($conZonasUsuario, MYSQLI_BOTH)) {
        $zonasUsuarioPermitidas[] = intval($zonaUsuario['zpu_zona']);
    }
}

$detalle = ClienteSeguimiento::obtenerTicketDetalleDrawer(
    $ticketId,
    $clienteId,
    intval($idEmpresa),
    $conexionBdPrincipal
);

if ($detalle === null) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Ticket no encontrado.']);
    exit;
}

if (!ClienteSeguimiento::usuarioPuedeVerZonaCliente(
    intval($detalle['cliente']['zona']),
    $zonasUsuarioPermitidas
)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tiene permisos para ver tickets de esta zona.']);
    exit;
}

$estadoTicket = intval($detalle['ticket']['estado'] ?? Ticket::ESTADO_CERRADO);

$detalle['permisos'] = [
    'puedeEditarTicket'       => $estadoTicket === Ticket::ESTADO_ABIERTO,
    'puedeAgregarSeguimiento' => Modulos::validarRol([13], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)
        && $estadoTicket === Ticket::ESTADO_ABIERTO,
    'puedeVerDetalleSeguimiento' => Modulos::validarRol([14], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'puedeVerSeguimientos'    => Modulos::validarRol([12], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
];

echo json_encode([
    'success' => true,
    'data'    => $detalle,
]);
