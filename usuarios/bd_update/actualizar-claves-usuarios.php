<?php
require_once("../sesion.php");

$idPagina = 57;

include(RUTA_PROYECTO."/usuarios/verificar-paginas.php");

$conexionBdPrincipal->query("UPDATE usuarios SET  usr_clave=SHA1('" . $_POST["clave"] . "') WHERE usr_id='" . $_POST["id"] . "'");

include(RUTA_PROYECTO."/usuarios/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../usuarios-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();