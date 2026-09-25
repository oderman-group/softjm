-- Estado operativo de productos_categorias.
-- catp_habilitada: 1 habilitada, 0 deshabilitada. Las existentes quedan habilitadas.
-- catp_cod_ofima se retira: el código Ofima queda solo en marcas (mar_cod_ofima).

ALTER TABLE productos_categorias
    ADD COLUMN catp_habilitada TINYINT(1) NOT NULL DEFAULT 1
    COMMENT '1 habilitada, 0 deshabilitada'
    AFTER catp_nombre;

ALTER TABLE productos_categorias
    DROP COLUMN catp_cod_ofima;

ALTER TABLE marcas
    ADD COLUMN mar_cod_ofima VARCHAR(50) NULL DEFAULT NULL
    COMMENT 'Código de homologación en Ofima'
    AFTER mar_nombre;
