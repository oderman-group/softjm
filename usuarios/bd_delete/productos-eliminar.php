<?php
require_once("../sesion.php");

$idPagina = 61;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

require_once RUTA_PROYECTO.'/usuarios/class/Producto.php';

$numeroProcesosComerciales = Producto::numeroProcesosComerciales($_GET["id"], $conexionBdPrincipal);
$numeroCombos = Producto::numeroCombos($_GET["id"], $conexionBdPrincipal);

if ($numeroProcesosComerciales > 0 || $numeroCombos > 0) {
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
}
	
	$conexionBdPrincipal->query("DELETE FROM productos_materiales WHERE ppmt_producto='" . $_GET["id"] . "'");
	$conexionBdPrincipal->query("DELETE FROM productos WHERE prod_id='" . $_GET["id"] . "' AND prod_id_empresa='".$idEmpresa."'");
	
	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();