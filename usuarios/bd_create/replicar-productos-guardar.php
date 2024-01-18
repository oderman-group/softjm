<?php
require_once("../sesion.php");

$idPagina = 306;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$prod = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
	WHERE czpp_id='" . $_GET["idItem"] . "'"));
	


	mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega)VALUES('" . $prod['czpp_cotizacion'] . "','" . $prod['czpp_producto'] . "', '" . $prod['czpp_valor'] . "', 1, 0, '" . $prod['czpp_impuesto'] . "', 3, 1)");
	

	

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();