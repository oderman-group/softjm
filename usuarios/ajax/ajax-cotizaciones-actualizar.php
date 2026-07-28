<?php
include("../sesion.php");
include_once RUTA_PROYECTO."/usuarios/class/Producto.php";
require_once RUTA_PROYECTO.'/usuarios/class/Tickets.php';

try {
	$idCliente = intval($_POST["idCliente"]);
	$idCotizacion = intval($_POST["idCotizacion"]);

	if ($idCliente <= 0 || $idCotizacion <= 0) {
		throw new Exception("Datos de cliente o cotización inválidos");
	}

	//Consultamos para actualizar la sucursal
	$consultaSucursal = $conexionBdPrincipal->query("SELECT sucu_id FROM sucursales 
	WHERE sucu_cliente_principal='".$idCliente."'");

	$numSucursales    = $consultaSucursal->num_rows;
	$idSucursalNueva = "NULL";
	$idSucursalInt = null;

	if ($numSucursales == 1) {
		$idSucursalInt = (int) mysqli_fetch_array($consultaSucursal, MYSQLI_ASSOC)['sucu_id'];
		$idSucursalNueva = $idSucursalInt;
	}

	//Consultamos para actualizar el contacto
	$consultaContactos = $conexionBdPrincipal->query("SELECT cont_id FROM contactos 
	WHERE cont_cliente_principal='".$idCliente."'");

	$numContactos    = $consultaContactos->num_rows;
	$idContactoNuevo = "NULL";

	if ($numContactos == 1) {
		$idContactoNuevo = mysqli_fetch_array($consultaContactos, MYSQLI_ASSOC)['cont_id'];
	}

	//Actualizamos los datos en la cotización
	mysqli_query($conexionBdPrincipal,"UPDATE cotizacion 
	SET cotiz_cliente=".$idCliente.", 
	cotiz_sucursal=".$idSucursalNueva.", 
	cotiz_contacto=".$idContactoNuevo.", 
	cotiz_ultima_modificacion=now(), 
	cotiz_usuario_modificacion='".$_SESSION["id"]."',
	cotiz_version = cotiz_version + 1 
	WHERE cotiz_id=".$idCotizacion);

	// Mantener alineado el ticket vinculado (evita cotización y ticket con clientes distintos)
	Ticket::sincronizarClienteDesdeCotizacion(
		$idCotizacion,
		$idCliente,
		$conexionBdPrincipal,
		$idSucursalInt
	);

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
