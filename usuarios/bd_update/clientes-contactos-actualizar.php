<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"UPDATE contactos SET cont_nombre='" . $_POST["nombre"] . "', cont_telefono='" . $_POST["telefono"] . "', cont_email='" . $_POST["email"] . "', cont_area='" . $_POST["area"] . "', cont_cargo='" . $_POST["cargo"] . "', cont_cliente_principal='" . $_POST["cliente"] . "', cont_celular='" . $_POST["celular"] . "', cont_telefonos='" . $_POST["telefonos"] . "', cont_sucursal='" . $_POST["sucursal"] . "' WHERE cont_id='" . $_POST["id"] . "'");

echo '<script type="text/javascript">window.location.href="../clientes-contactos-editar.php?id=' . $_POST["id"] . '&msg=2&cte=' . $_POST["cte"] . '";</script>';
exit();