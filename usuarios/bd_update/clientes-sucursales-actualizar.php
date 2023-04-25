<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"UPDATE sucursales SET sucu_ciudad='" . $_POST["ciudad"] . "', sucu_direccion='" . $_POST["direccion"] . "', sucu_telefono='" . $_POST["telefono"] . "', sucu_celular='" . $_POST["celular"] . "', sucu_telefonos='" . $_POST["telefonos"] . "', sucu_nombre='" . $_POST["nombre"] . "' WHERE sucu_id='" . $_POST["id"] . "'");
	
echo '<script type="text/javascript">window.location.href="../clientes-sucursales-editar.php?id=' . $_POST["id"] . '&msg=1&cte=' . $_POST["cte"] . '";</script>';
exit();