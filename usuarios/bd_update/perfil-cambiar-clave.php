<?php
require_once("../sesion.php");

$idPagina = 178;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$rst_usr = $conexionBdPrincipal->query("SELECT * FROM usuarios 
    WHERE usr_id='" . $_SESSION["id"] . "' AND usr_clave=SHA1('" . $_POST["claveActual"] . "')");

$fila = mysqli_fetch_array($rst_usr, MYSQLI_BOTH);
$num = $rst_usr->num_rows;

if ($num > 0) {

    $validarClave = validarClave($_POST["clave"]);
    if($validarClave == '0'){
        echo '<script type="text/javascript">window.location.href="../perfil-editar.php?error=2";</script>';
        exit();
    }

    $conexionBdPrincipal->query("UPDATE usuarios SET  usr_clave=SHA1('" . $_POST["clave"] . "') WHERE usr_id='" . $_SESSION["id"] . "'");

    echo '<script type="text/javascript">window.location.href="../perfil-editar.php?msg=2";</script>';
    exit();
} else {
    echo '<script type="text/javascript">window.location.href="../perfil-editar.php?error=1";</script>';
    exit();
}