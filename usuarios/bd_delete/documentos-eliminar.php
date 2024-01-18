<?php
require_once("../sesion.php");
require_once("../class/BaseDatos.php");

$idPagina = 294;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
mysqli_query($conexionBdPrincipal,"DELETE FROM documentos WHERE doc_id='" . $_GET["id"] . "'");

echo '<script type="text/javascript">window.location.href="../documentos.php?msg=3";</script>';
exit();