<?php
    require_once("../sesion.php");

    $idPagina = 201;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    $consultaProductos=$conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_referencia='".trim($_POST["referencia"])."'");
	$datos = mysqli_fetch_array($consultaProductos, MYSQLI_BOTH);

	$conexionBdPrincipal->query("INSERT INTO productos(prod_nombre, prod_categoria, prod_grupo1, prod_marca, prod_referencia, prod_proveedor, prod_id_empresa)VALUES('" . htmlspecialchars($_POST["nombre"], ENT_QUOTES) . "','" . $_POST["categoria"] . "','" . $_POST["grupo1"] . "','" . $_POST["marca"] . "','" . $_POST["referencia"] . "','" . $_POST["proveedor"] ."', '".$_SESSION["dataAdicional"]["id_empresa"]."')");
	$idInsertU = mysqli_insert_id($conexionBdPrincipal);

	$conexionBdPrincipal->query("INSERT INTO productos_bodegas(prodb_producto, prodb_bodega, prodb_fecha_actualizacion, prodb_usuario_actualizacion)VALUES('" . $idInsertU . "', 1, now(), '" . $_SESSION["id"] . "')");

    if(isset($_POST["replicar"])){
        if ($_POST["replicar"] == 1) {
            $conexionBdPrincipal->query("INSERT INTO productos_soptec(prod_nombre, prod_categoria, prod_grupo1, prod_marca)VALUES('" . $_POST["nombre"] . "','" . $_POST["categoria"] . "','" . $_POST["grupo1"] . "','" . $_POST["marca"] . "')");
        }
    }

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../productos-editar.php?id=' . $idInsertU . '&msg=1";</script>';
	exit();