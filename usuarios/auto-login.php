<?php
include("sesion.php");
$idPagina = 344;
$validarGet = validarVariableGet($_GET['user']);

$_SESSION['admin'] = $_SESSION['id'];
$_SESSION['id'] = $_GET['user'];

$rst_usr = $conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_id='".$_SESSION['id']."'");
$num = $rst_usr->num_rows;
$fila = mysqli_fetch_array($rst_usr, MYSQLI_BOTH);

//INICIO SESION
$consultaEmpresaSesion = $conexionBdAdmin->query("SELECT * FROM clientes_orion WHERE clio_id=".$fila['usr_id_empresa']);
$datosEmpresaSesion    = mysqli_fetch_array($consultaEmpresaSesion, MYSQLI_BOTH);

$consultaRolesUsuario = $conexionBdPrincipal->query("SELECT upr_id_rol, utipo_nombre FROM ".BDADMIN.".usuarios_roles
INNER JOIN ".MAINBD.".usuarios_tipos ON utipo_id=upr_id_rol 
WHERE upr_id_empresa={$fila['usr_id_empresa']}
AND upr_id_usuario={$fila['usr_id']}
");

$_SESSION["dataAdicional"] = [
    'id_empresa' 	       => $datosEmpresaSesion['clio_id'],
    'nombre_empresa'       => $datosEmpresaSesion['clio_empresa'],
    'dominio_empresa'      => $datosEmpresaSesion['clio_dominio'],
    'usuario_acceso'       => $fila['usr_login'],
    'datos_usuario_actual' => $fila,
    'roles'                => null,
    'roles_nombre'         => null
];

while( $datosRolesUsuario = mysqli_fetch_array($consultaRolesUsuario, MYSQLI_BOTH) ) {
    $_SESSION["dataAdicional"]["roles"][]        = $datosRolesUsuario['upr_id_rol'];
    $_SESSION["dataAdicional"]["roles_nombre"][] = $datosRolesUsuario['utipo_nombre'];
}

header("Location:index.php");