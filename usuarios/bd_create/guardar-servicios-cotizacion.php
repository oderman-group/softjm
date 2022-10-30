<?php
//Servicios
if($_POST["servicio"]!=''){
    $numero = (count($_POST["servicio"]));
    if ($numero > 0) {
        $contador = 0;
        while ($contador < $numero) {
            $consulta=$conexionBdPrincipal->query("SELECT * FROM servicios WHERE serv_id='" . $_POST["servicio"][$contador] . "'");
            $productoDatos = mysqli_fetch_array($consulta, MYSQLI_BOTH);

            $valorProducto = $productoDatos['serv_precio'];
            if ($_POST["moneda"] == 2) {
                $valorProducto = round(($productoDatos['serv_precio'] / $configuracion['conf_trm_compra']), 0);
            }

            $conexionBdPrincipal->query("INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_servicio, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo)VALUES('" . $idInsert . "','" . $_POST["servicio"][$contador] . "', '" . $valorProducto . "', '" . $contador . "', 1, 19, 1)");
            $contador++;
        }
    }
}