<?php
    require_once("../sesion.php");

    $idPagina = 224;

    include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
    
    if ($_POST["fce"] == 1) {

		mysqli_query($conexionBdPrincipal,"UPDATE facturas SET factura_fecha_propuesta='" . $_POST["fechaPropuesta"] . "', factura_proveedor='" . $_POST["proveedor"] . "', factura_fecha_vencimiento='" . $_POST["fechaVencimiento"] . "', factura_forma_pago='" . $_POST["formaPago"] . "', factura_moneda='" . $_POST["moneda"] . "', factura_ultima_modificacion=now(), factura_usuario_modificacion='" . $_SESSION["id"] . "', factura_observaciones='" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["notas"]) . "', factura_concepto='" . $_POST["concepto"] . "', factura_trm_usd='" . $_POST["trmUsd"] . "', factura_trm_euro='" . $_POST["trmEuro"] . "', factura_trm_usd_flete='" . $_POST["trmUsdFlete"] . "', factura_trm_euro_flete='" . $_POST["trmEuroFlete"] . "', factura_preferencia='" . $_POST["preferencia"] . "' WHERE factura_id='" . $_POST["id"] . "'");
		

		//PRODUCTOS
		$numero = (count($_POST["producto"]));
		if ($numero > 0) {
			$contador = 0;
			while ($contador < $numero) {

				$productoNum = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
				WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_producto='" . $_POST["producto"][$contador] . "' AND czpp_tipo='".CZPP_TIPO_FACT."'"));
				


				if ($productoNum['czpp_id'] == '') {
					$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos WHERE prod_id='" . $_POST["producto"][$contador] . "'"));
					

					

					mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_cantidad, czpp_impuesto, czpp_descuento, czpp_orden, czpp_tipo, czpp_bodega)VALUES('" . $_POST["id"] . "','" . $_POST["producto"][$contador] . "', 1, 19, 0, '" . $numero . "', 4, 1)");
					
				} 

				$contador++;
			}


			//ELIMINAR LOS QUE YA NO ESTÁN EN LA FACTURACIÓN.
			$productosWeb = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_tipo='".CZPP_TIPO_FACT."'");
			while ($pWeb = mysqli_fetch_array($productosWeb)) {

				$encontrado = 0;
				$contador = 0;
				while ($contador < $numero) {

					if ($pWeb['czpp_producto'] == $_POST["producto"][$contador]) {
						$encontrado = 1;
						break;
					}

					$contador++;
				}

				if ($encontrado == 0) {
					mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion_productos WHERE czpp_producto='" . $pWeb['czpp_producto'] . "' AND czpp_cotizacion='" . $_POST["id"] . "' AND czpp_tipo='".CZPP_TIPO_FACT."'");
					
				}
			}
		} else {
			mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_servicio IS NULL AND czpp_combo IS NULL AND czpp_tipo='".CZPP_TIPO_FACT."'");
			
		}



		echo '<script type="text/javascript">window.location.href="fce-editar.php?id=' . $_POST["id"] . '&msg=1";</script>';
	} else {

		mysqli_query($conexionBdPrincipal,"UPDATE facturas SET factura_fecha_propuesta='" . $_POST["fechaPropuesta"] . "', factura_proveedor='" . $_POST["proveedor"] . "', factura_fecha_vencimiento='" . $_POST["fechaVencimiento"] . "', factura_forma_pago='" . $_POST["formaPago"] . "', factura_moneda='" . $_POST["moneda"] . "', factura_ultima_modificacion=now(), factura_usuario_modificacion='" . $_SESSION["id"] . "', factura_observaciones='" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["notas"]) . "', factura_concepto='" . $_POST["concepto"] . "', factura_valor='" . $_POST["valor"] . "', factura_preferencia='" . $_POST["preferencia"] . "' WHERE factura_id='" . $_POST["id"] . "'");
		

		//PRODUCTOS
		$numero = (count($_POST["producto"]));
		if ($numero > 0) {
			$contador = 0;
			while ($contador < $numero) {

				$productoNum = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
				WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_producto='" . $_POST["producto"][$contador] . "' AND czpp_tipo='".CZPP_TIPO_FACT."'"));
				


				if ($productoNum['czpp_id'] == '') {
					$productoDatos = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos WHERE prod_id='" . $_POST["producto"][$contador] . "'"));
					

					$valorProducto = $productoDatos['prod_precio'];
					if ($_POST["moneda"] == 2) {
						$valorProducto = round(($productoDatos['prod_precio'] / $configuracion['conf_trm_compra']), 0);
					}

					mysqli_query($conexionBdPrincipal,"INSERT INTO cotizacion_productos(czpp_cotizacion, czpp_producto, czpp_cantidad, czpp_impuesto, czpp_descuento, czpp_valor, czpp_orden, czpp_tipo, czpp_bodega)VALUES('" . $_POST["id"] . "','" . $_POST["producto"][$contador] . "', 1, 19, 0, '" . $valorProducto . "', '" . $numero . "', 4, 1)");
					
				} else {
					if ($_POST["monedaActual"] != $_POST["moneda"]) {
						//Si cambió a pesos colombianos
						if ($_POST["moneda"] == 1) {
							$valorProducto = round(($productoNum['czpp_valor'] * $configuracion['conf_trm_venta']), 0);
						}
						//Si cambió a Dolares
						else {
							$valorProducto = round(($productoNum['czpp_valor'] / $configuracion['conf_trm_compra']), 0);
						}

						mysqli_query($conexionBdPrincipal,"UPDATE cotizacion_productos SET czpp_valor='" . $valorProducto . "' WHERE czpp_id='" . $productoNum['czpp_id'] . "'");
						
					}
				}

				$contador++;
			}


			//ELIMINAR LOS QUE YA NO ESTÁN EN LA FACTURACIÓN.
			$productosWeb = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_tipo='".CZPP_TIPO_FACT."'");
			while ($pWeb = mysqli_fetch_array($productosWeb)) {

				$encontrado = 0;
				$contador = 0;
				while ($contador < $numero) {

					if ($pWeb['czpp_producto'] == $_POST["producto"][$contador]) {
						$encontrado = 1;
						break;
					}

					$contador++;
				}

				if ($encontrado == 0) {
					mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion_productos WHERE czpp_producto='" . $pWeb['czpp_producto'] . "' AND czpp_cotizacion='" . $_POST["id"] . "' AND czpp_tipo='".CZPP_TIPO_FACT."'");
					
				}
			}
		} else {
			mysqli_query($conexionBdPrincipal,"DELETE FROM cotizacion_productos WHERE czpp_cotizacion='" . $_POST["id"] . "' AND czpp_servicio IS NULL AND czpp_combo IS NULL AND czpp_tipo='".CZPP_TIPO_FACT."'");
			
		}



		echo '<script type="text/javascript">window.location.href="../facturas-compra-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
	}
	
    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="../facturas-compra-editar.php?id=' . $_POST["id"] . '&msg=2";</script>';
    exit();