<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

/**
 * Clase para manejar las operaciones relacionadas con las cotizaciones.
 *
 * Esta clase extiende BaseDatos para proporcionar métodos específicos
 * para interactuar con la tabla de cotizaciones en la base de datos.
 */
class Cotizacion extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'cotizacion';
    public static $primaryKey = 'cotiz_id';
    public static $tableAs    = 'cotiz';

    public const COTIZACION_VENDIDA = 1;
    public const PRECOTIZACION      = 1;

    /**
     * Verifica si una cotización específica ha sido marcada como vendida.
     * 
     * @param int $idCotizacion El ID de la cotización a verificar.
     * @param int $idEmpresa    El ID de la empresa a la que pertenece la cotización, para seguridad.
     * @return bool             Retorna `true` si la cotización está vendida, de lo contrario `false`.
     */
    public static function esCotizacionVendida(int $idCotizacion, int $idEmpresa): bool {
        $predicado = [
            'cotiz_id'         => $idCotizacion,
            'cotiz_id_empresa' => $idEmpresa
        ];

        $consulta = self::Select($predicado);
        $datos = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $datos['cotiz_vendida'] == self::COTIZACION_VENDIDA;
    }

    /**
     * Obtener la versión actual de la cotización
     */
    public static function obtenerVersionCotizacion(int $version) {
        if ($version > 0) {
            return "<span style='font-size:9px;'>(V ".$version.")</span>";
        }

        return "";
    }

    public static function esPrecotizacion(int $idCotizacion, int $idEmpresa): bool {
        $predicado = [
            'cotiz_id'         => $idCotizacion,
            'cotiz_id_empresa' => $idEmpresa
        ];

        $consulta = self::Select($predicado);
        $datos = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $datos['cotiz_es_precotizacion'] == self::PRECOTIZACION;
    }

    public static function ticketAsociado(int $idTicket, int $idEmpresa): bool {
        $predicado = [
            'cotiz_ticket'     => $idTicket,
            'cotiz_id_empresa' => $idEmpresa
        ];

        $consulta = self::Select($predicado);
        $cantidad = mysqli_num_rows($consulta);

        return $cantidad > 0;
    }

    /**
     * Resumen de cotización para drawer lateral (vista rápida).
     */
    public static function obtenerResumenDrawer(
        int $idCotizacion,
        int $idEmpresa,
        $conexionBdPrincipal
    ): ?array {
        $idCotizacion = intval($idCotizacion);
        $idEmpresa    = intval($idEmpresa);

        if ($idCotizacion <= 0 || $idEmpresa <= 0) {
            return null;
        }

        $consulta = $conexionBdPrincipal->query("
            SELECT c.*,
                   cli.cli_id, cli.cli_nombre, cli.cli_zona,
                   cont.cont_nombre AS contacto_nombre,
                   suc.sucu_nombre AS sucursal_nombre,
                   vend.usr_nombre AS vendedor_nombre,
                   cread.usr_nombre AS creador_nombre
            FROM cotizacion c
            INNER JOIN clientes cli ON cli.cli_id = c.cotiz_cliente
            LEFT JOIN contactos cont ON cont.cont_id = c.cotiz_contacto
            LEFT JOIN sucursales suc ON suc.sucu_id = c.cotiz_sucursal
            LEFT JOIN usuarios vend ON vend.usr_id = c.cotiz_vendedor
            LEFT JOIN usuarios cread ON cread.usr_id = c.cotiz_creador
            WHERE c.cotiz_id = '" . $idCotizacion . "'
              AND c.cotiz_id_empresa = '" . $idEmpresa . "'
            LIMIT 1
        ");

        if (!$consulta || !($fila = mysqli_fetch_assoc($consulta))) {
            return null;
        }

        $items = self::obtenerItemsResumenDrawer($idCotizacion, $conexionBdPrincipal);
        $totales = self::calcularTotalesResumenDrawer($idCotizacion, floatval($fila['cotiz_envio'] ?? 0), $conexionBdPrincipal);

        $pedidoId = null;
        $consultaPedido = $conexionBdPrincipal->query("
            SELECT pedid_id
            FROM pedidos
            WHERE pedid_cotizacion = '" . $idCotizacion . "'
              AND pedid_id_empresa = '" . $idEmpresa . "'
            ORDER BY pedid_id DESC
            LIMIT 1
        ");
        if ($consultaPedido && ($pedido = mysqli_fetch_assoc($consultaPedido))) {
            $pedidoId = intval($pedido['pedid_id']);
        }

        global $simbolosMonedas;
        $monedaId = intval($fila['cotiz_moneda'] ?? 0);
        $simboloMoneda = $simbolosMonedas[$monedaId] ?? '$';

        return [
            'cotizacion' => [
                'id'                  => $idCotizacion,
                'tipo'                => intval($fila['cotiz_es_precotizacion'] ?? 0) === self::PRECOTIZACION ? 'precotizacion' : 'cotizacion',
                'tipoLabel'           => intval($fila['cotiz_es_precotizacion'] ?? 0) === self::PRECOTIZACION ? 'Pre-cotización' : 'Cotización',
                'fechaPropuesta'      => $fila['cotiz_fecha_propuesta'] ?? '',
                'fechaVencimiento'    => $fila['cotiz_fecha_vencimiento'] ?? '',
                'fechaVendida'        => $fila['cotiz_fecha_vendida'] ?? '',
                'vendida'             => intval($fila['cotiz_vendida'] ?? 0) === self::COTIZACION_VENDIDA,
                'vendidaLabel'        => intval($fila['cotiz_vendida'] ?? 0) === self::COTIZACION_VENDIDA ? 'Vendida' : 'No vendida',
                'formaPago'           => $fila['cotiz_forma_pago'] ?? '',
                'moneda'              => $monedaId,
                'monedaSimbolo'       => $simboloMoneda,
                'observaciones'       => trim($fila['cotiz_observaciones'] ?? ''),
                'version'             => intval($fila['cotiz_version'] ?? 0),
                'ticketId'            => !empty($fila['cotiz_ticket']) ? intval($fila['cotiz_ticket']) : null,
                'descuentosEspeciales'=> intval($fila['cotiz_descuentos_especiales'] ?? 0) === 1,
            ],
            'cliente' => [
                'id'     => intval($fila['cli_id']),
                'nombre' => $fila['cli_nombre'] ?? '',
                'zona'   => intval($fila['cli_zona'] ?? 0),
            ],
            'contacto' => [
                'nombre' => $fila['contacto_nombre'] ?? '',
            ],
            'sucursal' => [
                'nombre' => $fila['sucursal_nombre'] ?? '',
            ],
            'vendedor' => [
                'nombre' => $fila['vendedor_nombre'] ?? '',
            ],
            'creador' => [
                'nombre' => $fila['creador_nombre'] ?? '',
            ],
            'items' => $items,
            'totales' => $totales,
            'pedido' => [
                'id' => $pedidoId,
            ],
            'urls' => [
                'paginaCompleta' => 'cotizaciones-editar.php?id=' . $idCotizacion,
                'cliente'        => 'clientes-editar.php?id=' . intval($fila['cli_id']),
                'ticket'         => !empty($fila['cotiz_ticket'])
                    ? 'clientes-tikets-editar.php?id=' . intval($fila['cotiz_ticket']) . '&cte=' . intval($fila['cli_id'])
                    : null,
                'pedido'         => $pedidoId ? 'pedidos-editar.php?id=' . $pedidoId : null,
            ],
        ];
    }

    private static function obtenerItemsResumenDrawer(int $idCotizacion, $conexionBdPrincipal): array {
        $productos = [];
        $combos    = [];
        $servicios = [];

        $tipoCotz = CZPP_TIPO_COTZ;

        $qProductos = $conexionBdPrincipal->query("
            SELECT p.prod_nombre, cp.czpp_cantidad
            FROM cotizacion_productos cp
            INNER JOIN productos p ON p.prod_id = cp.czpp_producto
            WHERE cp.czpp_cotizacion = '" . $idCotizacion . "'
              AND cp.czpp_producto IS NOT NULL
              AND cp.czpp_producto != 0
            ORDER BY cp.czpp_orden
            LIMIT 12
        ");
        while ($qProductos && ($row = mysqli_fetch_assoc($qProductos))) {
            $productos[] = [
                'nombre'   => $row['prod_nombre'] ?? '',
                'cantidad' => intval($row['czpp_cantidad'] ?? 1),
            ];
        }

        $qCombos = $conexionBdPrincipal->query("
            SELECT cb.combo_nombre, cp.czpp_cantidad
            FROM cotizacion_productos cp
            INNER JOIN combos cb ON cb.combo_id = cp.czpp_combo
            WHERE cp.czpp_cotizacion = '" . $idCotizacion . "'
              AND cp.czpp_tipo = '" . $tipoCotz . "'
              AND cp.czpp_combo IS NOT NULL
              AND cp.czpp_combo != 0
            ORDER BY cp.czpp_orden
            LIMIT 8
        ");
        while ($qCombos && ($row = mysqli_fetch_assoc($qCombos))) {
            $combos[] = [
                'nombre'   => $row['combo_nombre'] ?? '',
                'cantidad' => intval($row['czpp_cantidad'] ?? 1),
            ];
        }

        $qServicios = $conexionBdPrincipal->query("
            SELECT s.serv_nombre, cp.czpp_cantidad
            FROM cotizacion_productos cp
            INNER JOIN servicios s ON s.serv_id = cp.czpp_servicio
            WHERE cp.czpp_cotizacion = '" . $idCotizacion . "'
              AND cp.czpp_servicio IS NOT NULL
              AND cp.czpp_servicio != 0
            ORDER BY cp.czpp_orden
            LIMIT 8
        ");
        while ($qServicios && ($row = mysqli_fetch_assoc($qServicios))) {
            $servicios[] = [
                'nombre'   => $row['serv_nombre'] ?? '',
                'cantidad' => intval($row['czpp_cantidad'] ?? 1),
            ];
        }

        return [
            'productos' => $productos,
            'combos'    => $combos,
            'servicios' => $servicios,
            'totalLineas' => count($productos) + count($combos) + count($servicios),
        ];
    }

    private static function calcularTotalesResumenDrawer(
        int $idCotizacion,
        float $envio,
        $conexionBdPrincipal
    ): array {
        $subtotal = 0.0;
        $descuento = 0.0;
        $iva = 0.0;

        $consulta = $conexionBdPrincipal->query("
            SELECT czpp_cantidad, czpp_valor, czpp_descuento, czpp_impuesto
            FROM cotizacion_productos
            WHERE czpp_cotizacion = '" . $idCotizacion . "'
        ");

        while ($consulta && ($row = mysqli_fetch_assoc($consulta))) {
            $cantidad = floatval($row['czpp_cantidad'] ?? 0);
            $valor    = floatval($row['czpp_valor'] ?? 0);
            $descPct  = floatval($row['czpp_descuento'] ?? 0);
            $impPct   = floatval($row['czpp_impuesto'] ?? 0);

            $subtotalRow = $cantidad * $valor;
            $descuentoRow = $subtotalRow * ($descPct / 100);
            $netoRow = $subtotalRow - $descuentoRow;
            $ivaRow = $netoRow * ($impPct / 100);

            $subtotal += $subtotalRow;
            $descuento += $descuentoRow;
            $iva += $ivaRow;
        }

        $total = $subtotal - $descuento + $iva + $envio;

        return [
            'subtotal'  => round($subtotal, 2),
            'descuento' => round($descuento, 2),
            'iva'       => round($iva, 2),
            'envio'     => round($envio, 2),
            'total'     => round($total, 2),
        ];
    }

}