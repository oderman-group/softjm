<?php
require_once("../sesion.php");

if ($_FILES['logo']['name'] != "") {
    $extension = end(explode(".", $_FILES['logo']['name']));
    $logo = uniqid('prov_') . "." . $extension;
    $destino = "../files/proveedores";
    move_uploaded_file($_FILES['logo']['tmp_name'], $destino . "/" . $logo);

    mysqli_query($conexionBdPrincipal,"UPDATE proveedores SET prov_logo='" . $logo . "' WHERE prov_id='" . $_POST["id"] . "'");
    
}

mysqli_query($conexionBdPrincipal,"UPDATE proveedores SET prov_documento='" . $_POST["dni"] . "', prov_nombre='" . $_POST["nombre"] . "', prov_email='" . $_POST["email"] . "', prov_telefono='" . $_POST["telefono"] . "', prov_ciudad='" . $_POST["ciudad"] . "', prov_tipo_regimen='" . $_POST["regimen"] . "', prov_direccion='" . $_POST["direccion"] . "', prov_pais='" . $_POST["pais"] . "', prov_otra_ciudad='" . $_POST["otraCiudad"] . "' WHERE prov_id='" . $_POST["id"] . "'");


echo '<script type="text/javascript">window.location.href="../proveedores-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();