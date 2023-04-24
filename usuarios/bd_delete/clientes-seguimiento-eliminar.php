<?php
require_once("../sesion.php");

$idPagina = 56;
include("includes/verificar-paginas.php");
mysqli_query($conexionBdPrincipal,"DELETE FROM cliente_seguimiento WHERE cseg_id='" . $_GET["id"] . "'");

echo '<script type="text/javascript">window.location.href="../clientes-seguimiento.php?cte=' . $_GET["cte"] . '";</script>';
exit();