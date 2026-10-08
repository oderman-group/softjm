<?php
/**
 * Crea la tabla api_ofima_conexion si no existe.
 * Guarda credenciales y URLs de pruebas/producción para autenticación Bearer con Ofima.
 */
function asegurarTablaApiOfimaConexion(mysqli $conexion): void
{
    $sql = "CREATE TABLE IF NOT EXISTS api_ofima_conexion (
        aoc_id INT(11) NOT NULL AUTO_INCREMENT,
        aoc_id_empresa INT(11) NOT NULL,
        aoc_ambiente ENUM('pruebas','produccion') NOT NULL DEFAULT 'pruebas',
        aoc_url_pruebas VARCHAR(500) NOT NULL DEFAULT 'http://20.119.237.168:34593',
        aoc_url_produccion VARCHAR(500) NOT NULL DEFAULT '',
        aoc_usuario_pruebas VARCHAR(100) NOT NULL DEFAULT '',
        aoc_clave_pruebas VARCHAR(255) NOT NULL DEFAULT '',
        aoc_usuario_produccion VARCHAR(100) NOT NULL DEFAULT '',
        aoc_clave_produccion VARCHAR(255) NOT NULL DEFAULT '',
        aoc_token TEXT NULL,
        aoc_token_expira DATETIME NULL,
        aoc_activo TINYINT(1) NOT NULL DEFAULT 1,
        aoc_fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        aoc_fecha_actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (aoc_id),
        UNIQUE KEY uk_aoc_empresa (aoc_id_empresa)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $conexion->query($sql);
}

/**
 * Indica si la integración Ofima está activa para la empresa (api_ofima_conexion.aoc_activo).
 */
function ofimaIntegracionActiva(mysqli $conexion, int $idEmpresa): bool
{
    if ($idEmpresa <= 0) {
        return false;
    }

    $config = obtenerApiOfimaConexion($conexion, $idEmpresa);
    if (!$config) {
        return false;
    }

    return (int) ($config['aoc_activo'] ?? 0) === 1;
}

/**
 * Obtiene la conexión Ofima de la empresa (o null).
 */
function obtenerApiOfimaConexion(mysqli $conexion, int $idEmpresa): ?array
{
    asegurarTablaApiOfimaConexion($conexion);

    $stmt = $conexion->prepare('SELECT * FROM api_ofima_conexion WHERE aoc_id_empresa = ? LIMIT 1');
    $stmt->bind_param('i', $idEmpresa);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}

/**
 * Credenciales y URL activa según el ambiente seleccionado.
 */
function resolverCredencialesOfima(array $config): array
{
    $esProduccion = ($config['aoc_ambiente'] ?? 'pruebas') === 'produccion';

    if ($esProduccion) {
        return [
            'ambiente' => 'produccion',
            'url_base' => rtrim((string) ($config['aoc_url_produccion'] ?? ''), '/'),
            'usuario'  => (string) ($config['aoc_usuario_produccion'] ?? ''),
            'clave'    => base64_decode((string) ($config['aoc_clave_produccion'] ?? '')),
        ];
    }

    return [
        'ambiente' => 'pruebas',
        'url_base' => rtrim((string) ($config['aoc_url_pruebas'] ?? ''), '/'),
        'usuario'  => (string) ($config['aoc_usuario_pruebas'] ?? ''),
        'clave'    => base64_decode((string) ($config['aoc_clave_pruebas'] ?? '')),
    ];
}

/**
 * Solicita token a Ofima: POST {base}/Api/Autenticacion/Validar
 */
function solicitarTokenOfima(string $urlBase, string $usuario, string $clave): array
{
    $urlBase = rtrim($urlBase, '/');
    if ($urlBase === '' || $usuario === '' || $clave === '') {
        return [
            'success' => false,
            'error' => 'URL base, usuario y clave son obligatorios',
            'codigo_http' => 0,
        ];
    }

    $url = $urlBase . '/Api/Autenticacion/Validar';
    $payload = json_encode(['usuario' => $usuario, 'clave' => $clave], JSON_UNESCAPED_UNICODE);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
        ],
    ]);

    $respuesta = curl_exec($ch);
    $codigoHttp = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorCurl = curl_error($ch);
    curl_close($ch);

    if ($errorCurl) {
        return [
            'success' => false,
            'error' => 'Error de conexión: ' . $errorCurl,
            'codigo_http' => 0,
            'url' => $url,
        ];
    }

    $datos = json_decode((string) $respuesta, true);
    $token = null;

    if (is_array($datos)) {
        $token = $datos['token']
            ?? $datos['Token']
            ?? $datos['access_token']
            ?? $datos['accessToken']
            ?? $datos['data']['token']
            ?? null;
    } elseif (is_string($datos) && $datos !== '') {
        $token = $datos;
    } elseif (is_string($respuesta) && $respuesta !== '' && strpos(ltrim($respuesta), '{') !== 0) {
        $token = trim($respuesta, " \t\n\r\0\x0B\"'");
    }

    $ok = ($codigoHttp >= 200 && $codigoHttp < 300 && !empty($token));

    return [
        'success' => $ok,
        'token' => $token,
        'codigo_http' => $codigoHttp,
        'datos' => $datos,
        'respuesta_raw' => $respuesta,
        'url' => $url,
        'error' => $ok ? null : ('No se pudo obtener el token. HTTP ' . $codigoHttp),
    ];
}

/**
 * Une URL base Ofima + ruta relativa del endpoint.
 * Si el endpoint ya es URL absoluta (legado), se retorna tal cual.
 */
function resolverUrlEndpointOfima(string $urlBase, string $endpoint): string
{
    $endpoint = trim($endpoint);
    if ($endpoint === '') {
        return rtrim($urlBase, '/');
    }
    if (preg_match('#^https?://#i', $endpoint)) {
        return $endpoint;
    }
    return rtrim($urlBase, '/') . '/' . ltrim($endpoint, '/');
}

/**
 * Extrae la ruta relativa de una URL absoluta Orion→Ofima (o deja la ruta si ya es relativa).
 */
function normalizarRutaEndpointOrionOfima(string $endpoint): string
{
    $endpoint = trim($endpoint);
    if ($endpoint === '') {
        return '';
    }
    if (!preg_match('#^https?://#i', $endpoint)) {
        return '/' . ltrim($endpoint, '/');
    }
    $parts = parse_url($endpoint);
    $path = $parts['path'] ?? '';
    if ($path === '') {
        return '';
    }
    return '/' . ltrim($path, '/');
}

/**
 * Actualiza el registro de endpoints orion_ofima en api_configuracion
 * a partir del catálogo del OfimaEndpointService (solo rutas relativas + habilitación).
 */
function sincronizarEndpointsApiConfiguracion(mysqli $conexion, int $idEmpresa, string $urlBase, string $usuario, string $clavePlano, int $activo): void
{
    if (!class_exists('OfimaEndpointService')) {
        require_once RUTA_PROYECTO . '/usuarios/class/OfimaEndpointService.php';
    }

    $password = base64_encode($clavePlano);
    $catalogo = OfimaEndpointService::catalogo();

    foreach ($catalogo as $modulo => $meta) {
        $rutaEndpoint = $meta['ruta'];
        $stmt = $conexion->prepare(
            "SELECT apic_id FROM api_configuracion
             WHERE apic_modulo = ? AND apic_direccion = 'orion_ofima' AND apic_id_empresa = ?
             LIMIT 1"
        );
        $stmt->bind_param('si', $modulo, $idEmpresa);
        $stmt->execute();
        $existente = $stmt->get_result()->fetch_assoc();

        if ($existente) {
            $upd = $conexion->prepare(
                "UPDATE api_configuracion
                 SET apic_url_endpoint = ?, apic_usuario = ?, apic_password = ?, apic_activo = ?
                 WHERE apic_id = ?"
            );
            $apicId = (int) $existente['apic_id'];
            $upd->bind_param('sssii', $rutaEndpoint, $usuario, $password, $activo, $apicId);
            $upd->execute();
        } else {
            $ins = $conexion->prepare(
                "INSERT INTO api_configuracion
                 (apic_modulo, apic_direccion, apic_url_endpoint, apic_usuario, apic_password, apic_activo, apic_id_empresa)
                 VALUES (?, 'orion_ofima', ?, ?, ?, ?, ?)"
            );
            $ins->bind_param('ssssii', $modulo, $rutaEndpoint, $usuario, $password, $activo, $idEmpresa);
            $ins->execute();
        }
    }
}
