<?php
require_once("../sesion.php");

if ($_FILES['documento']['name'] != "") {
    $destino = RUTA_PROYECTO."/usuarios/files/materiales";
    $extensionParte1 = explode(".", $_FILES['documento']['name']);
    $extension = end($extensionParte1);
    $nombreArchivoFinal = uniqid("file_") . "." . $extension;
    $rutaCompleta = $destino . "/" . $nombreArchivoFinal;
    move_uploaded_file($_FILES['documento']['tmp_name'], $rutaCompleta);

    $material = $nombreArchivoFinal;
    mysqli_query($conexionBdPrincipal,"UPDATE productos_materiales SET ppmt_material='" . $material . "' WHERE ppmt_id='" . $_POST["id"] . "'");
    
} else {
    $material = $_POST["video"];
    if ($_POST["tipo"] == 2) {
        mysqli_query($conexionBdPrincipal,"UPDATE productos_materiales SET ppmt_material='" . $material . "' WHERE ppmt_id='" . $_POST["id"] . "'");
    }
}
mysqli_query($conexionBdPrincipal,"UPDATE productos_materiales SET ppmt_tipo='" . $_POST["tipo"] . "', ppmt_activo='" . $_POST["activo"] . "', ppmt_nombre='" . $_POST["nombre"] . "' WHERE ppmt_id='" . $_POST["id"] . "'");

echo '<script type="text/javascript">window.location.href="../productos-materiales.php?msg=2&pdto=' . $_POST["pdto"] . '";</script>';
exit();