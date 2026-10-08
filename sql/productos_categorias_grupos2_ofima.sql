-- Grupo 2 (sublínea). catp_grupo sigue siendo 2.
-- catp_cod_grupo guarda el código Ofima.
-- Los de la lista quedan habilitados. El resto del grupo 2 de la empresa queda deshabilitado.
SET @id_empresa = 1;

-- 1. Deshabilitar todas las categorías actuales del grupo 2
UPDATE productos_categorias
SET catp_habilitada = 0
WHERE catp_grupo = 2
  AND catp_id_empresa = @id_empresa;


-- 2. Crear únicamente las categorías cuyo código
--    todavía NO exista en el grupo 2
INSERT INTO productos_categorias (
    catp_nombre,
    catp_grupo,
    catp_cod_grupo,
    catp_habilitada,
    catp_id_empresa
)
SELECT
    v.nombre,
    2,
    v.codigo,
    1,
    @id_empresa
FROM (
    SELECT 'VARIOS' AS nombre, '0' AS codigo

    UNION ALL SELECT 'EQUIPOS AGRICULTURA', '101'
    UNION ALL SELECT 'REPUESTOS DE AGRICULTURA', '102'
    UNION ALL SELECT 'ACCESORIOS DE AGRICULTURA', '103'
    UNION ALL SELECT 'SOFTWARE DE AGRICULTURA', '104'
    UNION ALL SELECT 'HERRAMIENTAS DE AGRICULTURA', '105'

    UNION ALL SELECT 'EQUIPOS TOPOGRAFÍA', '201'
    UNION ALL SELECT 'REPUESTOS DE TOPOGRAFIA', '202'
    UNION ALL SELECT 'ACCESORIOS TOPOGRAFÍA', '203'
    UNION ALL SELECT 'SOFTWARE TOPOGRAFIA', '204'
    UNION ALL SELECT 'HERRAMIENTAS DE TOPOGRAFIA', '205'
    UNION ALL SELECT 'DRONES ENTERPRISE', '206'
    UNION ALL SELECT 'DRONES TOPOGRAFÍA', '207'

    UNION ALL SELECT 'CURSOS Y CAPACITACIONES', '301'
    UNION ALL SELECT 'SERVICIO TÉCNICO AGRICULTURA', '302'
    UNION ALL SELECT 'SERVICIOS DE INGENIERIA Y TOPOGRAFIA', '303'
    UNION ALL SELECT 'SERVICIO TECNICO DE TOPOGRAFIA', '304'
    UNION ALL SELECT 'SERVICIO TÉCNICO TOPOGRAFÍA Y GPR', '305'

    UNION ALL SELECT 'DRONES CONSUMO', '401'
    UNION ALL SELECT 'GADGETS', '402'
    UNION ALL SELECT 'REPUESTOS CONSUMO', '403'
) v
WHERE NOT EXISTS (
    SELECT 1
    FROM productos_categorias c
    WHERE c.catp_grupo = 2
      AND c.catp_id_empresa = @id_empresa
      AND c.catp_cod_grupo = v.codigo
);
