<?php
//Productos
if($_POST["producto"]!=''){
    $numero = (count($_POST["producto"]));

    if ($numero > 0):
        $contador = 0;
        while ($contador < $numero):

            $consulta=$conexionBdPrincipal->query("SELECT prod_id, prod_utilidad, prod_precio, prod_costo, prod_costo_dolar FROM productos 
            WHERE prod_id='" . $_POST["producto"][$contador] . "'");
            $productoDatos = mysqli_fetch_array($consulta, MYSQLI_BOTH);

            $valorProducto = !empty($productoDatos['prod_precio']) ? $productoDatos['prod_precio'] : 0;

            if ($_POST["moneda"] == 2) {
                $valorProducto = !empty($productoDatos['prod_utilidad']) && !empty($productoDatos['prod_costo_dolar']) ? productosPrecioListaUSD($productoDatos['prod_utilidad'], $productoDatos['prod_costo_dolar']) : 0;
            }

            $conexionBdPrincipal->query("INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_costo, czpp_utilidad_porcentaje)VALUES('" . $idInsert . "','" . $_POST["producto"][$contador] . "', '" . $valorProducto . "', '" . $numero . "', 1, 19, 1, '".$productoDatos['prod_costo']."', '".$productoDatos['prod_utilidad']."')");

            $contador++;

        endwhile;

    endif;
}