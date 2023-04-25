<?php
require_once("../sesion.php");

if ($_FILES['bTop']['name'] != "") {
    $archivo = $_FILES['bTop']['name'];
    $destino = "../files/publicidad";
    move_uploaded_file($_FILES['bTop']['tmp_name'], $destino . "/" . $archivo);
    mysqli_query($conexionBdPrincipal,"UPDATE configuracion SET conf_banner_top='" . $archivo . "' WHERE conf_id=1");
    
}

if ($_FILES['bLat']['name'] != "") {
    $archivo = $_FILES['bLat']['name'];
    $destino = "../files/publicidad";
    move_uploaded_file($_FILES['bLat']['tmp_name'], $destino . "/" . $archivo);
    mysqli_query($conexionBdPrincipal,"UPDATE configuracion SET conf_banner_lateral='" . $archivo . "' WHERE conf_id=1");
    
}

mysqli_query($conexionBdPrincipal,"UPDATE configuracion SET conf_url_top='" . $_POST["urlTop"] . "', conf_url_lateral='" . $_POST["urlLat"] . "' WHERE conf_id=1");


echo '<script type="text/javascript">window.location.href="../publicidad.php?msg=2";</script>';
exit();