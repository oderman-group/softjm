<?php
include('../sesion.php');

header('Content-Type: application/json; charset=utf-8');

require_once RUTA_PROYECTO . '/usuarios/class/Cotizacion.php';
require_once RUTA_PROYECTO . '/usuarios/class/ClienteSeguimiento.php';
require_once RUTA_PROYECTO . '/usuarios/class/Modulos.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

if (!Modulos::validarRol([77], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tiene permisos para ver cotizaciones.']);
    exit;
}

$cotizacionId = !empty($_GET['cotizacion']) && is_numeric($_GET['cotizacion']) ? intval($_GET['cotizacion']) : 0;
$clienteId    = !empty($_GET['cliente']) && is_numeric($_GET['cliente']) ? intval($_GET['cliente']) : 0;

if ($cotizacionId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Cotización inválida.']);
    exit;
}

$detalle = Cotizacion::obtenerResumenDrawer(
    $cotizacionId,
    intval($idEmpresa),
    $conexionBdPrincipal
);

if ($detalle === null) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Cotización no encontrada.']);
    exit;
}

if ($clienteId > 0 && intval($detalle['cliente']['id']) !== $clienteId) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'La cotización no pertenece a este cliente.']);
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

if (!ClienteSeguimiento::usuarioPuedeVerZonaCliente(
    intval($detalle['cliente']['zona']),
    $zonasUsuarioPermitidas
)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tiene permisos para ver cotizaciones de esta zona.']);
    exit;
}

if (!Modulos::validarRol([395], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    $consultaAcceso = $conexionBdPrincipal->query("
        SELECT cotiz_creador, cotiz_vendedor
        FROM cotizacion
        WHERE cotiz_id = '" . $cotizacionId . "'
          AND cotiz_id_empresa = '" . intval($idEmpresa) . "'
        LIMIT 1
    ");
    $acceso = mysqli_fetch_assoc($consultaAcceso);
    $usuarioId = intval($_SESSION['id']);
    if (!$acceso || (intval($acceso['cotiz_creador']) !== $usuarioId && intval($acceso['cotiz_vendedor']) !== $usuarioId)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'No tiene permisos para ver esta cotización.']);
        exit;
    }
}

$detalle['permisos'] = [
    'puedeEditar' => Modulos::validarRol([79], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
];

echo json_encode([
    'success' => true,
    'data'    => $detalle,
]);
