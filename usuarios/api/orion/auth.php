<?php
/**
 * Login API Orion (Ofima → Orion).
 * POST con Basic Auth o JSON { usuario, password, id_empresa? }.
 * Devuelve un JWT para usar en Authorization: Bearer <token> en el resto de endpoints.
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido. Use POST.']);
    exit();
}

require_once '../../../conexion.php';
require_once RUTA_PROYECTO . '/usuarios/class/JwtHelper.php';

$usuario = $_SERVER['PHP_AUTH_USER'] ?? null;
$password = $_SERVER['PHP_AUTH_PW'] ?? null;

$input = file_get_contents('php://input');
$body = $input ? json_decode($input, true) : null;
if (!is_array($body)) {
    $body = [];
}

if (!$usuario || $password === null) {
    $usuario = $body['usuario'] ?? $body['user'] ?? $usuario;
    $password = $body['password'] ?? $body['clave'] ?? $password;
}

if (empty($usuario) || $password === null || $password === '') {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Credenciales requeridas',
        'error'   => 'Envíe Basic Auth o JSON con usuario y password'
    ]);
    exit();
}

$idEmpresaSolicitado = isset($body['id_empresa']) ? (int) $body['id_empresa'] : null;
if ($idEmpresaSolicitado <= 0) {
    $idEmpresaSolicitado = null;
}

$query = "SELECT apic_id_empresa, apic_usuario, apic_password 
          FROM api_configuracion 
          WHERE apic_direccion = 'ofima_orion' 
          AND apic_activo = 1";
$params = [];
$types = '';
if ($idEmpresaSolicitado > 0) {
    $query .= " AND apic_id_empresa = ?";
    $params[] = $idEmpresaSolicitado;
    $types .= 'i';
}
$query .= " ORDER BY apic_id_empresa LIMIT 100";

$stmt = $conexionBdPrincipal->prepare($query);
if ($types !== '' && !empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$idEmpresa = null;
while ($row = $result->fetch_assoc()) {
    if ($row['apic_usuario'] !== $usuario) {
        continue;
    }
    $passwordDecoded = base64_decode($row['apic_password'], true);
    if ($passwordDecoded === false) {
        $passwordDecoded = $row['apic_password'];
    }
    if ($passwordDecoded === $password) {
        $idEmpresa = (int) $row['apic_id_empresa'];
        break;
    }
}

if ($idEmpresa === null) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Credenciales inválidas',
        'error'   => 'Usuario o contraseña incorrectos'
    ]);
    exit();
}

try {
    $ttl = 43200; // 12 horas (en segundos)
    $token = JwtHelper::encode([
        'id_empresa' => $idEmpresa,
        'sub'        => $usuario,
    ], $ttl);

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Token generado correctamente',
        'token'   => $token,
        'expires_in' => $ttl,
        'token_type' => 'Bearer',
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al generar token',
        'error'   => $e->getMessage(),
    ]);
}
