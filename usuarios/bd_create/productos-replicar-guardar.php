<?php
    require_once("../sesion.php");

    $idPagina = 215;
    $idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    $consultaProductos=$conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_id='".trim($_GET["prod"])."' AND prod_id_empresa='".$idEmpresa."'");
    $datos = mysqli_fetch_array($consultaProductos, MYSQLI_BOTH);

    $conexionBdPrincipal->query("INSERT INTO productos_soptec(prod_nombre, prod_categoria, prod_grupo1, prod_marca, prod_id_empresa)VALUES('" . $datos['prod_nombre'] . "','" . $datos['prod_categoria'] . "','" . $datos['prod_grupo1'] . "','" . $datos['prod_marca'] . "','" . $datos['prod_id_empresa'] . "')");

    $idInsertU = mysqli_insert_id($conexionBdPrincipal);

    echo '<script type="text/javascript">window.location.href="../productos.php?id=' . $idInsertU . '&msg=11";</script>';
    exit();