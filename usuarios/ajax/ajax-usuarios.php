<?php include("sesion.php");?>
<?php

//METAS DE VENTAS
if($_POST["proceso"]==1){

	mysqli_query($conexionBdPrincipal, "UPDATE usuarios SET usr_meta_ventas='".$_POST["valorActual"]."' WHERE usr_id='".$_POST["idUsuario"]."'");
	
}
?>

<div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<i class="icon-exclamation-sign"></i><strong>Exito!</strong> Los cambios ya se guardaron y todo está bien.
</div>