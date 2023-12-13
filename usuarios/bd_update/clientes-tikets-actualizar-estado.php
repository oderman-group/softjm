<?php
require_once("../sesion.php");

$idPagina = 301;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"UPDATE clientes_tikets SET tik_estado='".TIK_ESTADO_CERRADO."' WHERE tik_id='" . $_GET["id"] . "'");
mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_realizado=1 WHERE cseg_tiket='" . $_GET["id"] . "'");



echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
exit();