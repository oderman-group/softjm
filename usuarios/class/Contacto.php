<?php
require_once RUTA_PROYECTO . '/usuarios/class/BaseDatos.php';

class Contacto extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'contactos';
    public static $primaryKey = 'cont_id';
    public static $tableAs    = 'cont';

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

    public static function listarSucursalesCliente(int $clienteId, $conexionBdPrincipal): array {
        $sucursales = [];
        $consulta = mysqli_query($conexionBdPrincipal, "
            SELECT sucu_id, sucu_nombre
            FROM " . self::$schema . ".sucursales
            WHERE sucu_cliente_principal = '" . intval($clienteId) . "'
            ORDER BY sucu_nombre
        ");

        while ($fila = mysqli_fetch_assoc($consulta)) {
            $sucursales[] = [
                'id'     => intval($fila['sucu_id']),
                'nombre' => $fila['sucu_nombre'] ?? '',
            ];
        }

        return $sucursales;
    }

    public static function obtenerDetalle(int $contactoId, int $clienteId, int $empresaId, $conexionBdPrincipal): ?array {
        $consulta = mysqli_query($conexionBdPrincipal, "
            SELECT c.*
            FROM " . self::$schema . ".contactos c
            INNER JOIN " . self::$schema . ".clientes cli ON cli.cli_id = c.cont_cliente_principal
            WHERE c.cont_id = '" . intval($contactoId) . "'
              AND c.cont_cliente_principal = '" . intval($clienteId) . "'
              AND cli.cli_id_empresa = '" . intval($empresaId) . "'
            LIMIT 1
        ");

        if (!$consulta || mysqli_num_rows($consulta) === 0) {
            return null;
        }

        $fila = mysqli_fetch_assoc($consulta);

        return [
            'id'        => intval($fila['cont_id']),
            'nombre'    => $fila['cont_nombre'] ?? '',
            'email'     => $fila['cont_email'] ?? '',
            'telefono'  => $fila['cont_telefono'] ?? '',
            'celular'   => $fila['cont_celular'] ?? '',
            'telefonos' => $fila['cont_telefonos'] ?? '',
            'area'      => $fila['cont_area'] ?? '',
            'cargo'     => $fila['cont_cargo'] ?? '',
            'sucursal'  => intval($fila['cont_sucursal'] ?? 0),
        ];
    }

    public static function crear(array $datos, $conexionBdPrincipal): int {
        $clienteId = intval($datos['cliente'] ?? 0);

        mysqli_query($conexionBdPrincipal, "
            INSERT INTO " . self::$schema . ".contactos (
                cont_nombre, cont_telefono, cont_email, cont_area, cont_cargo,
                cont_cliente_principal, cont_celular, cont_telefonos, cont_sucursal
            ) VALUES (
                '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['nombre'] ?? '') . "',
                '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['telefono'] ?? '') . "',
                '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['email'] ?? '') . "',
                '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['area'] ?? '') . "',
                '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['cargo'] ?? '') . "',
                '" . $clienteId . "',
                '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['celular'] ?? '') . "',
                '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['telefonos'] ?? '') . "',
                '" . intval($datos['sucursal'] ?? 0) . "'
            )
        ");

        return intval(mysqli_insert_id($conexionBdPrincipal));
    }

    public static function actualizar(int $contactoId, array $datos, $conexionBdPrincipal): bool {
        return (bool) mysqli_query($conexionBdPrincipal, "
            UPDATE " . self::$schema . ".contactos SET
                cont_nombre = '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['nombre'] ?? '') . "',
                cont_telefono = '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['telefono'] ?? '') . "',
                cont_email = '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['email'] ?? '') . "',
                cont_area = '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['area'] ?? '') . "',
                cont_cargo = '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['cargo'] ?? '') . "',
                cont_cliente_principal = '" . intval($datos['cliente'] ?? 0) . "',
                cont_celular = '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['celular'] ?? '') . "',
                cont_telefonos = '" . mysqli_real_escape_string($conexionBdPrincipal, $datos['telefonos'] ?? '') . "',
                cont_sucursal = '" . intval($datos['sucursal'] ?? 0) . "'
            WHERE cont_id = '" . intval($contactoId) . "'
        ");
    }
}
