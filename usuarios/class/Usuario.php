<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Usuario extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'usuarios';
    public static $primaryKey = 'usr_id';
    public static $tableAs    = 'usr';

}