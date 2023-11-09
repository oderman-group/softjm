<?php
require_once("../sesion.php");
$clave = round($_POST["pais"]);
mysqli_query($conexionBdPrincipal,"INSERT INTO proveedores(prov_documento, prov_clave, prov_nombre, prov_email, prov_telefono, prov_ciudad, prov_fecha_registro, prov_responsable, prov_eliminado, prov_tipo_regimen, prov_direccion, prov_pais, prov_otra_ciudad, prov_id_empresa)VALUES('" . $_POST["dni"] . "', '" . $clave . "', '" . $_POST["nombre"] . "', '" . $_POST["email"] . "', '" . $_POST["telefono"] . "', '" . $_POST["ciudad"] . "', now(), '" . $_SESSION["id"] . "', 0, '" . $_POST["regimen"] . "', '" . $_POST["direccion"] . "', '" . $_POST["pais"] . "', '" . $_POST["otraCiudad"] . "', '" . $idEmpresa . "')");

$idInsertU = mysqli_insert_id($conexionBdPrincipal);

echo '<script type="text/javascript">window.location.href="../proveedores-editar.php?id=' . $idInsertU . '&msg=1";</script>';
exit();