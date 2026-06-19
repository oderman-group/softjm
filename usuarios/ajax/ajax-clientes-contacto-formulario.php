<?php
include('../sesion.php');

header('Content-Type: application/json; charset=utf-8');

require_once RUTA_PROYECTO . '/usuarios/class/Contacto.php';
require_once RUTA_PROYECTO . '/usuarios/class/Modulos.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$clienteId   = !empty($_GET['cliente']) && is_numeric($_GET['cliente']) ? intval($_GET['cliente']) : 0;
$contactoId  = !empty($_GET['contacto']) && is_numeric($_GET['contacto']) ? intval($_GET['contacto']) : 0;
$modoEditar  = $contactoId > 0;

if ($clienteId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Cliente inválido.']);
    exit;
}

$rolRequerido = $modoEditar ? [46] : [45];
if (!Modulos::validarRol($rolRequerido, $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No tiene permisos para esta operación.']);
    exit;
}

if (!Contacto::clientePerteneceEmpresa($clienteId, intval($idEmpresa), $conexionBdPrincipal)) {
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

$contacto = null;
if ($modoEditar) {
    $contacto = Contacto::obtenerDetalle($contactoId, $clienteId, intval($idEmpresa), $conexionBdPrincipal);
    if ($contacto === null) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Contacto no encontrado.']);
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
    'contacto'   => $contacto,
    'sucursales' => Contacto::listarSucursalesCliente($clienteId, $conexionBdPrincipal),
    'urls' => [
        'paginaCompleta' => $modoEditar
            ? 'clientes-contactos-editar.php?id=' . $contactoId . '&cte=' . $clienteId
            : 'clientes-contactos-agregar.php?cte=' . $clienteId,
    ],
]);
