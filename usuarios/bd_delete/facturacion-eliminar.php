<?php
require_once("../sesion.php");

$idPagina = 58;
include("includes/verificar-paginas.php");
mysqli_query($conexionBdPrincipal,"DELETE FROM facturacion_abonos WHERE fpab_factura='" . $_GET["id"] . "'");
mysqli_query($conexionBdPrincipal,"DELETE FROM facturacion_productos WHERE fpp_factura='" . $_GET["id"] . "'");
mysqli_query($conexionBdPrincipal,"DELETE FROM facturacion WHERE fact_id='" . $_GET["id"] . "'");

echo '<script type="text/javascript">window.location.href="../facturacion.php?cte='.$_GET['cte'].'";</script>';
exit();