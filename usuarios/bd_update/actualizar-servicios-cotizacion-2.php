<?php
//SERVICIOS
if(isset($_POST["servicio"])){
    $numero = (count($_POST["servicio"]));
    $contador = 0;
    while ($contador < $numero) {

        $consulta=$conexionBdPrincipal->query("SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_servicio='" . $_POST["servicio"][$contador] . "'");
        $productoNum = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        
        //Si cambió a pesos colombianos
        if ($_POST["moneda"] == 1) {
            $valorProducto = !empty($productoNum['czpp_valor']) && !empty($configuracion['conf_trm_venta']) ? round(($productoNum['czpp_valor'] * $configuracion['conf_trm_venta']), 0) : 0;
        }
        //Si cambió a Dolares
        else {
            $valorProducto = !empty($productoNum['czpp_valor']) && !empty($configuracion['conf_trm_compra']) ? round(($productoNum['czpp_valor'] / $configuracion['conf_trm_compra']), 0) : 0;
        }

        $conexionBdPrincipal->query("UPDATE cotizacion_productos SET czpp_valor='" . $valorProducto . "' WHERE czpp_id='" . $productoNum['czpp_id'] . "'");

        $contador++;
    }
}