<?php
include("../sesion.php");
include_once RUTA_PROYECTO."/usuarios/class/Producto.php";
require_once RUTA_PROYECTO.'/usuarios/class/Tickets.php';
require_once RUTA_PROYECTO.'/usuarios/class/Cliente.php';
require_once RUTA_PROYECTO.'/usuarios/includes/api-ofima-conexion.php';

try {
	if (!Modulos::validarRol([79], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {
		throw new Exception("No tiene permiso para cambiar el cliente de la cotización.");
	}

	$idCliente = intval($_POST["idCliente"]);
	$idCotizacion = intval($_POST["idCotizacion"]);

	if ($idCliente <= 0 || $idCotizacion <= 0) {
		throw new Exception("Datos de cliente o cotización inválidos");
	}

	$cotizacionActual = mysqli_fetch_assoc($conexionBdPrincipal->query(
		"SELECT cotiz_id, cotiz_cliente, cotiz_vendida FROM cotizacion WHERE cotiz_id=" . $idCotizacion . " AND cotiz_id_empresa='" . (int) $idEmpresa . "' LIMIT 1"
	));
	if (!$cotizacionActual) {
		throw new Exception("La cotización no existe.");
	}
	$pedidoExistente = mysqli_fetch_assoc($conexionBdPrincipal->query(
		"SELECT pedid_id FROM pedidos WHERE pedid_cotizacion=" . $idCotizacion . " AND pedid_id_empresa='" . (int) $idEmpresa . "' LIMIT 1"
	));
	if ($pedidoExistente || (int) ($cotizacionActual['cotiz_vendida'] ?? 0) === 1) {
		throw new Exception("Esta cotización ya generó pedido. No se puede cambiar el cliente.");
	}

	$restringirZona = !Modulos::validarRol([383], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion);
	$sqlCliente = "SELECT cli.cli_id, cli.cli_integrado_ofima, cli.cli_papelera, cli.cli_categoria
		FROM clientes cli
		WHERE cli.cli_id=" . $idCliente . " AND cli.cli_id_empresa='" . (int) $idEmpresa . "'";
	$sqlCliente = Cliente::appendFiltrosVisibilidadAnalyticsWhere(
		$sqlCliente,
		(int) ($_SESSION['id'] ?? 0),
		$restringirZona,
		(int) ($cotizacionActual['cotiz_cliente'] ?? 0)
	);
	$clienteNuevo = mysqli_fetch_assoc($conexionBdPrincipal->query($sqlCliente . " LIMIT 1"));
	if (!$clienteNuevo) {
		throw new Exception("Ese cliente no está disponible para esta cotización.");
	}
	$esElMismo = (int) $clienteNuevo['cli_id'] === (int) $cotizacionActual['cotiz_cliente'];
	if (!$esElMismo) {
		if ((int) ($clienteNuevo['cli_papelera'] ?? 0) === 1) {
			throw new Exception("Solo puede elegir clientes habilitados.");
		}
		if (ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa) && (int) ($clienteNuevo['cli_integrado_ofima'] ?? 0) !== 1) {
			throw new Exception("Solo puede elegir clientes habilitados.");
		}
		if (!Modulos::validarRol([393], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)
			&& (int) ($clienteNuevo['cli_categoria'] ?? 0) === (int) CLI_CATEGORIA_DEALER) {
			throw new Exception("No puede asignar un cliente dealer.");
		}
	} else {
		$response = [
			'success' => true,
			'message' => 'El cliente no cambió.'
		];
		echo json_encode($response);
		exit;
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
		'message' => $e->getMessage() !== '' ? $e->getMessage() : "Hubo un error al actualizar el cliente en la cotización"
	];
}

echo json_encode($response);
