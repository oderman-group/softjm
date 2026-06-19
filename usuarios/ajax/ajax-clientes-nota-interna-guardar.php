<?php
include("../sesion.php");

header('Content-Type: application/json; charset=utf-8');

require_once RUTA_PROYECTO . '/usuarios/class/ClienteNotaInterna.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$clienteId = !empty($_POST['cliente']) && is_numeric($_POST['cliente']) ? intval($_POST['cliente']) : 0;
$nota      = trim($_POST['nota'] ?? '');

if ($clienteId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Cliente inválido.']);
    exit;
}

if ($nota === '') {
    echo json_encode(['success' => false, 'message' => 'La nota no puede estar vacía.']);
    exit;
}

if (!ClienteNotaInterna::clientePerteneceEmpresa($clienteId, $idEmpresa, $conexionBdPrincipal)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Cliente no encontrado.']);
    exit;
}

$notaId = ClienteNotaInterna::crear($clienteId, $_SESSION['id'], $idEmpresa, $nota, $conexionBdPrincipal);

if (!$notaId) {
    echo json_encode(['success' => false, 'message' => 'No se pudo guardar la nota.']);
    exit;
}

$nombreUsuario = !empty($datosUsuarioActual['usr_nombre'])
    ? $datosUsuarioActual['usr_nombre']
    : 'Usuario';

echo json_encode([
    'success' => true,
    'message' => 'Nota registrada correctamente.',
    'nota'    => ClienteNotaInterna::formatearNotaParaApi([
        'clin_id'               => $notaId,
        'clin_nota'             => $nota,
        'clin_tipo'             => ClienteNotaInterna::TIPO_TEXTO,
        'clin_audio_ruta'       => null,
        'clin_duracion_segundos'=> null,
        'clin_fecha_registro'   => date('Y-m-d H:i:s'),
        'usr_nombre'            => $nombreUsuario,
    ]),
]);
