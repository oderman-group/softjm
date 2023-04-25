<?php
require_once("../sesion.php");

$conexionBdPrincipal->query("DELETE FROM contactos WHERE cont_id='" . $_GET["id"] . "'");

echo '<script type="text/javascript">window.location.href="../clientes-contactos.php?cte=' . $_GET["cte"] . '";</script>';
exit();