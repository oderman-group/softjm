<?php
require_once("../sesion.php");
$idPagina = 200;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$conexionBdPrincipal->query("UPDATE zonas SET 
zon_nombre='" . $_POST["nombre"] . "', 
zon_observaciones='" . $_POST["observaciones"] . "' 
WHERE zon_id='" . $_POST["id"] . "'");
	
echo '<script type="text/javascript">window.location.href="../zonas-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();