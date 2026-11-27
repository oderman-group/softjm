-- MAIN. Flag Ofima: bloquear clientes no integrados en cotizaciones.
-- Requiere integración activa (aoc_activo=1) + este flag en 1.
-- Default 1 conserva el comportamiento actual (solo clientes en Ofima).
-- Si la columna ya existe, ignore el duplicado; asegurarTablaApiOfimaConexion() también la crea.

ALTER TABLE api_ofima_conexion
    ADD COLUMN aoc_bloquear_clientes_no_ofima TINYINT(1) NOT NULL DEFAULT 1 AFTER aoc_bloquear_pedidos;
