<?php
include("sesion.php");
include("../compartido/head.php");
$idPagina = 343;
$tituloPagina = "Quitar imagen de la remision";
include("verificar-paginas.php");


mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_archivo='' WHERE rem_id='".$_GET["id"]."'");
	
	echo '<script type="text/javascript">window.location.href="lab-remisiones-editar.php?id='.$_GET["id"].'";</script>';
	exit();
