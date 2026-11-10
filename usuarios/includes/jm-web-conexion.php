<?php
/**
 * Conexión a la BD de la tienda web JM (gw_*), separada de BDSTORE.
 */

/**
 * Abre (o reutiliza) mysqli a JM Web.
 * Retorna null si faltan constantes o falla la conexión.
 */
function obtenerConexionJmWeb(): ?mysqli
{
    static $conexion = null;
    static $intentoFallido = false;
    static $ultimoError = '';

    if ($conexion instanceof mysqli) {
        return $conexion;
    }
    if ($intentoFallido) {
        return null;
    }

    if (
        !defined('JM_WEB_SERVER')
        || !defined('JM_WEB_USER')
        || !defined('JM_WEB_PASS')
        || !defined('JM_WEB_DB')
    ) {
        $intentoFallido = true;
        $ultimoError = 'Constantes JM_WEB_* no definidas';
        $GLOBALS['jm_web_ultimo_error'] = $ultimoError;
        return null;
    }

    $puerto = defined('JM_WEB_PORT') ? (int) JM_WEB_PORT : 3306;
    $mysqli = mysqli_init();
    if (!$mysqli) {
        $intentoFallido = true;
        $ultimoError = 'No se pudo inicializar mysqli';
        $GLOBALS['jm_web_ultimo_error'] = $ultimoError;
        return null;
    }

    $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
    $ok = @$mysqli->real_connect(
        (string) JM_WEB_SERVER,
        (string) JM_WEB_USER,
        (string) JM_WEB_PASS,
        (string) JM_WEB_DB,
        $puerto
    );

    if (!$ok) {
        $intentoFallido = true;
        $ultimoError = 'Error de conexión JM Web: ' . $mysqli->connect_error;
        $GLOBALS['jm_web_ultimo_error'] = $ultimoError;
        return null;
    }

    $mysqli->set_charset('utf8mb4');
    $conexion = $mysqli;
    $GLOBALS['jm_web_ultimo_error'] = '';
    return $conexion;
}

/**
 * Último error de conexión JM Web (vacío si conectó bien).
 */
function jmWebUltimoErrorConexion(): string
{
    return (string) ($GLOBALS['jm_web_ultimo_error'] ?? '');
}
