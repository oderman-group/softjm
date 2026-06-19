<?php
include('../sesion.php');

header('Content-Type: application/json; charset=utf-8');

require_once RUTA_PROYECTO . '/usuarios/class/Contacto.php';
require_once RUTA_PROYECTO . '/usuarios/class/Modulos.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$contactoId = !empty($_POST['id']) && is_numeric($_POST['id']) ? intval($_POST['id']) : 0;
$clienteId  = !empty($_POST['cte']) && is_numeric($_POST['cte']) ? intval($_POST['cte']) : 0;
$modoEditar = $contactoId > 0;

$rolRequerido = $modoEditar ? [46] : [45];
if (!Modulos::validarRol($rolRequerido, $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tiene permisos para esta operación.']);
    exit;
}

if ($clienteId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Cliente inválido.']);
    exit;
}

if (!Contacto::clientePerteneceEmpresa($clienteId, intval($idEmpresa), $conexionBdPrincipal)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Cliente no encontrado.']);
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
if ($nombre === '') {
    echo json_encode(['success' => false, 'message' => 'El nombre del contacto es obligatorio.']);
    exit;
}

$celular = trim($_POST['celular'] ?? '');
if ($celular !== '' && !preg_match('/^\d{10}$/', $celular)) {
    echo json_encode(['success' => false, 'message' => 'El celular debe tener 10 dígitos sin espacios ni puntos.']);
    exit;
}

$email = trim($_POST['email'] ?? '');
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'El email no es válido.']);
    exit;
}

$datos = [
    'cliente'   => $clienteId,
    'nombre'    => $nombre,
    'email'     => $email,
    'telefono'  => trim($_POST['telefono'] ?? ''),
    'celular'   => $celular,
    'telefonos' => trim($_POST['telefonos'] ?? ''),
    'area'      => trim($_POST['area'] ?? ''),
    'cargo'     => trim($_POST['cargo'] ?? ''),
    'sucursal'  => !empty($_POST['sucursal']) && is_numeric($_POST['sucursal']) ? intval($_POST['sucursal']) : 0,
];

if ($modoEditar) {
    $existente = Contacto::obtenerDetalle($contactoId, $clienteId, intval($idEmpresa), $conexionBdPrincipal);
    if ($existente === null) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Contacto no encontrado.']);
        exit;
    }

    Contacto::actualizar($contactoId, $datos, $conexionBdPrincipal);
    $idResultado = $contactoId;
    $mensaje = 'Contacto actualizado correctamente.';
} else {
    $idResultado = Contacto::crear($datos, $conexionBdPrincipal);
    $mensaje = 'Contacto creado correctamente.';
}

if ($idResultado <= 0) {
    echo json_encode(['success' => false, 'message' => 'No se pudo guardar el contacto.']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => $mensaje,
    'id'      => $idResultado,
]);
