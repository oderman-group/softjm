<?php
require_once("../sesion.php");

$conexionBdPrincipal->query("UPDATE remisionbdg SET 
remi_fecha_propuesta='" . $_POST["fechaPropuesta"] . "', 
remi_cliente='" . $_POST["cliente"] . "', 
remi_sucursal='" . $_POST["sucursal"] . "', 
remi_contacto='" . $_POST["contacto"] . "', 
remi_fecha_vencimiento='" . $_POST["fechaVencimiento"] . "', 
remi_vendedor='" . $_POST["influyente"] . "', 
remi_forma_pago='" . $_POST["formaPago"] . "', 
remi_moneda='" . $_POST["moneda"] . "', 
remi_ultima_modificacion=now(), 
remi_usuario_modificacion='" . $_SESSION["id"] . "', 
remi_observaciones='" . $conexionBdPrincipal->real_escape_string($_POST["notas"]) . "'
WHERE remi_id='" . $_POST["id"] . "' AND  remi_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'");

require('actualizar-productos-cotizacion.php');

require('actualizar-combos-cotizacion.php');

require('actualizar-servicios-cotizacion.php');

echo '<script type="text/javascript">window.location.href="../remisionbdg-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();