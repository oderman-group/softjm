<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Configuracion extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'configuracion';
    public static $primaryKey = 'conf_id';
    public static $tableAs    = 'conf';

}