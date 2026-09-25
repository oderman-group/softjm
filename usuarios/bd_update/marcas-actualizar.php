<?php
require_once("../sesion.php");

$idPagina = 193;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
include_once(RUTA_PROYECTO."/usuarios/includes/api-ofima-conexion.php");

$esAjax = isset($_POST['ajax']) && (string) $_POST['ajax'] === '1';
$id = (int) $_POST["id"];
$idEmpresaSesion = (int) $_SESSION["dataAdicional"]["id_empresa"];
$codOfima = isset($_POST["cod_ofima"]) ? trim((string) $_POST["cod_ofima"]) : '';
if (ofimaIntegracionActiva($conexionBdPrincipal, $idEmpresaSesion) && $codOfima === '') {
    if ($esAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'El código es obligatorio mientras la integración Ofima esté activa.']);
        exit();
    }
    echo '<script type="text/javascript">alert("El código Ofima es obligatorio mientras la integración esté activa."); window.location.href="../marcas-editar.php?id=' . $id . '";</script>';
    exit();
}
$habilitada = isset($_POST["habilitada"]) && (string) $_POST["habilitada"] === "1" ? 1 : 0;
$codSql = mysqli_real_escape_string($conexionBdPrincipal, $codOfima);
$nombreSql = mysqli_real_escape_string($conexionBdPrincipal, (string) $_POST["nombre"]);
$conexionBdPrincipal->query("UPDATE marcas SET mar_nombre='" . $nombreSql . "', mar_cod_ofima=" . ($codSql === '' ? "NULL" : "'" . $codSql . "'") . ", mar_habilitada='" . $habilitada . "' WHERE mar_id='" . $id . "' AND mar_id_empresa='" . $idEmpresaSesion . "'");

include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

if ($esAjax) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true, 'id' => $id]);
    exit();
}

	echo '<script type="text/javascript">window.location.href="../marcas-editar.php?id=' . $id . '&msg=2";</script>';
exit();