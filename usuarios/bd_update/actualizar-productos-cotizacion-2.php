<?php
//PRODUCTOS
if(isset($_POST["producto"])){
    $numero = (count($_POST["producto"]));

    $contador = 0;
    while ($contador < $numero){

        //Se obtienen uno por uno los datos completos de los productos que vienen en el array y .
        $consulta=$conexionBdPrincipal->query("SELECT prod_id, prod_utilidad, prod_precio, prod_costo, prod_costo_dolar,
        czpp_id, czpp_producto, czpp_valor, czpp_cotizacion FROM productos
        LEFT JOIN cotizacion_productos ON czpp_producto=prod_id AND czpp_cotizacion='" . $_POST["id"] . "'
        WHERE prod_id='" . $_POST["producto"][$contador] . "'");
        $productoDatos = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        //Si cambió a pesos colombianos
        if ($_POST["moneda"] == 1) {
            $valorProducto = round(($productoDatos['czpp_valor'] * $configuracion['conf_trm_venta']), 0);
        } else {
            $valorProducto = productosPrecioListaUSD($productoDatos['prod_utilidad'], $productoDatos['prod_costo_dolar']);
        }

        $conexionBdPrincipal->query("UPDATE cotizacion_productos SET czpp_valor='" . $valorProducto . "' WHERE czpp_id='" . $productoDatos['czpp_id'] . "'");

        $contador++;
    }
}