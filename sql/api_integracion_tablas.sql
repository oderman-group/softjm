-- =====================================================
-- Tablas para Integración API Orion-Ofima
-- =====================================================

-- Tabla de configuración de APIs
CREATE TABLE IF NOT EXISTS api_configuracion (
    apic_id INT AUTO_INCREMENT PRIMARY KEY,
    apic_modulo VARCHAR(50) NOT NULL COMMENT 'clientes, productos, inventario, pedidos',
    apic_direccion ENUM('orion_ofima', 'ofima_orion') NOT NULL COMMENT 'Dirección de sincronización',
    apic_url_endpoint VARCHAR(500) NOT NULL COMMENT 'URL del endpoint de la API',
    apic_usuario VARCHAR(100) NOT NULL COMMENT 'Usuario para autenticación básica',
    apic_password VARCHAR(255) NOT NULL COMMENT 'Contraseña encriptada',
    apic_activo TINYINT(1) DEFAULT 1 COMMENT '1=Activo, 0=Inactivo',
    apic_id_empresa INT NOT NULL COMMENT 'ID de la empresa (multi-tenant)',
    apic_fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    apic_fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_modulo_direccion_empresa (apic_modulo, apic_direccion, apic_id_empresa),
    INDEX idx_empresa (apic_id_empresa),
    INDEX idx_modulo (apic_modulo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Configuración de endpoints y credenciales para APIs';

-- Tabla de logs de sincronizaciones
CREATE TABLE IF NOT EXISTS api_sincronizaciones (
    apis_id INT AUTO_INCREMENT PRIMARY KEY,
    apis_modulo VARCHAR(50) NOT NULL COMMENT 'clientes, productos, inventario, pedidos',
    apis_direccion ENUM('orion_ofima', 'ofima_orion') NOT NULL,
    apis_tipo_operacion ENUM('CREATE', 'UPDATE', 'DELETE') NOT NULL,
    apis_id_registro INT NOT NULL COMMENT 'ID del registro sincronizado',
    apis_referencia VARCHAR(100) COMMENT 'Referencia única del registro (ej: prod_referencia)',
    apis_datos_enviados TEXT COMMENT 'JSON con datos enviados',
    apis_respuesta TEXT COMMENT 'JSON con respuesta del servidor',
    apis_estado ENUM('pendiente', 'exitoso', 'error', 'reintento') DEFAULT 'pendiente',
    apis_codigo_respuesta INT COMMENT 'Código HTTP de respuesta',
    apis_mensaje_error TEXT COMMENT 'Mensaje de error si falló',
    apis_intentos INT DEFAULT 0 COMMENT 'Número de intentos realizados',
    apis_id_empresa INT NOT NULL,
    apis_fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    apis_fecha_procesado DATETIME NULL,
    INDEX idx_modulo (apis_modulo),
    INDEX idx_estado (apis_estado),
    INDEX idx_empresa (apis_id_empresa),
    INDEX idx_referencia (apis_referencia),
    INDEX idx_fecha_creacion (apis_fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Logs de todas las sincronizaciones realizadas';

-- Tabla de mapeo de campos entre sistemas
CREATE TABLE IF NOT EXISTS api_mapeo_campos (
    apim_id INT AUTO_INCREMENT PRIMARY KEY,
    apim_modulo VARCHAR(50) NOT NULL COMMENT 'clientes, productos, inventario, pedidos',
    apim_campo_orion VARCHAR(100) NOT NULL COMMENT 'Nombre del campo en Orion',
    apim_campo_ofima VARCHAR(100) NOT NULL COMMENT 'Nombre del campo en Ofima',
    apim_tipo_transformacion VARCHAR(50) DEFAULT 'directo' COMMENT 'directo, fecha, decimal, etc',
    apim_valor_default VARCHAR(255) NULL COMMENT 'Valor por defecto si no existe',
    apim_activo TINYINT(1) DEFAULT 1,
    apim_id_empresa INT NOT NULL,
    apim_fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_modulo_campo_orion_empresa (apim_modulo, apim_campo_orion, apim_id_empresa),
    INDEX idx_modulo (apim_modulo),
    INDEX idx_empresa (apim_id_empresa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Mapeo de campos entre Orion y Ofima';

-- Insertar configuraciones por defecto para productos (ejemplo)
-- NOTA: Las contraseñas deben ser encriptadas antes de insertar (ej. base64).
-- Para ofima_orion, apic_url_endpoint debe ser la URL COMPLETA que Ofima usará para llamar a Orion
-- (ej. https://tu-dominio.com/usuarios/api/orion/productos-recibir.php). Definir en despliegue.
INSERT INTO api_configuracion (apic_modulo, apic_direccion, apic_url_endpoint, apic_usuario, apic_password, apic_id_empresa) 
VALUES 
('productos', 'orion_ofima', 'https://ofima.com/api/productos', 'usuario_ofima', 'password_encriptado', 1),
('productos', 'ofima_orion', 'https://tu-dominio.com/usuarios/api/orion/productos-recibir.php', 'usuario_orion', 'password_encriptado', 1)
ON DUPLICATE KEY UPDATE apic_url_endpoint = VALUES(apic_url_endpoint);

-- Insertar mapeo de campos por defecto para productos
-- Incluye precios, costos, utilidades y campos opcionales (grupo, descuentos, comisión, costo_dolar)
INSERT INTO api_mapeo_campos (apim_modulo, apim_campo_orion, apim_campo_ofima, apim_tipo_transformacion, apim_id_empresa) 
VALUES 
('productos', 'prod_referencia', 'referencia', 'directo', 1),
('productos', 'prod_nombre', 'nombre', 'directo', 1),
('productos', 'prod_costo', 'costo', 'decimal', 1),
('productos', 'prod_utilidad', 'utilidad', 'decimal', 1),
('productos', 'prod_precio', 'precio', 'decimal', 1),
('productos', 'prod_categoria', 'categoria_id', 'entero', 1),
('productos', 'prod_grupo1', 'grupo_id', 'entero', 1),
('productos', 'prod_marca', 'marca_id', 'entero', 1),
('productos', 'prod_proveedor', 'proveedor_id', 'entero', 1),
('productos', 'prod_descripcion_corta', 'descripcion_corta', 'directo', 1),
('productos', 'prod_descripcion_larga', 'descripcion_larga', 'directo', 1),
('productos', 'prod_descuento1', 'descuento1', 'decimal', 1),
('productos', 'prod_comision', 'comision', 'decimal', 1),
('productos', 'prod_costo_dolar', 'costo_dolar', 'decimal', 1)
ON DUPLICATE KEY UPDATE apim_campo_ofima = VALUES(apim_campo_ofima);

-- Configuración por defecto para clientes (Orion ↔ Ofima)
INSERT INTO api_configuracion (apic_modulo, apic_direccion, apic_url_endpoint, apic_usuario, apic_password, apic_id_empresa) 
VALUES 
('clientes', 'orion_ofima', 'https://ofima.com/api/clientes', 'usuario_ofima', 'password_encriptado', 1),
('clientes', 'ofima_orion', 'https://tu-dominio.com/usuarios/api/orion/clientes-recibir.php', 'usuario_orion', 'password_encriptado', 1)
ON DUPLICATE KEY UPDATE apic_url_endpoint = VALUES(apic_url_endpoint);

-- Mapeo de campos por defecto para clientes (identificacion = NIT/documento = cli_usuario en Orion)
INSERT INTO api_mapeo_campos (apim_modulo, apim_campo_orion, apim_campo_ofima, apim_tipo_transformacion, apim_id_empresa) 
VALUES 
('clientes', 'cli_usuario', 'identificacion', 'directo', 1),
('clientes', 'cli_nombre', 'nombre', 'directo', 1),
('clientes', 'cli_email', 'email', 'directo', 1),
('clientes', 'cli_telefono', 'telefono', 'directo', 1),
('clientes', 'cli_direccion', 'direccion', 'directo', 1),
('clientes', 'cli_ciudad', 'ciudad', 'entero', 1),
('clientes', 'cli_celular', 'celular', 'directo', 1),
('clientes', 'cli_referencia', 'referencia', 'directo', 1)
ON DUPLICATE KEY UPDATE apim_campo_ofima = VALUES(apim_campo_ofima);

-- Configuración por defecto para inventario (Ofima → Orion, unidireccional)
INSERT INTO api_configuracion (apic_modulo, apic_direccion, apic_url_endpoint, apic_usuario, apic_password, apic_id_empresa) 
VALUES 
('inventario', 'ofima_orion', 'https://tu-dominio.com/usuarios/api/orion/inventario-recibir.php', 'usuario_orion', 'password_encriptado', 1)
ON DUPLICATE KEY UPDATE apic_url_endpoint = VALUES(apic_url_endpoint);

-- Política de inventario: cuando apin_solo_ofima_entrada = 1, el CRM no permite
-- crear ni editar existencias por bodega (solo Ofima vía API puede hacerlo).
CREATE TABLE IF NOT EXISTS api_inventario_config (
    apin_id_empresa INT NOT NULL PRIMARY KEY COMMENT 'ID empresa (multi-tenant)',
    apin_solo_ofima_entrada TINYINT(1) DEFAULT 0 COMMENT '1 = solo Ofima puede dar entrada de existencias',
    apin_fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Configuración política de inventario por empresa';

INSERT INTO api_inventario_config (apin_id_empresa, apin_solo_ofima_entrada) VALUES (1, 0)
ON DUPLICATE KEY UPDATE apin_id_empresa = apin_id_empresa;

-- Columna para sincronizar bodegas con Ofima (referencia/código en Ofima). Ejecutar si no existe.
-- ALTER TABLE bodegas ADD COLUMN bod_referencia VARCHAR(50) NULL DEFAULT NULL COMMENT 'Código/referencia Ofima';
-- CREATE INDEX idx_bodegas_referencia_empresa ON bodegas (bod_referencia, bod_id_empresa);

-- Configuración bodegas Ofima → Orion (para que Ofima notifique nuevas bodegas)
INSERT INTO api_configuracion (apic_modulo, apic_direccion, apic_url_endpoint, apic_usuario, apic_password, apic_id_empresa)
VALUES ('bodegas', 'ofima_orion', 'https://tu-dominio.com/usuarios/api/orion/bodegas-recibir.php', 'usuario_orion', 'password_encriptado', 1)
ON DUPLICATE KEY UPDATE apic_url_endpoint = VALUES(apic_url_endpoint);-- Configuración pedidos Orion → Ofima (Orion envía pedidos al ERP)
-- apic_url_endpoint = URL que Ofima expone para recibir pedidos (POST crear, PUT actualizar)
INSERT INTO api_configuracion (apic_modulo, apic_direccion, apic_url_endpoint, apic_usuario, apic_password, apic_id_empresa)
VALUES ('pedidos', 'orion_ofima', 'https://ofima.com/api/pedidos', 'usuario_ofima', 'password_encriptado', 1)
ON DUPLICATE KEY UPDATE apic_url_endpoint = VALUES(apic_url_endpoint);