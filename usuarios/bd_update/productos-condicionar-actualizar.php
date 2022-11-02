<?php
    require_once("../sesion.php");

    $idPagina = 203;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    //CONDICIONAR PRODUCTOS PARA DESCUENTO
    if ($_POST["opcion"] == 1) {
        $conexionBdPrincipal->query("UPDATE productos SET prod_descuento1='" . $_POST["dcto"] . "' WHERE " . $_POST["campo"] . " " . $_POST["es"] . " '" . $_POST["valor"] . "'");
    }

    //CONDICIONAR PRODUCTOS PARA COMISIÓN
    if ($_POST["opcion"] == 2) {
        $conexionBdPrincipal->query("UPDATE productos SET prod_comision='" . $_POST["comision"] . "' WHERE " . $_POST["campo"] . " " . $_POST["es"] . " '" . $_POST["valor"] . "'");
    }

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../productos.php?msg=2";</script>';
    exit();