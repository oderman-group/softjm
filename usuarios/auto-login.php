<?php
include("sesion.php");

$validarGet = validarVariableGet($_GET['user']);

$_SESSION['admin'] = $_SESSION['id'];
$_SESSION['id'] = $_GET['user'];

header("Location:perfil-editar.php");