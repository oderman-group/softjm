<?php
    require_once("../sesion.php");

    $idPagina = 210;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	if ($_FILES['archivo']['name'] != "") {
        $destino = "../files/productos/galeria/";
        $fileName='gp_'.basename($_FILES['archivo']['name']);
        $archivo=$destino.$fileName;
        move_uploaded_file($_FILES['archivo']['tmp_name'],$archivo);

		$conexionBdPrincipal->query("INSERT INTO productos_galeria(pgal_producto, pgal_foto)VALUES('" . $_POST["id"] . "', '" . $fileName . "')");
	}

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../productos-galeria.php?id=' . $_POST["id"] . '";</script>';
	exit();