<?php
require_once("../sesion.php");

$idPagina = 196;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

if($_POST['zonasTodas'] == 1){

    $conexionBdPrincipal->query("DELETE FROM zonas_usuarios WHERE zpu_usuario='" . $_POST["id"] . "'");


    $zonasConsulta = $conexionBdPrincipal->query("SELECT * FROM zonas",$conexion);
    while($zonasDatos = mysqli_fetch_array($zonasConsulta, MYSQLI_BOTH)){

        $conexionBdPrincipal->query("INSERT INTO zonas_usuarios(zpu_usuario, zpu_zona)VALUES('" . $_POST["id"] . "'," . $zonasDatos[0] . ")");

    }



}else{

    $numero = (count($_POST["zona"]));
    $contador = 0;
    $conexionBdPrincipal->query("DELETE FROM zonas_usuarios WHERE zpu_usuario='" . $_POST["id"] . "'");
    while ($contador < $numero) {
        $conexionBdPrincipal->query("INSERT INTO zonas_usuarios(zpu_usuario, zpu_zona)VALUES('" . $_POST["id"] . "'," . $_POST["zona"][$contador] . ")");
        $contador++;
    }
}


//asignar clientes
$numero = (count($_POST["cliente"]));
$contador = 0;
$conexionBdPrincipal->query("DELETE FROM clientes_usuarios WHERE cliu_usuario='" . $_POST["id"] . "'");
while ($contador < $numero) {
        $conexionBdPrincipal->query("INSERT INTO clientes_usuarios(cliu_usuario, cliu_cliente, cliu_fecha)VALUES('" . $_POST["id"] . "'," . $_POST["cliente"][$contador] . ", now())");
        $contador++;
}

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="usuarios-editar-zonas.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();