<?php
require_once("../sesion.php");
require_once RUTA_PROYECTO.'/usuarios/includes/api-ofima-conexion.php';
ofimaAbortarSiPedidosBloqueados($conexionBdPrincipal, (int) $idEmpresa);

mysqli_query($conexionBdPrincipal,"INSERT INTO pedidos_novedades(pednov_dia, pednov_mes, pednov_estado, pednov_novedad, pednov_pedido, pednov_usuario)VALUES('" . $_POST["dia"] . "', '" . $_POST["mes"] . "', '" . $_POST["estado"] . "', '" . $_POST["novedad"] . "', '" . $_POST["id"] . "', '" . $_SESSION["id"] . "')");
	

echo '<script type="text/javascript">window.location.href="../pedidos-timeline.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();