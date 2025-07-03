<?php
include("../sesion.php");

$resultado = array(
    "estado" => 0,
    "mensaje" => 0,
    "datos" => array()
);

if (isset($_POST["opcion"]) and $_POST["opcion"] == "consultar_kpi_ventas" ) {


    $e_dato = json_decode($_POST["e_datos"]);

    $sql = "
    SELECT
        fac.factura_id as id,
        fac.factura_fecha_creacion as fecha,
        us.usr_nombre as vendedor,
        sp.sucp_nombre as sucursal,
        cli.cli_nombre as cliente,
        fac.factura_id factura,
        ROUND(SUM(cp.czpp_cantidad * cp.czpp_valor),2) AS total
    FROM
        facturas fac
    INNER JOIN
        cotizacion_productos cp ON cp.czpp_cotizacion = fac.factura_id
        AND cp.czpp_tipo = 4
    INNER JOIN 
        clientes cli ON cli.cli_id=fac.factura_cliente
    INNER JOIN 
        usuarios us ON us.usr_id=fac.factura_vendedor
    INNER JOIN 
        sucursales_propias sp ON sp.sucp_id=us.usr_sucursal
    WHERE
        fac.factura_tipo = 1
    AND fac.factura_id_empresa = 1
    GROUP BY
        fac.factura_id,
        fac.factura_fecha_creacion,
        us.usr_nombre,
        sp.sucp_nombre,
        cli.cli_nombre
    ORDER BY
        fac.factura_id DESC
    ;
    ";
    $result = mysqli_query($conexionBdPrincipal, $sql);

    $results = [];
    if($result->num_rows > 0){
        
        $i=0;
        while($fila = mysqli_fetch_assoc($result)) {                    
            $datos[$i] = $fila;
            $i ++;
        }              
        
        $resultado["estado"] = "ok";
        $resultado["mensaje"] = "Listado de ventas para kpi" ;
        $resultado["datos"] = $datos; 
        
    }else {
        $resultado["estado"]= "ko";
        $resultado["mensaje"]= "No hay datos para mostrar" ;
    }

    echo json_encode($resultado,512);
}
?>
