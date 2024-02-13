<?php
require_once("../sesion.php");
$idPagina = 375;
mysqli_query($conexionBdPrincipal,"DELETE FROM remisionbdg WHERE remi_id='" . $_GET["id"] . "' AND  remi_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'");
mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion_productos WHERE czpp_cotizacion='" . $_GET["id"] . "'");
mysqli_query($conexionBdPrincipal,"DELETE FROM facturas WHERE factura_remision='" . $_GET["id"] . "'  AND  factura_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'");

echo '<script type="text/javascript">window.location.href="../remisionbdg.php?&msg=3";</script>';
exit();