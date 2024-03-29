<?php
require_once("../sesion.php");
require_once("../class/BaseDatos.php");

$idPagina = 115;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$infoEliminar = [
    'tabla'          => 'proyectos_tareas',
    'clave_primaria' => 'ptar_id',
    'id_registro'    => $_GET["id"]
];

BaseDatos::eliminarRegistro($infoEliminar);

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
exit();