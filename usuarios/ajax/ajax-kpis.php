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
            c.cli_id,
            c.cli_nombre AS cliente, -- Se añadió el nombre del cliente para mayor detalle
            -- Calcula la duración en días para cada venta individual
            ct.tik_fecha_creacion fecha,
            ct.tik_fecha_cierre,
            DATEDIFF(ct.tik_fecha_cierre, ct.tik_fecha_creacion) AS dias,
            u.usr_id AS id_vendedor,
            u.usr_nombre AS asesor,
            sp.sucp_id AS id_sucursal,
            sp.sucp_nombre AS sucursal,
            sp.sucp_ciudad AS ciudad_sucursal,
            sp.sucp_id_empresa AS id_empresa_sucursal
        FROM
            clientes AS c
        INNER JOIN
            clientes_tikets AS ct ON ct.tik_cliente = c.cli_id
        INNER JOIN
            usuarios AS u ON ct.tik_usuario_responsable = u.usr_id -- Se une con la tabla de usuarios para obtener información del vendedor
        INNER JOIN
            sucursales_propias AS sp ON u.usr_sucursal = sp.sucp_id -- Se une con la tabla de sucursales para obtener información de la sucursal
        WHERE
            ct.tik_estado = 2 -- Ticket Cerrado
            AND ct.tik_etapa = 5 -- Cerrado y ganado
            AND ct.tik_fecha_cierre IS NOT NULL
            AND ct.tik_fecha_cierre >= ct.tik_fecha_creacion
            AND ct.tik_tipo_negocio = 1 -- Tipo venta
            AND ct.tik_id_cotizacion IS NOT NULL
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
        um.um_id,
        CONCAT(um.um_year,'-',if(um.um_mes < 10, CONCAT('0',um.um_mes),um.um_mes),'-01') fecha,
        u.usr_sucursal id_sucural,
        sp.sucp_nombre sucursal,
        um.um_usuario id_asesor,
        u.usr_nombre asesor,
        um.um_meta planeada,
        IFNULL(v.valor,0) ejecutada
        FROM usuarios_metas um 
        INNER JOIN usuarios u ON u.usr_id = um.um_usuario
        INNER JOIN sucursales_propias sp ON sp.sucp_id= u.usr_sucursal
        LEFT JOIN(
        SELECT
            fac.factura_id as id,
            DATE_FORMAT(fac.factura_fecha_creacion,'%Y-%m') periodo,
            fac.factura_fecha_creacion as fecha,  
            us.usr_sucursal id_sucural,      
            sp.sucp_nombre as sucursal,
            us.usr_id id_asesor,
            us.usr_nombre as asesor,
            fac.factura_cliente,
            cli.cli_nombre as cliente,
            ROUND(SUM(cp.czpp_cantidad * cp.czpp_valor),2) AS valor
        FROM facturas fac
        INNER JOIN cotizacion_productos cp ON cp.czpp_cotizacion = fac.factura_id AND cp.czpp_tipo = 4
        INNER JOIN  clientes cli ON cli.cli_id=fac.factura_cliente
        INNER JOIN  usuarios us ON us.usr_id=fac.factura_vendedor
        INNER JOIN  sucursales_propias sp ON sp.sucp_id=us.usr_sucursal
        WHERE fac.factura_tipo = 1 AND fac.factura_id_empresa = 1
        GROUP BY us.usr_sucursal, us.usr_id , DATE_FORMAT(fac.factura_fecha_creacion,'%Y-%m')
        ORDER BY fac.factura_fecha_creacion DESC
        )v ON v.id_sucural = u.usr_sucursal AND v.id_asesor = um.um_usuario AND CONCAT(um.um_year,'-',if(um.um_mes < 10, CONCAT('0',um.um_mes),um.um_mes)) = v.periodo
        WHERE um.um_tipo_meta = 'VALOR_VENTAS';
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

}else if (isset($_POST["opcion"]) and $_POST["opcion"] == "consultar_kpi_6_ejecucion_demostraciones" ) {


    $e_dato = json_decode($_POST["e_datos"]);

    $sql = "
        SELECT
        um.um_id,
        CONCAT(um.um_year,'-',if(um.um_mes < 10, CONCAT('0',um.um_mes),um.um_mes),'-01') fecha,
        u.usr_sucursal id_sucural,
        sp.sucp_nombre sucursal,
        um.um_usuario id_asesor,
        u.usr_nombre asesor,
        um.um_meta planeada,
        IFNULL(cs.ejecutada,0) ejecutada
        FROM usuarios_metas um 
        INNER JOIN usuarios u ON u.usr_id = um.um_usuario
        INNER JOIN sucursales_propias sp ON sp.sucp_id= u.usr_sucursal
        LEFT JOIN(
        SELECT 
            cs.cseg_id id,
            DATE_FORMAT(cs.cseg_fecha_contacto,'%Y-%m') periodo,
            cs.cseg_fecha_contacto fecha,
            u.usr_sucursal id_sucural,
            sp.sucp_nombre sucursal,
            cs.cseg_usuario_responsable id_asesor,
            u.usr_nombre asesor,
            cs.cseg_cliente id_cliente,
            c.cli_nombre cliente,
            COUNT(cs.cseg_id) ejecutada
        FROM cliente_seguimiento cs
        INNER JOIN clientes c ON c.cli_id = cs.cseg_cliente
        INNER JOIN usuarios u ON u.usr_id = cs.cseg_usuario_responsable
        INNER JOIN sucursales_propias sp ON sp.sucp_id= u.usr_sucursal
        WHERE cs.cseg_demostracion = 1
        GROUP BY u.usr_sucursal, cs.cseg_usuario_responsable , DATE_FORMAT(cs.cseg_fecha_contacto,'%Y-%m')
        )cs ON cs.id_sucural = u.usr_sucursal AND cs.id_asesor = um.um_usuario AND CONCAT(um.um_year,'-',if(um.um_mes < 10, CONCAT('0',um.um_mes),um.um_mes)) = cs.periodo
        WHERE um.um_tipo_meta = 'NUMERO_DEMOSTRACIONES';
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
        select
        cseg_fecha_reporte as fecha,
        cli_nombre as cliente,
        usr_nombre as asesor,
        sucp_nombre as sucursal
        FROM cliente_seguimiento
        INNER JOIN clientes cli ON cli.cli_id=cseg_cliente 
            and cli.cli_forma_creacion = 'EJECUTIVO_PROSPECCION' 
            and (cli_fecha_ingreso >= DATE(cseg_fecha_reporte) or cli_fecha_ingreso is NULL)
        INNER JOIN usuarios u ON u.usr_id = cseg_usuario_responsable
        INNER JOIN sucursales_propias sucu ON sucp_id=usr_sucursal
        WHERE cseg_tipo = 1 and cseg_forma_contacto = 1
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

}else if (isset($_POST["opcion"]) and $_POST["opcion"] == "consultar_kpi_8_clientes_efectivos_por_evento" ) {


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
            WHERE cli_categoria IN (1,2,3) AND cli_fecha_ingreso is not null AND  cli_fecha_ingreso <> '0000-00-00' AND cli_referencia = 4
            GROUP BY sucp_nombre, usr_nombre , DATE_FORMAT(cli_fecha_ingreso,'%Y-%m')
            ORDER BY cli_fecha_ingreso DESC
                
        ) p ON p.usr_sucursal = u.usr_sucursal AND  p.cli_responsable = c.cli_responsable AND p.periodo = DATE_FORMAT(c.cli_fecha_registro,'%Y-%m')
        WHERE cli_categoria IN (1,2,3) AND cli_fecha_registro is not null AND cli_referencia = 4
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

}else if (isset($_POST["opcion"]) and $_POST["opcion"] == "consultar_kpi_9_nuevos_subdistribuidores" ) {


    $e_dato = json_decode($_POST["e_datos"]);

    $sql = "
          SELECT
            cli_id id,
            usr_id,
            usr_sucursal,
            usr_nombre vendedor,
            sucp_nombre sucursal,
            cli_fecha_registro AS fecha,
            DATE_FORMAT(cli_fecha_registro, '%Y%m') AS periodo,
            COUNT(cli_id) AS nuevos,
            IFNULL(SUM(COUNT(cli_id)) OVER (
                PARTITION BY usr_id, usr_sucursal
                ORDER BY DATE_FORMAT(cli_fecha_registro,'%Y%m')
                ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING
            ), 0) AS actuales,
            ROUND(
                CASE 
                WHEN IFNULL(SUM(COUNT(cli_id)) OVER (
                    PARTITION BY usr_id, usr_sucursal
                    ORDER BY DATE_FORMAT(cli_fecha_registro,'%Y%m')
                    ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING
                ), 0) = 0 THEN 0
                ELSE (COUNT(cli_id) / IFNULL(SUM(COUNT(cli_id)) OVER (
                    PARTITION BY usr_id, usr_sucursal
                    ORDER BY DATE_FORMAT(cli_fecha_registro,'%Y%m')
                    ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING
                ), 1)) * 100
                END, 2
            ) AS tasa
            FROM clientes
            JOIN usuarios ON usr_id = cli_responsable
            JOIN sucursales_propias ON sucp_id = usr_sucursal
            WHERE cli_categoria = 3
            GROUP BY usr_sucursal, usr_id, DATE_FORMAT(cli_fecha_registro,'%Y%m')
            ORDER BY usr_sucursal, usr_id, periodo;
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

}else if (isset($_POST["opcion"]) and $_POST["opcion"] == "consultar_kpi_10_captacion_clientes_instituciones" ) {


    $e_dato = json_decode($_POST["e_datos"]);

    $sql = "
          SELECT
            cli_id id,
            usr_id,
            usr_sucursal,
            usr_nombre vendedor,
            sucp_nombre sucursal,
            cli_fecha_registro AS fecha,
            DATE_FORMAT(cli_fecha_registro, '%Y%m') AS periodo,
            COUNT(cli_id) AS nuevos,
            IFNULL(SUM(COUNT(cli_id)) OVER (
                PARTITION BY usr_id, usr_sucursal
                ORDER BY DATE_FORMAT(cli_fecha_registro,'%Y%m')
                ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING
            ), 0) AS actuales,
            ROUND(
                CASE 
                WHEN IFNULL(SUM(COUNT(cli_id)) OVER (
                    PARTITION BY usr_id, usr_sucursal
                    ORDER BY DATE_FORMAT(cli_fecha_registro,'%Y%m')
                    ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING
                ), 0) = 0 THEN 0
                ELSE (COUNT(cli_id) / IFNULL(SUM(COUNT(cli_id)) OVER (
                    PARTITION BY usr_id, usr_sucursal
                    ORDER BY DATE_FORMAT(cli_fecha_registro,'%Y%m')
                    ROWS BETWEEN UNBOUNDED PRECEDING AND 1 PRECEDING
                ), 1)) * 100
                END, 2
            ) AS tasa
            FROM clientes
            JOIN usuarios ON usr_id = cli_responsable
            JOIN sucursales_propias ON sucp_id = usr_sucursal
            WHERE cli_institucional = 1
            GROUP BY usr_sucursal, usr_id, DATE_FORMAT(cli_fecha_registro,'%Y%m')
            ORDER BY usr_sucursal, usr_id, periodo;
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

}else if (isset($_POST["opcion"]) and $_POST["opcion"] == "consultar_kpi_11_numero_visitas_realizadas" ) {


    $e_dato = json_decode($_POST["e_datos"]);

    $sql = "
          SELECT
            um.um_id,
            CONCAT(um.um_year,'-',if(um.um_mes < 10, CONCAT('0',um.um_mes),um.um_mes),'-01') fecha,
            u.usr_sucursal id_sucural,
            sp.sucp_nombre sucursal,
            um.um_usuario id_asesor,
            u.usr_nombre asesor,
            um.um_meta planeada,
            IFNULL(cs.ejecutada,0) ejecutada
            FROM usuarios_metas um 
            INNER JOIN usuarios u ON u.usr_id = um.um_usuario
            INNER JOIN sucursales_propias sp ON sp.sucp_id= u.usr_sucursal
            LEFT JOIN(
            SELECT 
                cs.cseg_id id,
                DATE_FORMAT(cs.cseg_fecha_contacto,'%Y-%m') periodo,
                cs.cseg_fecha_contacto fecha,
                u.usr_sucursal id_sucural,
                sp.sucp_nombre sucursal,
                cs.cseg_usuario_responsable id_asesor,
                u.usr_nombre asesor,
                cs.cseg_cliente id_cliente,
                c.cli_nombre cliente,
                COUNT(cs.cseg_id) ejecutada
            FROM cliente_seguimiento cs
            INNER JOIN clientes c ON c.cli_id = cs.cseg_cliente
            INNER JOIN usuarios u ON u.usr_id = cs.cseg_usuario_responsable
            INNER JOIN sucursales_propias sp ON sp.sucp_id= u.usr_sucursal
            WHERE cs.cseg_visita = 1
            GROUP BY u.usr_sucursal, cs.cseg_usuario_responsable , DATE_FORMAT(cs.cseg_fecha_contacto,'%Y-%m')
            )cs ON cs.id_sucural = u.usr_sucursal AND cs.id_asesor = um.um_usuario AND CONCAT(um.um_year,'-',if(um.um_mes < 10, CONCAT('0',um.um_mes),um.um_mes)) = cs.periodo
            WHERE um.um_tipo_meta = 'NUMERO_VISITAS';
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
