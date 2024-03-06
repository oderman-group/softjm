<?php include("sesion.php");?>
<?php
$idPagina = 255; include("includes/verificar-paginas.php");
mysqli_query($conexionBdPrincipal, "INSERT INTO cliente_seguimiento(cseg_cliente, cseg_fecha_reporte, cseg_observacion, cseg_usuario_responsable, cseg_fecha_proximo_contacto, cseg_asunto, cseg_usuario_encargado, cseg_cotizacion, cseg_fecha_contacto)VALUES('".$_POST["cliente"]."',now(),'".$_POST["observaciones"]."','".$_SESSION["id"]."','".$_POST["fechaPC"]."','".$_POST["asunto"]."','".$_POST["encargado"]."','".$_POST["cotizacion"]."','".$_POST["fechaContacto"]."')");
echo "<b>Los cambios se guardaron correctamente. Puede cerrar la ventana pulsando el botón 'Cancel' o en la X.</b>";
?>