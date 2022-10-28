<?php   
require_once("../sesion.php");

$idPagina = 55;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$conexionBdPrincipal->query("DELETE FROM facturacion WHERE fact_cliente='" . $_GET["id"] . "'");
$conexionBdPrincipal->query("DELETE FROM cliente_seguimiento WHERE cseg_cliente='" . $_GET["id"] . "'");
$conexionBdPrincipal->query("DELETE FROM clientes_categorias WHERE cpcat_cliente='" . $_GET["id"] . "'");
$conexionBdPrincipal->query("DELETE FROM clientes_tikets WHERE tik_cliente='" . $_GET["id"] . "'");
$conexionBdPrincipal->query("DELETE FROM contactos WHERE cont_cliente_principal='" . $_GET["id"] . "'");
$conexionBdPrincipal->query("DELETE FROM cotizacion WHERE cotiz_cliente='" . $_GET["id"] . "'");

$conexionBdPrincipal->query("DELETE FROM clientes WHERE cli_id='" . $_GET["id"] . "'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");
	
echo '<script type="text/javascript">window.location.href="../clientes.php?msg=3";</script>';
exit();
