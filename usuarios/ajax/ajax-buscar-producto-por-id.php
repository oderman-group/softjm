<?php
include("../sesion.php");

$idProducto = $_GET["idProducto"] ?? null; 

$sql_producto = "SELECT 
    prod_id AS id_interno, 
    prod_foto AS foto_principal, 
    prod_nombre AS nombre, 
    prod_descripcion_corta AS descripcion_corta, 
    prod_costo AS costo, 
    prod_precio AS precio 
    FROM productos 
    WHERE prod_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."' 
    AND prod_id = '".mysqli_real_escape_string($conexionBdPrincipal, $idProducto)."' 
    LIMIT 1";

$result_producto = mysqli_query($conexionBdPrincipal, $sql_producto);
$producto = mysqli_fetch_assoc($result_producto);

if (!$producto) {
    echo json_encode(["success" => false, "message" => "Producto no encontrado o no pertenece a la empresa."]);
    exit;
}

$sql_galeria = "SELECT 
    pgal_foto AS ruta, 
    pgal_id AS id 
FROM productos_galeria 
WHERE pgal_producto = '".mysqli_real_escape_string($conexionBdPrincipal, $idProducto)."'";

$result_galeria = mysqli_query($conexionBdPrincipal, $sql_galeria);
$imagenes = mysqli_fetch_assoc($result_galeria);

$producto["imagenes"] = $imagenes;
$producto["success"] = true;

echo json_encode($producto);
?>