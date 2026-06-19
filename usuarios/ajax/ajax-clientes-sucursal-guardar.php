<?php
include('../sesion.php');

header('Content-Type: application/json; charset=utf-8');

require_once RUTA_PROYECTO . '/usuarios/class/Sucursal.php';
require_once RUTA_PROYECTO . '/usuarios/class/Modulos.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$sucursalId = !empty($_POST['id']) && is_numeric($_POST['id']) ? intval($_POST['id']) : 0;
$clienteId  = !empty($_POST['cte']) && is_numeric($_POST['cte']) ? intval($_POST['cte']) : 0;
$modoEditar = $sucursalId > 0;

$rolRequerido = $modoEditar ? [85] : [84];
if (!Modulos::validarRol($rolRequerido, $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tiene permisos para esta operación.']);
    exit;
}

if ($clienteId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Cliente inválido.']);
    exit;
}

if (!Sucursal::clientePerteneceEmpresa($clienteId, intval($idEmpresa), $conexionBdPrincipal)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Cliente no encontrado.']);
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
if ($nombre === '') {
    echo json_encode(['success' => false, 'message' => 'El nombre de la sucursal es obligatorio.']);
    exit;
}

$celular = trim($_POST['celular'] ?? '');
if ($celular !== '' && !preg_match('/^\d{10}$/', $celular)) {
    echo json_encode(['success' => false, 'message' => 'El celular debe tener 10 dígitos sin espacios ni puntos.']);
    exit;
}

$datos = [
    'cliente'   => $clienteId,
    'nombre'    => $nombre,
    'telefono'  => trim($_POST['telefono'] ?? ''),
    'celular'   => $celular,
    'telefonos' => trim($_POST['telefonos'] ?? ''),
    'direccion' => trim($_POST['direccion'] ?? ''),
    'ciudad'    => !empty($_POST['ciudad']) && is_numeric($_POST['ciudad']) ? intval($_POST['ciudad']) : 0,
];

if ($modoEditar) {
    $existente = Sucursal::obtenerDetalle($sucursalId, $clienteId, intval($idEmpresa), $conexionBdPrincipal);
    if ($existente === null) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Sucursal no encontrada.']);
        exit;
    }

    Sucursal::actualizar($sucursalId, $datos, $conexionBdPrincipal);
    $idResultado = $sucursalId;
    $mensaje = 'Sucursal actualizada correctamente.';
} else {
    $idResultado = Sucursal::crear($datos, $conexionBdPrincipal);
    $mensaje = 'Sucursal creada correctamente.';
}

if ($idResultado <= 0) {
    echo json_encode(['success' => false, 'message' => 'No se pudo guardar la sucursal.']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => $mensaje,
    'id'      => $idResultado,
]);
