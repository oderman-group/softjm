<?php

class BaseDatos {

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
                $sql .= "{$campos}='{$valores}',";
            }
        }
        $sql = substr($sql, 0, -1)." WHERE {$infoActualizar['clave_primaria']} = '{$infoActualizar['id_registro']}'";
        $conexionBdPrincipal->query($sql);

        return mysqli_affected_rows($conexionBdPrincipal);
    }

}