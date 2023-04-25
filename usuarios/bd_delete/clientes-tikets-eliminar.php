<?php
require_once("../sesion.php");

$idPagina = 91;
include("includes/verificar-paginas.php");
mysqli_query($conexionBdPrincipal,"DELETE FROM cliente_seguimiento WHERE cseg_tiket='" . $_GET["id"] . "'");
mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_tikets WHERE tik_id='" . $_GET["id"] . "'");

echo '<script type="text/javascript">window.location.href="../clientes-tikets.php?cte=' . $_GET["cte"] . '";</script>';
exit();