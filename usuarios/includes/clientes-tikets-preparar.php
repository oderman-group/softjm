<?php

/**
 * Prepara datos del listado de tickets (filtros, paginación, filas).
 * Requiere: $conexionBdPrincipal, $datosUsuarioActual, $configuracion, $idEmpresa
 */

$clienteIdPagina = !empty($_GET['cte']) && is_numeric($_GET['cte']) ? intval($_GET['cte']) : 0;

if ($clienteIdPagina > 0) {
    $consultaDatos = $conexionBdPrincipal->query("
        SELECT cli_id, cli_nombre
        FROM clientes
        WHERE cli_id = '" . $clienteIdPagina . "'
          AND cli_id_empresa = '" . intval($idEmpresa) . "'
        LIMIT 1
    ");
    $cliente = mysqli_fetch_array($consultaDatos, MYSQLI_ASSOC) ?: ['cli_nombre' => ''];
} else {
    $cliente = ['cli_nombre' => ''];
}

$ticketsPermisos = [
    'verTodos'          => Modulos::validarRol([384], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'restringirZona'    => !Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'excluirCiudad1122' => Modulos::validarRol([385], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'verSeguimientos'   => Modulos::validarRol([12], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'verCotizacion'     => Modulos::validarRol([77], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'verTicketDrawer'   => Modulos::validarRol([90], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
    'agregarTicket'     => Modulos::validarRol([89], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion),
];

$kpisTickets = Ticket::obtenerKpisComercialesResumen(
    $conexionBdPrincipal,
    $clienteIdPagina > 0 ? $clienteIdPagina : null
);
$totalTicketsComerciales = $kpisTickets['total'];
$ticketsComercialesEfectivos = $kpisTickets['efectivos'];
$ticketsComercialesNoEfectivos = $kpisTickets['no_efectivos'];
$porcentajeEfectivo = $totalTicketsComerciales > 0
    ? round(($ticketsComercialesEfectivos / $totalTicketsComerciales) * 100, 1)
    : 0;
$porcentajeNoEfectivo = $totalTicketsComerciales > 0
    ? round(($ticketsComercialesNoEfectivos / $totalTicketsComerciales) * 100, 1)
    : 0;

$ticketsWhere = Ticket::construirWhereListado(
    $_GET,
    $conexionBdPrincipal,
    $ticketsPermisos['excluirCiudad1122']
);

$usuarioIdTickets = intval($_SESSION['id']);

$SQLCount = Ticket::sqlConteoListado(
    $ticketsWhere,
    $usuarioIdTickets,
    $ticketsPermisos['verTodos'],
    $ticketsPermisos['restringirZona']
);

$usuariosFiltroTickets = [];
$consultaUsuariosFiltro = $conexionBdPrincipal->query("
    SELECT usr_id, usr_nombre
    FROM usuarios
    WHERE usr_bloqueado != 1
      AND usr_id_empresa = '" . intval($idEmpresa) . "'
    ORDER BY usr_nombre
");
while ($consultaUsuariosFiltro && ($usuarioFiltro = mysqli_fetch_array($consultaUsuariosFiltro, MYSQLI_ASSOC))) {
    $usuariosFiltroTickets[] = $usuarioFiltro;
}

$ticketsAnioActual = intval(date('Y'));
$ticketsAnalytics = Ticket::obtenerEstadisticasAnuales(
    $conexionBdPrincipal,
    $ticketsAnioActual,
    $usuarioIdTickets,
    $ticketsPermisos['verTodos'],
    $ticketsPermisos['restringirZona'],
    $clienteIdPagina > 0 ? $clienteIdPagina : null,
    $ticketsPermisos['excluirCiudad1122'],
    $opcionesEtapa
);

$ticketsResumenAnual = $ticketsAnalytics['resumen'] ?? [];
