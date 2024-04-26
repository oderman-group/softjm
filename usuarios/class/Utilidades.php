<?php
class Utilidades {

    /**
     * Generates a unique code based on a given index and a combination of numbers and the current timestamp.
     *
     * @param string $index An optional index to prepend to the generated code.
     * @return string The generated unique code.
     */
    public static function generateCode($index='')
    {
        return !empty($index) ? uniqid($index.'-') : uniqid();
        //return $index."-".self::guidv4();
    }

    /**
     * Obtiene el próximo valor AUTO_INCREMENT de una tabla específica en una base de datos.
     *
     * Este método consulta la tabla `information_schema.tables` para obtener el próximo
     * valor AUTO_INCREMENT de la tabla especificada en la base de datos dada. Es útil para
     * prever el próximo ID que se generará al insertar un nuevo registro en una tabla que
     * utiliza AUTO_INCREMENT.
     *
     * @param mysqli $conexion Objeto de conexión a la base de datos MySQLi.
     * @param string $bd Nombre de la base de datos donde se encuentra la tabla.
     * @param string $table Nombre de la tabla para la cual se desea obtener el próximo AUTO_INCREMENT.
     * @return int|null El próximo valor AUTO_INCREMENT de la tabla, o null si no se encuentra o hay un error.
     */
    public static function getNextIdSequence($conexion, $bd, $table) {
        $consecutivo=$conexion->query("SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_schema = '{$bd}' AND table_name = '{$table}'");
        $resultado = mysqli_fetch_array($consecutivo, MYSQLI_BOTH);

        return $resultado['AUTO_INCREMENT'];
    }

    /**
     * Obtiene un prefijo basado en el nombre de una tabla.
     *
     * Este método analiza el nombre de una tabla proporcionada como argumento para extraer
     * un prefijo. El prefijo se deriva de la primera parte del nombre de la tabla (asumiendo
     * que el nombre de la tabla sigue un formato que incluye al menos un guion bajo "_"). Si
     * el nombre de la tabla contiene guiones bajos, el método devuelve las primeras tres letras
     * de la segunda palabra en el nombre de la tabla. Si el nombre de la tabla no contiene
     * guiones bajos, simplemente devuelve las primeras tres letras del nombre de la tabla.
     * El prefijo se devuelve en mayúsculas.
     *
     * @param string $table El nombre de la tabla de la cual se desea obtener el prefijo.
     * @return string|null El prefijo derivado del nombre de la tabla en mayúsculas, o null si el nombre de la tabla está vacío.
     */
    public static function getPrefixFromTableName($table) {

        if (empty($table)) {
            return null;
        }
    
        // Divide el nombre de la tabla en partes basado en "_" y toma la palabra relevante.
        $parts = explode("_", $table);
        $word  = count($parts) > 1 ? $parts[1] : $parts[0];
    
        // Retorna las primeras tres letras de la palabra seleccionada en mayúsculas.
        return strtoupper(substr($word, 0, 3));

    }
}