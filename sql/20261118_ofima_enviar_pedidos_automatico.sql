-- BD: MAIN
-- Depende de: api_ofima_conexion (columna aoc_bloquear_pedidos).
-- Flag de control para enviar pedidos a Ofima de forma automática.
-- Solo aplica cuando aoc_bloquear_pedidos = 0. El envío todavía no está conectado.

ALTER TABLE api_ofima_conexion
    ADD COLUMN aoc_enviar_pedidos_auto TINYINT(1) NOT NULL DEFAULT 0 AFTER aoc_bloquear_pedidos;
