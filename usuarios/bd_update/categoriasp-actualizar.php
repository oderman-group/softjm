<?php
    require_once("../sesion.php");

    $idPagina = 198;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    $esAjax = isset($_POST['ajax']) && (string) $_POST['ajax'] === '1';
    $id = (int) $_POST["id"];
    $idEmpresa = (int) $_SESSION["dataAdicional"]["id_empresa"];
    $habilitada = isset($_POST["habilitada"]) && (string) $_POST["habilitada"] === "1" ? 1 : 0;
    $codGrupo = isset($_POST["cod_grupo"]) ? trim((string) $_POST["cod_grupo"]) : '';
    if ($habilitada === 1 && $codGrupo !== '') {
        $codBusqueda = mysqli_real_escape_string($conexionBdPrincipal, $codGrupo);
        $ocupado = $conexionBdPrincipal->query("SELECT catp_nombre FROM productos_categorias WHERE catp_id_empresa='" . $idEmpresa . "' AND catp_habilitada=1 AND catp_cod_grupo='" . $codBusqueda . "' AND catp_id<>'" . $id . "' LIMIT 1");
        $otra = $ocupado ? mysqli_fetch_assoc($ocupado) : null;
        if ($otra) {
            if ($esAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'El código ya está asignado al grupo habilitado ' . $otra['catp_nombre']]);
                exit();
            }
            echo '<script type="text/javascript">alert("El código Ofima ya está asignado al grupo habilitado ' . addslashes($otra['catp_nombre']) . '."); window.location.href="../categoriasp-editar.php?id=' . $id . '";</script>';
            exit();
        }
    }
    $codSql = mysqli_real_escape_string($conexionBdPrincipal, $codGrupo);
    $nombreSql = mysqli_real_escape_string($conexionBdPrincipal, (string) $_POST["nombre"]);

	$conexionBdPrincipal->query("UPDATE productos_categorias SET catp_nombre='" . $nombreSql . "', catp_habilitada='" . $habilitada . "', catp_cod_grupo=" . ($codSql === '' ? "NULL" : "'" . $codSql . "'") . " WHERE catp_id='" . $id . "' AND catp_id_empresa = '" . $idEmpresa . "'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    if ($esAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'id' => $id]);
        exit();
    }

	echo '<script type="text/javascript">window.location.href="../categoriasp-editar.php?id=' . $id . '&msg=2";</script>';
	exit();
