<?php
/**
 * Endpoint API para crear pedidos desde la tienda/CMS (Tienda → Orion [→ Ofima]).
 * POST /usuarios/api/orion/pedidos-recibir.php
 * Autenticación: Bearer JWT (recomendado) o Basic Auth
 *
 * Sin "origen" (o origen=ofima): solo crea el pedido en Orion.
 * Con origen=tienda_web|cms: crea en Orion y llama a Ofima /Integracion/Orion/Pedidos/Crear.
 *
 * Ítems: producto_id (prod_id) y/o referencia (prod_referencia); cantidad; valor/descuento/impuesto opcionales.
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
require_once RUTA_PROYECTO . '/usuarios/class/ApiOfimaClient.php';

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
            'error'   => 'Envíe Authorization: Bearer <token> o Basic Auth (usuario y contraseña)',
        ]);
        exit();
    }
}

$input = file_get_contents('php://input');
$datos = json_decode($input, true);

if (!$datos || !is_array($datos)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos',
        'error'   => 'JSON mal formado o vacío',
    ]);
    exit();
}

$tieneCliente = !empty(trim((string) ($datos['cliente_identificacion'] ?? $datos['identificacion'] ?? '')))
    || (int) ($datos['cliente_id'] ?? 0) > 0;
if (!$tieneCliente) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Campos requeridos faltantes',
        'error'   => 'Se requiere cliente_identificacion (o identificacion) o cliente_id',
    ]);
    exit();
}

if (empty($datos['items']) || !is_array($datos['items'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Campos requeridos faltantes',
        'error'   => 'Se requiere "items" (array con al menos un producto)',
    ]);
    exit();
}

try {
    if ($idEmpresa === null) {
        $idEmpresa = (int) ($datos['id_empresa'] ?? 1);
        $apiService = new ApiOrionService($conexionBdPrincipal, $idEmpresa);
        if (!$apiService->verificarAutenticacionPedidos($usuario, $password)) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Credenciales inválidas',
                'error'   => 'Usuario o contraseña incorrectos',
            ]);
            exit();
        }
    } else {
        $apiService = new ApiOrionService($conexionBdPrincipal, $idEmpresa);
    }

    $resultado = $apiService->recibirPedidoDeTienda($datos);

    if (empty($resultado['success'])) {
        http_response_code(400);
        echo json_encode([
            'success'   => false,
            'message'   => 'Error al procesar el pedido',
            'error'     => $resultado['error'] ?? 'Error desconocido',
            'pedido_id' => $resultado['pedido_id'] ?? null,
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $respuesta = [
        'success'       => true,
        'message'       => 'Pedido creado en Orion correctamente',
        'pedido_id'     => (int) $resultado['pedido_id'],
        'cotizacion_id' => (int) ($resultado['cotizacion_id'] ?? 0),
        'cliente_id'    => (int) ($resultado['cliente_id'] ?? 0),
        'items'         => (int) ($resultado['items'] ?? 0),
        'operacion'     => (string) ($resultado['operacion'] ?? 'CREATE'),
    ];

    if (ApiOrionService::esOrigenTiendaWeb($datos)) {
        $pedidoId = (int) $resultado['pedido_id'];
        $apiOfima = new ApiOfimaClient($conexionBdPrincipal, $idEmpresa);
        $syncOfima = $apiOfima->sincronizarPedido($pedidoId, 'CREATE');
        $respuesta['ofima'] = [
            'success'   => !empty($syncOfima['success']),
            'operacion' => 'CREATE',
            'message'   => !empty($syncOfima['success'])
                ? 'Pedido enviado a Ofima correctamente'
                : '',
            'error'     => !empty($syncOfima['success'])
                ? null
                : (string) ($syncOfima['error'] ?? $syncOfima['message'] ?? 'No se pudo sincronizar el pedido con Ofima'),
        ];
        if (!empty($syncOfima['success'])) {
            $respuesta['message'] = 'Pedido creado en Orion y enviado a Ofima';
        } else {
            $respuesta['message'] = 'Pedido creado en Orion; sync Ofima pendiente o con error';
        }
    }

    http_response_code(200);
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor',
        'error'   => $e->getMessage(),
    ]);
}
