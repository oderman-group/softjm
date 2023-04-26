<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"UPDATE proveedores SET prov_eliminado=1, prov_fecha_eliminado=now(), prov_responsable_elimacion='" . $_SESSION["id"] . "' WHERE prov_id='" . $_GET["id"] . "'");
	

echo '<script type="text/javascript">window.location.href="../proveedores.php?msg=1";</script>';
exit();