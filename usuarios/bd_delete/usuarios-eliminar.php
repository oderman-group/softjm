<?php
    require_once("../sesion.php");

	$idPagina = 53;
    include(RUTA_PROYECTO."/usuarios/verificar-paginas.php");

	$conexionBdPrincipal->query("DELETE FROM usuarios WHERE usr_id='" . $_GET["id"] . "'");

    include(RUTA_PROYECTO."/usuarios/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../usuarios.php?msg=3";</script>';
	exit();