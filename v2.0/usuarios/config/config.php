<?php
include("../../../conexion.php");
require_once(RUTA_PROYECTO."/usuarios/config/config.php");
$config = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1"), MYSQLI_BOTH);
$idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];
?>