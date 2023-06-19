<?php
session_start();
include("../conexion.php");
mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_sesion=0, cli_ultima_salida=now() WHERE cli_id='".$_SESSION["id"]."'");
session_destroy();
header("Location:http://jmequipos.com/clientes.php?6=current-menu-item");
?>