<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

class Cliente extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'clientes';
    public static $primaryKey = 'cli_id';
    public static $tableAs    = 'cli';

    /**
     * 
     */
    public static function consultarClientePorId(int $idRegistro, int $idEmpresa) {
        $predicado = [
            'cli_id'         => $idRegistro,
            'cli_id_empresa' => $idEmpresa
        ];

        $campos = 'cli_id, cli_categoria, cli_fecha_ingreso';

        $consulta = self::Select($predicado, $campos);

        return mysqli_fetch_array($consulta, MYSQLI_BOTH);
    }

    /**
     * 
     */
    public static function esCliente(array $datosRegistro) {
        return $datosRegistro['cli_categoria'] != 1 && !empty($datosRegistro['cli_fecha_ingreso']);
    }

    /**
     * 
     */
    public static function convertirACliente(int $idRegistro, int $idEmpresa) {
        $datosRegistro = self::consultarClientePorId($idRegistro, $idEmpresa);
        $esCliente     = self::esCliente($datosRegistro);

        if (!$esCliente) {
            $infoActualizar = [
                'tabla'           => self::$tableName,
                'clave_primaria'  => self::$primaryKey,
                'id_registro'     => $idRegistro,
                'sucp_id_empresa' => $idEmpresa
            ];

            $campos = [
                'cli_categoria'     => 2,
                'cli_nivel'         => 4,
                'cli_fecha_ingreso' => date('Y-m-d')
            ];

            return self::actualizarRegistro($infoActualizar, $campos);
        }
    }

}