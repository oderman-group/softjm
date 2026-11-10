<?php
include("../sesion.php");

header('Content-Type: application/json; charset=utf-8');

$idPagina = 144;
include(RUTA_PROYECTO . "/usuarios/includes/verificar-paginas.php");

$idEmpresa = (int) ($_SESSION["dataAdicional"]["id_empresa"] ?? 0);
$idBodega = (int) ($_POST["id"] ?? $_GET["id"] ?? 0);
$tiendaWeb = isset($_POST["tienda_web"])
	? ((string) $_POST["tienda_web"] === "1" ? 1 : 0)
	: (isset($_GET["tienda_web"]) ? ((string) $_GET["tienda_web"] === "1" ? 1 : 0) : -1);

if ($idEmpresa <= 0 || $idBodega <= 0 || ($tiendaWeb !== 0 && $tiendaWeb !== 1)) {
	echo json_encode([
		'success' => false,
		'message' => 'Datos inválidos para marcar tienda web.',
	], JSON_UNESCAPED_UNICODE);
	exit;
}

$consulta = $conexionBdPrincipal->query(
	"SELECT bod_id, bod_nombre, bod_tienda_web
	 FROM bodegas
	 WHERE bod_id='" . $idBodega . "'
	   AND bod_id_empresa='" . $idEmpresa . "'
	 LIMIT 1"
);
$bodega = $consulta ? mysqli_fetch_array($consulta, MYSQLI_ASSOC) : null;
if (!$bodega) {
	echo json_encode([
		'success' => false,
		'message' => 'No se encontró la bodega.',
	], JSON_UNESCAPED_UNICODE);
	exit;
}

$ok = $conexionBdPrincipal->query(
	"UPDATE bodegas
	 SET bod_tienda_web='" . $tiendaWeb . "'
	 WHERE bod_id='" . $idBodega . "'
	   AND bod_id_empresa='" . $idEmpresa . "'
	 LIMIT 1"
);

if (!$ok) {
	echo json_encode([
		'success' => false,
		'message' => 'No se pudo actualizar la bodega.',
	], JSON_UNESCAPED_UNICODE);
	exit;
}

echo json_encode([
	'success' => true,
	'id' => $idBodega,
	'tienda_web' => $tiendaWeb,
	'message' => 'Bodega "' . (string) ($bodega['bod_nombre'] ?? '') . '" marcada como tienda web: ' . ($tiendaWeb === 1 ? 'SI' : 'NO') . '.',
], JSON_UNESCAPED_UNICODE);
exit;
