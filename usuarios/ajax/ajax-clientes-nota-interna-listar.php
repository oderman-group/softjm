<?php
include('../sesion.php');

header('Content-Type: application/json; charset=utf-8');

require_once RUTA_PROYECTO . '/usuarios/class/ClienteNotaInterna.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$clienteId = !empty($_GET['cliente']) && is_numeric($_GET['cliente']) ? intval($_GET['cliente']) : 0;

if ($clienteId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Cliente inválido.']);
    exit;
}

if (!ClienteNotaInterna::clientePerteneceEmpresa($clienteId, $idEmpresa, $conexionBdPrincipal)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Cliente no encontrado.']);
    exit;
}

$consultaCliente = $conexionBdPrincipal->query("
    SELECT cli_nombre
    FROM clientes
    WHERE cli_id = '" . $clienteId . "'
      AND cli_id_empresa = '" . intval($idEmpresa) . "'
    LIMIT 1
");
$cliente = mysqli_fetch_assoc($consultaCliente);

$notasRaw = ClienteNotaInterna::listarPorCliente($clienteId, $idEmpresa, $conexionBdPrincipal);
$notas    = [];

foreach ($notasRaw as $notaItem) {
    $notas[] = ClienteNotaInterna::formatearNotaParaApi($notaItem);
}

echo json_encode([
    'success' => true,
    'cliente' => [
        'id'     => $clienteId,
        'nombre' => $cliente['cli_nombre'] ?? '',
    ],
    'notas'   => $notas,
    'total'   => count($notas),
]);
