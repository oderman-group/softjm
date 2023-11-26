<?php include("sesion.php");
$idPagina = 329;
include("verificar-paginas.php");
?>
<?php
mysqli_query($conexionBdPrincipal,"UPDATE clientes SET cli_usuario='".$_POST["usuario"]."', cli_clave='".$_POST["clave"]."', cli_nombre='".$_POST["nombre"]."', cli_email='".$_POST["email"]."' WHERE cli_id='".$_SESSION["id_cliente"]."'");
echo '<script type="text/javascript">window.location.href="perfil-editar.php?msg=2";</script>';
exit();
?>