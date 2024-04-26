<?php
    require_once("../sesion.php");

    $idPagina = 217;
    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    if ($_FILES['foto']['name'] != "") {
		$destino = RUTA_PROYECTO."/usuarios/files/combos";
		$fileName = subirArchivosAlServidor($_FILES['foto'], 'comb', $destino);

        $conexionBdPrincipal->query("UPDATE combos SET combo_imagen='" . $fileName . "' WHERE combo_id='" . $_POST["id"] . "' AND combo_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");
    }

    $_POST["dcto"]          = !empty($_POST["dcto"]) ? $_POST["dcto"] : 0;
    $_POST["descuentoMax"]  = !empty($_POST["descuentoMax"]) ? $_POST["descuentoMax"] : 0;
    $_POST["dctoDealer"]    = !empty($_POST["dctoDealer"]) ? $_POST["dctoDealer"] : 0;

    $conexionBdPrincipal->query("UPDATE combos SET 
    combo_nombre='" . $_POST["nombre"] . "', 
    combo_descripcion='" . $_POST["descripcion"] . "', 
    combo_descuento='" . $_POST["dcto"] . "', 
    combo_actualizaciones=combo_actualizaciones+1, 
    combo_ultima_actualizacion=now(), 
    combo_estado='" . $_POST["estado"] . "', 
    combo_descuento_maximo='" . $_POST["descuentoMax"] . "', 
    combo_descuento_dealer='" . $_POST["dctoDealer"] . "' 
    WHERE combo_id='" . $_POST["id"] . "' AND combo_id_empresa = '".$_SESSION["dataAdicional"]["id_empresa"]."'");

    if(isset($_POST["producto"])){

        $conexionBdPrincipal->query("DELETE FROM combos_productos WHERE copp_combo='" . $_POST["id"] . "'");

        $numero = (count($_POST["producto"]));
        if ($numero > 0) {
            $contador = 0;
            while ($contador < $numero) {
                $consultaProductos=$conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_id='" . $_POST["producto"][$contador] . "'");
                $productoDatos = mysqli_fetch_array($consultaProductos, MYSQLI_BOTH);

                $consultaNumComboProductos=$conexionBdPrincipal->query("SELECT * FROM combos_productos WHERE copp_producto='" . $_POST["producto"][$contador] . "' AND copp_combo='" . $_POST["id"] . "'");
                $productoN = $consultaNumComboProductos->num_rows;
                if ($productoN == 0) {
                    $conexionBdPrincipal->query("INSERT INTO combos_productos(copp_combo, copp_producto, copp_cantidad, copp_precio)VALUES('" . $_POST["id"] . "', '" . $_POST["producto"][$contador] . "', 1, '".$productoDatos['prod_precio']."')");
                }
                $contador++;
            }
        }
    } else {
        $conexionBdPrincipal->query("DELETE FROM combos_productos WHERE copp_combo='" . $_POST["id"] . "'");
    }

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../combos-editar.php?id=' . $_POST["id"] . '&msg=1";</script>';
    exit();