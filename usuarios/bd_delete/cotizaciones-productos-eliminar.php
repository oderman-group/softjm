<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion_productos WHERE czpp_id='" . $_GET["idItem"] . "'");

echo '<script type="text/javascript">window.location.href="../cotizaciones-editar.php?id=' . $_GET["id"] . '&msg=2";</script>';
exit();