<?php
include('../sesion.php');
include_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';
include_once RUTA_PROYECTO . '/usuarios/includes/productos-importar-helpers.php';

header('Content-Type: application/json; charset=utf-8');
@set_time_limit(120);

$jobId = trim((string) ($_POST['job_id'] ?? $_GET['job_id'] ?? ''));
$tamanoLote = (int) ($_POST['lote'] ?? $_GET['lote'] ?? 5);
if ($tamanoLote < 1) {
	$tamanoLote = 5;
}
if ($tamanoLote > 25) {
	$tamanoLote = 25;
}

if ($jobId === '') {
	echo json_encode(['success' => false, 'message' => 'job_id requerido']);
	exit;
}

$job = productosImportLeerJob($jobId);
if (!$job) {
	echo json_encode(['success' => false, 'message' => 'Trabajo no encontrado o expirado']);
	exit;
}

if ((int) ($job['user_id'] ?? 0) !== (int) ($_SESSION['id'] ?? 0)
	|| (int) ($job['empresa_id'] ?? 0) !== (int) $idEmpresa) {
	http_response_code(403);
	echo json_encode(['success' => false, 'message' => 'No autorizado para este trabajo']);
	exit;
}

if (($job['estado'] ?? '') === 'done') {
	echo json_encode([
		'success' => true,
		'done' => true,
		'job_id' => $jobId,
		'total' => (int) $job['total'],
		'procesados' => (int) $job['procesados'],
		'porcentaje' => 100,
		'eta_segundos' => 0,
		'resultado' => $job['resultado'] ?? productosImportResultadoVacio(),
	], JSON_UNESCAPED_UNICODE);
	exit;
}

$filas = $job['filas'] ?? [];
$total = (int) ($job['total'] ?? count($filas));
$procesados = (int) ($job['procesados'] ?? 0);
if (!isset($job['resultado']) || !is_array($job['resultado'])) {
	$job['resultado'] = productosImportResultadoVacio();
}

$ofimaActiva = !empty($job['ofima_activa']);
$usuarioId = (int) ($_SESSION['id'] ?? 0);
if (!isset($job['codigos_vistos']) || !is_array($job['codigos_vistos'])) {
	$job['codigos_vistos'] = [];
}
$t0 = microtime(true);
$finLote = min($total, $procesados + $tamanoLote);

for ($i = $procesados; $i < $finLote; $i++) {
	$fila = $filas[$i] ?? null;
	if (!is_array($fila)) {
		continue;
	}
	$referenciaFila = trim((string) ($fila['prod_referencia'] ?? ''));
	if (productosImportMarcarCodigo($job['codigos_vistos'], $referenciaFila)) {
		$detalleDuplicado = productosImportItemDetalle($fila, (int) ($fila['fila'] ?? 0));
		$detalleDuplicado['mensaje'] = 'Código repetido en el archivo. No se actualizó.';
		productosImportAcumularResultado($job['resultado'], [
			'ok' => true,
			'tipo' => 'duplicado',
			'detalle' => $detalleDuplicado,
		]);
		continue;
	}
	$proc = productosImportProcesarFila(
		$conexionBdPrincipal,
		(int) $idEmpresa,
		$ofimaActiva,
		$fila,
		$usuarioId
	);
	productosImportAcumularResultado($job['resultado'], $proc);
}

$procesados = $finLote;
$elapsedMs = (microtime(true) - $t0) * 1000;
$hechosEnLote = max(1, $finLote - (int) ($job['procesados'] ?? 0));
$msLote = $elapsedMs / $hechosEnLote;
$msPrev = (float) ($job['ms_por_fila'] ?? 0);
$job['ms_por_fila'] = $msPrev > 0 ? (($msPrev * 0.6) + ($msLote * 0.4)) : $msLote;
$job['procesados'] = $procesados;
$job['estado'] = ($procesados >= $total) ? 'done' : 'running';

$restantes = max(0, $total - $procesados);
$etaSeg = $job['ms_por_fila'] > 0 ? (int) ceil(($restantes * $job['ms_por_fila']) / 1000) : 0;
$porcentaje = $total > 0 ? (int) floor(($procesados / $total) * 100) : 100;

if ($job['estado'] === 'done') {
	$job['fin'] = time();
	$inicioJob = (int) ($job['inicio'] ?? time());
	$_SESSION['import_productos_resultado'] = [
		'fecha' => date('Y-m-d H:i:s'),
		'duracion_segundos' => max(0, (int) $job['fin'] - $inicioJob),
		'ofima_activa' => $ofimaActiva ? 1 : 0,
		'filas_excel' => (int) ($job['filas_excel'] ?? 0),
		'filas_con_datos' => $total,
		'filas_vacias' => (int) ($job['filas_vacias'] ?? 0),
		'creados' => $job['resultado']['creados'] ?? [],
		'actualizados' => $job['resultado']['actualizados'] ?? [],
		'sin_cambios' => $job['resultado']['sin_cambios'] ?? [],
		'duplicados' => $job['resultado']['duplicados'] ?? [],
		'invalidos' => $job['resultado']['invalidos'] ?? [],
		'ofima_creados' => $job['resultado']['ofima_creados'] ?? [],
		'ofima_actualizados' => $job['resultado']['ofima_actualizados'] ?? [],
		'ofima_errores' => $job['resultado']['ofima_errores'] ?? [],
		'ofima_omitidos' => $job['resultado']['ofima_omitidos'] ?? [],
	];
	productosImportBorrarJob($jobId);
} else {
	productosImportGuardarJob($jobId, $job);
}

echo json_encode([
	'success' => true,
	'done' => $job['estado'] === 'done',
	'job_id' => $jobId,
	'total' => $total,
	'procesados' => $procesados,
	'porcentaje' => min(100, $porcentaje),
	'eta_segundos' => $etaSeg,
	'ms_por_fila' => round((float) $job['ms_por_fila'], 1),
	'conteos' => [
		'creados' => count($job['resultado']['creados'] ?? []),
		'actualizados' => count($job['resultado']['actualizados'] ?? []),
		'sin_cambios' => count($job['resultado']['sin_cambios'] ?? []),
		'duplicados' => count($job['resultado']['duplicados'] ?? []),
		'ofima_creados' => count($job['resultado']['ofima_creados'] ?? []),
		'ofima_actualizados' => count($job['resultado']['ofima_actualizados'] ?? []),
		'ofima_errores' => count($job['resultado']['ofima_errores'] ?? []),
		'invalidos' => count($job['resultado']['invalidos'] ?? []),
	],
], JSON_UNESCAPED_UNICODE);
