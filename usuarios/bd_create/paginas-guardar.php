<?php
    require_once("../sesion.php");

    $idPagina = 75;
    include(RUTA_PROYECTO."/usuarios/verificar-paginas.php");
    
    $conexionBdAdmin->query("INSERT INTO paginas(pag_nombre, pag_tipo_crud, pag_id_modulo, pag_ruta)VALUES('" . $_POST["nombre"] . "','" . $_POST["crud"] . "','" . $_POST["modulo"] . "','". $_POST["ruta"] . "')");

    $idInsertU = mysqli_insert_id($conexionBdAdmin);

    include(RUTA_PROYECTO."/usuarios/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../paginas-editar.php?id=' . $idInsertU . '&msg=1";</script>';
    exit();