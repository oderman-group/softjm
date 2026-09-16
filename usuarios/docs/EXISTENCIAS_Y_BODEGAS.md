# Existencias de productos y bodegas en Orion

## Cómo está actualmente

### Dos niveles de existencias

| Dónde | Qué es |
|-------|--------|
| **`productos_bodegas`** (prodb_existencias) | **Origen real**: cantidad del producto en cada bodega. Una fila por cada par (producto, bodega). |
| **`productos`** (prod_existencias) | **Total calculado**: suma de todas las bodegas para ese producto. Se usa en listados, cotizaciones y reportes. |

La idea es: **las cantidades se guardan por bodega; el total del producto es la suma**.

---

### Dónde se modifican existencias por bodega

1. **Bodegas → Productos** (`bodegas-productos.php`, `bodegas-productos-agregar.php`):  
   Se crea o edita la relación producto–bodega y se pone `prodb_existencias`.  
   Al guardar se llama `Producto::sincronizarExistenciasConBodegas($productoId)` → se recalcula `prod_existencias` (suma de bodegas).

2. **Importar existencias** (`bodegas-importar-existencias.php`):  
   Actualiza `productos_bodegas` y luego llama a `sincronizarExistenciasConBodegas` por cada producto.

3. **AJAX desde la grilla de bodegas** (`ajax-bodegas-existencias.php`):  
   Actualiza `prodb_existencias` por `prodb_id` y luego `sincronizarExistenciasConBodegas`.

4. **API Inventario (Ofima → Orion)** (`inventario-recibir.php`):  
   Inserta/actualiza `productos_bodegas` y llama a `sincronizarExistenciasConBodegas`.

En todos estos casos, **primero se toca `productos_bodegas` y después se actualiza el total del producto**.

---

### Dónde se descuentan existencias (remisión)

- **Generar remisión** (`pedidos-generar-remision.php`, `pedidos-generar-remision-v2.php`):  
  1. Por cada ítem (y productos de combos) se llama a  
     `Producto::sacarExistenciasProductoMultiBodega($productoId, $cantidad, $conexion, true)`.  
  2. Esa función **descuenta de `productos_bodegas`** (por orden de `prodb_id`, hasta completar la cantidad).  
  3. Si el descuento fue bien, se llama a  
     `Producto::sincronizarExistenciasConBodegas($productoId)`  
     para volver a calcular `prod_existencias` desde las bodegas.

Así, **al remitir solo se mueve stock en bodegas y luego se actualiza el total del producto**.

---

### Resumen del flujo actual

- **Entrada de stock**: se escribe en `productos_bodegas` (por bodega) → después se ejecuta `sincronizarExistenciasConBodegas` → `prod_existencias` = suma de bodegas.
- **Salida de stock (remisión)**: se descuenta en `productos_bodegas` con `sacarExistenciasProductoMultiBodega` → después se ejecuta `sincronizarExistenciasConBodegas` → `prod_existencias` vuelve a ser la suma.

En la pantalla de edición de producto, el campo “Existencias” es **solo lectura**: muestra `prod_existencias` (el total) y no se debe editar ahí; las existencias se manejan por bodega.

---

## Cómo debería ser (y qué se ajustó)

- **Origen de la verdad**: solo `productos_bodegas`. Toda entrada o salida de stock debe tocar `prodb_existencias`.
- **Total del producto**: `prod_existencias` debe ser **siempre** el resultado de sumar las bodegas, nunca un número escrito a mano en `productos`.
- Por eso, al **guardar el producto** (editar nombre, precio, etc.) no se debe volver a guardar `prod_existencias` desde el formulario; en su lugar se debe **recalcular** con `sincronizarExistenciasConBodegas` para que no quede desincronizado.

En `productos-actualizar.php` se cambió para que ya no use el valor del formulario en `prod_existencias`, sino que después de actualizar el producto se llame a `sincronizarExistenciasConBodegas` y el total quede siempre alineado con las bodegas.

---

## Política "Solo Ofima" para entradas de inventario

Cuando la integración con Ofima está activa, las **entradas** de existencias (crear/editar cantidades por bodega) pueden restringirse para que **solo Ofima** las haga vía API.

- **Configuración:** tabla `api_inventario_config`. Por empresa: `apin_solo_ofima_entrada = 1` activa la política.
- **Efecto:** en el CRM se bloquean:
  - Guardar/editar existencias en "Bodegas por productos" (formulario y grilla).
  - Importar existencias desde Excel.
  - El botón "Agregar nuevo" y "Actualizar desde excel" se ocultan; las existencias se muestran solo lectura.
- **Siguen permitidas:** las **salidas** por remisión (descuento con `sacarExistenciasProductoMultiBodega` y `sincronizarExistenciasConBodegas`).

Para activar: `UPDATE api_inventario_config SET apin_solo_ofima_entrada = 1 WHERE apin_id_empresa = 1;`

---

## Sincronización de bodegas (Ofima → Orion)

Para que Orion conozca las bodegas que Ofima crea o modifica:

- **Endpoint:** `POST /usuarios/api/orion/bodegas-recibir.php` (JWT o Basic Auth).
- **Body:** `{ "referencia": "BOD-01", "nombre": "Bodega Principal", "ciudad": 1 }`.  
  `referencia` = código de la bodega en Ofima (obligatorio).
- **Requisito en BD:** la tabla `bodegas` debe tener la columna `bod_referencia` (script en `sql/api_bodegas_bod_referencia.sql`).
- Cuando Ofima crea o actualiza una bodega, llama a este endpoint y Orion inserta o actualiza por `bod_referencia` + empresa.

Así, el inventario que Ofima envía por `inventario-recibir.php` puede usar `bodega_id` de Orion (los mismos IDs si se crearon antes por este endpoint, o creados manualmente con la misma referencia).

---

## Resumen en una frase

**Se cargan o descuentan existencias en `productos_bodegas` (por bodega); el sistema actualiza solo `prod_existencias` con la suma mediante `sincronizarExistenciasConBodegas`.**
