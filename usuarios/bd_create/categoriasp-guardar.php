<?php
    require_once("../sesion.php");

    $idPagina = 197;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$conexionBdPrincipal->query("INSERT INTO productos_categorias(catp_nombre, catp_grupo)VALUES('" . $_POST["nombre"] . "', '" . $_POST["grupo"] . "')");
    
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../categoriasp-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();