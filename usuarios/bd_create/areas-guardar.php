<?php
require_once("../sesion.php");

$idPagina = 227;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$conexionBdPrincipal->query("INSERT INTO areas(ar_nombre)VALUES('" . $_POST["nombre"] . "')");  

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../areas.php?msg=1";</script>';
exit();
