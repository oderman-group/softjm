# Documentación Técnica - Módulo de Productos
## Integración API Orion CRM - Ofima ERP

**Versión:** 1.0  
**Fecha:** Diciembre 2025  
**Sistema:** Orion CRM 3.0

---

## 1. Información General

### 1.1 Base de Datos
- **Motor:** MySQL/MariaDB
- **Esquema Principal:** Variable `MAINBD` (configurada por empresa)
- **Esquema Admin:** Variable `BDADMIN` (tablas compartidas)

### 1.2 Tabla Principal
- **Nombre:** `productos`
- **Clave Primaria:** `prod_id` (INT, AUTO_INCREMENT)
- **Clave Única:** `prod_referencia` (VARCHAR) - Código de referencia único del producto

---

## 2. Estructura de la Tabla `productos`

### 2.1 Campos Principales

| Campo | Tipo | Descripción | Requerido | Notas |
|-------|------|-------------|-----------|-------|
| `prod_id` | INT | ID único del producto | Sí | Clave primaria, AUTO_INCREMENT |
| `prod_id_empresa` | INT | ID de la empresa (multi-tenant) | Sí | Filtro obligatorio en todas las consultas |
| `prod_referencia` | VARCHAR | Código de referencia único | Sí | **Campo clave para sincronización** |
| `prod_nombre` | VARCHAR | Nombre del producto | Sí | |
| `prod_descripcion_corta` | TEXT | Descripción corta | No | |
| `prod_descripcion_larga` | TEXT | Descripción larga/detallada | No | |
| `prod_categoria` | INT | ID de categoría (FK) | No | Referencia a `productos_categorias.catp_id` |
| `prod_grupo1` | INT | ID de grupo 1 (FK) | No | Referencia a `productos_categorias.catp_id` |
| `prod_marca` | INT | ID de marca (FK) | No | Referencia a `marcas.mar_id` |
| `prod_proveedor` | INT | ID de proveedor (FK) | No | Referencia a `proveedores.prov_id` |
| `prod_costo` | DECIMAL(10,2) | Costo del producto (COP) | No | |
| `prod_costo_dolar` | DECIMAL(10,2) | Costo en dólares | No | |
| `prod_utilidad` | DECIMAL(5,2) | Porcentaje de utilidad (0-100) | No | Ejemplo: 30.5 = 30.5% |
| `prod_precio` | DECIMAL(10,2) | Precio de lista calculado | No | Se calcula: `costo / (1 - utilidad/100)` |
| `prod_descuento1` | DECIMAL(5,2) | Descuento 1 (%) | No | |
| `prod_descuento2` | DECIMAL(5,2) | Descuento para dealers (%) | No | |
| `prod_descuento_web` | DECIMAL(5,2) | Descuento web (%) | No | |
| `prod_comision` | DECIMAL(5,2) | Comisión de vendedores (%) | No | |
| `prod_existencias` | INT | Existencias totales | No | Se sincroniza desde `productos_bodegas` |
| `prod_visible` | TINYINT | Visible en catálogo (1=Sí, 2=No) | No | Default: 2 (No visible) |
| `prod_foto` | VARCHAR | Nombre del archivo de imagen | No | Almacenado en `/files/productos/` |
| `prod_ultima_actualizacion` | DATETIME | Fecha/hora última actualización | No | Se actualiza automáticamente |
| `prod_ultima_actualizacion_usuario` | INT | ID usuario que actualizó | No | Referencia a `usuarios.usr_id` |

### 2.2 Campos Clave para Sincronización

**Campos obligatorios para crear/actualizar:**
- `prod_referencia` (único, usado como identificador externo)
- `prod_nombre`
- `prod_id_empresa`

**Campos recomendados:**
- `prod_costo`
- `prod_utilidad`
- `prod_precio`
- `prod_categoria`
- `prod_marca`
- `prod_proveedor`

---

## 3. Tablas Relacionadas

### 3.1 `productos_bodegas`
Almacena las existencias por bodega.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `prodb_id` | INT | ID único |
| `prodb_producto` | INT | FK a `productos.prod_id` |
| `prodb_bodega` | INT | FK a `bodegas.bod_id` |
| `prodb_existencias` | INT | Cantidad en esta bodega |
| `prodb_fecha_actualizacion` | DATETIME | Fecha última actualización |
| `prodb_usuario_actualizacion` | INT | FK a `usuarios.usr_id` |

**Nota:** El campo `prod_existencias` en la tabla `productos` es la suma de todas las bodegas.

### 3.2 `productos_categorias`
Categorías y grupos de productos.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `catp_id` | INT | ID único |
| `catp_nombre` | VARCHAR | Nombre de la categoría |
| `catp_id_empresa` | INT | FK a empresa |

### 3.3 `marcas`
Marcas de productos.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `mar_id` | INT | ID único |
| `mar_nombre` | VARCHAR | Nombre de la marca |
| `mar_id_empresa` | INT | FK a empresa |

### 3.4 `proveedores`
Proveedores de productos.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `prov_id` | INT | ID único |
| `prov_nombre` | VARCHAR | Nombre del proveedor |
| `prov_id_empresa` | INT | FK a empresa |

---

## 4. Consultas SQL de Referencia

### 4.1 Obtener Producto por Referencia
```sql
SELECT * FROM productos 
WHERE prod_referencia = 'REF-12345' 
AND prod_id_empresa = 1;
```

### 4.2 Obtener Producto Completo con Relaciones
```sql
SELECT 
    p.*,
    cat.catp_nombre AS categoria_nombre,
    grupo.catp_nombre AS grupo1_nombre,
    m.mar_nombre AS marca_nombre,
    prov.prov_nombre AS proveedor_nombre
FROM productos p
LEFT JOIN productos_categorias cat ON cat.catp_id = p.prod_categoria
LEFT JOIN productos_categorias grupo ON grupo.catp_id = p.prod_grupo1
LEFT JOIN marcas m ON m.mar_id = p.prod_marca
LEFT JOIN proveedores prov ON prov.prov_id = p.prod_proveedor
WHERE p.prod_referencia = 'REF-12345' 
AND p.prod_id_empresa = 1;
```

### 4.3 Obtener Existencias por Bodega
```sql
SELECT 
    pb.prodb_existencias,
    b.bod_nombre AS bodega_nombre
FROM productos_bodegas pb
INNER JOIN bodegas b ON b.bod_id = pb.prodb_bodega
WHERE pb.prodb_producto = 123
AND b.bod_id_empresa = 1;
```

### 4.4 Insertar Producto
```sql
INSERT INTO productos (
    prod_referencia,
    prod_nombre,
    prod_categoria,
    prod_grupo1,
    prod_marca,
    prod_proveedor,
    prod_costo,
    prod_utilidad,
    prod_precio,
    prod_id_empresa
) VALUES (
    'REF-12345',
    'Nombre del Producto',
    1,  -- categoria
    2,  -- grupo1
    3,  -- marca
    4,  -- proveedor
    100000.00,  -- costo
    30.00,      -- utilidad %
    142857.14,  -- precio calculado
    1           -- id_empresa
);
```

### 4.5 Actualizar Producto
```sql
UPDATE productos SET
    prod_nombre = 'Nuevo Nombre',
    prod_costo = 120000.00,
    prod_utilidad = 35.00,
    prod_precio = 184615.38,
    prod_ultima_actualizacion = NOW(),
    prod_ultima_actualizacion_usuario = 1
WHERE prod_referencia = 'REF-12345'
AND prod_id_empresa = 1;
```

---

## 5. Formato de Datos para API

### 5.1 Estructura JSON para Crear/Actualizar Producto

```json
{
    "referencia": "REF-12345",
    "nombre": "Producto Ejemplo",
    "descripcion_corta": "Descripción breve del producto",
    "descripcion_larga": "Descripción detallada del producto",
    "categoria_id": 1,
    "grupo1_id": 2,
    "marca_id": 3,
    "proveedor_id": 4,
    "costo": 100000.00,
    "costo_dolar": 25.50,
    "utilidad": 30.00,
    "precio": 142857.14,
    "descuento1": 10.00,
    "descuento2": 15.00,
    "descuento_web": 5.00,
    "comision": 5.00,
    "visible": 1,
    "id_empresa": 1
}
```

### 5.2 Estructura JSON de Respuesta

```json
{
    "success": true,
    "producto": {
        "id": 123,
        "referencia": "REF-12345",
        "nombre": "Producto Ejemplo",
        "categoria": {
            "id": 1,
            "nombre": "Categoría Ejemplo"
        },
        "marca": {
            "id": 3,
            "nombre": "Marca Ejemplo"
        },
        "costo": 100000.00,
        "precio": 142857.14,
        "existencias": 50,
        "ultima_actualizacion": "2025-12-15 10:30:00"
    }
}
```

---

## 6. Cálculo de Precio

### 6.1 Fórmula de Precio de Lista
```
Precio = Costo / (1 - (Utilidad / 100))
```

**Ejemplo:**
- Costo: $100,000
- Utilidad: 30%
- Precio = 100,000 / (1 - 0.30) = 100,000 / 0.70 = $142,857.14

### 6.2 Validaciones
- Si `utilidad` = 0 o NULL, `precio` puede ser igual a `costo`
- Si `costo` cambia o `utilidad` cambia, `precio` debe recalcularse
- Los cambios de precio se registran en `productos_historial_precios`

---

## 7. Endpoints API Esperados

### 7.0 Autenticación JWT (recomendada para Ofima → Orion)

Orion permite autenticarse con **JWT** (token con expiración) para mayor seguridad. El cliente (Ofima) puede usar Basic Auth en cada petición o, preferiblemente, obtener un token una vez y enviarlo en las siguientes peticiones.

**Endpoint de login (obtener token):**

- **URL:** `POST /usuarios/api/orion/auth.php` (ej. `https://tu-dominio.com/usuarios/api/orion/auth.php`)
- **Método:** `POST`
- **Credenciales:** Basic Auth (usuario y contraseña de `api_configuracion` para cualquier módulo con `apic_direccion = 'ofima_orion'`) **o** body JSON: `{ "usuario": "...", "password": "...", "id_empresa": 1 }`
- **Respuesta exitosa (200):**
```json
{
    "success": true,
    "message": "Token generado correctamente",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "expires_in": 3600,
    "token_type": "Bearer"
}
```
- El token expira en **12 horas** (43200 s). Tras expirar, el cliente debe volver a llamar a `auth.php` para obtener uno nuevo.
- **Uso del token:** En todos los endpoints (productos-recibir, y en el futuro clientes, inventario, pedidos), enviar el header: `Authorization: Bearer <token>`.

**Configuración en el servidor Orion:**

- En **producción** debe definirse la clave secreta para firmar los JWT. Añadir en `sensitive.php` (o donde se carguen constantes sensibles):  
  `define('API_JWT_SECRET', 'una-clave-muy-larga-y-aleatoria');`  
  Sin esta constante en entorno PROD, la generación de tokens fallará. En desarrollo se usa un valor por defecto si no está definida.

### 7.1 Contrato API Orion (Ofima → Orion) – Endpoint para recibir productos desde Ofima

Este es el contrato que **Ofima** debe usar para enviar productos a Orion (crear o actualizar). Orion identifica por `referencia`; si existe, actualiza; si no, crea.

**URL (completa):** Debe ser la URL absoluta del endpoint. Se configura en la tabla `api_configuracion` (campo `apic_url_endpoint`) para el registro con `apic_modulo = 'productos'` y `apic_direccion = 'ofima_orion'`. El instalador/administrador debe definir esta URL según el dominio del CRM.

- Ejemplo: `https://tu-dominio.com/usuarios/api/orion/productos-recibir.php`
- Ruta relativa al proyecto: `usuarios/api/orion/productos-recibir.php`

**Método:** `POST`

**Headers:**
```
Content-Type: application/json
Accept: application/json
Authorization: Bearer <token>   (recomendado; token obtenido de auth.php)
```
**O bien** (legacy):
```
Authorization: Basic <base64(usuario:contraseña)>
```

Si se envía `Authorization: Bearer <token>` válido y no expirado, se usa el `id_empresa` del token y no se requiere Basic Auth. Si no se envía Bearer o el token es inválido/expirado, se exige Basic Auth con el usuario y contraseña configurados en `api_configuracion` para productos / ofima_orion.

**Body (JSON):**

| Campo            | Tipo   | Obligatorio | Descripción |
|------------------|--------|-------------|-------------|
| `referencia`     | string | Sí          | Código único del producto (identificador para CREATE/UPDATE) |
| `nombre`         | string | Sí          | Nombre del producto |
| `id_empresa`     | int    | Recomendado | ID de empresa en Orion (default: 1 si se omite) |
| `costo`          | number | No          | Costo (≥ 0) |
| `utilidad`       | number | No          | Ver convención abajo (0–100 o 0–1) |
| `precio`         | number | No          | Precio de lista (≥ 0). Si se omite y hay costo y utilidad, Orion lo calcula |
| `categoria_id`   | int    | No          | ID categoría en Orion |
| `grupo_id`       | int    | No          | ID grupo en Orion |
| `marca_id`       | int    | No          | ID marca en Orion |
| `proveedor_id`   | int    | No          | ID proveedor en Orion |
| `descripcion_corta` | string | No       | Descripción corta |
| `descripcion_larga` | string | No       | Descripción larga |
| `descuento1`     | number | No          | Descuento 1 (%) |
| `comision`       | number | No          | Comisión (%) |
| `costo_dolar`    | number | No          | Costo en dólares |

**Convención de utilidad:** Orion almacena siempre el porcentaje en escala 0–100 (ej. 30.5 = 30,5%). Ofima puede enviar:
- **0–100** (porcentaje): ej. `30.5` → Orion guarda 30.5.
- **0–1** (factor decimal): ej. `0.305` → Orion convierte y guarda 30.5.

Si se envían `costo` y `utilidad` y no se envía `precio`, Orion calcula el precio con la fórmula: `precio = costo / (1 - utilidad/100)`.

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "message": "Producto sincronizado correctamente",
    "producto_id": 123,
    "referencia": "REF-12345",
    "operacion": "CREATE"
}
```
`operacion` puede ser `"CREATE"` o `"UPDATE"`.

**Respuesta error validación (400):**
```json
{
    "success": false,
    "message": "Campos requeridos faltantes",
    "error": "Los campos \"referencia\" y \"nombre\" son obligatorios"
}
```
Otros 400: costo negativo, utilidad fuera de rango, precio negativo (ver mensaje en `error`).

**Respuesta error autenticación (401):**
```json
{
    "success": false,
    "message": "Credenciales inválidas",
    "error": "Usuario o contraseña incorrectos"
}
```

**Respuesta error servidor (500):**
```json
{
    "success": false,
    "message": "Error del servidor",
    "error": "Descripción del error"
}
```

### 7.2 Endpoint para Enviar Productos a Ofima

**URL:** `POST https://ofima.com/api/productos` (configurable)

**Método:** POST o PUT según corresponda

**Formato:** JSON

---

## 8. Consideraciones Importantes

### 8.1 Identificación de Productos
- **Campo clave:** `prod_referencia` (debe ser único por empresa)
- Si el producto existe (misma referencia), se actualiza
- Si no existe, se crea

### 8.2 Multi-Tenant
- **Siempre** incluir `prod_id_empresa` en todas las consultas
- Cada empresa tiene sus propios productos

### 8.3 Sincronización de Existencias
- Las existencias se manejan en `productos_bodegas`
- El campo `prod_existencias` se calcula automáticamente como suma de bodegas
- Para actualizar existencias, usar el endpoint de inventario (módulo separado)

### 8.4 Historial de Precios
- Los cambios de precio se registran en `productos_historial_precios`
- Causas: 1=Costo, 2=Utilidad, 3=Ambos, 4=Otro

### 8.5 Validaciones
- `prod_referencia` no puede estar vacío
- `prod_nombre` no puede estar vacío
- `prod_id_empresa` debe existir
- Si `prod_categoria`, `prod_marca`, `prod_proveedor` se envían, deben existir en sus respectivas tablas

---

## 9. Ejemplo de Flujo de Sincronización

### 9.1 Orion → Ofima (Crear/Actualizar)
1. Usuario crea/modifica producto en Orion
2. Sistema Orion detecta el cambio (trigger o webhook)
3. Orion consume API de Ofima: `POST https://ofima.com/api/productos`
4. Ofima procesa y responde
5. Orion registra resultado en logs

### 9.2 Ofima → Orion (Crear/Actualizar)
1. Usuario crea/modifica producto en Ofima
2. Ofima consume API de Orion: `POST /api/orion/productos-recibir.php`
3. Orion verifica si existe por `prod_referencia`
4. Si existe: UPDATE
5. Si no existe: INSERT
6. Orion responde con resultado
7. Ofima registra resultado en logs

---

## 10. Manejo de Errores

### 10.1 Códigos de Error Comunes

| Código | Descripción | Solución |
|--------|-------------|----------|
| 400 | Datos inválidos | Verificar formato JSON y campos requeridos |
| 401 | No autorizado | Verificar credenciales de autenticación |
| 404 | Producto no encontrado | Verificar referencia del producto |
| 409 | Conflicto (referencia duplicada) | Verificar que la referencia sea única |
| 500 | Error del servidor | Revisar logs del servidor |

### 10.2 Reintentos
- Implementar sistema de reintentos con backoff exponencial
- Máximo 3 intentos
- Registrar errores en tabla de logs

---

## 11. Contacto y Soporte

Para dudas técnicas sobre la integración, contactar al equipo de desarrollo de Orion CRM.

**Versión del Documento:** 1.0  
**Última Actualización:** Diciembre 2025

