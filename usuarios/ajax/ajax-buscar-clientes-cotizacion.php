<?php
include('../sesion.php');
require_once RUTA_PROYECTO . '/usuarios/class/Cliente.php';
require_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';

header('Content-Type: application/json; charset=utf-8');

$termino = trim((string) ($_GET['q'] ?? $_POST['q'] ?? ''));
$soloHabilitados = (string) ($_GET['habilitados'] ?? $_POST['habilitados'] ?? '') === '1';
$restringirZona = !Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$soloIntegrados = $soloHabilitados && ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa);
$excluirDealers = $soloHabilitados && !Modulos::validarRol([393], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$filas = Cliente::buscarParaCotizacion(
    $conexionBdPrincipal,
    (int) $idEmpresa,
    $termino,
    25,
    (int) ($_SESSION['id'] ?? 0),
    $restringirZona,
    $soloHabilitados,
    $soloIntegrados,
    $excluirDealers
);

$clientes = [];
foreach ($filas as $fila) {
    $clientes[] = [
        'id' => (int) $fila['cli_id'],
        'nombre' => (string) ($fila['cli_nombre'] ?? ''),
        'documento' => (string) ($fila['cli_usuario'] ?? ''),
        'email' => (string) ($fila['cli_email'] ?? ''),
        'integrado_ofima' => (int) ($fila['cli_integrado_ofima'] ?? 0) === 1 ? 1 : 0,
    ];
}

echo json_encode([
    'success' => true,
    'clientes' => $clientes,
]);
