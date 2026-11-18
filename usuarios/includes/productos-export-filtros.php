<?php
/**
 * Construye el WHERE de exportación/conteo de productos.
 * Espera $conexionBdPrincipal y $idEmpresaExport (int).
 * Usa $_REQUEST (o $productosExportRequest si se define).
 * Deja $filtroProductosExport listo para concatenar tras WHERE.
 */
if (!isset($idEmpresaExport)) {
	$idEmpresaExport = (int) ($idEmpresa ?? ($_SESSION['dataAdicional']['id_empresa'] ?? 0));
}
$req = isset($productosExportRequest) && is_array($productosExportRequest) ? $productosExportRequest : $_REQUEST;

require_once RUTA_PROYECTO . '/usuarios/class/Producto.php';

$filtroProductosExport = " AND prod_id_empresa='" . (int) $idEmpresaExport . "'";

$filtroHabilitado = (isset($req['habilitado']) && (string) $req['habilitado'] === '0') ? 0 : 1;
$filtroProductosExport .= " AND prod_habilitado='" . $filtroHabilitado . "'";

if (!empty($req['vista']) && in_array($req['vista'], ['web', 'pdt', 'stock', 'utilidad'], true) && empty($req[$req['vista']])) {
	$req[$req['vista']] = 1;
}

if (!empty($req['grupo1']) && is_numeric($req['grupo1'])) {
	$filtroProductosExport .= " AND prod_grupo1='" . (int) $req['grupo1'] . "'";
}
if (!empty($req['grupo2']) && is_numeric($req['grupo2'])) {
	$filtroProductosExport .= " AND prod_categoria='" . (int) $req['grupo2'] . "'";
}
if (!empty($req['marca']) && is_numeric($req['marca'])) {
	$filtroProductosExport .= " AND prod_marca='" . (int) $req['marca'] . "'";
}
if (!empty($req['grupo3']) && is_numeric($req['grupo3'])) {
	$filtroProductosExport .= " AND prod_grupo3='" . (int) $req['grupo3'] . "'";
}
if (!empty($req['web']) && is_numeric($req['web'])) {
	$filtroProductosExport .= " AND prod_visible_web=1";
}
if (!empty($req['pdt']) && is_numeric($req['pdt'])) {
	$filtroProductosExport .= " AND prod_precio_predeterminado=1";
}
if (!empty($req['utilidad']) && (string) $req['utilidad'] === '1') {
	$filtroProductosExport .= " AND (prod_utilidad=0 OR prod_utilidad='' OR prod_utilidad IS NULL)";
}
if (!empty($req['stock']) && is_numeric($req['stock'])) {
	$filtroProductosExport .= " AND " . Producto::sqlSumaExistenciasHabilitadas('productos.prod_id') . " <= 0";
}
if (!empty($req['tipoProductos'])) {
	if ((string) $req['tipoProductos'] === '2') {
		$filtroProductosExport .= " AND prod_descuento_web>0";
	}
	if ((string) $req['tipoProductos'] === '3') {
		$filtroProductosExport .= " AND prod_precio_predeterminado=1";
	}
}
