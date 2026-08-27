<?php
include("../sesion.php");

if($_POST["opcion"]==1){
	$documento = trim($_POST["usuario"] ?? '');
	$formatoJson = isset($_POST['format']) && $_POST['format'] === 'json';

	if ($documento === '') {
		if ($formatoJson) {
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode(['available' => false, 'message' => 'Ingrese el número de documento.']);
			exit();
		}
		echo "<span style='font-family:arial; text-align:center; color:red;'>Ingrese el n&uacute;mero de documento.</span>";
		exit();
	}

	$documentoEsc = mysqli_real_escape_string($conexionBdPrincipal, $documento);
	$consulta = $conexionBdPrincipal->query(
		"SELECT cli_id, cli_nombre FROM clientes
		 WHERE cli_usuario='" . $documentoEsc . "' AND cli_id_empresa='" . $idEmpresa . "'"
	);
	$clienteV = mysqli_fetch_array($consulta, MYSQLI_BOTH);

	if(isset($clienteV[0])){
		if ($formatoJson) {
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode([
				'available'     => false,
				'message'       => 'Ya existe un cliente con este número de documento.',
				'clienteId'     => $clienteV['cli_id'],
				'clienteNombre' => $clienteV['cli_nombre'],
				'editUrl'       => 'clientes-editar.php?id=' . $clienteV['cli_id'],
			]);
			exit();
		}
		echo "<span style='font-family:arial; text-align:center; color:red;'>Ya existe un cliente con este n&uacute;mero de NIT: <b><a href='clientes-editar.php?id=".$clienteV['cli_id']."'>".$clienteV['cli_nombre']."</a></b></div>";
		exit();
	}

	if ($formatoJson) {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(['available' => true, 'message' => 'Documento disponible, puede continuar.']);
		exit();
	}

	echo "<span style='font-family:arial; text-align:center; color:blue;'>Este NIT disponible, puedes continuar.</div>";
	exit();
}

if($_POST["opcion"]==2){
	$consulta=$conexionBdPrincipal->query("SELECT * FROM proveedores WHERE prov_documento='".trim($_POST["usuario"])."'");
	$proveedoresV = mysqli_fetch_array($consulta, MYSQLI_BOTH);
	if(isset($proveedoresV[0])){
		echo "<span style='font-family:arial; text-align:center; color:red;'>Ya existe un proveedores con este DNI: <b><a href='proveedores-editar.php?id=".$proveedoresV['prov_id']."'>".$proveedoresV['prov_nombre']."</a></b></div>";
		exit();
	}else{
		echo "<span style='font-family:arial; text-align:center; color:blue;'>DNI disponible, puedes continuar.</div>";
		exit();
	}
}

if($_POST["opcion"]==3){
	$consulta=$conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_referencia='".trim($_POST["idUnico"])."'");
	$datos = mysqli_fetch_array($consulta, MYSQLI_BOTH);
	if(isset($datos[0])){
		echo "<span style='font-family:arial; text-align:center; color:red;'>Ya existe un registro con esta Referencia: <b><a href='productos-editar.php?id=".$datos[0]."'>".$datos['prod_nombre']."</a></b></div>";
		exit();
	}else{
		echo "<span style='font-family:arial; text-align:center; color:blue;'>REFERENCIA disponible, puedes continuar.</div>";
		exit();
	}
}
?>