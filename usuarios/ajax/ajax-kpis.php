<?php
include("../sesion.php");

$resultado = array(
    "estado" => 0,
    "mensaje" => 0,
    "datos" => array()
);

if (isset($_POST["opcion"]) and $_POST["opcion"] == "consultar_kpi_1_2_ventas" ) {


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
}else if (isset($_POST["opcion"]) and $_POST["opcion"] == "consultar_kpi_3_tiempo_promedio_cierre_ventas" ) {


    $e_dato = json_decode($_POST["e_datos"]);

    $sql = "
    SELECT
        f.factura_id as id,
        c.cli_id,
        c.cli_nombre AS cliente, -- Se añadió el nombre del cliente para mayor detalle
        c.cli_fecha_registro AS fecha,
        f.factura_id factura,
        f.factura_fecha_propuesta AS fecha_cierre_venta,
        f.factura_tipo,
        f.factura_estado,
        -- Calcula la duración en días para cada venta individual
        DATEDIFF(f.factura_fecha_propuesta, c.cli_fecha_registro) AS duracion_cierre_dias,
        u.usr_id AS id_vendedor,
        u.usr_nombre AS vendedor,
        sp.sucp_id AS id_sucursal,
        sp.sucp_nombre AS sucursal,
        sp.sucp_ciudad AS ciudad_sucursal,
        sp.sucp_id_empresa AS id_empresa_sucursal
    FROM
        clientes AS c
    INNER JOIN
        facturas AS f ON c.cli_id = f.factura_cliente
    INNER JOIN
        usuarios AS u ON f.factura_vendedor = u.usr_id -- Se une con la tabla de usuarios para obtener información del vendedor
    INNER JOIN
        sucursales_propias AS sp ON u.usr_sucursal = sp.sucp_id -- Se une con la tabla de sucursales para obtener información de la sucursal
    WHERE
        f.factura_tipo = 1 -- Asumiendo 1 para 'venta'
        AND f.factura_estado = 1 -- Asumiendo 1 para 'cerrada'
        AND f.factura_fecha_propuesta IS NOT NULL
        AND c.cli_fecha_registro IS NOT NULL
        AND f.factura_fecha_propuesta >= c.cli_fecha_registro;
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
}else if (isset($_POST["opcion"]) and $_POST["opcion"] == "consultar_kpi_7_numero_llamadas_enviadas_ejecutivo_prospeccion" ) {


    $e_dato = json_decode($_POST["e_datos"]);

    $sql = "
    SELECT
        u.usr_id AS id_ejecutivo,
        u.usr_nombre AS ejecutivo,
        sp.sucp_id AS id_sucursal,
        sp.sucp_nombre AS sucursal,
        sp.sucp_ciudad AS ciudad_sucursal,
        sp.sucp_id_empresa AS id_empresa_sucursal,
        cs.cseg_id AS id,
        cs.cseg_id AS seguimiento,
        cs.cseg_fecha_reporte AS fecha, -- Fecha de la llamada para agrupar por mes/año en DevExtreme
        cs.cseg_tipo, -- Tipo de seguimiento (1 para 'comercial')
        cs.cseg_forma_contacto, -- Forma de contacto (1 para 'empresa contactó al cliente')
        cs.cseg_canal, -- Canal de contacto (3 y 4 para 'llamadas')
        c.cli_id AS id_cliente,
        c.cli_nombre AS cliente
    FROM
        usuarios AS u
    INNER JOIN
        sucursales_propias AS sp ON u.usr_sucursal = sp.sucp_id
    INNER JOIN
        cliente_seguimiento AS cs ON u.usr_id = cs.cseg_usuario_encargado
    LEFT JOIN -- Se usa LEFT JOIN ya que un seguimiento no siempre podría tener un cliente asociado directamente
        clientes AS c ON cs.cseg_cliente = c.cli_id
    WHERE
        cs.cseg_tipo = 1 -- Filtra por tipo 'comercial'
        AND cs.cseg_forma_contacto = 1 -- Filtra por forma de contacto 'empresa contactó al cliente'
        AND cs.cseg_canal IN (3, 4); -- Filtra por canal 'llamadas' (canales 3 y 4);
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
