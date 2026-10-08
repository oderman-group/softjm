<?php
include('../sesion.php');
require_once RUTA_PROYECTO . '/usuarios/class/Producto.php';
require_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';

header('Content-Type: application/json; charset=utf-8');

$pk = 'prod_id';
$ofimaProductosActiva = ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa);
$columna = '';
if (Modulos::validarRol([400], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
    $columna = 'columnas1';
}

$tamanosPaginaProducto = [10, 25, 50, 100, 200];
$porPaginaProducto = (isset($_GET['por']) && in_array((int) $_GET['por'], $tamanosPaginaProducto, true))
    ? (int) $_GET['por']
    : 10;

include RUTA_PROYECTO . '/usuarios/includes/productos-listado-consulta.php';

ob_start();
include RUTA_PROYECTO . '/usuarios/includes/productos-listado-filas.php';
$htmlFilas = ob_get_clean();

ob_start();
include RUTA_PROYECTO . '/usuarios/includes/productos-listado-paginacion.php';
$htmlPaginacion = ob_get_clean();

echo json_encode([
    'success' => true,
    'html' => $htmlFilas,
    'pagination' => $htmlPaginacion,
    'total' => (int) ($numTotalProductos ?? 0),
    'pagina' => (int) ($paginaListaProductos ?? 1),
    'por' => (int) $porPaginaProducto,
]);
