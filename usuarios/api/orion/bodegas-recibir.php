<?php
/**
 * Endpoint API para recibir bodegas desde Ofima (Ofima → Orion).
 * POST /usuarios/api/orion/bodegas-recibir.php
 * Autenticación: Bearer JWT (recomendado) o Basic Auth
 *
 * Body: { "referencia": "BOD-01", "nombre": "Bodega Principal", "ciudad": "05001" }
 * ciudad = código DIAN (ciu_cod_dian). Se busca en localidad_ciudades y se guarda bod_ciudad = ciu_id.
 * referencia = código de la bodega en Ofima (obligatorio para identificar y sincronizar).
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
require_once RUTA_PROYECTO . '/usuarios/class/ApiOrionService.php';

$idEmpresa = null;
$authHeader = '';
if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
} elseif (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
    $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
} elseif (function_exists('getallheaders')) {
    $headers = getallheaders();
    if (is_array($headers)) {
        foreach ($headers as $nombreHeader => $valorHeader) {
            if (strcasecmp((string) $nombreHeader, 'Authorization') === 0) {
                $authHeader = (string) $valorHeader;
                break;
            }
        }
    }
}

$tokenEnviado = stripos($authHeader, 'Bearer ') === 0;
if ($tokenEnviado) {
    $token = trim(substr($authHeader, 7));
    $payload = ApiOrionService::validarTokenJwt($token);
    if ($payload !== null) {
        $idEmpresa = (int) $payload['id_empresa'];
    }
}

if ($idEmpresa === null) {
    $usuario = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
    $password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;
    if ($tokenEnviado || !$usuario || !$password) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => $tokenEnviado ? 'Token inválido o vencido' : 'Autenticación requerida',
            'error'   => $tokenEnviado
                ? 'Vuelva a ejecutar Login JWT y use ese token en Authorization: Bearer'
                : 'Envíe Authorization: Bearer <token> o Basic Auth (usuario y contraseña)'
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

if (empty(trim(isset($datos['referencia']) ? $datos['referencia'] : '')) || empty(trim(isset($datos['nombre']) ? $datos['nombre'] : ''))) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Campos requeridos faltantes',
        'error'   => 'Los campos "referencia" y "nombre" son obligatorios'
    ]);
    exit();
}

try {
    if ($idEmpresa === null) {
        $idEmpresa = (int) (isset($datos['id_empresa']) ? $datos['id_empresa'] : 1);
        $apiService = new ApiOrionService($conexionBdPrincipal, $idEmpresa);
        if (!$apiService->verificarAutenticacionBodegas($usuario, $password)) {
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

    $resultado = $apiService->recibirBodegaDeOfima($datos);

    if ($resultado['success']) {
        http_response_code(200);
        echo json_encode([
            'success'    => true,
            'message'    => 'Bodega sincronizada correctamente',
            'bodega_id'  => $resultado['bodega_id'],
            'operacion'  => $resultado['operacion']
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Error al procesar la bodega',
            'error'   => $resultado['error']
        ]);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor',
        'error'   => $e->getMessage()
    ]);
}
