-- Flag de intención CRM vs visibilidad CMS en tienda JM.
-- prod_visible_web: controla publicación real en el CMS.
-- prod_visible_web_crm: cómo está marcado Visible web en Orion.

SET @exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'gw_productos'
      AND COLUMN_NAME = 'prod_visible_web_crm'
);
SET @sql := IF(
    @exists = 0,
    'ALTER TABLE gw_productos ADD COLUMN prod_visible_web_crm TINYINT(1) NOT NULL DEFAULT 0 COMMENT ''1=marcado Visible web en Orion CRM'' AFTER prod_visible_web',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SELECT 'prod_visible_web_crm OK' AS estado;
