<?php
    require_once("../sesion.php");

    $idPagina = 210;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$bpp = $conexionBdPrincipal->query("SELECT * FROM productos_bodegas WHERE prodb_producto='" . $_POST["producto"] . "' AND prodb_bodega='" . $_POST["bodega"] . "'")->num_rows;

	if ($bpp == 0) {
		$conexionBdPrincipal->query("INSERT INTO productos_bodegas(prodb_producto, prodb_bodega, prodb_existencias, prodb_fecha_actualizacion, prodb_usuario_actualizacion)VALUES('" . $_POST["producto"] . "', '" . $_POST["bodega"] . "', '" . $_POST["existencia"] . "', now(), '" . $_SESSION["id"] . "')");
		$idInsertU = mysqli_insert_id($conexionBdPrincipal);
	} else {
		$conexionBdPrincipal->query("UPDATE productos_bodegas SET prodb_existencias='" . $_POST["existencia"] . "', prodb_fecha_actualizacion=now(), prodb_usuario_actualizacion='" . $_SESSION["id"] . "' WHERE prodb_producto='" . $_POST["producto"] . "' AND prodb_bodega='" . $_POST["bodega"] . "'");
	}

	$consultaExistencia=$conexionBdPrincipal->query("SELECT SUM(prodb_existencias) FROM productos_bodegas WHERE prodb_producto='".$_POST["producto"]."'");
	$exis = mysqli_fetch_array($consultaExistencia, MYSQLI_BOTH);

	$conexionBdPrincipal->query("UPDATE productos SET prod_existencias='".$exis[0]."' WHERE prod_id='".$_POST["producto"]."'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../bodegas-productos.php?prod=' . $_POST["producto"] . '";</script>';
	exit();