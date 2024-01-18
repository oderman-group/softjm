<?php   
require_once("../sesion.php");

$idPagina = 177;

validarReferencia('perfil-editar.php');


if ($_FILES['foto']['name'] != "") {
    $extensionParte1 = explode(".", $_FILES['foto']['name']);
    $extension = end($extensionParte1);
    $foto = uniqid('fp_') . "." . $extension;
    $destino = "../files/fotos";
    move_uploaded_file($_FILES['foto']['tmp_name'], $destino . "/" . $foto);

    $conexionBdPrincipal->query("UPDATE usuarios SET usr_foto='" . $foto . "' WHERE usr_id='" . $_SESSION["id"] . "'");
}

$conexionBdPrincipal->query("UPDATE usuarios SET
usr_nombre='" . htmlspecialchars ($_POST["nombre"]) . "', 
usr_email='" . $_POST["email"] . "' 
WHERE usr_id='" . $_SESSION["id"] . "'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../perfil-editar.php?msg=2";</script>';
exit();
