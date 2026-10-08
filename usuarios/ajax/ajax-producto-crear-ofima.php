<?php
include('../sesion.php');
require_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';
require_once RUTA_PROYECTO . '/usuarios/class/Producto.php';

header('Content-Type: application/json; charset=utf-8');

if (!Modulos::validarRol([38], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para editar productos.']);
    exit;
}

$productoId = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
$idEmpresaSesion = (int) $idEmpresa;

if ($productoId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Producto no válido.']);
    exit;
}

if (!ofimaIntegracionActiva($conexionBdPrincipal, $idEmpresaSesion)) {
    echo json_encode([
        'success' => false,
        'message' => 'Integración Ofima desactivada',
        'ofima' => ['sincronizado' => false, 'tipo' => 'warning', 'mensaje' => 'Integración Ofima desactivada'],
    ]);
    exit;
}

$stmt = $conexionBdPrincipal->prepare(
    'SELECT prod_id, prod_integrado_ofima, prod_referencia, prod_nombre
     FROM productos WHERE prod_id = ? AND prod_id_empresa = ? LIMIT 1'
);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al consultar el producto.']);
    exit;
}
$stmt->bind_param('ii', $productoId, $idEmpresaSesion);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();

if (!$producto) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado.']);
    exit;
}

if ((int) ($producto['prod_integrado_ofima'] ?? 0) === 1) {
    echo json_encode([
        'success' => true,
        'message' => 'El producto ya está integrado en Ofima',
        'integrado_ofima' => 1,
        'ofima' => [
            'sincronizado' => true,
            'tipo' => 'success',
            'mensaje' => 'El producto ya está integrado en Ofima',
        ],
    ]);
    exit;
}

try {
    $syncOfima = Producto::sincronizarConOfima($productoId, $conexionBdPrincipal, $idEmpresaSesion, 'CREATE');
    $ok = !empty($syncOfima['success']);
    $tipo = $syncOfima['notificacion']['tipo'] ?? ($ok ? 'success' : 'error');
    $mensaje = $syncOfima['notificacion']['mensaje']
        ?? $syncOfima['message']
        ?? $syncOfima['error']
        ?? ($ok ? 'Producto creado en Ofima.' : 'No se pudo crear en Ofima.');

    echo json_encode([
        'success' => $ok,
        'message' => $mensaje,
        'integrado_ofima' => $ok ? 1 : 0,
        'ofima' => [
            'sincronizado' => $ok,
            'tipo' => $tipo,
            'mensaje' => $mensaje,
            'codigo_http' => $syncOfima['codigo_http'] ?? null,
        ],
    ]);
} catch (Exception $e) {
    error_log('Error crear producto en Ofima: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Ofima: ' . $e->getMessage(),
        'integrado_ofima' => 0,
        'ofima' => [
            'sincronizado' => false,
            'tipo' => 'error',
            'mensaje' => 'Ofima: ' . $e->getMessage(),
        ],
    ]);
}
