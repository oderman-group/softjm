<?php
    require_once("../sesion.php");

	$idPagina = 222;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    
  	mysqli_query($conexionBdPrincipal,"DELETE FROM agenda WHERE age_id='" . $_GET["id"] . "'");
	
	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../calendario.php?id=' . $_SESSION["id"] . '";</script>';
	exit();