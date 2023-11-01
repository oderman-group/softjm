<?php
require_once("../sesion.php");

$idPagina = 311;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$idPagina = 72;
	include("includes/verificar-paginas.php");
	
	mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion (fact_cliente, fact_fecha, fact_valor, fact_estado, fact_usuario_responsable, fact_descripcion, fact_observacion, fact_descuento, fact_producto, fact_numero_fisica, fact_usuario_influyente, fact_fecha_real, fact_fecha_vencimiento) SELECT fact_cliente, now(), fact_valor, fact_estado, fact_usuario_responsable, fact_descripcion, fact_observacion, fact_descuento, fact_producto, fact_numero_fisica, fact_usuario_influyente, now(), fact_fecha_vencimiento FROM facturacion WHERE fact_id='" . $_GET["id"] . "'");
	
	
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
