# Implementación API Integración Orion-Ofima - Módulo Productos

## Resumen

Se ha implementado la integración API bidireccional para el módulo de **Productos** entre Orion CRM y Ofima ERP, según el plan de trabajo establecido.

## Archivos Creados

### 1. Base de Datos
- **`sql/api_integracion_tablas.sql`**
  - Tabla `api_configuracion`: Configuración de endpoints y credenciales
  - Tabla `api_sincronizaciones`: Logs de todas las sincronizaciones
  - Tabla `api_mapeo_campos`: Mapeo de campos entre sistemas
  - Datos iniciales de ejemplo

### 2. Clases PHP

#### **`usuarios/class/ApiOfimaClient.php`**
Clase para consumir APIs de Ofima desde Orion.
- Métodos principales:
  - `sincronizarProducto()`: Sincroniza producto a Ofima
  - `sincronizarCliente()`: Sincroniza cliente a Ofima (preparado)
  - `sincronizarPedido()`: Sincroniza pedido a Ofima (preparado)
- Funcionalidades:
  - Autenticación HTTP Basic
  - Mapeo automático de campos
  - Registro de sincronizaciones
  - Manejo de errores

#### **`usuarios/class/ApiOrionService.php`**
Clase para procesar datos recibidos desde Ofima.
- Métodos principales:
  - `recibirProductoDeOfima()`: Procesa producto recibido
  - `verificarAutenticacion()`: Valida credenciales
  - `crearProducto()`: Crea nuevo producto
  - `actualizarProducto()`: Actualiza producto existente
- Funcionalidades:
  - Mapeo inverso de campos (Ofima -> Orion)
  - Búsqueda por referencia
  - Cálculo automático de precios
  - Registro en historial de precios

### 3. Endpoints API

#### **`usuarios/api/orion/productos-recibir.php`**
Endpoint REST para recibir productos desde Ofima.
- Método: POST
- Autenticación: HTTP Basic Auth
- Formato: JSON
- Funcionalidades:
  - Validación de datos
  - Verificación de autenticación
  - Procesamiento asíncrono
  - Respuestas JSON estructuradas

### 4. Modificaciones a Archivos Existentes

#### **`usuarios/class/Producto.php`**
- Agregado método `sincronizarConOfima()`:
  - Verifica si la sincronización está activa
  - Obtiene datos completos del producto
  - Llama a ApiOfimaClient para sincronizar

#### **`usuarios/bd_create/productos-guardar.php`**
- Agregada llamada a sincronización después de crear producto
- Manejo de errores sin interrumpir el flujo principal

#### **`usuarios/bd_update/productos-actualizar.php`**
- Agregada llamada a sincronización después de actualizar producto
- Manejo de errores sin interrumpir el flujo principal

### 5. Documentación

#### **`usuarios/api/README.md`**
- Guía de instalación
- Configuración de credenciales
- Ejemplos de uso
- Troubleshooting

#### **`DOCUMENTACION_TECNICA_PRODUCTOS_API.md`**
- Documentación técnica completa
- Estructura de base de datos
- Formato de datos
- Ejemplos de consultas SQL

## Flujo de Sincronización

### Orion → Ofima

1. Usuario crea/actualiza producto en Orion
2. `productos-guardar.php` o `productos-actualizar.php` ejecuta
3. Se llama a `Producto::sincronizarConOfima()`
4. Se obtiene configuración desde `api_configuracion`
5. Se mapean campos usando `api_mapeo_campos`
6. Se envía petición HTTP POST a Ofima
7. Se registra resultado en `api_sincronizaciones`

### Ofima → Orion

1. Usuario crea/actualiza producto en Ofima
2. Ofima hace POST a `/usuarios/api/orion/productos-recibir.php`
3. Se verifica autenticación HTTP Basic
4. Se mapean campos de Ofima a Orion
5. Se busca producto por `prod_referencia`
6. Si existe: UPDATE, si no: INSERT
7. Se registra resultado en `api_sincronizaciones`

## Configuración Requerida

### 1. Ejecutar Script SQL
```sql
SOURCE sql/api_integracion_tablas.sql;
```

### 2. Configurar Credenciales
```sql
UPDATE api_configuracion 
SET apic_url_endpoint = 'https://ofima.com/api/productos',
    apic_usuario = 'usuario_ofima',
    apic_password = 'base64_encode(password)',
    apic_activo = 1
WHERE apic_modulo = 'productos' 
AND apic_direccion = 'orion_ofima';
```

### 3. Verificar Mapeo de Campos
Los mapeos por defecto ya están en el script SQL. Se pueden ajustar según necesidad.

## Pruebas

### Probar Sincronización Orion → Ofima

1. Crear o editar un producto en Orion
2. Verificar en `api_sincronizaciones` que se registró
3. Verificar en Ofima que el producto llegó

### Probar Sincronización Ofima → Orion

1. Desde Ofima, hacer POST a:
   ```
   POST /usuarios/api/orion/productos-recibir.php
   Authorization: Basic base64(usuario:password)
   Content-Type: application/json
   
   {
       "referencia": "TEST-001",
       "nombre": "Producto de Prueba",
       "costo": 100000,
       "utilidad": 30,
       "id_empresa": 1
   }
   ```

2. Verificar que el producto se creó/actualizó en Orion
3. Verificar en `api_sincronizaciones` el registro

## Estado de Implementación

### ✅ Completado
- [x] Tablas de base de datos
- [x] Clase ApiOfimaClient
- [x] Clase ApiOrionService
- [x] Endpoint productos-recibir.php
- [x] Método sincronizarConOfima en Producto
- [x] Integración en productos-guardar.php
- [x] Integración en productos-actualizar.php
- [x] Documentación técnica
- [x] README con instrucciones

### ⏳ Pendiente (Siguientes Módulos)
- [ ] Módulo Clientes (bidireccional)
- [ ] Módulo Inventario (Ofima → Orion)
- [ ] Módulo Pedidos (Orion → Ofima)
- [ ] Sistema de reintentos automáticos
- [ ] Interfaz de administración de logs
- [ ] Mejoras de seguridad (encriptación real)

## Notas Importantes

1. **Contraseñas**: Actualmente se usa base64_encode. Se debe implementar encriptación real en producción.

2. **Manejo de Errores**: Las sincronizaciones no bloquean el flujo principal. Los errores se registran en logs.

3. **Multi-Tenant**: Todas las consultas incluyen `prod_id_empresa` para soportar múltiples empresas.

4. **Referencia Única**: El campo `prod_referencia` es la clave para identificar productos entre sistemas.

5. **Cálculo de Precios**: Si no viene el precio pero hay costo y utilidad, se calcula automáticamente.

## Próximos Pasos

1. Probar la integración con Ofima en ambiente de desarrollo
2. Ajustar mapeo de campos según necesidades reales
3. Implementar módulos restantes (Clientes, Inventario, Pedidos)
4. Crear interfaz de administración para ver logs
5. Implementar sistema de reintentos para sincronizaciones fallidas

