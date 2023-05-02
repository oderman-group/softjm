<?php
require_once("../sesion.php");

$idPagina = 71;
include("../includes/verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"DELETE FROM productos_materiales WHERE ppmt_id='" . $_GET["id"] . "'");

echo '<script type="text/javascript">window.location.href="../productos-materiales.php?msg=3&pdto='.$_GET["pdto"].'";</script>';
exit();