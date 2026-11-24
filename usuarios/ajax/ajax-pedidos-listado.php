<?php
include('../sesion.php');
require_once RUTA_PROYECTO . '/usuarios/class/Pedido.php';
require_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';

header('Content-Type: application/json; charset=utf-8');

if (!Modulos::validarRol([151], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
	echo json_encode(array('success' => false, 'message' => 'Sin permiso'));
	exit();
}

$ofimaPedidosBloqueados = ofimaPedidosBloqueados($conexionBdPrincipal, (int) $idEmpresa);
$ofimaBotonEnvio = ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa) && empty($ofimaPedidosBloqueados);
$puedeTimelinePedido = Modulos::validarRol([237], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$puedeEliminarPedido = Modulos::validarRol([372], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$puedePdfPedido = Modulos::validarRol([373], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$puedeRemisionPedido = Modulos::validarRol([374], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);

include RUTA_PROYECTO . '/usuarios/includes/pedidos-listado-consulta.php';

ob_start();
include RUTA_PROYECTO . '/usuarios/includes/pedidos-listado-filas.php';
$htmlFilas = ob_get_clean();

ob_start();
include RUTA_PROYECTO . '/usuarios/includes/pedidos-listado-paginacion.php';
$htmlPaginacion = ob_get_clean();

echo json_encode(array(
	'success' => true,
	'html' => $htmlFilas,
	'pagination' => $htmlPaginacion,
	'total' => (int) $numTotalPedidos,
	'pagina' => (int) $paginaListaPedidos,
	'por' => (int) $porPaginaPedido,
));
