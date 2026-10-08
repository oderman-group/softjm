-- Flag: producto ya existe / está integrado en Ofima.
-- Default 0 (false). Se pone en 1 al:
--   - recibir desde Ofima→Orion (productos-recibir CREATE/UPDATE)
--   - crear/actualizar exitosamente en Ofima desde Orion (Productos/Crear o Actualizar)
ALTER TABLE productos
  ADD COLUMN prod_integrado_ofima TINYINT(1) NOT NULL DEFAULT 0
  COMMENT '1 = integrado con Ofima; 0 = solo Orion';
