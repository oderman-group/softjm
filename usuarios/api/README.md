# API de Integración Orion-Ofima

## Descripción

Este módulo implementa la integración bidireccional entre Orion CRM y Ofima ERP para sincronizar datos en tiempo real.

## Instalación

### 1. Ejecutar Scripts SQL

Ejecutar el script de creación de tablas:

```sql
SOURCE sql/api_integracion_tablas.sql;
```

O desde phpMyAdmin:
1. Seleccionar la base de datos principal
2. Ir a la pestaña SQL
3. Copiar y pegar el contenido de `sql/api_integracion_tablas.sql`
4. Ejecutar

### 2. Configurar Credenciales

Insertar o actualizar las credenciales en la tabla `api_configuracion`:

```sql
-- Ejemplo para productos (Orion -> Ofima)
UPDATE api_configuracion 
SET apic_url_endpoint = 'https://ofima.com/api/productos',
    apic_usuario = 'usuario_ofima',
    apic_password = 'base64_encode(password_aqui)',
    apic_activo = 1
WHERE apic_modulo = 'productos' 
AND apic_direccion = 'orion_ofima'
AND apic_id_empresa = 1;

-- Ejemplo para productos (Ofima -> Orion)
UPDATE api_configuracion 
SET apic_url_endpoint = '/api/orion/productos-recibir.php',
    apic_usuario = 'usuario_orion',
    apic_password = 'base64_encode(password_aqui)',
    apic_activo = 1
WHERE apic_modulo = 'productos' 
AND apic_direccion = 'ofima_orion'
AND apic_id_empresa = 1;
```

**Nota:** Las contraseñas deben estar encriptadas en base64 (temporalmente). Se recomienda implementar encriptación real.

### 3. Configurar Mapeo de Campos

El mapeo de campos se puede ajustar en la tabla `api_mapeo_campos`. Los valores por defecto ya están insertados en el script SQL.

## Endpoints Disponibles

### Autenticación JWT (obtener token)

**URL:** `POST /usuarios/api/orion/auth.php`

Obtiene un token JWT (válido 12 horas) para usar en el resto de endpoints. Así la contraseña no se envía en cada petición.

**Credenciales:** Basic Auth **o** body JSON: `{ "usuario": "...", "password": "...", "id_empresa": 1 }`

**Respuesta (200):**
```json
{
    "success": true,
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "expires_in": 43200,
    "token_type": "Bearer"
}
```

**Uso:** En productos-recibir (y futuros endpoints), enviar header: `Authorization: Bearer <token>`.

**Configuración:** En producción definir `API_JWT_SECRET` en `sensitive.php` (clave larga y aleatoria para firmar los tokens).

---

### Recibir Productos desde Ofima

**URL:** `POST /usuarios/api/orion/productos-recibir.php`

**Autenticación:** `Authorization: Bearer <token>` (recomendado) **o** HTTP Basic Auth

**Headers:**
```
Content-Type: application/json
Authorization: Bearer <token>
```
o
```
Authorization: Basic base64(usuario:password)
```

**Body (JSON):**
```json
{
    "referencia": "REF-12345",
    "nombre": "Producto desde Ofima",
    "costo": 100000.00,
    "utilidad": 30.00,
    "precio": 142857.14,
    "categoria_id": 1,
    "marca_id": 3,
    "proveedor_id": 4,
    "descripcion_corta": "Descripción breve",
    "descripcion_larga": "Descripción detallada",
    "id_empresa": 1
}
```

**Respuesta Exitosa (200):**
```json
{
    "success": true,
    "message": "Producto sincronizado correctamente",
    "producto_id": 123,
    "referencia": "REF-12345",
    "operacion": "CREATE"
}
```

## Funcionamiento

### Sincronización Orion -> Ofima

Cuando se crea o actualiza un producto en Orion:

1. El sistema detecta el cambio en `productos-guardar.php` o `productos-actualizar.php`
2. Se llama a `Producto::sincronizarConOfima()`
3. Se obtiene la configuración de API desde `api_configuracion`
4. Se mapean los campos de Orion a Ofima usando `api_mapeo_campos`
5. Se envía la petición HTTP a Ofima
6. Se registra el resultado en `api_sincronizaciones`

### Sincronización Ofima -> Orion

Cuando Ofima envía datos a Orion:

1. Ofima hace POST a `/usuarios/api/orion/productos-recibir.php`
2. Se verifica la autenticación
3. Se mapean los campos de Ofima a Orion
4. Se busca si el producto existe por referencia
5. Si existe: se actualiza
6. Si no existe: se crea
7. Se registra el resultado en `api_sincronizaciones`

## Logs y Monitoreo

Todas las sincronizaciones se registran en la tabla `api_sincronizaciones`:

```sql
-- Ver últimas sincronizaciones
SELECT * FROM api_sincronizaciones 
ORDER BY apis_fecha_creacion DESC 
LIMIT 50;

-- Ver sincronizaciones con error
SELECT * FROM api_sincronizaciones 
WHERE apis_estado = 'error'
ORDER BY apis_fecha_creacion DESC;

-- Ver estadísticas por módulo
SELECT apis_modulo, apis_estado, COUNT(*) as cantidad
FROM api_sincronizaciones
GROUP BY apis_modulo, apis_estado;
```

## Troubleshooting

### Error: "No hay configuración de API"

Verificar que existe un registro activo en `api_configuracion` para el módulo y dirección correspondiente.

### Error: "Autenticación requerida"

Verificar que las credenciales en `api_configuracion` sean correctas y que Ofima esté enviando las credenciales correctamente. Si usas JWT y el header `Authorization: Bearer` no llega a PHP (Apache puede eliminarlo), añade en `.htaccess` o en la config del virtual host: `SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1`.

### Error: "Producto no encontrado"

Verificar que el campo `referencia` en Ofima coincida con `prod_referencia` en Orion.

### Sincronización no se ejecuta

Verificar que `apic_activo = 1` en `api_configuracion` y que no haya errores en los logs de PHP.

## Seguridad

- Las contraseñas deben estar encriptadas (actualmente en base64, mejorar)
- Usar HTTPS para las comunicaciones
- Validar todos los datos recibidos
- Implementar rate limiting si es necesario
- Revisar logs regularmente

## Próximos Pasos

- [ ] Implementar encriptación real de contraseñas
- [ ] Agregar endpoints para clientes, inventario y pedidos
- [ ] Implementar sistema de reintentos automáticos
- [ ] Crear interfaz de administración para ver logs
- [ ] Agregar notificaciones de errores

