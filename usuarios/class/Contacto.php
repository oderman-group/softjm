<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Contacto extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'contactos';
    public static $primaryKey = 'cont_id';
    public static $tableAs    = 'cont';

}