<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Sucursal extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'sucursales';
    public static $primaryKey = 'sucu_id';
    public static $tableAs    = 'sucu';

}