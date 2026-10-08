<?php
include("../sesion.php");

header('Content-Type: application/json; charset=utf-8');

if (!Modulos::validarRol([209], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para ver la galería.']);
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$producto = $conexionBdPrincipal->query("SELECT prod_id, prod_nombre FROM productos WHERE prod_id='" . $id . "' AND prod_id_empresa='" . (int) $idEmpresa . "' LIMIT 1");
$fila = $producto ? mysqli_fetch_assoc($producto) : null;
if (!$fila) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado.']);
    exit;
}

$fotos = [];
$consulta = $conexionBdPrincipal->query("SELECT pgal_id, pgal_foto FROM productos_galeria WHERE pgal_producto='" . $id . "' ORDER BY pgal_id DESC");
while ($consulta && ($foto = mysqli_fetch_assoc($consulta))) {
    $nombre = basename((string) ($foto['pgal_foto'] ?? ''));
    if ($nombre === '' || $nombre === '.' || $nombre === '..') {
        continue;
    }
    $fotos[] = [
        'id' => (int) $foto['pgal_id'],
        'nombre' => $nombre,
    ];
}

echo json_encode([
    'success' => true,
    'producto' => (string) ($fila['prod_nombre'] ?? ''),
    'fotos' => $fotos,
]);
