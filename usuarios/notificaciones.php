<?php
if(isset($_GET["msg"])){
?>

	<?php if($_GET["msg"]==1){?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Exito!</strong> El registro fue ingresado correctamente y todo está bien.
		</div>
	<?php }?>

	<?php if($_GET["msg"]==2){?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Exito!</strong> Los cambios ya se guardaron y todo está bien.
		</div>
	<?php }?>

	<?php if($_GET["msg"]==3){?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Exito!</strong> El registro fue eliminado correctamente.
		</div>
	<?php }?>

	<?php if($_GET["msg"]==4){?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Exito!</strong> La encuesta fue enviada correctamente.
		</div>
	<?php }?>

	<?php if($_GET["msg"]==5){?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Exito!</strong> Acción completada 2.
		</div>
	<?php }?>

	<?php if($_GET["msg"]==6){?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Exito!</strong> La cotización fue enviada correctamente.
		</div>
	<?php }?>

	<?php if($_GET["msg"]==7){?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Exito!</strong> El abono fue generado correctamente y saldó en su totalidad la factura.
		</div>
	<?php }?>

	<?php if($_GET["msg"]==8){?>
		<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Error!</strong> Esta factura no tiene saldo pendiente por abonar.
		</div>
	<?php }?>

	<?php if($_GET["msg"]==9){?>
		<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Error!</strong> Debes escoger un cliente o un tiket para hacer el seguimiento.
		</div>
	<?php }?>

	<?php if($_GET["msg"]==10){?>
		<div class="alert alert-info">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Información!</strong> Por favor ahora crea el seguimiento para el tiket que acabas de crear.
		</div>
	<?php }?>

	<?php if($_GET["msg"]==11){?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Información!</strong> El producto fue replicado correctamente al área de soporte operativo
		</div>
	<?php }?>

	<?php if($_GET["msg"] == 12){?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Información!</strong> Los precios actuales de los productos fueron guardados en el historial de precios.
		</div>
	<?php }?>

	<?php if($_GET["msg"]==13){?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Exito!</strong> Las credenciales fueron enviadas correctamente.
		</div>
	<?php }?>

	<?php if($_GET["msg"]==14){?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Exito!</strong> Las claves de los clientes fueron cambiadas correctamente.
		</div>
	<?php }?>


<?php }?>

<?php
if(isset($_GET["error"])){
?>

	<?php if($_GET["error"]==1){?>
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Error!</strong> La contraseña actual es incorrecta.
		</div>
	<?php }?>

	<?php if($_GET["error"]==2){?>
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="icon-exclamation-sign"></i><strong>Error!</strong> La contraseña nueva no cumple con los criterios de aceptación.
		</div>
	<?php }?>

<?php }?>