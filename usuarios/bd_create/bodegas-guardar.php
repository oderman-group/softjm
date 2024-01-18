<?php
    require_once("../sesion.php");

    $idPagina = 219;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$conexionBdPrincipal->query("INSERT INTO bodegas(bod_nombre, bod_ciudad, bod_id_empresa)VALUES('" . $_POST["nombre"] . "', '" . $_POST["ciudad"] . "', '".$_SESSION["dataAdicional"]["id_empresa"]."')");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../bodegas.php?msg=1";</script>';
	exit();