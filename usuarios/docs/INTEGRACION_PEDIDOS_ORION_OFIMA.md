# Integración Pedidos (Orion → Ofima)

Flujo **unidireccional**: cuando se crea o modifica un pedido en Orion, Orion llama a la API de Ofima para crear o actualizar ese pedido en el ERP.

## Validación de facturación

- **No se permite modificar en Ofima** un pedido que ya esté facturado.
- Un pedido se considera **facturado** si existe una remisión generada desde ese pedido y esa remisión tiene una factura asociada (`remisionbdg.remi_pedido` → `facturas.factura_remision`).
- En **UPDATE**, si el pedido está facturado, la sincronización retorna error y no se envía la petición a Ofima. El cambio en Orion (ej. timeline) sí se guarda.

## Dónde se dispara la sincronización

1. **Al crear el pedido** (`bd_create/cotizaciones-generar-pedido.php`): después de insertar el pedido y sus ítems (desde la cotización), se llama a `ApiOfimaClient::sincronizarPedido($pedidId, 'CREATE')`. Si Ofima falla, el pedido en Orion se mantiene y el error se registra en `api_sincronizaciones`.
2. **Al actualizar el pedido** (`bd_update/pedido-timelina-actualizar.php`): después de actualizar fecha/estado/empresa de envío/código de seguimiento, se llama a `sincronizarPedido($pedidId, 'UPDATE')`. Si el pedido está facturado, la sync retorna error y se registra en logs.

## Contrato del payload (Orion → Ofima)

Orion envía **POST** (crear) o **PUT** (actualizar) con cuerpo JSON:

```json
{
  "numero": "123",
  "cliente_identificacion": "900123456-1",
  "cliente_id": 5,
  "fecha": "2025-02-26",
  "observaciones": "Texto opcional",
  "items": [
    {
      "referencia": "COD001",
      "producto_id": 10,
      "cantidad": 2,
      "valor": 15000.00,
      "descuento": 0,
      "impuesto": 19
    }
  ],
  "id_empresa": 1
}
```

- **numero**: `pedid_id` de Orion (identificador del pedido).
- **cliente_identificacion**: NIT/documento del cliente (`clientes.cli_usuario`).
- **fecha**: `pedid_fecha_propuesta` o `pedid_fecha_creacion`.
- **items**: solo ítems que son **productos**. Cada ítem incluye `referencia` (código del producto en Orion), `producto_id`, `cantidad`, `valor`, `descuento`, `impuesto`. **Los combos se desglosan en sus productos**: Ofima recibe únicamente líneas de producto, nunca líneas de combo.

Los ítems se obtienen de `cotizacion_productos` con `czpp_cotizacion = pedid_id` y `czpp_tipo = CZPP_TIPO_PED`. Las filas que son productos sueltos (`czpp_producto` no nulo y `czpp_combo` nulo) se envían tal cual. Las filas que son combos (`czpp_combo` no nulo) se expanden usando el JSON `czpp_productos_en_combo_generar_pedido` (o, si no existe, la tabla `combos_productos`), de modo que cada producto del combo se envía como una línea con su referencia, cantidad (cantidad en combo × cantidad de combos), valor unitario (con descuento del combo aplicado) y el mismo impuesto/descuento de la línea del combo.

## Configuración

- **api_configuracion**: módulo `pedidos`, dirección `orion_ofima`. `apic_url_endpoint` = URL que Ofima expone para recibir pedidos (POST/PUT). Mismas credenciales Basic Auth que el resto de módulos Orion → Ofima.
- **Logs**: cada envío (éxito o error) se registra en `api_sincronizaciones` (módulo `pedidos`, dirección `orion_ofima`).

## Mock para pruebas

- **`usuarios/api/mock/ofima-pedidos-recibir.php`**: recibe POST/PUT, escribe el payload en `log-ofima-pedidos.txt` y responde 200. Para pruebas locales, en `api_configuracion` para pedidos orion_ofima se puede poner esta URL como `apic_url_endpoint`.
