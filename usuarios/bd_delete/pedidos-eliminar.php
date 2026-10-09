<?php
require_once("../sesion.php");
$idPagina = 372;
require_once RUTA_PROYECTO.'/usuarios/includes/api-ofima-conexion.php';
ofimaAbortarSiPedidosBloqueados($conexionBdPrincipal, (int) $idEmpresa);

mysqli_query($conexionBdPrincipal,"DELETE FROM pedidos WHERE pedid_id='" . $_GET["id"] . "' AND pedid_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'");

echo '<script type="text/javascript">window.location.href="../pedidos.php?msg=3";</script>';
exit();