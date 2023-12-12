<?php
	include("../sesion.php");
	$idPagina = 343;

	include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_archivo='' WHERE rem_id='".$_GET["id"]."'");
	
	include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="../remisiones-editar.php?id='.$_GET["id"].'";</script>';
	exit();
