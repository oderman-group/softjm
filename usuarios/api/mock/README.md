# Mock API Ofima (pruebas locales)

Carpeta para simular la API de Ofima cuando se prueba el flujo **Orion → Ofima** en local (productos y clientes).

## Productos

1. En la base de datos, actualiza la configuración para productos / orion_ofima:

```sql
UPDATE api_configuracion
SET apic_url_endpoint = 'http://localhost/softjm/usuarios/api/mock/ofima-productos-recibir.php',
    apic_activo = 1
WHERE apic_modulo = 'productos'
  AND apic_direccion = 'orion_ofima'
  AND apic_id_empresa = 1;
```

2. Crea o edita un producto desde la aplicación Orion y guarda.

3. Revisa `log-ofima-productos.txt` en esta carpeta: verás cada petición (fecha, método POST/PUT y el JSON enviado).

## Clientes

1. En la base de datos, actualiza la configuración para clientes / orion_ofima:

```sql
UPDATE api_configuracion
SET apic_url_endpoint = 'http://localhost/softjm/usuarios/api/mock/ofima-clientes-recibir.php',
    apic_activo = 1
WHERE apic_modulo = 'clientes'
  AND apic_direccion = 'orion_ofima'
  AND apic_id_empresa = 1;
```

2. Crea o edita un cliente desde la aplicación Orion y guarda.

3. Revisa `log-ofima-clientes.txt` en esta carpeta: verás cada petición (fecha, método POST/PUT y el JSON del cliente).

## SQL completo para clientes

Para insertar/actualizar toda la configuración de clientes (incluyendo mock para Orion→Ofima y mapeo de campos), ejecuta el script:

`sql/api_clientes_config_mock.sql`

Cuando tengas la URL real de Ofima, cambia `apic_url_endpoint` en `api_configuracion` por esa URL (productos y/o clientes).
