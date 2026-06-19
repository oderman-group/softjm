<?php
require_once RUTA_PROYECTO . '/usuarios/class/BaseDatos.php';

class ClienteNotaInterna extends BaseDatos {

    public static $schema     = MAINBD;
    public static $tableName  = 'clientes_notas_internas';
    public static $primaryKey = 'clin_id';
    public static $tableAs    = 'clin';

    public const TIPO_TEXTO = 'texto';
    public const TIPO_VOZ   = 'voz';

    public static function asegurarTabla($conexionBdPrincipal) {
        mysqli_query($conexionBdPrincipal, "
            CREATE TABLE IF NOT EXISTS clientes_notas_internas (
                clin_id INT AUTO_INCREMENT PRIMARY KEY,
                clin_cliente INT NOT NULL COMMENT 'FK clientes.cli_id',
                clin_nota TEXT NOT NULL COMMENT 'Contenido de la nota interna',
                clin_tipo VARCHAR(10) NOT NULL DEFAULT 'texto',
                clin_audio_ruta VARCHAR(255) NULL,
                clin_duracion_segundos SMALLINT UNSIGNED NULL,
                clin_usuario INT NOT NULL COMMENT 'Usuario que registró la nota',
                clin_fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                clin_id_empresa INT NOT NULL COMMENT 'Empresa (multi-tenant)',
                INDEX idx_clin_cliente (clin_cliente),
                INDEX idx_clin_empresa (clin_id_empresa),
                INDEX idx_clin_fecha (clin_fecha_registro)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            COMMENT='Histórico de notas internas por cliente'
        ");

        self::asegurarColumnasVoz($conexionBdPrincipal);
    }

    private static function asegurarColumnasVoz($conexionBdPrincipal): void {
        $columnas = [];
        $consulta = mysqli_query($conexionBdPrincipal, 'SHOW COLUMNS FROM clientes_notas_internas');
        if ($consulta) {
            while ($fila = mysqli_fetch_assoc($consulta)) {
                $columnas[$fila['Field']] = true;
            }
        }

        if (!isset($columnas['clin_tipo'])) {
            mysqli_query($conexionBdPrincipal, "
                ALTER TABLE clientes_notas_internas
                ADD COLUMN clin_tipo VARCHAR(10) NOT NULL DEFAULT 'texto' AFTER clin_nota
            ");
        }

        if (!isset($columnas['clin_audio_ruta'])) {
            mysqli_query($conexionBdPrincipal, "
                ALTER TABLE clientes_notas_internas
                ADD COLUMN clin_audio_ruta VARCHAR(255) NULL AFTER clin_tipo
            ");
        }

        if (!isset($columnas['clin_duracion_segundos'])) {
            mysqli_query($conexionBdPrincipal, "
                ALTER TABLE clientes_notas_internas
                ADD COLUMN clin_duracion_segundos SMALLINT UNSIGNED NULL AFTER clin_audio_ruta
            ");
        }
    }

    public static function crear($clienteId, $usuarioId, $empresaId, $nota, $conexionBdPrincipal) {
        return self::crearTexto($clienteId, $usuarioId, $empresaId, $nota, $conexionBdPrincipal);
    }

    public static function crearTexto($clienteId, $usuarioId, $empresaId, $nota, $conexionBdPrincipal) {
        $clienteId = intval($clienteId);
        $usuarioId = intval($usuarioId);
        $empresaId = intval($empresaId);
        $nota      = trim($nota);

        if ($clienteId <= 0 || $usuarioId <= 0 || $nota === '') {
            return false;
        }

        return self::insertarNota(
            $clienteId,
            $usuarioId,
            $empresaId,
            $nota,
            self::TIPO_TEXTO,
            null,
            null,
            $conexionBdPrincipal
        );
    }

    public static function crearVoz(
        $clienteId,
        $usuarioId,
        $empresaId,
        string $rutaRelativa,
        int $duracionSegundos,
        $conexionBdPrincipal
    ) {
        $clienteId = intval($clienteId);
        $usuarioId = intval($usuarioId);
        $empresaId = intval($empresaId);
        $duracionSegundos = max(1, min(60, intval($duracionSegundos)));
        $rutaRelativa = trim(str_replace('\\', '/', $rutaRelativa));

        if ($clienteId <= 0 || $usuarioId <= 0 || $rutaRelativa === '') {
            return false;
        }

        return self::insertarNota(
            $clienteId,
            $usuarioId,
            $empresaId,
            'Nota de voz',
            self::TIPO_VOZ,
            $rutaRelativa,
            $duracionSegundos,
            $conexionBdPrincipal
        );
    }

    private static function insertarNota(
        $clienteId,
        $usuarioId,
        $empresaId,
        string $nota,
        string $tipo,
        ?string $rutaAudio,
        ?int $duracionSegundos,
        $conexionBdPrincipal
    ) {
        try {
            self::asegurarTabla($conexionBdPrincipal);

            $notaEscapada = mysqli_real_escape_string($conexionBdPrincipal, $nota);
            $tipoEscapado = mysqli_real_escape_string($conexionBdPrincipal, $tipo);
            $rutaSql      = $rutaAudio !== null
                ? "'" . mysqli_real_escape_string($conexionBdPrincipal, $rutaAudio) . "'"
                : 'NULL';
            $duracionSql  = $duracionSegundos !== null ? intval($duracionSegundos) : 'NULL';

            $resultado = mysqli_query($conexionBdPrincipal, "
                INSERT INTO clientes_notas_internas (
                    clin_cliente, clin_nota, clin_tipo, clin_audio_ruta, clin_duracion_segundos,
                    clin_usuario, clin_fecha_registro, clin_id_empresa
                ) VALUES (
                    '" . $clienteId . "',
                    '" . $notaEscapada . "',
                    '" . $tipoEscapado . "',
                    " . $rutaSql . ",
                    " . $duracionSql . ",
                    '" . $usuarioId . "',
                    NOW(),
                    '" . $empresaId . "'
                )
            ");

            if (!$resultado) {
                error_log('Error al guardar nota interna: ' . mysqli_error($conexionBdPrincipal));
                return false;
            }

            return mysqli_insert_id($conexionBdPrincipal);
        } catch (Throwable $e) {
            error_log('Error al guardar nota interna: ' . $e->getMessage());
            return false;
        }
    }

    public static function listarPorCliente($clienteId, $empresaId, $conexionBdPrincipal) {
        $clienteId = intval($clienteId);
        $empresaId = intval($empresaId);

        try {
            self::asegurarTabla($conexionBdPrincipal);

            $resultado = mysqli_query($conexionBdPrincipal, "
                SELECT
                    n.clin_id,
                    n.clin_nota,
                    n.clin_tipo,
                    n.clin_audio_ruta,
                    n.clin_duracion_segundos,
                    n.clin_fecha_registro,
                    u.usr_nombre
                FROM clientes_notas_internas n
                INNER JOIN usuarios u ON u.usr_id = n.clin_usuario
                WHERE n.clin_cliente = '" . $clienteId . "'
                  AND n.clin_id_empresa = '" . $empresaId . "'
                ORDER BY n.clin_fecha_registro DESC, n.clin_id DESC
            ");

            $notas = [];
            if ($resultado) {
                while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                    $notas[] = $fila;
                }
            }

            return $notas;
        } catch (Throwable $e) {
            error_log('Error al listar notas internas: ' . $e->getMessage());
            return [];
        }
    }

    private static function extensionPorMime(string $mime, string $nombreArchivo): ?string {
        $mime = strtolower(trim(explode(';', $mime)[0]));
        $mapa = [
            'audio/webm'              => 'webm',
            'audio/ogg'               => 'ogg',
            'audio/mp4'               => 'm4a',
            'audio/x-m4a'             => 'm4a',
            'audio/mpeg'              => 'mp3',
            'video/webm'              => 'webm',
            'application/octet-stream'=> null,
        ];

        if (isset($mapa[$mime]) && $mapa[$mime] !== null) {
            return $mapa[$mime];
        }

        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
        if (in_array($extension, ['webm', 'ogg', 'mp3', 'm4a'], true)) {
            return $extension;
        }

        if ($mime === 'application/octet-stream') {
            return 'webm';
        }

        return null;
    }

    public static function guardarArchivoAudio(array $archivo, int $empresaId, int $clienteId): ?string {
        if (empty($archivo['tmp_name']) || !is_uploaded_file($archivo['tmp_name'])) {
            return null;
        }

        $nombreOriginal = $archivo['name'] ?? 'nota-voz.webm';
        $mime           = $archivo['type'] ?? '';

        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $mimeDetectado = finfo_file($finfo, $archivo['tmp_name']);
                finfo_close($finfo);
                if (!empty($mimeDetectado)) {
                    $mime = $mimeDetectado;
                }
            }
        }

        $extension = self::extensionPorMime($mime, $nombreOriginal);
        if ($extension === null) {
            error_log('Nota de voz rechazada. MIME: ' . $mime . ' Archivo: ' . $nombreOriginal);
            return null;
        }

        if (($archivo['size'] ?? 0) > 5 * 1024 * 1024) {
            return null;
        }

        $directorio = self::obtenerDirectorioAudio($empresaId);
        $nombre     = 'nota_' . intval($clienteId) . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $rutaAbs    = $directorio . '/' . $nombre;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaAbs)) {
            error_log('No se pudo mover audio de nota interna a ' . $rutaAbs);
            return null;
        }

        return 'notas-voz/' . intval($empresaId) . '/' . $nombre;
    }

    public static function formatearNotaParaApi(array $fila): array {
        $tipo = $fila['clin_tipo'] ?? self::TIPO_TEXTO;
        $ruta = trim((string) ($fila['clin_audio_ruta'] ?? ''));

        if ($ruta !== '') {
            $tipo = self::TIPO_VOZ;
        }

        return [
            'id'                => intval($fila['clin_id']),
            'tipo'              => $tipo,
            'nota'              => $fila['clin_nota'] ?? '',
            'usuario'           => strtoupper($fila['usr_nombre'] ?? ''),
            'fecha'             => $fila['clin_fecha_registro'] ?? '',
            'audio_url'         => ($ruta !== '') ? 'files/' . ltrim($ruta, '/') : null,
            'duracion_segundos' => isset($fila['clin_duracion_segundos']) ? intval($fila['clin_duracion_segundos']) : null,
        ];
    }

    public static function obtenerDirectorioAudio(int $empresaId): string {
        $directorio = RUTA_PROYECTO . '/usuarios/files/notas-voz/' . intval($empresaId);
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        return $directorio;
    }

    public static function clientePerteneceEmpresa($clienteId, $empresaId, $conexionBdPrincipal) {
        $consulta = mysqli_query($conexionBdPrincipal, "
            SELECT cli_id
            FROM clientes
            WHERE cli_id = '" . intval($clienteId) . "'
              AND cli_id_empresa = '" . intval($empresaId) . "'
            LIMIT 1
        ");

        return $consulta && mysqli_num_rows($consulta) > 0;
    }
}
