-- Grupo 3 (clasificación). catp_grupo = 3.
-- catp_cod_grupo guarda el código Ofima, con ceros a la izquierda.
-- VARIOS queda con código 0. Los de la lista quedan habilitados.
-- El resto del grupo 3 de la empresa queda deshabilitado.
SET @id_empresa = 1;

-- 1. Deshabilitar todas las categorías actuales del grupo 3
UPDATE productos_categorias
SET catp_habilitada = 0
WHERE catp_grupo = 3
  AND catp_id_empresa = @id_empresa;


-- 2. Crear únicamente las categorías cuyo código
--    todavía NO exista en el grupo 3
INSERT INTO productos_categorias (
    catp_nombre,
    catp_grupo,
    catp_cod_grupo,
    catp_habilitada,
    catp_id_empresa
)
SELECT
    v.nombre,
    3,
    v.codigo,
    1,
    @id_empresa
FROM (
    SELECT 'VARIOS' AS nombre, '0' AS codigo

    UNION ALL SELECT 'ESTACIONES TOTALES', '001'
    UNION ALL SELECT 'RECEPTOR GNSS - GPS', '002'
    UNION ALL SELECT 'NIVELES', '003'
    UNION ALL SELECT 'ACCESORIOS ESTA-NIVEL-TEO-GNSS', '004'
    UNION ALL SELECT 'GEORRADARES', '005'
    UNION ALL SELECT 'LIDAR SLAM ESCANER', '006'
    UNION ALL SELECT 'COLECTORES Y TABLETS', '007'
    UNION ALL SELECT 'BATERIAS Y CARGADORES TOPOGRAFIA', '008'
    UNION ALL SELECT 'DRONES', '009'
    UNION ALL SELECT 'ACCESORIOS DRONES', '010'
    UNION ALL SELECT 'BATERIAS Y CARGADORES DRONES', '011'
    UNION ALL SELECT 'REPUESTOS TOPOGRAFIA', '012'
    UNION ALL SELECT 'REPUESTOS GEORRADAR', '013'
    UNION ALL SELECT 'REPUESTOS DRONE AGRAS', '014'
    UNION ALL SELECT 'REPUESTOS DRONE ENTERPRISE', '015'
    UNION ALL SELECT 'REPUESTOS DRONE CONSUMO', '016'
    UNION ALL SELECT 'ESTUCHES Y MORRALES', '017'
    UNION ALL SELECT 'HERRAMIENTAS Y MUEBLES', '018'
    UNION ALL SELECT 'SOFTWARE', '019'
    UNION ALL SELECT 'ENERGIA PORTATIL', '020'
    UNION ALL SELECT 'EQUIPO DE COMPUTO', '021'
    UNION ALL SELECT 'CAMARAS', '022'
    UNION ALL SELECT 'RELOJES', '023'
    UNION ALL SELECT 'AUDIFONOS', '024'
    UNION ALL SELECT 'SERVICIO DE ALQUILER', '025'
    UNION ALL SELECT 'SERVICIO DE CAPACITACION', '026'
    UNION ALL SELECT 'SERVICIO PRO INGENIERIA Y TOPO', '027'
    UNION ALL SELECT 'SERVICIO TECNICO', '028'
    UNION ALL SELECT 'TERMOMETROS', '029'
    UNION ALL SELECT 'TEODOLITOS', '030'
    UNION ALL SELECT 'GENERADORES PLANTA ELECTRICA PORTABLE', '031'
    UNION ALL SELECT 'DISTANCIOMETROS', '032'
    UNION ALL SELECT 'ESCANER LIDAR TERRESTRE', '033'
    UNION ALL SELECT 'LOCALIZADOR DE TUBERIA', '034'
) v
WHERE NOT EXISTS (
    SELECT 1
    FROM productos_categorias c
    WHERE c.catp_grupo = 3
      AND c.catp_id_empresa = @id_empresa
      AND c.catp_cod_grupo = v.codigo
);