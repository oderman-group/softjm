<?php
    require_once("../sesion.php");
    $idPagina = 59;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$conexionBdPrincipal->query("INSERT INTO usuarios_tipos(utipo_nombre, utipo_id_empresa)VALUES('" . $_POST["nombre"] . "', '".$idEmpresa."')");
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
    if(!empty($_POST["accionesNP"])){
	    $numeroA = (count($_POST["accionesNP"]));
    }else{
        $numeroA =0;
    }
	if ($numeroA == 0) {
		if(!empty($_POST["paginasP"])){
			$consultaPaginasUsuario = $conexionBdPrincipal->query("SELECT pper_pagina FROM paginas_perfiles WHERE pper_tipo_usuario = '$idInsertU'");
			$paginasExistentes = [];
			while ($fila = $consultaPaginasUsuario->fetch_assoc()) {
					$paginasExistentes[] = $fila['pper_pagina'];
			}
	
			$paginasAEliminar = array_diff($paginasExistentes, $_POST["paginasP"]);
			$paginasAInsertar = array_diff($_POST["paginasP"], $paginasExistentes);
	
			if (!empty($paginasAEliminar)) {
					$paginasAEliminarStr = implode("','", $paginasAEliminar);
					$conexionBdPrincipal->query("DELETE FROM paginas_perfiles WHERE pper_tipo_usuario = '$idInsertU' AND pper_pagina IN ('$paginasAEliminarStr')");
			}
	
			if (!empty($paginasAInsertar)) {
					foreach ($paginasAInsertar as $pagina) {
							$conexionBdPrincipal->query("INSERT INTO paginas_perfiles (pper_pagina, pper_tipo_usuario) VALUES ('$pagina', '$idInsertU')");
					}
			}
		}
	} else {
		$conexionBdPrincipal->query("DELETE FROM paginas_perfiles WHERE pper_tipo_usuario='" . $idInsertU . "'");
		$contador = 0;
		while ($contador < $numeroA) {
			$paginas = $conexionBdAdmin->query("SELECT * FROM paginas WHERE pag_tipo_crud='" . $_POST["accionesNP"][$contador] . "'");
			while ($pag = mysqli_fetch_array($paginas, MYSQLI_BOTH)) {
				$conexionBdPrincipal->query("INSERT INTO paginas_perfiles(pper_pagina, pper_tipo_usuario)VALUES(" . $pag[0] . ",'" . $idInsertU . "')");
			}
			$contador++;
		}
	}

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../roles-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();