<?php
    require_once("../sesion.php");
    $idPagina = 199;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    
	$conexionBdPrincipal->query("INSERT INTO zonas(zon_nombre, zon_observaciones, zon_id_empresa)VALUES('" . $_POST["nombre"] . "','" . $_POST["observaciones"] . "', '".$_SESSION["dataAdicional"]["id_empresa"]."')");
	$idInsertU = $conexionBdPrincipal->insert_id;

	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../zonas-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();
