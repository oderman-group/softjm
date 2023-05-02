<?php
require_once("../sesion.php");

$numero = (count($_FILES['documento']['name']));
if ($numero > 0 and $_FILES['documento']['name'][0] != "") {
	$contador = 0;
	while ($contador < $numero) {
		$destino = RUTA_PROYECTO."/usuarios/files/materiales";
		$extensionParte1 = explode(".", $_FILES['documento']['name'][$contador]);
		$extension = end($extensionParte1);
		$nombreArchivoFinal = uniqid("file_") . "." . $extension;
		$rutaCompleta = $destino . "/" . $nombreArchivoFinal;
		move_uploaded_file($_FILES['documento']['tmp_name'][$contador], $rutaCompleta);
		
		$material = $nombreArchivoFinal;
		mysqli_query($conexionBdPrincipal,"INSERT INTO productos_materiales(ppmt_material, ppmt_tipo, ppmt_activo, ppmt_producto, ppmt_nombre)VALUES('" . $material . "','" . $_POST["tipo"] . "','" . $_POST["activo"] . "','" . $_POST["pdto"] . "','" . $_POST["nombre"] . "')");
		
		$contador++;
	}
} else {
	$material = $_POST["video"];
	mysqli_query($conexionBdPrincipal,"INSERT INTO productos_materiales(ppmt_material, ppmt_tipo, ppmt_activo, ppmt_producto, ppmt_nombre)VALUES('" . $material . "','" . $_POST["tipo"] . "','" . $_POST["activo"] . "','" . $_POST["pdto"] . "','" . $_POST["nombre"] . "')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
}
echo '<script type="text/javascript">window.location.href="../productos-materiales.php?msg=1&pdto=' . $_POST["pdto"] . '";</script>';
exit();