<?php

/**
 * Ejecuta la consulta paginada y carga datos auxiliares del listado.
 * Requiere: $listadoFiltros, $inicio, $limite
 */

require_once RUTA_PROYECTO . '/usuarios/class/Etiqueta.php';

if (!isset($inicio)) {
    $paginaListado = isset($_GET['inicio']) && is_numeric($_GET['inicio']) ? intval($_GET['inicio']) : 1;
    $limiteListado = !empty($limite) ? intval($limite) : intval($configuracion['conf_paginacion'] ?? 50);
    $inicio        = $paginaListado <= 1 ? 0 : ($paginaListado - 1) * $limiteListado;
}

if (empty($limite)) {
    $limite = intval($configuracion['conf_paginacion'] ?? 50);
}

$filtrarPorPermisos = !Modulos::validarRol(
    [383],
    $conexionBdPrincipal,
    $conexionBdAdmin,
    $datosUsuarioActual,
    $configuracion
);

$permisosVisibilidad = $filtrarPorPermisos
    ? Cliente::permisosVisibilidadListado(intval($_SESSION['id']), $conexionBdPrincipal)
    : ['zonas' => [], 'clientes' => []];

$SQL     = Cliente::sqlFilasListado(
    $listadoFiltros['join_extra'],
    $listadoFiltros['where'],
    intval($inicio),
    intval($limite)
);
$consulta = $conexionBdPrincipal->query($SQL);

$filasClientes = [];
while ($filaCliente = mysqli_fetch_array($consulta, MYSQLI_ASSOC)) {
    $filasClientes[] = $filaCliente;
}

$idsClientesPagina = array_map(static function ($fila) {
    return intval($fila['cli_id']);
}, $filasClientes);

$etiquetasPorCliente = Etiqueta::listarPorEntidades(
    Etiqueta::MODULO_CLIENTE,
    $idsClientesPagina,
    $idEmpresa,
    $conexionBdPrincipal
);

$contadoresPorCliente = Cliente::contadoresRelacionadosBatch($idsClientesPagina, $conexionBdPrincipal);
