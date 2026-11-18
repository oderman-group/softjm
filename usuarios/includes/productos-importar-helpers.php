<?php
/**
 * Helpers compartidos para importación de productos por Excel (por código).
 */

function productosImportParseSiNo($valor): ?int
{
	$texto = strtoupper(trim((string) $valor));
	$texto = str_replace(['Í', 'É'], ['I', 'E'], $texto);
	if (in_array($texto, ['SI', 'S', '1', 'YES', 'Y'], true)) {
		return 1;
	}
	if (in_array($texto, ['NO', 'N', '0'], true)) {
		return 0;
	}
	return null;
}

function productosImportItemDetalle(array $row, int $fila = 0, int $id = 0): array
{
	return [
		'id' => $id > 0 ? $id : (int) ($row['prod_id'] ?? 0),
		'referencia' => (string) ($row['prod_referencia'] ?? ''),
		'nombre' => (string) ($row['prod_nombre'] ?? ''),
		'fila' => $fila,
	];
}

function productosImportJobsDir(): string
{
	$dir = RUTA_PROYECTO . '/usuarios/files/excel/jobs';
	if (!is_dir($dir)) {
		@mkdir($dir, 0775, true);
	}
	return $dir;
}

function productosImportJobPath(string $jobId): string
{
	$jobId = preg_replace('/[^a-zA-Z0-9_\-]/', '', $jobId);
	return productosImportJobsDir() . '/' . $jobId . '.json';
}

function productosImportLeerJob(string $jobId): ?array
{
	$path = productosImportJobPath($jobId);
	if (!is_file($path)) {
		return null;
	}
	$data = json_decode((string) file_get_contents($path), true);
	return is_array($data) ? $data : null;
}

function productosImportGuardarJob(string $jobId, array $job): bool
{
	$path = productosImportJobPath($jobId);
	$json = json_encode($job, JSON_UNESCAPED_UNICODE);
	return $json !== false && file_put_contents($path, $json, LOCK_EX) !== false;
}

/**
 * Procesa una fila ya normalizada del Excel.
 * @return array{ok:bool,tipo?:string,detalle?:array,ofima?:array}
 */
function productosImportProcesarFila(
	mysqli $conexion,
	int $idEmpresa,
	bool $ofimaActiva,
	array $fila,
	int $usuarioId
): array {
	require_once RUTA_PROYECTO . '/usuarios/class/Producto.php';

	$referencia = trim((string) ($fila['prod_referencia'] ?? ''));
	$nombre = trim((string) ($fila['prod_nombre'] ?? ''));
	$integrarOfima = productosImportParseSiNo($fila['integrar_ofima'] ?? null);
	$habilitado = productosImportParseSiNo($fila['prod_habilitado'] ?? null);
	$nroFila = (int) ($fila['fila'] ?? 0);

	$detalleBase = productosImportItemDetalle([
		'prod_referencia' => $referencia,
		'prod_nombre' => $nombre,
	], $nroFila);

	if ($referencia === '' || $nombre === '' || $integrarOfima === null || $habilitado === null) {
		return [
			'ok' => false,
			'tipo' => 'invalido',
			'detalle' => $detalleBase + ['motivo' => 'Campos obligatorios o SI/NO inválido'],
		];
	}

	$refEsc = $conexion->real_escape_string($referencia);
	$consulta = $conexion->query(
		"SELECT * FROM productos
		 WHERE prod_referencia='{$refEsc}'
		   AND prod_id_empresa='{$idEmpresa}'
		 LIMIT 1"
	);
	$datos = $consulta ? $consulta->fetch_assoc() : null;

	if ($datos && !empty($datos['prod_id'])) {
		$productoId = (int) $datos['prod_id'];
		$precioLista = is_numeric($fila['prod_precio'] ?? null)
			? (float) $fila['prod_precio']
			: (float) ($datos['prod_precio'] ?? 0);
		$precioAnterior = (float) ($datos['prod_precio'] ?? 0);

		if ($precioLista != $precioAnterior) {
			$conexion->query(
				"INSERT INTO productos_historial_precios(php_producto, php_precio_anterior, php_precio_nuevo, php_usuario, php_causa)
				 VALUES('{$productoId}', '{$precioAnterior}', '{$precioLista}', '{$usuarioId}', 1)"
			);
		}

		$conexion->query(
			"UPDATE productos SET
			prod_precio='{$precioLista}',
			prod_habilitado='" . (int) $habilitado . "',
			prod_ultima_actualizacion=now(),
			prod_ultima_actualizacion_usuario='{$usuarioId}'
			WHERE prod_id='{$productoId}'
			  AND prod_id_empresa='{$idEmpresa}'"
		);

		$detalle = productosImportItemDetalle([
			'prod_referencia' => $referencia,
			'prod_nombre' => $nombre,
		], $nroFila, $productoId);

		$ofimaInfo = productosImportSincronizarOfima(
			$conexion,
			$idEmpresa,
			$ofimaActiva,
			$integrarOfima === 1,
			$productoId,
			$detalle,
			((int) ($datos['prod_integrado_ofima'] ?? 0) === 1) ? 'UPDATE' : 'CREATE'
		);

		return [
			'ok' => true,
			'tipo' => 'actualizado',
			'detalle' => $detalle,
			'ofima' => $ofimaInfo,
		];
	}

	$precioLista = is_numeric($fila['prod_precio'] ?? null) ? (float) $fila['prod_precio'] : 0;
	$nombreEsc = $conexion->real_escape_string($nombre);

	$conexion->query(
		"INSERT INTO productos(
			prod_referencia, prod_nombre, prod_grupo1, prod_categoria, prod_marca,
			prod_costo, prod_descuento1, prod_descuento2, prod_utilidad,
			prod_precio_fabrica, prod_flete, prod_aduana, prod_costo_dolar,
			prod_visible, prod_habilitado, prod_id_empresa,
			prod_ultima_actualizacion, prod_ultima_actualizacion_usuario, prod_precio
		) VALUES (
			'{$refEsc}',
			'{$nombreEsc}',
			'" . $conexion->real_escape_string((string) ($fila['prod_grupo1'] ?? '')) . "',
			'" . $conexion->real_escape_string((string) ($fila['prod_categoria'] ?? '')) . "',
			'" . $conexion->real_escape_string((string) ($fila['prod_marca'] ?? '')) . "',
			'" . $conexion->real_escape_string((string) ($fila['prod_costo'] ?? '')) . "',
			'" . $conexion->real_escape_string((string) ($fila['prod_descuento1'] ?? '')) . "',
			'" . $conexion->real_escape_string((string) ($fila['prod_descuento2'] ?? '')) . "',
			'" . $conexion->real_escape_string((string) ($fila['prod_utilidad'] ?? '')) . "',
			'" . $conexion->real_escape_string((string) ($fila['prod_precio_fabrica'] ?? '')) . "',
			'" . $conexion->real_escape_string((string) ($fila['prod_flete'] ?? '')) . "',
			'" . $conexion->real_escape_string((string) ($fila['prod_aduana'] ?? '')) . "',
			'" . $conexion->real_escape_string((string) ($fila['prod_costo_dolar'] ?? '')) . "',
			1,
			'" . (int) $habilitado . "',
			'{$idEmpresa}',
			now(),
			'{$usuarioId}',
			'{$precioLista}'
		)"
	);

	$productoId = (int) $conexion->insert_id;
	if ($productoId <= 0) {
		return [
			'ok' => false,
			'tipo' => 'invalido',
			'detalle' => $detalleBase + ['motivo' => 'No se pudo crear el producto', 'mensaje' => $conexion->error],
		];
	}

	$conexion->query(
		"INSERT INTO productos_bodegas(prodb_producto, prodb_bodega, prodb_fecha_actualizacion, prodb_usuario_actualizacion)
		 VALUES ('{$productoId}', 1, now(), '{$usuarioId}')"
	);

	$detalle = productosImportItemDetalle([
		'prod_referencia' => $referencia,
		'prod_nombre' => $nombre,
	], $nroFila, $productoId);

	$ofimaInfo = productosImportSincronizarOfima(
		$conexion,
		$idEmpresa,
		$ofimaActiva,
		$integrarOfima === 1,
		$productoId,
		$detalle,
		'CREATE'
	);

	return [
		'ok' => true,
		'tipo' => 'creado',
		'detalle' => $detalle,
		'ofima' => $ofimaInfo,
	];
}

function productosImportSincronizarOfima(
	mysqli $conexion,
	int $idEmpresa,
	bool $ofimaActiva,
	bool $integrar,
	int $productoId,
	array $detalle,
	string $tipoOperacion
): array {
	if (!$integrar) {
		return ['estado' => 'omitido', 'detalle' => $detalle];
	}
	if (!$ofimaActiva) {
		return [
			'estado' => 'error',
			'detalle' => $detalle + [
				'operacion' => $tipoOperacion,
				'mensaje' => 'Ofima no está activo en la empresa',
			],
		];
	}

	try {
		$sync = Producto::sincronizarConOfima($productoId, $conexion, $idEmpresa, $tipoOperacion);
		if (!empty($sync['success'])) {
			return [
				'estado' => strtoupper($tipoOperacion) === 'UPDATE' ? 'actualizado' : 'creado',
				'detalle' => $detalle,
			];
		}
		return [
			'estado' => 'error',
			'detalle' => $detalle + [
				'operacion' => $tipoOperacion,
				'mensaje' => (string) ($sync['error'] ?? $sync['message'] ?? 'Error Ofima'),
			],
		];
	} catch (Throwable $e) {
		error_log('Import productos Ofima: ' . $e->getMessage());
		return [
			'estado' => 'error',
			'detalle' => $detalle + [
				'operacion' => $tipoOperacion,
				'mensaje' => $e->getMessage(),
			],
		];
	}
}

function productosImportResultadoVacio(): array
{
	return [
		'creados' => [],
		'actualizados' => [],
		'invalidos' => [],
		'ofima_creados' => [],
		'ofima_actualizados' => [],
		'ofima_errores' => [],
		'ofima_omitidos' => [],
	];
}

function productosImportAcumularResultado(array &$resultado, array $proc): void
{
	if (empty($proc['ok'])) {
		$resultado['invalidos'][] = $proc['detalle'] ?? [];
		return;
	}
	if (($proc['tipo'] ?? '') === 'creado') {
		$resultado['creados'][] = $proc['detalle'];
	} elseif (($proc['tipo'] ?? '') === 'actualizado') {
		$resultado['actualizados'][] = $proc['detalle'];
	}

	$ofima = $proc['ofima'] ?? null;
	if (!is_array($ofima)) {
		return;
	}
	$estado = (string) ($ofima['estado'] ?? '');
	$det = $ofima['detalle'] ?? ($proc['detalle'] ?? []);
	if ($estado === 'creado') {
		$resultado['ofima_creados'][] = $det;
	} elseif ($estado === 'actualizado') {
		$resultado['ofima_actualizados'][] = $det;
	} elseif ($estado === 'omitido') {
		$resultado['ofima_omitidos'][] = $det;
	} elseif ($estado === 'error') {
		$resultado['ofima_errores'][] = $det;
	}
}
