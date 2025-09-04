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
}else if (isset($_POST["opcion"]) and $_POST["opcion"] == "consultar_kpi_4_cumplimiento_cuota_comercial" ) {


    $e_dato = json_decode($_POST["e_datos"]);

    $sql = "
    SELECT
        f.factura_id as id,
        sucu.sucp_nombre as sucursal,
        f.factura_vendedor,
        usr.usr_nombre AS vendedor,
        f.factura_id factura,
        f.factura_fecha_creacion AS fecha,        
        COUNT(f.factura_id) ventas,
        um.um_meta AS meta_ventas,
        ROUND((COUNT(f.factura_id)/um.um_meta)*100,2) tasa       
    FROM
        facturas f
    JOIN
        usuarios usr ON usr.usr_id = f.factura_vendedor
    join sucursales_propias sucu on sucu.sucp_id=usr.usr_sucursal
    LEFT JOIN
        usuarios_metas um ON um.um_usuario = usr.usr_id
        AND um.um_tipo_meta = 'NUMERO_VENTA' -- Se identifica la meta de ventas
        AND um.um_year = YEAR(f.factura_fecha_creacion)
        AND um.um_mes = MONTH(f.factura_fecha_creacion)
    where
        f.factura_tipo = 1 AND f.factura_estado = 1 AND um.um_meta IS NOT NULL
    GROUP BY usr.usr_sucursal, f.factura_vendedor , DATE_FORMAT(f.factura_fecha_creacion,'%Y-%m')
    ORDER BY
        f.factura_fecha_creacion DESC;
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
}else if (isset($_POST["opcion"]) and $_POST["opcion"] == "consultar_kpi_5_tasa_conversión_prospecto_cliente" ) {


    $e_dato = json_decode($_POST["e_datos"]);

    $sql = "
        SELECT
            c.cli_id id,
            u.usr_sucursal,
            s.sucp_nombre sucursal,  
            u.usr_id,  
            u.usr_nombre vendedor,    
            DATE_FORMAT(c.cli_fecha_registro,'%Y-%m') periodo,
            c.cli_fecha_registro fecha,
            c.cli_fecha_ingreso,
            COUNT(c.cli_id) prospectos,
            IFNULL(p.clientes,0) clientes,
            ROUND(if(IFNULL(p.clientes,0)> 0, (IFNULL(p.clientes,0)/ COUNT(c.cli_id)) * 100, 0),2) tasa
        FROM clientes c
        join usuarios u on u.usr_id= c.cli_responsable
        join sucursales_propias s on s.sucp_id=u.usr_sucursal
        left JOIN(

            SELECT
                PASSWORD(cli_fecha_ingreso) id,
                usr_sucursal,
                sucp_nombre sucursal,  
                cli_responsable,
                usr_id,  
                usr_nombre vendedor,    
                DATE_FORMAT(cli_fecha_ingreso,'%Y-%m') periodo,
                cli_fecha_registro fecha,
                cli_fecha_ingreso,
                0 prospectos,
                COUNT(cli_id) clientes
            FROM clientes
            join usuarios on usr_id=cli_responsable
            join sucursales_propias on sucp_id=usr_sucursal
            WHERE cli_categoria IN (1,2,3) AND cli_fecha_ingreso is not null AND  cli_fecha_ingreso <> '0000-00-00'
            GROUP BY sucp_nombre, usr_nombre , DATE_FORMAT(cli_fecha_ingreso,'%Y-%m')
            ORDER BY cli_fecha_ingreso DESC
        
        ) p ON p.usr_sucursal = u.usr_sucursal AND  p.cli_responsable = c.cli_responsable AND p.periodo = DATE_FORMAT(c.cli_fecha_registro,'%Y-%m')
        WHERE cli_categoria IN (1,2,3) AND cli_fecha_registro is not null
        GROUP BY u.usr_sucursal, c.cli_responsable , DATE_FORMAT(c.cli_fecha_registro,'%Y-%m')
        ORDER BY u.usr_sucursal,c.cli_responsable,c.cli_fecha_registro DESC;
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
