<?php   
require_once("../sesion.php");

$idPagina = 284;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

if ($_FILES['archivo']['name'] != "") {
    $archivo = $_FILES['archivo']['name'];
    $destino = "files/comprobantes";
    move_uploaded_file($_FILES['archivo']['tmp_name'], $destino . "/" . $archivo);
    mysqli_query($conexionBdPrincipal,"UPDATE facturacion_abonos SET fpab_comprobante='" . $archivo . "' WHERE fpab_id='" . $_POST["id"] . "'");
}
mysqli_query($conexionBdPrincipal,"UPDATE facturacion_abonos SET fpab_fecha_abono='" . $_POST["fecha"] . "', fpab_valor='" . $_POST["valor"] . "', fpab_observaciones='" . $_POST["observaciones"] . "', fpab_medio_pago='" . $_POST["medio"] . "', fpab_responsable_modificacion='" . $_SESSION["id"] . "', fpab_fecha_ultima_modificacion=now() WHERE fpab_id='" . $_POST["id"] . "'");

//Calculamos el saldo
$abonos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT sum(fpab_valor), fact_valor, fact_id FROM facturacion_abonos, facturacion
WHERE fpab_factura='" . $_POST["fact"] . "' AND fact_id='" . $_POST["fact"] . "'"));
$saldoFinal = $abonos[1] - $abonos[0];
if ($saldoFinal <= 0) {
    mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=1 WHERE fact_id='" . $_POST["fact"] . "' AND fact_estado!=3");
    
} else {
    mysqli_query($conexionBdPrincipal,"UPDATE facturacion SET fact_estado=2 WHERE fact_id='" . $_POST["fact"] . "' AND fact_estado!=3");
    
}


echo '<script type="text/javascript">window.location.href="../facturacion-abonos-editar.php?id=' . $_POST["id"] . '&msg=2&fact=' . $_POST["fact"] . '";</script>';
exit();	

 
