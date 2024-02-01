<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"INSERT INTO cupones(cupo_codigo, cupo_descuento, cupo_activo, cupo_redimido, cupo_compra_minima, cupo_creacion, cupo_vencimiento, cupo_cliente, cupo_id_empresa)VALUES('" . $_POST["codigo"] . "','" . $_POST["descuento"] . "','" . $_POST["estado"] . "',0,'" . $_POST["compraMinima"] . "',now(),'" . $_POST["fechaVencimiento"] . "','" . $_POST["cliente"] . "','" .$_SESSION["dataAdicional"]["id_empresa"]."')");

echo '<script type="text/javascript">window.location.href="../cupones.php?id=' . $_POST["id"] . '&msg=1";</script>';
exit();