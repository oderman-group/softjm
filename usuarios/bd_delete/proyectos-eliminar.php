<?php
require_once("../sesion.php");
require_once("../class/BaseDatos.php");

$idPagina = 114;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$conexionBdPrincipal->query("DELETE FROM proyectos WHERE proy_id='" . $_GET["id"] ."' AND proy_id_empresa='" . $_SESSION["dataAdicional"]["id_empresa"]."'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
exit();