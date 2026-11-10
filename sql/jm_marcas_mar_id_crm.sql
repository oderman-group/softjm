-- Relación marcas tienda JM ↔ marcas Orion (mar_id).
-- Ejecutar en jmequipo_jm_dev (JM Web). Idempotente vía procedure dinámico.

SET @exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'marcas'
      AND COLUMN_NAME = 'mar_id_crm'
);
SET @sql := IF(
    @exists = 0,
    'ALTER TABLE marcas ADD COLUMN mar_id_crm INT UNSIGNED NULL DEFAULT NULL COMMENT ''ID marca en Orion CRM'' AFTER mar_icono',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (
    SELECT COUNT(*)
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'marcas'
      AND INDEX_NAME = 'uk_marcas_mar_id_crm'
);
SET @sql := IF(
    @exists = 0,
    'CREATE UNIQUE INDEX uk_marcas_mar_id_crm ON marcas (mar_id_crm)',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SELECT 'jm_marcas_mar_id_crm OK' AS estado;
