<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"UPDATE dealer SET deal_nombre='" . $_POST["nombre"] . "' WHERE deal_id='" . $_POST["id"] . "'");
	
$numero = (count($_POST["clientes"]));
$contador = 0;
mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_categorias WHERE cpcat_categoria='" . $_POST["id"] . "'");

while ($contador < $numero) {
    mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_categorias(cpcat_cliente, cpcat_categoria)VALUES(" . $_POST["clientes"][$contador] . ",'" . $_POST["id"] . "')");
    
    $contador++;
}
echo '<script type="text/javascript">window.location.href="../dealer-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();