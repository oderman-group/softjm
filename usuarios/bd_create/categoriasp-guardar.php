<?php
    require_once("../sesion.php");

    $idPagina = 197;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    $esAjax = isset($_POST['ajax']) && (string) $_POST['ajax'] === '1';
    $grupo = isset($_POST["grupo"]) ? (int) $_POST["grupo"] : 1;
    if ($grupo < 1 || $grupo > 3) {
        $grupo = 1;
    }
    $habilitada = isset($_POST["habilitada"]) && (string) $_POST["habilitada"] === "1" ? 1 : 0;
    $codGrupo = isset($_POST["cod_grupo"]) ? trim((string) $_POST["cod_grupo"]) : '';
    $idEmpresa = (int) $_SESSION["dataAdicional"]["id_empresa"];
    if ($habilitada === 1 && $codGrupo !== '') {
        $codBusqueda = mysqli_real_escape_string($conexionBdPrincipal, $codGrupo);
        $ocupado = $conexionBdPrincipal->query("SELECT catp_nombre FROM productos_categorias WHERE catp_id_empresa='" . $idEmpresa . "' AND catp_habilitada=1 AND catp_cod_grupo='" . $codBusqueda . "' LIMIT 1");
        $otra = $ocupado ? mysqli_fetch_assoc($ocupado) : null;
        if ($otra) {
            if ($esAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'El código ya está asignado al grupo habilitado ' . $otra['catp_nombre']]);
                exit();
            }
            echo '<script type="text/javascript">alert("El código Ofima ya está asignado al grupo habilitado ' . addslashes($otra['catp_nombre']) . '."); window.location.href="../categoriasp-agregar.php";</script>';
            exit();
        }
    }
    $codSql = mysqli_real_escape_string($conexionBdPrincipal, $codGrupo);
    $nombreSql = mysqli_real_escape_string($conexionBdPrincipal, (string) $_POST["nombre"]);

	$conexionBdPrincipal->query("INSERT INTO productos_categorias(catp_nombre, catp_grupo, catp_cod_grupo, catp_habilitada, catp_id_empresa)VALUES('" . $nombreSql . "', '" . $grupo . "', " . ($codSql === '' ? "NULL" : "'" . $codSql . "'") . ", '" . $habilitada . "', '" . $idEmpresa . "')");
    
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    if ($esAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'id' => $idInsertU]);
        exit();
    }

	echo '<script type="text/javascript">window.location.href="../categoriasp-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();
