<?php
require_once("../sesion.php");
require_once("../class/BaseDatos.php");

$idPagina = 327;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdAdmin,"DELETE FROM documentos WHERE doc_id ='" . $_GET["id"] . "' AND doc_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");
mysqli_query($conexionBdAdmin,"DELETE FROM documentos_configuracion WHERE dconf_id_documento ='" . $_GET["id"] . "' AND dconf_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");

echo '<script type="text/javascript">window.location.href="../documentos.php?msg=3";</script>';
exit();