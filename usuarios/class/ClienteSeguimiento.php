<?php
require_once RUTA_PROYECTO . '/usuarios/class/BaseDatos.php';
require_once RUTA_PROYECTO . '/usuarios/class/Tickets.php';

class ClienteSeguimiento extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'cliente_seguimiento';
    public static $primaryKey = 'cseg_id';
    public static $tableAs    = 'cs';

    /**
     * Obtiene el detalle de un seguimiento con ticket asociado para el drawer.
     */
    public static function obtenerDetalleDrawer(
        int $seguimientoId,
        int $clienteId,
        int $empresaId,
        $conexionBdPrincipal
    ): ?array {
        $seguimientoId = intval($seguimientoId);
        $clienteId     = intval($clienteId);
        $empresaId     = intval($empresaId);

        $consulta = mysqli_query($conexionBdPrincipal, "
            SELECT cs.*,
                c.cli_nombre, c.cli_id, c.cli_zona,
                u.usr_nombre AS responsable_nombre,
                enc.usr_nombre AS encargado_nombre,
                cont.cont_nombre, cont.cont_telefono, cont.cont_email,
                ct.tik_id, ct.tik_tipo_tiket, ct.tik_asunto_principal, ct.tik_fecha_creacion,
                ct.tik_valor, ct.tik_etapa, ct.tik_tipo_negocio, ct.tik_origen_negocio,
                ct.tik_usuario_responsable, ct.tik_id_cotizacion, ct.tik_estado, ct.tik_cliente,
                tik_usr.usr_nombre AS ticket_responsable_nombre
            FROM " . self::$schema . ".cliente_seguimiento cs
            INNER JOIN " . self::$schema . ".clientes c
                ON c.cli_id = cs.cseg_cliente AND c.cli_id_empresa = '" . $empresaId . "'
            INNER JOIN " . self::$schema . ".usuarios u ON u.usr_id = cs.cseg_usuario_responsable
            LEFT JOIN " . self::$schema . ".usuarios enc ON enc.usr_id = cs.cseg_usuario_encargado
            LEFT JOIN " . self::$schema . ".contactos cont ON cont.cont_id = cs.cseg_contacto
            LEFT JOIN " . self::$schema . ".clientes_tikets ct ON ct.tik_id = cs.cseg_tiket
            LEFT JOIN " . self::$schema . ".usuarios tik_usr ON tik_usr.usr_id = ct.tik_usuario_responsable
            WHERE cs.cseg_id = '" . $seguimientoId . "'
              AND cs.cseg_cliente = '" . $clienteId . "'
            LIMIT 1
        ");

        if (!$consulta || mysqli_num_rows($consulta) === 0) {
            return null;
        }

        $fila = mysqli_fetch_assoc($consulta);

        return self::formatearDetalleParaApi($fila, $conexionBdPrincipal);
    }

    /**
     * Obtiene un ticket con todos sus seguimientos para el drawer de tickets.
     */
    public static function obtenerTicketConSeguimientosDrawer(
        int $ticketId,
        int $clienteId,
        int $empresaId,
        $conexionBdPrincipal
    ): ?array {
        return self::obtenerTicketDrawer($ticketId, $clienteId, $empresaId, $conexionBdPrincipal, false);
    }

    /**
     * Obtiene el detalle completo de un ticket con seguimientos para el drawer de edición.
     */
    public static function obtenerTicketDetalleDrawer(
        int $ticketId,
        int $clienteId,
        int $empresaId,
        $conexionBdPrincipal
    ): ?array {
        return self::obtenerTicketDrawer($ticketId, $clienteId, $empresaId, $conexionBdPrincipal, true);
    }

    private static function obtenerTicketDrawer(
        int $ticketId,
        int $clienteId,
        int $empresaId,
        $conexionBdPrincipal,
        bool $detalleCompleto
    ): ?array {
        $ticketId  = intval($ticketId);
        $clienteId = intval($clienteId);
        $empresaId = intval($empresaId);

        $consultaTicket = mysqli_query($conexionBdPrincipal, "
            SELECT t.*,
                c.cli_nombre, c.cli_id, c.cli_zona,
                u.usr_nombre AS responsable_nombre
            FROM " . self::$schema . ".clientes_tikets t
            INNER JOIN " . self::$schema . ".clientes c
                ON c.cli_id = t.tik_cliente AND c.cli_id_empresa = '" . $empresaId . "'
            INNER JOIN " . self::$schema . ".usuarios u ON u.usr_id = t.tik_usuario_responsable
            WHERE t.tik_id = '" . $ticketId . "'
              AND t.tik_cliente = '" . $clienteId . "'
            LIMIT 1
        ");

        if (!$consultaTicket || mysqli_num_rows($consultaTicket) === 0) {
            return null;
        }

        $ticketFila = mysqli_fetch_assoc($consultaTicket);
        $ticket     = $detalleCompleto
            ? self::formatearTicketDetalleCompleto($ticketFila, $conexionBdPrincipal)
            : self::formatearTicketParaApi($ticketFila, $conexionBdPrincipal);

        $consultaSeguimientos = mysqli_query($conexionBdPrincipal, "
            SELECT cs.*,
                u.usr_nombre AS responsable_nombre,
                enc.usr_nombre AS encargado_nombre,
                cont.cont_nombre, cont.cont_telefono, cont.cont_email
            FROM " . self::$schema . ".cliente_seguimiento cs
            INNER JOIN " . self::$schema . ".usuarios u ON u.usr_id = cs.cseg_usuario_responsable
            LEFT JOIN " . self::$schema . ".usuarios enc ON enc.usr_id = cs.cseg_usuario_encargado
            LEFT JOIN " . self::$schema . ".contactos cont ON cont.cont_id = cs.cseg_contacto
            WHERE cs.cseg_tiket = '" . $ticketId . "'
              AND cs.cseg_cliente = '" . $clienteId . "'
            ORDER BY cs.cseg_id DESC
        ");

        $seguimientos  = [];
        $completados   = 0;
        $pendientes    = 0;
        $tipoTicketId  = intval($ticketFila['tik_tipo_tiket'] ?? 0);

        if ($consultaSeguimientos) {
            while ($filaSeg = mysqli_fetch_assoc($consultaSeguimientos)) {
                $item = self::formatearSeguimientoResumen($filaSeg, $tipoTicketId);
                $seguimientos[] = $item;
                if ($item['realizado']) {
                    $completados++;
                } else {
                    $pendientes++;
                }
            }
        }

        return [
            'ticket' => $ticket,
            'cliente' => [
                'id'     => $clienteId,
                'nombre' => $ticketFila['cli_nombre'] ?? '',
                'zona'   => intval($ticketFila['cli_zona'] ?? 0),
            ],
            'estadisticas' => [
                'total'       => count($seguimientos),
                'completados' => $completados,
                'pendientes'  => $pendientes,
            ],
            'seguimientos' => $seguimientos,
            'urls' => [
                'paginaCompleta' => $detalleCompleto
                    ? 'clientes-tikets-editar.php?id=' . $ticketId . '&cte=' . $clienteId
                    : 'clientes-seguimiento.php?idTK=' . $ticketId . '&cte=' . $clienteId,
                'editarTicket'   => 'clientes-tikets-editar.php?id=' . $ticketId . '&cte=' . $clienteId,
                'cliente'        => 'clientes-editar.php?id=' . $clienteId,
                'cotizacion'     => !empty($ticketFila['tik_id_cotizacion'])
                    ? 'cotizaciones-editar.php?id=' . intval($ticketFila['tik_id_cotizacion'])
                    : null,
                'seguimientos'   => 'clientes-seguimiento.php?idTK=' . $ticketId . '&cte=' . $clienteId,
            ],
        ];
    }

    /**
     * Valida acceso por zona del cliente (misma lógica que clientes-editar.php).
     */
    public static function usuarioPuedeVerZonaCliente(
        int $zonaCliente,
        ?array $zonasUsuarioPermitidas
    ): bool {
        if ($zonasUsuarioPermitidas === null) {
            return true;
        }

        return in_array(intval($zonaCliente), $zonasUsuarioPermitidas, true);
    }

    private static function formatearDetalleParaApi(array $fila, $conexionBdPrincipal): array {
        global $tipoTicket, $opcionesEtapa, $opcionesTipoNegocio, $opcionesOrigenNegocio;

        $tipoTicketId   = intval($fila['tik_tipo_tiket'] ?? 0);
        if ($tipoTicketId === 0) {
            $tipoTicketId = intval($fila['cseg_tipo'] ?? 0);
        }
        $estadoTicket   = !empty($fila['tik_id'])
            ? intval(Ticket::getEstado(intval($fila['tik_id']), $conexionBdPrincipal))
            : null;
        $realizado      = intval($fila['cseg_realizado'] ?? 0) === 1;
        $ticketId       = !empty($fila['tik_id']) ? intval($fila['tik_id']) : null;
        $seguimientoId  = intval($fila['cseg_id']);
        $clienteId      = intval($fila['cseg_cliente']);

        $formaContacto = ['', 'La empresa contactó al cliente', 'El cliente contactó a la empresa'];
        $canalContacto = ['', 'Facebook', 'WhatsApp', 'Fijo', 'Celular', 'Personal', 'Skype', 'Otro', 'Correo', 'Sitio Web'];
        $canalProximo  = ['', 'WhatsApp', 'Fijo', 'Celular', 'Visitar al cliente', 'El cliente me visita', 'Skype', 'Otro', 'Correo', 'Sitio Web'];

        $tipoTicketLabel = $tipoTicket[$tipoTicketId] ?? '—';
        $etapaLabel      = self::resolverEtapa($fila['tik_etapa'] ?? 0, $opcionesEtapa);
        $tipoNegocio     = self::resolverIndice($fila['tik_tipo_negocio'] ?? 0, $opcionesTipoNegocio, 3);
        $origenNegocio   = self::resolverIndice($fila['tik_origen_negocio'] ?? 0, $opcionesOrigenNegocio, 8);

        $valorTicket = '';
        if (!empty($fila['tik_valor'])) {
            $valorTicket = '$' . number_format(floatval($fila['tik_valor']), 0, ',', '.');
        }

        $archivo = trim($fila['cseg_archivo'] ?? '');
        $bloqueadoVarios = intval($fila['cseg_varios'] ?? 0) >= 1 && intval($fila['cseg_usuario_encargado'] ?? 0) === 0;

        return [
            'seguimiento' => [
                'id'                => $seguimientoId,
                'fechaReporte'      => $fila['cseg_fecha_reporte'] ?? '',
                'fechaContacto'     => $fila['cseg_fecha_contacto'] ?? '',
                'responsable'       => $fila['responsable_nombre'] ?? '',
                'realizado'         => $realizado,
                'estadoLabel'       => $realizado ? 'Completado' : 'Pendiente',
                'estadoClase'       => $realizado ? 'completado' : 'pendiente',
                'formaContacto'     => $formaContacto[intval($fila['cseg_forma_contacto'] ?? 0)] ?? '—',
                'canal'             => $canalContacto[intval($fila['cseg_canal'] ?? 0)] ?? '—',
                'observacion'       => $fila['cseg_observacion'] ?? '',
                'consiguioDatos'    => intval($fila['cseg_consiguio_datos'] ?? 0) === 1,
                'cotizo'            => intval($fila['cseg_cotizo'] ?? 0) === 1,
                'vendio'            => intval($fila['cseg_vendio'] ?? 0) === 1,
                'demostracion'      => intval($fila['cseg_demostracion'] ?? 0) === 1,
                'visita'            => intval($fila['cseg_visita'] ?? 0) === 1,
                'cotizacion'        => $fila['cseg_cotizacion'] ?? '',
                'archivo'           => $archivo,
                'archivoUrl'        => $archivo !== '' ? 'files/adjuntos/' . $archivo : '',
                'fechaProximo'      => $fila['cseg_fecha_proximo_contacto'] ?? '',
                'horaProximo'       => $fila['cseg_hora_proximo_contacto'] ?? '',
                'minutosRecordar'   => $fila['cseg_minutos_recordar_anticipadamente'] ?? '',
                'asuntoProximo'     => $fila['cseg_asunto'] ?? '',
                'encargadoProximo'  => $fila['encargado_nombre'] ?? '—',
                'canalProximo'      => $canalProximo[intval($fila['cseg_canal_proximo_contacto'] ?? 0)] ?? '—',
                'bloqueadoVarios'   => $bloqueadoVarios,
                'tipoSeguimiento'   => $tipoTicketLabel,
                'contacto'          => [
                    'nombre'   => $fila['cont_nombre'] ?? '',
                    'telefono' => $fila['cont_telefono'] ?? '',
                    'email'    => $fila['cont_email'] ?? '',
                ],
            ],
            'ticket' => $ticketId ? [
                'id'              => $ticketId,
                'tipo'            => $tipoTicketLabel,
                'tipoId'          => $tipoTicketId,
                'asunto'          => $fila['tik_asunto_principal'] ?? '',
                'fechaCreacion'   => $fila['tik_fecha_creacion'] ?? '',
                'valor'           => $valorTicket,
                'etapa'           => $etapaLabel,
                'tipoNegocio'     => $tipoNegocio,
                'origenNegocio'   => $origenNegocio,
                'responsable'     => $fila['ticket_responsable_nombre'] ?? '',
                'cotizacionId'    => !empty($fila['tik_id_cotizacion']) ? intval($fila['tik_id_cotizacion']) : null,
                'estado'          => $estadoTicket,
                'estadoLabel'     => $estadoTicket === Ticket::ESTADO_ABIERTO ? 'Abierto' : 'Cerrado',
                'estadoClase'     => $estadoTicket === Ticket::ESTADO_ABIERTO ? 'abierto' : 'cerrado',
                'clienteId'       => intval($fila['tik_cliente'] ?? $clienteId),
                'clienteNombre'   => $fila['cli_nombre'] ?? '',
            ] : null,
            'cliente' => [
                'id'     => $clienteId,
                'nombre' => $fila['cli_nombre'] ?? '',
                'zona'   => intval($fila['cli_zona'] ?? 0),
            ],
            'urls' => [
                'editarCompleta' => $ticketId
                    ? 'clientes-seguimiento-editar.php?id=' . $seguimientoId . '&idTK=' . $ticketId . '&cte=' . $clienteId
                    : 'clientes-seguimiento-editar.php?id=' . $seguimientoId . '&cte=' . $clienteId,
                'editarTicket'   => $ticketId ? 'clientes-tikets-editar.php?id=' . $ticketId : null,
                'cliente'        => 'clientes-editar.php?id=' . $clienteId,
                'cotizacion'     => !empty($fila['tik_id_cotizacion'])
                    ? 'cotizaciones-editar.php?id=' . intval($fila['tik_id_cotizacion'])
                    : null,
            ],
        ];
    }

    private static function formatearTicketParaApi(array $fila, $conexionBdPrincipal): array {
        global $tipoTicket, $opcionesEtapa, $opcionesTipoNegocio, $opcionesOrigenNegocio;

        $tipoTicketId = intval($fila['tik_tipo_tiket'] ?? 0);
        $estadoTicket = intval($fila['tik_estado'] ?? Ticket::ESTADO_CERRADO);
        $prioridades  = ['', 'Normal', 'Urgente', 'Muy Urgente'];

        $valorTicket = '';
        if (!empty($fila['tik_valor'])) {
            $valorTicket = '$' . number_format(floatval($fila['tik_valor']), 0, ',', '.');
        }

        return [
            'id'            => intval($fila['tik_id']),
            'tipo'          => $tipoTicket[$tipoTicketId] ?? '—',
            'tipoId'        => $tipoTicketId,
            'asunto'        => $fila['tik_asunto_principal'] ?? '',
            'fechaCreacion' => $fila['tik_fecha_creacion'] ?? '',
            'valor'         => $valorTicket,
            'etapa'         => self::resolverEtapa($fila['tik_etapa'] ?? 0, $opcionesEtapa),
            'tipoNegocio'   => self::resolverIndice($fila['tik_tipo_negocio'] ?? 0, $opcionesTipoNegocio, 3),
            'origenNegocio' => self::resolverIndice($fila['tik_origen_negocio'] ?? 0, $opcionesOrigenNegocio, 8),
            'responsable'   => $fila['responsable_nombre'] ?? '',
            'cotizacionId'  => !empty($fila['tik_id_cotizacion']) ? intval($fila['tik_id_cotizacion']) : null,
            'estado'        => $estadoTicket,
            'estadoLabel'   => $estadoTicket === Ticket::ESTADO_ABIERTO ? 'Abierto' : 'Cerrado',
            'estadoClase'   => $estadoTicket === Ticket::ESTADO_ABIERTO ? 'abierto' : 'cerrado',
            'prioridad'     => $prioridades[intval($fila['tik_prioridad'] ?? 1)] ?? 'Normal',
            'prioridadId'   => intval($fila['tik_prioridad'] ?? 1),
            'clienteId'     => intval($fila['tik_cliente'] ?? 0),
            'clienteNombre' => $fila['cli_nombre'] ?? '',
        ];
    }

    private static function formatearTicketDetalleCompleto(array $fila, $conexionBdPrincipal): array {
        global $negociosGanados, $negociosPerdidos;

        $ticket   = self::formatearTicketParaApi($fila, $conexionBdPrincipal);
        $canales  = ['', 'Facebook', 'WhatsApp', 'Fijo', 'Celular', 'Personal', 'Skype', 'Otro'];
        $etapaId  = intval($fila['tik_etapa'] ?? 0);

        $ticket['canal']         = $canales[intval($fila['tik_canal'] ?? 0)] ?? '—';
        $ticket['observaciones'] = $fila['tik_observaciones'] ?? '';
        $ticket['etapaId']       = $etapaId;
        $ticket['razonGanado']   = ($etapaId === 5 && intval($fila['tik_razon_ganado'] ?? 0) > 0)
            ? ($negociosGanados[intval($fila['tik_razon_ganado'])] ?? '—')
            : '';
        $ticket['razonPerdido']  = ($etapaId === 6 && intval($fila['tik_razon_perdido'] ?? 0) > 0)
            ? ($negociosPerdidos[intval($fila['tik_razon_perdido'])] ?? '—')
            : '';

        return $ticket;
    }

    private static function formatearSeguimientoResumen(array $fila, int $tipoTicketId): array {
        $formaContacto = ['', 'La empresa contactó al cliente', 'El cliente contactó a la empresa'];
        $canalContacto = ['', 'Facebook', 'WhatsApp', 'Fijo', 'Celular', 'Personal', 'Skype', 'Otro', 'Correo', 'Sitio Web'];
        $canalProximo  = ['', 'WhatsApp', 'Fijo', 'Celular', 'Visitar al cliente', 'El cliente me visita', 'Skype', 'Otro', 'Correo', 'Sitio Web'];

        $realizado     = intval($fila['cseg_realizado'] ?? 0) === 1;
        $seguimientoId = intval($fila['cseg_id']);
        $ticketId      = intval($fila['cseg_tiket'] ?? 0);
        $clienteId     = intval($fila['cseg_cliente'] ?? 0);
        $fechaProximo  = $fila['cseg_fecha_proximo_contacto'] ?? '';
        $tieneProximo  = $fechaProximo !== '' && $fechaProximo !== '0000-00-00';

        return [
            'id'               => $seguimientoId,
            'numero'           => $seguimientoId,
            'fechaReporte'     => $fila['cseg_fecha_reporte'] ?? '',
            'fechaContacto'    => $fila['cseg_fecha_contacto'] ?? '',
            'responsable'      => $fila['responsable_nombre'] ?? '',
            'realizado'        => $realizado,
            'estadoLabel'      => $realizado ? 'Completado' : 'Pendiente',
            'estadoClase'      => $realizado ? 'completado' : 'pendiente',
            'formaContacto'    => $formaContacto[intval($fila['cseg_forma_contacto'] ?? 0)] ?? '—',
            'canal'            => $canalContacto[intval($fila['cseg_canal'] ?? 0)] ?? '—',
            'observacion'      => $fila['cseg_observacion'] ?? '',
            'consiguioDatos'   => intval($fila['cseg_consiguio_datos'] ?? 0) === 1,
            'cotizo'           => intval($fila['cseg_cotizo'] ?? 0) === 1,
            'vendio'           => intval($fila['cseg_vendio'] ?? 0) === 1,
            'demostracion'     => intval($fila['cseg_demostracion'] ?? 0) === 1,
            'visita'           => intval($fila['cseg_visita'] ?? 0) === 1,
            'cotizacion'       => $fila['cseg_cotizacion'] ?? '',
            'archivo'          => trim($fila['cseg_archivo'] ?? ''),
            'archivoUrl'       => trim($fila['cseg_archivo'] ?? '') !== ''
                ? 'files/adjuntos/' . trim($fila['cseg_archivo'])
                : '',
            'fechaProximo'     => $tieneProximo ? $fechaProximo : '',
            'horaProximo'      => $fila['cseg_hora_proximo_contacto'] ?? '',
            'asuntoProximo'    => $fila['cseg_asunto'] ?? '',
            'encargadoProximo' => $fila['encargado_nombre'] ?? '—',
            'canalProximo'     => $canalProximo[intval($fila['cseg_canal_proximo_contacto'] ?? 0)] ?? '—',
            'contacto'         => [
                'nombre'   => $fila['cont_nombre'] ?? '',
                'telefono' => $fila['cont_telefono'] ?? '',
                'email'    => $fila['cont_email'] ?? '',
            ],
            'urls' => [
                'detalle'        => 'clientes-seguimiento-editar.php?id=' . $seguimientoId . '&idTK=' . $ticketId . '&cte=' . $clienteId,
                'editarCompleta' => 'clientes-seguimiento-editar.php?id=' . $seguimientoId . '&idTK=' . $ticketId . '&cte=' . $clienteId,
            ],
            'esComercial'      => $tipoTicketId !== 3,
        ];
    }

    private static function resolverEtapa($etapa, array $opciones): string {
        $idx = intval($etapa);
        if ($idx >= 1 && $idx <= 6 && isset($opciones[$idx])) {
            return $opciones[$idx];
        }
        return '—';
    }

    private static function resolverIndice($valor, array $opciones, int $max): string {
        $idx = intval($valor);
        if ($idx >= 1 && $idx <= $max && isset($opciones[$idx])) {
            return $opciones[$idx];
        }
        return '—';
    }
}
