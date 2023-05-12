<?php
require_once("../sesion.php");

$prod = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
WHERE czpp_id='" . $_GET["idItem"] . "'"));



mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega)VALUES('" . $prod['czpp_cotizacion'] . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', '" . $prod['czpp_orden'] . "', '" . $prod['czpp_cantidad'] . "', '" . $prod['czpp_impuesto'] . "', '" . $prod['czpp_tipo'] . "', 1)");


echo '<script type="text/javascript">window.location.href="../remisionbdg-editar.php?id=' . $_GET["id"] . '&msg=2";</script>';
exit();