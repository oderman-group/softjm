<?php
require_once("../sesion.php");

$idPagina = 227;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

if ($_FILES['archivo']['name'] != "") {
    $archivo = $_FILES['archivo']['name'];
    $destino = "files/documentos";
    move_uploaded_file($_FILES['archivo']['tmp_name'], $destino . "/" . $archivo);
}
mysqli_query($conexionBdPrincipal,"INSERT INTO documentos(doc_nombre, doc_documento, doc_cliente)VALUES('" . $_POST["nombre"] . "','" . $archivo . "','" . $_POST["cliente"] . "')");

$idInsertU = mysqli_insert_id($conexionBdPrincipal);


include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../documentos-editar.php?id=' . $idInsertU . '&msg=1&cte=' . $_POST["cte"] . '";</script>';
exit();
