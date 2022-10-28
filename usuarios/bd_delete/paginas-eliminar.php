<?php
    require_once("../sesion.php");

	$idPagina = 180;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$conexionBdAdmin->query("DELETE FROM paginas WHERE pag_id='" . $_GET["id"] . "'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../paginas.php?msg=3";</script>';
	exit();