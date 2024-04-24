<?php
require_once("constantes.php");

$conexionBdPrincipal = new mysqli(SERVER, USER, PASS, MAINBD);
if (!$conexionBdPrincipal->set_charset("utf8mb4")) {
    die("Error al establecer el conjunto de caracteres: " . $conexionBdPrincipal->error);
}
$conexionBdAdmin     = new mysqli(SERVER, USER, PASS, BDADMIN);
if (!$conexionBdAdmin->set_charset("utf8mb4")) {
    die("Error al establecer el conjunto de caracteres: " . $conexionBdAdmin->error);
}
$conexionBdStore     = new mysqli(SERVER, USER, PASS, BDSTORE);
if (!$conexionBdStore->set_charset("utf8mb4")) {
    die("Error al establecer el conjunto de caracteres: " . $conexionBdStore->error);
}