<?php include("../sesion.php"); ?>
<?php include("../../conexion.php"); ?>
<?php $configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1")); ?>
<!DOCTYPE HTML>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>INFORMES - COMBOS</title>
</head>

<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

	<h1 style="text-align:center;">INFORMES</h1>
	<h2 style="text-align:center;">COMBOS</h2>
	<div align="center" style="margin-bottom:5px;"><img src="../files/<?= $configuracion['conf_logo']; ?>" height="100" alt="<?= $configuracion['conf_empresa']; ?>"></div>

	<p><a href="combos-exportar.php" target="_blank">[EXPORTAR EXCEL]</a></p>

	<table width="100%" border="1" rules="all" align="center">
		<thead>
			<tr style="height:30px; background-color: blue; color: white;">
				<th>NO.</th>
				<th>COMBO</th>
				<th>PRECIO</th>
				<th>DCTO.</th>
				<th>PRECIO FINAL</th>
				<th>ESTADO</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM combos");

			while ($res = mysqli_fetch_array($consulta)) {
				$datosCombos = mysqli_query($conexionBdPrincipal,"SELECT ROUND((SUM(copp_cantidad)*prod_precio),0) FROM combos
								INNER JOIN combos_productos ON copp_combo=combo_id
								INNER JOIN productos ON prod_id=copp_producto
								WHERE combo_id='" . $res['combo_id'] . "'
								GROUP BY copp_producto");

				$precioCombo = 0;
				while ($dCombos = mysqli_fetch_array($datosCombos)) {
					$precioCombo += $dCombos[0];
				}

				$dcto = 0;
				if(!empty($res['combo_descuento'])){
					$dcto = $res['combo_descuento'] / 100;
				}
				$precioFinal = round($precioCombo - ($precioCombo * $dcto), 0);
			?>
				<tr style="height: 30px; font-size: 15px; background-color: yellow;">
					<td align="center"><?= $no; ?></td>
					<td><?= $res['combo_nombre']; ?></td>
					<td>$<?= number_format($precioCombo, 0, ".", "."); ?></td>
					<td align="center"><?= $res['combo_descuento']; ?>%</td>
					<td>$<?= number_format($precioFinal, 0, ".", "."); ?></td>
					<td><?= $estadoRegistros[$res['combo_estado']]; ?></td>
				</tr>

				<tr style="height: 15px;">
					<th>NO.</th>
					<th>PRODUCTO</th>
					<th>CANT.</th>
					<th>PRECIO LISTA</th>
					<th colspan="2">SUBTOTAL</th>
				</tr>

				<?php
				$nop = 1;
				$productos = mysqli_query($conexionBdPrincipal,"SELECT * FROM productos 
				INNER JOIN productos_categorias ON catp_id=prod_categoria
				INNER JOIN combos_productos ON copp_producto=prod_id AND copp_combo='".$res['combo_id']."'
				ORDER BY copp_id");
				while($prod = mysqli_fetch_array($productos)){
						
					$subtotal = ($prod['prod_precio'] * $prod['copp_cantidad']);
					$total +=$subtotal;
					
					$totalCantidad += $prod['copp_cantidad'];
				?>

<tr>
								<td align="center"><?=$nop;?></td>
                                <td>
									<?=$prod['prod_nombre'];?>
								</td>
                                <td align="center"><?=$prod['copp_cantidad'];?></td>
                                <td>$<?=number_format($prod['prod_precio'],0,",",".");?></td>
								<td colspan="2">$<?=number_format($subtotal,0,",",".");?></td>
							</tr>


			<?php $nop++;}?>


			<?php $no++;
			} ?>
		</tbody>
	</table>

</body>

</html>