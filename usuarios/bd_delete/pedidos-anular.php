<?php
require_once("../sesion.php");
require_once RUTA_PROYECTO.'/usuarios/includes/api-ofima-conexion.php';
ofimaAbortarSiPedidosBloqueados($conexionBdPrincipal, (int) $idEmpresa);

mysqli_query($conexionBdPrincipal,"UPDATE pedidos SET pedid_estado='".PEDID_ESTADO_CAMINO."' WHERE pedid_id='" . $_GET["id"] . "'");

echo '<script type="text/javascript">window.location.href="../pedidos.php";</script>';
exit();