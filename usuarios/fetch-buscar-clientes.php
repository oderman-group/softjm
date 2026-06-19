<?php
include('sesion.php');
include(RUTA_PROYECTO . '/usuarios/class/Cliente.php');

if (isset($_GET['buscar'])) {
    $_GET['buscar'] = trim($_GET['buscar']);
}

include('includes/clientes-listado-filtros.php');
include('includes/clientes-listado-cargar.php');
include('includes/clientes-listado-render-filas.php');
