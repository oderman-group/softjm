<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"UPDATE pedidos SET pedid_estado=2 WHERE pedid_id='" . $_GET["id"] . "'");

echo '<script type="text/javascript">window.location.href="../pedidos.php";</script>';
exit();