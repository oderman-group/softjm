<?php
	include("../sesion.php");
	$idPagina = 339;

	include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_generar_certificado=1, rem_fecha_certificado=now(), rem_estado_certificado='".REM_ESTADO_CERTIFICADO_VIGENTE."', rem_fecha=now() WHERE rem_id='".$_GET["id"]."'");
    
    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="../remisiones.php?idRem='.$_GET["id"].'";</script>';
    exit();