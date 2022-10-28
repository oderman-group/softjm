<?php
    require_once("../sesion.php");

	$idPagina = 186;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo "Por seguridad NO es posible eliminar estos registros.";
	exit();

	$conexionBdAdmin->query("DELETE FROM modulos WHERE mod_id='" . $_GET["id"] . "'");

	echo '<script type="text/javascript">window.location.href="../modulos.php?msg=3";</script>';
	exit();