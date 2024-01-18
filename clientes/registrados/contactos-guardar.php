<?php 
include("sesion.php"); 
$idPagina = 333;

mysqli_query($conexionBdPrincipal,"INSERT INTO contactos(cont_nombre, cont_email, cont_cliente_principal, cont_celular, cont_sucursal, cont_rol)VALUES('".$_POST["nombre"]."','".$_POST["email"]."','".$_SESSION["id_cliente"]."','".$_POST["celular"]."','".$_POST["sucursal"]."','".$_POST["rol"]."')");
	
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	
	echo '<script type="text/javascript">window.location.href="contactos-editar.php?id='.$idInsertU.'&msg=1";</script>';
	exit();

?>