<?php
    require_once("../sesion.php");

    $idPagina = 202;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    $consultaProductos=$conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_id='".$_POST["id"]."'");
    $datos = mysqli_fetch_array($consultaProductos, MYSQLI_BOTH);

	$origen = 0;
	if($datos['prod_utilidad'] != $_POST["utilidad"]){
		$origen = 2;
	}

	if($datos['prod_costo'] != $_POST["costo"]){
		$origen = 1;
	}

	if($datos['prod_costo'] != $_POST["costo"] and $datos['prod_utilidad'] != $_POST["utilidad"]){
		$origen = 3;
	}

	if ($_FILES['foto']['name'] != "") {
        $destino = "../files/productos/";
        $fileName='prod_'.basename($_FILES['foto']['name']);
        $archivo=$destino.$fileName;
        move_uploaded_file($_FILES['foto']['tmp_name'],$archivo);

		$conexionBdPrincipal->query("UPDATE productos SET prod_foto='" . $fileName . "' WHERE prod_id='" . $_POST["id"] . "'");
	}

	$utilidad = $_POST["utilidad"] / 100;
	$precio1 = $_POST["costo"] + ($_POST["costo"] * $utilidad);
	
	if($origen > 0){

		$conexionBdPrincipal->query("INSERT INTO productos_historial_precios(php_producto, php_precio_anterior, php_precio_nuevo, php_usuario, php_causa)VALUES('".$_POST["id"]."', '".$datos['prod_precio']."', '".$precio1."', '".$_SESSION["id"]."', '".$origen."')");

	}

	$conexionBdPrincipal->query("UPDATE productos SET 
    prod_nombre='" . htmlspecialchars($_POST["nombre"], ENT_QUOTES) . "', 
    prod_categoria='" . $_POST["categoria"] . "', 
    prod_grupo1='" . $_POST["grupo1"] . "', 
    prod_costo='" . $_POST["costo"] . "', 
    prod_utilidad='" . $_POST["utilidad"] . "', 
    prod_precio='" . $precio1 . "', 
    prod_descuento1='" . $_POST["dcto1"] . "', 
    prod_comision='" . $_POST["comision"] . "', 
    prod_marca='" . $_POST["marca"] . "', 
    prod_descripcion_corta='" . $conexionBdPrincipal->real_escape_string($_POST["descripcion"]) . "', 
    prod_costo_dolar='" . $_POST["costoDolar"] . "', 
    prod_referencia='" . $_POST["referencia"] . "', 
    prod_existencias='" . $_POST["cant"] . "', 
    prod_proveedor='" . $_POST["proveedor"] . "', 
    prod_descripcion_larga='" . $conexionBdPrincipal->real_escape_string($_POST["descripcionLarga"]) . "' 
    WHERE prod_id='" . $_POST["id"] . "'");

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../productos-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	exit();