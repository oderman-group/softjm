<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"DELETE FROM cupones WHERE cupo_id='" . $_GET["id"] . "'");

echo '<script type="text/javascript">window.location.href="../cupones.php?msg=3";</script>';
exit();