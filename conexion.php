<?php
require_once("constantes.php");

$conexionBdPrincipal = new mysqli(SERVER, USER, PASS, MAINBD);
$conexionBdAdmin     = new mysqli(SERVER, USER, PASS, BDADMIN);
$conexionBdStore     = new mysqli(SERVER, USER, PASS, BDSTORE);