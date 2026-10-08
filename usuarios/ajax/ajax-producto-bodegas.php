<?php
include("../sesion.php");
include_once RUTA_PROYECTO."/usuarios/includes/inventario-solo-ofima.php";

header('Content-Type: application/json; charset=utf-8');

if (!Modulos::validarRol([145], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para ver las bodegas del producto.']);
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

$soloOfima = inventarioSoloOfimaEntrada($conexionBdPrincipal, $idEmpresa);
$bodegas = [];
$consulta = $conexionBdPrincipal->query("SELECT prodb_id, prodb_existencias, prodb_fecha_actualizacion, bod_nombre, usr_nombre
	FROM productos_bodegas
	INNER JOIN productos ON prod_id=prodb_producto
	INNER JOIN bodegas ON bod_id=prodb_bodega
	LEFT JOIN usuarios ON usr_id=prodb_usuario_actualizacion
	WHERE prodb_producto='" . $id . "' AND prod_id_empresa='" . (int) $idEmpresa . "' AND bod_id_empresa='" . (int) $idEmpresa . "'
	ORDER BY bod_nombre");
while ($consulta && ($bod = mysqli_fetch_assoc($consulta))) {
    $bodegas[] = [
        'id' => (int) $bod['prodb_id'],
        'bodega' => (string) ($bod['bod_nombre'] ?? ''),
        'existencias' => (string) ($bod['prodb_existencias'] ?? '0'),
        'fecha' => (string) ($bod['prodb_fecha_actualizacion'] ?? ''),
        'responsable' => (string) ($bod['usr_nombre'] ?? ''),
    ];
}

echo json_encode([
    'success' => true,
    'producto' => (string) ($fila['prod_nombre'] ?? ''),
    'solo_ofima' => $soloOfima,
    'bodegas' => $bodegas,
]);
