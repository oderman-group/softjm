<?php
include('sesion.php');
include(RUTA_PROYECTO . '/usuarios/class/Cliente.php');

if (isset($_GET['buscar'])) {
    $_GET['buscar'] = trim($_GET['buscar']);
}

include('includes/clientes-listado-filtros.php');
include('includes/clientes-listado-cargar.php');

ob_start();
include('includes/clientes-listado-render-filas.php');
$htmlFilas = ob_get_clean();

$resultadoConteo = $conexionBdPrincipal->query($SQLCount);
$filaConteo      = mysqli_fetch_array($resultadoConteo, MYSQLI_NUM);
$total           = intval($filaConteo[0] ?? 0);

ob_start();
include('includes/clientes-listado-paginacion.php');
$htmlPaginacion = ob_get_clean();

$formatoJson = isset($_GET['format']) && $_GET['format'] === 'json';

if ($formatoJson) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'html'        => $htmlFilas,
        'total'       => $total,
        'pagination'  => $htmlPaginacion,
        'pagina'      => isset($_GET['inicio']) && is_numeric($_GET['inicio']) ? max(1, intval($_GET['inicio'])) : 1,
    ]);
    exit;
}

echo $htmlFilas;
