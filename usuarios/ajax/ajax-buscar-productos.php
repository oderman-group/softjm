<?php
include("../sesion.php");

header('Content-Type: application/json; charset=utf-8');

$search = trim($_GET['term'] ?? $_GET['q'] ?? '');
if ($search === '') {
    echo json_encode([]);
    exit;
}

$idEmpresa = (int) ($_SESSION['dataAdicional']['id_empresa'] ?? 0);
$searchEsc = mysqli_real_escape_string($conexionBdPrincipal, $search);

$soloHabilitados = (string) ($_GET['habilitado'] ?? '') === '1';
$filtroHabilitado = $soloHabilitados ? " AND prod_habilitado='1'" : '';

$sql = "SELECT prod_id, prod_referencia, prod_nombre, prod_existencias, prod_habilitado
        FROM productos
        WHERE prod_id_empresa='" . $idEmpresa . "'
          AND (prod_id LIKE '%" . $searchEsc . "%'
            OR prod_nombre LIKE '%" . $searchEsc . "%'
            OR prod_referencia LIKE '%" . $searchEsc . "%')"
        . $filtroHabilitado . "
        ORDER BY prod_nombre
        LIMIT 50";
$result = mysqli_query($conexionBdPrincipal, $sql);

$results = [];
while ($result && ($row = mysqli_fetch_assoc($result))) {
    $results[] = [
        'id' => $row['prod_id'],
        'text' => $row['prod_id'] . '. ' . $row['prod_referencia'] . ' ' . strtoupper($row['prod_nombre']) . ' - [HAY ' . $row['prod_existencias'] . ']',
        'habilitado' => ((int) ($row['prod_habilitado'] ?? 0) === 1) ? 1 : 0,
    ];
}

echo json_encode($results);
