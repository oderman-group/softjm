<?php   
require_once("../sesion.php");

$idPagina = 284;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_papelera=1, cli_papelera_por='" . $_SESSION["id"] . "', cli_papelera_fecha=now() WHERE cli_id='" . $_GET["idR"] . "'");
	

mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_estado_mercadeo='" . $_GET["em"] . "', cli_estado_mercadeo_fecha=now(), cli_estado_mercadeo_usuario='" . $_SESSION["id"] . "' WHERE cli_id='" . $_GET["idR"] . "'");
	

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();
 
