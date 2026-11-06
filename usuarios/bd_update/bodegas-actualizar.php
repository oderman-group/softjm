<?php
    require_once("../sesion.php");

    $idPagina = 221;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    include_once(RUTA_PROYECTO."/usuarios/includes/api-ofima-conexion.php");

    if (ofimaIntegracionActiva($conexionBdPrincipal, (int) $_SESSION["dataAdicional"]["id_empresa"])) {
        echo '<script type="text/javascript">alert("Las bodegas solo se editan desde Ofima mientras la integración esté activa."); window.location.href="../bodegas.php";</script>';
        exit();
    }
    
    $habilitada = (isset($_POST["habilitada"]) && (string) $_POST["habilitada"] === "1") ? 1 : 0;

    $conexionBdPrincipal->query("UPDATE bodegas SET bod_nombre='" . $_POST["nombre"] . "', bod_ciudad='" . $_POST["ciudad"] . "', bod_habilitada='" . $habilitada . "' WHERE bod_id='" . $_POST["id"] . "' AND bod_id_empresa='" . $_SESSION["dataAdicional"]["id_empresa"] . "'");

    require_once RUTA_PROYECTO . '/usuarios/class/Producto.php';
    $idBodega = (int) ($_POST["id"] ?? 0);
    $productosBodega = $conexionBdPrincipal->query(
        "SELECT DISTINCT prodb_producto FROM productos_bodegas WHERE prodb_bodega='" . $idBodega . "'"
    );
    while ($productosBodega && ($productoBodega = mysqli_fetch_assoc($productosBodega))) {
        Producto::sincronizarExistenciasConBodegas((int) $productoBodega['prodb_producto'], $conexionBdPrincipal);
    }

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../bodegas-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
    exit();
