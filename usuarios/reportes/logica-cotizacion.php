<?php
if(isset($_GET['cte'])){
	if ($_GET["cte"] == 1) {
		$_GET["id"] = base64_decode($_GET["id"]);
	} else {
		if ($_SESSION["id"] == "")
			header("Location:".RUTA_PROYECTO."/salir.php");
	}
}

/**
 * Prepara texto de cotización para TCPDF.
 * Conserva formato seguro del editor (p, br, negritas, listas, etc.),
 * convierte párrafos a saltos compatibles con celdas de tabla y escapa
 * el resto para que no rompa el HTML del PDF.
 */
function formatearTextoHtmlPdf($texto) {
	if ($texto === null || $texto === '') {
		return '';
	}

	$texto = (string) $texto;

	// <p> dentro de celdas de TCPDF suele fallar; convertir a saltos.
	$texto = preg_replace('/<\/p>\s*<p[^>]*>/i', '<br>', $texto);
	$texto = preg_replace('/<p[^>]*>/i', '', $texto);
	$texto = preg_replace('/<\/p>/i', '<br>', $texto);

	$allowedTags = '<br><br/><strong><b><em><i><u><s><strike><ul><ol><li>';
	$texto = strip_tags($texto, $allowedTags);

	// Escapar solo el texto (no las etiquetas permitidas) sin doble-encode de &nbsp; etc.
	$parts = preg_split('/(<[^>]+>)/', $texto, -1, PREG_SPLIT_DELIM_CAPTURE);
	$resultado = '';
	foreach ($parts as $part) {
		if ($part !== '' && isset($part[0]) && $part[0] === '<' && substr($part, -1) === '>') {
			$resultado .= $part;
			continue;
		}
		$resultado .= htmlspecialchars($part, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
	}

	$resultado = preg_replace("/\r\n|\r|\n/", '<br>', $resultado);
	$resultado = preg_replace('/(?:(?:&nbsp;|\xC2\xA0|\s)*<br\s*\/?>\s*)+$/i', '', $resultado);
	$resultado = preg_replace('/(?:&nbsp;|\xC2\xA0|\s)+$/u', '', $resultado);

	return $resultado;
}

$resultado = mysqli_fetch_array($conexionBdPrincipal->query("SELECT * FROM cotizacion
INNER JOIN clientes ON cli_id=cotiz_cliente
INNER JOIN sucursales ON sucu_id=cotiz_sucursal
INNER JOIN contactos ON cont_id=cotiz_contacto
INNER JOIN usuarios ON usr_id=cotiz_vendedor
WHERE cotiz_id='" . $_GET["id"] . "'"), MYSQLI_BOTH);

$total = number_format($resultado['cotiz_valor'] + ($resultado['cotiz_valor'] * ($resultado['cotiz_impuestos'] / 100)), 0, ",", ".");

if ($configuracion['conf_proveedor_cotizacion'] == 1) {
	$proveedor = mysqli_fetch_array($conexionBdPrincipal->query("SELECT * FROM proveedores WHERE prov_id='" . $resultado['cotiz_proveedor'] . "'"), MYSQLI_BOTH);
}