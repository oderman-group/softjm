<?php
require_once("../sesion.php");

$idPagina = 324;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdAdmin,"INSERT INTO documentos(doc_nombre, doc_id_empresa)VALUES('" . $_POST["nombre"] . "','" . $_SESSION["dataAdicional"]["id_empresa"]."')");

$idInsertU = mysqli_insert_id($conexionBdPrincipal);


include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../documentos.php?id=&msg=1";</script>';
exit();
