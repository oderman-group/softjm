<?php   
require_once("../sesion.php");

$idPagina = 17;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$consulta = $conexionBdPrincipal->query("SELECT * FROM clientes");
while ($datos = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
	$clave1 = generarClaves();
	$conexionBdPrincipal->query("UPDATE clientes SET cli_clave='" . $clave1 . "' WHERE cli_id='" . $datos['cli_id'] . "'");
}

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../clientes.php?msg=14";</script>';
exit();