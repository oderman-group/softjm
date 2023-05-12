<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"UPDATE pedidos SET pedid_fecha_propuesta='" . $_POST["fecha"] . "', pedid_estado='" . $_POST["estado"] . "', pedid_empresa_envio='" . $_POST["empresaEnvio"] . "', pedid_codigo_seguimiento='" . $_POST["codigoSeguimiento"] . "' WHERE pedid_id='" . $_POST["id"] . "'");
	
echo '<script type="text/javascript">window.location.href="../pedidos-timeline.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();