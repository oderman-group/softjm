<?php
require_once("../sesion.php");
require_once("../class/BaseDatos.php");

$idPagina = 227;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

BaseDatos::guardarRegistro('proyectos', $_POST);

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../proyectos.php?msg=1";</script>';
exit();