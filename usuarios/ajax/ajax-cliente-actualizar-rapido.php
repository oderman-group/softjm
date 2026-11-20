<?php
include('../sesion.php');
require_once RUTA_PROYECTO . '/usuarios/class/Cliente.php';

header('Content-Type: application/json; charset=utf-8');

if (!Modulos::validarRol([11], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para editar clientes.']);
    exit;
}

$clienteId = (int) ($_POST['id'] ?? 0);
$idEmpresaSesion = (int) $idEmpresa;
$nombre = trim((string) ($_POST['nombre'] ?? ''));
$documento = trim((string) ($_POST['documento'] ?? ''));
$tipoDocumento = (int) ($_POST['tipoDocumento'] ?? 0);
$ciudad = (int) ($_POST['ciudad'] ?? 0);
$email = trim((string) ($_POST['email'] ?? ''));
$telefono = trim((string) ($_POST['telefono'] ?? ''));
$celular = trim((string) ($_POST['celular'] ?? ''));
$direccion = trim((string) ($_POST['direccion'] ?? ''));
$referencia = trim((string) ($_POST['referencia'] ?? ''));
$nombreEvento = trim((string) ($_POST['nombreEvento'] ?? ''));
$asesor = (int) ($_POST['asesor'] ?? 0);

if ($clienteId <= 0 || $nombre === '' || $documento === '' || $ciudad <= 0) {
    echo json_encode(['success' => false, 'message' => 'Nombre, documento y ciudad son obligatorios.']);
    exit;
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'El email no es válido.']);
    exit;
}
if ($celular !== '' && !preg_match('/^\d{10}$/', $celular)) {
    echo json_encode(['success' => false, 'message' => 'El celular debe tener 10 dígitos.']);
    exit;
}

$stmtActual = $conexionBdPrincipal->prepare(
    'SELECT cli_id, cli_zona FROM clientes WHERE cli_id = ? AND cli_id_empresa = ? LIMIT 1'
);
$stmtActual->bind_param('ii', $clienteId, $idEmpresaSesion);
$stmtActual->execute();
$actual = $stmtActual->get_result()->fetch_assoc();
if (!$actual) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Cliente no encontrado.']);
    exit;
}
if (!Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    $permisos = Cliente::permisosVisibilidadListado((int) ($_SESSION['id'] ?? 0), $conexionBdPrincipal);
    if (!Cliente::usuarioPuedeVerClienteEnListado($permisos, (int) ($actual['cli_zona'] ?? 0), $clienteId)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Este cliente no está en sus zonas.']);
        exit;
    }
}

$stmtDup = $conexionBdPrincipal->prepare(
    'SELECT cli_id FROM clientes WHERE cli_usuario = ? AND cli_id_empresa = ? AND cli_id <> ? LIMIT 1'
);
$stmtDup->bind_param('sii', $documento, $idEmpresaSesion, $clienteId);
$stmtDup->execute();
if ($stmtDup->get_result()->fetch_assoc()) {
    echo json_encode(['success' => false, 'message' => 'Ya existe otro cliente con este número de documento.']);
    exit;
}

$stmtCiudad = $conexionBdAdmin->prepare('SELECT ciu_departamento FROM localidad_ciudades WHERE ciu_id = ? LIMIT 1');
$stmtCiudad->bind_param('i', $ciudad);
$stmtCiudad->execute();
$ciudadFila = $stmtCiudad->get_result()->fetch_assoc();
if (!$ciudadFila) {
    echo json_encode(['success' => false, 'message' => 'La ciudad seleccionada no es válida.']);
    exit;
}

$nombreMayus = strtoupper($nombre);
$emailMin = strtolower($email);
$direccionMayus = strtoupper($direccion);
$zona = (string) ($ciudadFila['ciu_departamento'] ?? '');
$evento = $referencia === '4' ? $nombreEvento : '';
if (!in_array($tipoDocumento, [2, 3, 4, 5, 6], true)) {
    $tipoDocumento = 1;
}

$stmt = $conexionBdPrincipal->prepare(
    'UPDATE clientes SET
        cli_nombre = ?, cli_usuario = ?, cli_tipo_documento = ?, cli_email = ?, cli_telefono = ?,
        cli_celular = ?, cli_direccion = ?, cli_ciudad = ?, cli_zona = ?, cli_referencia = ?,
        cli_nombre_evento = ?, cli_responsable = ?
     WHERE cli_id = ? AND cli_id_empresa = ? LIMIT 1'
);
$stmt->bind_param(
    'ssissssisssiii',
    $nombreMayus,
    $documento,
    $tipoDocumento,
    $emailMin,
    $telefono,
    $celular,
    $direccionMayus,
    $ciudad,
    $zona,
    $referencia,
    $evento,
    $asesor,
    $clienteId,
    $idEmpresaSesion
);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'No se pudo guardar el cliente.']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Cliente actualizado.',
    'cliente' => [
        'id' => $clienteId,
        'nombre' => $nombreMayus,
        'documento' => $documento,
        'email' => $emailMin,
    ],
]);
