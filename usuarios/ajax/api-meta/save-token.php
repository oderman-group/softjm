<?php
include("../../sesion.php");
require_once('config-helpers.php');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$pageToken = $data['page_token'] ?? null;
$pageId = $data['page_id'] ?? null;
$fbUserId = $data['fb_user_id'] ?? null;

if (!$pageToken || !$pageId) {
    sendError('Token o ID de Página faltante para el guardado.');
}

$sql_update = "UPDATE clientes_orion SET token_meta_fb_page = '{$pageToken}', page_id_meta_fb = '{$pageId}' WHERE clio_id = '".$_SESSION["dataAdicional"]["id_empresa"]."'";

$conexionBdAdmin->query($sql_update);

echo json_encode(['success' => true, 'message' => 'Token de Página guardado correctamente.']);
?>
