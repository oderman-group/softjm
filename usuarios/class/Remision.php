<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Remision extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'remisionbdg';
    public static $primaryKey = 'remi_id';
    public static $tableAs    = 'remi';

}