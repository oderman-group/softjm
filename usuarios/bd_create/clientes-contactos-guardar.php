<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"INSERT INTO contactos(cont_nombre, cont_telefono, cont_email, cont_area, cont_cargo, cont_cliente_principal, cont_celular, cont_telefonos, cont_sucursal)VALUES('" . $_POST["nombre"] . "','" . $_POST["telefono"] . "','" . $_POST["email"] . "','" . $_POST["area"] . "','" . $_POST["cargo"] . "','" . $_POST["cliente"] . "','" . $_POST["celular"] . "','" . $_POST["telefonos"] . "','" . $_POST["sucursal"] . "')");
	
$idInsertU = mysqli_insert_id($conexionBdPrincipal);
echo '<script type="text/javascript">window.location.href="../clientes-contactos-editar.php?id=' . $idInsertU . '&msg=1&cte=' . $_POST["cte"] . '";</script>';
exit();