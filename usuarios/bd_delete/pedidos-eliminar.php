<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"DELETE FROM pedidos WHERE pedid_id='" . $_GET["id"] . "'");

echo '<script type="text/javascript">window.location.href="../pedidos.php?msg=3";</script>';
exit();