<?php
    require_once("../sesion.php");

    $idPagina = 183;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    $conexionBdAdmin->query("INSERT INTO modulos(mod_nombre, mod_padre)VALUES('" . $_POST["nombre"] . "', '" . $_POST["moduloPadre"] . "')");

    $idInsertU = mysqli_insert_id($conexionBdAdmin);

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../modulos-editar.php?id=' . $idInsertU . '&msg=1";</script>';
    exit();