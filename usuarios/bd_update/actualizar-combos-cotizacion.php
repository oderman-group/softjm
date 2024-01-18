<?php
//COMBOS
if(isset($_POST["combo"])){
    $numero = (count($_POST["combo"]));
    $contador = 0;
    while ($contador < $numero) {

        $consulta=$conexionBdPrincipal->query("SELECT ROUND((SUM(copp_cantidad)*prod_precio),0), combo_descuento, combo_descuento_dealer FROM combos
        INNER JOIN combos_productos ON copp_combo=combo_id
        INNER JOIN productos ON prod_id=copp_producto
        WHERE combo_id='" . $_POST["combo"][$contador] . "'
        GROUP BY copp_producto
        ");
        $precioCombo = 0;
        $dctoCombo = 0;
        while ($dCombos = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
            $precioCombo += $dCombos[0];
            $dctoCombo = $dCombos[1];

            $dctoComboDealer = $dCombos[2];
        }


        //Si el cliente es DEALER
        if($datosCliente['cli_categoria'] == CLI_CATEGORIA_DEALER){
            if ($dctoComboDealer > 0) {
                $precioCombo = round($precioCombo - ($precioCombo * ($dctoComboDealer / 100)), 0);
            }
        }else{
            if ($dctoCombo > 0) {
                $precioCombo = round($precioCombo - ($precioCombo * ($dctoCombo / 100)), 0);
            }
        }

        $consultaProductoNum=$conexionBdPrincipal->query("SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_combo='" . $_POST["combo"][$contador] . "'");
        $productoNum = mysqli_fetch_array($consultaProductoNum, MYSQLI_BOTH);


        if ($productoNum['czpp_id'] == '') {
            $consultaProductoDatos=$conexionBdPrincipal->query("SELECT * FROM combos WHERE combo_id='" . $_POST["combo"][$contador] . "'");
            $productoDatos = mysqli_fetch_array($consultaProductoDatos, MYSQLI_BOTH);

            $valorProducto = $precioCombo;
            if ($_POST["moneda"] == 2) {
                $valorProducto = round(($precioCombo / $configuracion['conf_trm_compra']), 0);
            }

            $conexionBdPrincipal->query("INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_combo, czpp_cantidad, czpp_impuesto, czpp_descuento, czpp_valor, czpp_orden, czpp_tipo)VALUES('" . $_POST["id"] . "','" . $_POST["combo"][$contador] . "', 1, 19, 0, '" . $valorProducto . "', '" . $numero . "', 1)");
        } else {
            if ($_POST["monedaActual"] != $_POST["moneda"]) {
                //Si cambió a pesos colombianos
                if ($_POST["moneda"] == 1) {
                    $valorProducto = round(($productoNum['czpp_valor'] * $configuracion['conf_trm_venta']), 0);
                }
                //Si cambió a Dolares
                else {
                    $valorProducto = round(($productoNum['czpp_valor'] / $configuracion['conf_trm_compra']), 0);
                }

                $conexionBdPrincipal->query("UPDATE cotizacion_productos SET czpp_valor='" . $valorProducto . "' WHERE czpp_id='" . $productoNum['czpp_id'] . "'");
            }
        }

        $contador++;
    }

    //ELIMINAR LOS COMBOS QUE YA NO ESTÁN EN LA COTIZACIÓN.
    $productosWebCombos = $conexionBdPrincipal->query("SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "'");
    while ($pWebCombos = mysqli_fetch_array($productosWebCombos, MYSQLI_BOTH)) {

        $encontrado = 0;
        $contador = 0;
        while ($contador < $numero) {

            if ($pWebCombos['czpp_combo'] == $_POST["combo"][$contador]) {
                $encontrado = 1;
                break;
            }

            $contador++;
        }

        if ($encontrado == 0) {
            $conexionBdPrincipal->query("DELETE FROM cotizacion_productos WHERE czpp_combo='" . $pWebCombos['czpp_combo'] . "' AND czpp_cotizacion='" . $_POST["id"] . "'");
        }
    }
} else {
    $conexionBdPrincipal->query("DELETE FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_servicio IS NULL AND czpp_producto IS NULL");
}