<?php include("sesion.php");
$idPagina = 334;
include("verificar-paginas.php");
?>
<?php
mysqli_query($conexionBdPrincipal,"UPDATE contactos SET cont_nombre='".$_POST["nombre"]."', cont_email='".$_POST["email"]."', cont_rol='".$_POST["rol"]."', cont_celular='".$_POST["celular"]."', cont_sucursal='".$_POST["sucursal"]."' WHERE cont_id='".$_POST["id"]."'");
echo '<script type="text/javascript">window.location.href="contactos-editar.php?id='.$_POST["id"].'&msg=2";</script>';
exit();
?>