-- Estado operativo de la bodega: 1 = habilitada (en funcionamiento), 0 = deshabilitada.
-- Si la columna ya existe, omitir el ALTER y ejecutar solo los UPDATE.

ALTER TABLE bodegas
    ADD COLUMN bod_habilitada TINYINT(1) NOT NULL DEFAULT 0
    COMMENT '1 habilitada, 0 deshabilitada'
    AFTER bod_ciudad;

-- El resto queda deshabilitado. Solo estas referencias de Ofima quedan activas.
UPDATE bodegas
SET bod_habilitada = 0;

UPDATE bodegas
SET bod_habilitada = 1
WHERE bod_referencia IN (
    '0',
    'APARTADO',
    'BOGOTA',
    'MEDELLIN',
    'REPUESTOBTA',
    'REPUESTOMED',
    'RESPUESTOAPA',
    'TRANSITO'
);
