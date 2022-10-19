<?php   
require_once("../sesion.php");
require("../funciones-para-el-sistema.php");

$consulta = $conexionBdPrincipal->query("SELECT * FROM clientes");
while ($datos = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
	$clave1 = generarClaves();
	$conexionBdPrincipal->query("UPDATE clientes SET cli_clave='" . $clave1 . "' WHERE cli_id='" . $datos['cli_id'] . "'");
}

include(RUTA_PROYECTO."/usuarios/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../clientes.php?msg=passOk";</script>';
exit();