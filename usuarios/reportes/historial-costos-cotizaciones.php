<?php include("../sesion.php"); ?>
<?php include("../../conexion.php"); ?>
<?php $configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1")); ?>
<!DOCTYPE HTML>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>INFORMES - HISTORIAL DE COSTO Y UTILIDAD EN COTIZACIONES</title>
</head>

<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

	<h1 style="text-align:center;">INFORMES</h1>
	<h2 style="text-align:center;">HISTORIAL DE COSTO Y UTILIDAD EN COTIZACIONES</h2>
	<div align="center" style="margin-bottom:5px;"><img src="../files/<?= $configuracion['conf_logo']; ?>" height="100" alt="<?= $configuracion['conf_empresa']; ?>"></div>
	<table width="100%" border="1" rules="all" align="center">
		<thead>
			<tr style="height:30px; background-color: blue; color: white;">
				<th>No</th>
				<th>Cotización</th>
				<th>Item</th>
				<th>Costo</th>
				<th>Utilidad</th>
				<th>Costo Actual</th>
				<th>Utilidad Actual</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			$consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM cotizacion_productos 
									INNER JOIN productos ON prod_id=czpp_producto
									WHERE (czpp_producto!='' OR czpp_producto IS NOT NULL) AND (czpp_servicio='' OR czpp_servicio IS NULL)
									ORDER BY czpp_cotizacion DESC");

			while ($res = mysqli_fetch_array($consulta)) {

				$colorCosto = '';
				$colorUtilidad = '';
				if($res['czpp_costo'] != $res['prod_costo']){
					$colorCosto = 'gold';
				}
				if($res['czpp_utilidad_porcentaje'] != $res['prod_utilidad']){
					$colorUtilidad = 'gold';
				}

			?>
				<tr>
					<td align="center"><?= $no; ?></td>
					<td align="center"><?= $res['czpp_cotizacion']; ?></td>
					<td><?= $res['prod_id']." - ".$res['prod_nombre']; ?> </td>
					<td align="center" style="background-color: <?=$colorCosto;?>;">$<?php if(!empty($res['czpp_costo'])){ echo number_format($res['czpp_costo'],0,".",".");} ?></td>
					<td align="center" style="background-color: <?=$colorUtilidad;?>;"><?= $res['czpp_utilidad_porcentaje']; ?>%</td>
					<td align="center">$<?php if(!empty($res['prod_costo'])){ echo number_format($res['prod_costo'],0,".",".");}?></td>
					<td align="center"><?= $res['prod_utilidad']; ?>%</td>
				</tr>
			<?php $no++;
			} ?>
		</tbody>
	</table>

</body>

</html>