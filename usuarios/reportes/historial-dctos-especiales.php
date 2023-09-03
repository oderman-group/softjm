<?php include("../sesion.php"); ?>
<?php include("../../conexion.php"); ?>
<?php $configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1")); ?>
<!DOCTYPE HTML>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>INFORMES - HISTORIAL DE DESCUENTOS ESPECIALES</title>
</head>

<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

	<h1 style="text-align:center;">INFORMES</h1>
	<h2 style="text-align:center;">HISTORIAL DE DESCUENTOS ESPECIALES</h2>
	<div align="center" style="margin-bottom:5px;"><img src="../files/<?= $configuracion['conf_logo']; ?>" height="100" alt="<?= $configuracion['conf_empresa']; ?>"></div>
	<table width="100%" border="1" rules="all" align="center">
		<thead>
			<tr style="height:30px; background-color: blue; color: white;">
				<th>No</th>
				<th>Cotización</th>
				<th>Tipo</th>
				<th>Item</th>
				<th>Dcto. Especial</th>
				<th>Fecha</th>
				<th>Responsable</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
									LEFT JOIN usuarios ON usr_id=czpp_aprobado_usuario
									LEFT JOIN productos ON prod_id=czpp_producto
									LEFT JOIN combos ON combo_id=czpp_combo
									WHERE czpp_descuento_especial>0
									ORDER BY czpp_cotizacion");

			while ($res = mysqli_fetch_array($consulta)) {

				$tipo = 'PRODUCTO';
				if($res['czpp_combo']!=""){
					$tipo = 'COMBO';
				}
			?>
				<tr>
					<td align="center"><?= $no; ?></td>
					<td align="center"><?= $res['czpp_cotizacion']; ?></td>
					<td align="center"><?= $tipo; ?></td>
					<td>
						<?= $res['prod_nombre']; ?> 
						<?= $res['combo_nombre']; ?>
						
					</td>
					<td align="center"><?= $res['czpp_descuento_especial']; ?>%</td>
					<td align="center"><?= $res['czpp_aprobado_fecha']; ?></td>
					<td><?= $res['usr_nombre']; ?></td>
				</tr>
			<?php $no++;
			} ?>
		</tbody>
	</table>

</body>

</html>