<?php
    require_once("../sesion.php");

    $idPagina = 312;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    
    $not = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM notificaciones WHERE not_id='" . $_GET["id"] . "'"));
	if ($not[5] == 1) $estadoN = 2;
	else $estadoN = 1;
	mysqli_query($conexionBdPrincipal,"UPDATE notificaciones SET not_estado='" . $estadoN . "' WHERE not_id='" . $_GET["id"] . "'");
	
	mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_realizado='" . $estadoN . "' WHERE cseg_id='" . $_GET["seg"] . "'");
	
	
    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();