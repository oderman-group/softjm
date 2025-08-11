<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

/**
 * Clase para manejar las operaciones relacionadas con las cotizaciones.
 *
 * Esta clase extiende BaseDatos para proporcionar métodos específicos
 * para interactuar con la tabla de cotizaciones en la base de datos.
 */
class Cotizacion extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'cotizacion';
    public static $primaryKey = 'cotiz_id';
    public static $tableAs    = 'cotiz';

    public const COTIZACION_VENDIDA = 1;

    /**
     * Verifica si una cotización específica ha sido marcada como vendida.
     * 
     * @param int $idCotizacion El ID de la cotización a verificar.
     * @param int $idEmpresa    El ID de la empresa a la que pertenece la cotización, para seguridad.
     * @return bool             Retorna `true` si la cotización está vendida, de lo contrario `false`.
     */
    public static function esCotizacionVendida(int $idCotizacion, int $idEmpresa): bool {
        $predicado = [
            'cotiz_id'         => $idCotizacion,
            'cotiz_id_empresa' => $idEmpresa
        ];

        $consulta = self::Select($predicado);
        $datos = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $datos['cotiz_vendida'] == self::COTIZACION_VENDIDA;
    }

    /**
     * Obtener la versión actual de la cotización
     */
    public static function obtenerVersionCotizacion(int $version) {
        if ($version > 0) {
            return "<span style='font-size:9px;'>(V ".$version.")</span>";
        }

        return "";
    }

}