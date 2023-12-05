<?php
require_once("../sesion.php");
$idPagina = 378;
mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion_productos WHERE czpp_id='" . $_GET["idItem"] . "'");

echo '<script type="text/javascript">window.location.href="../remisionbdg-editar.php?id=' . $_GET["id"] . '&msg=2";</script>';
exit();