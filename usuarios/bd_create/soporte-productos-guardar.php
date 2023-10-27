<?php
require_once("../sesion.php");

$idPagina = 285;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

if ($_FILES['imagen']['name'] != "") {
	$imagen = $_FILES['imagen']['name'];
	$destino = "files/soporte";
	move_uploaded_file($_FILES['imagen']['tmp_name'], $destino . "/" . $imagen);
}
mysqli_query($conexionBdPrincipal,"INSERT INTO soporte_productos(sop_nombre, sop_descripcion, sop_imagen, sop_video, sop_nivel, sop_padre)VALUES('" . $_POST["nombre"] . "','" . $_POST["descripcion"] . "','" . $imagen . "','" . $_POST["video"] . "','" . $_POST["nivel"] . "','" . $_POST["padre"] . "')");

$idInsertU = mysqli_insert_id($conexionBdPrincipal);
echo '<script type="text/javascript">window.location.href="../soporte-productos-editar.php?id=' . $idInsertU . '&msg=1";</script>';
exit();
