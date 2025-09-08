<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Cliente extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'clientes';
    public static $primaryKey = 'cli_id';
    public static $tableAs    = 'cli'; 

}