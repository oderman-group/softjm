<?php
    require_once("../sesion.php");

    $idPagina = 216;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

    $fileName = "";
	if (!empty($_FILES['foto']['name'])) {
		$destino = RUTA_PROYECTO."/usuarios/files/combos";
		$fileName = subirArchivosAlServidor($_FILES['foto'], 'comb', $destino);
	}

    $_POST["dcto"]          = !empty($_POST["dcto"]) ? $_POST["dcto"] : 0;
    $_POST["descuentoMax"]  = !empty($_POST["descuentoMax"]) ? $_POST["descuentoMax"] : 0;
    $_POST["dctoDealer"]    = !empty($_POST["dctoDealer"]) ? $_POST["dctoDealer"] : 0;

	$conexionBdPrincipal->query("INSERT INTO combos(combo_nombre, combo_descripcion, combo_imagen, combo_descuento, combo_estado, combo_fecha_registro, combo_actualizaciones, combo_descuento_maximo, combo_descuento_dealer, combo_id_empresa)VALUES('" . $_POST["nombre"] . "', '" . $_POST["descripcion"] . "', '" . $fileName . "', '" . $_POST["dcto"] . "', '" . $_POST["estado"] . "', now(), 0, '" . $_POST["descuentoMax"] . "', '" . $_POST["dctoDealer"] ."', '".$_SESSION["dataAdicional"]["id_empresa"]."')");
	$idInsert = mysqli_insert_id($conexionBdPrincipal);

    if(isset($_POST["producto"])){
        $numero = (count($_POST["producto"]));
        if ($numero > 0) {
            $contador = 0;
            while ($contador < $numero) {

                $consultaProducto=$conexionBdPrincipal->query("SELECT * FROM productos WHERE prod_id='" . $_POST["producto"][$contador] . "'");
                $productoDatos = mysqli_fetch_array($consultaProducto, MYSQLI_BOTH);

                $conexionBdPrincipal->query("INSERT INTO combos_productos(copp_combo, copp_producto, copp_cantidad, copp_precio)VALUES('" . $idInsert . "', '" . $_POST["producto"][$contador] . "', 1, '".$productoDatos['prod_precio']."')");
                $contador++;
            }
        }
    }

    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="../combos.php?id=' . $idInsert . '&msg=1";</script>';
	exit();