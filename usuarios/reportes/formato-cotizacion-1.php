<?php
include("../sesion.php");

$idPagina = 50;

require_once("logica-cotizacion.php");
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Cotización <?= $resultado['cotiz_id']; ?> (<?= $resultado['cotiz_fecha_propuesta']; ?>) - <?= $resultado['cli_nombre']; ?></title>

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

<body style="font-family:Verdana, sans-serif; font-size:11px;">

	<div class="container">

		<div class="text-center">
			<img src="../images/<?= $configuracion['conf_encabezado_cotizacion']; ?>" style="width: 100%;"><br>
			<img src="0033a0.png" style="width: 100%;">
		</div>

		<div id="contenedor">

			<div class="container text-left mt-2">
				<div class="row align-items-center">

					<div class="col-8" style="padding-left: 10px;">

						<div class="card border border-dark" style="width: 25rem;">
							<div class="card-body">
								<h5 class="card-title"><?= strtoupper($resultado['cli_nombre']); ?></h5>
								<h6 class="card-subtitle mb-2 text-muted">NIT: <?= $resultado['cli_usuario']; ?></h6>
								<p class="card-text">
									<strong>DIRECCIÓN:</strong> <?= $resultado['cli_direccion']; ?><br>
									<strong>EMAIL:</strong> <?= $resultado['cont_email']; ?><br>
									<strong>TELÉFONO:</strong> <?= $resultado['cli_telefono']; ?><br>
									<strong>CELULAR:</strong> <?= $resultado['cli_celular']; ?><br>
									<strong>CONTACTO:</strong> <?= $resultado['cont_nombre']; ?><br>

								</p>
							</div>
						</div>

					</div>


					<div class="col-3 text-right">
						<div class="card border border-dark" style="width: 18rem;">
							<div class="card-body">
								<h5 class="card-title">COTIZACIÓN # <?= $_GET["id"]; ?></h5>
								<p class="card-text">

									<strong>FECHA PROPUESTA:</strong> <?= $resultado['cotiz_fecha_propuesta']; ?><br>
									<strong>FECHA VENCIMIENTO:</strong> <?= $resultado['cotiz_fecha_vencimiento']; ?><br>
									<strong>FORMA DE PAGO:</strong> <?= $formaPago[$resultado['cotiz_forma_pago']]; ?><br>
									<strong>VENDEDOR:</strong> <?= strtoupper($resultado['usr_nombre']); ?><br>
									<strong>EMAIL:</strong> <?= strtoupper($resultado['usr_email']); ?>
								</p>

								<?php
								if ($configu['conf_proveedor_cotizacion'] == 1) {
									?>
									<p class="border border-warning p-2">
										<span style="font-size: 12px; font-weight: bold;">NEGOCIO EN REPRESENTACIÓN<br>COMERCIAL DE</span><br>
										<?= strtoupper($proveedor['prov_nombre']); ?><br>
										DNI: <?= strtoupper($proveedor['prov_documento']); ?>
									</p>
								<?php } ?>

							</div>
						</div>


						
					</div>

				</div>
			</div> 



			<div class="border border-dark rounded p-2" style="margin: 10px; font-size: 12px;" align="center">


				<table width="100%" border="0" rules="groups">
					<thead>
						<tr style="background-color: #0033a0; height: 50px; color: white;">
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
						$no = 1;

						$productos = $conexionBdPrincipal->query("SELECT * FROM combos
							INNER JOIN cotizacion_productos ON czpp_combo=combo_id AND czpp_cotizacion='" . $_GET["id"] . "'
							ORDER BY czpp_orden");
							$totalIva = 0;
							$subtotal=0;
							$totalDescuento=0;
						while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {

							require("logica-cotizacion-items.php");

							$precioNormalCombo = mysqli_fetch_array($conexionBdPrincipal->query("SELECT SUM(copp_cantidad*prod_precio) FROM combos_productos
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
											$productosCombo = $conexionBdPrincipal->query("SELECT prod_id, prod_nombre, copp_cantidad FROM productos 
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
							$productos = $conexionBdPrincipal->query("SELECT * FROM productos 
								INNER JOIN productos_categorias ON catp_id=prod_categoria
								INNER JOIN cotizacion_productos ON czpp_producto=prod_id AND czpp_cotizacion='" . $_GET["id"] . "'
								ORDER BY czpp_orden");
							while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
								require("logica-cotizacion-items.php");
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
										<span style="font-size: 9px; color: #0033a0;"><?= $prod['prod_descripcion_corta']; ?></span><br>
										<span style="font-size: 9px; color: #0033a0;"><?= $prod['czpp_observacion']; ?></span>
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
							$productos = $conexionBdPrincipal->query("SELECT * FROM servicios
								INNER JOIN cotizacion_productos ON czpp_servicio=serv_id AND czpp_cotizacion='" . $_GET["id"] . "'
								ORDER BY czpp_orden");
							while ($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)) {
								require("logica-cotizacion-items.php");
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
							if($resultado['cotiz_envio']==''){$envio=0;}else{$envio=$resultado['cotiz_envio'];}
							$total = $subtotal- $totalDescuento + $totalIva + $envio;
							?>
						</tbody>
						<tfoot>
							<tr style="font-weight: bold; font-size: 13px; height: 20px;">

								<td colspan="3" rowspan="5" class="alinear">
							
				<div style="height: 95px; display:block;">
					OBSERVACIONES:<br>
					<span style="font-size: 11px; font-weight: normal;"><?= $resultado['cotiz_observaciones']; ?></span>




				</div>
			</td>

			<td style="text-align: right;" colspan="3">SUBTOTAL <?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?></td>
			<td align="right" colspan="2"><?php if(isset($subtotal)) echo number_format($subtotal, 0, ",", "."); ?></td>
		</tr>
		<tr style="font-weight: bold; font-size: 13px; height: 20px;">
			<td style="text-align: right;" colspan="3">DESCUENTO <?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?></td>
			<td align="right" colspan="2"><?php if(isset($totalDescuento)) echo number_format($totalDescuento, 0, ",", "."); ?></td>
		</tr>
		<tr style="font-weight: bold; font-size: 13px; height: 20px;">
			<td style="text-align: right;" colspan="3">IVA <?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?></td>
			<td align="right" colspan="2"><?php if(isset($totalIva)) echo number_format($totalIva, 0, ",", "."); ?></td>
		</tr>
		<tr style="font-weight: bold; font-size: 13px; height: 20px;">
			<td style="text-align: right;" colspan="3">ENVÍO <?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?></td>
			<td align="right" colspan="2"><?php if(isset($resultado)) echo number_format(floatval($resultado['cotiz_envio']), 0, ",", "."); ?></td>
		</tr>
		<tr style="font-weight: bold; font-size: 13px; height: 20px;">
			<td style="text-align: right; background-color: #0033a0; color:white;" colspan="3">TOTAL NETO <?= $simbolosMonedas[$resultado['cotiz_moneda']]; ?></td>
			<td align="right" style="background-color: #0033a0; color:white;" colspan="2"><?= number_format($total, 0, ",", "."); ?></td>
		</tr>
	</tfoot>

</table>



</div>


</div>


	
		<div class="border border-dark rounded p-2 m-2">
			<img src="condicionesCoti.png" style="width: 100%;">
		</div>
	

							<p>&nbsp;</p>
							<div class="text-center" style="display:block;">
								<p><img src="../images/<?= $configuracion['conf_pie_cotizacion']; ?>" style="width: 100%;"></p>
							</div>

						</div>

					</body>

					<script type="application/javascript">
						print();
					</script>

					</html>




