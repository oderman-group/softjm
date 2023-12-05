<?php
require_once("../sesion.php");

$idPagina = 64;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
$consulta   = $conexionBdPrincipal->query("SELECT * FROM usuarios_tipos WHERE utipo_id='".$_POST["id"]."' AND utipo_id_empresa={$idEmpresa}");
$resultadoD = mysqli_fetch_array($consulta, MYSQLI_BOTH);
if(!empty($resultadoD)) {

	$conexionBdPrincipal->query("UPDATE usuarios_tipos SET utipo_nombre='" . $_POST["nombre"] . "' WHERE utipo_id='" . $_POST["id"] . "' AND utipo_id_empresa={$idEmpresa}");

    if(isset($_POST["accionesNP"])){
	    $numeroA = (count($_POST["accionesNP"]));
    }else{
        $numeroA =0;
    }
	if ($numeroA == 0) {
		$idUsuario = $_POST["id"];
		$consultaPaginasUsuario = $conexionBdPrincipal->query("SELECT pper_pagina FROM paginas_perfiles WHERE pper_tipo_usuario = '$idUsuario'");
		$paginasExistentes = [];
		while ($fila = $consultaPaginasUsuario->fetch_assoc()) {
				$paginasExistentes[] = $fila['pper_pagina'];
		}

		$paginasAEliminar = array_diff($paginasExistentes, $_POST["paginasP"]);
		$paginasAInsertar = array_diff($_POST["paginasP"], $paginasExistentes);

		if (!empty($paginasAEliminar)) {
				$paginasAEliminarStr = implode("','", $paginasAEliminar);
				$conexionBdPrincipal->query("DELETE FROM paginas_perfiles WHERE pper_tipo_usuario = '$idUsuario' AND pper_pagina IN ('$paginasAEliminarStr')");
		}

		if (!empty($paginasAInsertar)) {
				foreach ($paginasAInsertar as $pagina) {
						$conexionBdPrincipal->query("INSERT INTO paginas_perfiles (pper_pagina, pper_tipo_usuario) VALUES ('$pagina', '$idUsuario')");
				}
		}
	} else {
		$conexionBdPrincipal->query("DELETE FROM paginas_perfiles WHERE pper_tipo_usuario='" . $_POST["id"] . "'");

		$contador = 0;
		while ($contador < $numeroA) {
			$paginas = $conexionBdAdmin->query("SELECT * FROM paginas WHERE pag_tipo_crud='" . $_POST["accionesNP"][$contador] . "'");
			while ($pag = mysqli_fetch_array($paginas)) {
				$conexionBdPrincipal->query("INSERT INTO paginas_perfiles(pper_pagina, pper_tipo_usuario)VALUES(" . $pag[0] . ",'" . $_POST["id"] . "')");
			}
			$contador++;
		}
	}

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../roles-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
} else {
	echo '<script type="text/javascript">window.location.href="../index.php?error=Unauthorized";</script>';
}
	exit();