<?php
require_once("../sesion.php");

if ($_FILES['archivo']['name'] != "") {
    $archivo = $_FILES['archivo']['name'];
    $destino = "files/adjuntos";
    move_uploaded_file($_FILES['archivo']['tmp_name'], $destino . "/" . $archivo);
    mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_archivo='" . $archivo . "' WHERE cseg_id='" . $_POST["id"] . "'");
}

$datos = 0;
if ($_POST["datos"] == 1) {
    $datos = 1;
}

$cotizo = 0;
if ($_POST["cotizo"] == 1) {
    $cotizo = 1;
}

$vendio = 0;
if ($_POST["vendio"] == 1) {
    $vendio = 1;
}

mysqli_query($conexionBdPrincipal,"UPDATE cliente_seguimiento SET cseg_cliente='" . $_POST["cliente"] . "', cseg_observacion='" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["observaciones"]) . "', cseg_fecha_proximo_contacto='" . $_POST["fechaPC"] . "', cseg_asunto='" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["asunto"]) . "', cseg_usuario_encargado='" . $_POST["encargado"] . "', cseg_cotizacion='" . $_POST["cotizacion"] . "', cseg_fecha_contacto='" . $_POST["fechaContacto"] . "', cseg_tipo='" . $_POST["tipoS"] . "', cseg_contacto='" . $_POST["contacto"] . "', cseg_canal='" . $_POST["canal"] . "', cseg_cotizo='" . $cotizo . "', cseg_vendio='" . $vendio . "', cseg_consiguio_datos='" . $datos . "', cseg_forma_contacto='" . $_POST["formaContacto"] . "' WHERE cseg_id='" . $_POST["id"] . "'");

echo '<script type="text/javascript">window.location.href="../clientes-seguimiento-editar.php?id=' . $_POST["id"] . '&msg=2&idTK=' . $_POST["idTK"] . '";</script>';
exit();