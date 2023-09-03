<?php
require_once("../sesion.php");
require_once("../class/BaseDatos.php");

$idPagina = 228;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$infoActualizar = [
    'tabla'          => 'proyectos_tareas',
    'clave_primaria' => 'ptar_id',
    'id_registro'    => $_POST["id"]
];

$_POST["ptar_ultima_modificacion"]  = date('Y-m-d H:i:s');
$_POST["ptar_usuario_modificacion"] = $_SESSION["id"];

BaseDatos::actualizarRegistro($infoActualizar, $_POST);

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../proyectos-tareas-editar.php?id=' . $_POST["id"] . '&msg=2&proy='.$_POST["ptar_id_proyecto"].'";</script>';
exit();