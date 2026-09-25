-- Campos homologación códigos DIAN / DIVIPOLA
-- Ejecutar en BDADMIN (ej. orioncrmcom_dev_crm_admin)

ALTER TABLE localidad_departamentos
  ADD COLUMN IF NOT EXISTS dep_cod_dian VARCHAR(5) NULL DEFAULT NULL
  COMMENT 'Código DIAN/DIVIPOLA del departamento (2 dígitos)'
  AFTER dep_indicativo;

ALTER TABLE localidad_ciudades
  ADD COLUMN IF NOT EXISTS ciu_cod_dian VARCHAR(10) NULL DEFAULT NULL
  COMMENT 'Código DIAN/DIVIPOLA del municipio (5 dígitos)'
  AFTER ciu_departamento;

-- Índices útiles para búsqueda por código
CREATE INDEX IF NOT EXISTS idx_dep_cod_dian ON localidad_departamentos (dep_cod_dian);
CREATE INDEX IF NOT EXISTS idx_ciu_cod_dian ON localidad_ciudades (ciu_cod_dian);
