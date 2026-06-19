<?php
/**
 * Helper para generar y validar JWT (HMAC-SHA256).
 * Usado en la API Orion que recibe llamadas de Ofima (productos, clientes, etc.).
 *
 * En producción definir API_JWT_SECRET en sensitive.php (clave larga y aleatoria).
 */
class JwtHelper {

    /** Tiempo de vida del token en segundos (1 hora por defecto) */
    const DEFAULT_TTL = 3600;

    /**
     * Genera un JWT con payload (id_empresa, sub, etc.) y lo firma con el secreto.
     *
     * @param array $payload Datos a incluir (ej. id_empresa, sub=usuario). Se añaden iat y exp.
     * @param int   $ttl     Segundos hasta expiración (default 1 hora).
     * @return string Token JWT
     */
    public static function encode(array $payload, $ttl = self::DEFAULT_TTL) {
        $secret = self::getSecret();
        $now = time();
        $payload['iat'] = $now;
        $payload['exp'] = $now + (int) $ttl;

        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $headerB64 = self::base64UrlEncode(json_encode($header));
        $payloadB64 = self::base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', $headerB64 . '.' . $payloadB64, $secret, true);
        $signatureB64 = self::base64UrlEncode($signature);

        return $headerB64 . '.' . $payloadB64 . '.' . $signatureB64;
    }

    /**
     * Valida el JWT y devuelve el payload si es válido (firma correcta y no expirado).
     *
     * @param string $token Token JWT (con o sin prefijo "Bearer ")
     * @return array|null Payload decodificado o null si es inválido/expirado
     */
    public static function decode($token) {
        if (empty($token)) {
            return null;
        }
        $token = trim($token);
        if (stripos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        $secret = self::getSecret();
        $signature = hash_hmac('sha256', $parts[0] . '.' . $parts[1], $secret, true);
        $signatureB64 = self::base64UrlEncode($signature);
        if (!hash_equals($signatureB64, $parts[2])) {
            return null;
        }

        $payload = json_decode(self::base64UrlDecode($parts[1]), true);
        if (!is_array($payload) || !isset($payload['exp'])) {
            return null;
        }
        if ((int) $payload['exp'] < time()) {
            return null; // Expirado
        }

        return $payload;
    }

    private static function getSecret() {
        if (!defined('API_JWT_SECRET') || API_JWT_SECRET === '') {
            // Fallback solo para desarrollo; en producción definir en sensitive.php
            if (defined('CURRENT_ENVIROMENT') && CURRENT_ENVIROMENT === 'PROD') {
                throw new RuntimeException('API_JWT_SECRET debe estar definido en sensitive.php');
            }
            return 'orion-api-jwt-dev-change-in-production';
        }
        return API_JWT_SECRET;
    }

    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode($data) {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
