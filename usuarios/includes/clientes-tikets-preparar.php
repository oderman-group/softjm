<?php

/**
 * Prepara datos del listado de tickets (filtros, paginación, filas).
 * Requiere: $conexionBdPrincipal, $datosUsuarioActual, $configuracion, $idEmpresa
 *
 * KPIs/analytics se cargan bajo demanda (AJAX) para evitar timeout en roles comerciales.
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

$usuarioIdTickets = intval($_SESSION['id']);
$zonasUsuarioTickets = $ticketsPermisos['restringirZona']
    ? Ticket::obtenerIdsZonasUsuario($conexionBdPrincipal, $usuarioIdTickets)
    : null;

// Placeholders: los números reales se rellenan al expandir indicadores (AJAX).
$totalTicketsComerciales = 0;
$ticketsComercialesEfectivos = 0;
$ticketsComercialesNoEfectivos = 0;
$porcentajeEfectivo = 0;
$porcentajeNoEfectivo = 0;

$ticketsWhere = Ticket::construirWhereListado(
    $_GET,
    $conexionBdPrincipal,
    $ticketsPermisos['excluirCiudad1122'],
    (int) $idEmpresa
);

$SQLCount = Ticket::sqlConteoListado(
    $ticketsWhere,
    $usuarioIdTickets,
    $ticketsPermisos['verTodos'],
    $ticketsPermisos['restringirZona'],
    $zonasUsuarioTickets
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
$ticketsResumenAnual = [
    'total'          => 0,
    'abiertos'       => 0,
    'cerrados'       => 0,
    'con_cotizacion' => 0,
    'ganados'        => 0,
    'perdidos'       => 0,
    'tasa_ganados'   => 0,
];

$ticketsAnalytics = [
    'anio'             => $ticketsAnioActual,
    'es_vista_cliente' => $clienteIdPagina > 0,
    'resumen'          => $ticketsResumenAnual,
    'lazy'             => true,
    'kpis_comerciales' => [
        'total'        => 0,
        'efectivos'    => 0,
        'no_efectivos' => 0,
    ],
    'por_mes'          => array_fill(0, 12, 0),
    'por_etapa'        => [],
    'por_estado'       => [],
    'por_tipo'         => [],
    'por_prioridad'    => [],
    'por_responsable'  => [],
    'top_clientes'     => [],
];
