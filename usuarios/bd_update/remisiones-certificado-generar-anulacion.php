<?php
include("../sesion.php");

$consultaAnulado = mysqli_query($conexionBdPrincipal,"SELECT * FROM certificados_anulados WHERE certanu_id_certificado='".$_REQUEST["id"]."'");
$numAnulado=mysqli_num_rows($consultaAnulado);

$version=($numAnulado+1);
mysqli_query($conexionBdPrincipal,"INSERT INTO certificados_anulados(certanu_id_certificado,certanu_version,certanu_fecha_anulacion,certanu_descripcion) VALUES ('".$_REQUEST["id"]."','".$version."',now(),'".$_REQUEST["motivo"]."')");

mysqli_query($conexionBdPrincipal,"UPDATE remisiones SET rem_estado=1, rem_generar_certificado=0, rem_fecha_certificado=NULL, rem_estado_certificado=0, rem_certificado_anulado=1 WHERE rem_id='".$_REQUEST["id"]."'");

echo '<script type="text/javascript">window.location.href="../remisiones-editar.php?id='.$_REQUEST["id"].'";</script>';
exit();