<?php include("../sesion.php");?>
<?php

//METAS DE VENTAS
if($_POST["proceso"]==1){

	mysqli_query($conexionBdPrincipal, "UPDATE usuarios SET usr_meta_ventas='".$_POST["valorActual"]."' WHERE usr_id='".$_POST["idUsuario"]."'");

	$titulo = 'Éxito';
	$mensaje = 'Los cambios ya se guardaron y todo está bien.';
	$tipo = 'success';
	
}

if($_POST["proceso"] == 2) {

	$tipo_meta = 'NUMERO_VENTA'; // Usar el operador null coalescing para evitar errores si no existe
	$usuario = $_POST['idUsuario'] ?? '';
	$id_empresa = 1;
	$year = date('Y');
	$mes = date('m');
	$meta = $_POST['valorActual'] ?? '';

	// Convertir a tipos de datos esperados
	$id_empresa = (int)$id_empresa;
	$year = (int)$year;
	$mes = (int)$mes;
	// um_meta es VARCHAR(100), así que se puede dejar como string

	// 2. Definir la consulta SQL con marcadores de posición (?)
	$sql = "
		INSERT INTO usuarios_metas (
			um_tipo_meta,
			um_usuario,
			um_id_empresa,
			um_year,
			um_mes,
			um_meta
		) VALUES (
			?, ?, ?, ?, ?, ?
		)
		ON DUPLICATE KEY UPDATE
			um_meta = VALUES(um_meta)
	";

	// 3. Preparar la sentencia
	if ($stmt = $conexionBdPrincipal->prepare($sql)) {
		// 4. Vincular los parámetros
		// 'ssiiis' -> s: string, i: integer. Debe coincidir con el tipo de cada marcador de posición.
		// um_tipo_meta (s), um_usuario (s), um_id_empresa (i), um_year (i), um_mes (i), um_meta (s)
		$stmt->bind_param("siiiis", $tipo_meta, $usuario, $id_empresa, $year, $mes, $meta);

		// 5. Ejecutar la sentencia
		if ($stmt->execute()) {
			// La operación fue exitosa
			if ($stmt->affected_rows > 0) {
				// Si affected_rows es 1, fue un INSERT.
				// Si affected_rows es 2, fue un UPDATE.
				// (En algunos casos, puede ser 0 si el UPDATE no cambió ningún valor)
				$titulo = 'Éxito';
				$mensaje = 'Operación de actualización de meta de número de ventas exitosa.';
				$tipo = 'success';
			} else {
				$titulo = 'Información';
				$mensaje = 'No se realizaron cambios (posiblemente la meta ya tenía el mismo valor).';
				$tipo = 'info';
			}
		} else {
			$titulo = 'Error';
			$mensaje = "Error al ejecutar la sentencia: " . $stmt->error;
			$tipo = 'error';
		}

		// 6. Cerrar la sentencia
		$stmt->close();
	} else {
		$titulo = 'Error';
		$mensaje = "Error al ejecutar la sentencia: " . $conexionBdPrincipal->error;
		$tipo = 'error';
	}
	
}

if($_POST["proceso"] == 3) {

	$tipo_meta = 'DEMO'; // Usar el operador null coalescing para evitar errores si no existe
	$usuario = $_POST['idUsuario'] ?? '';
	$id_empresa = 1;
	$year = date('Y');
	$mes = date('m');
	$meta = $_POST['valorActual'] ?? '';

	// Convertir a tipos de datos esperados
	$id_empresa = (int)$id_empresa;
	$year = (int)$year;
	$mes = (int)$mes;
	// um_meta es VARCHAR(100), así que se puede dejar como string

	// 2. Definir la consulta SQL con marcadores de posición (?)
	$sql = "
		INSERT INTO usuarios_metas (
			um_tipo_meta,
			um_usuario,
			um_id_empresa,
			um_year,
			um_mes,
			um_meta
		) VALUES (
			?, ?, ?, ?, ?, ?
		)
		ON DUPLICATE KEY UPDATE
			um_meta = VALUES(um_meta)
	";

	// 3. Preparar la sentencia
	if ($stmt = $conexionBdPrincipal->prepare($sql)) {
		// 4. Vincular los parámetros
		// 'ssiiis' -> s: string, i: integer. Debe coincidir con el tipo de cada marcador de posición.
		// um_tipo_meta (s), um_usuario (s), um_id_empresa (i), um_year (i), um_mes (i), um_meta (s)
		$stmt->bind_param("siiiis", $tipo_meta, $usuario, $id_empresa, $year, $mes, $meta);

		// 5. Ejecutar la sentencia
		if ($stmt->execute()) {
			// La operación fue exitosa
			if ($stmt->affected_rows > 0) {
				// Si affected_rows es 1, fue un INSERT.
				// Si affected_rows es 2, fue un UPDATE.
				// (En algunos casos, puede ser 0 si el UPDATE no cambió ningún valor)
				$titulo = 'Éxito';
				$mensaje = 'Operación de actualización de meta de demostraciones exitosa.';
				$tipo = 'success';
			} else {
				$titulo = 'Información';
				$mensaje = 'No se realizaron cambios (posiblemente la meta ya tenía el mismo valor).';
				$tipo = 'info';
			}
		} else {
			$titulo = 'Error';
			$mensaje = "Error al ejecutar la sentencia: " . $stmt->error;
			$tipo = 'error';
		}

		// 6. Cerrar la sentencia
		$stmt->close();
	} else {
		$titulo = 'Error';
		$mensaje = "Error al ejecutar la sentencia: " . $conexionBdPrincipal->error;
		$tipo = 'error';
	}
	
}
?>

<div class="alert alert-<?=$tipo;?>">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<i class="icon-exclamation-sign"></i><strong><?=$titulo;?></strong> <?=$mensaje;?>
</div>