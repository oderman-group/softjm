<?php
include("sesion.php");
$_SESSION['admin'] = $_SESSION['id'];
$_SESSION['id'] = $_GET['user'];

header("Location:perfil-editar.php");