<?php
    require_once("../sesion.php");

    $idPagina = 219;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    include_once(RUTA_PROYECTO."/usuarios/includes/api-ofima-conexion.php");

    if (ofimaIntegracionActiva($conexionBdPrincipal, (int) $_SESSION["dataAdicional"]["id_empresa"])) {
        echo '<script type="text/javascript">alert("Las bodegas solo se crean desde Ofima mientras la integración esté activa."); window.location.href="../bodegas.php";</script>';
        exit();
    }

	$habilitada = (isset($_POST["habilitada"]) && (string) $_POST["habilitada"] === "1") ? 1 : 0;

	$conexionBdPrincipal->query("INSERT INTO bodegas(bod_nombre, bod_ciudad, bod_habilitada, bod_id_empresa)VALUES('" . $_POST["nombre"] . "', '" . $_POST["ciudad"] . "', '" . $habilitada . "', '".$_SESSION["dataAdicional"]["id_empresa"]."')");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../bodegas.php?msg=1";</script>';
	exit();
