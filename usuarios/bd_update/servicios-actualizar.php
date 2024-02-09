<?php
    require_once("../sesion.php");

    $idPagina = 224;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    
    $conexionBdPrincipal->query("UPDATE servicios SET serv_nombre='" . $_POST["nombre"] . "', serv_precio='" . $_POST["precio"] . "' WHERE serv_id='" . $_POST["id"] . "' AND serv_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../servicios-editar.php?id=' . $_POST["id"] . '";</script>';
    exit();