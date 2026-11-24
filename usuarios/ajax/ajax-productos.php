<?php
include("../sesion.php");
include_once RUTA_PROYECTO."/usuarios/class/Producto.php";

function mensajeDescuentoExcedido(array $linea, $porcentaje, $monto, $maximo, string $tipo): string
{
	$porcentaje = (float) str_replace(',', '.', (string) $porcentaje);
	$maximo = (float) str_replace(',', '.', (string) $maximo);
	$monto = (float) str_replace(',', '.', (string) $monto);
	if ($monto <= 0) {
		$base = (float) ($linea['czpp_cantidad'] ?? 0) * (float) ($linea['czpp_valor'] ?? 0);
		$monto = $base * ($porcentaje / 100);
	}
	$textoPct = rtrim(rtrim(number_format(round($porcentaje, 3), 3, '.', ''), '0'), '.');
	$textoMax = rtrim(rtrim(number_format(round($maximo, 3), 3, '.', ''), '0'), '.');
	$textoMonto = number_format($monto, 2, ',', '.');
	$quien = $tipo === 'combo' ? 'combo' : 'producto';

	return 'Está intentando un descuento de $'.$textoMonto.', que equivale a '.$textoPct.'%. El máximo para este '.$quien.' es '.$textoMax.'%.';
}

/**
 * Devuelve el nombre de columna válido en cotizacion_productos.
 * En BD la columna es czpp_observacion (sin tilde); si el cliente envía czpp_observación u otra variante, el UPDATE falla.
 */
function cotizacionProductoCampoActualizable(string $campo): string
{
	$campo = trim($campo);
	if ($campo !== '' && preg_match('/^czpp_observaci[óo]n$/u', $campo)) {
		return 'czpp_observacion';
	}
	return $campo;
}

/**
 * Aplica Visible web en Orion + sync JM.
 * $nuevoEstado: 1=SI, 0=NO (estado destino explícito).
 *
 * @return array{ok:bool, code:string, message:string, estado:?int}
 */
function aplicarVisibleWebProducto(
	mysqli $conexionBdPrincipal,
	?mysqli $jm,
	array $datos,
	int $nuevoEstado
): array {
	$productoId = (int) ($datos['prod_id'] ?? 0);
	if ($productoId <= 0) {
		return ['ok' => false, 'code' => 'not_found', 'message' => 'Producto no encontrado', 'estado' => null];
	}

	$nuevoEstado = $nuevoEstado === 1 ? 1 : 0;
	$habilitado = (int) ($datos['prod_habilitado'] ?? 0) === 1;

	if ($nuevoEstado === 1 && !$habilitado) {
		return [
			'ok' => false,
			'code' => 'not_habilitado',
			'message' => 'Solo productos habilitados pueden marcarse como visibles en la web (SI)',
			'estado' => (int) ($datos['prod_visible_web'] ?? 0),
		];
	}

	mysqli_query(
		$conexionBdPrincipal,
		"UPDATE productos SET prod_visible_web='" . $nuevoEstado . "' WHERE prod_id='" . $productoId . "' LIMIT 1"
	);

	if (!$jm) {
		return [
			'ok' => true,
			'code' => 'orion_ok_jm_fail',
			'message' => 'Visible en la web actualizado a ' . ($nuevoEstado === 1 ? 'SI' : 'NO') . ' en Orion. No hay conexión con la tienda JM.',
			'estado' => $nuevoEstado,
		];
	}

	require_once RUTA_PROYECTO . '/usuarios/class/JmWebProductosSync.php';

	$payload = [
		'prod_id' => $productoId,
		'prod_nombre' => (string) ($datos['prod_nombre'] ?? ''),
		'prod_referencia' => (string) ($datos['prod_referencia'] ?? ''),
		'prod_precio' => $datos['prod_precio'] ?? 0,
		'prod_descuento_web' => $datos['prod_descuento_web'] ?? 0,
		'prod_descripcion_corta' => (string) ($datos['prod_descripcion_corta'] ?? ''),
		'prod_descripcion_larga' => (string) ($datos['prod_descripcion_larga'] ?? ''),
		'prod_marca' => (int) ($datos['prod_marca'] ?? 0),
		'mar_nombre' => (string) ($datos['mar_nombre'] ?? ''),
	];

	try {
		$resultadoSync = JmWebProductosSync::sincronizarDesdeOrion($jm, $payload, $nuevoEstado === 1);
		if (empty($resultadoSync['success'])) {
			return [
				'ok' => true,
				'code' => 'orion_ok_jm_error',
				'message' => 'Visible en la web en Orion a ' . ($nuevoEstado === 1 ? 'SI' : 'NO') . ', pero falló la sync JM: ' . (string) ($resultadoSync['message'] ?? 'error'),
				'estado' => $nuevoEstado,
			];
		}
		$accionJm = (string) ($resultadoSync['action'] ?? '');
		if ($nuevoEstado === 1) {
			$detalleAccion = $accionJm === 'insert'
				? 'creado y publicado en la tienda JM'
				: ($accionJm === 'update' ? 'actualizado en la tienda JM' : 'sincronizado con la tienda JM');
			$mensaje = 'Visible en la web: SI. Producto ' . $detalleAccion . '.';
		} else {
			$mensaje = $accionJm === 'noop'
				? 'Visible en la web: NO. El producto no estaba en la tienda JM.'
				: 'Visible en la web: NO. Producto ocultado en la tienda JM.';
		}
		return [
			'ok' => true,
			'code' => $nuevoEstado === 1 ? 'published' : 'hidden',
			'message' => $mensaje,
			'estado' => $nuevoEstado,
			'jm_action' => $accionJm,
		];
	} catch (Throwable $e) {
		return [
			'ok' => true,
			'code' => 'orion_ok_jm_exception',
			'message' => 'Visible en la web en Orion a ' . ($nuevoEstado === 1 ? 'SI' : 'NO') . ', pero hubo un error en JM: ' . $e->getMessage(),
			'estado' => $nuevoEstado,
		];
	}
}

/**
 * Envía al CMS el precio web (lista menos descuento web) del producto ya publicado.
 */
function sincronizarPrecioWebCms(mysqli $conexionBdPrincipal, int $productoId): void
{
	if ($productoId <= 0) {
		return;
	}

	require_once RUTA_PROYECTO . '/usuarios/includes/jm-web-conexion.php';
	require_once RUTA_PROYECTO . '/usuarios/class/JmWebProductosSync.php';

	$stmt = $conexionBdPrincipal->prepare(
		'SELECT prod_id, prod_precio, prod_descuento_web FROM productos WHERE prod_id = ? LIMIT 1'
	);
	if (!$stmt) {
		echo '<div class="alert alert-warning">No se pudo leer el producto para actualizar el precio web en el CMS.</div>';
		return;
	}
	$stmt->bind_param('i', $productoId);
	$stmt->execute();
	$producto = $stmt->get_result()->fetch_assoc();
	$stmt->close();
	if (!$producto) {
		return;
	}

	$jm = obtenerConexionJmWeb();
	if (!$jm) {
		echo '<div class="alert alert-warning">El precio quedó guardado en Orion. No hay conexión con el CMS para actualizar el precio web.</div>';
		return;
	}

	try {
		$resultado = JmWebProductosSync::actualizarPrecioWebExistente($jm, $producto);
	} catch (Throwable $e) {
		echo '<div class="alert alert-warning">El precio quedó guardado en Orion. No se pudo actualizar el CMS: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
		return;
	}

	if (($resultado['action'] ?? '') === 'noop') {
		echo '<div class="alert alert-warning">El precio quedó guardado en Orion. Este producto no está en el CMS.</div>';
		return;
	}
	if (empty($resultado['success'])) {
		echo '<div class="alert alert-warning">El precio quedó guardado en Orion. No se pudo actualizar el precio web en el CMS.</div>';
		return;
	}

	$precio = (string) ($resultado['precio'] ?? '');
	echo '<div class="alert alert-success">Precio web actualizado en el CMS: $' . htmlspecialchars($precio, ENT_QUOTES, 'UTF-8') . '.</div>';
}

//EDITAR PRODUCTOS
if ($_POST["proceso"] == 1) {

	try {
		$campoEdicion = (string) ($_POST['campo'] ?? '');
		if ($campoEdicion === Producto::PROD_COSTO || $campoEdicion === Producto::PROD_UTILIDAD) {
			include_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';
			if (ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa)) {
				echo '<span style="color:#b91c1c;">Con Ofima activo, el costo y la utilidad solo se modifican desde Ofima.</span>';
				exit();
			}
		}

		$precioAnterior = null;
		if ($campoEdicion === 'prod_precio') {
			$consultaPrecio = mysqli_query($conexionBdPrincipal, "SELECT prod_precio FROM productos WHERE prod_id='".$_POST["producto"]."' LIMIT 1");
			$filaPrecio = $consultaPrecio ? mysqli_fetch_assoc($consultaPrecio) : null;
			$precioAnterior = $filaPrecio['prod_precio'] ?? null;
		}

		mysqli_query($conexionBdPrincipal,"UPDATE ".$_POST["tabla"]." 
		SET ".$_POST["campo"]."='".$_POST["valor"]."', 
		prod_ultima_actualizacion=now(), 
		prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' 
		WHERE ".$_POST["pk"]."='".$_POST["producto"]."'");

		if ($campoEdicion === 'prod_precio' && $precioAnterior !== null) {
			mysqli_query($conexionBdPrincipal,"INSERT INTO productos_historial_precios(php_producto, php_precio_anterior, php_precio_nuevo, php_usuario, php_causa)
			VALUES('".$_POST["producto"]."', '".$precioAnterior."', '".$_POST["valor"]."', '".$_SESSION["id"]."', 5)");

			$syncOfima = ['success' => false, 'error' => 'No se pudo sincronizar el precio con Ofima'];
			try {
				$syncOfima = Producto::sincronizarConOfima((int) $_POST['producto'], $conexionBdPrincipal, (int) $idEmpresa, 'UPDATE');
			} catch (Exception $e) {
				error_log('Error al actualizar precio de lista en Ofima: ' . $e->getMessage());
				$syncOfima = [
					'success' => false,
					'error' => $e->getMessage(),
					'notificacion' => [
						'tipo' => 'error',
						'mensaje' => 'Ofima: ' . $e->getMessage(),
					],
				];
			}
			$ofimaOk = !empty($syncOfima['success']);
			$ofimaTipo = $syncOfima['notificacion']['tipo'] ?? ($ofimaOk ? 'success' : 'error');
			$ofimaMensaje = $syncOfima['notificacion']['mensaje']
				?? $syncOfima['error']
				?? $syncOfima['message']
				?? 'No se pudo actualizar el precio de lista en Ofima.';
			$claseAlerta = 'alert-danger';
			if ($ofimaTipo === 'success') {
				$claseAlerta = 'alert-success';
			} elseif ($ofimaTipo === 'warning') {
				$claseAlerta = 'alert-warning';
			}
			echo '<div class="alert ' . $claseAlerta . '">' . htmlspecialchars((string) $ofimaMensaje, ENT_QUOTES, 'UTF-8') . '</div>';
		}

		if ($campoEdicion === 'prod_precio' || $campoEdicion === 'prod_descuento_web') {
			sincronizarPrecioWebCms($conexionBdPrincipal, (int) $_POST['producto']);
		}

		if ($_POST["campo"] == Producto::PROD_UTILIDAD) {
			$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos WHERE prod_id='".$_POST["producto"]."'");
			$datos    = mysqli_fetch_array($consulta, MYSQLI_BOTH);

			$utilidad = $_POST["valor"] / 100;
			$precio1  = Producto::CalcularPrecioLista($datos[Producto::PROD_COSTO], $utilidad);

			mysqli_query($conexionBdPrincipal,"INSERT INTO productos_historial_precios(php_producto, php_precio_anterior, php_precio_nuevo, php_usuario, php_causa)
			VALUES('".$_POST["producto"]."', '".$datos['prod_precio']."', '".$precio1."', '".$_SESSION["id"]."', 2)");

			mysqli_query($conexionBdPrincipal,"UPDATE productos 
			SET prod_precio='".$precio1."', 
			prod_ultima_actualizacion=now(), 
			prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' 
			WHERE prod_id='".$_POST["producto"]."'");
		}
		
		if ($_POST["campo"] == Producto::PROD_COSTO) {
			$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos 
			WHERE prod_id='".$_POST["producto"]."'");
			$datos    = mysqli_fetch_array($consulta, MYSQLI_BOTH);

			$utilidad = $datos[Producto::PROD_UTILIDAD]/100;
			$precio1  = Producto::CalcularPrecioLista($_POST["valor"], $utilidad);

			mysqli_query($conexionBdPrincipal,"INSERT INTO productos_historial_precios(php_producto, php_precio_anterior, php_precio_nuevo, php_usuario, php_causa)
			VALUES('".$_POST["producto"]."', '".$datos['prod_precio']."', '".$precio1."', '".$_SESSION["id"]."', 1)");

			mysqli_query($conexionBdPrincipal,"UPDATE productos 
			SET prod_precio='".$precio1."', 
			prod_ultima_actualizacion=now(), 
			prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' 
			WHERE prod_id='".$_POST["producto"]."'");
		}
	} catch (Exception $e) {
?>
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Error!</strong> Ha ocurrido un error al intentar editar el producto. <?php echo $e->getMessage(); ?>
		</div>
<?php
		exit();
	}
}

//PRODUCTOS DE LA COTIZACIÓN
if ($_POST["proceso"] == 2) {
	try {
		$campoCotizacion = cotizacionProductoCampoActualizable((string)($_POST['campo'] ?? ''));

		$consultaProducto=mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
		INNER JOIN productos ON prod_id=czpp_producto 
		WHERE czpp_id='".$_POST["producto"]."' ");
		$datosProducto = mysqli_fetch_array($consultaProducto, MYSQLI_BOTH);

		if ($campoCotizacion == 'czpp_descuento') {
			if ($_POST["valor"] > $datosProducto['prod_descuento1']) {
				$response = [
					'success' => false,
					'message' => mensajeDescuentoExcedido(
						$datosProducto,
						$_POST['valor'],
						$_POST['monto'] ?? 0,
						$datosProducto['prod_descuento1'],
						'producto'
					),
				];
				echo json_encode($response);
				exit();
			}
		}

		if ($campoCotizacion == 'czpp_valor') {
			if($_POST["valor"] < $datosProducto['prod_precio']) {

				$response = [
					'success' => false,
					'message' => "El precio que está otorgando es menor al permitido para este producto, el cual es de $" . number_format($datosProducto['prod_precio'],0,".",".")
				];

				echo json_encode($response);
				exit();
			}
		}

		mysqli_query($conexionBdPrincipal,"UPDATE cotizacion_productos SET 
		".$campoCotizacion."='".mysqli_real_escape_string($conexionBdPrincipal,$_POST["valor"])."', 
		czpp_ultima_actualizacion=now(), 
		czpp_cantidad_actualizaciones=czpp_cantidad_actualizaciones+1, 
		czpp_usuario_ultima_actualizacion='".$_SESSION["id"]."' 
		WHERE czpp_id='".$_POST["producto"]."'");

		$response = [
			'success' => true,
			'message' => '<div class="alert alert-success">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<i class="icon-exclamation-sign"></i><strong>Exito!</strong> Los cambios ya se guardaron y todo está bien.
						</div>'
		];
		echo json_encode($response);
		exit();

	} catch (Exception $e) {

		$response = [
			'success' => false,
			'message' => 'Ha ocurrido un error al intentar hacer el cambio. ' . $e->getMessage()
		];
		echo json_encode($response);
		exit();
	}
	//echo '<script type="text/javascript">location.reload();</script>';
}

/******************************************************************************************** */
//PRODUCTOS DESDE LA CATEGORIA (GRUPO 1)
if ($_POST["proceso"] == 3) {
	try {
		mysqli_query($conexionBdPrincipal,
		"UPDATE productos 
		SET 
			".$_POST["campo"]."='".$_POST["valor"]."', 
			prod_ultima_actualizacion=now(), 
			prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' 
		WHERE 
			prod_categoria='".$_POST["producto"]."' 
		AND prod_precio_predeterminado=0
		");

		if ($_POST["campo"] == Producto::PROD_UTILIDAD) {
			$utilidad  = $_POST["valor"] / 100;
			$productos = mysqli_query($conexionBdPrincipal,
			"SELECT * FROM productos 
			WHERE 
				prod_categoria='".$_POST["producto"]."' 
			AND prod_precio_predeterminado=0
			");

			while ($datos = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
				$precio1 = Producto::CalcularPrecioLista($datos[Producto::PROD_COSTO], $utilidad);

				mysqli_query($conexionBdPrincipal,
				"UPDATE productos 
				SET 
					prod_precio='".$precio1."', 
					prod_ultima_actualizacion=now(), 
					prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' 
				WHERE 
					prod_id='".$datos['prod_id']."' 
				AND prod_precio_predeterminado=0
				");
			}
		}

		mysqli_query($conexionBdPrincipal,
		"UPDATE productos_categorias 
		SET 
			".$_POST["nombreCat"]."='".$_POST["valor"]."', 
			catp_usuario='".$_SESSION["id"]."', 
			catp_fecha=now() 
		WHERE 
			catp_id='".$_POST["producto"]."'
		");
	} catch (Exception $e) {
?>
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Error!</strong> Ha ocurrido un error al intentar actualizar los productos del grupo 1. <?php echo $e->getMessage(); ?>
		</div>
<?php
		exit();
	}
}

//PRODUCTOS DESDE EL GRUPO (GRUPO 2)
if ($_POST["proceso"] == 4) {
	try {
		mysqli_query($conexionBdPrincipal,
		"UPDATE productos 
		SET 
			".$_POST["campo"]."='".$_POST["valor"]."', 
			prod_ultima_actualizacion=now(), 
			prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' 
		WHERE 
			prod_grupo1='".$_POST["producto"]."' 
		AND prod_precio_predeterminado=0
		");

		if ($_POST["campo"] == Producto::PROD_UTILIDAD) {
			$utilidad = $_POST["valor"]/100;
			$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos WHERE prod_grupo1='".$_POST["producto"]."' AND prod_precio_predeterminado=0");

			while ($datos = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
				$precio1 = Producto::CalcularPrecioLista($datos[Producto::PROD_COSTO], $utilidad);

				mysqli_query($conexionBdPrincipal,
				"UPDATE productos 
				SET 
					prod_precio='".$precio1."', 
					prod_ultima_actualizacion=now(), 
					prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' 
				WHERE 
					prod_id='".$datos['prod_id']."' 
				AND prod_precio_predeterminado=0
				");
			}
		}

		mysqli_query($conexionBdPrincipal,
		"UPDATE 
			productos_categorias 
		SET 
			".$_POST["nombreCat"]."='".$_POST["valor"]."', 
			catp_usuario='".$_SESSION["id"]."', 
			catp_fecha=now() 
		WHERE 
			catp_id='".$_POST["producto"]."'
		");
	} catch (Exception $e) {
?>
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Error!</strong> Ha ocurrido un error al intentar actualizar los productos del grupo 2. <?php echo $e->getMessage(); ?>
		</div>
<?php
		exit();
	}
}

//PRODUCTOS DESDE LA MARCA
if ($_POST["proceso"] == 5) {
	try {
		mysqli_query($conexionBdPrincipal,
		"UPDATE productos 
		SET 
			".$_POST["campo"]."='".$_POST["valor"]."', 
			prod_ultima_actualizacion=now(), 
			prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' 
		WHERE 
			prod_marca='".$_POST["producto"]."' 
		AND prod_precio_predeterminado=0
		");

		if ($_POST["campo"] == Producto::PROD_UTILIDAD) {
			$utilidad = $_POST["valor"]/100;

			$productos = mysqli_query($conexionBdPrincipal,
			"SELECT * FROM productos 
			WHERE 
				prod_marca='".$_POST["producto"]."' 
			AND prod_precio_predeterminado=0
			");

			while ($datos = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
				$precio1 = Producto::CalcularPrecioLista($datos[Producto::PROD_COSTO], $utilidad);

				mysqli_query($conexionBdPrincipal,
				"UPDATE productos 
				SET 
					prod_precio='".$precio1."', 
					prod_ultima_actualizacion=now(), 
					prod_ultima_actualizacion_usuario='".$_SESSION["id"]."' 
				WHERE 
					prod_id='".$datos['prod_id']."' 
				AND prod_precio_predeterminado=0
				");
			}
		}
	} catch (Exception $e) {
?>
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Error!</strong> Ha ocurrido un error al intentar actualizar los productos de las marcas. <?php echo $e->getMessage(); ?>
		</div>
<?php
		exit();
	}
}
/******************************************************************************************** */

//PRODUCTOS PREDETERMINADOS
if ($_POST["proceso"] == 6) {
	mysqli_query($conexionBdPrincipal, "
		UPDATE productos 
		SET prod_precio_predeterminado = IF(prod_precio_predeterminado = '0', '1', '0') 
		WHERE prod_id = '" . $_POST["producto"] . "'
	");
}

//PRODUCTOS DE LOS COMBOS
if ($_POST["proceso"]==7) {
	try {
		mysqli_query($conexionBdPrincipal,"UPDATE combos_productos 
		SET ".$_POST["campo"]."='".$_POST["valor"]."' 
		WHERE copp_id='".$_POST["producto"]."'");
	} catch (Exception $e) {
?>
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Error!</strong> Ha ocurrido un error. <?php echo $e->getMessage(); ?>
		</div>
<?php
		exit();
	}
}

//PRODUCTOS DE LA REMISIÓN
if($_POST["proceso"]==8){
	$campoCotizacion = cotizacionProductoCampoActualizable((string)($_POST['campo'] ?? ''));

	if($campoCotizacion=='czpp_descuento'){
		$datosProducto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos INNER JOIN productos ON prod_id=czpp_producto
		WHERE czpp_id='".$_POST["producto"]."'
		"), MYSQLI_BOTH);
		
		if($_POST["valor"]>$datosProducto['prod_descuento1']){
			$mensaje = mensajeDescuentoExcedido($datosProducto, $_POST['valor'], $_POST['monto'] ?? 0, $datosProducto['prod_descuento1'], 'producto');
			echo '<script type="text/javascript">alert("'.addslashes($mensaje).'");</script>';
			exit();
		}
	}

	if($campoCotizacion=='czpp_cantidad'){
		include_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';
		$tipoLinea = mysqli_fetch_assoc(mysqli_query(
			$conexionBdPrincipal,
			"SELECT czpp_tipo FROM cotizacion_productos WHERE czpp_id='".(int) $_POST['producto']."' LIMIT 1"
		));
		$remisionSinStock = ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa)
			&& (int) ($tipoLinea['czpp_tipo'] ?? 0) === (int) CZPP_TIPO_REM;

		if (!$remisionSinStock) {
			$datosProducto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
			INNER JOIN productos_bodegas ON prodb_producto=czpp_producto AND prodb_bodega=czpp_bodega
			WHERE czpp_id='".$_POST["producto"]."'
			"), MYSQLI_BOTH);
			
			if($_POST["valor"]>$datosProducto['prodb_existencias']){
				echo '<script type="text/javascript">alert("Excedió el limite: Solo existen '.$datosProducto['prodb_existencias'].' unidades de este producto en esta bodega. No es posible colocar '.$_POST["valor"].'.");</script>';
				exit();	
			}

			if($_POST["valor"]>$datosProducto['czpp_cantidad']){
				$result = $_POST["valor"] - $datosProducto['czpp_cantidad'];

				mysqli_query($conexionBdPrincipal,"UPDATE productos_bodegas SET prodb_existencias=(prodb_existencias - $result) WHERE prodb_id='".$datosProducto["prodb_id"]."'");
				
			}else{
				$result = $datosProducto['czpp_cantidad'] - $_POST["valor"] ;

				mysqli_query($conexionBdPrincipal,"UPDATE productos_bodegas SET prodb_existencias=(prodb_existencias + $result) WHERE prodb_id='".$datosProducto["prodb_id"]."'");
				
			}
		}
	}

	mysqli_query($conexionBdPrincipal,"UPDATE cotizacion_productos SET ".$campoCotizacion."='".mysqli_real_escape_string($conexionBdPrincipal,$_POST["valor"])."' WHERE czpp_id='".$_POST["producto"]."'");
	
	
	//echo '<script type="text/javascript">location.reload();</script>';
}

//PRODUCTOS DE LA IMPORTACIÓN
if($_POST["proceso"]==9){
	$campoCotizacion = cotizacionProductoCampoActualizable((string)($_POST['campo'] ?? ''));

	if($campoCotizacion=='czpp_descuento'){
		$datosProducto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos INNER JOIN productos ON prod_id=czpp_producto
		WHERE czpp_id='".$_POST["producto"]."'
		"), MYSQLI_BOTH);
		
		if($_POST["valor"]>$datosProducto['prod_descuento1']){
			$mensaje = mensajeDescuentoExcedido($datosProducto, $_POST['valor'], $_POST['monto'] ?? 0, $datosProducto['prod_descuento1'], 'producto');
			echo '<script type="text/javascript">alert("'.addslashes($mensaje).'");</script>';
			exit();
		}
	}

	mysqli_query($conexionBdPrincipal,"UPDATE cotizacion_productos SET ".$campoCotizacion."='".mysqli_real_escape_string($conexionBdPrincipal,$_POST["valor"])."' WHERE czpp_id='".$_POST["producto"]."'");
	
	
	//echo '<script type="text/javascript">location.reload();</script>';
}
//PRODUCTOS VISIBLES WEB → sync tienda JM (solo habilitados pueden pasar a SI)
if ($_POST["proceso"] == 10) {
	header('Content-Type: application/json; charset=utf-8');
	$productoId = (int) ($_POST["producto"] ?? 0);
	$consultaVisWeb = mysqli_query(
		$conexionBdPrincipal,
		"SELECT p.*, m.mar_nombre
		 FROM productos p
		 LEFT JOIN marcas m ON m.mar_id = p.prod_marca AND m.mar_id_empresa = p.prod_id_empresa
		 WHERE p.prod_id = '" . $productoId . "'
		 LIMIT 1"
	);
	$datos = $consultaVisWeb ? mysqli_fetch_array($consultaVisWeb, MYSQLI_ASSOC) : null;

	if (!$datos || $productoId <= 0) {
		echo json_encode([
			'success' => false,
			'code' => 'not_found',
			'message' => 'No se encontró el producto para cambiar la visibilidad en la web.',
			'estado' => null,
			'tipo' => 'error',
		], JSON_UNESCAPED_UNICODE);
		exit;
	}

	$estadoActual = (int) ($datos['prod_visible_web'] ?? 0);
	$nuevoEstado = $estadoActual === 0 ? 1 : 0;

	require_once RUTA_PROYECTO . '/usuarios/includes/jm-web-conexion.php';
	$jm = obtenerConexionJmWeb();
	$resultado = aplicarVisibleWebProducto($conexionBdPrincipal, $jm, $datos, $nuevoEstado);
	$code = (string) ($resultado['code'] ?? '');
	$tipo = 'success';
	if ($code === 'not_habilitado' || $code === 'not_found') {
		$tipo = 'error';
	} elseif (strpos($code, 'jm') !== false) {
		$tipo = 'warning';
	} elseif ($nuevoEstado !== 1) {
		$tipo = 'info';
	}

	echo json_encode([
		'success' => !empty($resultado['ok']),
		'code' => $code,
		'message' => (string) ($resultado['message'] ?? 'Visible en la web actualizado.'),
		'estado' => $resultado['estado'] ?? null,
		'tipo' => $tipo,
		'jm_action' => (string) ($resultado['jm_action'] ?? ''),
	], JSON_UNESCAPED_UNICODE);
	exit;
}

// VISIBLE WEB MASIVO (estado destino explícito: 1=SI, 0=NO)
if ((string) ($_POST['proceso'] ?? '') === '12') {
	header('Content-Type: application/json; charset=utf-8');

	$estadoDestino = isset($_POST['estado']) ? (int) $_POST['estado'] : -1;
	$idsRaw = $_POST['productos'] ?? [];
	if (!is_array($idsRaw)) {
		$idsRaw = preg_split('/\s*,\s*/', (string) $idsRaw) ?: [];
	}
	$ids = array_values(array_unique(array_filter(array_map('intval', $idsRaw), static function ($id) {
		return $id > 0;
	})));

	if ($estadoDestino !== 0 && $estadoDestino !== 1) {
		echo json_encode(['success' => false, 'message' => 'Estado inválido'], JSON_UNESCAPED_UNICODE);
		exit;
	}
	if (empty($ids)) {
		echo json_encode(['success' => false, 'message' => 'No hay productos seleccionados'], JSON_UNESCAPED_UNICODE);
		exit;
	}
	if (count($ids) > 100) {
		echo json_encode(['success' => false, 'message' => 'Máximo 100 productos por lote'], JSON_UNESCAPED_UNICODE);
		exit;
	}

	require_once RUTA_PROYECTO . '/usuarios/includes/jm-web-conexion.php';
	$jm = obtenerConexionJmWeb();

	$idsSql = implode(',', $ids);
	$mapa = [];
	$consultaLote = mysqli_query(
		$conexionBdPrincipal,
		"SELECT p.*, m.mar_nombre
		 FROM productos p
		 LEFT JOIN marcas m ON m.mar_id = p.prod_marca AND m.mar_id_empresa = p.prod_id_empresa
		 WHERE p.prod_id IN (" . $idsSql . ")"
	);
	while ($consultaLote && ($fila = mysqli_fetch_array($consultaLote, MYSQLI_ASSOC))) {
		$mapa[(int) $fila['prod_id']] = $fila;
	}

	$ok = 0;
	$omitidos = 0;
	$errores = 0;
	$detalle = [];

	foreach ($ids as $productoId) {
		if (empty($mapa[$productoId])) {
			$errores++;
			$detalle[] = ['id' => $productoId, 'ok' => false, 'code' => 'not_found', 'estado' => null];
			continue;
		}
		$datos = $mapa[$productoId];
		$estadoActual = (int) ($datos['prod_visible_web'] ?? 0);
		if ($estadoActual === $estadoDestino) {
			$omitidos++;
			$detalle[] = ['id' => $productoId, 'ok' => true, 'code' => 'unchanged', 'estado' => $estadoActual];
			continue;
		}
		$resultado = aplicarVisibleWebProducto($conexionBdPrincipal, $jm, $datos, $estadoDestino);
		if (($resultado['code'] ?? '') === 'not_habilitado') {
			$omitidos++;
		} elseif (!empty($resultado['ok'])) {
			$ok++;
		} else {
			$errores++;
		}
		$detalle[] = [
			'id' => $productoId,
			'ok' => !empty($resultado['ok']),
			'code' => (string) ($resultado['code'] ?? ''),
			'estado' => $resultado['estado'] ?? null,
			'message' => (string) ($resultado['message'] ?? ''),
		];
	}

	echo json_encode([
		'success' => true,
		'estado' => $estadoDestino,
		'ok' => $ok,
		'omitidos' => $omitidos,
		'errores' => $errores,
		'detalle' => $detalle,
		'jm_conectado' => $jm instanceof mysqli,
	], JSON_UNESCAPED_UNICODE);
	exit;
}

//DESCUENTO DE COMBOS DE LA COTIZACIÓN
if($_POST["proceso"]==11){

	$campoCotizacion = cotizacionProductoCampoActualizable((string)($_POST['campo'] ?? ''));

	$datosProducto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
	INNER JOIN combos ON combo_id=czpp_combo
	WHERE czpp_id='".$_POST["producto"]."'
	"), MYSQLI_BOTH);

	if($campoCotizacion=='czpp_descuento'){
		
		if($_POST["valor"] > $datosProducto['combo_descuento_maximo']){
			$mensaje = mensajeDescuentoExcedido($datosProducto, $_POST['valor'], $_POST['monto'] ?? 0, $datosProducto['combo_descuento_maximo'], 'combo');
			echo '<script type="text/javascript">alert("'.addslashes($mensaje).'");</script>';
			exit();
		}
	}


	mysqli_query($conexionBdPrincipal,"UPDATE cotizacion_productos SET ".$campoCotizacion."='".mysqli_real_escape_string($conexionBdPrincipal,$_POST["valor"])."', czpp_ultima_actualizacion=now(), czpp_cantidad_actualizaciones=czpp_cantidad_actualizaciones+1, czpp_usuario_ultima_actualizacion='".$_SESSION["id"]."' 
	WHERE czpp_id='".$_POST["producto"]."'");
	
	
	//echo '<script type="text/javascript">location.reload();</script>';
}

//DESCUENTO DE Servicios DE LA COTIZACIÓN
if($_POST["proceso"]==12){

	$campoCotizacion = cotizacionProductoCampoActualizable((string)($_POST['campo'] ?? ''));

	mysqli_query($conexionBdPrincipal,"UPDATE cotizacion_productos SET ".$campoCotizacion."='".mysqli_real_escape_string($conexionBdPrincipal,$_POST["valor"])."' WHERE czpp_id='".$_POST["producto"]."'");
}

if ($_POST["proceso"] != 2) {
	echo '<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Éxito:</strong> Los cambios se guardaron correctamente.
		</div>';
}