<?php
include("../sesion.php");

header('Content-Type: application/json; charset=utf-8');

if (!Modulos::validarRol([38], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para editar productos.']);
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$consulta = $conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_id='" . $id . "' AND prod_id_empresa='" . (int) $idEmpresa . "' LIMIT 1");
$producto = $consulta ? mysqli_fetch_assoc($consulta) : null;
if (!$producto) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado.']);
    exit;
}

$utilidad = !empty($producto['prod_utilidad']) ? $producto['prod_utilidad'] : 0;
$precio = !empty($producto['prod_precio']) ? (float) $producto['prod_precio'] : 0;

echo json_encode([
    'success' => true,
    'producto' => [
        'id' => (int) $producto['prod_id'],
        'referencia' => (string) ($producto['prod_referencia'] ?? ''),
        'existencias' => (string) ($producto['prod_existencias'] ?? ''),
        'nombre' => (string) ($producto['prod_nombre'] ?? ''),
        'descripcion' => (string) ($producto['prod_descripcion_corta'] ?? ''),
        'descripcion_larga' => (string) ($producto['prod_descripcion_larga'] ?? ''),
        'proveedor' => (int) ($producto['prod_proveedor'] ?? 0),
        'grupo1' => (int) ($producto['prod_grupo1'] ?? 0),
        'categoria' => (int) ($producto['prod_categoria'] ?? 0),
        'grupo3' => (int) ($producto['prod_grupo3'] ?? 0),
        'marca' => (int) ($producto['prod_marca'] ?? 0),
        'costo' => (string) ($producto['prod_costo'] ?? ''),
        'costo_dolar' => (string) ($producto['prod_costo_dolar'] ?? ''),
        'utilidad' => (string) $utilidad,
        'precio' => number_format($precio, 0, ',', '.'),
        'precio_usd' => number_format((float) productosPrecioListaUSD($utilidad, $producto['prod_costo_dolar']), 0, ',', '.'),
        'dcto1' => (string) ($producto['prod_descuento1'] ?? ''),
        'comision' => (string) ($producto['prod_comision'] ?? ''),
    ],
]);
