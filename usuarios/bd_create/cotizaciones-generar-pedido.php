<?php   
require_once("../sesion.php");

$idPagina = 263;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$generoPedido = mysqli_fetch_array($conexionBdPrincipal->query("SELECT * FROM pedidos WHERE pedid_cotizacion='" . $_GET["id"] . "'"), MYSQLI_BOTH);

$consulta = $conexionBdPrincipal->query("INSERT INTO pedidos (pedid_fecha_propuesta, pedid_observaciones, pedid_cliente, pedid_fecha_vencimiento, pedid_vendedor, pedid_creador, pedid_sucursal, pedid_contacto, pedid_forma_pago, pedid_fecha_creacion, pedid_moneda, pedid_cotizacion, pedid_estado) SELECT now(), cotiz_observaciones, cotiz_cliente, cotiz_fecha_vencimiento, cotiz_vendedor, '" . $_SESSION["id"] . "', cotiz_sucursal, cotiz_contacto, cotiz_forma_pago, now(), cotiz_moneda, '" . $_GET["id"] . "', 1 
FROM cotizacion 
WHERE cotiz_id='" . $_GET["id"] . "'");
$idInsert = mysqli_insert_id($conexionBdPrincipal);


$productos = $conexionBdPrincipal->query("SELECT * FROM cotizacion_productos 
WHERE czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_COTZ."'");

while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
    if ($prod['czpp_orden'] == "") $prod['czpp_orden'] = 1;
    if ($prod['czpp_cantidad'] == "") $prod['czpp_cantidad'] = 1;

    $jsonProductosCombo = null;

    if (!empty($prod['czpp_combo'])) {
        $combos = mysqli_query($conexionBdPrincipal,"SELECT * FROM combos_productos 
        INNER JOIN productos ON prod_id=copp_producto
        INNER JOIN combos ON combo_id=copp_combo
        WHERE copp_combo='" . $prod['czpp_combo'] . "'");

        $productosComboArray = [];

        //Construir JSON con los productos
        while ($comProd = mysqli_fetch_array($combos)) {

            $subtotalLinea = $comProd['copp_cantidad'] * $comProd['copp_precio'];

            $productosComboArray[] = [
                "id_producto"              => (int)$comProd['prod_id'],
                "nombre_producto"          => $comProd['prod_nombre'],
                "existencias_actuales"     => $comProd['prod_existencias'],
                "cantidad_en_combo"        => (int)$comProd['copp_cantidad'],
                "precio_unitario_cotizado" => (float)$comProd['copp_precio'],
                "descuento_del_combo"      => (float)$comProd['combo_descuento'],
                "subtotal_linea_cotizado"  => (float)$subtotalLinea
            ];
        }

        // --- 2. Codificar el array PHP a formato JSON ---
        $jsonProductosCombo = json_encode($productosComboArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        // Manejo de errores de JSON (opcional, pero recomendado)
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Error al generar JSON para el combo $idInsert: " . json_last_error_msg());
            $jsonProductosCombo = '[]'; // O manejar el error de otra forma
        }
    }

    $conexionBdPrincipal->query("INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_servicio, czpp_combo, czpp_descuento, czpp_productos_en_combo_generar_pedido, czpp_productos_en_combo, czpp_nombre_original)VALUES('" . $idInsert . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', '" . $prod['czpp_orden'] . "', '" . $prod['czpp_cantidad'] . "', '" . $prod['czpp_impuesto'] . "', ".CZPP_TIPO_PED.", '" . $prod['czpp_servicio'] . "', '" . $prod['czpp_combo'] . "', '" . $prod['czpp_descuento'] . "', '".$jsonProductosCombo."', '" . $prod['czpp_productos_en_combo'] . "', '" . $prod['czpp_nombre_original'] . "')");

    $contador++;
}

//Marcamos la cotización como vendida
$conexionBdPrincipal->query("UPDATE cotizacion SET cotiz_vendida=1, cotiz_fecha_vendida=now() 
WHERE cotiz_id='" . $_GET["id"] . "'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../pedidos.php?busqueda=' . $idInsert . '";</script>';
exit();