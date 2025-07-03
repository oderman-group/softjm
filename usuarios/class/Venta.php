<?php

// Definiciones de constantes (asegúrate de que existan)
if (!defined('FACTURA_TIPO_VENTA')) {
    define('FACTURA_TIPO_VENTA', 1);
}
if (!defined('CZPP_TIPO_FACT')) {
    define('CZPP_TIPO_FACT', 4); // Asumo este valor por tu consulta
}

class Venta
{
    private $dbConnection; // Propiedad para la conexión a la base de datos

    // El constructor recibe la conexión para inyectarla
    public function __construct(mysqli $connection)
    {
        $this->dbConnection = $connection;
    }

    /**
     * Obtiene un listado de facturas con sus totales agregados.
     *
     * @param int    $tipoOperacion Tipo de operación de la factura (e.g., venta, compra).
     * @param int    $idEmpresa     ID de la empresa a la que pertenece la factura.
     * @param string $fechaDesde    Fecha de inicio del rango (formato 'YYYY-MM-DD').
     * @param string $fechaHasta    Fecha de fin del rango (formato 'YYYY-MM-DD').
     * @return mysqli_result|false Retorna un array de arrays asociativos con los datos de las facturas, o false en caso de error.
     */
    public function listadoFacturas(
        int $tipoOperacion = FACTURA_TIPO_VENTA,
        int $idEmpresa = 1,
        string $fechaDesde = '2025-01-01',
        string $fechaHasta = '2025-06-30'
    ): mysqli_result|false {

        // Validación básica de fechas
        if (!strtotime($fechaDesde) || !strtotime($fechaHasta)) {
            error_log("Error: Formato de fecha inválido en listadoFacturas.");
            return false;
        }

        $sql = "
            SELECT
                fac.factura_id AS id,
                fac.factura_fecha_creacion AS fecha,
                us.usr_nombre AS vendedor,
                sp.sucp_nombre AS sucursal,
                cli.cli_nombre AS cliente,
                ROUND(SUM(cp.czpp_cantidad * cp.czpp_valor), 2) AS total
            FROM
                facturas fac
            INNER JOIN
                cotizacion_productos cp ON cp.czpp_cotizacion = fac.factura_id
                AND cp.czpp_tipo = ?
            INNER JOIN
                clientes cli ON cli.cli_id = fac.factura_cliente
            INNER JOIN
                usuarios us ON us.usr_id = fac.factura_vendedor
            INNER JOIN
                sucursales_propias sp ON sp.sucp_id = us.usr_sucursal
            WHERE
                fac.factura_tipo = ?
                AND fac.factura_id_empresa = ?
                AND fac.factura_fecha_creacion BETWEEN ? AND ?
            GROUP BY
                fac.factura_id,
                fac.factura_fecha_creacion,
                us.usr_nombre,
                sp.sucp_nombre,
                cli.cli_nombre
            ORDER BY
                fac.factura_id DESC
        ";

        $stmt = $this->dbConnection->prepare($sql);

        if ($stmt === false) {
            error_log("Error al preparar la consulta SQL en listadoFacturas: " . $this->dbConnection->error);
            return false;
        }

        $stmt->bind_param(
            "iiiss",
            CZPP_TIPO_FACT,
            $tipoOperacion,
            $idEmpresa,
            $fechaDesde,
            $fechaHasta
        );

        $executeResult = $stmt->execute();

        if ($executeResult === false) {
            error_log("Error al ejecutar la consulta SQL en listadoFacturas: " . $stmt->error);
            $stmt->close();
            return false;
        }

        $result = $stmt->get_result();

        $stmt->close();

        return $result; 
    }

    /**
     * Obtiene el número total de facturas para los criterios dados.
     *
     * @param int    $tipoOperacion Tipo de operación de la factura.
     * @param int    $idEmpresa     ID de la empresa.
     * @param string $fechaDesde    Fecha de inicio del rango (formato 'YYYY-MM-DD').
     * @param string $fechaHasta    Fecha de fin del rango (formato 'YYYY-MM-DD').
     * @return int|false Retorna el número de facturas, o false en caso de error.
     */
    public function numeroFacturas(
        int $tipoOperacion = FACTURA_TIPO_VENTA,
        int $idEmpresa = 1,
        string $fechaDesde = '2025-01-01',
        string $fechaHasta = '2025-06-30'
    ): int|false {

        // Validación básica de fechas
        if (!strtotime($fechaDesde) || !strtotime($fechaHasta)) {
            error_log("Error: Formato de fecha inválido en numeroFacturas.");
            return false;
        }

        $sql = "
            SELECT
                COUNT(DISTINCT fac.factura_id) AS total_facturas
            FROM
                facturas fac
            INNER JOIN
                cotizacion_productos cp ON cp.czpp_cotizacion = fac.factura_id
                AND cp.czpp_tipo = ?
            WHERE
                fac.factura_tipo = ?
                AND fac.factura_id_empresa = ?
                AND fac.factura_fecha_creacion BETWEEN ? AND ?
        ";

        $stmt = $this->dbConnection->prepare($sql);

        if ($stmt === false) {
            error_log("Error al preparar la consulta SQL en numeroFacturas: " . $this->dbConnection->error);
            return false;
        }

        $stmt->bind_param(
            "iiiss",
            CZPP_TIPO_FACT,
            $tipoOperacion,
            $idEmpresa,
            $fechaDesde,
            $fechaHasta
        );

        $executeResult = $stmt->execute();

        if ($executeResult === false) {
            error_log("Error al ejecutar la consulta SQL en numeroFacturas: " . $stmt->error);
            $stmt->close();
            return false;
        }

        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $result->free();
            return (int) $row['total_facturas'];
        }

        $result->free();
        return 0;
    }

}