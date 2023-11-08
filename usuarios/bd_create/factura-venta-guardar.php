<?php
require_once("../sesion.php");

$idPagina = 291;

include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
$idEmpresa = $_SESSION["dataAdicional"]["id_empresa"];
mysqli_query($conexionBdPrincipal,"INSERT INTO facturas(factura_fecha_propuesta, factura_cliente, factura_fecha_vencimiento, factura_vendedor, factura_creador, factura_sucursal, factura_contacto, factura_forma_pago, factura_fecha_creacion, factura_moneda, factura_estado, factura_tipo, factura_id_empresa)VALUES('" . $_POST["fechaPropuesta"] . "','" . $_POST["cliente"] . "','" . $_POST["fechaVencimiento"] . "','" . $_POST["influyente"] . "','" . $_SESSION["id"] . "','" . $_POST["sucursal"] . "','" . $_POST["contacto"] . "','" . $_POST["formaPago"] . "',now(),'" . $_POST["moneda"] . "', 1, 1, '".$idEmpresa."')");
	
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

			mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo)VALUES('" . $idInsert . "','" . $_POST["producto"][$contador] . "', '" . $valorProducto . "', '" . $contador . "', 1, 19, 4)");
			
			$contador++;
		}
	}

	//COMBOS
	$numero = (count($_POST["combo"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {

			$datosCombos = mysqli_query($conexionBdPrincipal,"SELECT ROUND((SUM(copp_cantidad)*prod_precio),0), combo_descuento FROM combos
			INNER JOIN combos_productos ON copp_combo=combo_id
			INNER JOIN productos ON prod_id=copp_producto
			WHERE combo_id='" . $_POST["combo"][$contador] . "' AND combo_id_empresa='".$idEmpresa."'
			GROUP BY copp_producto
			");
			$precioCombo = 0;
			$dctoCombo = 0;
			while ($dCombos = mysqli_fetch_array($datosCombos)) {
				$precioCombo += $dCombos[0];
				$dctoCombo = $dCombos[1];
			}
			if ($dctoCombo > 0) {
				$precioCombo = round($precioCombo - ($precioCombo * ($dctoCombo / 100)), 0);
			}


			$productoNum = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_combo='" . $_POST["combo"][$contador] . "'"));
			


			if ($productoNum['czpp_id'] == '') {
				$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM combos WHERE combo_id='" . $_POST["combo"][$contador] . "'  AND combo_id_empresa='".$idEmpresa."'"));
				

				$valorProducto = $precioCombo;
				if ($_POST["moneda"] == 2) {
					$valorProducto = round(($precioCombo / $configuracion['conf_trm_compra']), 0);
				}

				mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_combo, czpp_cantidad, czpp_impuesto, czpp_descuento, czpp_valor, czpp_orden, czpp_tipo)VALUES('" . $idInsert . "','" . $_POST["combo"][$contador] . "', 1, 19, 0, '" . $valorProducto . "', '" . $numero . "', 4)");
				
			}

			$contador++;
		}
	}

	//Servicios
	$numero = (count($_POST["servicio"]));
	if ($numero > 0) {
		$contador = 0;
		while ($contador < $numero) {
			$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM servicios WHERE serv_id='" . $_POST["servicio"][$contador] . "'"));
			

			$valorProducto = $productoDatos['serv_precio'];
			if ($_POST["moneda"] == 2) {
				$valorProducto = round(($productoDatos['serv_precio'] / $configuracion['conf_trm_compra']), 0);
			}

			mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_servicio, czpp_valor, czpp_orden, czpp_cantidad, czpp_impuesto, czpp_tipo)VALUES('" . $idInsert . "','" . $_POST["servicio"][$contador] . "', '" . $valorProducto . "', '" . $contador . "', 1, 19, 4)");
			
			$contador++;
		}
	}

	echo '<script type="text/javascript">window.location.href="../facturas.php?id=' . $idInsert . '&msg=1";</script>';
	exit();
