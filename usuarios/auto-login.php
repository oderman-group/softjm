<?php
include("sesion.php");
$idPagina = 344;
$validarGet = validarVariableGet($_GET['user']);

$_SESSION['admin'] = $_SESSION['id'];
$_SESSION['id'] = $_GET['user'];

header("Location:index.php");