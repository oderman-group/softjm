<?php
    require_once("../sesion.php");

    $idPagina = 192;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    $conexionBdPrincipal->query("INSERT INTO marcas(mar_nombre,mar_id_empresa)VALUES('" . $_POST["nombre"] . "' , '". $idEmpresa ."')");

    $idInsertU = mysqli_insert_id($conexionBdPrincipal);

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../marcas-editar.php?id=' . $idInsertU . '&msg=1";</script>';
    exit();