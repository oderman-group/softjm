<?php
/**
 * Endpoint API para recibir clientes desde Ofima (Ofima → Orion)
 * POST /usuarios/api/orion/clientes-recibir.php
 * Autenticación: Bearer JWT (recomendado) o Basic Auth
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../../conexion.php';
require_once RUTA_PROYECTO . '/usuarios/class/Cliente.php';
require_once RUTA_PROYECTO . '/usuarios/class/ApiOrionService.php';

$idEmpresa = null;
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';

if (stripos($authHeader, 'Bearer ') === 0) {
    $token = trim(substr($authHeader, 7));
    $payload = ApiOrionService::validarTokenJwt($token);
    if ($payload !== null) {
        $idEmpresa = (int) $payload['id_empresa'];
    }
}

if ($idEmpresa === null) {
    $usuario = $_SERVER['PHP_AUTH_USER'] ?? null;
    $password = $_SERVER['PHP_AUTH_PW'] ?? null;
    if (!$usuario || !$password) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Autenticación requerida',
            'error'   => 'Envíe Authorization: Bearer <token> o Basic Auth (usuario y contraseña)'
        ]);
        exit();
    }
}

$input = file_get_contents('php://input');
$datos = json_decode($input, true);

if (!$datos) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos',
        'error'   => 'JSON mal formado o vacío'
    ]);
    exit();
}

if (empty(trim($datos['identificacion'] ?? '')) || empty(trim($datos['nombre'] ?? ''))) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Campos requeridos faltantes',
        'error'   => 'Los campos "identificacion" (NIT/documento) y "nombre" son obligatorios'
    ]);
    exit();
}

try {
    if ($idEmpresa === null) {
        $idEmpresa = (int) ($datos['id_empresa'] ?? 1);
        $apiService = new ApiOrionService($conexionBdPrincipal, $idEmpresa);
        if (!$apiService->verificarAutenticacionCliente($usuario, $password)) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Credenciales inválidas',
                'error'   => 'Usuario o contraseña incorrectos'
            ]);
            exit();
        }
    } else {
        $apiService = new ApiOrionService($conexionBdPrincipal, $idEmpresa);
    }

    $resultado = $apiService->recibirClienteDeOfima($datos);

    if ($resultado['success']) {
        http_response_code(200);
        echo json_encode([
            'success'    => true,
            'message'    => 'Cliente sincronizado correctamente',
            'cliente_id' => $resultado['cliente_id'],
            'identificacion' => $datos['identificacion'],
            'operacion'  => $resultado['operacion']
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Error al procesar el cliente',
            'error'   => $resultado['error']
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor',
        'error'   => $e->getMessage()
    ]);
}
