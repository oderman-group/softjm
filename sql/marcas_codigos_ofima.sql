-- Relaciona marcas ya existentes (empresa 1) con el código Ofima.
-- Solo actualiza cuando el nombre coincide. No crea marcas nuevas.
-- TRIMBLE LEAP no toma el código de TRIMBLE.

SET @id_empresa = 1;

UPDATE marcas
SET mar_cod_ofima = CASE UPPER(CONVERT(TRIM(mar_nombre) USING utf8mb4))
    WHEN 'COMNAV' THEN '1021'
    WHEN 'GARMIN' THEN '1024'
    WHEN 'GEOCUE' THEN '1009'
    WHEN 'GEOMAX' THEN '1004'
    WHEN 'GOWIN' THEN '1020'
    WHEN 'KOLIDA' THEN '1006'
    WHEN 'LEICA' THEN '1012'
    WHEN 'MICROSURVEY' THEN '1026'
    WHEN 'NIKON' THEN '1017'
    WHEN 'PANASONIC' THEN '1031'
    WHEN 'PENTAX' THEN '1019'
    WHEN 'SECO' THEN '1030'
    WHEN 'SOKKIA' THEN '1018'
    WHEN 'SONY' THEN '1034'
    WHEN 'SOUTH' THEN '1013'
    WHEN 'SPECTRA' THEN '1029'
    WHEN 'TOPCON' THEN '1008'
    WHEN 'TRIMBLE' THEN '1011'
    WHEN 'WINMATE' THEN '1028'
END
WHERE mar_id_empresa = @id_empresa
  AND UPPER(CONVERT(TRIM(mar_nombre) USING utf8mb4)) IN (
    'COMNAV',
    'GARMIN',
    'GEOCUE',
    'GEOMAX',
    'GOWIN',
    'KOLIDA',
    'LEICA',
    'MICROSURVEY',
    'NIKON',
    'PANASONIC',
    'PENTAX',
    'SECO',
    'SOKKIA',
    'SONY',
    'SOUTH',
    'SPECTRA',
    'TOPCON',
    'TRIMBLE',
    'WINMATE'
  );
