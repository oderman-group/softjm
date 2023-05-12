<?php
require_once("../sesion.php");

if ($_GET["ope"] == 1) {
	mysqli_query($conexionBdPrincipal,"UPDATE configuracion SET conf_banner_top='' WHERE conf_id=1");
}

if ($_GET["ope"] == 2) {
	mysqli_query($conexionBdPrincipal,"UPDATE configuracion SET conf_banner_lateral='' WHERE conf_id=1");
}

echo '<script type="text/javascript">window.location.href="../publicidad.php";</script>';
exit();