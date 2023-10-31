<?php
require_once("../sesion.php");

$idPagina = 299;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
$nmsg = 8;
	$abonos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT sum(fpab_valor), fact_valor, fact_id, fact_fecha_real, fact_impuestos, fact_retencion, fact_descuento FROM facturacion_abonos, facturacion
	WHERE fpab_factura='" . $_GET["id"] . "' AND fact_id='" . $_GET["id"] . "'"));
	$impuestos = $abonos['fact_valor'] * $abonos['fact_impuestos'] / 100;
	$retencion = $abonos['fact_valor'] * $abonos['fact_retencion'] / 100;
	$descuento = $res['fact_valor'] * $abonos['fact_descuento'] / 100;
	$valorReal = ($abonos['fact_valor'] + $impuestos) - ($retencion + $descuento);
	$saldoFinal = $valorReal - $abonos[0];
	if ($saldoFinal > 0) {
		mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion_abonos(fpab_factura, fpab_fecha_abono, fpab_valor, fpab_fecha_registro, fpab_observaciones, fpab_medio_pago, fpab_responsable_registro)VALUES('" . $_GET["id"] . "','" . $abonos[3] . "','" . $saldoFinal . "',now(),'Abono automático por el saldo pendiente',8,'" . $_SESSION["id"] . "')");
		
		$idInsertU = mysqli_insert_id($conexionBdPrincipal);

		mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=1, fact_observacion=CONCAT(fact_observacion, ' <br>-- ', now(), ' Abono automático por el saldo pendiente y cambió a estado pagada') WHERE fact_id='" . $_GET["id"] . "' AND fact_estado!=3");
		
		$nmsg = 7;
	}

	
include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../facturacion.php?msg=' . $nmsg . '";</script>';
	exit();