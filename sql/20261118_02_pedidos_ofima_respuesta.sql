-- BD: MAIN
-- Depende de: tabla pedidos.
-- Última respuesta y estado del envío del pedido a Ofima.

ALTER TABLE pedidos
    ADD COLUMN pedid_ofima_estado TINYINT NOT NULL DEFAULT 0 COMMENT '0 pendiente, 1 exitoso, 2 error' AFTER pedid_estado;

ALTER TABLE pedidos
    ADD COLUMN pedid_ofima_respuesta TEXT NULL COMMENT 'Última respuesta o error del envío a Ofima' AFTER pedid_ofima_estado;

ALTER TABLE pedidos
    ADD COLUMN pedid_ofima_integrado TINYINT NOT NULL DEFAULT 0 COMMENT '1 si Crear o Actualizar en Ofima fue exitoso alguna vez' AFTER pedid_ofima_respuesta;
