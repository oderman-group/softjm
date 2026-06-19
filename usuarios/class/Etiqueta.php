<?php
require_once RUTA_PROYECTO . '/usuarios/class/BaseDatos.php';

class Etiqueta extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'etiquetas';
    public static $primaryKey = 'etiq_id';
    public static $tableAs    = 'etiq';

    public const MODULO_CLIENTE = 'cliente';

    private static function etiquetasDefaultCliente(): array {
        return [
            [
                'nombre' => 'Marketing',
                'slug'   => 'marketing',
                'color'  => '#AD1457',
                'fondo'  => '#FCE4EC',
                'orden'  => 1,
            ],
            [
                'nombre' => 'Prospección',
                'slug'   => 'prospeccion',
                'color'  => '#EF6C00',
                'fondo'  => '#FFF3E0',
                'orden'  => 2,
            ],
            [
                'nombre' => 'Comercial',
                'slug'   => 'comercial',
                'color'  => '#1565C0',
                'fondo'  => '#E3F2FD',
                'orden'  => 3,
            ],
            [
                'nombre' => 'Fidelización',
                'slug'   => 'fidelizacion',
                'color'  => '#2E7D32',
                'fondo'  => '#E8F5E9',
                'orden'  => 4,
            ],
        ];
    }

    public static function asegurarTablas($conexionBdPrincipal): void {
        mysqli_query($conexionBdPrincipal, "
            CREATE TABLE IF NOT EXISTS etiquetas (
                etiq_id INT AUTO_INCREMENT PRIMARY KEY,
                etiq_nombre VARCHAR(100) NOT NULL,
                etiq_slug VARCHAR(100) NOT NULL,
                etiq_color VARCHAR(7) NOT NULL DEFAULT '#1565C0',
                etiq_color_fondo VARCHAR(7) NOT NULL DEFAULT '#E3F2FD',
                etiq_modulo VARCHAR(50) NOT NULL DEFAULT 'cliente',
                etiq_id_empresa INT NOT NULL,
                etiq_activo TINYINT(1) NOT NULL DEFAULT 1,
                etiq_orden INT NOT NULL DEFAULT 0,
                etiq_fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uk_etiq_empresa_modulo_slug (etiq_id_empresa, etiq_modulo, etiq_slug),
                INDEX idx_etiq_modulo_empresa (etiq_modulo, etiq_id_empresa, etiq_activo)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        mysqli_query($conexionBdPrincipal, "
            CREATE TABLE IF NOT EXISTS etiquetas_asignaciones (
                etas_id INT AUTO_INCREMENT PRIMARY KEY,
                etas_etiqueta INT NOT NULL,
                etas_modulo VARCHAR(50) NOT NULL,
                etas_entidad_id INT NOT NULL,
                etas_usuario INT NULL,
                etas_fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                etas_id_empresa INT NOT NULL,
                UNIQUE KEY uk_etas_unica (etas_etiqueta, etas_modulo, etas_entidad_id),
                INDEX idx_etas_entidad (etas_modulo, etas_entidad_id),
                INDEX idx_etas_empresa (etas_id_empresa)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public static function sembrarEtiquetasCliente(int $empresaId, $conexionBdPrincipal): void {
        self::asegurarTablas($conexionBdPrincipal);
        $empresaId = intval($empresaId);

        foreach (self::etiquetasDefaultCliente() as $etiqueta) {
            $slugEscapado = mysqli_real_escape_string($conexionBdPrincipal, $etiqueta['slug']);
            $consulta = mysqli_query($conexionBdPrincipal, "
                SELECT etiq_id
                FROM etiquetas
                WHERE etiq_id_empresa = '" . $empresaId . "'
                  AND etiq_modulo = '" . self::MODULO_CLIENTE . "'
                  AND etiq_slug = '" . $slugEscapado . "'
                LIMIT 1
            ");

            if ($consulta && mysqli_num_rows($consulta) > 0) {
                continue;
            }

            $nombreEscapado = mysqli_real_escape_string($conexionBdPrincipal, $etiqueta['nombre']);
            $colorEscapado  = mysqli_real_escape_string($conexionBdPrincipal, $etiqueta['color']);
            $fondoEscapado  = mysqli_real_escape_string($conexionBdPrincipal, $etiqueta['fondo']);

            mysqli_query($conexionBdPrincipal, "
                INSERT INTO etiquetas (
                    etiq_nombre, etiq_slug, etiq_color, etiq_color_fondo,
                    etiq_modulo, etiq_id_empresa, etiq_activo, etiq_orden
                ) VALUES (
                    '" . $nombreEscapado . "',
                    '" . $slugEscapado . "',
                    '" . $colorEscapado . "',
                    '" . $fondoEscapado . "',
                    '" . self::MODULO_CLIENTE . "',
                    '" . $empresaId . "',
                    1,
                    '" . intval($etiqueta['orden']) . "'
                )
            ");
        }
    }

    public static function listarPorModulo(string $modulo, int $empresaId, $conexionBdPrincipal, bool $soloActivas = true): array {
        if ($modulo === self::MODULO_CLIENTE) {
            self::sembrarEtiquetasCliente($empresaId, $conexionBdPrincipal);
        } else {
            self::asegurarTablas($conexionBdPrincipal);
        }

        $moduloEscapado  = mysqli_real_escape_string($conexionBdPrincipal, $modulo);
        $filtroActivo    = $soloActivas ? ' AND etiq_activo = 1 ' : '';
        $lista           = [];

        $consulta = mysqli_query($conexionBdPrincipal, "
            SELECT etiq_id, etiq_nombre, etiq_slug, etiq_color, etiq_color_fondo, etiq_orden
            FROM etiquetas
            WHERE etiq_modulo = '" . $moduloEscapado . "'
              AND etiq_id_empresa = '" . intval($empresaId) . "'
              {$filtroActivo}
            ORDER BY etiq_orden ASC, etiq_nombre ASC
        ");

        while ($fila = mysqli_fetch_assoc($consulta)) {
            $lista[] = $fila;
        }

        return $lista;
    }

    public static function listarPorEntidad(string $modulo, int $entidadId, int $empresaId, $conexionBdPrincipal): array {
        $mapa = self::listarPorEntidades($modulo, [$entidadId], $empresaId, $conexionBdPrincipal);
        return $mapa[intval($entidadId)] ?? [];
    }

    public static function listarPorEntidades(string $modulo, array $entidadIds, int $empresaId, $conexionBdPrincipal): array {
        self::asegurarTablas($conexionBdPrincipal);

        $entidadIds = array_values(array_filter(array_map('intval', $entidadIds)));
        if (empty($entidadIds)) {
            return [];
        }

        $moduloEscapado = mysqli_real_escape_string($conexionBdPrincipal, $modulo);
        $idsSql         = implode(',', $entidadIds);
        $mapa           = [];

        foreach ($entidadIds as $idEntidad) {
            $mapa[$idEntidad] = [];
        }

        $consulta = mysqli_query($conexionBdPrincipal, "
            SELECT
                a.etas_entidad_id,
                e.etiq_id,
                e.etiq_nombre,
                e.etiq_slug,
                e.etiq_color,
                e.etiq_color_fondo,
                e.etiq_orden
            FROM etiquetas_asignaciones a
            INNER JOIN etiquetas e ON e.etiq_id = a.etas_etiqueta
            WHERE a.etas_modulo = '" . $moduloEscapado . "'
              AND a.etas_entidad_id IN (" . $idsSql . ")
              AND a.etas_id_empresa = '" . intval($empresaId) . "'
              AND e.etiq_activo = 1
            ORDER BY e.etiq_orden ASC, e.etiq_nombre ASC
        ");

        while ($fila = mysqli_fetch_assoc($consulta)) {
            $entidadId = intval($fila['etas_entidad_id']);
            unset($fila['etas_entidad_id']);
            $mapa[$entidadId][] = $fila;
        }

        return $mapa;
    }

    public static function obtenerIdsAsignados(string $modulo, int $entidadId, int $empresaId, $conexionBdPrincipal): array {
        $etiquetas = self::listarPorEntidad($modulo, $entidadId, $empresaId, $conexionBdPrincipal);
        return array_map(static function ($etiqueta) {
            return intval($etiqueta['etiq_id']);
        }, $etiquetas);
    }

    public static function sincronizarAsignaciones(
        string $modulo,
        int $entidadId,
        array $etiquetaIds,
        int $usuarioId,
        int $empresaId,
        $conexionBdPrincipal
    ): void {
        self::asegurarTablas($conexionBdPrincipal);

        $entidadId   = intval($entidadId);
        $usuarioId   = intval($usuarioId);
        $empresaId   = intval($empresaId);
        $moduloEsc   = mysqli_real_escape_string($conexionBdPrincipal, $modulo);
        $etiquetaIds = array_values(array_unique(array_filter(array_map('intval', $etiquetaIds))));

        mysqli_query($conexionBdPrincipal, "
            DELETE FROM etiquetas_asignaciones
            WHERE etas_modulo = '" . $moduloEsc . "'
              AND etas_entidad_id = '" . $entidadId . "'
              AND etas_id_empresa = '" . $empresaId . "'
        ");

        foreach ($etiquetaIds as $etiquetaId) {
            mysqli_query($conexionBdPrincipal, "
                INSERT INTO etiquetas_asignaciones (
                    etas_etiqueta, etas_modulo, etas_entidad_id, etas_usuario, etas_id_empresa
                ) VALUES (
                    '" . $etiquetaId . "',
                    '" . $moduloEsc . "',
                    '" . $entidadId . "',
                    '" . $usuarioId . "',
                    '" . $empresaId . "'
                )
            ");
        }
    }

    public static function renderBadges(array $etiquetas, string $claseExtra = ''): string {
        if (empty($etiquetas)) {
            return '';
        }

        $claseContenedor = trim('crm-etiquetas ' . $claseExtra);
        $html = '<div class="' . htmlspecialchars($claseContenedor) . '">';

        foreach ($etiquetas as $etiqueta) {
            $nombre = htmlspecialchars($etiqueta['etiq_nombre'] ?? '');
            $color  = htmlspecialchars($etiqueta['etiq_color'] ?? '#1565C0');
            $fondo  = htmlspecialchars($etiqueta['etiq_color_fondo'] ?? '#E3F2FD');

            $html .= '<span class="crm-etiqueta" style="color:' . $color . ';background-color:' . $fondo . ';border-color:' . $color . ';">'
                . $nombre
                . '</span>';
        }

        return $html . '</div>';
    }
}
