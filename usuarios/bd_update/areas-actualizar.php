<?php
require_once("../sesion.php");

$idPagina = 228;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$conexionBdPrincipal->query("UPDATE areas SET ar_nombre='" . $_POST["nombre"] . "' WHERE ar_id='" . $_POST["id"] . "'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../areas-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();