<?php
include("sesion.php");
include_once RUTA_PROYECTO . "/usuarios/class/Producto.php";
include_once RUTA_PROYECTO . "/usuarios/includes/api-ofima-conexion.php";
include_once RUTA_PROYECTO . "/usuarios/includes/productos-importar-helpers.php";

$idPagina = 207;
include("includes/verificar-paginas.php");

require '../librerias/Excel/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

@set_time_limit(0);

$temName = $_FILES['planilla']['tmp_name'];
$archivo = $_FILES['planilla']['name'];
$destino = "files/excel/";
$explode = explode(".", $archivo);
$extension = end($explode);
$fullArchivo = uniqid('importado_') . "." . $extension;
$nombreArchivo = $destino . $fullArchivo;
$idEmpresaImport = (int) $idEmpresa;
$ofimaActiva = ofimaIntegracionActiva($conexionBdPrincipal, $idEmpresaImport);

$parseSiNo = static function ($valor): ?int {
	$texto = strtoupper(trim((string) $valor));
	$texto = str_replace(['Í', 'É'], ['I', 'E'], $texto);
	if (in_array($texto, ['SI', 'S', '1', 'YES', 'Y'], true)) {
		return 1;
	}
	if (in_array($texto, ['NO', 'N', '0'], true)) {
		return 0;
	}
	return null;
};

$itemProducto = static function (array $row, int $fila = 0, int $id = 0): array {
	return [
		'id' => $id > 0 ? $id : (int) ($row['prod_id'] ?? 0),
		'referencia' => (string) ($row['prod_referencia'] ?? ''),
		'nombre' => (string) ($row['prod_nombre'] ?? ''),
		'fila' => $fila,
	];
};

if ($extension == 'xlsx') {

	if ($_FILES['planilla']['error'] != UPLOAD_ERR_OK) {
		$message = 'Ha ocurrido un error al subir el archivo: ' . $_FILES['planilla']['error'];
		echo '<script type="text/javascript">window.location.href="productos-importar.php?error=3&message=' . rawurlencode($message) . '";</script>';
		exit();
	}

	if (move_uploaded_file($temName, $nombreArchivo)) {

		if ($_FILES['planilla']['error'] === UPLOAD_ERR_OK) {

			$documento = IOFactory::load($nombreArchivo);
			$hojaActual = $documento->getSheet(0);
			$numFilas = (int) $hojaActual->getHighestDataRow();
			if ($numFilas < 2) {
				$numFilas = 1;
			}

			$f = 2;
			$creadosOrion = [];
			$actualizadosOrion = [];
			$sinCambiosOrion = [];
			$duplicadosOrion = [];
			$codigosVistos = [];
			$ofimaCreados = [];
			$ofimaActualizados = [];
			$ofimaErrores = [];
			$ofimaOmitidos = [];
			$filasInvalidas = [];
			$filasVaciasOmitidas = 0;
			$filasConDatos = 0;

			$filaTieneDatos = static function ($hoja, $fila): bool {
				foreach (['B', 'C', 'P', 'AA', 'AB'] as $col) {
					$valor = $hoja->getCell($col . $fila)->getValue();
					if ($valor !== null && trim((string) $valor) !== '') {
						return true;
					}
				}
				return false;
			};

			$importInicioTs = time();
			while ($f <= $numFilas) {
				if (!$filaTieneDatos($hojaActual, $f)) {
					$filasVaciasOmitidas++;
					$f++;
					continue;
				}
				$filasConDatos++;

				$arrayIndividual = [
					'prod_referencia' => trim((string) $hojaActual->getCell('B' . $f)->getValue()),
					'prod_nombre' => trim((string) $hojaActual->getCell('C' . $f)->getValue()),
					'prod_grupo1' => $hojaActual->getCell('D' . $f)->getValue(),
					'prod_categoria' => $hojaActual->getCell('F' . $f)->getValue(),
					'prod_marca' => $hojaActual->getCell('H' . $f)->getValue(),
					'prod_costo_dolar' => $hojaActual->getCell('K' . $f)->getValue(),
					'prod_costo' => $hojaActual->getCell('M' . $f)->getValue(),
					'prod_utilidad' => $hojaActual->getCell('N' . $f)->getValue(),
					'prod_descuento1' => $hojaActual->getCell('O' . $f)->getValue(),
					'prod_precio' => $hojaActual->getCell('P' . $f)->getValue(),
					'prod_descuento2' => $hojaActual->getCell('Q' . $f)->getValue(),
					'prod_descuento_web' => $hojaActual->getCell('S' . $f)->getValue(),
					'prod_precio_fabrica' => $hojaActual->getCell('W' . $f)->getValue(),
					'prod_flete' => $hojaActual->getCell('X' . $f)->getValue(),
					'prod_aduana' => $hojaActual->getCell('Y' . $f)->getValue(),
					'integrar_ofima' => $hojaActual->getCell('AA' . $f)->getValue(),
					'prod_habilitado' => $hojaActual->getCell('AB' . $f)->getValue(),
				];

				$integrarOfima = $parseSiNo($arrayIndividual['integrar_ofima']);
				$habilitado = $parseSiNo($arrayIndividual['prod_habilitado']);
				$todoBien = $arrayIndividual['prod_referencia'] !== ''
					&& $arrayIndividual['prod_nombre'] !== ''
					&& $integrarOfima !== null
					&& $habilitado !== null;

				$detalleBase = $itemProducto($arrayIndividual, $f);

				if (!$todoBien) {
					$filasInvalidas[] = $detalleBase + ['motivo' => 'Campos obligatorios o SI/NO inválido'];
					$f++;
					continue;
				}

				if (productosImportMarcarCodigo($codigosVistos, (string) $arrayIndividual['prod_referencia'])) {
					$duplicadosOrion[] = $detalleBase + ['mensaje' => 'Código repetido en el archivo. No se actualizó.'];
					$f++;
					continue;
				}

				$refEsc = $conexionBdPrincipal->real_escape_string($arrayIndividual['prod_referencia']);
				$consultaProducto = $conexionBdPrincipal->query(
					"SELECT * FROM productos
					 WHERE prod_referencia='{$refEsc}'
					   AND prod_id_empresa='{$idEmpresaImport}'
					 LIMIT 1"
				);
				$datos = $consultaProducto ? mysqli_fetch_array($consultaProducto, MYSQLI_BOTH) : null;

				if ($datos && !empty($datos['prod_id'])) {
					$filaProc = $arrayIndividual;
					$filaProc['fila'] = $f;
					$proc = productosImportProcesarFila(
						$conexionBdPrincipal,
						$idEmpresaImport,
						$ofimaActiva,
						$filaProc,
						(int) ($_SESSION['id'] ?? 0)
					);
					if (($proc['tipo'] ?? '') === 'sin_cambios') {
						$sinCambiosOrion[] = $proc['detalle'] ?? [];
					} elseif (($proc['tipo'] ?? '') === 'actualizado') {
						$actualizadosOrion[] = $proc['detalle'] ?? [];
					} elseif (empty($proc['ok'])) {
						$filasInvalidas[] = $proc['detalle'] ?? $detalleBase;
					}
					$ofima = $proc['ofima'] ?? null;
					if (is_array($ofima)) {
						$estadoOfima = (string) ($ofima['estado'] ?? '');
						$detOfima = $ofima['detalle'] ?? ($proc['detalle'] ?? []);
						if ($estadoOfima === 'creado') {
							$ofimaCreados[] = $detOfima;
						} elseif ($estadoOfima === 'actualizado') {
							$ofimaActualizados[] = $detOfima;
						} elseif ($estadoOfima === 'omitido') {
							$ofimaOmitidos[] = $detOfima;
						} elseif ($estadoOfima === 'error') {
							$ofimaErrores[] = $detOfima;
						}
					}
				} else {
					// Crear: prod_id autoincrement; el código es la clave nueva.
					$precioLista = is_numeric($arrayIndividual['prod_precio'])
						? (float) $arrayIndividual['prod_precio']
						: 0;
					$nombreEsc = $conexionBdPrincipal->real_escape_string($arrayIndividual['prod_nombre']);

					try {
						$conexionBdPrincipal->query(
							"INSERT INTO productos(
								prod_referencia, prod_nombre, prod_grupo1, prod_categoria, prod_marca,
								prod_costo, prod_descuento1, prod_descuento2, prod_utilidad,
								prod_precio_fabrica, prod_flete, prod_aduana, prod_costo_dolar,
								prod_visible, prod_habilitado, prod_id_empresa,
								prod_ultima_actualizacion, prod_ultima_actualizacion_usuario, prod_precio
							) VALUES (
								'{$refEsc}',
								'{$nombreEsc}',
								'" . $conexionBdPrincipal->real_escape_string((string) $arrayIndividual['prod_grupo1']) . "',
								'" . $conexionBdPrincipal->real_escape_string((string) $arrayIndividual['prod_categoria']) . "',
								'" . $conexionBdPrincipal->real_escape_string((string) $arrayIndividual['prod_marca']) . "',
								'" . $conexionBdPrincipal->real_escape_string((string) $arrayIndividual['prod_costo']) . "',
								'" . $conexionBdPrincipal->real_escape_string((string) $arrayIndividual['prod_descuento1']) . "',
								'" . $conexionBdPrincipal->real_escape_string((string) $arrayIndividual['prod_descuento2']) . "',
								'" . $conexionBdPrincipal->real_escape_string((string) $arrayIndividual['prod_utilidad']) . "',
								'" . $conexionBdPrincipal->real_escape_string((string) $arrayIndividual['prod_precio_fabrica']) . "',
								'" . $conexionBdPrincipal->real_escape_string((string) ($arrayIndividual['prod_flete'] ?? '')) . "',
								'" . $conexionBdPrincipal->real_escape_string((string) ($arrayIndividual['prod_aduana'] ?? '')) . "',
								'" . $conexionBdPrincipal->real_escape_string((string) $arrayIndividual['prod_costo_dolar']) . "',
								1,
								'" . (int) $habilitado . "',
								'{$idEmpresaImport}',
								now(),
								'" . (int) $_SESSION["id"] . "',
								'{$precioLista}'
							)"
						);

						$productoId = (int) mysqli_insert_id($conexionBdPrincipal);
						$detalle = $itemProducto($arrayIndividual, $f, $productoId);
						$creadosOrion[] = $detalle;

						if ($productoId > 0) {
							$conexionBdPrincipal->query(
								"INSERT INTO productos_bodegas(prodb_producto, prodb_bodega, prodb_fecha_actualizacion, prodb_usuario_actualizacion)
								 VALUES ('{$productoId}', 1, now(), '" . (int) $_SESSION["id"] . "')"
							);
						}

						if ($integrarOfima === 1 && $ofimaActiva && $productoId > 0) {
							try {
								$sync = Producto::sincronizarConOfima($productoId, $conexionBdPrincipal, $idEmpresaImport, 'CREATE');
								if (!empty($sync['success'])) {
									$ofimaCreados[] = $detalle;
								} else {
									$ofimaErrores[] = $detalle + [
										'operacion' => 'CREATE',
										'mensaje' => (string) ($sync['error'] ?? $sync['message'] ?? 'Error Ofima'),
									];
								}
							} catch (Throwable $e) {
								$ofimaErrores[] = $detalle + [
									'operacion' => 'CREATE',
									'mensaje' => $e->getMessage(),
								];
								error_log('Import productos Ofima CREATE: ' . $e->getMessage());
							}
						} elseif ($integrarOfima !== 1) {
							$ofimaOmitidos[] = $detalle;
						} elseif (!$ofimaActiva && $integrarOfima === 1) {
							$ofimaErrores[] = $detalle + [
								'operacion' => 'CREATE',
								'mensaje' => 'Ofima no está activo en la empresa',
							];
						}
					} catch (Exception $e) {
						$filasInvalidas[] = $detalleBase + ['mensaje' => $e->getMessage(), 'motivo' => 'Error al crear'];
					}
				}

				$f++;
			}

			$_SESSION['import_productos_resultado'] = [
				'fecha' => date('Y-m-d H:i:s'),
				'duracion_segundos' => max(0, time() - (int) ($importInicioTs ?? time())),
				'ofima_activa' => $ofimaActiva ? 1 : 0,
				'filas_excel' => $numFilas,
				'filas_con_datos' => $filasConDatos,
				'filas_vacias' => $filasVaciasOmitidas,
				'creados' => $creadosOrion,
				'actualizados' => $actualizadosOrion,
				'sin_cambios' => $sinCambiosOrion,
				'duplicados' => $duplicadosOrion,
				'invalidos' => $filasInvalidas,
				'ofima_creados' => $ofimaCreados,
				'ofima_actualizados' => $ofimaActualizados,
				'ofima_errores' => $ofimaErrores,
				'ofima_omitidos' => $ofimaOmitidos,
			];

			if (file_exists($nombreArchivo)) {
				unlink($nombreArchivo);
			}

			echo '<script type="text/javascript">window.location.href="productos-importar.php?import=ok";</script>';
			exit();

		} else {
			switch ($_FILES['planilla']['error']) {
				case UPLOAD_ERR_INI_SIZE:
					$message = "El fichero subido excede la directiva upload_max_filesize de php.ini.";
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$message = "El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML.";
					break;
				case UPLOAD_ERR_PARTIAL:
					$message = "El fichero fue sólo parcialmente subido.";
					break;
				case UPLOAD_ERR_NO_FILE:
					$message = "No se subió ningún fichero.";
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$message = "Falta la carpeta temporal.";
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$message = "No se pudo escribir el fichero en el disco.";
					break;
				case UPLOAD_ERR_EXTENSION:
					$message = "Una extensión de PHP detuvo la subida de ficheros.";
					break;
				default:
					$message = "Error al subir el archivo.";
					break;
			}
			echo '<script type="text/javascript">window.location.href="productos-importar.php?error=3&message=' . rawurlencode($message) . '";</script>';
			exit();
		}
	} else {
		$message = 'El archivo enviado es invalido. Por favor vuelva a intentarlo: ' . $_FILES['planilla']['error'];
		echo '<script type="text/javascript">window.location.href="productos-importar.php?error=3&message=' . rawurlencode($message) . '";</script>';
		exit();
	}
} else {
	$message = "Este archivo no es admitido, por favor verifique que el archivo a importar sea un excel (.xlsx)";
	echo '<script type="text/javascript">window.location.href="productos-importar.php?error=3&message=' . rawurlencode($message) . '";</script>';
	exit();
}
