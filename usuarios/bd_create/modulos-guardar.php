<?php
    require_once("../sesion.php");

    $idPagina = 183;
    include(RUTA_PROYECTO."/usuarios/verificar-paginas.php");
    $conexionBdAdmin->query("INSERT INTO modulos(mod_nombre)VALUES('" . $_POST["nombre"] . "')");

    $idInsertU = mysqli_insert_id($conexionBdAdmin);

    include(RUTA_PROYECTO."/usuarios/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../modulos-editar.php?id=' . $idInsertU . '&msg=1";</script>';
    exit();