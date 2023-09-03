<?php
include("sesion.php");
$_SESSION['id'] = $_SESSION['admin'];
$_SESSION['admin'] = '';
unset( $_SESSION["admin"] );

header("Location:index.php");