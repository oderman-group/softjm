<?php   
require_once("../sesion.php");

$idPagina = 284;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"UPDATE agenda SET age_evento='" . $_POST["evento"] . "', age_fecha='" . $_POST["fecha"] . "', age_lugar='" . $_POST["lugar"] . "', age_notas='" . $_POST["notas"] . "', age_inicio='" . $_POST["inicio"] . "', age_fin='" . $_POST["fin"] . "' WHERE age_id='" . $_POST["id"] . "'");
	
	echo '<script type="text/javascript">window.location.href="../calendario.php?id=' . $_SESSION["id"] . '";</script>';
	exit();

 
