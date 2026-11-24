<?php
include('../sesion.php');
require_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';

header('Content-Type: application/json; charset=utf-8');

$idPagina = 77;
if (!Modulos::validarRol([$idPagina], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
	echo json_encode(array('success' => false, 'message' => 'Sin permiso'));
	exit();
}

$ofimaPedidosBloqueados = ofimaPedidosBloqueados($conexionBdPrincipal, (int) $idEmpresa);
$ofimaBotonEnvio = ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa) && empty($ofimaPedidosBloqueados);
$puedeEditarCotiz = Modulos::validarRol([79], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$puedePdfCotiz = Modulos::validarRol([50], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$puedeReplicarCotiz = Modulos::validarRol([253], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
$puedeGenerarPedidoCotiz = Modulos::validarRol([263], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);

include RUTA_PROYECTO . '/usuarios/includes/cotizaciones-listado-consulta.php';

ob_start();
include RUTA_PROYECTO . '/usuarios/includes/cotizaciones-listado-filas.php';
$htmlFilas = ob_get_clean();

ob_start();
include RUTA_PROYECTO . '/usuarios/includes/cotizaciones-listado-paginacion.php';
$htmlPaginacion = ob_get_clean();

echo json_encode(array(
	'success' => true,
	'html' => $htmlFilas,
	'pagination' => $htmlPaginacion,
	'total' => (int) $numTotalCotizaciones,
	'pagina' => (int) $paginaListaCotizaciones,
	'por' => (int) $porPaginaCotizacion,
));
