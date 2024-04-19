<?php
include("../sesion.php");

$search = $_GET["term"]; 

$sql = "SELECT serv_id, serv_nombre FROM servicios WHERE serv_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' AND (serv_id LIKE '%$search%' OR serv_nombre LIKE '%$search%')";
$result = mysqli_query($conexionBdPrincipal, $sql);


$results = [];
while ($row = mysqli_fetch_assoc($result)) {
    $results[] = [

        "id" => $row["serv_id"],
        "text" => strtoupper($row["serv_nombre"]),
    ];
}

echo json_encode($results);
?>
