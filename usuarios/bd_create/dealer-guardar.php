<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"INSERT INTO dealer(deal_nombre, deal_id_empresa)VALUES('" . $_POST["nombre"] ."', '".$_SESSION["dataAdicional"]["id_empresa"]."')");
	
$idInsertU = mysqli_insert_id($conexionBdPrincipal);
mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_categorias WHERE cpcat_categoria='" . $idInsertU . "'");

if(!empty($_POST["clientes"])){
	$numero = (count($_POST["clientes"]));
	$contador = 0;
	while ($contador < $numero) {
		mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_categorias(cpcat_cliente, cpcat_categoria)VALUES(" . $_POST["clientes"][$contador] . ",'" . $idInsertU . "')");
		
		$contador++;
	}
}
echo '<script type="text/javascript">window.location.href="../dealer-editar.php?id=' . $idInsertU . '&msg=1";</script>';
exit();