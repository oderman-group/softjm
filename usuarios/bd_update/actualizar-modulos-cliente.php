<?php
require_once("../sesion.php");

$idPagina = 414;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$clio_id = $_POST["id"];
$consultaModulo = $conexionBdAdmin->query("SELECT m.mxe_id_modulo FROM modulos_empresa m WHERE m.mxe_id_empresa = '$clio_id' AND mxe_id_modulo IN ('$ModulosEliminarStr');");
$modulosExistentes = [];

while ($fila = $consultaModulo->fetch_assoc()) {
	$modulosExistentes[] = $fila['mxe_id_modulo'];
}

if (isset($_POST["ModulosS"]) && is_array($_POST["ModulosS"])) {
    $ModulosEliminar = array_diff($modulosExistentes, $_POST["ModulosS"]);
    $ModulosAInsertar = array_diff($_POST["ModulosS"], $modulosExistentes);

    if (!empty($ModulosEliminar)) {
        $ModulosEliminarStr = implode("','", $ModulosEliminar);
        $conexionBdAdmin->query("DELETE FROM modulos_empresa WHERE mxe_id_empresa = '$clio_id' AND mxe_id_modulo IN ('$ModulosEliminarStr')");
    }

    if (!empty($ModulosAInsertar)) {
        foreach ($ModulosAInsertar as $modulo) {
            if ($modulo != "") {
                $conexionBdAdmin->query("INSERT INTO modulos_empresa (mxe_id_modulo, mxe_id_empresa) VALUES ('$modulo', '$clio_id')");
            }
        }
    }
} else {
    // Tratar el caso en que $_POST["ModulosS"] no está definido o no es un arreglo
}


echo '<script type="text/javascript">window.location.href="../clientes-orion-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();
