<?php
require_once("../sesion.php");

$idPagina = 185;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$conexionBdAdmin->query("UPDATE modulos SET mod_nombre='".$_POST["nombre"]."', mod_padre='".$_POST["moduloPadre"]."' WHERE mod_id='".$_POST["id"]."'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../modulos-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();