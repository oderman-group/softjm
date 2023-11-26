<?php
include("sesion.php");
include("../compartido/head.php");
$idPagina = 338;
$tituloPagina = "Actualizar salidad remisión";
include("verificar-paginas.php");


mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_estado=2, rem_fecha_salida=now() WHERE rem_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="lab-remisiones-imprimir.php?id='.$_GET["id"].'&estado=2";</script>';
	exit();
