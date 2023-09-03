<?php
require_once("../sesion.php");
require_once("../class/BaseDatos.php");

$idPagina = 228;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$infoActualizar = [
    'tabla'          => 'proyectos',
    'clave_primaria' => 'proy_id',
    'id_registro'    => $_POST["id"]
];

$_POST["proy_ultima_modificacion"]  = date('Y-m-d H:i:s');
$_POST["proy_usuario_modificacion"] = $_SESSION["id"];

BaseDatos::actualizarRegistro($infoActualizar, $_POST);

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../proyectos-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();