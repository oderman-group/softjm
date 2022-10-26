<?php
require_once("../sesion.php");

$idPagina = 52;
include(RUTA_PROYECTO."/usuarios/verificar-paginas.php");

if ($_FILES['foto']['name'] != "") {
    $destino = "../files/fotos/";
    $fileName='fp_'.basename($_FILES['foto']['name']);
    $archivo=$destino.$fileName;
    move_uploaded_file($_FILES['foto']['tmp_name'],$archivo);

    $conexionBdPrincipal->query("UPDATE usuarios SET usr_foto='" . $fileName . "' WHERE usr_id='" . $_POST["id"] . "'");
}

$conexionBdPrincipal->query("UPDATE usuarios SET usr_login='" . $_POST["usuario"] . "', usr_nombre='" . $_POST["nombre"] . "', usr_email='" . $_POST["email"] . "', usr_tipo='" . $_POST["tipoU"] . "', usr_ciudad='" . $_POST["ciudad"] . "', usr_area='" . $_POST["area"] . "', usr_bloqueado='" . $_POST["bloqueado"] . "', usr_intentos_fallidos='" . $_POST["fallidos"] . "', usr_sucursal='" . $_POST["sucursal"] . "', usr_meta_ventas='" . $_POST["metaVentas"] . "'
WHERE usr_id='" . $_POST["id"] . "'");

include(RUTA_PROYECTO."/usuarios/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../usuarios-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();