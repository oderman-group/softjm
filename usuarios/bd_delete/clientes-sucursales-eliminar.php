<?php   
require_once("../sesion.php");

$idPagina = 86;
include("includes/verificar-paginas.php");
mysqli_query($conexionBdPrincipal,"DELETE FROM sucursales WHERE sucu_id='" . $_GET["id"] . "'");

echo '<script type="text/javascript">window.location.href="../clientes-sucursales.php";</script>';
exit();