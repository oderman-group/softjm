<?php
require_once("../sesion.php");
$idPagina = 372;
require_once RUTA_PROYECTO.'/usuarios/includes/api-ofima-conexion.php';
ofimaAbortarSiPedidosBloqueados($conexionBdPrincipal, (int) $idEmpresa);

$pedidoId = (int) ($_GET['id'] ?? 0);
$empresaPedido = (int) ($_SESSION['dataAdicional']['id_empresa'] ?? 0);
$consultaOfima = $conexionBdPrincipal->prepare(
	'SELECT pedid_ofima_integrado, pedid_ofima_estado FROM pedidos WHERE pedid_id = ? AND pedid_id_empresa = ? LIMIT 1'
);
if ($consultaOfima) {
	$consultaOfima->bind_param('ii', $pedidoId, $empresaPedido);
	$consultaOfima->execute();
	$pedidoOfima = $consultaOfima->get_result()->fetch_assoc();
	$yaEnOfima = $pedidoOfima
		&& ((int) ($pedidoOfima['pedid_ofima_integrado'] ?? 0) === 1 || (int) ($pedidoOfima['pedid_ofima_estado'] ?? 0) === 1);
	if ($yaEnOfima) {
		echo "<span style='font-family:arial; text-align:center; color:red;'>No se puede eliminar este pedido porque ya está en Ofima.</span>";
		exit();
	}
}

mysqli_query($conexionBdPrincipal,"DELETE FROM pedidos WHERE pedid_id='" . $_GET["id"] . "' AND pedid_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'");

echo '<script type="text/javascript">window.location.href="../pedidos.php?msg=3";</script>';
exit();