<?php
    require_once("sesion.php");

    $idPagina = 208;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
		
	$consulta = $conexionBdPrincipal->query("SELECT * FROM productos");

	while($datos = mysqli_fetch_array($consulta, MYSQLI_BOTH)){

		$conexionBdPrincipal->query("INSERT INTO productos_historial_precios(php_producto, php_precio_anterior, php_precio_nuevo, php_usuario, php_causa)VALUES('".$datos["prod_id"]."', '".$datos['prod_precio']."', '".$datos['prod_precio']."', '".$_SESSION["id"]."', 4)");

	}

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="productos.php?msg=12";</script>';
	exit();
