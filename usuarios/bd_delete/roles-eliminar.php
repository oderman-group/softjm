<?php
    require_once("../sesion.php");

	$idPagina = 54;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$conexionBdPrincipal->query("DELETE FROM paginas_perfiles WHERE pper_tipo_usuario='" . $_GET["id"] . "'");
	$conexionBdPrincipal->query("DELETE FROM usuarios WHERE usr_tipo='" . $_GET["id"] . "'");
	$conexionBdPrincipal->query("DELETE FROM usuarios_tipos WHERE utipo_id='" . $_GET["id"] . "'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../roles.php?msg=3";</script>';
	exit();