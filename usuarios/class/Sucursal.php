<?php
require_once RUTA_PROYECTO . '/usuarios/class/BaseDatos.php';

class Sucursal extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'sucursales';
    public static $primaryKey = 'sucu_id';
    public static $tableAs    = 'sucu';

    public static function listarCiudades($conexionBdAdmin): array {
        $ciudades = [];
        $consulta = mysqli_query($conexionBdAdmin, "
            SELECT c.ciu_id, c.ciu_nombre, d.dep_nombre
            FROM localidad_ciudades c
            INNER JOIN localidad_departamentos d ON d.dep_id = c.ciu_departamento
            ORDER BY c.ciu_nombre
        ");

        while ($fila = mysqli_fetch_assoc($consulta)) {
            $ciudades[] = [
                'id'         => intval($fila['ciu_id']),
                'nombre'     => $fila['ciu_nombre'],
                'departamento' => $fila['dep_nombre'],
                'label'      => $fila['ciu_nombre'] . ', ' . $fila['dep_nombre'],
            ];
        }

        return $ciudades;
    }

    public static function clientePerteneceEmpresa(int $clienteId, int $empresaId, $conexionBdPrincipal): bool {
        $consulta = mysqli_query($conexionBdPrincipal, "
            SELECT cli_id
            FROM " . self::$schema . ".clientes
            WHERE cli_id = '" . intval($clienteId) . "'
              AND cli_id_empresa = '" . intval($empresaId) . "'
            LIMIT 1
        ");

        return $consulta && mysqli_num_rows($consulta) > 0;
    }

    public static function obtenerDetalle(int $sucursalId, int $clienteId, int $empresaId, $conexionBdPrincipal): ?array {
        $consulta = mysqli_query($conexionBdPrincipal, "
            SELECT s.*
            FROM " . self::$schema . ".sucursales s
            INNER JOIN " . self::$schema . ".clientes c ON c.cli_id = s.sucu_cliente_principal
            WHERE s.sucu_id = '" . intval($sucursalId) . "'
              AND s.sucu_cliente_principal = '" . intval($clienteId) . "'
              AND c.cli_id_empresa = '" . intval($empresaId) . "'
            LIMIT 1
        ");

        if (!$consulta || mysqli_num_rows($consulta) === 0) {
            return null;
        }

        $fila = mysqli_fetch_assoc($consulta);

        return [
            'id'        => intval($fila['sucu_id']),
            'nombre'    => $fila['sucu_nombre'] ?? '',
            'telefono'  => $fila['sucu_telefono'] ?? '',
            'celular'   => $fila['sucu_celular'] ?? '',
            'telefonos' => $fila['sucu_telefonos'] ?? '',
            'direccion' => $fila['sucu_direccion'] ?? '',
            'ciudad'    => intval($fila['sucu_ciudad'] ?? 0),
        ];
    }

    public static function crear(array $datos, $conexionBdPrincipal): int {
        $clienteId = intval($datos['cliente'] ?? 0);

        mysqli_query($conexionBdPrincipal, "
            INSERT INTO " . self::$schema . ".sucursales (
                sucu_cliente_principal, sucu_ciudad, sucu_direccion, sucu_telefono,
                sucu_celular, sucu_telefonos, sucu_nombre
            ) VALUES (
                '" . $clienteId . "',
                '" . intval($datos['ciudad'] ?? 0) . "',
                '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['direccion'] ?? '') . "',
                '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['telefono'] ?? '') . "',
                '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['celular'] ?? '') . "',
                '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['telefonos'] ?? '') . "',
                '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['nombre'] ?? '') . "'
            )
        ");

        return intval(mysqli_insert_id($conexionBdPrincipal));
    }

    public static function actualizar(int $sucursalId, array $datos, $conexionBdPrincipal): bool {
        return (bool) mysqli_query($conexionBdPrincipal, "
            UPDATE " . self::$schema . ".sucursales SET
                sucu_ciudad = '" . intval($datos['ciudad'] ?? 0) . "',
                sucu_direccion = '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['direccion'] ?? '') . "',
                sucu_telefono = '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['telefono'] ?? '') . "',
                sucu_celular = '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['celular'] ?? '') . "',
                sucu_telefonos = '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['telefonos'] ?? '') . "',
                sucu_nombre = '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['nombre'] ?? '') . "'
            WHERE sucu_id = '" . intval($sucursalId) . "'
        ");
    }
}
