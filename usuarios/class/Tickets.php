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
    public static function getEstado($idTicket, $conexionBdPrincipal) {

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

}