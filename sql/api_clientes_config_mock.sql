-- =====================================================
-- Configuración API clientes (Orion ↔ Ofima)
-- Ejecutar en la BD principal (MAINBD) para habilitar
-- el módulo de clientes y, opcionalmente, apuntar
-- Orion → Ofima al mock local para pruebas.
-- =====================================================
-- Ajustar apic_id_empresa si tu instalación usa otro (ej. 1).
-- Las contraseñas en apic_password deben ir en base64.
-- =====================================================

-- Insertar o actualizar configuración para clientes (Orion → Ofima)
-- Para pruebas locales con mock, la URL apunta al script mock.
INSERT INTO api_configuracion (apic_modulo, apic_direccion, apic_url_endpoint, apic_usuario, apic_password, apic_activo, apic_id_empresa)
VALUES (
    'clientes',
    'orion_ofima',
    'http://localhost/softjm/usuarios/api/mock/ofima-clientes-recibir.php',
    'usuario_ofima',
    'cGFzc3dvcmRfZW5jcmlwdGFk',  -- base64 de 'password_encryptad' (cambiar por tu contraseña en base64)
    1,
    1
)
ON DUPLICATE KEY UPDATE
    apic_url_endpoint = VALUES(apic_url_endpoint),
    apic_activo = VALUES(apic_activo);

-- Insertar o actualizar configuración para clientes (Ofima → Orion)
-- Ofima llamará a esta URL; en producción usar la URL real de tu servidor.
INSERT INTO api_configuracion (apic_modulo, apic_direccion, apic_url_endpoint, apic_usuario, apic_password, apic_activo, apic_id_empresa)
VALUES (
    'clientes',
    'ofima_orion',
    'https://tu-dominio.com/usuarios/api/orion/clientes-recibir.php',
    'usuario_orion',
    'cGFzc3dvcmRfZW5jcmlwdGFk',  -- base64 (cambiar en producción)
    1,
    1
)
ON DUPLICATE KEY UPDATE
    apic_url_endpoint = VALUES(apic_url_endpoint);

-- Mapeo de campos clientes (si aún no existen)
INSERT INTO api_mapeo_campos (apim_modulo, apim_campo_orion, apim_campo_ofima, apim_tipo_transformacion, apim_activo, apim_id_empresa)
VALUES
    ('clientes', 'cli_usuario', 'identificacion', 'directo', 1, 1),
    ('clientes', 'cli_nombre', 'nombre', 'directo', 1, 1),
    ('clientes', 'cli_email', 'email', 'directo', 1, 1),
    ('clientes', 'cli_telefono', 'telefono', 'directo', 1, 1),
    ('clientes', 'cli_direccion', 'direccion', 'directo', 1, 1),
    ('clientes', 'cli_ciudad', 'ciudad', 'entero', 1, 1),
    ('clientes', 'cli_celular', 'celular', 'directo', 1, 1),
    ('clientes', 'cli_referencia', 'referencia', 'directo', 1, 1)
ON DUPLICATE KEY UPDATE apim_campo_ofima = VALUES(apim_campo_ofima);
