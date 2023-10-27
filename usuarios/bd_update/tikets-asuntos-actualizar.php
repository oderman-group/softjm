<?php   
require_once("../sesion.php");

$idPagina = 287;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"UPDATE tikets_asuntos SET tkpas_nombre='" . $_POST["nombre"] . "' WHERE tkpas_id='" . $_POST["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="../tikets-asuntos-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();

 
