<?php

/**
 * Prepara filtros y SQL de conteo para el listado de clientes.
 * Define: $listadoFiltros, $SQLCount, $dpto, $tipoDoc
 */

$excluirCiudadDesconocida = Modulos::validarRol(
    [385],
    $conexionBdPrincipal,
    $conexionBdAdmin,
    $datosUsuarioActual,
    $configuracion
);

$listadoFiltros = Cliente::prepararFiltrosListado(
    $_GET,
    $idEmpresa,
    $conexionBdPrincipal,
    $excluirCiudadDesconocida
);

$restringirPorZona = !Modulos::validarRol(
    [383],
    $conexionBdPrincipal,
    $conexionBdAdmin,
    $datosUsuarioActual,
    $configuracion
);

if ($restringirPorZona) {
    $listadoFiltros['where'] = Cliente::appendFiltrosVisibilidadAnalyticsWhere(
        $listadoFiltros['where'],
        intval($_SESSION['id'] ?? 0),
        true
    );
}

$SQLCount = Cliente::sqlConteoListado($listadoFiltros['join_extra'], $listadoFiltros['where']);
$dpto     = $listadoFiltros['dpto'];
$tipoDoc  = $listadoFiltros['tipo_doc'];
