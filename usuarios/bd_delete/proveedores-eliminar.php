<?php
require_once("../sesion.php");
$idPagina = 404;
mysqli_query($conexionBdPrincipal,"UPDATE proveedores SET prov_eliminado=1, prov_fecha_eliminado=now(), prov_responsable_elimacion='" . $_SESSION["id"] . "' WHERE prov_id='" . $_GET["id"] . "' AND prov_id_empresa='".$idEmpresa."'");
	

echo '<script type="text/javascript">window.location.href="../proveedores.php?msg=1";</script>';
exit();