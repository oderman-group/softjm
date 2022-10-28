<?php
    require_once("../sesion.php");

	$idPagina = 180;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo "Por seguridad NO es posible eliminar estos registros.";
	exit();

	$conexionBdAdmin->query("DELETE FROM paginas WHERE pag_id='" . $_GET["id"] . "'");

	echo '<script type="text/javascript">window.location.href="../paginas.php?msg=3";</script>';
	exit();