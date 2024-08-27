<?php
include("../sesion.php");
include_once RUTA_PROYECTO."/usuarios/class/Producto.php";

try {
	if (empty($_GET["idRegistro"])) {
		throw new Exception("No se ha recibido el ID de registro.");
	}

	if (!isset($_GET["existencias"])) {
		throw new Exception("No se ha recibido la cantidad de existencias.");
	}

	if (empty($_GET["existencias"])) {
		$_GET["existencias"] = 0;
	}
	
	mysqli_query($conexionBdPrincipal,"UPDATE productos_bodegas 
	SET prodb_existencias = '".$_GET["existencias"]."', 
	prodb_fecha_actualizacion = now(), 
	prodb_usuario_actualizacion = '".$_SESSION["id"]."' 
	WHERE prodb_id='".$_GET["idRegistro"]."'
	");
} catch (Exception $e) {
?>
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<i class="icon-exclamation-sign"></i><strong>Error!</strong> Ha ocurrido un error. <?php echo $e->getMessage(); ?>
	</div>
<?php
	exit();
}

?>

<div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<i class="icon-exclamation-sign"></i><strong>Exito!</strong> Los cambios ya se guardaron y todo está bien.
</div>