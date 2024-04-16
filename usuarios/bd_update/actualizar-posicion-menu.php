<?php
require_once("../sesion.php");

$idPagina = 87;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$conexionBdAdmin->query("UPDATE modulos_empresa SET mxe_posicion=(SELECT mxe_posicion FROM modulos_empresa WHERE mxe_id_modulo='".$_GET["id"]."' AND mxe_id_empresa='".$idEmpresa."')
WHERE mxe_posicion='".$_GET["endPosition"]."' AND mxe_id_empresa='".$idEmpresa."'");

$conexionBdAdmin->query("UPDATE modulos_empresa SET mxe_posicion='".$_GET["endPosition"]."' 
WHERE mxe_id_modulo='".$_GET["id"]."' AND mxe_id_empresa='".$idEmpresa."'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

exit();