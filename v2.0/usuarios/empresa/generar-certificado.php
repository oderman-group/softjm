<?php
include("sesion.php");
include("../compartido/head.php");
$idPagina = 339;
$tituloPagina = "Generar certificado";
include("verificar-paginas.php");


mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_generar_certificado=1, rem_fecha_certificado=now(), rem_estado_certificado='".REM_ESTADO_CERTIFICADO_VIGENTE."', rem_fecha=now() WHERE rem_id='".$_GET["id"]."'");
	
echo '<script type="text/javascript">window.location.href="lab-remisiones.php?idRem='.$_GET["id"].'";</script>';
exit();
