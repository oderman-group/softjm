<?php
if(isset($_GET['cte'])){
	if ($_GET["cte"] == 1) {
		$_GET["id"] = base64_decode($_GET["id"]);
	} else {
		if ($_SESSION["id"] == "")
			header("Location:".RUTA_PROYECTO."/salir.php");
	}
}

$resultado = mysqli_fetch_array($conexionBdPrincipal->query("SELECT * FROM cotizacion
INNER JOIN clientes ON cli_id=cotiz_cliente
INNER JOIN sucursales ON sucu_id=cotiz_sucursal
INNER JOIN contactos ON cont_id=cotiz_contacto
INNER JOIN usuarios ON usr_id=cotiz_vendedor
WHERE cotiz_id='" . $_GET["id"] . "'"), MYSQLI_BOTH);

$total = number_format($resultado['cotiz_valor'] + ($resultado['cotiz_valor'] * ($resultado['cotiz_impuestos'] / 100)), 0, ",", ".");

if ($configu['conf_proveedor_cotizacion'] == 1) {
	$proveedor = mysqli_fetch_array($conexionBdPrincipal->query("SELECT * FROM proveedores WHERE prov_id='" . $resultado['cotiz_proveedor'] . "'"), MYSQLI_BOTH);
}