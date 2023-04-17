<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"INSERT INTO sucursales(sucu_cliente_principal, sucu_ciudad, sucu_direccion, sucu_telefono, sucu_celular, sucu_telefonos, sucu_nombre)VALUES('" . $_POST["cte"] . "','" . $_POST["ciudad"] . "','" . $_POST["direccion"] . "','" . $_POST["telefono"] . "','" . $_POST["celular"] . "','" . $_POST["telefonos"] . "','" . $_POST["nombre"] . "')");
	
$idInsertU = mysqli_insert_id($conexionBdPrincipal);
echo '<script type="text/javascript">window.location.href="../clientes-sucursales-editar.php?id=' . $idInsertU . '&msg=1&cte=' . $_POST["cte"] . '";</script>';
exit();