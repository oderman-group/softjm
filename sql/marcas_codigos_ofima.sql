SET @id_empresa = 1;

-- 1. Deshabilitar todas las marcas de la empresa
UPDATE marcas
SET mar_habilitada = 0
WHERE mar_id_empresa = @id_empresa;


-- 2. Crear una tabla temporal con la homologación Ofima
DROP TEMPORARY TABLE IF EXISTS tmp_marcas_ofima;

CREATE TEMPORARY TABLE tmp_marcas_ofima (
    mar_cod_ofima VARCHAR(20),
    mar_nombre VARCHAR(255)
);

INSERT INTO tmp_marcas_ofima (mar_cod_ofima, mar_nombre) VALUES
('0',    'VARIOS'),
('1001', 'DJI ENTERPRISE'),
('1002', 'FORTE'),
('1003', 'TRACKER'),
('1004', 'GEOMAX'),
('1005', 'ESURVEY'),
('1006', 'KOLIDA'),
('1007', 'IDS'),
('1008', 'TOPCON'),
('1009', 'GEOCUE'),
('1010', 'DERUITE'),
('1011', 'TRIMBLE'),
('1012', 'LEICA'),
('1013', 'SOUTH'),
('1014', 'PRODUCTOS NACIONALES'),
('1015', 'ECOFLOW'),
('1016', 'JM EQUIPOS'),
('1017', 'NIKON'),
('1018', 'SOKKIA'),
('1019', 'PENTAX'),
('1020', 'GOWIN'),
('1021', 'COMNAV'),
('1022', 'RinoNav'),
('1023', 'INSTA360'),
('1024', 'GARMIN'),
('1025', 'SHOKZ'),
('1026', 'MICROSURVEY'),
('1027', 'LAP CORP'),
('1028', 'WINMATE'),
('1029', 'SPECTRA'),
('1030', 'SECO'),
('1031', 'PANASONIC'),
('1032', 'DJI AGRICULTURA'),
('1033', 'DJI CONSUMO'),
('1034', 'SONY'),
('1035', 'CHC NAV');


-- 3. Actualizar las marcas que YA existen
UPDATE marcas m
INNER JOIN tmp_marcas_ofima o
    ON UPPER(CONVERT(TRIM(m.mar_nombre) USING utf8mb4))
       = UPPER(CONVERT(TRIM(o.mar_nombre) USING utf8mb4))
SET
    m.mar_habilitada = 1,
    m.mar_cod_ofima = o.mar_cod_ofima
WHERE m.mar_id_empresa = @id_empresa;


-- 4. Crear las marcas que NO existen
INSERT INTO marcas (
    mar_id_empresa,
    mar_nombre,
    mar_cod_ofima,
    mar_habilitada
)
SELECT
    @id_empresa,
    o.mar_nombre,
    o.mar_cod_ofima,
    1
FROM tmp_marcas_ofima o
LEFT JOIN marcas m
    ON m.mar_id_empresa = @id_empresa
    AND UPPER(CONVERT(TRIM(m.mar_nombre) USING utf8mb4))
        = UPPER(CONVERT(TRIM(o.mar_nombre) USING utf8mb4))
WHERE m.mar_id IS NULL;


-- 5. Eliminar tabla temporal
DROP TEMPORARY TABLE IF EXISTS tmp_marcas_ofima;