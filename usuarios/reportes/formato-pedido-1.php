<?php
include("../sesion.php");
$idPagina = 373;
if (!empty($_GET["cte"]) AND $_GET["cte"] == 1) {
	$_GET["id"] = base64_decode($_GET["id"]);
} else {
	if ($_SESSION["id"] == "")
		header("Location:../../salir.php");
}

//CONFIGURACIÓN DEL PROGRAMA
$monedas = array("", "COP", "USD");
$simbolosMonedas = array("", "$", "USD");


$configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1"), MYSQLI_BOTH);

$resultado = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM pedidos
INNER JOIN clientes ON cli_id=pedid_cliente
INNER JOIN sucursales ON sucu_id=pedid_sucursal
INNER JOIN contactos ON cont_id=pedid_contacto
INNER JOIN usuarios ON usr_id=pedid_vendedor
WHERE pedid_id='" . $_GET["id"] . "'"), MYSQLI_BOTH);
$consulta=$conexionBdAdmin->query("SELECT * FROM documentos_configuracion 
WHERE dconf_id_empresa= '".$idEmpresa."' 
AND dconf_id_documento= '".ID_DOC_PEDIDO."';");
$configuracionDoc = mysqli_fetch_array($consulta, MYSQLI_BOTH);
$fontLink = "https://fonts.googleapis.com/css2?family=" . str_replace(' ', '+', $configuracionDoc["dconf_estilo_letra"]) . "&display=swap";
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Pedido <?= $resultado['pedid_id']; ?> (<?= $resultado['pedid_propuesta']; ?>) - <?= $resultado['pedid_nombre']; ?></title>
	<link rel="stylesheet" href="<?php echo $fontLink;?>">
	<style type="text/css">
		#contenedor {
			max-height: 1122px;
			max-width: 793px;
		}
	</style>
</head>

<body style="font-family:<?php echo $configuracionDoc['dconf_estilo_letra'] ?? 'Verdana, sans-serif'; ?>; font-size:<?php echo $configuracionDoc['dconf_tamano_letra'] ?? '11'; ?>px;">
	<!--
<div style="text-align:left;"><img src="https://softjm.com/usuarios/files/logojm.png" width="300"></div>


<div style="width: 100%; height: 40px; background-color: #fbbd01; text-align: center; font-size: 20px; font-weight: bold;">
	<p style="padding: 10px;">COTIZACIÓN</p>
</div>
-->
	<img src="../images/<?= $configuracion['conf_encabezado_pedido']; ?>" style="width: 793px;"><br>
	<img src="../images/<?= $configuracion['conf_encabezado2_pedido']; ?>" style="width: 793px;">

	<div id="contenedor">
		<?php
		$formaPago = array("", "CONTADO", "CRÉDITO");
		?>
		<div style="margin: 10px;">
			<table width="100%">
				<tr>
					<td width="60%">
						<span style="font-size: 16px; font-weight: bold;">CLIENTE:</span><br>
						<?= strtoupper($resultado['cli_nombre']); ?><br>
						<strong>NIT:</strong> <?= $resultado['cli_usuario']; ?><br>
						<strong>DIRECCIÓN:</strong> <?= $resultado['cli_direccion']; ?><br>
						<strong>EMAIL:</strong> <?= $resultado['cli_email']; ?><br>
						<strong>TELÉFONO:</strong> <?= $resultado['cli_telefono']; ?><br>
						<strong>CELULAR:</strong> <?= $resultado['cli_celular']; ?><br>
						<strong>CONTACTO:</strong> <?= $resultado['cont_nombre']; ?><br>

					</td>
					<td width="40%">
						<span style="font-size: 16px; font-weight: bold;">PEDIDO # <?= $_GET["id"]; ?></span><br>
						<strong>FECHA PROPUESTA:</strong> <?= $resultado['pedid_fecha_propuesta']; ?><br>
						<strong>FECHA VENCIMIENTO:</strong> <?= $resultado['pedid_fecha_vencimiento']; ?><br>
						<strong>FORMA DE PAGO:</strong> <?= $formaPago[$resultado['pedid_forma_pago']]; ?><br>
						<strong>VENDEDOR:</strong> <?= strtoupper($resultado['usr_nombre']); ?><br>
						<strong>EMAIL:</strong> <?= strtoupper($resultado['usr_email']); ?>
					</td>
				</tr>
			</table>
		</div>

		<div style="margin: 10px; font-size: 10px;" align="center">
			<table width="100%" border="0" rules="groups">
				<thead>
					<tr style="background-color:<?php echo $configuracionDoc['dconf_estilo'] ?? '#00002b'; ?>; height: 50px; color: white;">
						<th>No</th>
						<th>&nbsp;</th>
						<th>Producto/Servicio</th>
						<th>Cant.</th>
						<th>Valor</th>
						<th>IVA</th>
						<th>Dcto.</th>
						<th>SUBTOTAL</th>
					</tr>
				</thead>
				<tbody>

					<!-- COMBOS -->
					<?php
						$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM combos
		INNER JOIN cotizacion_productos ON czpp_combo=combo_id AND czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_PED."'
		ORDER BY czpp_orden");


						while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
							$dcto = 0;
							$valorTotal = 0;

							$valorTotal = ($prod['czpp_valor'] * $prod['czpp_cantidad']);

							if ($prod['czpp_cantidad'] > 0 and $prod['czpp_descuento'] > 0) {
								$dcto = ($valorTotal * ($prod['czpp_descuento'] / 100));
								$totalDescuento += $dcto;
							}

							$valorConDcto = $valorTotal - $dcto;

							$totalIva += ($valorConDcto * ($prod['czpp_impuesto'] / 100));

							$subtotal += $valorTotal;


							$totalCantidad += $prod['czpp_cantidad'];

							$fondo = 'white';
							if ($no % 2 == 0) {
								$fondo = 'lightgray';
							}

							$precioNormalCombo = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT SUM(copp_cantidad*prod_precio) FROM combos_productos
								INNER JOIN productos ON prod_id=copp_producto
								WHERE copp_combo='".$prod['combo_id']."'"), MYSQLI_BOTH);
						?>
							<tr style="height: 30px; background-color: <?= $fondo; ?>;">
								<td align="center"><?= $no; ?></td>
								<td align="center">
									<?php if ($prod['combo_imagen'] != "") { ?>
										<img src="../files/combos/<?= $prod['combo_imagen']; ?>" width="40">
									<?php } ?>
								</td>
								<td>
									<?= $prod['combo_nombre']; ?><br>

									<?php if($prod['combo_descuento']!="" and $resultado['cotiz_ocultar_descuento_combo']=='0'){?>
										<span><b>Precio Normal:</b> $<?=number_format($precioNormalCombo[0],0,".",".");?></span><br>
										<span><b>Descuento:</b> <?=$prod['combo_descuento'];?>%</span><br>
									<?php }?>

									<span style="font-size: 9px; color: darkblue;"><?= $prod['combo_descripcion']; ?></span><br>
									<span style="font-size: 9px; color: teal;">
										<?php
										$productosCombo = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos 
										INNER JOIN productos_categorias ON catp_id=prod_categoria
										INNER JOIN combos_productos ON copp_producto=prod_id AND copp_combo='" . $prod['combo_id'] . "'
										ORDER BY copp_id");
										$c = 1;
										while ($prodCombo = mysqli_fetch_array($productosCombo, MYSQLI_BOTH)) {
											if ($c == 1) {
												echo "<br><b>INCLUYE:</b><br>";
											}
											echo $prodCombo['prod_nombre'] . " (" . $prodCombo['copp_cantidad'] . " Unds.).<br>";
											$c++;
										}
										?>
									</span>
									<span style="font-size: 9px; color: darkblue;"><?= $prod['czpp_observacion']; ?></span>
								</td>
								<td align="center" class="alinear"><?= $prod['czpp_cantidad']; ?></td>
								<td align="center" class="alinear"><?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?><?= number_format($prod['czpp_valor'], 0, ",", "."); ?></td>
								<td align="center" class="alinear"><?= $prod['czpp_impuesto']; ?>%</td>
								<td align="center" class="alinear"><?= $prod['czpp_descuento']; ?>%</td>
								<td align="right" class="alinear"><?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?><?= number_format($valorTotal, 0, ",", "."); ?></td>
							</tr>
						<?php
							$no++;
						}
						?>


				
					<?php
					//Productos
					$no = 1;
					$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos 
		INNER JOIN productos_categorias ON catp_id=prod_categoria
		INNER JOIN cotizacion_productos ON czpp_producto=prod_id AND czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_PED."'
		ORDER BY czpp_orden");
					while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
						$dcto = 0;
							$valorTotal = 0;

							$valorTotal = ($prod['czpp_valor'] * $prod['czpp_cantidad']);

							if ($prod['czpp_cantidad'] > 0 and $prod['czpp_descuento'] > 0) {
								$dcto = ($valorTotal * ($prod['czpp_descuento'] / 100));
								$totalDescuento += $dcto;
							}

							$valorConDcto = $valorTotal - $dcto;

							$totalIva += ($valorConDcto * ($prod['czpp_impuesto'] / 100));

							$subtotal += $valorTotal;


							$totalCantidad += $prod['czpp_cantidad'];
						$total += $subtotal;

						$fondo = 'white';
						if ($no % 2 == 0) {
							$fondo = 'lightgray';
						}
					?>
						<tr style="height: 30px; background-color: <?= $fondo; ?>;">
							<td align="center"><?= $no; ?></td>
							<td align="center">
								<?php if ($prod['prod_foto'] != "") { ?>
									<img src="../files/productos/<?= $prod['prod_foto']; ?>" width="40">
								<?php } ?>
							</td>
							<td>
								<?= "<b>" . $prod['prod_referencia'] . "</b> " . $prod['prod_nombre']; ?><br>
								<span style="font-size: 9px; color: darkblue;"><?= $prod['prod_descripcion_corta']; ?></span<br>
									<span style="font-size: 9px; color: darkblue;"><?= $prod['czpp_observacion']; ?></span>
							</td>
							<td align="center"><?= $prod['czpp_cantidad']; ?></td>
							<td align="right"><?= $simbolosMonedas[$resultado['pedid_moneda']]; ?><?= number_format($prod['czpp_valor'], 0, ",", "."); ?></td>
							<td align="center"><?= $prod['czpp_impuesto']; ?>%</td>
							<td align="center"><?= $prod['czpp_descuento']; ?>%</td>
							<td align="right"><?= $simbolosMonedas[$resultado['pedid_moneda']]; ?><?= number_format($subtotal, 0, ",", "."); ?></td>
						</tr>
					<?php
						$no++;
					}
					?>


						
					<?php
					//Servicios
					$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM servicios
		INNER JOIN cotizacion_productos ON czpp_servicio=serv_id AND czpp_cotizacion='" . $_GET["id"] . "' AND czpp_tipo='".CZPP_TIPO_PED."'
		ORDER BY czpp_orden");
					while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
						$valorTotal += ($prod['czpp_valor'] * $prod['czpp_cantidad']);

						$totalIva += ($prod['czpp_cantidad'] * ($prod['czpp_valor'] * ($prod['czpp_impuesto'] / 100)));

						$valorTotal = $prod['czpp_valor'] + ($prod['czpp_valor'] * ($prod['czpp_impuesto'] / 100));

						if ($prod['czpp_cantidad'] > 0 and $prod['czpp_descuento'] > 0) {
							$totalDescuento += $prod['czpp_cantidad'] * ($valorTotal * ($prod['czpp_descuento'] / 100));
						}

						$valorTotal = $valorTotal - ($valorTotal * ($prod['czpp_descuento'] / 100));

						$subtotal = ($prod['czpp_cantidad'] * $valorTotal);
						$total += $subtotal;


						$totalCantidad += $prod['czpp_cantidad'];

						$fondo = 'white';
						if ($no % 2 == 0) {
							$fondo = 'lightgray';
						}
					?>
						<tr style="height: 30px; background-color: <?= $fondo; ?>;">
							<td align="center"><?= $no; ?></td>
							<td align="center">&nbsp;</td>
							<td>
								<?= $prod['serv_nombre']; ?><br>
								<span style="font-size: 9px; color: darkblue;"><?= $prod['czpp_observacion']; ?></span>
							</td>
							<td align="center"><?= $prod['czpp_cantidad']; ?></td>
							<td align="right"><?= $simbolosMonedas[$resultado['pedid_moneda']]; ?><?= number_format($prod['czpp_valor'], 0, ",", "."); ?></td>
							<td align="center"><?= $prod['czpp_impuesto']; ?>%</td>
							<td align="center"><?= $prod['czpp_descuento']; ?>%</td>
							<td align="right"><?= $simbolosMonedas[$resultado['pedid_moneda']]; ?><?= number_format($subtotal, 0, ",", "."); ?></td>
						</tr>
					<?php
						$no++;
					}
					?>
				</tbody>


				<?php
					$total = $subtotal - $totalDescuento;
					$total += $totalIva;

					?>

				<tfoot>
					<tr style="font-weight: bold; font-size: 13px; height: 30px;">
						<td colspan="6" rowspan="4">
							<!--
						<div style="text-align: justify; font-size: 10px; font-weight: normal;">
							<h3>Términos y condiciones</h3>
							<strong>GARANTÍA:</strong> Un año por desperfectos de fabricación para los equipos 3 meses para los accesorios.<br><br>
							<strong>TIEMPO DE ENTREGA:</strong> Si se encuentra en Stock sería inmediatamente, de lo contrario 20 días aproximadamente.
						</div>
						-->
							<?= $resultado['pedid_observaciones']; ?>
						</td>
						<td style="text-align: right;">SUBTOTAL</td>
						<td align="right""><?= $simbolosMonedas[$resultado['pedid_moneda']]; ?><?= number_format($subtotal, 0, ",", "."); ?></td>
			</tr>
			<tr style=" font-weight: bold; font-size: 13px; height: 30px;">

						<td style="text-align: right;">DESCUENTO</td>
						<td align="right"><?= $simbolosMonedas[$resultado['pedid_moneda']]; ?><?= number_format($totalDescuento, 0, ",", "."); ?></td>
					</tr>
					<tr style="font-weight: bold; font-size: 13px; height: 30px;">

						<td style="text-align: right;">IVA</td>
						<td align="right"><?= $simbolosMonedas[$resultado['pedid_moneda']]; ?><?= number_format($totalIva, 0, ",", "."); ?></td>
					</tr>
					<tr style="font-weight: bold; font-size: 13px; height: 30px;">

						<td style="text-align: right; background-color: <?php echo $configuracionDoc['dconf_estilo'] ?? '#00002b'; ?>;">TOTAL NETO</td>
						<td align="right" style="background-color: <?php echo $configuracionDoc['dconf_estilo'] ?? '#00002b'; ?>;"><?= $simbolosMonedas[$resultado['pedid_moneda']]; ?><?= number_format($total, 0, ",", "."); ?></td>
					</tr>
				</tfoot>

			</table>



		</div>


	</div>

	<p>&nbsp;</p>
	<p><img src="../images/<?= $configuracion['conf_pie_pedido']; ?>" style="width: 793px;"></p>

</body>

<script type="application/javascript">
	print();
</script>

</html>