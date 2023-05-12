<?php
include("../../../conexion.php");
$config = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1"), MYSQLI_BOTH);
?>