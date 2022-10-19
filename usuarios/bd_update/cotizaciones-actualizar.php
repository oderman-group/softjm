<?php
require_once("../sesion.php");
require_once("../../conexion.php");
include("../config/config.php");
require("../funciones-para-el-sistema.php");

$datosCliente = mysql_fetch_array(mysql_query("SELECT * FROM clientes WHERE cli_id='".$_POST["cliente"]."'",$conexion));

mysql_query("UPDATE cotizacion SET 
    cotiz_fecha_propuesta='" . $_POST["fechaPropuesta"] . "', 
    cotiz_cliente='" . $_POST["cliente"] . "', 
    cotiz_sucursal='" . $_POST["sucursal"] . "', 
    cotiz_contacto='" . $_POST["contacto"] . "', 
    cotiz_fecha_vencimiento='" . $_POST["fechaVencimiento"] . "', 
    cotiz_vendedor='" . $_POST["influyente"] . "', 
    cotiz_forma_pago='" . $_POST["formaPago"] . "', 
    cotiz_moneda='" . $_POST["moneda"] . "', 
    cotiz_ultima_modificacion=now(), 
    cotiz_usuario_modificacion='" . $_SESSION["id"] . "', 
    cotiz_observaciones='" . mysql_real_escape_string($_POST["notas"]) . "', 
    cotiz_envio='" . $_POST["envio"] . "', 
    cotiz_ocultar_descuento_combo='" . $_POST["dctoCombos"] . "', 
    cotiz_descuentos_especiales='" . $_POST["dctoEspecial"] . "' 
    WHERE cotiz_id='" . $_POST["id"] . "'
", $conexion);

if (mysql_errno() != 0) {echo informarErrorAlUsuario(__LINE__, mysql_error()); exit();}

    
    require('actualizar-productos-cotizacion.php');

    require('actualizar-combos-cotizacion.php');

    require('actualizar-servicios-cotizacion.php');

include(RUTA_PROYECTO."/usuarios/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../cotizaciones-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();
