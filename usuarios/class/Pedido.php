<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Pedido extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'pedidos';
    public static $primaryKey = 'pedid_id';
    public static $tableAs    = 'pedid';

    const ESTADO_EN_PREPRACION = 'En prepración';
    const ESTADO_EN_CAMINO     = 'En camino';
    const ESTADO_ENTREGADO     = 'Entregado';
    const ESTADO_PEDIDO = ['DESCONOCIDO', self::ESTADO_EN_PREPRACION, self::ESTADO_EN_CAMINO, self::ESTADO_ENTREGADO];

}