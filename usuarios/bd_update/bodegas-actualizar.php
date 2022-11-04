<?php
    require_once("../sesion.php");

    $idPagina = 221;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    
    $conexionBdPrincipal->query("UPDATE bodegas SET bod_nombre='" . $_POST["nombre"] . "', bod_ciudad='" . $_POST["ciudad"] . "' WHERE bod_id='" . $_POST["id"] . "'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../bodegas-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
    exit();