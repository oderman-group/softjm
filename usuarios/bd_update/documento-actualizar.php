<?php
require_once("../sesion.php");

$idPagina = 325;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$conexionBdAdmin->query("UPDATE documentos SET doc_nombre='". $_POST["nombre"]."' WHERE doc_id='". $_POST["id"] . "'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../documentos.php?msg=2";</script>';
exit();