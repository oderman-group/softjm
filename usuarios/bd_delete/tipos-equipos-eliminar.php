<?php
require_once("../sesion.php");
$idPagina = 413;
mysqli_query($conexionBdPrincipal,"DELETE FROM tipos_equipos WHERE tipeq_id='" . $_GET["id"] . "'");

echo '<script type="text/javascript">window.location.href="../tipos-equipos.php?msg=3";</script>';
exit();