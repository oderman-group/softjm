<?php
include('../sesion.php');

header('Content-Type: application/json; charset=utf-8');

require_once RUTA_PROYECTO . '/usuarios/class/ClienteNotaInterna.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$clienteId = !empty($_POST['cliente']) && is_numeric($_POST['cliente']) ? intval($_POST['cliente']) : 0;
$duracion  = !empty($_POST['duracion']) && is_numeric($_POST['duracion']) ? intval($_POST['duracion']) : 0;

if ($clienteId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Cliente inválido.']);
    exit;
}

if ($duracion < 1 || $duracion > 60) {
    echo json_encode(['success' => false, 'message' => 'La duración debe estar entre 1 y 60 segundos.']);
    exit;
}

if (empty($_FILES['audio']) || $_FILES['audio']['error'] !== UPLOAD_ERR_OK) {
    $codigoError = $_FILES['audio']['error'] ?? UPLOAD_ERR_NO_FILE;
    $mensajesError = [
        UPLOAD_ERR_INI_SIZE   => 'El archivo supera el límite permitido por el servidor.',
        UPLOAD_ERR_FORM_SIZE  => 'El archivo supera el límite del formulario.',
        UPLOAD_ERR_PARTIAL    => 'La subida del audio quedó incompleta.',
        UPLOAD_ERR_NO_FILE    => 'No se recibió el archivo de audio.',
        UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal del servidor.',
        UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir el archivo en disco.',
        UPLOAD_ERR_EXTENSION  => 'Una extensión de PHP bloqueó la subida.',
    ];
    echo json_encode([
        'success' => false,
        'message' => $mensajesError[$codigoError] ?? 'No se recibió el archivo de audio.',
    ]);
    exit;
}

if (!ClienteNotaInterna::clientePerteneceEmpresa($clienteId, $idEmpresa, $conexionBdPrincipal)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Cliente no encontrado.']);
    exit;
}

$rutaRelativa = ClienteNotaInterna::guardarArchivoAudio($_FILES['audio'], $idEmpresa, $clienteId);

if (!$rutaRelativa) {
    echo json_encode(['success' => false, 'message' => 'Formato de audio no válido o archivo demasiado grande.']);
    exit;
}

$notaId = ClienteNotaInterna::crearVoz(
    $clienteId,
    $_SESSION['id'],
    $idEmpresa,
    $rutaRelativa,
    $duracion,
    $conexionBdPrincipal
);

if (!$notaId) {
    @unlink(RUTA_PROYECTO . '/usuarios/files/' . $rutaRelativa);
    echo json_encode(['success' => false, 'message' => 'No se pudo guardar la nota de voz.']);
    exit;
}

$nombreUsuario = !empty($datosUsuarioActual['usr_nombre'])
    ? $datosUsuarioActual['usr_nombre']
    : 'Usuario';

echo json_encode([
    'success' => true,
    'message' => 'Nota de voz registrada correctamente.',
    'nota'    => [
        'id'                => $notaId,
        'tipo'              => ClienteNotaInterna::TIPO_VOZ,
        'nota'              => 'Nota de voz',
        'usuario'           => strtoupper($nombreUsuario),
        'fecha'             => date('Y-m-d H:i:s'),
        'audio_url'         => 'files/' . $rutaRelativa,
        'duracion_segundos' => $duracion,
    ],
]);
