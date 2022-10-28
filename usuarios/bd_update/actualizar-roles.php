<?php
require_once("../sesion.php");

$idPagina = 64;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$conexionBdPrincipal->query("UPDATE usuarios_tipos SET utipo_nombre='" . $_POST["nombre"] . "' WHERE utipo_id='" . $_POST["id"] . "'");

    if(isset($_POST["accionesNP"])){
	    $numeroA = (count($_POST["accionesNP"]));
    }else{
        $numeroA =0;
    }
	if ($numeroA == 0) {
		$numero = (count($_POST["paginasP"]));
		$contador = 0;
		$conexionBdPrincipal->query("DELETE FROM paginas_perfiles WHERE pper_tipo_usuario='" . $_POST["id"] . "'");

		while ($contador < $numero) {
			$conexionBdPrincipal->query("INSERT INTO paginas_perfiles(pper_pagina, pper_tipo_usuario)VALUES(" . $_POST["paginasP"][$contador] . ",'" . $_POST["id"] . "')");

			$contador++;
		}
	} else {
		$conexionBdPrincipal->query("DELETE FROM paginas_perfiles WHERE pper_tipo_usuario='" . $_POST["id"] . "'");

		$contador = 0;
		while ($contador < $numeroA) {
			$paginas = $conexionBdAdmin->query("SELECT * FROM paginas WHERE pag_tipo_crud='" . $_POST["accionesNP"][$contador] . "'");
			while ($pag = mysql_fetch_array($paginas)) {
				$conexionBdPrincipal->query("INSERT INTO paginas_perfiles(pper_pagina, pper_tipo_usuario)VALUES(" . $pag[0] . ",'" . $_POST["id"] . "')");
			}
			$contador++;
		}
	}

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../roles-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();