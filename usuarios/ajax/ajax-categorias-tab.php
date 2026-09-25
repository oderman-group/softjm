<?php
include("../sesion.php");
include_once(RUTA_PROYECTO."/usuarios/includes/api-ofima-conexion.php");

$tabFragmento = isset($_GET['tab']) ? (string) $_GET['tab'] : '';
if (!in_array($tabFragmento, ['g2', 'marcas', 'g3'], true)) {
    http_response_code(400);
    exit;
}

$ofimaActiva = ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa);

header('Content-Type: text/html; charset=utf-8');
include(RUTA_PROYECTO."/usuarios/includes/categoriasp-tab-fragmento.php");
