<?php
require_once("../sesion.php");
require_once("../class/BaseDatos.php");

$idPagina = 276;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$infoEliminar = [
    'tabla'          => 'sucursales_propias',
    'clave_primaria' => 'sucp_id',
    'id_registro'    => $_GET["id"],
    'sucp_id_empresa'  => $_SESSION["dataAdicional"]["id_empresa"]
];

BaseDatos::eliminarRegistro($infoEliminar);

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
exit();