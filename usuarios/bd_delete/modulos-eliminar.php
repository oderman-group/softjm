<?php
    require_once("../sesion.php");

	$idPagina = 186;
    include(RUTA_PROYECTO."/usuarios/verificar-paginas.php");

	$conexionBdAdmin->query("DELETE FROM modulos WHERE mod_id='" . $_GET["id"] . "'");

    include(RUTA_PROYECTO."/usuarios/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../modulos.php?msg=3";</script>';
	exit();