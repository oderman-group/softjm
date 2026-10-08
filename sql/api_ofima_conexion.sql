-- Conexión Ofima (pruebas / producción) para autenticación Bearer
-- Complementa api_configuracion con credenciales y URL base por ambiente

CREATE TABLE IF NOT EXISTS api_ofima_conexion (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
