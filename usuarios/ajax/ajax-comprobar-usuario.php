<?php
include("../sesion.php");
if($_REQUEST['usuario']!=""){
$usuario = $_REQUEST['usuario'].$_SESSION["dataAdicional"]["dominio_empresa"];
$idUsuario = $_REQUEST['idUsuario'];
$jsonData = array();

$filtro = "";
if (!empty($idUsuario) && $idUsuario != 0) {
    $filtro = "AND usr_id != '".$idUsuario."'";
}

try {
    $consulta = $conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_login LIKE '%" . $usuario . "%' {$filtro}");
} catch ( Exception $e) {
    echo $e;
    exit();
}

if( mysqli_num_rows($consulta) <= 0 ){
    $jsonData['success'] = 0;
    $jsonData['message'] = '';
} else{
    
    $jsonData['success'] = 1;
    $jsonData['message'] = '<div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="icon-exclamation-sign"></i>Este Usuario Ya Se Encuentra Registrado</div>';
}

header('Content-type: application/json; charset=utf-8');
echo json_encode( $jsonData );
}