<?php
include('../sesion.php');
require_once RUTA_PROYECTO . '/usuarios/class/Cliente.php';

header('Content-Type: application/json; charset=utf-8');

$clienteId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$idEmpresaSesion = (int) $idEmpresa;

if ($clienteId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Cliente no válido.']);
    exit;
}

$stmt = $conexionBdPrincipal->prepare(
    'SELECT cli_id, cli_nombre, cli_usuario, cli_tipo_documento, cli_email, cli_telefono,
            cli_celular, cli_direccion, cli_ciudad, cli_zona, cli_referencia, cli_nombre_evento, cli_responsable
     FROM clientes WHERE cli_id = ? AND cli_id_empresa = ? LIMIT 1'
);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al consultar el cliente.']);
    exit;
}
$stmt->bind_param('ii', $clienteId, $idEmpresaSesion);
$stmt->execute();
$cliente = $stmt->get_result()->fetch_assoc();

if (!$cliente) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Cliente no encontrado.']);
    exit;
}

if (!Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    $permisos = Cliente::permisosVisibilidadListado((int) ($_SESSION['id'] ?? 0), $conexionBdPrincipal);
    if (!Cliente::usuarioPuedeVerClienteEnListado($permisos, (int) ($cliente['cli_zona'] ?? 0), (int) $cliente['cli_id'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Este cliente no está en sus zonas.']);
        exit;
    }
}

echo json_encode([
    'success' => true,
    'cliente' => [
        'id' => (int) $cliente['cli_id'],
        'nombre' => (string) ($cliente['cli_nombre'] ?? ''),
        'documento' => (string) ($cliente['cli_usuario'] ?? ''),
        'tipoDocumento' => (string) ($cliente['cli_tipo_documento'] ?? ''),
        'email' => (string) ($cliente['cli_email'] ?? ''),
        'telefono' => (string) ($cliente['cli_telefono'] ?? ''),
        'celular' => (string) ($cliente['cli_celular'] ?? ''),
        'direccion' => (string) ($cliente['cli_direccion'] ?? ''),
        'ciudad' => (string) ($cliente['cli_ciudad'] ?? ''),
        'referencia' => (string) ($cliente['cli_referencia'] ?? ''),
        'nombreEvento' => (string) ($cliente['cli_nombre_evento'] ?? ''),
        'asesor' => (string) ($cliente['cli_responsable'] ?? ''),
    ],
]);
