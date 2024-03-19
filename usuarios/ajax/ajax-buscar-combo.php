<?php
include("../sesion.php");

$search = $_GET["term"]; 

$sql = "SELECT combo_id, combo_nombre FROM combos WHERE combo_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' AND combo_nombre LIKE '%$search%' ";
$result = mysqli_query($conexionBdPrincipal, $sql);


$results = [];
while ($row = mysqli_fetch_assoc($result)) {
    $results[] = [

        "id" => $row["combo_id"],
        "text" => strtoupper($row["combo_nombre"]),
    ];
}

echo json_encode($results);
?>
