<?php
//COMBOS
if($_POST["combo"]!=''){
    $numero = (count($_POST["combo"]));
    if ($numero > 0) {

        $consulta     = $conexionBdPrincipal->query("SELECT * FROM clientes WHERE cli_id='".$_POST["cliente"]."'");
        $datosCliente = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        $contador = 0;
        while ($contador < $numero) {

            $datosCombos = $conexionBdPrincipal->query("SELECT ROUND((SUM(copp_cantidad)*copp_precio),0) as subtotalProducto, combo_descuento, combo_descuento_dealer, copp_producto 
            FROM combos
            INNER JOIN combos_productos ON copp_combo=combo_id
            INNER JOIN productos ON prod_id=copp_producto
            WHERE combo_id='" . $_POST["combo"][$contador] . "'
            GROUP BY copp_producto
            ");
            $precioCombo = 0;
            $dctoCombo = 0;
            $productosEnCombo = "";

            while ($dCombos = mysqli_fetch_array($datosCombos, MYSQLI_BOTH)) {
                $precioCombo += $dCombos['subtotalProducto'];
                $dctoCombo = $dCombos['combo_descuento'];

                $dctoComboDealer = $dCombos['combo_descuento_dealer'];
                $productosEnCombo .= $dCombos['copp_producto'] .",";
            }

            $productosEnCombo = substr($productosEnCombo, 0, -1);

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
            

            

            $consultaProduto=$conexionBdPrincipal->query("SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_combo='" . $_POST["combo"][$contador] . "'");
            $productoNum = mysqli_fetch_array($consultaProduto, MYSQLI_BOTH);


            if (empty($productoNum['czpp_id'])) {
                $consultaCombo= $conexionBdPrincipal->query("SELECT * FROM combos WHERE combo_id='" . $_POST["combo"][$contador] . "'");
                $productoDatos = mysqli_fetch_array($consultaCombo, MYSQLI_BOTH);

                $valorProducto = !empty($precioCombo) ? $precioCombo : 0;
                if ($_POST["moneda"] == 2) {
                    $valorProducto = !empty($precioCombo) && !empty($configuracion['conf_trm_compra']) ? round(($precioCombo / $configuracion['conf_trm_compra']), 0) : 0;
                }

                $conexionBdPrincipal->query("INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_combo, czpp_cantidad, czpp_impuesto, czpp_descuento, czpp_valor, czpp_orden, czpp_tipo, czpp_nombre_original, czpp_descuento_maximo_original, czpp_productos_en_combo, czpp_precio_original)VALUES('" . $idInsert . "','" . $_POST["combo"][$contador] . "', 1, 19, 0, '" . $valorProducto . "', '" . $numero . "', ".CZPP_TIPO_COTZ.", '".$productoDatos['combo_nombre']."', '".$productoDatos['combo_descuento_maximo']."', '".$productosEnCombo."', '".$valorProducto."')");
            }

            $contador++;
        }
    }
}