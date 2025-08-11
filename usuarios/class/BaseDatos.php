<?php

class BaseDatos {

    public const OTHER_PREDICATE = 'OTHER_PREDICATE';

    public static $schema;
    public static $tableName;

    public static function eliminarRegistro(array $infoEliminar): string
    {
        global $conexionBdPrincipal;
        try {
            $conexionBdPrincipal->query("DELETE FROM {$infoEliminar['tabla']} WHERE {$infoEliminar['clave_primaria']}='".$infoEliminar['id_registro']."'");
            return mysqli_affected_rows($conexionBdPrincipal);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }

    public static function guardarRegistro($tabla, $post)
    {  
        global $conexionBdPrincipal;

        $sql = "INSERT INTO {$tabla}(";
        foreach ($post as $campos => $valores) {
            $sql .= "{$campos},";
        }
        $sql = substr($sql, 0, -1).")VALUES(";
        foreach ($post as $valores) {
            $sql .= "'{$valores}',";
        }
        $sql = substr($sql, 0, -1).")";

        $conexionBdPrincipal->query($sql);

        return mysqli_affected_rows($conexionBdPrincipal);
    }

    public static function actualizarRegistro(array $infoActualizar, $post)
    {  
        global $conexionBdPrincipal;

        $sql = "UPDATE {$infoActualizar['tabla']} SET ";
        foreach ($post as $campos => $valores) {
            if($campos != 'id'){
                if ($valores === 'INCREMENT_BY_ONE') {
                    $sql .= "{$campos} = {$campos} + 1,";
                } else {
                    $sql .= "{$campos}='{$valores}',";
                }
            }
        }
        $sql = substr($sql, 0, -1)." WHERE {$infoActualizar['clave_primaria']} = '{$infoActualizar['id_registro']}'";
        $conexionBdPrincipal->query($sql);

        return mysqli_affected_rows($conexionBdPrincipal);
    }

    public static function Select(
        array $predicado = [], 
        string $campos = '*', 
        string $sqlfooter ="", 
        string $join = ""
    ) {
        global $conexionBdPrincipal;
        $where = '';

        $campos ??= '*';

        if( !empty($predicado) ) {
            $where = "WHERE ";
            foreach ( $predicado as $clave => $valor ) {
                if ($clave === self::OTHER_PREDICATE) {
                    $where.= " {$valor} AND ";
                } else {
                    $asociacion = explode(" ",$clave);
                    if (empty($asociacion[1])) {
                        $where .= $clave ." = ".self::formatValor($valor)." AND ";
                    } else {
                        $where .= $clave ."  ".$valor." AND ";
                    }
                }
                
            }

            $where = substr($where, 0, -5);
        }

        try {
            $consulta = "SELECT $campos FROM ".static::$schema.".".static::$tableName." ".$join." {$where} ".$sqlfooter;

            $execute = $conexionBdPrincipal->query($consulta);

            if ($execute) {

                return $execute;

            } else {
                throw new Exception("Error al preparar la consulta.");
            }
        } catch (PDOException  $e) {
            echo "Excepción capturada: " . $e->getMessage();
            return null;
        }

    }

    public static function formatValor($valor): string {
        if ( is_numeric($valor) || is_bool($valor)) {
            $result = $valor+0;
        } else{
            $result = "'".$valor."'";
        }

        return $result;
    }

}