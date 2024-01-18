<?php
require_once("../sesion.php");

$idPagina = 296;

if ($_FILES['archivo']['name'] != "") {
    $archivo = $_FILES['archivo']['name'];
    $destino = "files/comprobantes";
    move_uploaded_file($_FILES['archivo']['tmp_name'], $destino . "/" . $archivo);
}
mysqli_query($conexionBdPrincipal,"INSERT INTO facturacion_abonos(fpab_factura, fpab_fecha_abono, fpab_valor, fpab_fecha_registro, fpab_observaciones, fpab_medio_pago, fpab_responsable_registro, fpab_comprobante)VALUES('" . $_POST["fact"] . "','" . $_POST["fecha"] . "','" . $_POST["valor"] . "',now(),'" . $_POST["observaciones"] . "','" . $_POST["medio"] . "','" . $_SESSION["id"] . "','" . $archivo . "')");

$idInsertU = mysqli_insert_id($conexionBdPrincipal);
//Calculamos el saldo
$abonos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT sum(fpab_valor), fact_valor, fact_id FROM facturacion_abonos, facturacion
WHERE fpab_factura='" . $_POST["fact"] . "' AND fact_id='" . $_POST["fact"] . "'"));
$saldoFinal = $abonos[1] - $abonos[0];
if ($saldoFinal <= 0) {
    mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=1 WHERE fact_id='" . $_POST["fact"] . "' AND fact_estado!=3");
    
} else {
    mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=2 WHERE fact_id='" . $_POST["fact"] . "' AND fact_estado!=3");
    
}


include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../facturacion-abonos-editar.php?id=' . $idInsertU . '&msg=1&fact=' . $_POST["fact"] . '";</script>';
exit();