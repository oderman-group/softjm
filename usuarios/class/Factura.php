<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Factura extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'facturas';
    public static $primaryKey = 'factura_id';
    public static $tableAs    = 'fact';

}