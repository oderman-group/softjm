<?php
include('../sesion.php');

header('Content-Type: application/json; charset=utf-8');

require_once RUTA_PROYECTO . '/usuarios/class/Sucursal.php';
require_once RUTA_PROYECTO . '/usuarios/class/Modulos.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$clienteId    = !empty($_GET['cliente']) && is_numeric($_GET['cliente']) ? intval($_GET['cliente']) : 0;
$sucursalId   = !empty($_GET['sucursal']) && is_numeric($_GET['sucursal']) ? intval($_GET['sucursal']) : 0;
$modoEditar   = $sucursalId > 0;

if ($clienteId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Cliente inválido.']);
    exit;
}

$rolRequerido = $modoEditar ? [85] : [84];
if (!Modulos::validarRol($rolRequerido, $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tiene permisos para esta operación.']);
    exit;
}

if (!Sucursal::clientePerteneceEmpresa($clienteId, intval($idEmpresa), $conexionBdPrincipal)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Cliente no encontrado.']);
    exit;
}

$consultaCliente = $conexionBdPrincipal->query("
    SELECT cli_nombre FROM clientes
    WHERE cli_id = '" . $clienteId . "' AND cli_id_empresa = '" . intval($idEmpresa) . "'
    LIMIT 1
");
$cliente = mysqli_fetch_assoc($consultaCliente);

$sucursal = null;
if ($modoEditar) {
    $sucursal = Sucursal::obtenerDetalle($sucursalId, $clienteId, intval($idEmpresa), $conexionBdPrincipal);
    if ($sucursal === null) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Sucursal no encontrada.']);
        exit;
    }
}

echo json_encode([
    'success' => true,
    'modo'    => $modoEditar ? 'editar' : 'crear',
    'cliente' => [
        'id'     => $clienteId,
        'nombre' => $cliente['cli_nombre'] ?? '',
    ],
    'sucursal' => $sucursal,
    'ciudades' => Sucursal::listarCiudades($conexionBdAdmin),
    'urls' => [
        'paginaCompleta' => $modoEditar
            ? 'clientes-sucursales-editar.php?id=' . $sucursalId . '&cte=' . $clienteId
            : 'clientes-sucursales-agregar.php?cte=' . $clienteId,
    ],
]);
