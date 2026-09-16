-- Añadir columna bod_referencia a bodegas para sincronización Ofima → Orion.
-- Ejecutar en la BD principal si vas a usar el endpoint bodegas-recibir.php.
-- Permite identificar bodegas por el código/referencia que envía Ofima.

ALTER TABLE bodegas ADD COLUMN bod_referencia VARCHAR(50) NULL DEFAULT NULL COMMENT 'Código/referencia en Ofima' AFTER bod_id_empresa;
CREATE INDEX idx_bodegas_referencia_empresa ON bodegas (bod_referencia, bod_id_empresa);
