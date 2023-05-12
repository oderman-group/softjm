<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"UPDATE cupones SET
cupo_descuento='".$_POST["descuento"]."',
cupo_activo='".$_POST["estado"]."',
cupo_redimido='".$_POST["redimido"]."',
cupo_compra_minima='".$_POST["compraMinima"]."',
cupo_vencimiento='".$_POST["fechaVencimiento"]."',
cupo_cliente='".$_POST["cliente"]."'
WHERE cupo_id='".$_POST["id"]."'");

echo '<script type="text/javascript">window.location.href="../cupones.php?id='.$_POST["id"].'&msg=2";</script>';
exit();