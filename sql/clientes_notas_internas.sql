-- =====================================================
-- Notas internas de clientes (histórico)
-- Cualquier usuario puede registrar notas visibles al consultar el cliente
-- =====================================================

CREATE TABLE IF NOT EXISTS clientes_notas_internas (
    clin_id INT AUTO_INCREMENT PRIMARY KEY,
    clin_cliente INT NOT NULL COMMENT 'FK clientes.cli_id',
    clin_nota TEXT NOT NULL COMMENT 'Contenido de la nota interna',
    clin_usuario INT NOT NULL COMMENT 'Usuario que registró la nota',
    clin_fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    clin_id_empresa INT NOT NULL COMMENT 'Empresa (multi-tenant)',
    INDEX idx_clin_cliente (clin_cliente),
    INDEX idx_clin_empresa (clin_id_empresa),
    INDEX idx_clin_fecha (clin_fecha_registro)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Histórico de notas internas por cliente';
