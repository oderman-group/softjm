<?php
require_once("../sesion.php");

$pedidId = isset($_POST["id"]) ? (int) $_POST["id"] : 0;
mysqli_query($conexionBdPrincipal,"UPDATE pedidos SET pedid_fecha_propuesta='" . $_POST["fecha"] . "', pedid_estado='" . $_POST["estado"] . "', pedid_empresa_envio='" . $_POST["empresaEnvio"] . "', pedid_codigo_seguimiento='" . $_POST["codigoSeguimiento"] . "' WHERE pedid_id='" . $pedidId . "'");

// Sincronizar con Ofima si el pedido no está facturado (no bloquea si falla)
try {
    require_once RUTA_PROYECTO.'/usuarios/class/ApiOfimaClient.php';
    $apiOfima = new ApiOfimaClient($conexionBdPrincipal, $idEmpresa);
    $apiOfima->sincronizarPedido($pedidId, 'UPDATE');
} catch (Exception $e) {
    error_log("Error al sincronizar pedido con Ofima: " . $e->getMessage());
}
	
echo '<script type="text/javascript">window.location.href="../pedidos-timeline.php?id=' . $pedidId . '&msg=2";</script>';
exit();