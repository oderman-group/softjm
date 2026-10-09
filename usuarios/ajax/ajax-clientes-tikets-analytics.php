<?php
include("../sesion.php");

require_once RUTA_PROYECTO . '/usuarios/class/Tickets.php';

header('Content-Type: application/json; charset=utf-8');

if (!Modulos::validarRol([88], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para ver estadísticas de tickets.']);
    exit;
}

$clienteId = !empty($_GET['cte']) && is_numeric($_GET['cte']) ? (int) $_GET['cte'] : 0;
$anio = !empty($_GET['anio']) && is_numeric($_GET['anio']) ? (int) $_GET['anio'] : (int) date('Y');
$usuarioId = (int) $_SESSION['id'];

$verTodos = Modulos::validarRol([384], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$restringirZona = !Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$excluirCiudad1122 = Modulos::validarRol([385], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$zonasUsuario = $restringirZona
    ? Ticket::obtenerIdsZonasUsuario($conexionBdPrincipal, $usuarioId)
    : null;

if (!isset($opcionesEtapa) || !is_array($opcionesEtapa)) {
    $opcionesEtapa = [
        'N/A',
        'En progreso',
        'En espera',
        'Propuesta/Cotización',
        'Negociación/Revisión',
        'Cerrado y ganado',
        'Cerrado y perdido',
    ];
}

$analytics = Ticket::obtenerEstadisticasAnuales(
    $conexionBdPrincipal,
    $anio,
    $usuarioId,
    $verTodos,
    $restringirZona,
    $clienteId > 0 ? $clienteId : null,
    $excluirCiudad1122,
    $opcionesEtapa,
    (int) $idEmpresa,
    $zonasUsuario
);
$analytics['lazy'] = false;

echo json_encode([
    'success' => true,
    'analytics' => $analytics,
], JSON_UNESCAPED_UNICODE);
