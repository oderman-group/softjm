<?php
require_once("../sesion.php");

$idPagina = 60;
include("includes/verificar-paginas.php");
mysqli_query($conexionBdPrincipal,"DELETE FROM dealer WHERE deal_id='" . $_GET["id"] . "'  AND deal_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'");

echo '<script type="text/javascript">window.location.href="../dealer.php";</script>';
exit();