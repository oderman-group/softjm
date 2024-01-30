<?php
    require_once("../sesion.php");

	$idPagina = 53;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$conexionBdPrincipal->query("DELETE FROM usuarios WHERE usr_id='" . $_GET["id"] ."' AND usr_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'");
	$conexionBdAdmin->query("DELETE FROM usuarios_roles WHERE upr_id_usuario='" . $_GET["id"] . "'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../usuarios.php?msg=3";</script>';
	exit();