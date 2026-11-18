<?php
include('../sesion.php');
include_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';
include_once RUTA_PROYECTO . '/usuarios/includes/productos-importar-helpers.php';

header('Content-Type: application/json; charset=utf-8');
@set_time_limit(120);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['success' => false, 'message' => 'Método no permitido']);
	exit;
}

if (empty($_FILES['planilla']) || !is_uploaded_file($_FILES['planilla']['tmp_name'])) {
	echo json_encode(['success' => false, 'message' => 'Seleccione un archivo Excel (.xlsx)']);
	exit;
}

$archivo = (string) ($_FILES['planilla']['name'] ?? '');
$extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
if ($extension !== 'xlsx') {
	echo json_encode(['success' => false, 'message' => 'Solo se admite Excel (.xlsx)']);
	exit;
}

if ((int) ($_FILES['planilla']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
	echo json_encode(['success' => false, 'message' => 'Error al subir el archivo']);
	exit;
}

require RUTA_PROYECTO . '/librerias/Excel/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$destinoDir = RUTA_PROYECTO . '/usuarios/files/excel/';
if (!is_dir($destinoDir)) {
	@mkdir($destinoDir, 0775, true);
}
$nombreArchivo = $destinoDir . uniqid('importado_', true) . '.xlsx';

if (!move_uploaded_file($_FILES['planilla']['tmp_name'], $nombreArchivo)) {
	echo json_encode(['success' => false, 'message' => 'No se pudo guardar el archivo temporal']);
	exit;
}

try {
	$documento = IOFactory::load($nombreArchivo);
	$hoja = $documento->getSheet(0);
	$numFilas = (int) $hoja->getHighestDataRow();
	if ($numFilas < 2) {
		@unlink($nombreArchivo);
		echo json_encode(['success' => false, 'message' => 'El Excel no tiene filas de datos']);
		exit;
	}

	$filas = [];
	$vacias = 0;
	for ($f = 2; $f <= $numFilas; $f++) {
		$tieneDatos = false;
		foreach (['B', 'C', 'P', 'AA', 'AB'] as $col) {
			$valor = $hoja->getCell($col . $f)->getValue();
			if ($valor !== null && trim((string) $valor) !== '') {
				$tieneDatos = true;
				break;
			}
		}
		if (!$tieneDatos) {
			$vacias++;
			continue;
		}
		$filas[] = [
			'fila' => $f,
			'prod_referencia' => trim((string) $hoja->getCell('B' . $f)->getValue()),
			'prod_nombre' => trim((string) $hoja->getCell('C' . $f)->getValue()),
			'prod_grupo1' => $hoja->getCell('D' . $f)->getValue(),
			'prod_categoria' => $hoja->getCell('F' . $f)->getValue(),
			'prod_marca' => $hoja->getCell('H' . $f)->getValue(),
			'prod_costo_dolar' => $hoja->getCell('K' . $f)->getValue(),
			'prod_costo' => $hoja->getCell('M' . $f)->getValue(),
			'prod_utilidad' => $hoja->getCell('N' . $f)->getValue(),
			'prod_descuento1' => $hoja->getCell('O' . $f)->getValue(),
			'prod_precio' => $hoja->getCell('P' . $f)->getValue(),
			'prod_descuento2' => $hoja->getCell('Q' . $f)->getValue(),
			'prod_descuento_web' => $hoja->getCell('S' . $f)->getValue(),
			'prod_precio_fabrica' => $hoja->getCell('W' . $f)->getValue(),
			'prod_flete' => $hoja->getCell('X' . $f)->getValue(),
			'prod_aduana' => $hoja->getCell('Y' . $f)->getValue(),
			'integrar_ofima' => $hoja->getCell('AA' . $f)->getValue(),
			'prod_habilitado' => $hoja->getCell('AB' . $f)->getValue(),
		];
	}
} catch (Throwable $e) {
	@unlink($nombreArchivo);
	echo json_encode(['success' => false, 'message' => 'No se pudo leer el Excel: ' . $e->getMessage()]);
	exit;
}

@unlink($nombreArchivo);

if (!$filas) {
	echo json_encode(['success' => false, 'message' => 'No se encontraron filas con datos para importar']);
	exit;
}

$jobId = 'imp_' . date('YmdHis') . '_' . bin2hex(random_bytes(4));
$job = [
	'job_id' => $jobId,
	'user_id' => (int) ($_SESSION['id'] ?? 0),
	'empresa_id' => (int) $idEmpresa,
	'ofima_activa' => ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa) ? 1 : 0,
	'estado' => 'pending',
	'total' => count($filas),
	'procesados' => 0,
	'filas_vacias' => $vacias,
	'filas_excel' => $numFilas,
	'inicio' => time(),
	'ms_por_fila' => 0,
	'filas' => $filas,
	'resultado' => productosImportResultadoVacio(),
];

if (!productosImportGuardarJob($jobId, $job)) {
	echo json_encode(['success' => false, 'message' => 'No se pudo crear el trabajo de importación']);
	exit;
}

echo json_encode([
	'success' => true,
	'job_id' => $jobId,
	'total' => (int) $job['total'],
	'ofima_activa' => (int) $job['ofima_activa'],
	'message' => 'Archivo cargado. Iniciando procesamiento…',
], JSON_UNESCAPED_UNICODE);
