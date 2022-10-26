<?php
    require_once("../sesion.php");
    $idPagina = 59;
    include(RUTA_PROYECTO."/usuarios/verificar-paginas.php");

	$conexionBdPrincipal->query("INSERT INTO usuarios_tipos(utipo_nombre)VALUES('" . $_POST["nombre"] . "')");
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
    if(isset($_POST["accionesNP"])){
	    $numeroA = (count($_POST["accionesNP"]));
    }else{
        $numeroA =0;
    }
	if ($numeroA == 0) {
		$numero = (count($_POST["paginasP"]));
		$contador = 0;
		$conexionBdPrincipal->query("DELETE FROM paginas_perfiles WHERE pper_tipo_usuario='" . $idInsertU . "'");
		while ($contador < $numero) {
			$conexionBdPrincipal->query("INSERT INTO paginas_perfiles(pper_pagina, pper_tipo_usuario)VALUES(" . $_POST["paginasP"][$contador] . ",'" . $idInsertU . "')");
			$contador++;
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

    include(RUTA_PROYECTO."/usuarios/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../roles-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();