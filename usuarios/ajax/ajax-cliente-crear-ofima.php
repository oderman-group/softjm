<?php
include('../sesion.php');
require_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';
require_once RUTA_PROYECTO . '/usuarios/class/Cliente.php';

header('Content-Type: application/json; charset=utf-8');

$clienteId = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
$idEmpresaSesion = (int) $idEmpresa;

if ($clienteId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Cliente no válido.']);
    exit;
}

if (!ofimaIntegracionActiva($conexionBdPrincipal, $idEmpresaSesion)) {
    echo json_encode([
        'success' => false,
        'message' => 'Integración Ofima desactivada',
        'integrado_ofima' => 0,
    ]);
    exit;
}

$stmt = $conexionBdPrincipal->prepare(
    'SELECT cli_id, cli_nombre, cli_usuario, cli_zona, cli_integrado_ofima
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

$restringirZona = !Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
if ($restringirZona) {
    $permisosZona = Cliente::permisosVisibilidadListado((int) ($_SESSION['id'] ?? 0), $conexionBdPrincipal);
    $puedeVer = Cliente::usuarioPuedeVerClienteEnListado(
        $permisosZona,
        (int) ($cliente['cli_zona'] ?? 0),
        (int) $cliente['cli_id']
    );
    if (!$puedeVer) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Este cliente no está en sus zonas.']);
        exit;
    }
}

if ((int) ($cliente['cli_integrado_ofima'] ?? 0) === 1) {
    echo json_encode([
        'success' => true,
        'message' => 'El cliente ya está integrado en Ofima.',
        'integrado_ofima' => 1,
        'id' => $clienteId,
        'nombre' => (string) ($cliente['cli_nombre'] ?? ''),
    ]);
    exit;
}

try {
    $syncOfima = Cliente::sincronizarConOfima($clienteId, $conexionBdPrincipal, $idEmpresaSesion, 'CREATE');
    $ok = !empty($syncOfima['success']);
    $mensaje = $syncOfima['notificacion']['mensaje']
        ?? $syncOfima['message']
        ?? $syncOfima['error']
        ?? ($ok ? 'Cliente creado en Ofima.' : 'No se pudo crear en Ofima.');
    $detalle = '';
    if (!$ok) {
        $datosOfima = $syncOfima['datos'] ?? null;
        if (is_array($datosOfima)) {
            $detalle = (string) json_encode($datosOfima, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } elseif (is_string($datosOfima) && trim($datosOfima) !== '') {
            $detalle = trim($datosOfima);
        } elseif (!empty($syncOfima['respuesta_raw']) && is_string($syncOfima['respuesta_raw'])) {
            $detalle = trim($syncOfima['respuesta_raw']);
        }
    }

    echo json_encode([
        'success' => $ok,
        'message' => $mensaje,
        'detalle' => $detalle,
        'codigo_http' => $syncOfima['codigo_http'] ?? null,
        'integrado_ofima' => $ok ? 1 : 0,
        'id' => $clienteId,
        'nombre' => (string) ($cliente['cli_nombre'] ?? ''),
    ]);
} catch (Exception $e) {
    error_log('Error crear cliente en Ofima: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Ofima: ' . $e->getMessage(),
        'integrado_ofima' => 0,
        'id' => $clienteId,
        'nombre' => (string) ($cliente['cli_nombre'] ?? ''),
    ]);
}
