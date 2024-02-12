<?php
    require_once("../sesion.php");

    $idPagina = 198;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

	$conexionBdPrincipal->query("UPDATE productos_categorias SET catp_nombre='" . $_POST["nombre"] . "' WHERE catp_id='" . $_POST["id"] . "' AND catp_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../categoriasp-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();