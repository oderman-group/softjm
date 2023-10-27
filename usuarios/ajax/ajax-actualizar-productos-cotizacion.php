<?php
include("../sesion.php");

if (isset($_POST["producto"]) && count($_POST["producto"]) > 0) {
    $numero = count($_POST["producto"]);

    $contador = 0;
    while ($contador < $numero):

        //Se obtienen uno por uno los datos completos de los productos que vienen en el array y .
        $consulta=$conexionBdPrincipal->query("SELECT prod_id, prod_utilidad, prod_precio, prod_costo, prod_costo_dolar,
        czpp_id, czpp_producto, czpp_valor, czpp_cotizacion 
                FROM productos
                LEFT JOIN cotizacion_productos ON czpp_producto=prod_id AND czpp_cotizacion='" . $_POST["id"] . "'
                WHERE prod_id='" . $_POST["producto"][$contador] . "'");
        $productoDatos = mysqli_fetch_array($consulta, MYSQLI_BOTH);


        /* 
        * Si el producto NO está asociado a la cotización.
        */
        if ($productoDatos['czpp_id'] == '') {

            //Para pesos colombianos
            $valorProducto = $productoDatos['prod_precio'];
            
            //Si la cotización ya está en USD
            if ($_POST["moneda"] == 2) {
                $valorProducto = productosPrecioListaUSD($productoDatos['prod_utilidad'], $productoDatos['prod_costo_dolar']);
            }

            $conexionBdPrincipal->query("INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_cantidad, czpp_impuesto, czpp_descuento, czpp_valor, czpp_orden, czpp_tipo, czpp_costo, czpp_utilidad_porcentaje)VALUES('" . $_POST["id"] . "','" . $_POST["producto"][$contador] . "', 1, 19, 0, '" . $valorProducto . "', '" . $numero . "', 1, '".$productoDatos['prod_costo']."', '".$productoDatos['prod_utilidad']."')");

            } else {
                if ($_POST["monedaActual"] != $_POST["moneda"]) {
                    
                    //Si cambió a pesos colombianos
                    if ($_POST["moneda"] == 1) {
                        $valorProducto = round(($productoDatos['czpp_valor'] * $configuracion['conf_trm_venta']), 0);
                    }
                    //Si cambió a Dolares
                    else {
                        
                        $valorProducto = productosPrecioListaUSD($productoDatos['prod_utilidad'], $productoDatos['prod_costo_dolar']);
                    }

                    $conexionBdPrincipal->query("UPDATE cotizacion_productos SET czpp_valor='" . $valorProducto . "' WHERE czpp_id='" . $productoDatos['czpp_id'] . "'");
                }
            }

            $contador++;
    endwhile;


    // ELIMINAR LOS QUE YA NO ESTÁN EN LA COTIZACIÓN.
    $productosEnCotizacion = $conexionBdPrincipal->query("SELECT * FROM cotizacion_productos 
    WHERE czpp_cotizacion='" . $_POST["id"] . "'");
    
    $productosCotizacion = [];
    
    while ($pec = mysqli_fetch_array($productosEnCotizacion, MYSQLI_BOTH)) {
        $productosCotizacion[] = $pec['czpp_producto'];
    }
    
    // Encuentra los productos que ya no están en la cotización
    $productosEliminados = array_diff($productosCotizacion, $_POST["producto"]);
    
    foreach ($productosEliminados as $productoEliminado) {
        $conexionBdPrincipal->query("DELETE FROM cotizacion_productos 
        WHERE czpp_producto='" . $productoEliminado . "' AND czpp_cotizacion='" . $_POST["id"] . "'");
    }
} else {
    // Elimina todos los productos de la cotización cuando no se proporcionan productos
    $conexionBdPrincipal->query("DELETE FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_servicio IS NULL AND czpp_combo IS NULL");
}
?>