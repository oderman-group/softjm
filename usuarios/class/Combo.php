<?php
require_once RUTA_PROYECTO.'/usuarios/class/BaseDatos.php';

/**
 * Clase para manejar las operaciones relacionadas con las cotizaciones.
 *
 * Esta clase extiende BaseDatos para proporcionar métodos específicos
 * para interactuar con la tabla de cotizaciones en la base de datos.
 */
class Combo extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'combos';
    public static $primaryKey = 'combos_id';
    public static $tableAs    = 'combos';


    /**
     * 
     */
    public static function obtenerValorActualCombo(int $comboId, $conexionBdPrincipal) {
        // Es crucial usar sentencias preparadas para seguridad
        $sql = "
            SELECT
                c.combo_id,
                c.combo_nombre,
                (
                    SELECT SUM(cp.copp_precio * cp.copp_cantidad)
                    FROM combos_productos cp
                    WHERE cp.copp_combo = c.combo_id
                ) AS subtotal_productos_sin_descuento,
                c.combo_descuento,
                (
                    (
                        SELECT SUM(cp.copp_precio * cp.copp_cantidad)
                        FROM combos_productos cp
                        WHERE cp.copp_combo = c.combo_id
                    )
                    *
                    (1 - (c.combo_descuento / 100))
                ) AS valor_actual_combo_con_descuento
            FROM
                combos c
            WHERE
                c.combo_id = ?
        ";

        $stmt = $conexionBdPrincipal->prepare($sql);

        if ($stmt === false) {
            error_log("Error al preparar la consulta de valor de combo: " . $conexionBdPrincipal->error);
            return null; // O manejar el error como prefieras
        }

        $stmt->bind_param("i", $comboId); // "i" porque combo_id es un entero

        if (!$stmt->execute()) {
            error_log("Error al ejecutar la consulta de valor de combo: " . $stmt->error);
            $stmt->close();
            return null;
        }

        $result = $stmt->get_result();
        $comboData = $result->fetch_assoc();

        $stmt->close();
        $result->free();

        return $comboData['valor_actual_combo_con_descuento'];
    }

    /**
     * Devuelve el número de cotizaciones en las cuales está incluido el combo
     */
    public static function numeroCotizacionesCombo(int $comboId, $conexionBdPrincipal) {

        $consultaCotizProducto = $conexionBdPrincipal->query("SELECT * FROM cotizacion_productos WHERE czpp_combo='".$comboId."'");
        return $consultaCotizProducto->num_rows;

    }


}