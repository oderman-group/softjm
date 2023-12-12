<?php
include("../sesion.php");
$idPagina = 338;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_estado=2, rem_fecha_salida=now() WHERE rem_id='".$_GET["id"]."'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="../reportes/remisiones-imprimir.php?id='.$_GET["id"].'&estado=2";</script>';
exit();
