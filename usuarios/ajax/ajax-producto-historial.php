<?php
include("../sesion.php");

header('Content-Type: application/json; charset=utf-8');

if (!Modulos::validarRol([214], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para ver el historial de precios.']);
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

$origenes = $origenPrecioProducto ?? ["N/A", "Costo", "Utilidad", "Costo y utilidad", "Guardado de precios"];
$movimientos = [];
$consulta = $conexionBdPrincipal->query("SELECT php_precio_anterior, php_precio_nuevo, php_causa, php_fecha_cambio, usr_nombre
	FROM productos_historial_precios
	INNER JOIN productos ON prod_id=php_producto
	LEFT JOIN usuarios ON usr_id=php_usuario
	WHERE php_producto='" . $id . "' AND prod_id_empresa='" . (int) $idEmpresa . "'
	ORDER BY php_id DESC");
while ($consulta && ($mov = mysqli_fetch_assoc($consulta))) {
    $causa = (int) ($mov['php_causa'] ?? 0);
    $movimientos[] = [
        'anterior' => '$' . number_format((float) $mov['php_precio_anterior'], 0, ',', '.'),
        'nuevo' => '$' . number_format((float) $mov['php_precio_nuevo'], 0, ',', '.'),
        'origen' => (string) ($origenes[$causa] ?? 'N/A'),
        'fecha' => (string) ($mov['php_fecha_cambio'] ?? ''),
        'responsable' => (string) ($mov['usr_nombre'] ?? ''),
    ];
}

echo json_encode([
    'success' => true,
    'producto' => (string) ($fila['prod_nombre'] ?? ''),
    'movimientos' => $movimientos,
]);
