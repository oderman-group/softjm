<?php
require_once("../sesion.php");

$idPagina = 29;
if(isset($_POST["envio"])){
    $envio=$_POST["envio"];
}else{
    $envio=0;
}

$consulta=$conexionBdPrincipal->query("SELECT * FROM clientes WHERE cli_id='".$_POST["cliente"]."'");
$datosCliente = mysqli_fetch_array($consulta, MYSQLI_BOTH);

    $conexionBdPrincipal->query("INSERT INTO cotizacion(cotiz_fecha_propuesta, cotiz_cliente, cotiz_fecha_vencimiento, cotiz_vendedor, cotiz_creador, cotiz_sucursal, cotiz_contacto, cotiz_forma_pago, cotiz_fecha_creacion, cotiz_moneda, cotiz_observaciones, cotiz_envio, cotiz_proveedor, cotiz_id_empresa)VALUES('" . $_POST["fechaPropuesta"] . "','" . $_POST["cliente"] . "','" . $_POST["fechaVencimiento"] . "','" . $_POST["influyente"] . "','" . $_SESSION["id"] . "','" . $_POST["sucursal"] . "','" . $_POST["contacto"] . "','" . $_POST["formaPago"] . "',now(),'" . $_POST["moneda"] . "','" . $_POST["notas"] . "','" . $envio . "','" . $_POST["proveedor"] . "','" . $idEmpresa . "' )");
    $idInsert = mysqli_insert_id($conexionBdPrincipal);

    require("guardar-productos-cotizacion.php");

    require("guardar-combos-cotizacion.php");

    require("guardar-servicios-cotizacion.php");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../cotizaciones-editar.php?id=' . $idInsert . '&msg=1";</script>';
    exit();