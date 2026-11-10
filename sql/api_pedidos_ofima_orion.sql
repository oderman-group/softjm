-- Registra el endpoint Orion que recibe pedidos desde tienda/CMS (ofima_orion).
-- Reutiliza credenciales del módulo clientes ofima_orion de la misma empresa.

INSERT INTO api_configuracion (
    apic_modulo,
    apic_direccion,
    apic_url_endpoint,
    apic_usuario,
    apic_password,
    apic_activo,
    apic_id_empresa
)
SELECT
    'pedidos',
    'ofima_orion',
    'https://developer.orioncrm.com.co/softjm/usuarios/api/orion/pedidos-recibir.php',
    c.apic_usuario,
    c.apic_password,
    1,
    c.apic_id_empresa
FROM api_configuracion c
WHERE c.apic_modulo = 'clientes'
  AND c.apic_direccion = 'ofima_orion'
  AND NOT EXISTS (
      SELECT 1
      FROM api_configuracion x
      WHERE x.apic_modulo = 'pedidos'
        AND x.apic_direccion = 'ofima_orion'
        AND x.apic_id_empresa = c.apic_id_empresa
  );
