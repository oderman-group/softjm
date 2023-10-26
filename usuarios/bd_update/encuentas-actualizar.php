<?php   
require_once("../sesion.php");

$idPagina = 284;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"UPDATE encuesta_satisfaccion SET encs_cliente='" . $_POST["cliente"] . "', encs_atendido='" . $_POST["usuario"] . "', encs_producto='" . $_POST["producto"] . "' WHERE encs_id='" . $_POST["id"] . "'");
	
echo '<script type="text/javascript">window.location.href="../encuesta-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();

 
