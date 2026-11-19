<?php
require_once("../sesion.php");
require_once RUTA_PROYECTO.'/usuarios/includes/api-ofima-conexion.php';
require_once RUTA_PROYECTO.'/usuarios/class/ApiOfimaClient.php';

ofimaAbortarSiPedidosBloqueados($conexionBdPrincipal, (int) $idEmpresa);

$puedeEnviar = Modulos::validarRol([151], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)
    || Modulos::validarRol([263], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
if (!$puedeEnviar) {
    echo "<span style='font-family:arial; text-align:center; color:red;'>No tienes permiso para enviar pedidos a Ofima.</span>";
    exit();
}

$pedidId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($pedidId <= 0) {
    echo "<span style='font-family:arial; text-align:center; color:red;'>Pedido no válido.</span>";
    exit();
}

$apiOfima = new ApiOfimaClient($conexionBdPrincipal, $idEmpresa);
$resultado = $apiOfima->sincronizarPedido($pedidId, $apiOfima->operacionPedidoOfima($pedidId));
$ok = !empty($resultado['success']) ? 1 : 0;
$detalle = '';
if (!$ok) {
    $detalle = isset($resultado['error']) ? (string) $resultado['error'] : 'No se pudo enviar el pedido a Ofima';
}

echo '<script type="text/javascript">window.location.href="../pedidos.php?busqueda=' . $pedidId
    . '&ofima=' . $ok
    . '&ofima_msg=' . rawurlencode($detalle)
    . '";</script>';
exit();
