<?php
//COMBOS
if(isset($_POST["combo"]) && $_POST["monedaActual"] != $_POST["moneda"]){
    $numero = (count($_POST["combo"]));
    $contador = 0;
    while ($contador < $numero) {

        $consultaProductoNum=$conexionBdPrincipal->query("SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_combo='" . $_POST["combo"][$contador] . "'");
        $productoNum = mysqli_fetch_array($consultaProductoNum, MYSQLI_BOTH);

        //Si cambió a pesos colombianos
        if ($_POST["moneda"] == 1) {
            $valorProducto = round(($productoNum['czpp_valor'] * $configuracion['conf_trm_venta']), 0);
        }
        //Si cambió a Dolares
        else {
            $valorProducto = round(($productoNum['czpp_valor'] / $configuracion['conf_trm_compra']), 0);
        }

        $conexionBdPrincipal->query("UPDATE cotizacion_productos SET czpp_valor='" . $valorProducto . "' WHERE czpp_id='" . $productoNum['czpp_id'] . "'");

        $contador++;
    }
}