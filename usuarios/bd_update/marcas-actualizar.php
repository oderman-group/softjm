<?php
require_once("../sesion.php");

$idPagina = 193;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$conexionBdPrincipal->query("UPDATE marcas SET mar_nombre='" . $_POST["nombre"] . "' WHERE mar_id='" . $_POST["id"] . "' AND mar_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../marcas-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();