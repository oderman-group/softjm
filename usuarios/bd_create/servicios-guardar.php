<?php
    require_once("../sesion.php");

    $idPagina = 223;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    
    $conexionBdPrincipal->query("INSERT INTO servicios(serv_nombre, serv_precio,serv_id_empresa)VALUES('" . $_POST["nombre"] . "','" . $_POST["precio"] . "','".$idEmpresa."')");

    $idInsertU = mysqli_insert_id($conexionBdPrincipal);

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../servicios-editar.php?id=' . $idInsertU . '";</script>';
    exit();