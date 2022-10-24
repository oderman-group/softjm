<?php
//COMBOS
    $numero = (count($_POST["combo"]));
    if ($numero > 0) {
        $contador = 0;
        while ($contador < $numero) {

            $datosCombos = $conexionBdPrincipal->query("SELECT ROUND((SUM(copp_cantidad)*prod_precio),0), combo_descuento, combo_descuento_dealer FROM combos
            INNER JOIN combos_productos ON copp_combo=combo_id
            INNER JOIN productos ON prod_id=copp_producto
            WHERE combo_id='" . $_POST["combo"][$contador] . "'
            GROUP BY copp_producto
            ");
            $precioCombo = 0;
            $dctoCombo = 0;
            while ($dCombos = mysqli_fetch_array($datosCombos, MYSQLI_BOTH)) {
                $precioCombo += $dCombos[0];
                $dctoCombo = $dCombos[1];

                $dctoComboDealer = $dCombos[2];
            }

            //Si el cliente es DEALER
            if($datosCliente['cli_categoria'] == 3){
                if ($dctoComboDealer > 0) {
                    $precioCombo = round($precioCombo - ($precioCombo * ($dctoComboDealer / 100)), 0);
                }
            }else{
                if ($dctoCombo > 0) {
                    $precioCombo = round($precioCombo - ($precioCombo * ($dctoCombo / 100)), 0);
                }
            }
            

            

            $consultaProduto=$conexionBdPrincipal->query("SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_combo='" . $_POST["combo"][$contador] . "'");
            $productoNum = mysqli_fetch_array($consultaProduto, MYSQLI_BOTH);


            if ($productoNum['czpp_id'] == '') {
                $consultaCombo= $conexionBdPrincipal->query("SELECT * FROM combos WHERE combo_id='" . $_POST["combo"][$contador] . "'");
                $productoDatos = mysqli_fetch_array($consultaCombo, MYSQLI_BOTH);

                $valorProducto = $precioCombo;
                if ($_POST["moneda"] == 2) {
                    $valorProducto = round(($precioCombo / $configu['conf_trm_compra']), 0);
                }

                $conexionBdPrincipal->query("INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_combo, czpp_cantidad, czpp_impuesto, czpp_descuento, czpp_valor, czpp_orden, czpp_tipo)VALUES('" . $idInsert . "','" . $_POST["combo"][$contador] . "', 1, 19, 0, '" . $valorProducto . "', '" . $numero . "', 1)");
            }

            $contador++;
        }
    }