<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Factura extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'facturas';
    public static $primaryKey = 'factura_id';
    public static $tableAs    = 'fact';

    public static function trazabilidadFactura(int $idFactura, $conexionBdPrincipal) {
        $consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM ".self::$schema.".".self::$tableName."
        INNER JOIN remisionbdg ON remi_id=factura_remision
        INNER JOIN pedidos ON pedid_id=remi_pedido
        INNER JOIN cotizacion ON cotiz_id=pedid_cotizacion
        INNER JOIN clientes_tikets ON tik_id_cotizacion=cotiz_id
        WHERE ".self::$primaryKey." = " . $idFactura);

        return mysqli_fetch_assoc($consulta);
    }

}