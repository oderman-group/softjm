<?php
require_once("../sesion.php");

$idPagina = 292;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

mysqli_query($conexionBdPrincipal,"INSERT INTO facturas(factura_fecha_propuesta, factura_proveedor, factura_fecha_vencimiento, factura_creador, factura_forma_pago, factura_fecha_creacion, factura_moneda, factura_estado, factura_tipo, factura_concepto, factura_extranjera, factura_preferencia)VALUES('" . $_POST["fechaPropuesta"] . "','" . $_POST["proveedor"] . "','" . $_POST["fechaVencimiento"] . "','" . $_SESSION["id"] . "','" . $_POST["formaPago"] . "',now(),'" . $_POST["moneda"] . "', 1, 2, '" . $_POST["concepto"] . "', '" . $_POST["fce"] . "', '" . $_POST["preferencia"] . "')");
	
	$idInsert = mysqli_insert_id($conexionBdPrincipal);

	//Productos
	$numero = (count($_POST["producto"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {
			$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos WHERE prod_id='" . $_POST["producto"][$contador] . "'"));
			

			$valorProducto = $productoDatos['prod_precio'];
			if ($_POST["moneda"] == 2) {
				$valorProducto = round(($productoDatos['prod_precio'] / $configuracion['conf_trm_compra']), 0);
			}

			mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo, czpp_bodega)VALUES('" . $idInsert . "','" . $_POST["producto"][$contador] . "', '" . $valorProducto . "', '" . $contador . "', 1, 19, 4, 1)");
			
			$contador++;
		}
	}

	if ($_POST["fce"] == 1) {

		echo '<script type="text/javascript">window.location.href="fce-editar.php?id=' . $idInsert . '&msg=1";</script>';
	} else {
		echo '<script type="text/javascript">window.location.href="../facturas-compra-editar.php?id=' . $idInsert . '&msg=1";</script>';
	}


	exit();