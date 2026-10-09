-- Bloquear movimientos de pedidos en Orion cuando Ofima está activa.
-- Requiere: integración activa (aoc_activo=1) + este flag en 1.
-- Si la columna ya existe, ignore el error duplicado o use el auto-ALTER de asegurarTablaApiOfimaConexion().

ALTER TABLE api_ofima_conexion
    ADD COLUMN aoc_bloquear_pedidos TINYINT(1) NOT NULL DEFAULT 0 AFTER aoc_activo;
