<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"UPDATE importaciones SET imp_fecha='" . $_POST["fecha"] . "', imp_proveedor='" . $_POST["proveedor"] . "', imp_concepto='" . $_POST["concepto"] . "', imp_responsable='" . $_SESSION["id"] . "', imp_liquidada='" . $_POST["liquidada"] . "', imp_fce='" . $_POST["fce"] . "', imp_valor_nacionalizacion='" . $_POST["nacionalizacion"] . "', imp_otros_gastos='" . $_POST["otrosCostos"] . "' WHERE imp_id='" . $_POST["id"] . "'");



//Facturas asociadas
$numero = (count($_POST["facturas"]));
if ($numero > 0) {
    mysqli_query($conexionBdPrincipal,"DELETE FROM importaciones_facturas
    WHERE impf_importacion='" . $_POST["id"] . "' AND impf_preferencia='0'");
    
    $contador = 0;
    while ($contador < $numero) {

        mysqli_query($conexionBdPrincipal,"INSERT INTO importaciones_facturas(impf_fecha, impf_importacion, impf_factura, impf_responsable)VALUES(now(), '" . $_POST["id"] . "', '" . $_POST["facturas"][$contador] . "', '" . $_SESSION["id"] . "')");
        
        $contador++;
    }
}

//Facturas costos nacionalización
$numero = (count($_POST["facturasNac"]));
if ($numero > 0) {
    mysqli_query($conexionBdPrincipal,"DELETE FROM importaciones_facturas
    WHERE impf_importacion='" . $_POST["id"] . "' AND impf_preferencia=1");
    
    $contador = 0;
    while ($contador < $numero) {

        mysqli_query($conexionBdPrincipal,"INSERT INTO importaciones_facturas(impf_fecha, impf_importacion, impf_factura, impf_responsable, impf_preferencia)VALUES(now(), '" . $_POST["id"] . "', '" . $_POST["facturasNac"][$contador] . "', '" . $_SESSION["id"] . "', 1)");
        
        $contador++;
    }
}

//Cuando se liquida una factura
if ($_POST["liquidada"]) {
    //Facturas asociadas
    $facturasValor =  mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT SUM(factura_valor) FROM importaciones_facturas
    INNER JOIN facturas ON factura_id=impf_factura
    WHERE impf_importacion='" . $_POST["id"] . "'
    "));
    


    //Cantidad de productos asociados
    $productoNum = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT SUM(czpp_cantidad) FROM cotizacion_productos
    WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_tipo=5"));
    

    $valorRepartido = ($facturasValor[0] / $productoNum[0]);

    //Consulto productos
    $productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos
    WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_tipo=5");
    

    //Actualizo el costo y las cantidades en la bodega general
    while ($prod = mysqli_fetch_array($productos)) {

        $costoProducto = ($prod['czpp_valor'] + $valorRepartido);

        mysqli_query($conexionBdPrincipal,"UPDATE productos SET prod_costo='" . $costoProducto . "' 
        WHERE prod_id='" . $prod['czpp_producto'] . "'");
        

        //En Bodega...
        $bpp = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos_bodegas WHERE prodb_producto='" . $prod['czpp_producto'] . "' AND prodb_bodega=1"));
        

        if ($bpp[0] == "") {
            mysqli_query($conexionBdPrincipal,"INSERT INTO productos_bodegas(prodb_producto, prodb_bodega, prodb_existencias, prodb_fecha_actualizacion, prodb_usuario_actualizacion)VALUES('" . $prod['czpp_producto'] . "', 1, '" . $prod['czpp_cantidad'] . "', now(), '" . $_SESSION["id"] . "')");
            
            $idInsertU = mysqli_insert_id($conexionBdPrincipal);
        } else {
            $nuevaCantidad = $bpp['prodb_existencias'] + $prod['czpp_cantidad'];

            mysqli_query($conexionBdPrincipal,"UPDATE productos_bodegas SET prodb_existencias= '" . $nuevaCantidad . "', prodb_fecha_actualizacion=now(), prodb_usuario_actualizacion='" . $_SESSION["id"] . "' WHERE prodb_producto='" . $prod['czpp_producto'] . "' AND prodb_bodega=1");
            
        }
    }
}



echo '<script type="text/javascript">window.location.href="../importacion-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();