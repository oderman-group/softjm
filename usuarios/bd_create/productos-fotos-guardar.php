<?php
    require_once("../sesion.php");

    $idPagina = 210;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	if ($_FILES['archivo']['name'] != "") {
		$destino = RUTA_PROYECTO."/usuarios/files/productos/galeria";
		$fileName = subirArchivosAlServidor($_FILES['archivo'], 'gp', $destino);

		$conexionBdPrincipal->query("INSERT INTO productos_galeria(pgal_producto, pgal_foto)VALUES('" . $_POST["id"] . "', '" . $fileName . "')");
	}

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	if (!empty($_POST['ajax'])) {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(['success' => true]);
		exit();
	}

	echo '<script type="text/javascript">window.location.href="../productos-galeria.php?id=' . $_POST["id"] . '";</script>';
	exit();