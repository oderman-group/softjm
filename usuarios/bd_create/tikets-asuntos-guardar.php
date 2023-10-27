<?php
require_once("../sesion.php");

$idPagina = 286;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"INSERT INTO tikets_asuntos(tkpas_nombre)VALUES('" . $_POST["nombre"] . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	echo '<script type="text/javascript">window.location.href="../tikets-asuntos-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();
