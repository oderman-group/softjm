<?php
require_once("../sesion.php");

header('Content-Type: application/json; charset=utf-8');

try {
    $idPagina = 265;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    
    $idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];
    
    // Función para subir archivos
    function subirArchivo($archivo, $prefijo, $destino) {
        if ($archivo['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            $nombreArchivo = $prefijo . '_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
            $rutaCompleta = $destino . '/' . $nombreArchivo;
            
            if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
                return $nombreArchivo;
            }
        }
        return false;
    }
    
    // Procesar archivos subidos
    $archivosSubidos = [];
    
    // Logo de la empresa
    if (isset($_FILES['logo']) && $_FILES['logo']['name'] != "") {
        $destino = RUTA_PROYECTO."/usuarios/files";
        $archivo = subirArchivo($_FILES['logo'], 'logo', $destino);
        if ($archivo) {
            $conexionBdPrincipal->query("UPDATE configuracion SET conf_logo='" . $archivo . "' WHERE conf_id_empresa= '{$idEmpresa}'");
            $archivosSubidos['logo'] = $archivo;
        }
    }
    
    // Encabezados de cotización
    $destinoImagenes = RUTA_PROYECTO."/usuarios/images";
    
    if (isset($_FILES['encabezadoCotizacion']) && $_FILES['encabezadoCotizacion']['name'] != "") {
        $archivo = subirArchivo($_FILES['encabezadoCotizacion'], 'ec', $destinoImagenes);
        if ($archivo) {
            $conexionBdPrincipal->query("UPDATE configuracion SET conf_encabezado_cotizacion='" . $archivo . "' WHERE conf_id_empresa= '{$idEmpresa}'");
            $archivosSubidos['encabezadoCotizacion'] = $archivo;
        }
    }
    
    if (isset($_FILES['encabezadoCotizacion2']) && $_FILES['encabezadoCotizacion2']['name'] != "") {
        $archivo = subirArchivo($_FILES['encabezadoCotizacion2'], 'ec2', $destinoImagenes);
        if ($archivo) {
            $conexionBdPrincipal->query("UPDATE configuracion SET conf_encabezado2_cotizacion='" . $archivo . "' WHERE conf_id_empresa= '{$idEmpresa}'");
            $archivosSubidos['encabezadoCotizacion2'] = $archivo;
        }
    }
    
    if (isset($_FILES['pieCotizacion']) && $_FILES['pieCotizacion']['name'] != "") {
        $archivo = subirArchivo($_FILES['pieCotizacion'], 'pc', $destinoImagenes);
        if ($archivo) {
            $conexionBdPrincipal->query("UPDATE configuracion SET conf_pie_cotizacion='" . $archivo . "' WHERE conf_id_empresa= '{$idEmpresa}'");
            $archivosSubidos['pieCotizacion'] = $archivo;
        }
    }
    
    // Encabezados de pedidos
    if (isset($_FILES['encabezadoPedido']) && $_FILES['encabezadoPedido']['name'] != "") {
        $archivo = subirArchivo($_FILES['encabezadoPedido'], 'ep', $destinoImagenes);
        if ($archivo) {
            $conexionBdPrincipal->query("UPDATE configuracion SET conf_encabezado_pedido='" . $archivo . "' WHERE conf_id_empresa= '{$idEmpresa}'");
            $archivosSubidos['encabezadoPedido'] = $archivo;
        }
    }
    
    if (isset($_FILES['encabezadoPedido2']) && $_FILES['encabezadoPedido2']['name'] != "") {
        $archivo = subirArchivo($_FILES['encabezadoPedido2'], 'ep2', $destinoImagenes);
        if ($archivo) {
            $conexionBdPrincipal->query("UPDATE configuracion SET conf_encabezado2_pedido='" . $archivo . "' WHERE conf_id_empresa= '{$idEmpresa}'");
            $archivosSubidos['encabezadoPedido2'] = $archivo;
        }
    }
    
    if (isset($_FILES['piePedido']) && $_FILES['piePedido']['name'] != "") {
        $archivo = subirArchivo($_FILES['piePedido'], 'pp', $destinoImagenes);
        if ($archivo) {
            $conexionBdPrincipal->query("UPDATE configuracion SET conf_pie_pedido='" . $archivo . "' WHERE conf_id_empresa= '{$idEmpresa}'");
            $archivosSubidos['piePedido'] = $archivo;
        }
    }
    
    // Actualizar campos de texto
    $query = "UPDATE configuracion SET
        conf_empresa='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["nombre"]) . "', 
        conf_email='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["email"]) . "', 
        conf_web='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["web"]) . "', 
        conf_url_encuestas='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["urlEncuestas"]) . "', 
        conf_nit='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["nit"]) . "', 
        conf_telefono='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["telefono"]) . "', 
        conf_fondo_boletin='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["fondoBoletin"]) . "', 
        conf_fondo_mensaje='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["fondoMensaje"]) . "', 
        conf_color_letra='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["colorLetra"]) . "', 
        conf_color_link='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["colorLink"]) . "', 
        conf_mensaje_pie='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["mensajePie"]) . "', 
        conf_nombre_boton='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["botonNombre"]) . "', 
        conf_url_boton='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["botonUrl"]) . "', 
        conf_paginacion='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["paginacion"]) . "', 
        conf_agno_inicio='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["agnoInicio"]) . "', 
        conf_ancho_logo='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["anchoLogo"]) . "', 
        conf_alto_logo='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["altoLogo"]) . "', 
        conf_trm_compra='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["dolarCompra"]) . "', 
        conf_trm_venta='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["dolarVenta"]) . "', 
        conf_clave_correo='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["claveEmail"]) . "', 
        conf_proveedor_cotizacion='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["proveedorCotizacion"]) . "', 
        conf_porcentaje_clientes='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["porcentajeClientes"]) . "', 
        conf_comision_vendedores='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["comisionVendedores"]) . "', 
        conf_coreo_puntos='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["correoPuntos"]) . "', 
        conf_vencimiento_puntos='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["fechaVencimientoSaldo"]) . "', 
        conf_cliente_imprimir_certificado='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["clientesImprimir"]) . "', 
        conf_terminos_condiciones='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["terminos"]) . "',
        conf_tiempo_ticket_sin_respuesta='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["conf_tiempo_ticket_sin_respuesta"]) . "',
        conf_tiempo_tareas_vencidas='" . mysqli_real_escape_string($conexionBdPrincipal, $_POST["conf_tiempo_tareas_vencidas"]) . "'
        WHERE conf_id_empresa= '{$idEmpresa}'";
    
    if (!$conexionBdPrincipal->query($query)) {
        throw new Exception("Error al actualizar configuración: " . $conexionBdPrincipal->error);
    }
    
    // Guardar historial de acciones
    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");
    
    echo json_encode([
        'success' => true,
        'message' => 'Configuración actualizada exitosamente',
        'archivos_subidos' => $archivosSubidos,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
}

exit();
