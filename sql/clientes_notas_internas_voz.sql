-- Notas de voz en clientes_notas_internas (máx. 1 minuto en aplicación)
ALTER TABLE clientes_notas_internas
    ADD COLUMN clin_tipo VARCHAR(10) NOT NULL DEFAULT 'texto' COMMENT 'texto|voz' AFTER clin_nota,
    ADD COLUMN clin_audio_ruta VARCHAR(255) NULL COMMENT 'Ruta relativa bajo usuarios/files/' AFTER clin_tipo,
    ADD COLUMN clin_duracion_segundos SMALLINT UNSIGNED NULL AFTER clin_audio_ruta;
