<?php
require_once("../sesion.php");

$idPagina = 31;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");


	if ($_FILES['logo']['name'] != "") {
		$destino = RUTA_PROYECTO."/usuarios/files";
		$archivo = subirArchivosAlServidor($_FILES['logo'], 'logo', $destino);

		$conexionBdPrincipal->query("UPDATE configuracion SET conf_logo='" . $archivo . "' WHERE conf_id=1");
	}

	$destino = RUTA_PROYECTO."/usuarios/images";
	if ($_FILES['encabezadoCotizacion']['name'] != "") {
		$archivo = subirArchivosAlServidor($_FILES['encabezadoCotizacion'], 'ec', $destino);

		$conexionBdPrincipal->query("UPDATE configuracion SET conf_encabezado_cotizacion='" . $archivo . "' WHERE conf_id=1");
	}

	if ($_FILES['pieCotizacion']['name'] != "") {
		$archivo = subirArchivosAlServidor($_FILES['pieCotizacion'], 'pc', $destino);

		$conexionBdPrincipal->query("UPDATE configuracion SET conf_pie_cotizacion='" . $archivo . "' WHERE conf_id=1");
	}

	if ($_FILES['encabezadoCotizacion2']['name'] != "") {
		$archivo2 = subirArchivosAlServidor($_FILES['encabezadoCotizacion2'], 'ec2', $destino);

		$conexionBdPrincipal->query("UPDATE configuracion SET conf_encabezado2_cotizacion='" . $archivo2 . "' WHERE conf_id=1");
	}

	$destino = RUTA_PROYECTO."/usuarios/images";
	if ($_FILES['encabezadoPedido']['name'] != "") {
		$archivo = subirArchivosAlServidor($_FILES['encabezadoPedido'], 'ep', $destino);
		$conexionBdPrincipal->query("UPDATE configuracion SET conf_encabezado_pedido='" . $archivo . "' WHERE conf_id=1");
	}

	if ($_FILES['piePedido']['name'] != "") {
		$archivo = subirArchivosAlServidor($_FILES['piePedido'], 'pp', $destino);
		$conexionBdPrincipal->query("UPDATE configuracion SET conf_pie_pedido='" . $archivo . "' WHERE conf_id=1");
	}

	if ($_FILES['encabezadoPedido2']['name'] != "") {
		$archivo2 = subirArchivosAlServidor($_FILES['encabezadoPedido2'], 'ep2', $destino);
		$conexionBdPrincipal->query("UPDATE configuracion SET conf_encabezado2_pedido='" . $archivo2 . "' WHERE conf_id=1");
	}

	$conexionBdPrincipal->query("UPDATE configuracion SET
    conf_empresa='" . $_POST["nombre"] . "', 
    conf_email='" . $_POST["email"] . "', 
    conf_web='" . $_POST["web"] . "', 
    conf_url_encuestas='" . $_POST["urlEncuestas"] . "', 
    conf_nit='" . $_POST["nit"] . "', 
    conf_telefono='" . $_POST["telefono"] . "', 
    conf_fondo_boletin='" . $_POST["fondoBoletin"] . "', 
    conf_fondo_mensaje='" . $_POST["fondoMensaje"] . "', 
    conf_color_letra='" . $_POST["colorLetra"] . "', 
    conf_color_link='" . $_POST["colorLink"] . "', 
    conf_mensaje_pie='" . $_POST["mensajePie"] . "', 
    conf_nombre_boton='" . $_POST["botonNombre"] . "', 
    conf_url_boton='" . $_POST["botonUrl"] . "', 
    conf_paginacion='" . $_POST["paginacion"] . "', 
    conf_agno_inicio='" . $_POST["agnoInicio"] . "', 
    conf_ancho_logo='" . $_POST["anchoLogo"] . "', 
    conf_alto_logo='" . $_POST["altoLogo"] . "', 
    conf_trm_compra='" . $_POST["dolarCompra"] . "', 
    conf_trm_venta='" . $_POST["dolarVenta"] . "', 
    conf_clave_correo='" . $_POST["claveEmail"] . "', 
    conf_proveedor_cotizacion='" . $_POST["proveedorCotizacion"] . "', 
    conf_porcentaje_clientes='" . $_POST["porcentajeClientes"] . "', 
    conf_comision_vendedores='" . $_POST["comisionVendedores"] . "', 
    conf_coreo_puntos='" . $_POST["correoPuntos"] . "', 
    conf_vencimiento_puntos='" . $_POST["fechaVencimientoSaldo"] . "', 
    conf_cliente_imprimir_certificado='" . $_POST["clientesImprimir"] . "' 
    WHERE conf_id=1");
	
	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");
	
	echo '<script type="text/javascript">window.location.href="../configuracion.php?msg=2";</script>';
	exit();
