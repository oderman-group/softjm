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

function productosImportCodigoClave(string $referencia): string
{
	return strtoupper(trim($referencia));
}

/**
 * La primera fila de un código se procesa. Devuelve true si esa referencia ya salió en el archivo.
 *
 * @param array<string, true> $vistos
 */
function productosImportMarcarCodigo(array &$vistos, string $referencia): bool
{
	$clave = productosImportCodigoClave($referencia);
	if ($clave === '') {
		return false;
	}
	if (isset($vistos[$clave])) {
		return true;
	}
	$vistos[$clave] = true;
	return false;
}

function productosImportBorrarJob(string $jobId): void
{
	$path = productosImportJobPath($jobId);
	if (is_file($path)) {
		@unlink($path);
	}
}

/**
 * Quita los JSON de importación de este usuario al entrar o recargar la página.
 * El resultado visible ya está en sesión; el archivo solo sirve mientras el lote corre.
 */
function productosImportLimpiarJobsUsuario(int $userId, int $empresaId): void
{
	if ($userId <= 0 || $empresaId <= 0) {
		return;
	}
	$archivos = glob(productosImportJobsDir() . '/imp_*.json') ?: [];
	foreach ($archivos as $path) {
		if (!is_file($path)) {
			continue;
		}
		$data = json_decode((string) file_get_contents($path), true);
		if (!is_array($data)) {
			continue;
		}
		if ((int) ($data['user_id'] ?? 0) !== $userId || (int) ($data['empresa_id'] ?? 0) !== $empresaId) {
			continue;
		}
		@unlink($path);
	}
}

function productosImportIdComparable($valor): string
{
	$texto = trim((string) $valor);
	if ($texto === '' || !is_numeric($texto)) {
		return $texto;
	}
	return (string) (int) round((float) $texto);
}

function productosImportIdGuardar($excel, $actual): string
{
	$nuevo = productosImportIdComparable($excel);
	if ($nuevo === '') {
		return productosImportIdComparable($actual);
	}
	return $nuevo;
}

/**
 * Compara los campos que autorizan un UPDATE: nombre, línea, sublínea,
 * grupo, precio de lista, integrado Ofima y habilitado.
 * Una celda de código vacía conserva el valor actual y no cuenta como cambio.
 *
 * @return array{cambio:bool,precio:float,precio_cambio:bool,grupo1:string,categoria:string,marca:string,integrar_ofima:int}
 */
function productosImportDetectarCambios(array $datos, array $fila, int $habilitado, int $integrarOfima): array
{
	$precioActual = round((float) ($datos['prod_precio'] ?? 0), 2);
	$precioExcel = $fila['prod_precio'] ?? null;
	$precioNuevo = (is_numeric($precioExcel) && trim((string) $precioExcel) !== '')
		? round((float) $precioExcel, 2)
		: $precioActual;

	$grupo1 = productosImportIdGuardar($fila['prod_grupo1'] ?? '', $datos['prod_grupo1'] ?? '');
	$categoria = productosImportIdGuardar($fila['prod_categoria'] ?? '', $datos['prod_categoria'] ?? '');
	$marca = productosImportIdGuardar($fila['prod_marca'] ?? '', $datos['prod_marca'] ?? '');
	$integradoActual = ((int) ($datos['prod_integrado_ofima'] ?? 0) === 1) ? 1 : 0;
	$integradoNuevo = $integradoActual === 1 ? 1 : ($integrarOfima === 1 ? 1 : 0);

	$cambios = [
		trim((string) ($datos['prod_nombre'] ?? '')) !== trim((string) ($fila['prod_nombre'] ?? '')),
		productosImportIdComparable($datos['prod_grupo1'] ?? '') !== $grupo1,
		productosImportIdComparable($datos['prod_categoria'] ?? '') !== $categoria,
		productosImportIdComparable($datos['prod_marca'] ?? '') !== $marca,
		$precioActual !== $precioNuevo,
		$integradoActual !== $integradoNuevo,
		(((int) ($datos['prod_habilitado'] ?? 0) === 1) ? 1 : 0) !== ($habilitado === 1 ? 1 : 0),
	];

	return [
		'cambio' => in_array(true, $cambios, true),
		'precio' => $precioNuevo,
		'precio_cambio' => $precioActual !== $precioNuevo,
		'grupo1' => $grupo1,
		'categoria' => $categoria,
		'marca' => $marca,
		'integrar_ofima' => $integradoNuevo,
	];
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
		$detalle = productosImportItemDetalle([
			'prod_referencia' => $referencia,
			'prod_nombre' => $nombre,
		], $nroFila, $productoId);
		$cambios = productosImportDetectarCambios($datos, $fila, (int) $habilitado, (int) $integrarOfima);
		$integrarOfima = (int) ($cambios['integrar_ofima'] ?? 0);

		if (!$cambios['cambio']) {
			return [
				'ok' => true,
				'tipo' => 'sin_cambios',
				'detalle' => $detalle,
			];
		}

		$precioLista = $cambios['precio'];
		$precioAnterior = round((float) ($datos['prod_precio'] ?? 0), 2);
		if ($cambios['precio_cambio']) {
			$conexion->query(
				"INSERT INTO productos_historial_precios(php_producto, php_precio_anterior, php_precio_nuevo, php_usuario, php_causa)
				 VALUES('{$productoId}', '{$precioAnterior}', '{$precioLista}', '{$usuarioId}', 1)"
			);
		}

		$nombreEsc = $conexion->real_escape_string($nombre);
		$grupo1Esc = $conexion->real_escape_string($cambios['grupo1']);
		$categoriaEsc = $conexion->real_escape_string($cambios['categoria']);
		$marcaEsc = $conexion->real_escape_string($cambios['marca']);
		$actualizado = $conexion->query(
			"UPDATE productos SET
			prod_nombre='{$nombreEsc}',
			prod_grupo1='{$grupo1Esc}',
			prod_categoria='{$categoriaEsc}',
			prod_marca='{$marcaEsc}',
			prod_precio='{$precioLista}',
			" . ($integrarOfima === 1 ? '' : "prod_integrado_ofima='0',") . "
			prod_habilitado='" . (int) $habilitado . "',
			prod_ultima_actualizacion=now(),
			prod_ultima_actualizacion_usuario='{$usuarioId}'
			WHERE prod_id='{$productoId}'
			  AND prod_id_empresa='{$idEmpresa}'"
		);
		if (!$actualizado) {
			return [
				'ok' => false,
				'tipo' => 'invalido',
				'detalle' => $detalle + ['motivo' => 'No se pudo actualizar el producto', 'mensaje' => $conexion->error],
			];
		}

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
		'sin_cambios' => [],
		'duplicados' => [],
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
	} elseif (($proc['tipo'] ?? '') === 'sin_cambios') {
		$resultado['sin_cambios'][] = $proc['detalle'];
	} elseif (($proc['tipo'] ?? '') === 'duplicado') {
		$resultado['duplicados'][] = $proc['detalle'] ?? [];
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
