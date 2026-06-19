-- =====================================================
-- Sistema de etiquetas reutilizable (multi-módulo)
-- Prioridad inicial: clientes
-- =====================================================

CREATE TABLE IF NOT EXISTS etiquetas (
    etiq_id INT AUTO_INCREMENT PRIMARY KEY,
    etiq_nombre VARCHAR(100) NOT NULL COMMENT 'Nombre visible',
    etiq_slug VARCHAR(100) NOT NULL COMMENT 'Identificador interno único por módulo/empresa',
    etiq_color VARCHAR(7) NOT NULL DEFAULT '#1565C0' COMMENT 'Color texto (hex)',
    etiq_color_fondo VARCHAR(7) NOT NULL DEFAULT '#E3F2FD' COMMENT 'Color fondo (hex)',
    etiq_modulo VARCHAR(50) NOT NULL DEFAULT 'cliente' COMMENT 'cliente, ticket, cotizacion, etc.',
    etiq_id_empresa INT NOT NULL,
    etiq_activo TINYINT(1) NOT NULL DEFAULT 1,
    etiq_orden INT NOT NULL DEFAULT 0,
    etiq_fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_etiq_empresa_modulo_slug (etiq_id_empresa, etiq_modulo, etiq_slug),
    INDEX idx_etiq_modulo_empresa (etiq_modulo, etiq_id_empresa, etiq_activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Catálogo de etiquetas reutilizable por módulo';

CREATE TABLE IF NOT EXISTS etiquetas_asignaciones (
    etas_id INT AUTO_INCREMENT PRIMARY KEY,
    etas_etiqueta INT NOT NULL COMMENT 'FK etiquetas.etiq_id',
    etas_modulo VARCHAR(50) NOT NULL COMMENT 'Debe coincidir con etiquetas.etiq_modulo',
    etas_entidad_id INT NOT NULL COMMENT 'ID del registro etiquetado (ej. cli_id)',
    etas_usuario INT NULL COMMENT 'Usuario que asignó',
    etas_fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    etas_id_empresa INT NOT NULL,
    UNIQUE KEY uk_etas_unica (etas_etiqueta, etas_modulo, etas_entidad_id),
    INDEX idx_etas_entidad (etas_modulo, etas_entidad_id),
    INDEX idx_etas_empresa (etas_id_empresa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Asignación de etiquetas a registros de cualquier módulo';
