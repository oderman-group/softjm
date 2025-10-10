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

$resultado = array(
    "estado" => 0,
    "mensaje" => 0,
    "datos" => array()
);

if (isset($_POST["opcion"]) and $_POST["opcion"] == "consultar_usuarios_metas_id_empresa" ) {


    $e_dato = json_decode($_POST["e_datos"]);

    $sql = "
		SELECT 
		um.um_id id,
		um.um_fecha_creacion fecha,
		um.um_tipo_meta tipo,
		um.um_id_empresa id_empresa,
		um.um_usuario id_usuario,
		u.usr_login usuario,
		u.usr_nombre nombre,
		um.um_year anno,
		if(um.um_mes < 10, CONCAT('0',um.um_mes),um.um_mes) mes,
		CONCAT(um.um_year,'-',if(um.um_mes < 10, CONCAT('0',um.um_mes),um.um_mes)) periodo,
		um.um_meta meta
		FROM usuarios_metas um
		JOIN usuarios u ON u.usr_id = um.um_usuario 
		WHERE um.um_id_empresa = ".$e_dato[0]->id_empresa."
		ORDER BY um.um_tipo_meta,u.usr_nombre,um.um_year,um.um_mes
    ";
    $result = mysqli_query($conexionBdPrincipal, $sql);

    $results = [];
    if($result->num_rows > 0){
        
        $i=0;
        while($fila = mysqli_fetch_assoc($result)) {                    
            $datos[$i] = $fila;
            $i ++;
        }              
        
        $resultado["estado"] = "ok";
        $resultado["mensaje"] = "Listado de metas de usuarios"; ;
        $resultado["datos"] = $datos; 
        
    }else {
        $resultado["estado"]= "ko";
        $resultado["mensaje"]= "No hay datos para mostrar" ;
    }

    echo json_encode($resultado,512);
	exit();
}else if (isset($_POST["opcion"]) and $_POST["opcion"] == "consultar_usuarios_id_empresa" ) {


    $e_dato = json_decode($_POST["e_datos"]);

    $sql = "
		SELECT
		usr_id id,
		usr_login usuario,
		usr_nombre nombre,
		usr_email email
		FROM usuarios
		WHERE usr_id_empresa = ".$e_dato[0]->id_empresa."
		ORDER BY usr_nombre;
    ";
    $result = mysqli_query($conexionBdPrincipal, $sql);

    $results = [];
    if($result->num_rows > 0){
        
        $i=0;
        while($fila = mysqli_fetch_assoc($result)) {                    
            $datos[$i] = $fila;
            $i ++;
        }              
        
        $resultado["estado"] = "ok";
        $resultado["mensaje"] = "Listado de metas de usuarios"; ;
        $resultado["datos"] = $datos; 
        
    }else {
        $resultado["estado"]= "ko";
        $resultado["mensaje"]= "No hay datos para mostrar" ;
    }

    echo json_encode($resultado,512);
	exit();
}else if (isset($_POST["opcion"]) and $_POST["opcion"] == "crear_usuarios_metas" ) {


    $e_dato = json_decode($_POST["e_datos"]);


	$query = '
		SELECT
		um.um_id
		FROM usuarios_metas um
		WHERE um.um_usuario = "'.$e_dato[0]->id_usuario.'"
			AND um.um_tipo_meta = "'.$e_dato[0]->tipo_meta.'"
			AND um.um_id_empresa = "'.$e_dato[0]->id_empresa.'"
			AND um.um_year = "'.$e_dato[0]->anno.'"
			AND um.um_mes = "'.$e_dato[0]->mes.'"
			AND um.um_id <> 0 ;
	';

	$result = mysqli_query($conexionBdPrincipal, $query);

	if($result->num_rows == 0){                                   

		$query = '
			INSERT INTO usuarios_metas (
				um_tipo_meta,
				um_usuario,
				um_id_empresa,
				um_year,
				um_mes,
				um_meta
			) VALUES (
				"'.$e_dato[0]->tipo_meta.'",
				"'.$e_dato[0]->id_usuario.'",
				"'.$e_dato[0]->id_empresa.'",
				"'.$e_dato[0]->anno.'",
				"'.$e_dato[0]->mes.'",
				"'.$e_dato[0]->valor_meta.'"
			)
		';

		$result = $conexionBdPrincipal->prepare($query);
		$result->execute();

		$resultado["estado"] = "ok";
		$resultado["mensaje"] = "Meta agregada correctamente"; ;
		$resultado["datos"] = $e_dato;  
	}else{

		$resultado["estado"]= "ko";
        $resultado["mensaje"]= "La meta para el usuario y el periodo seleccionado ya existe.";
		$resultado["datos"] = $e_dato; 
	}    

    echo json_encode($resultado,512);
	exit();
}else if (isset($_POST["opcion"]) and $_POST["opcion"] == "eliminar_usuarios_metas" ) {


    $e_dato = json_decode($_POST["e_datos"]);
                                 

	$query = '
		DELETE FROM usuarios_metas 
		WHERE um_id = "'.$e_dato[0]->id_meta.'";
	';

	$result = $conexionBdPrincipal->prepare($query);
	$result->execute();

	$resultado["estado"] = "ok";
	$resultado["mensaje"] = "Meta eliminada correctamente"; ;
	$resultado["datos"] = $e_dato;  
  

    echo json_encode($resultado,512);
	exit();
}

?>

<div class="alert alert-<?=$tipo;?>">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<i class="icon-exclamation-sign"></i><strong><?=$titulo;?></strong> <?=$mensaje;?>
</div>