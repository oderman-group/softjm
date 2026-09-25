<?php
/**
 * Catálogo de endpoints Orion → Ofima.
 * Las rutas viven aquí (no en BD). api_configuracion solo registra y habilita/deshabilita.
 */
class OfimaEndpointService
{
    public const DIRECCION = 'orion_ofima';

    /**
     * Catálogo canónico de endpoints Ofima.
     *
     * @return array<string, array{ruta:string,grupo:string,operacion:string,metodo:string,descripcion:string}>
     */
    public static function catalogo(): array
    {
        return [
            'autenticacion' => [
                'ruta' => '/Api/Autenticacion/Validar',
                'grupo' => 'autenticacion',
                'operacion' => 'validar',
                'metodo' => 'POST',
                'descripcion' => 'Validar credenciales y obtener token',
            ],
            'productos' => [
                'ruta' => '/Integracion/Orion/Productos/Crear',
                'grupo' => 'productos',
                'operacion' => 'CREATE',
                'metodo' => 'POST',
                'descripcion' => 'Crear producto',
            ],
            'productos_actualizar' => [
                'ruta' => '/Integracion/Orion/Productos/Actualizar',
                'grupo' => 'productos',
                'operacion' => 'UPDATE',
                'metodo' => 'POST',
                'descripcion' => 'Actualizar producto',
            ],
            'clientes' => [
                'ruta' => '/Integracion/Orion/Clientes/Crear',
                'grupo' => 'clientes',
                'operacion' => 'CREATE',
                'metodo' => 'POST',
                'descripcion' => 'Crear cliente',
            ],
            'clientes_actualizar' => [
                'ruta' => '/Integracion/Orion/Clientes/Actualizar',
                'grupo' => 'clientes',
                'operacion' => 'UPDATE',
                'metodo' => 'POST',
                'descripcion' => 'Actualizar cliente',
            ],
            'pedidos' => [
                'ruta' => '/Integracion/Orion/Pedidos/Crear',
                'grupo' => 'pedidos',
                'operacion' => 'CREATE',
                'metodo' => 'POST',
                'descripcion' => 'Crear pedido',
            ],
            'pedidos_actualizar' => [
                'ruta' => '/Integracion/Orion/Pedidos/Actualizar',
                'grupo' => 'pedidos',
                'operacion' => 'UPDATE',
                'metodo' => 'POST',
                'descripcion' => 'Actualizar pedido',
            ],
        ];
    }

    /**
     * Clave de catálogo según módulo de negocio y tipo de operación.
     */
    public static function claveOperacion(string $modulo, string $tipoOperacion): string
    {
        $modulo = strtolower(trim($modulo));
        if ($modulo === 'autenticacion') {
            return 'autenticacion';
        }
        return strtoupper($tipoOperacion) === 'UPDATE' ? ($modulo . '_actualizar') : $modulo;
    }

    public static function obtener(string $clave): ?array
    {
        $catalogo = self::catalogo();
        return $catalogo[$clave] ?? null;
    }

    public static function ruta(string $clave): ?string
    {
        $item = self::obtener($clave);
        return $item['ruta'] ?? null;
    }

    /**
     * URL absoluta: base ambiente (pruebas/prod) + ruta del servicio.
     */
    public static function urlAbsoluta(string $urlBase, string $clave): ?string
    {
        $ruta = self::ruta($clave);
        if ($ruta === null) {
            return null;
        }
        if (!function_exists('resolverUrlEndpointOfima')) {
            require_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';
        }
        return resolverUrlEndpointOfima($urlBase, $ruta);
    }

    /**
     * Lista para UI: catálogo del servicio + estado/registro de BD (si existe).
     *
     * @return array<int, array>
     */
    public static function listarConEstado(mysqli $conexion, int $idEmpresa): array
    {
        $ofimaActiva = function_exists('ofimaIntegracionActiva')
            ? ofimaIntegracionActiva($conexion, $idEmpresa)
            : false;

        $estados = [];
        $stmt = $conexion->prepare(
            "SELECT apic_modulo, apic_activo, apic_fecha_actualizacion, apic_url_endpoint
             FROM api_configuracion
             WHERE apic_id_empresa = ? AND apic_direccion = ?"
        );
        $direccion = self::DIRECCION;
        $stmt->bind_param('is', $idEmpresa, $direccion);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $estados[$row['apic_modulo']] = $row;
        }

        $lista = [];
        foreach (self::catalogo() as $clave => $meta) {
            $reg = $estados[$clave] ?? null;
            if ($reg !== null) {
                $activo = (int) $reg['apic_activo'] === 1 ? 1 : 0;
            } else {
                $activo = $ofimaActiva ? 1 : 0;
            }
            $lista[] = [
                'apic_modulo' => $clave,
                'apic_direccion' => self::DIRECCION,
                'apic_url_endpoint' => $meta['ruta'],
                'apic_activo' => $activo,
                'apic_fecha_actualizacion' => $reg['apic_fecha_actualizacion'] ?? null,
                'grupo' => $meta['grupo'],
                'operacion' => $meta['operacion'],
                'metodo' => $meta['metodo'],
                'descripcion' => $meta['descripcion'],
                'registrado' => $reg !== null,
            ];
        }

        return $lista;
    }

    /**
     * ¿El endpoint está habilitado?
     * - Sin registro: habilitado si la conexión Ofima está activa.
     * - Con registro: respeta apic_activo.
     */
    public static function estaHabilitado(mysqli $conexion, int $idEmpresa, string $clave): bool
    {
        $stmt = $conexion->prepare(
            "SELECT apic_activo
             FROM api_configuracion
             WHERE apic_id_empresa = ? AND apic_direccion = ? AND apic_modulo = ?
             LIMIT 1"
        );
        $direccion = self::DIRECCION;
        $stmt->bind_param('iss', $idEmpresa, $direccion, $clave);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (!$row) {
            return function_exists('ofimaIntegracionActiva')
                ? ofimaIntegracionActiva($conexion, $idEmpresa)
                : false;
        }
        return (int) $row['apic_activo'] === 1;
    }
}
