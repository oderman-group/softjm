<?php
include("../sesion.php");

//SERVICIOS
if(!empty($_POST["servicio"])){
    $numero = (count($_POST["servicio"]));
    $contador = 0;
    while ($contador < $numero) {

        $consulta=$conexionBdPrincipal->query("SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_servicio='" . $_POST["servicio"][$contador] . "'");
        $productoNum = mysqli_fetch_array($consulta, MYSQLI_BOTH);


        if ($productoNum['czpp_id'] == '') {
            $consultaProductoDatos=$conexionBdPrincipal->query("SELECT * FROM servicios WHERE serv_id='" . $_POST["servicio"][$contador] . "'");
            $productoDatos = mysqli_fetch_array($consultaProductoDatos, MYSQLI_BOTH);

            $valorProducto = $productoDatos['serv_precio'];
            if ($_POST["moneda"] == 2) {
                $valorProducto = round(($productoDatos['serv_precio'] / $configuracion['conf_trm_compra']), 0);
            }

            $conexionBdPrincipal->query("INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_servicio, czpp_cantidad, czpp_impuesto, czpp_descuento, czpp_valor, czpp_orden, czpp_tipo)VALUES('" . $_POST["id"] . "','" . $_POST["servicio"][$contador] . "', 1, 19, 0, '" . $valorProducto . "', '" . $numero . "', 1)");
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

    //ELIMINAR LOS SERVICIOS QUE YA NO ESTÁN EN LA COTIZACIÓN.
    $productosWebServicio = $conexionBdPrincipal->query("SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "'");
    while ($pWebServicio = mysqli_fetch_array($productosWebServicio, MYSQLI_BOTH)) {

        $encontrado = 0;
        $contador = 0;
        while ($contador < $numero) {

            if ($pWebServicio['czpp_servicio'] == $_POST["servicio"][$contador]) {
                $encontrado = 1;
                break;
            }

            $contador++;
        }

        if ($encontrado == 0) {
            $conexionBdPrincipal->query("DELETE FROM cotizacion_productos WHERE czpp_servicio='" . $pWebServicio['czpp_servicio'] . "' AND czpp_cotizacion='" . $_POST["id"] . "'");
        }
    }
} else {
    $conexionBdPrincipal->query("DELETE FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_combo IS NULL AND czpp_producto IS NULL");
}
?>