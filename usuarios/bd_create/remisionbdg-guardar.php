<?php
require_once("../sesion.php");
mysqli_query($conexionBdPrincipal,"INSERT INTO remisionbdg(remi_fecha_propuesta, remi_observaciones, remi_cliente, remi_fecha_vencimiento, remi_vendedor, remi_creador, remi_sucursal, remi_contacto, remi_forma_pago, remi_fecha_creacion, remi_moneda, remi_estado, remi_id_empresa)VALUES('" . $_POST["fechaPropuesta"] . "','" . $_POST["notas"] . "','" . $_POST["cliente"] . "','" . $_POST["fechaVencimiento"] . "','" . $_POST["influyente"] . "','" . $_SESSION["id"] . "','" . $_POST["sucursal"] . "','" . $_POST["contacto"] . "','" . $_POST["formaPago"] . "',now(),'" . $_POST["moneda"] . "', 1, '".$idEmpresa."')");

$idInsert = mysqli_insert_id($conexionBdPrincipal);

require("guardar-productos-cotizacion.php");

require("guardar-combos-cotizacion.php");

require("guardar-servicios-cotizacion.php");

echo '<script type="text/javascript">window.location.href="../remisionbdg-editar.php?id=' . $idInsert . '&msg=1";</script>';
exit();