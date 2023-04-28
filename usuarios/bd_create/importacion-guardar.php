<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"INSERT INTO importaciones(imp_fecha, imp_proveedor, imp_concepto, imp_liquidada, imp_responsable)VALUES('" . $_POST["fecha"] . "','" . $_POST["proveedor"] . "', '" . $_POST["concepto"] . "', 0, '" . $_SESSION["id"] . "')");

$idInsert = mysqli_insert_id($conexionBdPrincipal);

echo '<script type="text/javascript">window.location.href="../importacion-editar.php?id=' . $idInsert . '&msg=1";</script>';
exit();