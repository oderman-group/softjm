<?php   
require_once("../sesion.php");

$idPagina = 290;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

if ($_FILES['imagen']['name'] != "") {
    $imagen = $_FILES['imagen']['name'];
    $destino = "files/soporte";
    move_uploaded_file($_FILES['imagen']['tmp_name'], $destino . "/" . $imagen);
    mysqli_query($conexionBdPrincipal,"UPDATE soporte_productos SET sop_imagen='" . $imagen . "' WHERE sop_id='" . $_POST["id"] . "'");
}
mysqli_query($conexionBdPrincipal,"UPDATE soporte_productos SET sop_nombre='" . $_POST["nombre"] . "', sop_descripcion='" . $_POST["descripcion"] . "', sop_nivel='" . $_POST["nivel"] . "', sop_video='" . $_POST["video"] . "', sop_padre='" . $_POST["padre"] . "' WHERE sop_id='" . $_POST["id"] . "'");

echo '<script type="text/javascript">window.location.href="../soporte-productos-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();
 
