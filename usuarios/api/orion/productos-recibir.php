<?php
/**
 * Endpoint API para recibir productos desde Ofima
 * POST /api/orion/productos-recibir.php
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../../conexion.php';
require_once RUTA_PROYECTO.'/usuarios/class/Producto.php';
require_once RUTA_PROYECTO.'/usuarios/class/ApiOrionService.php';

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
            'error' => 'Envíe Authorization: Bearer <token> o Basic Auth (usuario y contraseña)'
        ]);
        exit();
    }
}

// Obtener datos JSON del body
$input = file_get_contents('php://input');
$datos = json_decode($input, true);

if (!$datos) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos',
        'error' => 'JSON mal formado o vacío'
    ]);
    exit();
}

// Validar campos requeridos
if (empty(trim($datos['referencia'] ?? '')) || empty(trim($datos['nombre'] ?? ''))) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Campos requeridos faltantes',
        'error' => 'Los campos "referencia" y "nombre" son obligatorios'
    ]);
    exit();
}

// Validar rangos numéricos (costo >= 0, utilidad 0-100 o 0-1, precio >= 0 si se envía)
if (isset($datos['costo']) && (floatval($datos['costo']) < 0)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Validación fallida',
        'error' => 'El campo "costo" no puede ser negativo'
    ]);
    exit();
}
$utilidadVal = isset($datos['utilidad']) ? floatval($datos['utilidad']) : null;
if ($utilidadVal !== null && ($utilidadVal < 0 || $utilidadVal > 100)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Validación fallida',
        'error' => 'El campo "utilidad" debe estar entre 0 y 100 (porcentaje) o entre 0 y 1 (factor decimal).'
    ]);
    exit();
}
if (isset($datos['precio']) && (floatval($datos['precio']) < 0)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Validación fallida',
        'error' => 'El campo "precio" no puede ser negativo'
    ]);
    exit();
}

try {
    if ($idEmpresa === null) {
        $idEmpresa = (int) ($datos['id_empresa'] ?? 1);
        $apiService = new ApiOrionService($conexionBdPrincipal, $idEmpresa);
        if (!$apiService->verificarAutenticacion($usuario, $password)) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Credenciales inválidas',
                'error' => 'Usuario o contraseña incorrectos'
            ]);
            exit();
        }
    } else {
        $apiService = new ApiOrionService($conexionBdPrincipal, $idEmpresa);
    }
    
    // Procesar producto desde Ofima
    $resultado = $apiService->recibirProductoDeOfima($datos);
    
    if ($resultado['success']) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Producto sincronizado correctamente',
            'producto_id' => $resultado['producto_id'],
            'referencia' => $datos['referencia'],
            'operacion' => $resultado['operacion']
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Error al procesar el producto',
            'error' => $resultado['error']
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor',
        'error' => $e->getMessage()
    ]);
}

