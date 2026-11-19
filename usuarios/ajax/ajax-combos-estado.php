<?php
include('../sesion.php');
require_once RUTA_PROYECTO . '/usuarios/class/Combo.php';

header('Content-Type: application/json; charset=utf-8');

$idPagina = 175;
include RUTA_PROYECTO . '/usuarios/includes/verificar-paginas.php';

if (!Modulos::validarRol([175], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    echo json_encode(['success' => false, 'message' => 'No tiene permiso para cambiar el estado del combo.']);
    exit;
}

$comboId = (int) ($_POST['id'] ?? 0);
$estado = (int) ($_POST['estado'] ?? 0) === 1 ? 1 : 0;
$idEmpresa = (int) ($_SESSION['dataAdicional']['id_empresa'] ?? 0);

if (!Combo::establecerEstado($comboId, $estado, $conexionBdPrincipal, $idEmpresa)) {
    echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado del combo.']);
    exit;
}

echo json_encode([
    'success' => true,
    'estado' => $estado,
    'message' => $estado === 1 ? 'Combo activado.' : 'Combo desactivado.',
]);
