<?php
require_once("../sesion.php");

$idPagina = 57;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"UPDATE cotizacion_productos SET czpp_descuento=czpp_descuento_especial, czpp_aprobado_usuario='".$_SESSION['id']."', czpp_aprobado_fecha=now() WHERE czpp_id='" . $_GET["idItem"] . "'");

	
include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
exit();