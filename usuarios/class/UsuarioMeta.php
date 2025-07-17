<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class UsuarioMeta extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'usuarios_metas';
    public static $primaryKey = 'um_id';
    public static $tableAs    = 'um';

}