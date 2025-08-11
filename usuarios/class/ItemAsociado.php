<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';
/**
 * Esta clase apunta a la tabla donde están relacionados los items 
 * de una cotización, pedido, remisión o factura.
 * 
 * Los items pueden ser productos, combos o servicios.
 */
class ItemAsociado extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'cotizacion_productos';
    public static $primaryKey = 'czpp_id';
    public static $tableAs    = 'czpp';

    public const PROCESO_COTIZACION = 1;
    public const PROCESO_PEDIDO     = 2;
    public const PROCESO_REMSION    = 3;
    public const PROCESO_FACTURA    = 4;

    private $dbConnection; // Propiedad para la conexión a la base de datos

    /**
     * Constructor de la clase.
     *
     * @param mysqli $connection Objeto de conexión a la base de datos.
     */
    public function __construct(mysqli $connection)
    {
        $this->dbConnection = $connection;
    }

    /**
     * Devuelve el listado de combos asociados a un proceso.
     *
     * @param int $idProceso ID del proceso (cotización, pedido, etc.).
     * @param int $tipoProceso Tipo de proceso (usando las constantes de la clase).
     * @return mysqli_result|false Retorna el resultado de la consulta o false en caso de error.
     */
    public function listadoAsociadoCombos(
        int $idProceso, 
        int $tipoProceso = self::PROCESO_COTIZACION
    ) {

        $sql = "
            SELECT
                *
            FROM
                ".self::$tableName." ".self::$tableAs."
            INNER JOIN
                combos cb ON cb.combo_id = ".self::$tableAs.".czpp_combo
            WHERE
                ".self::$tableAs.".czpp_cotizacion = ?
                AND ".self::$tableAs.".czpp_tipo = ?
                AND czpp_combo != ''
            ORDER BY
                ".self::$tableAs.".czpp_orden ASC
        ";

        $stmt = $this->dbConnection->prepare($sql);

        if ($stmt === false) {
            error_log("Error al preparar la consulta SQL en listadoAsociadoCombos: " . $this->dbConnection->error);
            return false;
        }

        $stmt->bind_param(
            "ii",
            $idProceso,
            $tipoProceso
        );

        $executeResult = $stmt->execute();

        if ($executeResult === false) {
            error_log("Error al ejecutar la consulta SQL en listadoAsociadoCombos: " . $stmt->error);
            $stmt->close();
            return false;
        }

        $result = $stmt->get_result();

        $stmt->close();

        return $result; 
    }

    /**
     * Devuelve el listado de productos asociados a un proceso.
     *
     * @param int $idProceso ID del proceso (cotización, pedido, etc.).
     * @param int $tipoProceso Tipo de proceso (usando las constantes de la clase).
     * @return mysqli_result|false Retorna el resultado de la consulta o false en caso de error.
     */
    public function listadoAsociadoProductos(
        int $idProceso, 
        int $tipoProceso = self::PROCESO_COTIZACION
    ) {

        $sql = "
            SELECT
                *
            FROM
                ".self::$tableName." ".self::$tableAs."
            INNER JOIN
                productos prod ON prod.prod_id = ".self::$tableAs.".czpp_producto
            WHERE
                ".self::$tableAs.".czpp_cotizacion = ?
                AND ".self::$tableAs.".czpp_tipo = ?
                AND czpp_producto != ''
            ORDER BY
                ".self::$tableAs.".czpp_orden ASC
        ";

        $stmt = $this->dbConnection->prepare($sql);

        if ($stmt === false) {
            error_log("Error al preparar la consulta SQL en listadoAsociadoProductos: " . $this->dbConnection->error);
            return false;
        }

        $stmt->bind_param(
            "ii",
            $idProceso,
            $tipoProceso
        );

        $executeResult = $stmt->execute();

        if ($executeResult === false) {
            error_log("Error al ejecutar la consulta SQL en listadoAsociadoProductos: " . $stmt->error);
            $stmt->close();
            return false;
        }

        $result = $stmt->get_result();

        $stmt->close();

        return $result; 
    }

    /**
     * Devuelve el listado de servicios asociados a un proceso.
     *
     * @param int $idProceso ID del proceso (cotización, pedido, etc.).
     * @param int $tipoProceso Tipo de proceso (usando las constantes de la clase).
     * @return mysqli_result|false Retorna el resultado de la consulta o false en caso de error.
     */
    public function listadoAsociadoServicios(
        int $idProceso, 
        int $tipoProceso = self::PROCESO_COTIZACION
    ) {

        $sql = "
            SELECT
                *
            FROM
                ".self::$tableName." ".self::$tableAs."
            INNER JOIN
                servicios serv ON serv.serv_id = ".self::$tableAs.".czpp_servicio
            WHERE
                ".self::$tableAs.".czpp_cotizacion = ?
                AND ".self::$tableAs.".czpp_tipo = ?
                AND czpp_servicio != ''
            ORDER BY
                ".self::$tableAs.".czpp_orden ASC
        ";

        $stmt = $this->dbConnection->prepare($sql);

        if ($stmt === false) {
            error_log("Error al preparar la consulta SQL en listadoAsociadoServicios: " . $this->dbConnection->error);
            return false;
        }

        $stmt->bind_param(
            "ii",
            $idProceso,
            $tipoProceso
        );

        $executeResult = $stmt->execute();

        if ($executeResult === false) {
            error_log("Error al ejecutar la consulta SQL en listadoAsociadoServicios: " . $stmt->error);
            $stmt->close();
            return false;
        }

        $result = $stmt->get_result();

        $stmt->close();

        return $result; 
    }

    /**
     * 
     */
    public function listadoAsociadoTodosItems($idProceso, $tipoProceso) {
        $listadoCombos = $this->listadoAsociadoCombos($idProceso, $tipoProceso);
        $listadoProductos = $this->listadoAsociadoProductos($idProceso, $tipoProceso);

        $todosLosItemsParaTabla = [];

        while ($combos = mysqli_fetch_array($listadoCombos, MYSQLI_BOTH)) {

            $itemActual  = [
                'nombre'      => '<a href="combos-editar.php?id='.$combos['czpp_combo'].'" target="_blank" style="color:blue; text-decoration:underline;">'.$combos['combo_nombre'].'</a>',
                'cantidad'    => $combos['czpp_cantidad'],
                'valor'       => $combos['czpp_valor'],
                'descuento'   => $combos['czpp_descuento'],
                'impuesto'    => $combos['czpp_impuesto'],
                'observacion' => $combos['czpp_observacion']
            ];

            $todosLosItemsParaTabla[] = $itemActual;
        }

        $listadoCombos->free();

        while ($producto = mysqli_fetch_array($listadoProductos, MYSQLI_BOTH)) {

            $itemActual  = [
                'nombre'      => '<a href="productos-editar.php?id='.$producto['prod_id'].'" target="_blank" style="color:blue; text-decoration:underline;">'.$producto['prod_nombre'].'</a>',
                'cantidad'    => $producto['czpp_cantidad'],
                'valor'       => $producto['czpp_valor'],
                'descuento'   => $producto['czpp_descuento'],
                'impuesto'    => $producto['czpp_impuesto'],
                'observacion' => $producto['czpp_observacion']
            ];

            $todosLosItemsParaTabla[] = $itemActual;
        }

        $listadoProductos->free();

        return $todosLosItemsParaTabla;
    }

}