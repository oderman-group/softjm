<?php
require_once("../sesion.php");
require("../funciones-para-el-sistema.php");

$conexionBdPrincipal->query("UPDATE configuracion SET conf_emsj_cotizacion='" . $_POST["eCotizacion"] . "', conf_emsj_portafolios='" . $_POST["ePortafolio"] . "' WHERE conf_id=1");

include(RUTA_PROYECTO."/usuarios/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../estructura-mensajes.php?msg=2";</script>';
exit();