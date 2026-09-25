<?php
    require_once("../sesion.php");

    $idPagina = 192;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    include_once(RUTA_PROYECTO."/usuarios/includes/api-ofima-conexion.php");

    $esAjax = isset($_POST['ajax']) && (string) $_POST['ajax'] === '1';
    $codOfima = isset($_POST["cod_ofima"]) ? trim((string) $_POST["cod_ofima"]) : '';
    if (ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa) && $codOfima === '') {
        if ($esAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'El código es obligatorio mientras la integración Ofima esté activa.']);
            exit();
        }
        echo '<script type="text/javascript">alert("El código Ofima es obligatorio mientras la integración esté activa."); window.location.href="../marcas-agregar.php";</script>';
        exit();
    }
    $habilitada = isset($_POST["habilitada"]) && (string) $_POST["habilitada"] === "1" ? 1 : 0;
    $codSql = mysqli_real_escape_string($conexionBdPrincipal, $codOfima);
    $nombreSql = mysqli_real_escape_string($conexionBdPrincipal, (string) $_POST["nombre"]);
    $conexionBdPrincipal->query("INSERT INTO marcas(mar_nombre, mar_cod_ofima, mar_habilitada, mar_id_empresa)VALUES('" . $nombreSql . "', " . ($codSql === '' ? "NULL" : "'" . $codSql . "'") . ", '" . $habilitada . "', '". $idEmpresa ."')");

    $idInsertU = mysqli_insert_id($conexionBdPrincipal);

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    if ($esAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'id' => $idInsertU]);
        exit();
    }

    echo '<script type="text/javascript">window.location.href="../marcas-editar.php?id=' . $idInsertU . '&msg=1";</script>';
    exit();