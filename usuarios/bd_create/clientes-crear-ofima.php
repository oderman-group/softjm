<?php
require_once '../sesion.php';
require_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';
require_once RUTA_PROYECTO . '/usuarios/class/Cliente.php';

$clienteId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$idEmpresaSesion = (int) $idEmpresa;

if ($clienteId <= 0) {
    header('Location: ../clientes.php');
    exit;
}

if (!ofimaIntegracionActiva($conexionBdPrincipal, $idEmpresaSesion)) {
    header('Location: ../clientes-editar.php?id=' . $clienteId . '&ofima=omitido&ofima_msg=' . urlencode('Integración Ofima desactivada'));
    exit;
}

$stmt = $conexionBdPrincipal->prepare(
    'SELECT cli_id, cli_integrado_ofima FROM clientes WHERE cli_id = ? AND cli_id_empresa = ? LIMIT 1'
);
$stmt->bind_param('ii', $clienteId, $idEmpresaSesion);
$stmt->execute();
$cliente = $stmt->get_result()->fetch_assoc();

if (!$cliente) {
    header('Location: ../clientes.php');
    exit;
}

if ((int) ($cliente['cli_integrado_ofima'] ?? 0) === 1) {
    header('Location: ../clientes-editar.php?id=' . $clienteId . '&ofima=ok&ofima_msg=' . urlencode('El cliente ya está integrado en Ofima'));
    exit;
}

try {
    $syncOfima = Cliente::sincronizarConOfima($clienteId, $conexionBdPrincipal, $idEmpresaSesion, 'CREATE');
    $tipoNotif = $syncOfima['notificacion']['tipo'] ?? (!empty($syncOfima['success']) ? 'success' : 'error');
    $msgNotif = $syncOfima['notificacion']['mensaje'] ?? ($syncOfima['error'] ?? $syncOfima['message'] ?? 'Resultado Ofima');
    if ($tipoNotif === 'success') {
        $ofimaQuery = 'ofima=ok&ofima_msg=' . urlencode($msgNotif);
    } elseif ($tipoNotif === 'warning') {
        $ofimaQuery = 'ofima=omitido&ofima_msg=' . urlencode($msgNotif);
    } else {
        $ofimaQuery = 'ofima=error&ofima_msg=' . urlencode($msgNotif);
    }
} catch (Exception $e) {
    error_log('Error crear cliente en Ofima: ' . $e->getMessage());
    $ofimaQuery = 'ofima=error&ofima_msg=' . urlencode('Ofima: ' . $e->getMessage());
}

header('Location: ../clientes-editar.php?id=' . $clienteId . '&' . $ofimaQuery);
exit;
