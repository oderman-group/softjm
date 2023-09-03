<?php
require_once("../sesion.php");
require_once("../class/BaseDatos.php");

$idPagina = 227;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$_POST["ptar_creada_fecha"]  = date('Y-m-d H:i:s');
$_POST["ptar_creada_usuario"] = $_SESSION["id"];

BaseDatos::guardarRegistro('proyectos_tareas', $_POST);

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../proyectos-tareas.php?msg=1&proy='.$_POST["ptar_id_proyecto"].'";</script>';
exit();