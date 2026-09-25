<?php
    require_once("../sesion.php");

    $idPagina = 221;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    include_once(RUTA_PROYECTO."/usuarios/includes/api-ofima-conexion.php");

    if (ofimaIntegracionActiva($conexionBdPrincipal, (int) $_SESSION["dataAdicional"]["id_empresa"])) {
        echo '<script type="text/javascript">alert("Las bodegas solo se editan desde Ofima mientras la integración esté activa."); window.location.href="../bodegas.php";</script>';
        exit();
    }
    
    $habilitada = (isset($_POST["habilitada"]) && (string) $_POST["habilitada"] === "1") ? 1 : 0;

    $conexionBdPrincipal->query("UPDATE bodegas SET bod_nombre='" . $_POST["nombre"] . "', bod_ciudad='" . $_POST["ciudad"] . "', bod_habilitada='" . $habilitada . "' WHERE bod_id='" . $_POST["id"] . "' AND bod_id_empresa='" . $_SESSION["dataAdicional"]["id_empresa"] . "'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../bodegas-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
    exit();
