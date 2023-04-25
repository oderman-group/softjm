<?php
require_once("../sesion.php");

//Ingresar la factura
mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion(fact_cliente, fact_fecha,  fact_usuario_responsable, fact_fecha_real, fact_tipo, fact_estado )VALUES('" . $_POST["cliente"] . "', now(), '" . $_SESSION["id"] . "', '" . $_POST["fechaFactura"] . "', 1, 1)");

$idInsertU = mysqli_insert_id($conexionBdPrincipal);


$numero = (count($_POST["producto"]));
$contador = 0;
mysqli_query($conexionBdPrincipal,"DELETE FROM facturacion_productos WHERE fpp_factura='" . $idInsertU . "'");

while ($contador < $numero) {
	mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion_productos(fpp_factura, fpp_producto)VALUES('" . $idInsertU . "'," . $_POST["producto"][$contador] . ")");
	$contador++;
}

echo '<script type="text/javascript">window.location.href="../facturacion-editar.php?id=' . $idInsertU . '&msg=1&cte='.$_POST['cte'].'";</script>';
exit();