<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Ticket extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'clientes_tikets';
    public static $primaryKey = 'tik_id';
    public static $tableAs    = 'tik';

    public static $campoEstado = 'tik_estado';

    const ESTADO_ABIERTO = 1;
    const ESTADO_CERRADO = 2;

    /**
     * Este método obtiene los productos que hicieron parte del combo 
     * al convertir una cotización en pedido. Este listado se usa
     * como oficial en todas las operaciones asociadas a este pedido.
     */
    public static function getEstado(int $idTicket, $conexionBdPrincipal) {

        $campo = mysqli_fetch_assoc(
                    mysqli_query($conexionBdPrincipal,"SELECT ".self::$campoEstado." FROM ".self::$schema.".".self::$tableName." 
                    WHERE ".self::$primaryKey." = " . $idTicket)
                );

        return $campo['tik_estado'];
    }

    /**
     * 
     */
    public static function obtenerDatosTikcetPorIdCotizacion(int $idCotizacion, $conexionBdPrincipal) {
        $datos = mysqli_fetch_assoc(
                    mysqli_query($conexionBdPrincipal,"SELECT * FROM ".self::$schema.".".self::$tableName." 
                    WHERE tik_id_cotizacion = " . $idCotizacion)
                );

        return $datos;
    }

    /**
     * Alinea el cliente (y opcionalmente la sucursal) del ticket vinculado a una cotización.
     * Cubre ambos enlaces: tik_id_cotizacion y cotiz_ticket.
     */
    public static function sincronizarClienteDesdeCotizacion(
        int $idCotizacion,
        int $idCliente,
        $conexionBdPrincipal,
        ?int $idSucursal = null
    ): bool {
        $idCotizacion = intval($idCotizacion);
        $idCliente = intval($idCliente);

        if ($idCotizacion <= 0 || $idCliente <= 0) {
            return false;
        }

        $ids = [];

        $porCotizacion = mysqli_query(
            $conexionBdPrincipal,
            "SELECT tik_id FROM " . self::$schema . "." . self::$tableName . "
             WHERE tik_id_cotizacion = " . $idCotizacion
        );
        if ($porCotizacion) {
            while ($row = mysqli_fetch_assoc($porCotizacion)) {
                $ids[] = (int) $row['tik_id'];
            }
        }

        $porFk = mysqli_query(
            $conexionBdPrincipal,
            "SELECT cotiz_ticket FROM " . MAINBD . ".cotizacion
             WHERE cotiz_id = " . $idCotizacion . "
               AND cotiz_ticket IS NOT NULL
               AND cotiz_ticket > 0
             LIMIT 1"
        );
        if ($porFk && ($row = mysqli_fetch_assoc($porFk)) && !empty($row['cotiz_ticket'])) {
            $ids[] = (int) $row['cotiz_ticket'];
        }

        $ids = array_values(array_unique(array_filter($ids)));
        if (empty($ids)) {
            return false;
        }

        $setSucursal = '';
        if ($idSucursal !== null && $idSucursal > 0) {
            $setSucursal = ", tik_sucursal = " . intval($idSucursal);
        }

        return (bool) mysqli_query(
            $conexionBdPrincipal,
            "UPDATE " . self::$schema . "." . self::$tableName . "
             SET tik_cliente = " . $idCliente . $setSucursal . "
             WHERE tik_id IN (" . implode(',', $ids) . ")"
        );
    }

    /**
     * 
     */
    public static function obtenerTotalTicketsComerciales($conexionBdPrincipal) {
        $datos = mysqli_fetch_assoc(
                    mysqli_query($conexionBdPrincipal,"SELECT count(*) as cant 
                    FROM ".self::$schema.".".self::$tableName." ct
                    WHERE 
                        ct.tik_estado = 2 -- Ticket Cerrado
                    AND (ct.tik_etapa = 5 OR  ct.tik_etapa = 6) -- Cerrado y ganado o perdido
                    AND ct.tik_fecha_cierre IS NOT NULL
                    AND ct.tik_fecha_cierre >= ct.tik_fecha_creacion
                    AND ct.tik_tipo_negocio = 1 -- Tipo venta
                    ")
                );

        return $datos['cant'];
    }

    /**
     * 
     */
    public static function obtenerTicketsComercialesEfectivos($conexionBdPrincipal) {
        $datos = mysqli_fetch_assoc(
                    mysqli_query($conexionBdPrincipal,"SELECT count(*) as cant 
                    FROM ".self::$schema.".".self::$tableName." ct
                        INNER JOIN cotizacion 
                        ON cotiz_id = ct.tik_id_cotizacion 
                        AND cotiz_vendida = 1
                    WHERE 
                        ct.tik_estado = 2 -- Ticket Cerrado
                    AND ct.tik_etapa = 5 -- Cerrado y ganado
                    AND ct.tik_fecha_cierre IS NOT NULL
                    AND ct.tik_fecha_cierre >= ct.tik_fecha_creacion
                    AND ct.tik_tipo_negocio = 1 -- Tipo venta
                    AND ct.tik_id_cotizacion IS NOT NULL
                    ")
                );

        return $datos['cant'];
    }

    /**
     * 
     */
    public static function obtenerTicketsComercialesNoEfectivos($conexionBdPrincipal) {
        $datos = mysqli_fetch_assoc(
                    mysqli_query($conexionBdPrincipal,"SELECT count(*) as cant 
                    FROM ".self::$schema.".".self::$tableName." ct
                    WHERE 
                        ct.tik_estado = 2 -- Ticket Cerrado
                    AND ct.tik_etapa = 6 -- Cerrado y perdido
                    AND ct.tik_fecha_cierre IS NOT NULL
                    AND ct.tik_fecha_cierre >= ct.tik_fecha_creacion
                    AND ct.tik_tipo_negocio = 1 -- Tipo venta
                    ")
                );

        return $datos['cant'];
    }

    /**
     * KPIs comerciales en una sola consulta (opcionalmente filtrados por cliente).
     */
    public static function obtenerKpisComercialesResumen($conexionBdPrincipal, ?int $clienteId = null): array {
        $filtroCliente = '';
        if ($clienteId !== null && $clienteId > 0) {
            $filtroCliente = " AND ct.tik_cliente = '" . intval($clienteId) . "'";
        }

        $sql = "SELECT
            SUM(CASE
                WHEN ct.tik_estado = 2
                 AND ct.tik_etapa IN (5, 6)
                 AND ct.tik_fecha_cierre IS NOT NULL
                 AND ct.tik_fecha_cierre >= ct.tik_fecha_creacion
                 AND ct.tik_tipo_negocio = 1
                THEN 1 ELSE 0 END) AS total,
            SUM(CASE
                WHEN ct.tik_estado = 2
                 AND ct.tik_etapa = 5
                 AND ct.tik_fecha_cierre IS NOT NULL
                 AND ct.tik_fecha_cierre >= ct.tik_fecha_creacion
                 AND ct.tik_tipo_negocio = 1
                 AND ct.tik_id_cotizacion IS NOT NULL
                 AND c.cotiz_vendida = 1
                THEN 1 ELSE 0 END) AS efectivos,
            SUM(CASE
                WHEN ct.tik_estado = 2
                 AND ct.tik_etapa = 6
                 AND ct.tik_fecha_cierre IS NOT NULL
                 AND ct.tik_fecha_cierre >= ct.tik_fecha_creacion
                 AND ct.tik_tipo_negocio = 1
                THEN 1 ELSE 0 END) AS no_efectivos
            FROM " . self::$schema . "." . self::$tableName . " ct
            LEFT JOIN " . MAINBD . ".cotizacion c
                ON c.cotiz_id = ct.tik_id_cotizacion
            WHERE 1=1" . $filtroCliente;

        $datos = mysqli_fetch_assoc(mysqli_query($conexionBdPrincipal, $sql)) ?: [];

        return [
            'total'         => intval($datos['total'] ?? 0),
            'efectivos'     => intval($datos['efectivos'] ?? 0),
            'no_efectivos'  => intval($datos['no_efectivos'] ?? 0),
        ];
    }

    /**
     * Construye condiciones WHERE para el listado de tickets.
     */
    public static function construirWhereListado(array $get, $conexionBdPrincipal, bool $excluirCiudad1122): string {
        $where = '';

        if (!empty($get['busqueda'])) {
            $busqueda = mysqli_real_escape_string($conexionBdPrincipal, $get['busqueda']);
            $where .= " AND (ct.tik_id LIKE '%" . $busqueda . "%' OR ct.tik_asunto_principal LIKE '%" . $busqueda . "%')";
        }
        if (!empty($get['cte']) && is_numeric($get['cte'])) {
            $where .= " AND ct.tik_cliente='" . intval($get['cte']) . "'";
        }
        if (!empty($get['resp']) && is_numeric($get['resp'])) {
            $where .= " AND ct.tik_usuario_responsable='" . intval($get['resp']) . "'";
        }
        if (!empty($get['tipo']) && is_numeric($get['tipo'])) {
            $where .= " AND ct.tik_tipo_tiket='" . intval($get['tipo']) . "'";
        }
        if (!empty($get['estado']) && is_numeric($get['estado'])) {
            $where .= " AND ct.tik_estado='" . intval($get['estado']) . "'";
        }
        if (!empty($get['prioridad']) && is_numeric($get['prioridad'])) {
            $where .= " AND ct.tik_prioridad='" . intval($get['prioridad']) . "'";
        }
        if (!empty($get['etapa']) && is_numeric($get['etapa'])) {
            $where .= " AND ct.tik_etapa='" . intval($get['etapa']) . "'";
        }
        if (!empty($get['fecha_inicio'])) {
            $fechaInicio = mysqli_real_escape_string($conexionBdPrincipal, $get['fecha_inicio']);
            $where .= " AND ct.tik_fecha_creacion >= '" . $fechaInicio . " 00:00:00'";
        }
        if (!empty($get['fecha_fin'])) {
            $fechaFin = mysqli_real_escape_string($conexionBdPrincipal, $get['fecha_fin']);
            $where .= " AND ct.tik_fecha_creacion <= '" . $fechaFin . " 23:59:59'";
        }
        if ($excluirCiudad1122) {
            $where .= " AND cli.cli_ciudad != '1122'";
        }

        return $where;
    }

    public static function sqlJoinsListado(): string {
        return "
            INNER JOIN " . MAINBD . ".clientes cli ON cli.cli_id = ct.tik_cliente
            INNER JOIN " . MAINBD . ".usuarios usr ON usr.usr_id = ct.tik_usuario_responsable
            LEFT JOIN " . MAINBD . ".sucursales suc ON suc.sucu_id = ct.tik_sucursal
        ";
    }

    private static function appendFiltrosPermisosWhere(
        string $where,
        int $usuarioId,
        bool $verTodosLosTickets,
        bool $restringirPorZona
    ): string {
        if (!$verTodosLosTickets) {
            $where .= " AND ct.tik_usuario_responsable='" . intval($usuarioId) . "'";
        }
        if ($restringirPorZona) {
            $where .= " AND cli.cli_zona IN (
                SELECT zpu_zona
                FROM " . MAINBD . ".zonas_usuarios
                WHERE zpu_usuario='" . intval($usuarioId) . "'
            )";
        }

        return $where;
    }

    private static function construirWhereAnual(
        int $anio,
        ?int $clienteId,
        bool $excluirCiudad1122
    ): string {
        $anio = intval($anio);
        $where = " AND ct.tik_fecha_creacion >= '" . $anio . "-01-01 00:00:00'";
        $where .= " AND ct.tik_fecha_creacion <= '" . $anio . "-12-31 23:59:59'";

        if ($clienteId !== null && $clienteId > 0) {
            $where .= " AND ct.tik_cliente='" . intval($clienteId) . "'";
        }
        if ($excluirCiudad1122) {
            $where .= " AND cli.cli_ciudad != '1122'";
        }

        return $where;
    }

    /**
     * Estadísticas agregadas del año en curso para gráficos del listado.
     */
    public static function obtenerEstadisticasAnuales(
        $conexionBdPrincipal,
        int $anio,
        int $usuarioId,
        bool $verTodosLosTickets,
        bool $restringirPorZona,
        ?int $clienteId,
        bool $excluirCiudad1122,
        array $opcionesEtapa
    ): array {
        $where = self::construirWhereAnual($anio, $clienteId, $excluirCiudad1122);
        $where = self::appendFiltrosPermisosWhere($where, $usuarioId, $verTodosLosTickets, $restringirPorZona);
        $from = " FROM " . self::$schema . "." . self::$tableName . " ct " . self::sqlJoinsListado() . " WHERE 1=1 " . $where;

        $resumen = mysqli_fetch_assoc(mysqli_query($conexionBdPrincipal, "
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN ct.tik_estado = 1 THEN 1 ELSE 0 END) AS abiertos,
                SUM(CASE WHEN ct.tik_estado = 2 THEN 1 ELSE 0 END) AS cerrados,
                SUM(CASE WHEN ct.tik_id_cotizacion IS NOT NULL AND ct.tik_id_cotizacion != '' AND ct.tik_id_cotizacion != '0' THEN 1 ELSE 0 END) AS con_cotizacion,
                SUM(CASE WHEN ct.tik_etapa = 5 THEN 1 ELSE 0 END) AS ganados,
                SUM(CASE WHEN ct.tik_etapa = 6 THEN 1 ELSE 0 END) AS perdidos,
                SUM(CASE WHEN ct.tik_tipo_tiket = 1 THEN 1 ELSE 0 END) AS comerciales,
                SUM(CASE WHEN ct.tik_tipo_tiket = 3 THEN 1 ELSE 0 END) AS soporte
            " . $from)) ?: [];

        $porMes = array_fill(1, 12, 0);
        $consultaMes = mysqli_query($conexionBdPrincipal, "
            SELECT MONTH(ct.tik_fecha_creacion) AS mes, COUNT(*) AS total
            " . $from . "
            GROUP BY MONTH(ct.tik_fecha_creacion)
        ");
        while ($consultaMes && ($fila = mysqli_fetch_assoc($consultaMes))) {
            $mes = intval($fila['mes']);
            if ($mes >= 1 && $mes <= 12) {
                $porMes[$mes] = intval($fila['total']);
            }
        }

        $porEtapa = [];
        $consultaEtapa = mysqli_query($conexionBdPrincipal, "
            SELECT ct.tik_etapa AS id, COUNT(*) AS total
            " . $from . "
            GROUP BY ct.tik_etapa
            ORDER BY ct.tik_etapa
        ");
        while ($consultaEtapa && ($fila = mysqli_fetch_assoc($consultaEtapa))) {
            $idEtapa = intval($fila['id']);
            $porEtapa[] = [
                'id'    => $idEtapa,
                'label' => $opcionesEtapa[$idEtapa] ?? ('Etapa ' . $idEtapa),
                'total' => intval($fila['total']),
            ];
        }

        $porEstado = [
            ['label' => 'Abiertos', 'total' => intval($resumen['abiertos'] ?? 0)],
            ['label' => 'Cerrados', 'total' => intval($resumen['cerrados'] ?? 0)],
        ];

        $porTipo = [
            ['label' => 'Comercial', 'total' => intval($resumen['comerciales'] ?? 0)],
            ['label' => 'Soporte operativo', 'total' => intval($resumen['soporte'] ?? 0)],
        ];

        $porPrioridad = [];
        $labelsPrioridad = [1 => 'Normal', 2 => 'Urgente', 3 => 'Muy urgente'];
        $consultaPrioridad = mysqli_query($conexionBdPrincipal, "
            SELECT ct.tik_prioridad AS id, COUNT(*) AS total
            " . $from . "
            GROUP BY ct.tik_prioridad
            ORDER BY ct.tik_prioridad
        ");
        while ($consultaPrioridad && ($fila = mysqli_fetch_assoc($consultaPrioridad))) {
            $id = intval($fila['id']);
            $porPrioridad[] = [
                'label' => $labelsPrioridad[$id] ?? ('Prioridad ' . $id),
                'total' => intval($fila['total']),
            ];
        }

        $porResponsable = [];
        $consultaResp = mysqli_query($conexionBdPrincipal, "
            SELECT usr.usr_nombre AS label, COUNT(*) AS total
            " . $from . "
            GROUP BY ct.tik_usuario_responsable, usr.usr_nombre
            ORDER BY total DESC
            LIMIT 8
        ");
        while ($consultaResp && ($fila = mysqli_fetch_assoc($consultaResp))) {
            $porResponsable[] = [
                'label' => $fila['label'] ?? 'Sin responsable',
                'total' => intval($fila['total']),
            ];
        }

        $topClientes = [];
        if ($clienteId === null || $clienteId <= 0) {
            $consultaClientes = mysqli_query($conexionBdPrincipal, "
                SELECT cli.cli_nombre AS label, COUNT(*) AS total
                " . $from . "
                GROUP BY ct.tik_cliente, cli.cli_nombre
                ORDER BY total DESC
                LIMIT 8
            ");
            while ($consultaClientes && ($fila = mysqli_fetch_assoc($consultaClientes))) {
                $topClientes[] = [
                    'label' => $fila['label'] ?? 'Sin nombre',
                    'total' => intval($fila['total']),
                ];
            }
        }

        $total = intval($resumen['total'] ?? 0);
        $cerrados = intval($resumen['cerrados'] ?? 0);
        $ganados = intval($resumen['ganados'] ?? 0);

        return [
            'anio'              => intval($anio),
            'es_vista_cliente'  => $clienteId !== null && $clienteId > 0,
            'resumen'           => [
                'total'          => $total,
                'abiertos'       => intval($resumen['abiertos'] ?? 0),
                'cerrados'       => $cerrados,
                'con_cotizacion' => intval($resumen['con_cotizacion'] ?? 0),
                'ganados'        => $ganados,
                'perdidos'       => intval($resumen['perdidos'] ?? 0),
                'tasa_ganados'   => $cerrados > 0 ? round(($ganados / $cerrados) * 100, 1) : 0,
            ],
            'por_mes'           => array_values($porMes),
            'por_etapa'         => $porEtapa,
            'por_estado'        => $porEstado,
            'por_tipo'          => $porTipo,
            'por_prioridad'     => $porPrioridad,
            'por_responsable'   => $porResponsable,
            'top_clientes'      => $topClientes,
        ];
    }

    public static function sqlConteoListado(
        string $whereExtra,
        int $usuarioId,
        bool $verTodosLosTickets,
        bool $restringirPorZona
    ): string {
        $where = self::appendFiltrosPermisosWhere(
            $whereExtra,
            $usuarioId,
            $verTodosLosTickets,
            $restringirPorZona
        );

        return "SELECT COUNT(*)
            FROM " . self::$schema . "." . self::$tableName . " ct
            " . self::sqlJoinsListado() . "
            WHERE 1=1 " . $where;
    }

    public static function sqlFilasListado(
        string $whereExtra,
        int $usuarioId,
        bool $verTodosLosTickets,
        bool $restringirPorZona,
        int $inicio,
        int $limite
    ): string {
        $where = self::appendFiltrosPermisosWhere(
            $whereExtra,
            $usuarioId,
            $verTodosLosTickets,
            $restringirPorZona
        );

        $inicio = max(0, intval($inicio));
        $limite = max(1, intval($limite));

        return "SELECT
                ct.tik_id,
                ct.tik_tipo_tiket,
                ct.tik_fecha_creacion,
                ct.tik_cliente,
                ct.tik_sucursal,
                ct.tik_asunto_principal,
                ct.tik_usuario_responsable,
                ct.tik_id_cotizacion,
                ct.tik_estado,
                ct.tik_etapa,
                ct.tik_prioridad,
                cli.cli_nombre,
                cli.cli_telefono,
                cli.cli_email,
                cli.cli_zona,
                usr.usr_nombre,
                suc.sucu_nombre,
                (
                    SELECT COUNT(cs.cseg_id)
                    FROM " . MAINBD . ".cliente_seguimiento cs
                    WHERE cs.cseg_tiket = ct.tik_id
                ) AS num_seguimientos
            FROM " . self::$schema . "." . self::$tableName . " ct
            " . self::sqlJoinsListado() . "
            WHERE 1=1 " . $where . "
            ORDER BY ct.tik_id DESC
            LIMIT " . $inicio . ", " . $limite;
    }

}