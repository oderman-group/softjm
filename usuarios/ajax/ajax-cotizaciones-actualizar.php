<?php
include("../sesion.php");
include_once RUTA_PROYECTO."/usuarios/class/Producto.php";

try {
	//Consultamos para actualizar la sucursal
	$consultaSucursal = $conexionBdPrincipal->query("SELECT sucu_id FROM sucursales 
	WHERE sucu_cliente_principal='".$_POST["idCliente"]."'");

	$numSucursales    = $consultaSucursal->num_rows;
	$idSucursalNueva = "NULL";

	if ($numSucursales == 1) {
		$idSucursalNueva = mysqli_fetch_array($consultaSucursal, MYSQLI_ASSOC)['sucu_id'];
	}

	//Consultamos para actualizar el contacto
	$consultaContactos = $conexionBdPrincipal->query("SELECT cont_id FROM contactos 
	WHERE cont_cliente_principal='".$_POST["idCliente"]."'");

	$numContactos    = $consultaContactos->num_rows;
	$idContactoNuevo = "NULL";

	if ($numContactos == 1) {
		$idContactoNuevo = mysqli_fetch_array($consultaContactos, MYSQLI_ASSOC)['cont_id'];
	}

	//Actualizamos los datos en la cotización
	mysqli_query($conexionBdPrincipal,"UPDATE cotizacion 
	SET cotiz_cliente=".$_POST["idCliente"].", 
	cotiz_sucursal=".$idSucursalNueva.", 
	cotiz_contacto=".$idContactoNuevo.", 
	cotiz_ultima_modificacion=now(), 
	cotiz_usuario_modificacion='".$_SESSION["id"]."',
	cotiz_version = cotiz_version + 1 
	WHERE cotiz_id=".$_POST["idCotizacion"]);

	$response = [
		'success' => true,
		'message' => "El cliente fue actualizado en la cotización correctamente."
	];

} catch (Exception $e) {
	$response = [
		'success' => false,
		'message' => "Hubo un error al actualizar el cliente en la cotización"
	];
}

echo json_encode($response);