<?php
session_start();
if ($_GET["cte"] == 1) {
	$_GET["id"] = base64_decode($_GET["id"]);
} else {
	if ($_SESSION["id"] == "")
		header("Location:../../salir.php");
}

include("../../conexion.php");

//CONFIGURACIÓN DEL PROGRAMA
$monedas = array("", "COP", "USD");
$simbolosMonedas = array("", "$", "USD");

$configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT * FROM configuracion WHERE conf_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'"));

$resultado = mysqli_fetch_array(mysqli_query($conexionBdPrincipal, "SELECT * FROM cotizacion
INNER JOIN clientes ON cli_id=cotiz_cliente
INNER JOIN sucursales ON sucu_id=cotiz_sucursal
INNER JOIN contactos ON cont_id=cotiz_contacto
INNER JOIN usuarios ON usr_id=cotiz_vendedor
WHERE cotiz_id='" . $_GET["id"] . "' AND cotiz_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."'"));

$total = number_format($resultado['cotiz_valor'] + ($resultado['cotiz_valor'] * ($resultado['cotiz_impuestos'] / 100)), 0, ",", ".");
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Cotizacion <?= $resultado['cotiz_id']; ?> (<?= $resultado['cotiz_fecha_propuesta']; ?>) - <?= $resultado['cli_nombre']; ?></title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<style type="text/css">
		#contenedor {
			/*max-height: 1122px;*/
			/*max-width: 793px;*/
		}

		.alinear {
			vertical-align: top;
		}
	</style>
</head>

<body style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
	<!--
<div style="text-align:left;"><img src="https://softjm.com/usuarios/files/logojm.png" width="300"></div>


<div style="width: 100%; height: 40px; background-color: #fbbd01; text-align: center; font-size: 20px; font-weight: bold;">
	<p style="padding: 10px;">COTIZACIÓN</p>
</div>
-->
	<div class="container">
		<div class="text-center">
			<img src="../images/<?= $configuracion['conf_encabezado_cotizacion']; ?>" style="width: 100%;"><br>
			<img src="../images/<?= $configuracion['conf_encabezado2_cotizacion']; ?>" style="width: 100%;">
		</div>

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
							<strong>EMAIL:</strong> <?= $resultado['cont_email']; ?><br>
							<strong>TELÉFONO:</strong> <?= $resultado['cli_telefono']; ?><br>
							<strong>CELULAR:</strong> <?= $resultado['cli_celular']; ?><br>
							<strong>CONTACTO:</strong> <?= $resultado['cont_nombre']; ?><br>

						</td>
						<td width="40%">
							<?php
							if ($configuracion['conf_proveedor_cotizacion'] == 1) {
								$proveedor = mysqli_fetch_array(mysqli_query("SELECT * FROM proveedores WHERE prov_id='" . $resultado['cotiz_proveedor'] . "'"));
							?>
								<p class="border border-warning p-2">
									<span style="font-size: 12px; font-weight: bold;">NEGOCIO EN REPRESENTACIÓN<br>COMERCIAL DE</span><br>
									<?= strtoupper($proveedor['prov_nombre']); ?><br>
									DNI: <?= strtoupper($proveedor['prov_documento']); ?>
								</p>
							<?php } ?>

							<span style="font-size: 16px; font-weight: bold;">COTIZACIÓN # <?= $_GET["id"]; ?></span><br>
							<strong>FECHA PROPUESTA:</strong> <?= $resultado['cotiz_fecha_propuesta']; ?><br>
							<strong>FECHA VENCIMIENTO:</strong> <?= $resultado['cotiz_fecha_vencimiento']; ?><br>
							<strong>FORMA DE PAGO:</strong> <?= $formaPago[$resultado['cotiz_forma_pago']]; ?><br>
							<strong>VENDEDOR:</strong> <?= strtoupper($resultado['usr_nombre']); ?><br>
							<strong>EMAIL:</strong> <?= strtoupper($resultado['usr_email']); ?>
						</td>
					</tr>
				</table>
			</div>

			<div style="margin: 10px; font-size: 12px;" align="center">
				<table width="100%" border="0" rules="groups">
					<thead>
						<tr style="background-color: #00002b; height: 50px; color: white;">
							<th>No</th>
							<th>&nbsp;</th>
							<th>Producto/Servicio</th>
							<th>Cant.</th>
							<th width="20%">Valor Unitario</th>
							<th>IVA</th>
							<th>Dcto.</th>
							<th>VALOR TOTAL</th>
						</tr>
					</thead>
					<tbody>


						<!-- COMBOS -->
						<?php
						$productos = mysqli_query("SELECT * FROM combos
		INNER JOIN cotizacion_productos ON czpp_combo=combo_id AND czpp_cotizacion='" . $_GET["id"] . "'
		ORDER BY czpp_orden");


						while ($prod = mysqli_fetch_array($productos)) {
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

							$precioNormalCombo = mysqli_fetch_array(mysqli_query("SELECT SUM(copp_cantidad*prod_precio) FROM combos_productos
								INNER JOIN productos ON prod_id=copp_producto
								WHERE copp_combo='".$prod['combo_id']."'",$conexion));
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
										$productosCombo = mysqli_query("SELECT * FROM productos 
					INNER JOIN productos_categorias ON catp_id=prod_categoria
					INNER JOIN combos_productos ON copp_producto=prod_id AND copp_combo='" . $prod['combo_id'] . "'
					ORDER BY copp_id");
										$c = 1;
										while ($prodCombo = mysqli_fetch_array($productosCombo)) {
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
								<td align="center" class="alinear">
									<?= $prod['czpp_descuento']; ?>% <br>
									<?php 
										if($dcto>0)
											echo "$".number_format($dcto,0,".",".");
										?>
								</td>
								<td align="right" class="alinear"><?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?><?= number_format($valorTotal, 0, ",", "."); ?></td>
							</tr>
						<?php
							$no++;
						}

						?>

						<!-- PRODUCTOS -->
						<?php
						$no = 1;
						$productos = mysqli_query("SELECT * FROM productos 
		INNER JOIN productos_categorias ON catp_id=prod_categoria
		INNER JOIN cotizacion_productos ON czpp_producto=prod_id AND czpp_cotizacion='" . $_GET["id"] . "'
		ORDER BY czpp_orden");


						while ($prod = mysqli_fetch_array($productos)) {
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
						?>
							<tr style="height: 30px; background-color: <?= $fondo; ?>;">
								<td align="center"><?= $no; ?></td>
								<td align="center">
									<?php if ($prod['prod_foto'] != "") { ?>
										<img src="../files/productos/<?= $prod['prod_foto']; ?>" width="40">
									<?php } ?>
								</td>
								<td>
									<?= $prod['prod_nombre']; ?><br>
									<span style="font-size: 9px; color: darkblue;"><?= $prod['prod_descripcion_corta']; ?></span><br>
									<span style="font-size: 9px; color: darkblue;"><?= $prod['czpp_observacion']; ?></span>
								</td>
								<td align="center" class="alinear"><?= $prod['czpp_cantidad']; ?></td>
								<td align="center" class="alinear"><?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?><?= number_format($prod['czpp_valor'], 0, ",", "."); ?></td>
								<td align="center" class="alinear"><?= $prod['czpp_impuesto']; ?>%</td>
								<td align="center" class="alinear">
									<?= $prod['czpp_descuento']; ?>%<br>
									<?php 
										if($dcto>0)
											echo '<span style="font-size:9px; color: blue;">$'.number_format($dcto,0,".",".").'</span>';
										?>
								</td>
								<td align="right" class="alinear"><?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?><?= number_format($valorTotal, 0, ",", "."); ?></td>
							</tr>
						<?php
							$no++;
						}
						?>


						<!-- SERVICIOS -->
						<?php
						$productos = mysqli_query("SELECT * FROM servicios
		INNER JOIN cotizacion_productos ON czpp_servicio=serv_id AND czpp_cotizacion='" . $_GET["id"] . "'
		ORDER BY czpp_orden");


						while ($prod = mysqli_fetch_array($productos)) {
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
						?>
							<tr style="height: 30px; background-color: <?= $fondo; ?>;">
								<td align="center"><?= $no; ?></td>
								<td align="center">&nbsp;</td>
								<td>
									<?= $prod['serv_nombre']; ?><br>
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
					</tbody>

					<?php
					$total = $subtotal - $totalDescuento;
					$total += $resultado['cotiz_envio'] + $totalIva;

					?>
					<tfoot>
						<tr style="font-weight: bold; font-size: 13px; height: 20px;">

							<td colspan="3" rowspan="5" class="alinear">
								<!--
						<div style="text-align: justify; font-size: 10px; font-weight: normal;">
							<h3>Términos y condiciones</h3>
							<strong>GARANTÍA:</strong> Un año por desperfectos de fabricación para los equipos 3 meses para los accesorios.<br><br>
							<strong>TIEMPO DE ENTREGA:</strong> Si se encuentra en Stock sería inmediatamente, de lo contrario 20 días aproximadamente.
						</div>
						-->
								<div style="height: 95px; display:block;">
									OBSERVACIONES:<br>
									<span style="font-size: 11px; font-weight: normal;"><?= $resultado['cotiz_observaciones']; ?></span>

									
									
								
								</div>
							</td>

							<td style="text-align: right;" colspan="3">SUBTOTAL <?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?></td>
							<td align="right" colspan="2"><?= number_format($subtotal, 0, ",", "."); ?></td>
						</tr>
						<tr style="font-weight: bold; font-size: 13px; height: 20px;">
							<td style="text-align: right;" colspan="3">DESCUENTO <?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?></td>
							<td align="right" colspan="2"><?= number_format($totalDescuento, 0, ",", "."); ?></td>
						</tr>
						<tr style="font-weight: bold; font-size: 13px; height: 20px;">
							<td style="text-align: right;" colspan="3">IVA <?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?></td>
							<td align="right" colspan="2"><?= number_format($totalIva, 0, ",", "."); ?></td>
						</tr>
						<tr style="font-weight: bold; font-size: 13px; height: 20px;">
							<td style="text-align: right;" colspan="3">ENVÍO <?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?></td>
							<td align="right" colspan="2"><?= number_format($resultado['cotiz_envio'], 0, ",", "."); ?></td>
						</tr>
						<tr style="font-weight: bold; font-size: 13px; height: 20px;">
							<td style="text-align: right; background-color: #fbc930;" colspan="3">TOTAL NETO <?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?></td>
							<td align="right" style="background-color: #fbc930;" colspan="2"><?= number_format($total, 0, ",", "."); ?></td>
						</tr>
					</tfoot>

				</table>



			</div>


		</div>

		<?php
		
		$marginTop = '0px';
		/*
		if($resultado['cotiz_observaciones']!=''){
			$marginTop = '150px';
		}*/x
		?>

		<span>
										<img src="condicionesCoti.png" style="width: 100%;">
										<!--
										* Por pagos realizados con Tarjetas de Crédito agregar 4% de servicios bancarios  al valor neto de la cotización.<br>
										* Por pagos realizados con Tarjetas de Debito agregar 2% de servicios bancarios  al valor neto de la cotización.<br>
										* Transferencias Bancarias NO tienen Gastos adicionales.<br>
										* Después de Aprobada la cotización no se aceptan devoluciones o reversiones pactadas dentro de ella.<br>
										* Las Garantías serán cubiertas después de haber recibido los equipos o accesorios en nuestros centros autorizados de servicios para revisión y aprobación por garantía.
										-->
									</span>

		<p>&nbsp;</p>
		<div class="text-center" style="display:block;  padding: 0; margin-top:<?=$marginTop;?> ;">
			<p><img src="../images/<?= $configuracion['conf_pie_cotizacion']; ?>" style="width: 100%;"></p>
		</div>

	</div>

</body>

<script type="application/javascript">
	print();
</script>

</html>