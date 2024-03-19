<?php
require_once("../sesion.php");

$idPagina = 52;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

if ($_FILES['foto']['name'] != "") {
    $destino = RUTA_PROYECTO."/usuarios/files/fotos";
    $fileName = subirArchivosAlServidor($_FILES['foto'], 'fp', $destino);

    $conexionBdPrincipal->query("UPDATE usuarios SET usr_foto='" . $fileName . "' WHERE usr_id='" . $_POST["id"] . "' AND usr_id_empresa='" . $_SESSION["dataAdicional"]["id_empresa"] . "'");
}
$usuario = $_POST["usuario"] . $_SESSION["dataAdicional"]["dominio_empresa"];
$conexionBdPrincipal->query("UPDATE usuarios SET usr_login='" . $usuario . "', usr_nombre='" . $_POST["nombre"] . "', usr_email='" . $_POST["email"] . "', usr_ciudad='" . $_POST["ciudad"] . "', usr_area='" . $_POST["area"] . "', usr_bloqueado='" . $_POST["bloqueado"] . "', usr_intentos_fallidos='" . $_POST["fallidos"] . "', usr_sucursal='" . $_POST["sucursal"] . "', usr_meta_ventas='" . $_POST["metaVentas"] . "'
WHERE usr_id='" . $_POST["id"] . "' AND usr_id_empresa='" . $_SESSION["dataAdicional"]["id_empresa"] . "'");

// Obtener los roles seleccionados desde el formulario
$rolesSeleccionados = $_POST['tipoU'];

// Obtener los roles existentes del usuario desde la base de datos
$idUsuario = $_POST["id"];
$consultaRolesUsuario = $conexionBdAdmin->query("SELECT upr_id_rol FROM usuarios_roles WHERE upr_id_usuario = '$idUsuario'");
$rolesExistentes = [];
while ($fila = $consultaRolesUsuario->fetch_assoc()) {
    $rolesExistentes[] = $fila['upr_id_rol'];
}

if (!empty($_POST['tipoU'])) {
    $rolesAEliminar = array_diff($rolesExistentes, $rolesSeleccionados);
    $rolesAInsertar = array_diff($rolesSeleccionados, $rolesExistentes);

    if (!empty($rolesAEliminar)) {
        $rolesAEliminarStr = implode("','", $rolesAEliminar);
        $conexionBdAdmin->query("DELETE FROM usuarios_roles WHERE upr_id_usuario = '$idUsuario' AND upr_id_rol IN ('$rolesAEliminarStr')");
    }

    $upr_fec = date("Y-m-d H:i:s");
    $upr_responsable = $_SESSION["id"];
    $id_empresa = $_SESSION["dataAdicional"]["id_empresa"];
    if (!empty($rolesAInsertar)) {
        foreach ($rolesAInsertar as $rol) {
            $conexionBdAdmin->query("INSERT INTO usuarios_roles (upr_id_usuario, upr_id_rol, upr_fec, upr_responsable,upr_id_empresa) VALUES ('$idUsuario', '$rol', '$upr_fec', '$upr_responsable', '$id_empresa')");
        }
    }
}

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="../usuarios-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
exit();