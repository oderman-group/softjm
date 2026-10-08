-- Flag: cliente ya existe / está integrado en Ofima.
-- Default 0 (false). Se pone en 1 al recibir desde Ofima→Orion o al crear exitosamente en Ofima desde Orion.
ALTER TABLE clientes
  ADD COLUMN cli_integrado_ofima TINYINT(1) NOT NULL DEFAULT 0
  COMMENT '1 = integrado/creado en Ofima; 0 = solo Orion'
  AFTER cli_forma_creacion;
