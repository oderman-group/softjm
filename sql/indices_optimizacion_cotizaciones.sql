-- =====================================================
-- ÍNDICES PARA OPTIMIZACIÓN DE cotizaciones-editar.php
-- =====================================================
-- Estos índices mejoran el rendimiento de las consultas
-- más frecuentes en la página de edición de cotizaciones
-- =====================================================

-- Tabla: cotizacion
-- Optimiza la consulta principal
ALTER TABLE cotizacion 
ADD INDEX idx_cotiz_id_empresa (cotiz_id, cotiz_id_empresa),
ADD INDEX idx_cotiz_cliente (cotiz_cliente),
ADD INDEX idx_cotiz_contacto (cotiz_contacto);

-- Tabla: clientes
-- Optimiza búsqueda de clientes por empresa y categoría
ALTER TABLE clientes 
ADD INDEX idx_cli_empresa_categoria (cli_id_empresa, cli_categoria);

-- Tabla: sucursales
-- Optimiza búsqueda de sucursales por cliente
ALTER TABLE sucursales 
ADD INDEX idx_sucu_cliente (sucu_cliente_principal);

-- Tabla: contactos
-- Optimiza búsqueda de contactos por cliente
ALTER TABLE contactos 
ADD INDEX idx_cont_cliente (cont_cliente_principal);

-- Tabla: usuarios
-- Optimiza búsqueda de usuarios activos por empresa
ALTER TABLE usuarios 
ADD INDEX idx_usr_empresa_bloqueado (usr_id_empresa, usr_bloqueado);

-- Tabla: cotizacion_productos
-- Optimiza búsqueda de productos/combos de una cotización
ALTER TABLE cotizacion_productos 
ADD INDEX idx_czpp_cotiz_tipo (czpp_cotizacion, czpp_tipo),
ADD INDEX idx_czpp_producto (czpp_producto),
ADD INDEX idx_czpp_combo (czpp_combo);

-- Tabla: productos
-- Optimiza join con cotizacion_productos
ALTER TABLE productos 
ADD INDEX idx_prod_id_empresa (prod_id, prod_id_empresa);

-- Tabla: combos
-- Optimiza join con cotizacion_productos
ALTER TABLE combos 
ADD INDEX idx_combo_id_empresa (combo_id, combo_id_empresa);

-- Tabla: clientes_tikets
-- Optimiza búsqueda de tickets disponibles para cotización
ALTER TABLE clientes_tikets 
ADD INDEX idx_tik_cliente_estado (tik_cliente, tik_estado, tik_tipo_tiket, tik_tipo_negocio, tik_id_cotizacion);

-- Tabla: proveedores
-- Optimiza búsqueda de proveedores por empresa
ALTER TABLE proveedores 
ADD INDEX idx_prov_empresa (prov_id_empresa);

-- =====================================================
-- VERIFICAR ÍNDICES EXISTENTES ANTES DE EJECUTAR
-- =====================================================
-- Ejecutar primero para ver qué índices ya existen:
-- SHOW INDEX FROM cotizacion;
-- SHOW INDEX FROM clientes;
-- SHOW INDEX FROM sucursales;
-- SHOW INDEX FROM contactos;
-- SHOW INDEX FROM usuarios;
-- SHOW INDEX FROM cotizacion_productos;
-- SHOW INDEX FROM productos;
-- SHOW INDEX FROM combos;
-- SHOW INDEX FROM clientes_tikets;
-- SHOW INDEX FROM proveedores;

-- =====================================================
-- NOTAS IMPORTANTES:
-- =====================================================
-- 1. Algunos índices pueden ya existir. Si al ejecutar
--    aparece "Duplicate key name", significa que el
--    índice ya existe y puede ignorarse.
--
-- 2. Para tablas muy grandes (>1M registros), ejecutar
--    estos comandos en horarios de baja actividad.
--
-- 3. Después de crear los índices, ejecutar:
--    ANALYZE TABLE nombre_tabla;
--    para actualizar las estadísticas.
--
-- 4. Monitorear el tamaño de los índices con:
--    SELECT 
--        table_name,
--        index_name,
--        ROUND(stat_value * @@innodb_page_size / 1024 / 1024, 2) AS size_mb
--    FROM mysql.innodb_index_stats
--    WHERE database_name = 'nombre_bd'
--    AND table_name IN ('cotizacion', 'clientes', 'productos')
--    ORDER BY size_mb DESC;
-- =====================================================

