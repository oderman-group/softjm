<?php
include('../sesion.php');

header('Content-Type: application/json; charset=utf-8');

$idEmpresaExport = (int) $idEmpresa;
include RUTA_PROYECTO . '/usuarios/includes/productos-export-filtros.php';

$sql = "SELECT COUNT(*) AS total
        FROM productos
        WHERE prod_id = prod_id
        {$filtroProductosExport}";

$resultado = $conexionBdPrincipal->query($sql);
$total = 0;
if ($resultado) {
	$row = $resultado->fetch_assoc();
	$total = (int) ($row['total'] ?? 0);
}

echo json_encode([
	'success' => true,
	'total' => $total,
	'habilitado' => (isset($_REQUEST['habilitado']) && (string) $_REQUEST['habilitado'] === '0') ? 0 : 1,
], JSON_UNESCAPED_UNICODE);
