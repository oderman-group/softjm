<?php
include("../../../conexion.php");
$config = mysql_fetch_array(mysql_query("SELECT * FROM configuracion WHERE conf_id=1",$conexion));
?>