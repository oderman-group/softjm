-- =============================================================================
-- Corrección: tickets con cliente distinto al de su cotización vinculada
-- Causa: al cambiar cotiz_cliente no se actualizaba tik_cliente
-- Criterio: prevalece el cliente actual de la cotización (cotiz_cliente)
-- =============================================================================

-- 1) Diagnóstico (antes)
SELECT
    c.cotiz_id,
    t.tik_id,
    c.cotiz_cliente,
    cli_c.cli_nombre AS cliente_cotizacion,
    t.tik_cliente,
    cli_t.cli_nombre AS cliente_ticket,
    c.cotiz_sucursal,
    t.tik_sucursal,
    t.tik_asunto_principal
FROM cotizacion c
INNER JOIN clientes_tikets t ON t.tik_id_cotizacion = c.cotiz_id
INNER JOIN clientes cli_c ON cli_c.cli_id = c.cotiz_cliente
INNER JOIN clientes cli_t ON cli_t.cli_id = t.tik_cliente
WHERE c.cotiz_cliente <> t.tik_cliente
ORDER BY c.cotiz_id;

-- 2) Corrección: alinear ticket con la cotización
--    También actualiza sucursal cuando la cotización tiene una definida
START TRANSACTION;

UPDATE clientes_tikets t
INNER JOIN cotizacion c ON c.cotiz_id = t.tik_id_cotizacion
SET
    t.tik_cliente = c.cotiz_cliente,
    t.tik_sucursal = CASE
        WHEN c.cotiz_sucursal IS NOT NULL AND c.cotiz_sucursal > 0
            THEN c.cotiz_sucursal
        ELSE t.tik_sucursal
    END
WHERE c.cotiz_cliente <> t.tik_cliente;

-- Revisar filas afectadas antes de COMMIT
SELECT ROW_COUNT() AS tickets_corregidos;

-- Si el resultado es el esperado (p.ej. 11), confirmar:
COMMIT;
-- Si algo no cuadra: ROLLBACK;

-- 3) Verificación (después): debe devolver 0 filas
SELECT
    c.cotiz_id,
    t.tik_id,
    c.cotiz_cliente,
    t.tik_cliente
FROM cotizacion c
INNER JOIN clientes_tikets t ON t.tik_id_cotizacion = c.cotiz_id
WHERE c.cotiz_cliente <> t.tik_cliente;

-- 4) Opcional: casos enlazados solo por cotiz_ticket (sin tik_id_cotizacion)
SELECT
    c.cotiz_id,
    t.tik_id,
    c.cotiz_cliente,
    t.tik_cliente,
    t.tik_id_cotizacion
FROM cotizacion c
INNER JOIN clientes_tikets t ON t.tik_id = c.cotiz_ticket
WHERE c.cotiz_cliente <> t.tik_cliente
   OR (t.tik_id_cotizacion IS NULL OR t.tik_id_cotizacion = 0 OR t.tik_id_cotizacion <> c.cotiz_id);
