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

}