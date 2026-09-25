<?php
    require_once("../sesion.php");

    $idPagina = 220;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    include_once(RUTA_PROYECTO."/usuarios/includes/api-ofima-conexion.php");
    if (ofimaIntegracionActiva($conexionBdPrincipal, (int) $_SESSION["dataAdicional"]["id_empresa"])) {
        echo '<script type="text/javascript">alert("Con la integración Ofima activa, las transferencias de productos solo se gestionan desde Ofima."); window.location.href="../bodegas.php";</script>';
        exit();
    }

	$conexionBdPrincipal->query("UPDATE productos_bodegas SET prodb_bodega='" . $_POST["hasta"] . "' WHERE prodb_bodega='" . $_POST["desde"] . "'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../bodegas.php?msg=2";</script>';
	exit();
