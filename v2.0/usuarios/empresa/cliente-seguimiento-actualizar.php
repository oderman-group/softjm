<?php
include("sesion.php");
include("../compartido/head.php");
$idPagina = 342;
$tituloPagina = "Actualizar salidad remisión";
include("verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_realizado=1 WHERE cseg_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();
