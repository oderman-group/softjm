<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"INSERT INTO dealer(deal_nombre)VALUES('" . $_POST["nombre"] . "')");
	
$idInsertU = mysqli_insert_id($conexionBdPrincipal);
$numero = (count($_POST["clientes"]));
$contador = 0;
mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_categorias WHERE cpcat_categoria='" . $idInsertU . "'");

while ($contador < $numero) {
	mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_categorias(cpcat_cliente, cpcat_categoria)VALUES(" . $_POST["clientes"][$contador] . ",'" . $idInsertU . "')");
	
	$contador++;
}
echo '<script type="text/javascript">window.location.href="../dealer-editar.php?id=' . $idInsertU . '&msg=1";</script>';
exit();