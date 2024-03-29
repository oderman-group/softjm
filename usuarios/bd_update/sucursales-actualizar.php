<?php
require_once("../sesion.php");
require_once("../class/BaseDatos.php");

$idPagina = 278;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$infoActualizar = [
    'tabla'          => 'sucursales_propias',
    'clave_primaria' => 'sucp_id',
    'id_registro'    => $_POST["id"],
    'sucp_id_empresa'=> $_POST["sucp_id_empresa"]
];

BaseDatos::actualizarRegistro($infoActualizar, $_POST);

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../sucursales-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();