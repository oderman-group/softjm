<?php
/**
 * Endpoint API para recibir inventario (existencias por producto y bodega) desde Ofima (Ofima → Orion).
 * POST /usuarios/api/orion/inventario-recibir.php
 * Autenticación: Bearer JWT (recomendado) o Basic Auth
 *
 * Body (un ítem):
 *   { "referencia": "COD001", "bodega_id": 1, "existencias": 50 }
 *   o { "producto_id": 123, "bodega_id": 1, "existencias": 50 }
 *
 * Body (lote):
 *   { "items": [ { "referencia": "COD001", "bodega_id": 1, "existencias": 50 }, ... ] }
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
require_once RUTA_PROYECTO . '/usuarios/class/Producto.php';
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

$items = isset($datos['items']) && is_array($datos['items']) ? $datos['items'] : array($datos);
$primerItem = isset($items[0]) ? $items[0] : null;
if (!$primerItem || (!isset($primerItem['referencia']) && !isset($primerItem['producto_id']))) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Campos requeridos faltantes',
        'error'   => 'Envíe un objeto con referencia o producto_id, bodega_id y existencias; o un array en "items"'
    ]);
    exit();
}

try {
    if ($idEmpresa === null) {
        $idEmpresa = (int) ($datos['id_empresa'] ?? 1);
        $apiService = new ApiOrionService($conexionBdPrincipal, $idEmpresa);
        if (!$apiService->verificarAutenticacionInventario($usuario, $password)) {
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

    $resultado = $apiService->recibirInventarioDeOfima($datos);

    if ($resultado['success']) {
        http_response_code(200);
        echo json_encode([
            'success'    => true,
            'message'    => 'Inventario sincronizado correctamente',
            'procesados' => $resultado['procesados'],
            'resultados' => $resultado['resultados']
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Error al procesar inventario',
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
