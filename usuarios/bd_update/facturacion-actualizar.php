<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_cliente='" . $_POST["cliente"] . "',  fact_ultima_modificacion=now(), fact_usuario_modificacion='" . $_SESSION["id"] . "',  fact_fecha_real='" . $_POST["fechaFactura"] . "'  WHERE fact_id='" . $_POST["id"] . "'");
	

$numero = (count($_POST["producto"]));
$contador = 0;
mysqli_query($conexionBdPrincipal,"DELETE FROM facturacion_productos WHERE fpp_factura='" . $_POST["id"] . "'");

while ($contador < $numero) {
    mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion_productos(fpp_factura, fpp_producto)VALUES('" . $_POST["id"] . "'," . $_POST["producto"][$contador] . ")");
    $contador++;
}
echo '<script type="text/javascript">window.location.href="../facturacion-editar.php?id=' . $_POST["id"] . '&msg=2&cte='.$_POST['cte'].'";</script>';
exit();