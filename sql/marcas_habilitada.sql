-- mar_cod_ofima ya existe: código de homologación Ofima.
-- mar_habilitada: 1 habilitada, 0 deshabilitada. Las existentes quedan habilitadas.

ALTER TABLE marcas
    ADD COLUMN mar_habilitada TINYINT(1) NOT NULL DEFAULT 1
    COMMENT '1 habilitada, 0 deshabilitada'
    AFTER mar_cod_ofima;
