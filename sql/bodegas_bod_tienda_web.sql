-- Marca si la bodega es tienda web: 1 = sí, 0 = no.
-- Si la columna ya existe, omitir el ALTER.

ALTER TABLE bodegas
    ADD COLUMN bod_tienda_web TINYINT(1) NOT NULL DEFAULT 0
    COMMENT '1 tienda web, 0 no'
    AFTER bod_habilitada;
