-- catp_grupo sigue siendo 1 = grupo 1 y 2 = grupo 2.
-- catp_cod_grupo guarda el código Ofima del grupo (0, 100, 200...).
-- Si la columna ya existe, omitir el ALTER y ejecutar el resto.
SET @id_empresa = 1;

-- 1. Deshabilitar todas las categorías actuales del grupo 1
UPDATE productos_categorias
SET catp_habilitada = 0
WHERE catp_grupo = 1
  AND catp_id_empresa = @id_empresa;


-- 2. Crear únicamente las categorías cuyo código
--    todavía NO exista en el grupo 1
INSERT INTO productos_categorias (
    catp_nombre,
    catp_grupo,
    catp_cod_grupo,
    catp_habilitada,
    catp_id_empresa
)
SELECT
    v.nombre,
    1,
    v.codigo,
    1,
    @id_empresa
FROM (
    SELECT 'VARIOS' AS nombre, '0' AS codigo
    UNION ALL SELECT 'AGRICULTURA', '100'
    UNION ALL SELECT 'TOPOGRAFÍA', '200'
    UNION ALL SELECT 'SERVICIOS', '300'
    UNION ALL SELECT 'TECNOLOGÍA Y CONSUMO', '400'
    UNION ALL SELECT 'FERRETERÍA', '500'
) v
WHERE NOT EXISTS (
    SELECT 1
    FROM productos_categorias c
    WHERE c.catp_grupo = 1
      AND c.catp_id_empresa = @id_empresa
      AND c.catp_cod_grupo = v.codigo
);